#!/bin/bash

php app/console assets:install &&
php app/console assetic:dump &&
php app/console cache:clear