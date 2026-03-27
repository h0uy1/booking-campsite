# Tam Durian Farm & Campsite Booking Platform 🏕️✨

**🌎 Live Demo:** [https://tam-duran-farm.onrender.com](https://tam-duran-farm.onrender.com)

A high-end, luxury campsite booking platform built with **Laravel 12** and **Tailwind CSS**. Designed to handle real-time reservations, secure checkout flows, and automated receipt generation for a premium guest experience.

## ✨ Key Features
- **Luxury UI/UX:** A bespoke, responsive frontend featuring a monochromatic 'stone' palette, elegant modern typography, and glassmorphism components.
- **Real-Time Booking:** Dynamic campsite availability tracking, conflict prevention, and integrated admin blockout dates.
- **Stripe Checkout:** Secure, PCI-compliant payment processing for all reservations using Stripe webhooks.
- **Automated Email & PDF Receipts:** Built-in integration with the Resend API to send beautifully styled HTML confirmation emails, using `laravel-dompdf` to instantly generate and attach official A4 PDF invoices.
- **Advanced Admin Dashboard:** Comprehensive backend featuring visual 14-day occupancy grids, lifetime KPI tracking, and complete manual booking management.
- **Cloud-Ready:** Includes a highly optimized `Dockerfile` tailored specifically for seamless, memory-efficient deployment on platforms like Render or Fly.io using TiDB Serverless.

## 🛠️ Technology Stack
- **Backend:** Laravel 12 (PHP 8.3+)
- **Frontend:** Laravel Blade, Tailwind CSS v3, Alpine.js, Vite
- **Database:** MySQL (Production on TiDB) / SQLite (Local)
- **Integrations:** Stripe SDK, Resend PHP SDK, DomPDF
- **Deployment:** Docker, Render, Nginx

## 🚀 Local Development Setup

Follow these steps to get the project running on your local machine:

### 1. Clone the repository
```bash
git clone https://github.com/h0uy1/booking-campsite.git
cd booking-campsite
```

### 2. Install Dependencies
Ensure you have PHP 8.2+ and Node.js installed, then run:
```bash
composer install
npm install
```

### 3. Environment Configuration
Copy the example environment file and generate a secure application key:
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure your APIs & Database
Open the `.env` file and configure your local database (e.g., `sqlite` or `mysql`). You will also need to add your API keys to enable checkouts and emails:
```env
STRIPE_KEY=your_stripe_public_key
STRIPE_SECRET=your_stripe_secret_key
STRIPE_WEBHOOK_SECRET=your_webhook_signing_secret

MAIL_MAILER=resend
RESEND_API_KEY=re_your_resend_api_key
```

### 5. Run Migrations & Seeders
This builds your database tables and populates it with a base admin account:
```bash
php artisan migrate --seed
```

### 6. Compile Assets & Start the Server
Compile the Tailwind CSS styles and launch the Laravel development server:
```bash
npm run build    # Or 'npm run dev' to watch for live changes
php artisan serve
```
*Your application will now be live at `http://127.0.0.1:8000`.*

## ☁️ Production Deployment
This repository is pre-configured for Dockerized deployment on **Render.com**. 
1. Select **Docker** as the runtime environment.
2. The provided `Dockerfile` will automatically fetch `webdevops/php-nginx:8.3-alpine`, install Composer dependencies efficiently (`--ignore-platform-reqs`), and optimize the Nginx server.
3. Don't forget to push your compiled `public/build` assets, set `APP_ENV=production`, and configure `$middleware->trustProxies(at: '*');` within `bootstrap/app.php` to prevent HTTPS mixed-content errors!

---
*Developed for Tam Durian Farm & Campsite Enterprise.*
