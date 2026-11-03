# BookEasy – Appointment Booking System

BookEasy is a lightweight PHP and MySQL appointment booking application designed to run locally with XAMPP. Visitors can browse services, request an appointment, and send a message. An administrator can review bookings and messages and update appointment status.

## Features

- Service listing with duration and price
- Appointment requests with server-side validation, CSRF protection, and duplicate-slot prevention
- Contact form for visitor messages
- Admin dashboard for reviewing appointments and messages
- Appointment status updates: pending, confirmed, and cancelled
- Responsive, plain CSS interface

## Technologies

- PHP with PDO
- MySQL / MariaDB
- HTML5 and CSS
- XAMPP (Apache and MySQL)

## Project structure

```text
booking/
├── admin.php              # Admin login and dashboard
├── book.php               # Appointment request form
├── contact.php            # Contact form
├── index.php              # Home page
├── services.php           # Service catalog
├── schema.sql             # Database and initial service data
├── css/style.css          # Site styles
└── includes/
    ├── config.php         # Loads environment and local settings
    ├── config.example.php # Safe configuration template
    ├── db.php             # PDO connection
    ├── footer.php
    ├── functions.php      # Escaping, CSRF, and form helpers
    └── header.php
```

## Install with XAMPP

1. Place this project directory at `C:\xampp\htdocs\booking` (or your XAMPP `htdocs` directory).
2. Start **Apache** and **MySQL** from the XAMPP Control Panel.
3. Open phpMyAdmin at `http://localhost/phpmyadmin/`.
4. Import `schema.sql` using the Import tab. It creates the `booking_db` database and four starter services. Importing the schema does not remove an existing database; the script uses `CREATE DATABASE IF NOT EXISTS` and creates tables and seed rows, so import it into a fresh database or review it before rerunning against existing tables.
5. Copy `includes/config.example.php` to `includes/config.local.php` and update the database and admin settings. The local config is ignored by Git. Default XAMPP MySQL uses host `localhost`, database `booking_db`, user `root`, and an empty password; set `db_pass` if your MySQL root account has a password.
6. Open `http://localhost/booking/`.

The app reads `includes/config.local.php` when present. Environment variables `BOOKEASY_DB_HOST`, `BOOKEASY_DB_NAME`, `BOOKEASY_DB_USER`, `BOOKEASY_DB_PASS`, and `BOOKEASY_ADMIN_PASS` are also supported. Local file settings take precedence over environment values.

## Pages

- Home: `http://localhost/booking/`
- Services: `http://localhost/booking/services.php`
- Book an appointment: `http://localhost/booking/book.php`
- Contact: `http://localhost/booking/contact.php`
- Admin: `http://localhost/booking/admin.php`

## Admin login setup

Set a private `admin_pass` value in `includes/config.local.php` before using the admin page. The local-only config preserves the current local setting so the XAMPP installation continues to work; change it before sharing or deploying. The example config contains only placeholders and no working credentials. Admin login uses the configured password directly.

## Database preservation

BookEasy does not delete or overwrite your local database during installation. Back up any existing database before importing schema changes. The provided schema creates the database if absent and includes starter service rows; inspect it before re-importing into an already populated database.

