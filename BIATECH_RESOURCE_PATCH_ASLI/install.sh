#!/usr/bin/env bash
set -euo pipefail

# BIATECH: update existing Filament v3 resource files only.
# Run from /root/perkuliahan/pulsa/BIATECH_RESOURCE_PATCH_ASLI
PACKAGE_DIR="$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_DIR="$(dirname -- "$PACKAGE_DIR")"
TARGET="$PROJECT_DIR/src"
LIST="$PACKAGE_DIR/FILES.txt"

if [[ ! -f "$TARGET/app/Filament/Admin/Resources/CategoryResource.php" || ! -f "$PROJECT_DIR/docker-compose.yml" && ! -f "$PROJECT_DIR/compose.yaml" && ! -f "$PROJECT_DIR/compose.yml" ]]; then
    echo "ERROR: Struktur project tidak sesuai. Ekstrak paket di root folder pulsa/ (sejajar src/)." >&2
    exit 1
fi

mode="${1:---check}"
case "$mode" in
    --check|--apply) ;;
    *) echo "Cara pakai: bash BIATECH_RESOURCE_PATCH_ASLI/install.sh [--check|--apply]" >&2; exit 2;;
esac

count=0
while IFS= read -r relative; do
    [[ -n "$relative" ]] || continue
    if [[ "$relative" != app/Filament/Admin/Resources/* || "$relative" == *..* ]]; then
        echo "ERROR: path patch tidak aman: $relative" >&2; exit 1
    fi
    if [[ ! -f "$TARGET/$relative" || ! -f "$PACKAGE_DIR/$relative" ]]; then
        echo "ERROR: file asli tidak ditemukan: src/$relative" >&2
        exit 1
    fi
    ((count+=1))
done < "$LIST"

echo "Terverifikasi $count file yang SUDAH ADA (tidak ada Resource/folder baru)."
if [[ "$mode" == "--check" ]]; then
    echo "Berikut file yang isinya akan berubah:"
    while IFS= read -r relative; do
        [[ -n "$relative" ]] || continue
        if ! cmp -s "$TARGET/$relative" "$PACKAGE_DIR/$relative"; then
            echo "  src/$relative"
        fi
    done < "$LIST"
    echo "Tidak ada file yang diubah. Untuk terapkan: bash BIATECH_RESOURCE_PATCH_ASLI/install.sh --apply"
    exit 0
fi

BACKUP="$PROJECT_DIR/backups/biatech-resource-$(date +%Y%m%d_%H%M%S)_$$"
mkdir -p "$BACKUP"
while IFS= read -r relative; do
    [[ -n "$relative" ]] || continue
    mkdir -p "$BACKUP/$(dirname "$relative")"
    cp -p "$TARGET/$relative" "$BACKUP/$relative"
done < "$LIST"
cp "$LIST" "$BACKUP/FILES.txt"

while IFS= read -r relative; do
    [[ -n "$relative" ]] || continue
    cp "$PACKAGE_DIR/$relative" "$TARGET/$relative"
done < "$LIST"

echo "PATCH TERPASANG pada $count file asli."
echo "BACKUP: $BACKUP"
echo "Untuk rollback: bash '$PACKAGE_DIR/rollback.sh' '$BACKUP'"
echo "Lanjutkan: docker compose exec -T -u www-data php php artisan optimize:clear"
