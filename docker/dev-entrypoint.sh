#!/bin/sh

if [ -n "$UNIX_UID" -a -n "$UNIX_GID"  ]
then
    groupmod --gid "$UNIX_GID" www-data
    usermod --uid "$UNIX_UID" --gid "$UNIX_GID" www-data
fi

exec "$@"
