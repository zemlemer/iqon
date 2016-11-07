#!/usr/bin/env bash

sudo cp /vagrant/.provision/nginx/nginx.conf /etc/nginx/sites-available/site.conf
sudo chmod 644 /etc/nginx/sites-available/site.conf
sudo ln -s /etc/nginx/sites-available/site.conf /etc/nginx/sites-enabled/site.conf
sudo service nginx restart

mysql -uhomestead -psecret homestead < /vagrant/.provision/sql/comments.sql

sudo rm -Rf /var/www
sudo ln -s /vagrant/www /var/www
