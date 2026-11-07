#!/usr/bin/env python3
"""Install BIATECH branding without changing route, database or layout structure."""
from __future__ import annotations

import argparse
import hashlib
import json
import os
from pathlib import Path
import re
import shutil
import stat
import sys
from datetime import datetime

PACKAGE = Path(__file__).resolve().parent
BLADE = Path('src/resources/views/layouts/biatech.blade.php')
PANEL = Path('src/app/Providers/Filament/AdminPanelProvider.php')
PUBLIC = Path('src/public/biatech')
BRAND_ASSETS = {
    PUBLIC / 'css/brand-theme.css': 'brand-theme.css',
    PUBLIC / 'images/biatech-logo.png': 'biatech-logo.png',
    PUBLIC / 'images/biatech-favicon.png': 'biatech-favicon.png',
    PUBLIC / 'images/biatech-admin-logo-light.png': 'biatech-admin-logo-light.png',
    PUBLIC / 'images/biatech-admin-logo-dark.png': 'biatech-admin-logo-dark.png',
}
CSS_REFERENCE = "{{ asset('biatech/css/storefront.css') }}"
THEME_REFERENCE = "{{ asset('biatech/css/brand-theme.css') }}"
ICON_REFERENCE = "{{ asset('biatech/images/biatech-favicon.png') }}"
LOGO_REFERENCE = "{{ asset('biatech/images/biatech-logo.png') }}"
MARK_PATTERN = re.compile(
    r'<span class="brand__mark">\s*<x-biatech\.icon\s+name="zap"\s+:size="(?:26|27)"\s*/>\s*</span>'
)


def sha256(data: bytes) -> str:
    return hashlib.sha256(data).hexdigest()


def edit_blade(data: bytes) -> bytes:
    text = data.decode('utf-8-sig')
    if CSS_REFERENCE not in text:
        raise ValueError('Layout tidak memiliki link storefront.css yang dikenali. Tidak akan mengubah file.')
    if '<!DOCTYPE html>' not in text and '<!doctype html>' not in text:
        raise ValueError('Layout tidak dikenali sebagai dokumen HTML BIATECH.')

    if THEME_REFERENCE not in text:
        link = f'    <link rel="stylesheet" href="{THEME_REFERENCE}">'
        needle = f'<link rel="stylesheet" href="{CSS_REFERENCE}">'
        text = text.replace(needle, needle + '\n' + link, 1)
    if ICON_REFERENCE not in text:
        needle = f'<link rel="stylesheet" href="{CSS_REFERENCE}">'
        favicon = f'    <link rel="icon" type="image/png" href="{ICON_REFERENCE}">'
        text = text.replace(needle, needle + '\n' + favicon, 1)

    text = re.sub(
        r'(<meta\s+name="theme-color"\s+content=")[^"]+("\s*/?>)',
        r'\g<1>#0B102F\2',
        text,
        count=1,
    )
    count = len(MARK_PATTERN.findall(text))
    if count == 2:
        replacement = (
            '<span class="brand__mark">'
            f'<img src="{LOGO_REFERENCE}" alt="" width="44" height="44">'
            '</span>'
        )
        text = MARK_PATTERN.sub(replacement, text)
    elif count == 0 and text.count(LOGO_REFERENCE) == 2:
        pass  # Re-run on already branded file.
    else:
        raise ValueError(
            f'Temuan logo lama={count}. Seharusnya 2 (header dan footer). '
            'Markup berbeda dari referensi; hentikan agar tidak merusak layout.'
        )
    if text.count(THEME_REFERENCE) != 1 or text.count(ICON_REFERENCE) != 1:
        raise ValueError('Link theme/favicon terduplikasi, perubahan dihentikan.')
    return text.encode('utf-8')


def edit_admin(data: bytes) -> bytes | None:
    text = data.decode('utf-8-sig')
    marker = "->font('Montserrat')"
    if '->brandLogo(' in text:
        print('[SKIP ADMIN] Panel sudah memakai brandLogo; perubahan manual perlu direview.')
        return None
    if text.count(marker) != 1:
        print('[SKIP ADMIN] Tidak menemukan ->font(\'Montserrat\') tepat sekali.')
        return None
    addition = (
        '\n            ->brandLogo(asset(\'biatech/images/biatech-admin-logo-light.png\'))'
        '\n            ->darkModeBrandLogo(asset(\'biatech/images/biatech-admin-logo-dark.png\'))'
        "\n            ->brandLogoHeight('2.5rem')"
        "\n            ->favicon(asset('biatech/images/biatech-favicon.png'))"
    )
    text = text.replace(marker, marker + addition, 1)
    # Filament 3 accepts a hex string and generates the corresponding RGB palette.
    text = re.sub(
        r"('primary'\s*=>\s*)Color::Blue\b",
        r"\g<1>'#3B82F6'",
        text,
        count=1,
    )
    return text.encode('utf-8')


def prepare(root: Path) -> dict[Path, bytes]:
    app_root = root / 'src'
    if not app_root.is_dir():
        raise ValueError(f'Folder src tidak ditemukan: {app_root}')
    if not (app_root / 'public/biatech/css/storefront.css').is_file():
        raise ValueError('src/public/biatech/css/storefront.css tidak ditemukan.')
    layout = root / BLADE
    if not layout.is_file():
        raise ValueError(f'Layout tidak ditemukan: {layout}')

    result: dict[Path, bytes] = {BLADE: edit_blade(layout.read_bytes())}
    for relative, asset_name in BRAND_ASSETS.items():
        path = PACKAGE / 'assets' / asset_name
        if not path.is_file():
            raise ValueError(f'Aset patch hilang: {path.name}')
        data = path.read_bytes()
        deployed = root / relative
        if deployed.exists() and deployed.read_bytes() != data:
            raise ValueError(
                f'Aset sudah ada dengan isi berbeda: {relative}. '
                'Rename/backup dulu; installer tidak akan menimpa aset asing.'
            )
        result[relative] = data

    panel = root / PANEL
    if panel.is_file():
        changed = edit_admin(panel.read_bytes())
        if changed is not None:
            result[PANEL] = changed
    else:
        print('[SKIP ADMIN] AdminPanelProvider.php tidak ditemukan.')
    return result


def atomic_write(path: Path, data: bytes, old_mode: int | None) -> None:
    path.parent.mkdir(parents=True, exist_ok=True)
    temp = path.with_name(path.name + '.biatech-tmp')
    try:
        temp.write_bytes(data)
        if old_mode is not None:
            os.chmod(temp, old_mode)
        os.replace(temp, path)
    finally:
        if temp.exists():
            temp.unlink()


def install(root: Path, changes: dict[Path, bytes]) -> None:
    actionable = [p for p, new in changes.items() if not (root / p).exists() or (root / p).read_bytes() != new]
    if not actionable:
        print('[PASS] Semua branding sudah terpasang. Tidak ada file berubah.')
        return

    stamp = datetime.now().strftime('%Y%m%d_%H%M%S')
    backup = root.parent / f'{root.name}-branding-backup-{stamp}-{os.getpid()}'
    backup.mkdir(parents=True, exist_ok=False)
    manifest = {'root': str(root), 'entries': []}
    for p in actionable:
        dest = root / p
        original = dest.read_bytes() if dest.is_file() else None
        old_mode = stat.S_IMODE(dest.stat().st_mode) if dest.is_file() else None
        if original is not None:
            stored = backup / p
            stored.parent.mkdir(parents=True, exist_ok=True)
            shutil.copy2(dest, stored)
        manifest['entries'].append({
            'relative': str(p),
            'existed_before': original is not None,
            'applied_sha256': sha256(changes[p]),
            'original_mode': old_mode,
        })

    manifest_path = backup / 'manifest.json'
    manifest_path.write_text(json.dumps(manifest, indent=2) + '\n', encoding='utf-8')
    for p in actionable:
        original_file = root / p
        old_mode = stat.S_IMODE(original_file.stat().st_mode) if original_file.is_file() else None
        atomic_write(original_file, changes[p], old_mode)
        print(f'[UPDATED] {p}')
    print(f'\nBackup aman: {backup}')
    print('Rollback (jika perlu):')
    print(f'python3 {PACKAGE / "install.py"} --root {root} --rollback {backup}')


def rollback(root: Path, backup: Path) -> None:
    manifest_file = backup / 'manifest.json'
    if not manifest_file.is_file():
        raise ValueError('Manifest rollback tidak ditemukan.')
    manifest = json.loads(manifest_file.read_text(encoding='utf-8'))
    if Path(manifest['root']).resolve() != root:
        raise ValueError('Backup berasal dari proyek lain. Rollback dibatalkan.')
    for item in manifest['entries']:
        target = root / item['relative']
        if not target.is_file() or sha256(target.read_bytes()) != item['applied_sha256']:
            raise ValueError(
                f'{item["relative"]} telah berubah sejak install. '
                'Rollback dibatalkan untuk melindungi perubahan baru.'
            )
    for item in manifest['entries']:
        target = root / item['relative']
        if item['existed_before']:
            original = (backup / item['relative']).read_bytes()
            atomic_write(target, original, item['original_mode'])
        else:
            target.unlink()
        print(f'[RESTORED] {item["relative"]}')
    print('Rollback selesai. Tidak menyentuh data MariaDB atau routing.')


def main() -> int:
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument('--root', required=True, help='Project root yang memuat folder src/')
    group = parser.add_mutually_exclusive_group(required=True)
    group.add_argument('--check', action='store_true', help='Preview tanpa menulis')
    group.add_argument('--apply', action='store_true', help='Install dengan backup')
    group.add_argument('--rollback', type=Path, help='Lokasi backup dari instalasi sebelumnya')
    args = parser.parse_args()
    root = Path(args.root).expanduser().resolve()
    try:
        if args.rollback is not None:
            rollback(root, args.rollback.expanduser().resolve())
            return 0
        changes = prepare(root)
        print(f'Project: {root}')
        for relative, new in changes.items():
            target = root / relative
            state = 'UNCHANGED' if target.is_file() and target.read_bytes() == new else ('UPDATE' if target.is_file() else 'CREATE')
            print(f'[{state}] {relative}')
        if args.apply:
            install(root, changes)
        else:
            print('\nCHECK OK. Tidak ada file yang diubah.')
        return 0
    except (ValueError, OSError, UnicodeError) as error:
        print(f'\nSTOP: {error}', file=sys.stderr)
        return 1


if __name__ == '__main__':
    sys.exit(main())
