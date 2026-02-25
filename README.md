# Silver Academy

A modern Laravel + Inertia.js + Vue 3 application for Silver Academy's marketing website and parent portal.

## Tech Stack

- **Backend**: Laravel 12
- **Frontend**: Vue 3 with Composition API
- **Bridge**: Inertia.js 2.0
- **Styling**: Tailwind CSS 4
- **Authentication**: Laravel Sanctum
- **Database**: Supabase PostgreSQL (configurable)
- **Build Tool**: Vite

## Project Structure

```
silveracademy-com/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/              # Authentication controllers
│   │   │   ├── Marketing/         # Public marketing pages
│   │   │   └── Portal/            # Authenticated parent portal
│   │   └── Middleware/
│   └── Models/
├── resources/
│   ├── js/
│   │   ├── Components/            # Reusable Vue components
│   │   ├── Layouts/               # Page layouts
│   │   │   ├── GuestLayout.vue    # Auth pages layout
│   │   │   ├── MarketingLayout.vue # Public marketing layout
│   │   │   └── PortalLayout.vue   # Parent portal layout
│   │   └── Pages/
│   │       ├── Auth/              # Login, Register pages
│   │       ├── Marketing/         # Home, About, Services, Contact
│   │       └── Portal/            # Dashboard and portal features
│   └── css/
│       └── app.css                # Tailwind CSS with custom theme
├── routes/
│   ├── web.php                    # Main routes
│   └── auth.php                   # Authentication routes
└── vite.config.js
```

## Getting Started

### Prerequisites

- PHP 8.2+
- Composer
- Node.js 18+
- npm

### Installation

1. Clone the repository:
```bash
git clone [repository-url]
cd silveracademy-com
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install Node dependencies:
```bash
npm install
```

4. Copy environment file and configure:
```bash
cp .env.example .env
php artisan key:generate
```

5. Configure your database in `.env`:
```env
# For Supabase PostgreSQL
DB_CONNECTION=pgsql
DB_HOST=db.YOUR_PROJECT_REF.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your-password
```

6. Run migrations:
```bash
php artisan migrate
```

7. Build assets:
```bash
npm run build
```

### Development

Run the development servers:

```bash
# Terminal 1: Laravel server
php artisan serve --port=8000

# Terminal 2: Vite dev server
npm run dev
```

Visit `http://localhost:8000` to view the application.

### Using the remote database (e.g. on a laptop)

The app’s `.env` points at `127.0.0.1:3307`. On your main machine you may have an SSH tunnel or local MySQL already. On a fresh clone (e.g. a laptop) nothing is listening on that port.

**Start the DB tunnel** (run in its own terminal and leave it open):

```bash
./bin/db-tunnel
```

Then start the dev servers as above. The tunnel forwards local port 3307 to MySQL on the Forge server, so the app connects with no other config changes.

## Deployment on Laravel Forge

### Server Requirements

- PHP 8.2+
- Node.js 18+ (for build step)
- PostgreSQL (via Supabase)

### Deploy Script

```bash
cd /home/forge/silveracademy.com
git pull origin main

composer install --no-dev --optimize-autoloader

npm ci
npm run build

php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

php artisan queue:restart
```

### Environment Variables

Set these in Forge's environment tab:
- `APP_ENV=production`
- `APP_DEBUG=false`
- `DB_CONNECTION=pgsql`
- Database credentials from Supabase

## Routes

### Marketing (Public)
- `/` - Home page
- `/about` - About page
- `/services` - Services page
- `/contact` - Contact page

### Authentication
- `/login` - Login page
- `/register` - Registration page (parent approval flow)
- `/parent/signup` - Parent signup with code (email + Parent Code)
- `/logout` - Logout (POST)

### Portal (Authenticated)
- `/portal/dashboard` - Parent dashboard

## Parent Code workflow

### Admin: generating and distributing codes

1. Go to **Grades** in the admin portal, then open a grade.
2. Each student row shows a **Parent Code** column. If no code exists, click **Generate**. To replace an existing code, click **Regenerate** (the old code stops working immediately).
3. After generating or regenerating, the new code is shown **once** in a modal. Use **Copy** to copy it, then share it with the family through a secure channel (e.g. in person or secure message).
4. The column shows the last 4 characters and link count (e.g. `••••1234 (3/5)`). Up to 5 parent accounts can link per student by default; admins can change this under student code settings if needed.

### Parent: registering and adding children

1. **First-time signup:** Open the login page and click **First time? Sign up with a Parent Code**. Enter your email and the Parent Code from the school. Submit the form; you will receive an email with a temporary password and a link to log in. Log in and change your password in Settings.
2. **Adding another child:** After logging in, go to **Settings**. In the "Linked Students" section, enter the other child’s Parent Code and click **Add child**. That child’s grade-level content will appear on your dashboard.
3. **Logging in later:** Use your email and password on the login page (no need to enter the code again).

## Customization

### Brand Colors

The brand colors are defined in `resources/css/app.css`:

```css
@theme {
    --color-brand-500: oklch(0.58 0.14 260);
    --color-brand-600: oklch(0.50 0.16 260);
    /* ... */
}
```

### Adding New Pages

1. Create a controller in `app/Http/Controllers/`
2. Add routes in `routes/web.php`
3. Create Vue page in `resources/js/Pages/`
4. Use appropriate layout (Marketing, Portal, or Guest)

## License

Proprietary - Silver Academy
