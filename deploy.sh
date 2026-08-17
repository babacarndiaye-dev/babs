#!/usr/bin/env bash
set -e

# Forge deployment script — mirrors what's pasted into the site's
# "Deployment Script" field in the Forge dashboard. Kept here so the
# deploy process is versioned and reviewable like the rest of the app.
# See docs/DEPLOIEMENT.md for the full setup walkthrough.

cd $FORGE_SITE_PATH

git pull origin $FORGE_SITE_BRANCH

$FORGE_COMPOSER install --no-dev --no-interaction --prefer-dist --optimize-autoloader

( flock -w 10 9 || exit 1
    echo 'Restarting FPM...'; sudo -S service $FORGE_PHP_FPM reload ) 9>/tmp/fpmlock

npm ci
npm run build

$FORGE_PHP artisan migrate --force
$FORGE_PHP artisan storage:link
$FORGE_PHP artisan config:cache
$FORGE_PHP artisan route:cache
$FORGE_PHP artisan view:cache
$FORGE_PHP artisan event:cache
