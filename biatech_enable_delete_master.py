#!/usr/bin/env python3
"""Enable row-level Delete in existing BIATECH Filament v3 master Resources.

Safe by default: dry-run unless --apply is provided.
No model, policy, panel, routes, migration, or transactional Resources are modified.
"""

from __future__ import annotations

import argparse
from datetime import datetime
from pathlib import Path
import re
import shutil
import sys

RESOURCES = (
    "CategoryResource",
    "ProviderResource",
    "ProductResource",
    "SupplierResource",
    "SupplierProductResource",
    "SettingResource",
)


def update_resource(source: str, name: str) -> str:
    match = re.search(r"\bpublic\s+static\s+function\s+table\s*\(", source)
    if match is None:
        raise ValueError(f"{name}: method table() tidak ditemukan")

    # Only inspect from table() to the next public static method.
    following = re.search(r"\n\s*public\s+static\s+function\s+\w+\s*\(", source[match.end():])
    end = match.end() + following.start() if following else source.rfind("}")
    head, body, tail = source[: match.start()], source[match.start():end], source[end:]

    if "->actions([" not in body:
        raise ValueError(f"{name}: ->actions([ ... ]) tidak ditemukan pada table()")
    if re.search(r"\bDeleteAction::make\(\)", body):
        return source

    # All master Resources in the BIATECH archive have an EditAction row.
    action = re.search(
        r"(?m)^(?P<indent>[ \t]*)(?P<edit>[^\n]*\bEditAction::make\(\),)[ \t]*$",
        body,
    )
    if action is None:
        raise ValueError(f"{name}: EditAction tidak ditemukan; tidak mengubah file")

    inserted = (
        action.group(0)
        + "\n"
        + action.group("indent")
        + "\\Filament\\Tables\\Actions\\DeleteAction::make(),"
    )
    updated_body = body[: action.start()] + inserted + body[action.end():]
    return head + updated_body + tail


def main() -> int:
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument("--apply", action="store_true", help="Terapkan setelah dry-run")
    parser.add_argument("--project", default=".", help="Root project docker-compose")
    args = parser.parse_args()

    root = Path(args.project).resolve()
    path = root / "src/app/Filament/Admin/Resources"
    if not (root / "src/artisan").is_file() or not path.is_dir():
        print("ERROR: jalankan dari /root/perkuliahan/pulsa (root Docker Compose)")
        return 2

    updates: dict[Path, str] = {}
    failures = []
    for name in RESOURCES:
        file = path / f"{name}.php"
        if not file.is_file():
            failures.append(f"{name}: file tidak ditemukan")
            continue
        before = file.read_text(encoding="utf-8")
        if f"class {name} extends Resource" not in before:
            failures.append(f"{name}: class tidak sesuai")
            continue
        if "namespace App\\Filament\\Admin\\Resources;" not in before:
            failures.append(f"{name}: namespace tidak sesuai")
            continue
        try:
            after = update_resource(before, name)
        except ValueError as exc:
            failures.append(str(exc))
            continue
        if after != before:
            updates[file] = after
            print("AKAN UPDATE:", file.relative_to(root))
        else:
            print("SUDAH ADA DELETE:", file.relative_to(root))

    if failures:
        for error in failures:
            print("ERROR:", error)
        print("Tidak ada file yang diubah.")
        return 2

    if not updates:
        print("Semua master Resource sudah memiliki DeleteAction.")
        return 0

    if not args.apply:
        print("\nDRY RUN SELESAI. Jalankan ulang dengan --apply untuk menerapkan.")
        return 0

    backup = root / "backups" / "biatech-delete-master" / datetime.now().strftime("%Y%m%d-%H%M%S")
    backup.mkdir(parents=True, exist_ok=False)

    for file in updates:
        shutil.copy2(file, backup / file.name)

    for file, new_code in updates.items():
        temp = file.with_name(file.name + ".new")
        temp.write_text(new_code, encoding="utf-8")
        temp.chmod(file.stat().st_mode)
        temp.replace(file)

    print("\nBERHASIL. Backup:", backup)
    print("Tidak mengubah Filament Panel, transaksi, policies, database, atau landing page.")
    print("Berikutnya: php -l, optimize:clear, cek permission delete Shield.")
    return 0


if __name__ == "__main__":
    sys.exit(main())
