#!/usr/bin/env bash
set -euo pipefail

mode="${1:---check}"
if [[ "$mode" != '--check' && "$mode" != '--apply' ]]; then
    echo 'Usage: bash BIATECH_Admin_Dashboard_Widgets_v1/install.sh --check|--apply' >&2
    exit 2
fi

script_dir="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
project_root="$(cd "$script_dir/.." && pwd)"
target_dir="$project_root/src/app/Filament/Admin/Widgets"

if [[ ! -f "$project_root/src/app/Providers/Filament/AdminPanelProvider.php" ]]; then
    echo "ERROR: AdminPanelProvider.php tidak ditemukan di $project_root" >&2
    exit 1
fi

if ! grep -q 'discoverWidgets' "$project_root/src/app/Providers/Filament/AdminPanelProvider.php"; then
    echo 'ERROR: Panel belum mengaktifkan widget discovery; tidak memasang secara otomatis.' >&2
    exit 1
fi

files=(BiatechOperationalStats.php BiatechIntegrationStatus.php)
for file in "${files[@]}"; do
    source_file="$script_dir/app/Filament/Admin/Widgets/$file"
    target_file="$target_dir/$file"

    if [[ ! -f "$source_file" ]]; then
        echo "ERROR: Paket tidak lengkap: $source_file" >&2
        exit 1
    fi

    if [[ -e "$target_file" ]]; then
        echo "ERROR: File sudah ada, tidak ditimpa: $target_file" >&2
        exit 1
    fi

done

if [[ "$mode" == '--check' ]]; then
    echo '[CHECK OK] 2 widget siap dipasang. Tidak ada file yang diubah.'
    exit 0
fi

mkdir -p "$target_dir"
for file in "${files[@]}"; do
    cp -n "$script_dir/app/Filament/Admin/Widgets/$file" "$target_dir/$file"
done

echo '[APPLY OK] 2 widget terpasang. AdminPanelProvider.php tidak diubah.'
