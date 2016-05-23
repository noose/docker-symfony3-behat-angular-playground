#!/usr/bin/env bash
export SYMFONY__DATABASE__HOST=db
export SYMFONY__DATABASE__PORT=3306
export SYMFONY__DATABASE__USER=$DB_ENV_MYSQL_USER
export SYMFONY__DATABASE__PASSWORD=$DB_ENV_MYSQL_PASSWORD
export SYMFONY__DATABASE__DATABASE=$DB_ENV_MYSQL_DATABASE
if [ ! -z "$1" ]; then
    if [[ -x "$1" ]]; then # if file can be executed then execute it
        $@
    else
        which $1 && $@  || . $@ # find file in $PATH and execute if present. If not - then execute ". $1" (like source $1)
    fi
fi
