#!/bin/bash

set -e

set -o allexport
source .env
set +o allexport

# Run MySQL
if ! docker ps --filter "name=$JOOMLA_MYSQL_CONTAINER_NAME" --filter "status=running" | grep -q $JOOMLA_MYSQL_CONTAINER_NAME; then
  echo "MySQL running..."
  docker compose up -d
fi

if [ ! -d joomla ]; then
	mkdir -p joomla
fi
cd joomla

# Download Joomla
if [ ! -f index.php ]; then
  echo "Joomla downloading..."
  curl -L -o joomla.zip https://downloads.joomla.org/cms/joomla5/5-2-5/Joomla_5-2-5-Stable-Full_Package.zip?format=zip?format=zip
  unzip joomla.zip
  rm joomla.zip
fi

if [ -d installation ]; then
  echo "Joomla installing..."
  php installation/joomla.php install -vvv -n \
      --db-type=mysqli \
      --db-host=${JOOMLA_DB_HOST} \
      --db-user=${JOOMLA_DB_USER} \
      --db-pass=${JOOMLA_DB_PASS} \
      --db-name=${JOOMLA_DB_NAME} \
      --db-encryption=0 \
      --db-prefix=jos_ \
      --admin-user=${JOOMLA_ADMIN_USER} \
      --admin-username=${JOOMLA_ADMIN_USER} \
      --admin-password=${JOOMLA_ADMIN_PASS} \
      --admin-email=${JOOMLA_ADMIN_EMAIL} \
      --site-name="${JOOMLA_SITE_NAME}"
fi

cd ..

php -S localhost:8000 -t joomla
docker compose down
