# blog
Simple Symfony blog Application with CRUD (Create, Read, Update, Delete) functions.
# Installation

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
- Add administrator: run this sql query 

```
INSERT INTO `user` (`id`, `email`, `roles`, `password`) VALUES
(1, 'admin@admin.com', '[\"ROLE_ADMIN\"]', '$2y$13$7YdqvPd./AmWS1MnBVD7seKIMDFDjI4GVgyhsQWrPpxgXkEWWAI5O');
```
- Launch web server
```
symfony serve:start
```
- Connect to the application using  ```/login``` route
```
Credentials:
Email:admin@admin.com
Password:123456
```
