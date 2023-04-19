## PHP api setup
- Install xamp
- go to htdocs folder and create a folder "api"
- paste all the files inside the "api" folder
### Installing composer in 'api' folder
- In ubuntu you simply install composer with the following code as specified at 
https://getcomposer.org/download/
```bash
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === '55ce33d7678c5a611085589f1f3ddf8b3c52d662cd01d4ba75c0ee0459970c2200a51f492d557530c71c15d8dba01eae') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
```
- after installing a file composer.phar will be created that you can mange your libraries of the project
- then install a JWT package for implementing JWT logic
```bash
./composer.phar require firebase/php-jwt
```
#### After installing the library you should see a 'vendor' folder with other files

## Setting up database
API tries to connect to localhost on default myslq port 3306
Username / Password and other configurations can be modified on file *DatabaseConnector.php*

### Using mysql for the sql server
#### In xaml you can use the xamp built in mysql server no need to install
#### there should be a console somewhere in control panel - figure out default username password (google)
- Create Database
```mysql
create database php_page;
```
- Create 'user' table in database for login
```mysql
use php_page;
create table user(id int AUTO_INCREMENT PRIMARY KEY, username varchar(255), password varchar(255));
```
- Insert a test user to test api
```mysql
use php_page;
insert into user (username, password) values ('username', 'password');
```

## Testing API
### You can install Postman to send a test request or create first the FrontEnd then send the request from the page
- Request type: POST
- Form data needed: 'username', 'password'
### Returns
- 200 OK with token - successful login
- 403 Unauthorized - unsuccessful login
