#!/bin/bash

set -euo pipefail

if [ "$#" -ne 1 ]; then
    echo "Usage: ./restore.sh <date>"
    exit 1
fi

BACKUP_DIR="./storage/backups/$1"

if [ ! -d "$BACKUP_DIR" ]; then
    echo "Backup not found: $1"
    exit 1
fi

if [ ! -f "$BACKUP_DIR/database.sql.gz" ] ||
   [ ! -f "$BACKUP_DIR/files.tar.gz" ] ||
   [ ! -f "$BACKUP_DIR/logs.tar.gz" ]; then
    echo "Backup is incomplete: $1"
    exit 1
fi

read -r -p "Restore backup '$1'? This will erase current data. [y/N] " answer

if [[ "$answer" != "y" ]]; then
    echo "Restore cancelled."
    exit 0
fi

FILES_DIR="./storage/files"
LOGS_DIR="./storage/logs"

rm -rf "$FILES_DIR"/*
tar -xzf "$BACKUP_DIR/files.tar.gz"

rm -rf "$LOGS_DIR"/*
tar -xzf "$BACKUP_DIR/logs.tar.gz"

docker compose exec -T db sh -c \
    'mysql -uroot -p"$MYSQL_ROOT_PASSWORD" \
    -e "DROP DATABASE \`$MYSQL_DATABASE\`; CREATE DATABASE \`$MYSQL_DATABASE\`;"'

gzip -dc "$BACKUP_DIR/database.sql.gz" \
    | docker compose exec -T db sh -c \
    'mysql -u"$MYSQL_USER" -p"$MYSQL_PASSWORD" "$MYSQL_DATABASE"'

echo "Restore completed successfully."