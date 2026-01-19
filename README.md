
# PG-Chatting-App

> The real-time chat experience you've been waiting for. Fast, secure, and built with the modern stack for people who value speed.

[![Lint Status](https://github.com/abu_jandal/PG-chatting-app/actions/workflows/lint.yml/badge.svg)](https://github.com/abu_jandal/PG-chatting-app/actions/workflows/lint.yml)
[![Tests Status](https://github.com/abu_jandal/PG-chatting-app/actions/workflows/tests.yml/badge.svg)](https://github.com/abu_jandal/PG-chatting-app/actions/workflows/tests.yml)

## Features

-   Real-time messaging with WebSockets
-   Secure authentication
-   User profile and settings management
-   Two-factor authentication
-   Admin dashboard for user management
-   Modern, responsive UI

## Tech Stack

**Backend:**
-   [Laravel](https://laravel.com/)
-   [Laravel Reverb](https://laravel.com/docs/master/reverb) for WebSockets
-   [PHPUnit](https://phpunit.de/) for testing

**Frontend:**
-   [Svelte](https://svelte.dev/)
-   [Inertia.js](https://inertiajs.com/)
-   [Vite](https://vitejs.dev/)
-   [Tailwind CSS](https://tailwindcss.com/)
-   [TypeScript](https://www.typescriptlang.org/)

## Getting Started

Follow these instructions to get the project up and running on your local machine.

### Prerequisites

-   PHP >= 8.2
-   Node.js >= 20.x
-   pnpm
-   Composer
-   A database (e.g., MySQL, PostgreSQL)

### Installation

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/abu_jandal/PG-chatting-app.git
    cd PG-chatting-app
    ```

2.  **Install PHP dependencies:**
    ```bash
    composer install
    ```

3.  **Install JavaScript dependencies:**
    ```bash
    pnpm install
    ```

4.  **Set up your environment:**
    -   Copy the example environment file:
        ```bash
        cp .env.example .env
        ```
    -   Generate your application key:
        ```bash
        php artisan key:generate
        ```
    -   Configure your database and other services in the `.env` file.

5.  **Run database migrations:**
    ```bash
    php artisan migrate
    ```

## Usage

To run the application, you need to start the Laravel development server, the Vite development server, and the Reverb WebSocket server.

1.  **Start the Laravel development server:**
    ```bash
    php artisan serve
    ```

2.  **Start the Vite development server:**
    ```bash
    pnpm dev
    ```

3.  **Start the Reverb WebSocket server:**
    ```bash
    php artisan reverb:start
    ```

or you can just run the following and everything will startup automaticly:  
```bash
composer run dev
```

Now, you can access the application at the URL provided by `php artisan serve` (usually `http://127.0.0.1:8000`).

## Linting and Formatting

This project uses ESLint for linting and Prettier for code formatting.

-   **Check for linting errors:**
    ```bash
    pnpm lint
    ```
-   **Check for formatting issues:**
    ```bash
    pnpm format:check
    ```
-   **Fix formatting issues:**
    ```bash
    pnpm format
    ```

## License

This project is open-source and available under the [MIT License](LICENSE).
