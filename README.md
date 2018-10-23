<p align="center">
    <h1 align="center">Globelink Coffee Place</h1>
    <br>
</p>

## Project Installation Guide:

* First you need to clone Coffee Place project to an appropriate directory of your web server:
```
cd /your/server/web/root
git clone https://github.com/iegorlopatin/globelink.git
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
  * Create "globelink" user with "globelink" password
  * Create globelink database
  * If you decided to use other database or user, please edit 'db' section of': 
    - /PATH TO PROJECT/globelink/environments/dev/common/config/main-local.php*
    - /PATH TO PROJECT/globelink/environments/dev/console/config/main-local.php*
```
'db' => [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=127.0.01;dbname=globelink',
    'username' => 'globelink',
    'password' => 'globelink',
    'charset' => 'utf8',
],
```
* Install *composer* if you haven't it installed
* After you've set up DB and Web Server, you can run application initialisation scripts:
```
composer install
php init #select dev env and follow ferther instractions
php yii migrate/up
```

* After that you should be able to open [globelink.local](http://globelink.local) in browser

Working with "Coffee Place" app
-------------------------------

* When you first open [globelink.local](http://globelink.local) page, you should be able to see a "Sign Up" form
  * Use this Sign Up form to create your user. If all successful, after that you should be able to see a page with
    a list of available coffee and a form to place ad order for a coffee.
  * Try it out and place an order, this form has validation, so don't be scared to play around with it.
  
* Now, when you have an order placed, you can login to [globelink-backend.local/](http://globelink-backend.local/) - 
  Admin Panel for Coffee Place, user: "Mark", password: "globelink"
  * Now you should be able to see an Admin Section with two tabs - "Coffee", "Orders"
  * "Coffee" tab shows you a list of coffees and allows you to create new one or update existing
  * "Orders" tab allows you to process clients order, which automatically will withdraw an appropriate amount of coffee from the stock

Project Directory Structure
---------------------------

```
common
    config/              contains shared configurations
    mail/                contains view files for e-mails
    models/              contains model classes used in both backend and frontend
    tests/               contains tests for common classes    
console
    config/              contains console configurations
    controllers/         contains console controllers (commands)
    migrations/          contains database migrations
    models/              contains console-specific model classes
    runtime/             contains files generated during runtime
backend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains backend configurations
    controllers/         contains Web controller classes
    models/              contains backend-specific model classes
    runtime/             contains files generated during runtime
    tests/               contains tests for backend application    
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
frontend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains frontend configurations
    controllers/         contains Web controller classes
    models/              contains frontend-specific model classes
    runtime/             contains files generated during runtime
    tests/               contains tests for frontend application
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
    widgets/             contains frontend widgets
vendor/                  contains dependent 3rd-party packages
environments/            contains environment-based overrides
```