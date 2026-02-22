<div align="center">
  <h1>LocalBox</h1>
  <p>
    LocalBox is a lightweight and easy-to-use file hosting solution designed for quick file sharing between devices on a local network. Built with <b>PHP 8</b>, <b>Symfony 8</b>, <b>Tailwind 4</b>, and <b>MySQL</b>.
  </p>
  <p>
    <img alt="PHP 8" src="https://img.shields.io/badge/PHP-v8-777BB4?style=for-the-badge&logo=php">
    <img alt="Symfony 8" src="https://img.shields.io/badge/Symfony-v8-000000?style=for-the-badge&logo=symfony">
    <img alt="Tailwind CSS 4" src="https://img.shields.io/badge/Tailwind%20CSS-v4-06B6D4?style=for-the-badge&logo=tailwindcss">
    <img alt="MySQL 9" src="https://img.shields.io/badge/MySQL-v9-4479A1?style=for-the-badge&logo=mysql">
    <img alt="Docker supported" src="https://img.shields.io/badge/Docker-supported-2496ED?style=for-the-badge&logo=docker">
    <img alt="License MIT" src="https://img.shields.io/badge/License-MIT-42b883?style=for-the-badge" >
  </p>
</div>

## Features

- Simple and intuitive user interface for fast file uploads and downloads.
- Automatic selection of the optimal upload strategy (chunked or single-request) based on file size and server limits.
- Automatic grouping of simultaneously uploaded files into logical sets.
- Administrative panel for managing uploaded files.

## Preview

![LocalBox Preview GIF](https://i.imgur.com/oHalGCZ.gif)

## Requirements

LocalBox requires the following components:

### Mandatory

- `PHP >= 8.4`
- `Composer >= 2.8`

### Recommended

The following components are recommended for a typical production setup.

- `nginx` (or any compatible web server such as `apache`)
- `MySQL >= 9.5`

## Quick Start

LocalBox provides a ready-to-use docker environment and can also be set up manually.  
Using docker is recommended for the fastest and most consistent setup.

### Docker Setup (Recommended)

1. Clone the repository:

   ```bash
   git clone https://github.com/r1pk/localbox.git
   cd localbox/docker
   ```

2. Start the docker environment:

   ```bash
   docker compose up -d --build
   ```

3. Run the setup script inside the PHP container:

   ```bash
   docker compose exec php bash /var/www/localbox/setup.sh
   ```

Once the setup is complete, open `http://127.0.0.1:8000` in your browser to start uploading and downloading files.

To manage uploaded files, open `http://127.0.0.1:8000/admin` and sign in using the default administrator credentials:

- **Username:** `admin`
- **Password:** `admin`

### Manual Setup

Follow these steps to run the project without docker.

1. Clone the repository:

   ```bash
   git clone https://github.com/r1pk/localbox.git
   cd localbox
   ```

2. Install PHP dependencies:

   ```bash
   composer install
   ```

3. Configure the database schema:

   ```bash
   php bin/console doctrine:schema:update --force
   ```

4. Build frontend assets (Tailwind via AssetMapper):

   ```bash
   php bin/console tailwind:build
   ```

5. Clear the cache:

   ```bash
   php bin/console cache:clear
   ```

6. Load data fixtures:

   ```bash
   php bin/console doctrine:fixtures:load --no-interaction
   ```

7. Start the local Symfony server:

   ```bash
   symfony server:start
   ```

   Alternatively, configure your web server (e.g., nginx or apache) to point to the `public/` directory.

Once the setup is complete, open `http://127.0.0.1` in your browser to start uploading and downloading files.

To manage uploaded files, open `http://127.0.0.1/admin` and sign in using the default administrator credentials:

- **Username:** `admin`
- **Password:** `admin`

## Configuration

LocalBox follows the standard Symfony environment configuration, using `.env` files for all settings.  
Most framework-related parameters retain their default values, so no additional changes are required for typical setup.

### Upload Storage Directory

This variable defines the absolute path where uploaded files are stored.

```
UPLOAD_DIRECTORY=/var/www/storage
```

Before running the application, ensure that:

- The directory exists.
- The PHP process has write and delete permissions.

To customize the location, adjust this value in `.env` or `.env.local`.

## Author

- **Patryk Krawczyk** - [@r1pk](https://github.com/r1pk)

## License

This project is licensed under the [MIT License](LICENSE.md).
