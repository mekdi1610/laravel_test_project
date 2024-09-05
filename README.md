Certainly! Below is a sample `README.md` for your Laravel application, considering the details and functionalities you’ve shared previously.

---

# Laravel Application

## Overview

This Laravel application is designed for managing vehicles and user authentication with role-based access control. It includes features for CRUD operations on vehicles and role-based access restrictions for certain routes.

## Features

- **Role-Based Access Control**: Restrict access to routes based on user roles (e.g., admin).
- **Vehicle Management**: CRUD operations for managing vehicles.
- **User Authentication**: JWT token-based authentication for secure API access.

## Requirements

- PHP 8.1 or higher
- Composer
- Laravel 11.9
- MySQL or any other supported database

## Installation

1. **Clone the Repository**

   ```bash
   git clone https://github.com/mekdi1610/laravel_test_project.git
   cd laravel_test_project
   ```

2. **Install Dependencies**

   ```bash
   composer install
   ```

3. **Set Up Environment**

   Copy the `.env.example` file to `.env`:

   ```bash
   cp .env.example .env
   ```

   Update the `.env` file with your database and other environment configurations.

4. **Generate Application Key**

   ```bash
   php artisan key:generate
   ```

5. **Run Migrations**

   ```bash
   php artisan migrate
   ```

6. **Serve the Application**

   ```bash
   php artisan serve
   ```

## Routes

### Driver Routes

- `GET /vehicles` - List all vehicles
- `GET /vehicles/{id}` - Show details of a specific vehicle

### Admin Routes

- `POST /vehicles` - Create a new vehicle
- `PUT /vehicles/{id}` - Update an existing vehicle
- `DELETE /vehicles/{id}` - Delete a specific vehicle

### Authentication

The application uses JWT tokens for authentication. Ensure you include a valid token in the `Authorization` header for routes that require authentication.

## Middleware

### Role Middleware

- **RoleMiddleware**: Checks if the authenticated user has the required role.

  - `role:admin` - Only accessible to users with the `admin` role.
  - `role:driver` - Only accessible to users with the `driver` role.

## Database Schema

- **users**: Contains user information and role ID.
- **roles**: Contains role information.
- **vehicles**: Contains vehicle information.

### Example Migration Files

**Create Users Table**

```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->string('password');
    $table->foreignId('role_id')->constrained('roles');
    $table->timestamps();
});
```

**Create Roles Table**

```php
Schema::create('roles', function (Blueprint $table) {
    $table->id();
    $table->string('name')->unique();
    $table->timestamps();
});
```

**Create Vehicles Table**

```php
Schema::create('vehicles', function (Blueprint $table) {
    $table->id();
    $table->string('make');
    $table->string('model');
    $table->year('year');
    $table->timestamps();
});
```

## Contributing

1. Fork the repository.
2. Create a feature branch (`git checkout -b feature-branch`).
3. Commit your changes (`git commit -am 'Add new feature'`).
4. Push to the branch (`git push origin feature-branch`).
5. Create a new Pull Request.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

---

Feel free to adjust any details according to your specific application and requirements!
