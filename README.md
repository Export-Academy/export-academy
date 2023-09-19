
# The Export Academy Web Appilication

The Export Academy application was built using the Laravel framework. Before you can run the project in a production environment, make sure you have the following prerequisites installed:

- PHP (version >= 7.4)
- Composer (for managing PHP dependencies)
- Web server
- Database server (MySQL is expected)
- Node.js (for compiling assets)

1. Install PHP dependencies using Composer:

```
composer install --optimize-autoloader --no-dev
```

2. Create a .env file and configure it with your production settings, including your database connection, app key, and any other environment-specific variables, below are the expected env variables that are required.

```
# App Configuration
APP_NAME=<Application Name>
APP_ENV=<environment dev|production>
APP_KEY=<app key>
APP_DEBUG=true
APP_URL=<base url>
DEFAULT_TIMEZONE = UTC
USER_DEFAULT_TIMEZONE = EST

# Database Cofiguration
DB_CONNECTION=
DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

# Mailer Configuration
MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=<from email>
MAIL_FROM_NAME="${APP_NAME}"

# Super Admin User Credentials
SUPER_EMAIL=
SUPER_FIRST_NAME=
SUPER_LAST_NAME=
```

3. Generate the application key:
```
php artisan key:generate
```

4. Run database migrations and seed the database:
```
php artisan migrate --force
php artisan db:seed --force
```

5. Compile and optimize assets:
```
npm install
npm run production
```

6. Configure your web server to serve the Laravel application. Set the document root to the `public` directory within your project.

To run in development run, 
```
php artisan serve
```
