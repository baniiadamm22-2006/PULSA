#!/usr/bin/env python3
"""Read-only inventory of demo/simulation references in the BIATECH source tree."""
from __future__ import annotations
import argparse
import re
from pathlib import Path

MATCH = re.compile(
    r'(?i)demo|simulasi|simulator|dummy|mock|sandbox|prototype|'
    r'faker|fake[_ -]?order|biatech_demo_orders|catalog_source|isDemo|BT-DEMO'
)
FOLDERS = ['app', 'config', 'routes', 'resources/views', 'database/seeders', 'tests', 'public/biatech']


def main() -> None:
    p = argparse.ArgumentParser(description=__doc__)
    p.add_argument('--root', type=Path, default=Path('/root/perkuliahan/pulsa'))
    args = p.parse_args()
    base = args.root.resolve() / 'src'
    files = 0
    occurrences = 0
    for folder in FOLDERS:
        current = base / folder
        if not current.exists():
            continue
        for path in sorted(current.rglob('*')):
            if not path.is_file() or path.suffix not in ('.php', '.css', '.js', '.md', '.html', '.json'):
                continue
            try:
                lines = path.read_text(encoding='utf-8').splitlines()
            except (UnicodeDecodeError, OSError):
                continue
            nums = [i for i, line in enumerate(lines, 1) if MATCH.search(line)]
            if nums:
                files += 1
                occurrences += len(nums)
                print(f'{path.relative_to(base)}: {", ".join(str(i) for i in nums[:40])}')
    print(f'Found {occurrences} matching lines across {files} files.')
    print('This is a read-only inventory. Review findings manually before deleting or changing data.')


if __name__ == '__main__':
    main()
