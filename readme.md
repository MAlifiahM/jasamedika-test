# Travel App Frontend and Backend

This document provides instructions on setting up, running, and building both the frontend and backend of the Travel
App.

---

## Requirements

### Prerequisites

- [Node.js](https://nodejs.org/) (version 16 or later recommended)
- [npm](https://www.npmjs.com/) (comes bundled with Node.js)
- [PHP](https://www.php.net/) (version 8.2 or later)
- [Composer](https://getcomposer.org/) (latest version)
- [Laravel CLI](https://laravel.com/docs/)
- PostgreSQL
- A modern browser for testing the app
- A compatible code editor or IDE (e.g., Visual Studio Code, WebStorm, PHPStorm)

---

## Installation

### Clone the Repository

1. Clone the repository:

   ```bash
   git clone https://github.com/your-repository/travel-app.git
   ```
   Replace `https://github.com/your-repository/travel-app.git` with the actual repository URL.

2. Navigate to the project directory:

   ```bash
   cd travel-app
   ```

### Backend Setup (Laravel)

1. Navigate to the backend application directory (root of `travel-app`):

   ```bash
   cd travel-app
   ```

2. Install Laravel dependencies using Composer:

   ```bash
   composer install
   ```

3. Create a `.env` file (if not already present) by copying the example file:

   ```bash
   cp .env.example .env
   ```

4. Configure database connection settings in the `.env` file (adjust as needed):

   ```env
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_database_user
   DB_PASSWORD=your_database_password
   ```

5. Generate the application key:

   ```bash
   php artisan key:generate
   ```

6. Run database migrations:

   ```bash
   php artisan migrate
   ```

7. (Optional) Seed the database:

   ```bash
   php artisan db:seed
   ```

8. Start the Laravel backend server:

   ```bash
   php artisan serve
   ```

   By default, the backend will be available at `http://127.0.0.1:8000`.

9. Enable Sanctum for API authentication by adding Sanctum's middleware:

   Ensure proper configuration in your Sanctum service provider and the web middleware group as described in Laravel's
   Sanctum documentation.

---

### Frontend Setup (Vue + TypeScript)

1. Navigate to the frontend application directory:

   ```bash
   cd travel-app/frontend
   ```

2. Install Vue dependencies using npm:

   ```bash
   npm install
   ```

3. Start the development server:

   ```bash
   npm run dev
   ```

   By default, the frontend will be available at `http://localhost:5173`.

4. Ensure the environment configuration in `frontend/.env` points to the Laravel backend API:

   Create a `.env` file in the `frontend` directory if not created:

   ```env
   VITE_API_BASE_URL=http://127.0.0.1:8000/api
   ```

---

## Running the Applications

To run both the backend and the frontend applications, follow these steps:

1. **Backend:**
    - Open a terminal window, navigate to the `travel-app` directory, and run:
      ```bash
      php artisan serve
      ```

2. **Frontend:**
    - Open another terminal window, navigate to the `travel-app/frontend` directory, and run:
      ```bash
      npm run dev
      ```

   You should be able to access the application frontend in your browser, and the frontend will communicate with the
   backend API.

---

## Using Postman for API Testing

If you'd like to use Postman to test the API, you can import the Postman collection provided in the repository.

1. Navigate to the `travel-app/postman` directory in the project.
2. Open Postman, then go to the "Import" option.
3. Select the Postman collection file (`.json`) located in the `travel-app/postman` directory.
4. Once imported, ensure the environment variables in Postman (e.g., `base_url`) are set correctly. Typically, the base
   URL should be:

   ```
   http://127.0.0.1:8000/api
   ```

You can now use Postman to test the available API endpoints provided by the backend.

---

## Building for Production

### Backend

To prepare the backend for production, follow Laravel's deployment workflow, including optimizing configuration and
caching:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Consult the [Laravel Deployment Guide](https://laravel.com/docs/deployment) for more details.

### Frontend

To build a production-ready version of the frontend:

```bash
npm run build
```

The production build will be available in the `dist/` directory of the frontend folder.

---

## Scripts Overview

### Backend

- `php artisan serve`: Starts the Laravel development server.
- `php artisan migrate`: Runs database migrations.
- `php artisan db:seed`: Seeds the database with test data.
- `php artisan config:cache`: Optimizes configuration for production.
- `composer install`: Installs Laravel dependencies.

### Frontend

- `npm install`: Installs project dependencies.
- `npm run dev`: Starts the frontend development server.
- `npm run build`: Creates a production-ready build.
- `npm run preview`: Previews the production build locally.

## Contributing

1. Fork the repository.
2. Create a branch for your feature/fix: `git checkout -b feature-name`.
3. Commit your changes: `git commit -m "Add feature description"`.
4. Push to your branch: `git push origin feature-name`.
5. Open a pull request.

---