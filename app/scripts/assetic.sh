#!/bin/bash

php app/console cache:clear &&
php app/console assets:install --symlink &&
php app/console assetic:dump
