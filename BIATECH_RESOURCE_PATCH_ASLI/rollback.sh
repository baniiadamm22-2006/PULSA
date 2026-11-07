#!/usr/bin/env bash
set -euo pipefail
PACKAGE_DIR="$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_DIR="$(dirname -- "$PACKAGE_DIR")"
TARGET="$PROJECT_DIR/src"
BACKUP="${1:-}"
if [[ -z "$BACKUP" || ! -d "$BACKUP" || "$BACKUP" != "$PROJECT_DIR"/backups/biatech-resource-* ]]; then
    echo "Masukkan path BACKUP yang dicetak installer." >&2
    exit 2
fi
if [[ ! -f "$BACKUP/FILES.txt" ]]; then
    echo "Backup tidak berisi FILES.txt" >&2; exit 1
fi
while IFS= read -r relative; do
    [[ -n "$relative" ]] || continue
    if [[ "$relative" != app/Filament/Admin/Resources/* || "$relative" == *..* ]]; then
        echo "Path invalid: $relative" >&2; exit 1
    fi
    if [[ ! -f "$BACKUP/$relative" || ! -f "$TARGET/$relative" ]]; then
        echo "File backup/target tidak ada: $relative" >&2; exit 1
    fi
done < "$BACKUP/FILES.txt"
while IFS= read -r relative; do
    [[ -n "$relative" ]] || continue
    cp "$BACKUP/$relative" "$TARGET/$relative"
done < "$BACKUP/FILES.txt"
echo "File Resources dipulihkan dari: $BACKUP"
echo "Jalankan: docker compose exec -T -u www-data php php artisan optimize:clear"
