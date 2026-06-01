# PropSurat — Server Setup (159.195.52.197)

## 1. Install Docker on server

```bash
curl -fsSL https://get.docker.com | sh
sudo usermod -aG docker $USER
newgrp docker
```

## 2. Clone repo + upload .env.production

```bash
git clone https://github.com/YOUR_ORG/propsurat.git /srv/propsurat
cd /srv/propsurat
# SCP the .env.production from your local machine:
# scp .env.production root@159.195.52.197:/srv/propsurat/.env.production
```

## 3. First-time SSL (Let's Encrypt)

Run nginx in HTTP-only mode first to get certs:

```bash
# Temporarily comment out the ssl_certificate lines in nginx/nginx.conf
# and change port 443 block to listen 80 only, then:
docker compose --env-file .env.production up -d nginx db redis

# Get cert:
docker compose run --rm certbot certonly \
  --webroot -w /var/www/certbot \
  --email hello@propsurat.in \
  --agree-tos --no-eff-email \
  -d propsurat.com -d www.propsurat.com

# Restore nginx.conf SSL lines, then start everything:
docker compose --env-file .env.production up -d
```

## 4. Seed initial data (first deploy only)

```bash
docker compose exec web python manage.py shell -c "
exec(open('seed_data.py').read())
"
```

## 5. Create superuser

```bash
docker compose exec web python manage.py createsuperuser
```

## 6. Subsequent deploys

```bash
bash deploy.sh
```

---

## Services

| Service  | Port  | Notes                        |
|----------|-------|------------------------------|
| nginx    | 80/443| Reverse proxy + SSL          |
| web      | 8000  | Gunicorn (internal only)     |
| db       | 5432  | PostgreSQL (internal only)   |
| redis    | 6379  | Cache + sessions (internal)  |
| certbot  | —     | Auto-renew certs every 12h   |
