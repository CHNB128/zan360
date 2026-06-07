#!/usr/bin/env bash

set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
THEME_SLUG="$(basename "$ROOT_DIR")"
DIST_DIR="$ROOT_DIR/dist"
BUILD_DIR="$DIST_DIR/$THEME_SLUG"
ZIP_PATH="$DIST_DIR/$THEME_SLUG.zip"

cd "$ROOT_DIR"

echo "Preparing $THEME_SLUG for hosting deploy..."

if [[ -f yarn.lock ]] && command -v yarn >/dev/null 2>&1; then
  yarn install --frozen-lockfile
elif [[ -f package-lock.json ]]; then
  npm ci
else
  npm install
fi

npm run build

composer install \
  --no-dev \
  --prefer-dist \
  --optimize-autoloader \
  --no-interaction

ACORN_RENDERER_DIST="$ROOT_DIR/vendor/roots/acorn/src/Illuminate/Foundation/resources/exceptions/renderer/dist"

if [[ ! -f "$ACORN_RENDERER_DIST/styles.css" || ! -f "$ACORN_RENDERER_DIST/scripts.js" ]]; then
  echo "Missing Acorn exception renderer assets. Re-run composer install and make sure vendor/roots/acorn is complete." >&2
  exit 1
fi

rm -rf "$BUILD_DIR" "$ZIP_PATH"
mkdir -p "$BUILD_DIR"

rsync -a ./ "$BUILD_DIR/" \
  --exclude='.DS_Store' \
  --exclude='.editorconfig' \
  --exclude='.env' \
  --exclude='.git' \
  --exclude='.gitignore' \
  --exclude='dist' \
  --exclude='node_modules' \
  --exclude='package-lock.json' \
  --exclude='package.json' \
  --exclude='public/hot' \
  --exclude='resources/css' \
  --exclude='resources/js' \
  --exclude='scripts' \
  --exclude='vite.config.js' \
  --exclude='yarn.lock'

(
  cd "$DIST_DIR"
  zip -qr "$ZIP_PATH" "$THEME_SLUG"
)

echo "Deploy archive created: $ZIP_PATH"
