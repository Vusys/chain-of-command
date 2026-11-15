#!/usr/bin/env bash
#
# Serve the application for the Tetryon browser suite.
#
# The suite drives a real browser against a separately-served instance of the
# app, so the two must agree on the URL to drive and on a *file* database they
# can both open.
#
#   URL       APP_URL, or TETRYON_BASE_URL when that is set. The suite reads the
#             same pair through config/tetryon.php, so the two cannot drift.
#   Database  database/tetryon.sqlite, throwaway and rebuilt on every start.
#             phpunit.browser.xml points the test process at the same file.
#
# File session and cache drivers keep the served app off the sqlite file the
# test process is writing to.
#
# Usage: bin/browser-serve.sh [--keep-database]

set -Eeuo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT_DIR"

DB_FILE='database/tetryon.sqlite'
FRESH=1

for argument in "$@"; do
    case "$argument" in
        --keep-database) FRESH=0 ;;
        -h | --help)
            sed -n '3,17p' "${BASH_SOURCE[0]}" | sed 's/^# \{0,1\}//'
            exit 0
            ;;
        *)
            echo "Unknown option: $argument" >&2
            exit 64
            ;;
    esac
done

base_url="${TETRYON_BASE_URL:-}"
if [[ -z "$base_url" && -f .env ]]; then
    base_url="$(sed -n 's/^APP_URL=//p' .env | head -1 | tr -d '"'"'"'')"
fi
base_url="${base_url:-http://127.0.0.1:8000}"

host="$(php -r 'echo parse_url($argv[1], PHP_URL_HOST) ?: "127.0.0.1";' "$base_url")"
port="$(php -r 'echo parse_url($argv[1], PHP_URL_PORT) ?: 8000;' "$base_url")"

export DB_CONNECTION=sqlite
export DB_DATABASE="$DB_FILE"

touch "$DB_FILE"

if [[ "$FRESH" -eq 1 ]]; then
    echo "==> Rebuilding $DB_FILE"
    php artisan migrate:fresh --force
fi

echo "==> Serving $base_url for the browser suite (run: composer browser)"

# PHP's own server rather than `artisan serve`, which drops -d flags on its
# child process: opcache must be off, or a php.ini with a long
# revalidate_freq keeps serving the old code for minutes after an edit and
# the suite passes against code that no longer exists. Laravel's router
# script expects to run from public/, as artisan serve arranges.
cd public
exec env \
    APP_ENV=local \
    APP_URL="$base_url" \
    SESSION_DRIVER=file \
    CACHE_STORE=file \
    QUEUE_CONNECTION=sync \
    php -d opcache.enable=0 -S "$host:$port" ../vendor/laravel/framework/src/Illuminate/Foundation/resources/server.php
