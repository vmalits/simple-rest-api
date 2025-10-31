# Simple REST API (Laravel 12)

A minimal, production‑ready REST API built with Laravel 12. It provides versioned endpoints, token‑based authentication with Sanctum, robust rate limiting, rich API documentation (Scribe), and clean resource responses. The example domain manages users with full CRUD and query capabilities.

- PHP 8.4, Laravel 12
- Auth: Laravel Sanctum (personal access tokens)
- Docs: Scribe 5 (generate static API docs)
- Querying: Spatie Laravel Query Builder (filter/sort/pagination conventions)
- Testing: Pest
- Docker: Postgres + Redis (Sail‑compatible compose)

## Table of contents
- Quick start
- Requirements
- Local setup
- Running the app
- API authentication
- Usage & conventions
- Endpoints
- API docs (Scribe)
- Rate limiting
- Testing
- Code style
- Troubleshooting
- License

## Quick start

```bash
# 1) Clone and install dependencies
git clone https://github.com/vmalits/simple-rest-api && cd simple-rest-api
composer install
npm install # optional; only needed if you run Vite/dev script

# 2) Create .env and app key
cp .env.example .env
php artisan key:generate

# 3a) Use SQLite for a zero‑config DB (quickest)
php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"
sed -i.bak 's/DB_CONNECTION=.*/DB_CONNECTION=sqlite/' .env && rm .env.bak

# 3b) Or use Docker (Postgres + Redis)
#   Ensure Docker is running, then:
sail up -d
# Update .env DB_ variables to match compose.yaml (see below)

# 4) Run migrations
php artisan migrate

# 5) Serve the API
php artisan serve # http://127.0.0.1:8000 or sail up -d (if using Docker)
```

## Requirements
- PHP 8.4+
- Composer 2.x
- Node 20+ (only if running Vite/dev assets; not required for the API)
- Docker (optional) if you prefer Postgres/Redis via containers

## Local setup

### Environment
Copy the example file and set values as needed:

```dotenv
APP_NAME="Simple REST API"
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost

# Choose ONE of the database options below
# 1) SQLite (recommended for quick start)
DB_CONNECTION=sqlite

# 2) Postgres (Docker compose)
# DB_CONNECTION=pgsql
# DB_HOST=127.0.0.1
# DB_PORT=5432
# DB_DATABASE=app
# DB_USERNAME=postgres
# DB_PASSWORD=secret

# Sanctum
SESSION_DRIVER=cookie
SANCTUM_STATEFUL_DOMAINS=localhost,127.0.0.1

# Rate limits (defaults shown)
RATE_LIMIT_API_PER_MINUTE=120
RATE_LIMIT_LOGIN_PER_MINUTE=5
RATE_LIMIT_LOGIN_PER_HOUR=50
RATE_LIMIT_REGISTER_PER_MINUTE=3
RATE_LIMIT_REGISTER_PER_HOUR=20
```

### Using Docker (optional)
This repo ships a compose file similar to Laravel Sail, providing PHP + Nginx, Postgres, and Redis.

- Start services: `docker compose up -d`
- The API will be available at the port exposed in `compose.yaml` (default 80 → `http://localhost`).
- Example Postgres credentials (configure in `.env`):
  - `DB_CONNECTION=pgsql`
  - `DB_HOST=127.0.0.1`
  - `DB_PORT=5432`
  - `DB_DATABASE=app`
  - `DB_USERNAME=postgres`
  - `DB_PASSWORD=secret`

## Running the app
- Start the built‑in server: `php artisan serve` (default `http://127.0.0.1:8000`)
- Or use the provided dev script which also runs queue logs and Vite:

```bash
composer run dev
```

## API authentication
Authentication uses Laravel Sanctum personal access tokens.

- Register: `POST /api/v1/register`
- Login: `POST /api/v1/login`
- Authenticated routes use `auth:sanctum` middleware and expect an `Authorization: Bearer <token>` header.

Typical flow:

```bash
# Register (returns token)
curl -X POST http://127.0.0.1:8000/api/v1/register \
  -H "Content-Type: application/json" \
  -d '{"name":"Jane Doe","email":"jane@example.com","password":"P@ssw0rd!23"}'

# Login (returns token)
curl -X POST http://127.0.0.1:8000/api/v1/login \
  -H "Content-Type: application/json" \
  -d '{"email":"jane@example.com","password":"P@ssw0rd!23"}'

# Use the token for authenticated requests
curl -H "Authorization: Bearer <token>" \
  http://127.0.0.1:8000/api/v1/users
```

Token response shape (simplified):

```json
{
  "token": "<personal-access-token>",
  "token_type": "Bearer"
}
```

## Usage & conventions
- Base URL: `/api/v1`
- Content type: `application/json`
- Pagination: `per_page` query param (min 1, max 100). Responses include `meta` and `links`.
- Filtering: exact filters supported (e.g., `filter[name]`, `filter[email]`).
- Sorting: e.g., `sort=name` or `sort=-name`.

## Endpoints

Auth
- `POST /api/v1/register` — create account and receive token
- `POST /api/v1/login` — obtain token

Users (requires `Authorization: Bearer <token>`) under `/api/v1/users`:
- `GET /` — list users with pagination/filter/sort
- `GET /{user}` — show a user
- `POST /` — create user
- `PUT /{user}` — update user
- `DELETE /{user}` — delete user

See routes in:
- `routes/api.php`
- `routes/api/users.php`

## API docs (Scribe)
This project uses Scribe for beautiful static API documentation.

Generate docs:

```bash
php artisan scribe:generate
```

Open the generated site at `public/docs/index.html`.

Scribe annotations live in the controllers, for example:
- `App/Http/Controllers/Users/V1/IndexController.php`
- `App/Http/Controllers/Users/V1/StoreController.php`

## Rate limiting
We enforce named limiters via Laravel’s `RateLimiter` (see `app/Providers/AppServiceProvider.php`). Applied in `routes/api.php`.

- General API: `throttle:api` — default `120/min` keyed by authenticated user ID (fallback to IP)
- Login: `throttle:login` — strict per `email+IP` `5/min` and IP cap `50/hour`
- Register: `throttle:register` — per `email+IP` `3/min` and IP cap `20/hour`

Customize through environment variables:

```dotenv
RATE_LIMIT_API_PER_MINUTE=120
RATE_LIMIT_LOGIN_PER_MINUTE=5
RATE_LIMIT_LOGIN_PER_HOUR=50
RATE_LIMIT_REGISTER_PER_MINUTE=3
RATE_LIMIT_REGISTER_PER_HOUR=20
```

On limit breach, the API returns `429` with a JSON body and standard `X-RateLimit-*` headers.

## Testing
Run the test suite with Pest:

```bash
composer test
```

Useful housekeeping before tests:

```bash
php artisan config:clear
php artisan migrate:fresh --seed # if you have seeders
```

## Code style
This repo includes a Pint config. Format code with:

```bash
./vendor/bin/pint
```
