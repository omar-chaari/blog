# blog
Simple Symfony blog Application with CRUD (Create, Read, Update, Delete) functions.
# Installation

Download or Clone this repository
- Download or Clone this repository
```
git clone https://github.com/omar-chaari/blog.git
```
- Create a new database
- Edit the ```.env``` file to change the attributes for database to your database configurations (host,username,password etc)
- Open up Command Prompt(CMD) or Terminal in the project directory and run these commands:
```
composer install
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```
- Launch web server
```
symfony serve:start
```
