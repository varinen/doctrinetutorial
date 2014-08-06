#!/usr/bin/env bash

xdebug () {
read xdebugpart
echo "xdebug location at $xdebugpart"
cat << EOF | sudo tee -a /etc/php5/apache2/php.ini
xdebug.remote_host = 192.168.33.1
xdebug.remote_enable = 1
xdebug.remote_port = 9000
xdebug.remote_handler = dbgp
xdebug.remote_mode = req
zend_extension=$xdebugpart
EOF

cat << EOF | sudo tee -a /etc/php5/cli/php.ini
xdebug.remote_host = 192.168.33.1
xdebug.remote_enable = 1
xdebug.remote_port = 9000
xdebug.remote_handler = dbgp
xdebug.remote_mode = req
EOF
}


mkDirIfNotExist() {
if [ -d $1 ];
then
    echo $1 " exists"
else
    echo $1 " not exists"
    sudo mkdir -p $1
    echo $1 " created"
fi
}
# @param host
# @param user
# @param db_password
# @param db_name
# @param project name
setupDb () {
    echo "setting up the database"
    mysql -h$1 -u$2 -p$3 $4  < $1 ;
    echo "update core_config_data set value = 'http://www.$5.dev/' where path = 'web/unsecure/base_url'" | mysql -h$1 -u$2 -p$3 $4
    echo "update core_config_data set value = 'https://www.$5.dev/' where path = 'web/secure/base_url'" | mysql -h$1 -u$2 -p$3 $4
    echo "update core_config_data set value = 'www.$5.dev' where path = 'web/cookie/cookie_domain'" | mysql -h$1 -u$2 -p$3 $4

}

forceSymLink() {
    sudo rm -rf $2
    sudo ln -s $1 $2
    echo $2 " created"
}

echo "Start provisioning"

sudo apt-get update >/dev/null 2>&1

sudo a2enmod headers

sudo apt-get install php5-xdebug
echo "writing xdebug"
cd /usr/lib
find $PWD -name "xdebug.so"  | xdebug
cd /vagrant

sudo rm -rf /etc/apache2/sites-enabled/*
sudo cp -r /vagrant/apache_hosts/* /etc/apache2/sites-available/
sudo a2ensite 0_macro.conf
sudo a2ensite vhosts.conf
sudo a2ensite ssl.conf
forceSymLink "/var/www/www.doctrine.dev/project/src" "/var/www/www.doctrine.dev/webroot"

sudo chown -R vagrant.vagrant /var/www/www.doctrine.dev

sudo service apache2 restart >/dev/null 2>&1
#in case mysql is not running
sudo service mysql restart >/dev/null 2>&1
#setupDb 192.168.33.1 root dev miamoda_de miamoda

echo "Provisioning complete"