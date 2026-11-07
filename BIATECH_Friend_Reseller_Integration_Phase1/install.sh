#!/usr/bin/env bash
set -euo pipefail

MODE="${1:---check}"
BASE="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT="${2:-$(pwd)}"

case "$MODE" in
  --check|--apply) ;;
  *) echo 'Usage: bash install.sh --check|--apply [project-root]' >&2; exit 2 ;;
esac

if [[ ! -d "$PROJECT/src/app" || ! -f "$PROJECT/docker-compose.yml" ]]; then
  echo "ERROR: Project root bukan folder BIATECH (src/app dan docker-compose.yml diperlukan): $PROJECT" >&2
  exit 1
fi

FILES=(
  'src/config/biatech-reseller.php'
  'src/app/Services/Reseller/FriendResellerClient.php'
  'src/app/Console/Commands/CheckFriendReseller.php'
)

for relative in "${FILES[@]}"; do
  if [[ -e "$PROJECT/$relative" ]]; then
    echo "CONFLICT: $relative sudah ada. Tidak akan ditimpa." >&2
    exit 1
  fi
  if command -v php >/dev/null 2>&1; then
    php -l "$BASE/files/$relative" >/dev/null
    echo "NEW: $relative (PHP syntax OK)"
  else
    echo "NEW: $relative (validasi sintaks PHP lewat Docker setelah pemasangan)"
  fi
done

if [[ "$MODE" == '--check' ]]; then
  echo 'CHECK OK: Tidak ada file existing yang akan ditimpa.'
  exit 0
fi

for relative in "${FILES[@]}"; do
  install -D -m 0644 "$BASE/files/$relative" "$PROJECT/$relative"
done

echo 'APPLY OK: 3 file BARU sudah ditambahkan. Tidak ada file lama yang diubah.'
echo 'NEXT: Atur src/.env lalu jalankan docker compose exec -T php php artisan config:clear'
