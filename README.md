<p align="center">
    <h1 align="center">Globelink Coffee Place</h1>
    <br>
</p>

## Project Installation Guide:

* First you need to clone Coffee Place project to an appropriate directory of your web server:
```
$cd /your/server/web/root
$git clone https://github.com/iegorlopatin/globelink.git
```

* Next you need to configure your Web Server (I prefer to use Mac default [apache2 server](https://httpd.apache.org/docs/trunk/getting-started.html). Please keep in mind that you are going to need to turn on mysql, php_pdo and vhost modules if they are not turned on yet. You can do it in httpd.conf file of your apache2 server.
* After that you are going to need to set up virtual hosts for your application (httpd-vhosts.conf file in case of apache server), put following code in it:
```
<VirtualHost *:80>
  DocumentRoot "/YOUR PROJECT DIR/globelink/backend/web"
  ServerName globelink-backend.local
  ErrorLog "/path/to/your/server/logdir/globelink-admin-panel-error_log"
  CustomLog "/path/to/your/server/logdir/globelink-admin-panel-access_log" common
</VirtualHost>

<VirtualHost *:80>
  DocumentRoot "/YOUR PROJECT DIR/globelink/frontend/web"
  ServerName globelink.local
  ErrorLog "/path/to/your/server/logdir/globelink-error_log"
  CustomLog "/path/to/your/server/logdir/globelink-access_log" common
</VirtualHost>
```
* Next your need to set up [mysql database server](https://dev.mysql.com/doc/refman/8.0/en/)



