Vagrant.configure("2") do |config|

    config.vm.box = "ubuntu/trusty64"

	config.vm.network "private_network", ip: "192.168.13.37"

    config.vm.synced_folder "./", "/project", type: "nfs"

	config.vm.provider "virtualbox" do |box|
		box.memory = 2048
	end

end

# Define provision script
$provision = <<PROVISION

echo This can take a few minutes...

# Configure bash
echo "\n\ncd /project" >> /home/vagrant/.bash_profile

# Update repository
add-apt-repository ppa:ondrej/php > /dev/null 2>&1
apt-get update > /dev/null 2>&1
apt-get upgrade > /dev/null 2>&1

# Install binaries
apt-get install zip git php7.0-fpm php7.0-dom php7.0-xdebug -y > /dev/null 2>&1

# Install composer
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer creates=/usr/local/bin/composer > /dev/null 2>&1

# Configure php
sed -i 's/error_reporting = E_ALL & ~E_DEPRECATED & ~E_STRICT/error_reporting = E_ALL/g' /etc/php/7.0/fpm/php.ini > /dev/null 2>&1
sed -i 's/display_errors = Off/display_errors = On/g' /etc/php/7.0/fpm/php.ini > /dev/null 2>&1
echo "\nxdebug.remote_enable = On" >> /etc/php/7.0/cli/conf.d/20-xdebug.ini
echo "\nxdebug.remote_autostart = On" >> /etc/php/7.0/cli/conf.d/20-xdebug.ini
service php7.0-fpm restart > /dev/null 2>&1

cd /project > /dev/null 2>&1
composer install > /dev/null 2>&1

PROVISION

# Finally do provision
Vagrant.configure("2") do |config|

    config.vm.provision :shell, :inline => $provision

end