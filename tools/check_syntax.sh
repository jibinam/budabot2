#!/bin/sh
find ./ -type f -name \*.php -exec php -l {} \; &> ./syntax.log