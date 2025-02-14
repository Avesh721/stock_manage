# Laravel Project Setup Guide

## 1. Install Dependencies

Run the following command to install all required dependencies:

```sh
composer install
```

## 2. Start the Laravel Server

Use the command:

```sh
php artisan serve
```

## 3. Database Setup

-   If the database is not yet imported into MySQL, Laravel will prompt you to create it.
-   You can manually import the database from the provided SQL file in the `database` folder.

## 4. User Authentication

-   Laravel Breeze is used for authentication.
-   After successful login or registration, users are redirected to the dashboard (`/dashboard`).
-   The dashboard displays product details.

## 5. Dashboard Features

The dashboard allows users to:

-   Add a new product
-   Edit an existing product
-   Delete a product
-   Update product quantity

## 6. Stock Movement Tracking

-   A Stock Movement button is available on the dashboard.
-   Clicking this button redirects to the Stock Movement page, which tracks:
    -   Adding or removing products
    -   Editing product names

## 7. Frontend Functionality

-   A custom JavaScript file (`public/custom.js`) handles:
    -   AJAX requests
    -   jQuery-based client-side validation
    -   Form submissions and data retrieval

## 8. Database Import

-   A database file is available in the `database` folder.
-   Import it into MySQL before running the project.

---

