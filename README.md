# Task Management

This is simple Laravel web app for task management.
You can create tasks and projects, reorder tasks by drag and drop, and filter tasks by project.

## Requirements

- PHP 8.3+
- Composer
- MySQL

## Setup (local)

1. Clone the project and go to the project folder
2. Install packages:

```bash
composer install
```

3. Copy env file and generate app key:

```bash
cp .env.example .env
php artisan key:generate
```

On Windows PowerShell you can use:

```powershell
Copy-Item .env.example .env
php artisan key:generate
```

4. Create a MySQL database, then set DB values in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_management
DB_USERNAME=root
DB_PASSWORD=
```

5. Run migrations:

```bash
php artisan migrate
```

6. Start the app:

```bash
php artisan serve
```

Open `http://127.0.0.1:8000` in browser.

## Deploy

- Upload or clone the project on the server
- Run `composer install --no-dev`
- Setup `.env` (DB info, `APP_KEY`, set `APP_DEBUG=false`)
- Run `php artisan key:generate` if APP_KEY is empty
- Run `php artisan migrate`
- Point the web server document root to the `public` folder

## Notes

- No login is required for this app
- Frontend libs (Bootstrap, jQuery, etc.) are loaded from CDN
