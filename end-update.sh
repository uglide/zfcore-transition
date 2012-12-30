#!/bin/sh

#
# Simple script for opening site for users
#

# Copy .htaccess file
echo "* Copy .htaccess file"
cp -v ./public/.htaccess.sample ./public/.htaccess