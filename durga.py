#!/usr/bin/env python3
"""
durga.py — PropSurat dev runner
Usage: python durga.py [port]
  Clears .pyc cache, runs Django dev server
"""
import os
import sys
import shutil
import subprocess

BASE_DIR = os.path.dirname(os.path.abspath(__file__))


def clear_cache():
    cleared = 0
    for root, dirs, files in os.walk(BASE_DIR):
        for d in dirs:
            if d == '__pycache__':
                path = os.path.join(root, d)
                shutil.rmtree(path)
                cleared += 1
        for f in files:
            if f.endswith('.pyc'):
                os.remove(os.path.join(root, f))
    if cleared:
        print(f'[durga] Cleared {cleared} cache dirs')


def main():
    port = sys.argv[1] if len(sys.argv) > 1 else '8000'
    clear_cache()
    os.environ.setdefault('DJANGO_SETTINGS_MODULE', 'core.settings')
    print(f'[durga] Starting PropSurat on http://127.0.0.1:{port}/')
    subprocess.run([sys.executable, 'manage.py', 'runserver', port])


if __name__ == '__main__':
    main()
