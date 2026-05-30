"""
convert_to_jinja.py
Converts all static HTML pages to Jinja2 templates.
Run from project root: python3 convert_to_jinja.py
"""
import re
import os

BASE = os.path.dirname(os.path.abspath(__file__))
TPL_DIR = os.path.join(BASE, 'templates', 'pages')

URL_MAP = [
    ('href="index.html"', 'href="{{ url(\'home\') }}"'),
    ('href="listings.html"', 'href="{{ url(\'listings\') }}"'),
    ('href="agents.html"', 'href="{{ url(\'agents\') }}"'),
    ('href="agent-detail.html"', 'href="{{ url(\'agent_detail\') }}"'),
    ('href="neighborhoods.html"', 'href="{{ url(\'neighborhoods\') }}"'),
    ('href="blog.html"', 'href="{{ url(\'blog\') }}"'),
    ('href="blog-detail.html"', 'href="{{ url(\'blog_detail\') }}"'),
    ('href="about.html"', 'href="{{ url(\'about\') }}"'),
    ('href="contact.html"', 'href="{{ url(\'contact\') }}"'),
    ('href="login.html"', 'href="{{ url(\'login\') }}"'),
    ('href="register.html"', 'href="{{ url(\'register\') }}"'),
    ('href="dashboard-admin.html"', 'href="{{ url(\'dashboard_admin\') }}"'),
    ('href="dashboard-broker.html"', 'href="{{ url(\'dashboard_broker\') }}"'),
    ('href="dashboard-client.html"', 'href="{{ url(\'dashboard_client\') }}"'),
    ('href="dashboard-builder.html"', 'href="{{ url(\'dashboard_builder\') }}"'),
    ('href="property-detail.html"', 'href="{{ url(\'property_detail\') }}"'),
]


def apply_url_map(content):
    for old, new in URL_MAP:
        content = content.replace(old, new)
    return content


def get_title(raw):
    m = re.search(r'<title>(.*?)</title>', raw, re.DOTALL)
    return m.group(1).strip() if m else 'PropSurat'


def find_first_nav_end(lines):
    """First </nav> = end of main sticky navbar"""
    for i, line in enumerate(lines):
        if '</nav>' in line:
            return i
    return 0


def find_footer_start(lines):
    for i, line in enumerate(lines):
        if '═══════════════ FOOTER ═══════════════' in line:
            return i
        if '<!-- Footer' in line and '<footer' not in line:
            return i
    # fallback: find the rich footer tag
    for i, line in enumerate(lines):
        if '<footer class="bg-[#08111F]' in line:
            return i
    return len(lines)


# ── PUBLIC PAGES ──────────────────────────────────────────────────────────────
PUBLIC_PAGES = {
    'index.html':          'index.jinja',
    'listings.html':       'listings.jinja',
    'property-detail.html': 'property_detail.jinja',
    'agents.html':         'agents.jinja',
    'agent-detail.html':   'agent_detail.jinja',
    'neighborhoods.html':  'neighborhoods.jinja',
    'about.html':          'about.jinja',
    'contact.html':        'contact.jinja',
    'blog.html':           'blog.jinja',
    'blog-detail.html':    'blog_detail.jinja',
}

for src_file, tpl_name in PUBLIC_PAGES.items():
    src_path = os.path.join(BASE, src_file)
    with open(src_path, encoding='utf-8') as f:
        raw = f.read()

    title = get_title(raw)
    lines = raw.split('\n')

    nav_end = find_first_nav_end(lines)
    footer_start = find_footer_start(lines)

    content_lines = lines[nav_end + 1:footer_start]
    # strip leading blank lines
    while content_lines and not content_lines[0].strip():
        content_lines.pop(0)
    content = '\n'.join(content_lines)
    content = apply_url_map(content)

    out = f"{{% extends 'base.jinja' %}}\n\n"
    out += f"{{% block title %}}{title}{{% endblock %}}\n\n"
    out += f"{{% block content %}}\n{content}\n{{% endblock %}}\n"

    out_path = os.path.join(TPL_DIR, tpl_name)
    with open(out_path, 'w', encoding='utf-8') as f:
        f.write(out)
    print(f'  public  {src_file:30s} → pages/{tpl_name}  ({len(content_lines)} content lines)')


# ── DASHBOARD PAGES ───────────────────────────────────────────────────────────
def extract_body_xdata(raw):
    """Get the x-data string from <body ...>"""
    m = re.search(r'<body[^>]*x-data="([^"]*(?:"[^"]*")*[^"]*)"', raw, re.DOTALL)
    if m:
        return m.group(1)
    m2 = re.search(r"<body[^>]*x-data='([^']*)'", raw, re.DOTALL)
    if m2:
        return m2.group(1)
    return ''


def extract_inline_styles(raw):
    """Get content of first <style> block in <head>"""
    m = re.search(r'<style>(.*?)</style>', raw, re.DOTALL)
    return m.group(1).strip() if m else ''


def extract_body_content(raw):
    """Everything between <body ...> and </body>"""
    m = re.search(r'<body[^>]*>\n?(.*)</body>', raw, re.DOTALL)
    return m.group(1) if m else ''


DASHBOARD_PAGES = {
    'dashboard-admin.html':   'dashboard_admin.jinja',
    'dashboard-broker.html':  'dashboard_broker.jinja',
    'dashboard-client.html':  'dashboard_client.jinja',
    'dashboard-builder.html': 'dashboard_builder.jinja',
}

for src_file, tpl_name in DASHBOARD_PAGES.items():
    src_path = os.path.join(BASE, src_file)
    with open(src_path, encoding='utf-8') as f:
        raw = f.read()

    title = get_title(raw)
    xdata = extract_body_xdata(raw).strip()
    styles = extract_inline_styles(raw)
    body = extract_body_content(raw).strip()
    body = apply_url_map(body)

    # Build the template
    out = f"{{% extends 'layouts/dashboard_base.jinja' %}}\n\n"
    out += f"{{% block title %}}{title}{{% endblock %}}\n\n"

    # Extra styles (only page-specific bits not in dashboard_base)
    # We skip the shared CSS (sidebar/main/topbar) since it's in dashboard_base
    # Only keep styles that are NOT already in dashboard_base
    unique_styles = []
    skip_patterns = [
        'sidebar', '.main ', '.topbar', '.stat-card', '.mini-bar', '.tag',
        '.lead-row', '.btn', '.progress', '.mobile-menu', '.form-',
        '.modal', "* {", "h1,h2,h3", "@media"
    ]
    for block in re.split(r'\n(?=\S)', styles):
        if not any(p in block for p in skip_patterns):
            unique_styles.append(block.strip())

    extra_css = '\n'.join(s for s in unique_styles if s)
    if extra_css:
        out += f"{{% block extra_styles %}}\n{extra_css}\n{{% endblock %}}\n\n"

    if xdata:
        out += f'{{% block body_attrs %}}x-data=\'{xdata}\'{{% endblock %}}\n\n'

    out += f"{{% block body %}}\n{body}\n{{% endblock %}}\n"

    out_path = os.path.join(TPL_DIR, tpl_name)
    with open(out_path, 'w', encoding='utf-8') as f:
        f.write(out)
    print(f'  dashboard {src_file:30s} → pages/{tpl_name}')

print('\nDone. All pages converted.')
