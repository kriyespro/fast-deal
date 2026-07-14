# PropSurat

India's multi-broker property portal — Buy, Sell, Rent verified properties across 50+ cities.

**Stack:** Django 5 · Jinja2 · HTMX · Alpine.js · Tailwind CSS

## Roles

| Role | Dashboard |
|------|-----------|
| Client | `/dashboard/client/` |
| Broker | `/dashboard/broker/` |
| Builder | `/dashboard/builder/` |
| City Admin | `/dashboard/admin/` |
| Global Admin | `/dashboard/admin/` + Mission Control `/admin/` |

## Quick start

```bash
python3 durga.py          # clears cache + starts http://127.0.0.1:8000/
# or
python3 manage.py runserver 8000
```

See `test_user.txt` for demo credentials.
