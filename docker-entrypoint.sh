#!/bin/sh
: "${PORT:=80}"
# Replace Apache Listen port with the value from $PORT (Render sets PORT env)
if [ -n "$PORT" ]; then
  sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf || true
  sed -i "s/:80/:${PORT}/g" /etc/apache2/sites-available/000-default.conf || true
fi

exec "$@"
