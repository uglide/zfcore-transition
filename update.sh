#!/bin/sh
############################
# Application Update Script
############################

# Server data
echo "* Permissions for ./data/"

chmod -v a+w ./data/cache/
chmod -v a+w ./data/logs/
chmod -v a+w ./data/languages/
chmod -v a+w ./data/session/
chmod -v a+w ./data/uploads/

# Public data
echo "* Permissions for ./public/"
chmod -v a+w ./public/captcha/
chmod -v a+w ./public/uploads/

echo "* Copy application.yaml file"
cp -v ./application/configs/application.yaml.dist ./application/configs/application.yaml

#Clean cache
echo "* Clean cache files"
rm -f ./data/cache/*

echo "* Install composer dependancies"
curl -s http://getcomposer.org/installer | php
php composer.phar update

echo "* done"