# Sipi Asset Recovery Auto-Posting App

## Requirements

The minimal requirements for the applications are those required by Laravel framework:

- PHP >= 5.5.9
- OpenSSL PHP Extension
- PDO PHP Extension
- Mbstring PHP Extension
- Tokenizer PHP Extension
- PHP Composer

While there's no minimum requirement for MySQL, probably 5.5.0 is the reasonable minimum.

We recommend using newest MySQL from 5.7.x branch, and newest PHP, ideally >= 7.0.0. 

**Important! Laravel framework relies on strict type checking, when comparing values (===). This can result in the application working erratically, if the types do not match. Depending on your MySQL/PDO driver, it can return query result numeric columns as strings or (properly) as numeric PHP counterparts. Make sure you're using the "mysqlnd" driver.** 

**For the purpose of CSV processing and file upload, we recommend setting the PHP variables: max_execution_time, max_input_times, memory_limit, upload_max_filesize, post_max_size to higher-than-usual values.**

For Less compilation via Laravel's Elixir, you need:

- Node.JS 
- Gulp (as Node package)

(more information on [Elixir Documentation](https://laravel.com/docs/5.2/elixir))

## Installation

After copying all the files, delete the "node_modules" and "vendor" folders (if they are present). While, in theory, these should work file on a new system, ideally, you should rebuild them on new environment.

- Run "composer update" (will rebuild "vendor" folder)
- Run "composer dump-autoload"
- Run "npm install" (will rebuild "node_modules" folder)
- Run "gulp less" (will rebuild *.less files from resources/assets and put them into public/css)
- Run "artisan config:clear" - clear config caches to be sure
- Run "artisan view:clear" - clear view caches to be sure
- Run "artisan cache:clear" - clear all the other caches to be sure
- Run "artisan clear-compiled" - clear artisan compiled classes
- Run "artisan optimize" - optimize framework

Create and update your target .env file, basing on .env.example. Key changes:

- Set APP_ENV to "production"
- Copy APP_KEY from the example - this is used as a salt for crypt, and migrated database already contains some encrypted data. If you change it, you won't be able to decrypt it.
- Set APP_DEBUG to false.
- Set APP_URL to match your target host/url
- Configure your main DB connection, by setting DB_* properties.
- Configure your legacy DB connection, by setting DB_OLD_* properties. Legacy database is used (ideally once) for migration purposes.
- Configure your sending email account, by setting MAIL_* properties.
- Configure your Amazon S3 bucket, by setting S3_* properties. Region and Bucket Path are already populated.

Prepare your DB. Make a clean database that will match the settings/credentials specified in DB.* properties. 

- Run "artisan migrate:refresh --seed". It will warn you about going into production and ask if you want to do this. Confirm (few times).
- Import database/migrated.sql into the database.

Setup the job/queue processing.

- For this, please read [Running the Queue](https://laravel.com/docs/5.2/queues#running-the-queue-listener) and setup the listener according to your environment/strategy. 
- "artisan queue:work --daemon" is usually the standard option.

And you're ready to go!

## Laravel PHP Framework (5.2)

[![Build Status](https://travis-ci.org/laravel/framework.svg)](https://travis-ci.org/laravel/framework)
[![Total Downloads](https://poser.pugx.org/laravel/framework/d/total.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Stable Version](https://poser.pugx.org/laravel/framework/v/stable.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Unstable Version](https://poser.pugx.org/laravel/framework/v/unstable.svg)](https://packagist.org/packages/laravel/framework)
[![License](https://poser.pugx.org/laravel/framework/license.svg)](https://packagist.org/packages/laravel/framework)

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Laravel attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as authentication, routing, sessions, queueing, and caching.

Laravel is accessible, yet powerful, providing tools needed for large, robust applications. A superb inversion of control container, expressive migration system, and tightly integrated unit testing support give you the tools you need to build any application with which you are tasked.

### Official Documentation

Documentation for the framework can be found on the [Laravel website](http://laravel.com/docs).

### Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](http://laravel.com/docs/contributions).

### Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell at taylor@laravel.com. All security vulnerabilities will be promptly addressed.

### License

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
