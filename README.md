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
- `/register` - Registration page
- `/logout` - Logout (POST)

### Portal (Authenticated)
- `/portal/dashboard` - Parent dashboard

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
