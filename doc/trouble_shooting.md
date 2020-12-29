## Trouble Shooting ##

- Question #1
```
    PHP Fatal error:  Uncaught Error: Call to undefined function Unirest\curl_init()
```
- Answer
```
    - In Ubuntu
        sudo apt-get install php-curl
    - In Apache
        file : php.ini 
        uncomment this line : ;extension=php_curl.dll
```
