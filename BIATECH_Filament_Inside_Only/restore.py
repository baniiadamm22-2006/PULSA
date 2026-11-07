#!/usr/bin/env python3
"""Restore last BIATECH resource-interior patch from its recorded backup."""
from pathlib import Path
import shutil, sys
project = Path.cwd()
backups = project/'backups'/'biatech-resource-interiors'
if len(sys.argv)>1:
    backup=Path(sys.argv[1]).resolve()
else:
    matches=sorted(p for p in backups.glob('*') if (p/'files.txt').is_file())
    if not matches:raise SystemExit('No backup found.')
    backup=matches[-1]
manifest=backup/'files.txt'
if not manifest.is_file():raise SystemExit('Invalid backup; files.txt is missing')
for rel in manifest.read_text().splitlines():
    src=backup/rel; dest=project/rel
    if not src.is_file(): raise SystemExit('Incomplete backup: '+str(src))
    shutil.copy2(src,dest)
    print('RESTORED:',rel)
print('Rollback complete. Run: docker compose exec -u www-data php php artisan optimize:clear')
