#!/usr/bin/env bash
php bin/console server:start --force 0.0.0.0 && php bin/console assetic:watch --force
