#!/usr/bin/env python3
"""Fix active navbar categories in BIATECH without replacing unrelated Blade code."""

from __future__ import annotations

import argparse
import shutil
from datetime import datetime
from pathlib import Path


NAV_LINKS = [
    (
        "{{ route('biatech.catalog') }}",
        "Semua Produk",
        "request()->routeIs('biatech.catalog') && blank(request()->query('category'))",
    ),
    (
        "{{ route('biatech.catalog', ['category' => 'pulsa-reguler']) }}",
        "Pulsa",
        "request()->routeIs('biatech.catalog') && request()->query('category') === 'pulsa-reguler'",
    ),
    (
        "{{ route('biatech.catalog', ['category' => 'paket-data-internet']) }}",
        "Paket Data",
        "request()->routeIs('biatech.catalog') && request()->query('category') === 'paket-data-internet'",
    ),
    (
        "{{ route('biatech.catalog', ['category' => 'roaming-internasional']) }}",
        "Roaming Internasional",
        "request()->routeIs('biatech.catalog') && request()->query('category') === 'roaming-internasional'",
    ),
    (
        "{{ route('biatech.catalog', ['category' => 'roaming-umroh']) }}",
        "Paket Umroh",
        "request()->routeIs('biatech.catalog') && request()->query('category') === 'roaming-umroh'",
    ),
    (
        "{{ route('biatech.catalog', ['category' => 'token-listrik-pln']) }}",
        "Token PLN",
        "request()->routeIs('biatech.catalog') && request()->query('category') === 'token-listrik-pln'",
    ),
]


def main() -> None:
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument("--root", type=Path, default=Path.cwd())
    args = parser.parse_args()

    target = args.root / "src/resources/views/layouts/biatech.blade.php"
    if not target.is_file():
        parser.error(f"File tidak ditemukan: {target}")

    original = target.read_text(encoding="utf-8")
    nav_marker = '        <details class="mobile-nav">'
    if original.count(nav_marker) != 1:
        raise SystemExit("STOP: Batas desktop/mobile navbar tidak ditemukan.")

    desktop, marker, mobile_and_rest = original.partition(nav_marker)
    updated = desktop
    changes = 0

    for href, label, condition in NAV_LINKS:
        desired = (
            '<a href="' + href + '" class="{{ ' + condition
            + " ? 'is-active' : '' }}\">" + label + '</a>'
        )
        plain = '<a href="' + href + '">' + label + '</a>'
        old_all = (
            '<a href="' + href
            + "\" class=\"{{ request()->routeIs('biatech.catalog') ? 'is-active' : '' }}\">"
            + label + '</a>'
        )

        if desired in updated:
            continue

        expected = old_all if label == "Semua Produk" else plain
        if updated.count(expected) != 1:
            raise SystemExit(
                f"STOP: Bentuk link '{label}' berbeda dari referensi. "
                "Tidak ada file yang ditimpa."
            )

        updated = updated.replace(expected, desired, 1)
        changes += 1

    if changes == 0:
        print("OK: Navbar sudah benar; tidak ada yang diubah.")
        return

    updated = updated + marker + mobile_and_rest

    stamp = datetime.now().strftime("%Y%m%d_%H%M%S")
    backup = target.with_name(f"{target.name}.backup-{stamp}")
    shutil.copy2(target, backup)
    target.write_text(updated, encoding="utf-8")

    print(f"SUKSES: {changes} item navbar diperbaiki.")
    print(f"File: {target}")
    print(f"Backup: {backup}")
    print("Lanjut: docker compose exec -T php php artisan view:clear")


if __name__ == "__main__":
    main()
