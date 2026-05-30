import os
import re

directories = ['app/Views']
pattern1 = re.compile(r'\$([0-9])')
pattern2 = re.compile(r"'\$' \+") # for x-text="'$' + price"

files_updated = 0

for d in directories:
    for root, dirs, files in os.walk(d):
        for file in files:
            if file.endswith('.php'):
                filepath = os.path.join(root, file)
                with open(filepath, 'r') as f:
                    content = f.read()
                
                new_content = pattern1.sub(r'₹\1', content)
                new_content = pattern2.sub(r"'₹' +", new_content)
                
                if new_content != content:
                    with open(filepath, 'w') as f:
                        f.write(new_content)
                    files_updated += 1
                    print(f"Updated {filepath}")

print(f"Total files updated: {files_updated}")
