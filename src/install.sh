#!/usr/bin/env bash
# BIATECH Frontend Installer — Laravel 12 / Livewire 3
# Dry-run by default; --apply copies with backups and appends one routes require.
set -euo pipefail

SOURCE_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_DIR="${1:-./src}"
MODE="${2:---dry-run}"

if [[ "$PROJECT_DIR" == "--apply" || "$PROJECT_DIR" == "--dry-run" ]]; then
    MODE="$PROJECT_DIR"
    PROJECT_DIR="./src"
fi
if [[ "$MODE" != "--apply" && "$MODE" != "--dry-run" ]]; then
    echo "Usage: bash install.sh /path/to/laravel [--dry-run|--apply]" >&2
    exit 2
fi
if [[ ! -f "$PROJECT_DIR/artisan" || ! -f "$PROJECT_DIR/routes/web.php" ]]; then
    echo "ERROR: $PROJECT_DIR bukan root aplikasi Laravel (artisan/routes/web.php tidak ditemukan)." >&2
    exit 1
fi
PROJECT_DIR="$(cd "$PROJECT_DIR" && pwd)"
BACKUP_DIR="$PROJECT_DIR/.biatech-frontend-backups/$(date +%Y%m%d_%H%M%S)_$$"

mapfile -d '' FILES < <(cd "$SOURCE_DIR" && find app config public resources routes tests -type f -print0 | sort -z)

printf '\nBIATECH Frontend — %s\n' "$MODE"
printf 'Source: %s\nTarget: %s\nFiles: %s\n\n' "$SOURCE_DIR" "$PROJECT_DIR" "${#FILES[@]}"

for RELATIVE in "${FILES[@]}"; do
    [[ "$RELATIVE" == *".DS_Store" ]] && continue
    if [[ -e "$PROJECT_DIR/$RELATIVE" ]]; then
        echo "REPLACE (backup): $RELATIVE"
    else
        echo "ADD: $RELATIVE"
    fi
    if [[ "$MODE" == "--apply" ]]; then
        mkdir -p "$(dirname "$PROJECT_DIR/$RELATIVE")"
        if [[ -e "$PROJECT_DIR/$RELATIVE" ]]; then
            mkdir -p "$(dirname "$BACKUP_DIR/$RELATIVE")"
            cp -p "$PROJECT_DIR/$RELATIVE" "$BACKUP_DIR/$RELATIVE"
        fi
        cp -p "$SOURCE_DIR/$RELATIVE" "$PROJECT_DIR/$RELATIVE"
    fi
done

ROUTE_LINE="require_once __DIR__.'/biatech-storefront.php';"
if grep -Fq "biatech-storefront.php" "$PROJECT_DIR/routes/web.php"; then
    echo "Route require sudah tersedia; tidak ditambahkan lagi."
else
    echo "APPEND route registration: $ROUTE_LINE"
    if [[ "$MODE" == "--apply" ]]; then
        mkdir -p "$BACKUP_DIR/routes"
        cp -p "$PROJECT_DIR/routes/web.php" "$BACKUP_DIR/routes/web.php"
        printf '\n// BIATECH customer storefront; admin /admin remains handled by Filament.\n%s\n' "$ROUTE_LINE" >> "$PROJECT_DIR/routes/web.php"
    fi
fi

if [[ "$MODE" == "--apply" ]]; then
    echo
    echo "FILES INSTALLED. Existing files backed up in: $BACKUP_DIR"
    echo "Run (inside Laravel PHP container): php artisan optimize:clear"
    echo "Then: php artisan route:list --name=biatech"
    echo "IMPORTANT: Do not enable database checkout; payment is explicitly session DEMO."
else
    echo
    echo "DRY-RUN ONLY: no files were changed. Use --apply after reviewing."
fi
