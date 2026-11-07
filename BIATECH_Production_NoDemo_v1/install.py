#!/usr/bin/env python3
"""BIATECH storefront production cleanup, with strict preflight and rollback.

Usage:
    python3 install.py --root /root/perkuliahan/pulsa --check
    python3 install.py --root /root/perkuliahan/pulsa --apply
    python3 install.py --root /root/perkuliahan/pulsa --rollback /path/to/backup

This tool only operates on files listed in manifest.json. It never edits .env,
Laravel web.php routing, Filament Resources, Docker, migrations, or database rows.
"""

from __future__ import annotations

import argparse
import hashlib
import json
import re
import shutil
import sys
from datetime import datetime
from pathlib import Path

HERE = Path(__file__).resolve().parent
MANIFEST = json.loads((HERE / "manifest.json").read_text(encoding="utf-8"))
DANGEROUS_REFS = re.compile(
    r"\bbiatech_demo_orders\b|\bcatalog_source\b|\bBIATECH_CATALOG_SOURCE\b|\bPaymentSimulator\b|"
    r"\bbiatech\.payment-simulator\b|\bCheckoutForm\b|"
    r"\bbiatech\.checkout-form\b|\bTrackOrder\b|"
    r"\bbiatech\.track-order\b|\bisDemo\s*\(",
    flags=re.IGNORECASE,
)


def checksum(data: bytes) -> str:
    return hashlib.sha256(data).hexdigest()


def safe_target(root: Path, relative: str) -> Path:
    base = (root / "src").resolve()
    target = (base / relative).resolve()
    if not target.is_relative_to(base):
        raise ValueError(f"Invalid target path: {relative}")
    return target


def validate_sources(root: Path) -> list[str]:
    errors: list[str] = []
    for name, info in MANIFEST["replace"].items():
        target = safe_target(root, name)
        if not target.is_file():
            errors.append(f"MISSING: src/{name}")
            continue
        actual = checksum(target.read_bytes())
        if actual not in info["sha256_allowed"]:
            if actual == info["sha256_new"]:
                errors.append(f"ALREADY UPDATED: src/{name}")
            else:
                errors.append(f"CHANGED FROM REFERENCE: src/{name}")

    for name, allowed in MANIFEST["delete"].items():
        target = safe_target(root, name)
        if not target.is_file():
            errors.append(f"MISSING: src/{name}")
        elif checksum(target.read_bytes()) not in allowed:
            errors.append(f"CHANGED FROM REFERENCE: src/{name}")

    # Reject removing components still referenced from other source code.
    ignored = set(MANIFEST["replace"]) | set(MANIFEST["delete"])
    base = root / "src"
    for folder in ("app", "resources/views", "routes", "config"):
        current = base / folder
        if not current.exists():
            continue
        for file in current.rglob("*"):
            if not file.is_file() or file.suffix not in (".php", ".blade.php"):
                continue
            name = file.relative_to(base).as_posix()
            if name in ignored:
                continue
            try:
                raw = file.read_text(encoding="utf-8")
            except UnicodeDecodeError:
                continue
            if DANGEROUS_REFS.search(raw):
                errors.append(f"EXTERNAL DEMO REFERENCE: src/{name}")
    return errors


def backup(root: Path) -> Path:
    base = root.parent / "biatech-backups"
    stamp = datetime.now().strftime("%Y%m%d-%H%M%S-%f")
    directory = base / f"production-cleanup-{stamp}"
    directory.mkdir(parents=True, exist_ok=False)

    names = list(MANIFEST["replace"]) + list(MANIFEST["delete"])
    for name in names:
        current = safe_target(root, name)
        archived = directory / "files" / name
        archived.parent.mkdir(parents=True, exist_ok=True)
        shutil.copy2(current, archived)

    (directory / "manifest.json").write_text(
        json.dumps(MANIFEST, indent=2, ensure_ascii=False), encoding="utf-8"
    )
    (directory / "root.txt").write_text(str(root.resolve()), encoding="utf-8")
    return directory


def apply(root: Path) -> None:
    errors = validate_sources(root)
    if errors:
        print("STOP: Sumber tidak cocok. Tidak ada file yang diubah:")
        for error in errors:
            print(" -", error)
        sys.exit(2)

    directory = backup(root)
    print("Backup:", directory)
    try:
        for name in MANIFEST["replace"]:
            target = safe_target(root, name)
            source = HERE / "patches" / name
            if checksum(source.read_bytes()) != MANIFEST["replace"][name]["sha256_new"]:
                raise ValueError(f"Patch checksum mismatch: {name}")
            shutil.copy2(source, target)
            print("UPDATED: src/" + name)

        for name in MANIFEST["delete"]:
            safe_target(root, name).unlink()
            print("REMOVED: src/" + name)
    except Exception:
        print("Pemasangan tidak selesai. Memulihkan semua file dari backup...")
        for name in list(MANIFEST["replace"]) + list(MANIFEST["delete"]):
            previous = directory / "files" / name
            target = safe_target(root, name)
            target.parent.mkdir(parents=True, exist_ok=True)
            shutil.copy2(previous, target)
        raise

    print("DONE. Jalankan php -l, optimize:clear, dan tes HTTP.")
    print("ROLLBACK:", f"python3 install.py --root {root} --rollback {directory}")


def rollback(root: Path, directory: Path) -> None:
    manifest = json.loads((directory / "manifest.json").read_text(encoding="utf-8"))
    prior_root = (directory / "root.txt").read_text(encoding="utf-8")
    if Path(prior_root).resolve() != root.resolve():
        raise ValueError("Backup berasal dari root proyek berbeda.")
    if manifest != MANIFEST:
        raise ValueError("Backup manifest tidak cocok dengan paket installer.")

    names = list(manifest["replace"]) + list(manifest["delete"])
    for name in names:
        original = directory / "files" / name
        if not original.is_file():
            raise ValueError(f"Backup tidak lengkap: {name}")
        target = safe_target(root, name)
        if name in manifest["replace"]:
            expected = manifest["replace"][name]["sha256_new"]
            if not target.is_file() or checksum(target.read_bytes()) != expected:
                raise ValueError(f"STOP: file berubah setelah patch: {name}")
        elif target.exists():
            raise ValueError(f"STOP: file yang dihapus telah dibuat kembali: {name}")

    for name in names:
        target = safe_target(root, name)
        target.parent.mkdir(parents=True, exist_ok=True)
        shutil.copy2(directory / "files" / name, target)
        print("RESTORED: src/" + name)
    print("Rollback sukses. Jalankan optimize:clear.")


def main() -> None:
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument("--root", type=Path, default=Path("/root/perkuliahan/pulsa"))
    options = parser.add_mutually_exclusive_group(required=True)
    options.add_argument("--check", action="store_true")
    options.add_argument("--apply", action="store_true")
    options.add_argument("--rollback", type=Path, metavar="BACKUP_DIR")
    args = parser.parse_args()
    root = args.root.resolve()
    if not (root / "src" / "app").is_dir():
        parser.error(f"Laravel src/app not found in {root}")

    if args.check:
        errors = validate_sources(root)
        print(f"Planned: {len(MANIFEST['replace'])} updated, {len(MANIFEST['delete'])} removed")
        if errors:
            print("CHECK FAILED:")
            for item in errors:
                print(" -", item)
            sys.exit(2)
        print("CHECK OK: source matches known storefront version. No files changed.")
    elif args.apply:
        apply(root)
    else:
        rollback(root, args.rollback.resolve())


if __name__ == "__main__":
    main()
