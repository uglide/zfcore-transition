#!/bin/sh

#
# Simple script for update
#

# close site for users
cp -v ./public/maintenance.htaccess ./public/.htaccess

# close site for search spiders
cp -v ./public/maintenance-robots.txt ./public/robots.txt
