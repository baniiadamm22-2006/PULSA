#!/usr/bin/env python3
"""BIATECH Filament v3: update only Resource form/table/infolist method bodies."""
from __future__ import annotations
from pathlib import Path
import argparse
import re
import shutil
from datetime import datetime

ROOT = Path(__file__).resolve().parent
METHODS = ROOT / 'methods'
NAMES = sorted(f.stem for f in METHODS.glob('*.txt'))
OPTIONAL = {'AdminActivityLogResource'}  # May be provided by filament-logger instead of an app Resource.


def find_php_method(code: str, method: str) -> tuple[int,int] | None:
    match = re.search(r'(?m)^\s*public\s+static\s+function\s+' + method + r'\s*\(', code)
    if not match: return None
    start = match.start()
    pos = match.end()
    # Advance to opening brace of method, ignoring parentheses in type declarations.
    opening = code.find('{', pos)
    if opening == -1: raise ValueError(f'Opening brace missing for {method}')
    depth = 0; state='code'; i=opening
    while i < len(code):
        ch = code[i]; nxt=code[i+1] if i+1<len(code) else ''
        if state=='code':
            if ch=="'":state='single'
            elif ch=='"':state='double'
            elif ch=='/' and nxt=='/':state='line';i+=1
            elif ch=='#':state='line'
            elif ch=='/' and nxt=='*':state='block';i+=1
            elif ch=='{':depth+=1
            elif ch=='}':
                depth-=1
                if depth==0:return start,i+1
        elif state in ('single','double'):
            if ch=='\\':i+=1
            elif (ch=="'" and state=='single') or (ch=='"' and state=='double'):state='code'
        elif state=='line':
            if ch=='\n':state='code'
        elif state=='block':
            if ch=='*' and nxt=='/':state='code';i+=1
        i+=1
    raise ValueError(f'Unclosed method: {method}')


def patch_file(orig: str, payload: str) -> str:
    code = orig
    for name in ('form','infolist','table'):
        m = find_php_method(payload,name)
        if m is None:continue
        new = payload[m[0]:m[1]].strip()
        old = find_php_method(code,name)
        if old is not None:
            code = code[:old[0]]+'\n    '+new.replace('\n','\n    ')+code[old[1]:]
        else:
            # Add missing method directly before getPages(), leaving navigation and authorization untouched.
            anchor = re.search(r'(?m)^\s*public\s+static\s+function\s+getPages\s*\(',code)
            if not anchor:raise ValueError(f'Cannot insert {name}: getPages() missing')
            code = code[:anchor.start()] + '\n    ' + new.replace('\n','\n    ') + '\n\n' + code[anchor.start():]
    return code


def main() -> int:
    p = argparse.ArgumentParser(description='Fill existing Filament resource interiors only')
    p.add_argument('--project', default='.', help='Docker compose project root containing src/')
    p.add_argument('--apply', action='store_true', help='Write files after preflight and backup')
    args=p.parse_args()
    project=Path(args.project).resolve()
    src=project/'src'/'app'/'Filament'
    primary=src/'Admin'/'Resources'
    secondary=src/'Resources'
    if not (project/'src'/'artisan').is_file():
        print('ERROR: src/artisan not found. Run from /root/perkuliahan/pulsa.');return 2
    plan=[]; errors=[]
    for name in NAMES:
        options=[f for f in (primary/f'{name}.php',secondary/f'{name}.php') if f.is_file()]
        if len(options)==0 and name in OPTIONAL:
            print(f'SKIP     {name} (provided by a plugin or not installed)')
            continue
        if len(options)!=1:
            errors.append(f'{name}: expected exactly one existing Resource file (found {len(options)})')
            continue
        target=options[0]
        original=target.read_text()
        if not re.search(r'class\s+'+name+r'\s+extends\s+Resource\b',original):
            errors.append(f'{name}: class declaration differs from expected');continue
        if not re.search(r'public\s+static\s+function\s+getPages\s*\(',original):
            errors.append(f'{name}: getPages() not found');continue
        # Require standard resource model so fields match original BIATECH snapshot.
        model=name.removesuffix('Resource')
        if not re.search(r'\$model\s*=\s*'+model+r'::class',original):
            errors.append(f'{name}: model differs from App\\Models\\{model}');continue
        try: patched=patch_file(original,(METHODS/f'{name}.txt').read_text())
        except Exception as e:errors.append(f'{name}: {e}');continue
        plan.append((target,original,patched))

    if errors:
        print('Preflight failed — nothing modified:')
        for e in errors:print(' -',e)
        print('Send resource paths/errors before installing; do not force overwrite.')
        return 2
    for target,original,patched in plan:
        print(('CHANGE' if original!=patched else 'SAME').ljust(8),target.relative_to(project))
    if not args.apply:
        print('\nDRY RUN ONLY. To install: python3 BIATECH_Filament_Inside_Only/apply.py --apply')
        return 0
    backups=project/'backups'/'biatech-resource-interiors'
    backups.mkdir(parents=True,exist_ok=True)
    backup=backups/datetime.now().strftime('%Y%m%d-%H%M%S')
    if backup.exists():
        raise RuntimeError('Backup already exists — retry after one second')
    backup.mkdir()
    manifest=[]
    for target,orig,patched in plan:
        relative=target.relative_to(project)
        saved=backup/relative
        saved.parent.mkdir(parents=True,exist_ok=True)
        saved.write_text(orig)
        manifest.append(str(relative))
    (backup/'files.txt').write_text('\n'.join(manifest)+'\n')
    try:
        for target,orig,patched in plan:
            target.write_text(patched)
    except BaseException:
        for target,orig,_ in plan:target.write_text(orig)
        raise
    print('BACKUP:',backup)
    print('DONE: patched resource internals only; models, routes, providers, schema, pages unchanged.')
    print('NEXT: Run PHP syntax checks and php artisan optimize:clear; README provides commands.')
    return 0

if __name__=='__main__':
    raise SystemExit(main())
