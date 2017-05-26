# Laravel-based CMS

## Server Requirements
- PHP >= 7.0
- PDO PHP Extension
- Mbstring PHP Extension
- Tokenizer PHP Extension

## Installation

1. Download the project:
```
composer create-project mikelmi/mks-engine
```
```
cd mks-engine
```
2. Configure database connection in `.env` file
3. Set write permissions for folders (and all of its subfolders): `storage/`, `bootstrap/cache/`, `public/files/`.
4. Setup application:
```
php artisan app:install
```
5. Configure virtual host to use folder `public` as DocumentRoot,
or you can run php built-in server via laravel artisan command, e.g.: `php artisan --port=8000`

Administration panel wiil be available by **/admin-panel** url.
Admin credentials you provided during running `app:install` command in step 4.
Default credentials:
_user: **admin@admin.com**_  
_password: **admin**_

