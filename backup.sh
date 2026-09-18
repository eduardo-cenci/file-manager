#!/bin/bash

set -euo pipefail

BACKUP_DIR="./storage/backups/$(date +%Y-%m-%d)"

mkdir -p "$BACKUP_DIR"

tar -czf "$BACKUP_DIR/files.tar.gz" ./storage/files
tar -czf "$BACKUP_DIR/logs.tar.gz" ./storage/logs

docker compose exec -T db sh -c \
    'mysqldump --no-tablespaces -u"$MYSQL_USER" -p"$MYSQL_PASSWORD" "$MYSQL_DATABASE"' \
    | gzip > "$BACKUP_DIR/database.sql.gz"

backups=(./storage/backups/*)

if (( ${#backups[@]} > 7 )); then
    for backup in "${backups[@]:0:${#backups[@]}-7}"; do
        rm -rf "$backup"
    done
fi