set -e

cd {SITE_DIRECTORY}

# Deploys are non-interactive: no purge prompt, no update notifier
export CI=true

if [ ! -f .env ] && [ -f .env.production.example ]; then
  cp .env.production.example .env
fi

# Discard build artifacts written on the server
git reset --hard
git pull origin main

composer install --no-interaction --prefer-dist --optimize-autoloader

{RELOAD_PHP_FPM}

export NVM_DIR="$HOME/.nvm"
[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"

corepack enable
corepack install

pnpm install --frozen-lockfile
pnpm run build

rm -rf storage/cache/{SITE_DOMAIN}

echo "🚀 Application deployed!"
