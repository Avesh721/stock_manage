# Laravel Project Setup Guide

## 1. Install Dependencies

Run the following command to install all required dependencies:

```sh
composer install
```

## 2. Create and Configure `.env` File

- Copy the example environment file and rename it:

```sh
cp .env.example .env
```

- Open the `.env` file and update the database configuration. Uncomment and modify the following lines:

```sh
# Uncomment below for MySQL configuration
DB_CONNECTION=mysql  
DB_HOST=127.0.0.1  
DB_PORT=3306  
DB_DATABASE=stock_man  
DB_USERNAME=root  
DB_PASSWORD=  
```

- Generate an application key:

```sh
php artisan key:generate
```

## 3. Start the Laravel Server

Run the following command:

```sh
php artisan serve
```

## 4. Database Setup

- If the database is not yet imported into MySQL, Laravel will prompt you to create it.
- Alternatively, manually import the database from the provided SQL file in the `database` folder using:

```sh
```

## 5. User Authentication

- Laravel Breeze is used for authentication.
- After successful login or registration, users are redirected to the dashboard (`/dashboard`).
- The dashboard displays product details.

## 6. Dashboard Features

The dashboard allows users to:

- Add a new product
- Edit an existing product
- Delete a product
- Update product quantity

## 7. Stock Movement Tracking

- A **Stock Movement** button is available on the dashboard.
- Clicking this button redirects to the **Stock Movement** page, which tracks:
    - Adding or removing products
    - Editing product names

## 8. Frontend Functionality

- A custom JavaScript file (`public/custom.js`) handles:
    - AJAX requests
    - jQuery-based client-side validation
    - Form submissions and data retrieval

## 9. Database Import

- A database file is available in the `database` folder.
- Import it into MySQL before running the project using:

```sh
```

