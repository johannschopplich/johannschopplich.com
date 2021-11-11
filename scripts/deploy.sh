#!/bin/sh

website="johannschopplich.com"
host="russel.uberspace.de"
read -p "Target host (default is \`$host\`): " h
if [ "$h" != "" ]; then
  user="$h"
fi

user=""
read -p "Login user (default is \`$user\`): " u
if [ "$u" != "" ]; then
  user="$u"
fi

echo "--- Deploying latest changes…"
ssh -tt $user@$host "\
  cd /var/www/virtual/$user/sites/$website; \
  git pull; \
  composer install; \
  pnpm i; \
  pnpm run build; \
  rm -rf storage/cache/$website; \
"
