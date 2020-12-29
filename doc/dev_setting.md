## Testing in Localhost ##

```
(172.ABC.XXX.XXX is Sandbox Anyonepay API server to communicate with merchant API server 
 172.MER.XXX.XXX is nginx for fake merchant shop)

  lib/Anyonepay/core/Configuration.php
    const API_ORIGIN_SANDBOX    = "http://172.ABC.XXX.XXX:3020";
  test/shop.html
    var cc = "http://172.MER.XXX.XXX/test/register.php";
```
```
  Staging : http://testshop.anyonepay.ph
  Default Password : admin / lucky (It can be different by server environment)
```
## WSL2 nginx php environment for windows 10 user ##
```
#Install PHP
sudo apt install php7.2 php7.2-cli php7.2-fpm
#Check the version
php -version
Nginx Installation
# Install Nginx
sudo apt install nginx

# Start Nginx server
sudo service nginx start

wsl 2 & Networking
Wsl 2 uses a virtual switch to route traffic. We will need to obtain the IP address for the network interface.

ip addr show


Setting up PHP-FPM
The next steps is setting up the PHP-FPM processor. We need to get the unix sock file that the PHP-FPM service will listen on.

grep "listen =" /etc/php/7.2/fpm/pool.d/www.conf
#output: listen = /run/php/php7.2-fpm.sock

#start the service
sudo service php7.2-fpm start
We need to modify the Nginx default server block settings.

sudo nano /etc/nginx/sites-available/default
Add the following location location block to the default site.

  location ~ \.php$ {
                 include snippets/fastcgi-php.conf;
 
                 # Make sure unix socket path matches PHP-FPM configured path above
                 fastcgi_pass unix:/run/php/php7.2-fpm.sock;
 
                 # Prevent ERR_INCOMPLETE_CHUNKED_ENCODING when browser hangs on response
                 fastcgi_buffering off;
         } 
We will need to restart the Nginx server for it to load the new settings.

sudo service nginx restart
```