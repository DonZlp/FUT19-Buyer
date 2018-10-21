# FUT19 Buyer

**A very simple FIFA 19 Autobuyer coded in Laravel**

[FUT19 Buyer](https://github.com/InkedCurtis/FUT19-Buyer) is a simplistic FIFA 19 Autobuyer coded using the Laravel 5 Framework alongside the Backpack package.

## Requirements

* DigitalOcean Account - ($100 in Credit if you register using this link - [DigitalOcean](https://m.do.co/c/96b227b93ca5)
* FIFA 19 WebApp Enabled Account

## Installation

* Create a LAMP Droplet within DigitalOcean (Create Droplet > One-Click Apps -> LAMP on 18.04)
* Login to your server via SSH
* Install Composer - [Instructions](https://www.hostinger.co.uk/tutorials/how-to-install-composer)
* Go into your web directory (var/www/html) & run the following: `git clone https://github.com/InkedCurtis/FUT19-Buyer.git .`
* Install ZIP `sudo apt-get install php7.2-zip`
* Install MBString `sudo apt-get install php7.2-mbstring`
* Install DOM `sudo apt-get install php7.2-dom`
* While still inside your web directory run the following `composer install --no-dev`
* Edit your Apache config located at */etc/apache2/sites-available/000-default.conf* & change *AllowOverride None* to *AllowOverride All*, *DocumentRoot var/www/html to DocumentRoot var/www/html/public* & then save it
* Enable ModRewrite using `sudo a2enmod rewrite`
* Restart Apache `systemctl restart apache2`
* Create your mySQL Database - [Instructions](http://wiki.gandi.net/en/hosting/using-linux/tutorials/ubuntu/createdatabase)
* Chmod your Laravel Storage folder using `sudo chmod -R 777 /var/www/html/storage`
* Make a folder for your FUT Cookies `mkdir /var/www/html/storage/app/fut_cookies`
* Copy your *.env.example* to *.env* `php -r "copy('/var/www/html/.env.example', '/var/www/html/.env');"`
* Generate your application key: `php artisan key:generate -—ansi`
* Create the database tables `php artisan migrate`
* Import initial database seeds `php artisan db:seed`
* Setup Laravel Scheduler using `crontab -e` & then run `php /var/www/html/artisan schedule:run >> /dev/null 2>&1`

## Screenshots

![Screenshot](https://i.imgur.com/4kBLiIp.png)

## More Projects
If you require any projects/systems to be developed alongside the FIFA 19 WebApp API then be sure to contact me using one of the methods below.

Skype: <strong>bws-curtis</strong><br/>
Email: <strong>wscrewey@hotmail.com</strong><br/>
Website: <strong>https://curtiscrewe.co.uk</strong>