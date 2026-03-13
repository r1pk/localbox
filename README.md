<div align="center">
  <h1>LocalBox</h1>
  <p>
    LocalBox is a lightweight file hosting solution for quick file sharing between devices on a local network. Built with <b>PHP 8</b>, <b>Symfony 8</b>, <b>Tailwind CSS 4</b>, and <b>MySQL</b>.
  </p>
  <p>
    <img alt="PHP 8" src="https://img.shields.io/badge/PHP-v8-777BB4?style=for-the-badge&logo=php">
    <img alt="Symfony 8" src="https://img.shields.io/badge/Symfony-v8-000000?style=for-the-badge&logo=symfony">
    <img alt="Tailwind CSS 4" src="https://img.shields.io/badge/Tailwind%20CSS-v4-06B6D4?style=for-the-badge&logo=tailwindcss">
    <img alt="MySQL 9" src="https://img.shields.io/badge/MySQL-v9-4479A1?style=for-the-badge&logo=mysql">
    <img alt="Docker ready" src="https://img.shields.io/badge/Docker-ready-2496ED?style=for-the-badge&logo=docker">
    <img alt="License MIT" src="https://img.shields.io/badge/License-MIT-42b883?style=for-the-badge" >
  </p>
</div>

## Features

- Simple interface for quick file uploads and downloads.
- Automatic upload strategy selection based on file size and server limits.
- Automatic grouping of files uploaded together.
- Admin panel for managing files and users.

## Preview

![LocalBox Preview GIF](https://i.imgur.com/oHalGCZ.gif)

## Requirements

- **PHP**: version 8.4 or higher
- **Composer**: version 2.8 or higher
- **MySQL**: version 9.5 or higher
- **Web Server:** Nginx, Apache, or any other compatible web server
- **Symfony CLI** (optional, for local development)
- **Docker** (optional, for containerized setup)

## Quick start

LocalBox provides a ready-to-use Docker configuration, but it can also be set up manually.

### Docker setup (recommended)

1. Clone the repository:

   ```bash
   git clone https://github.com/r1pk/localbox.git
   cd localbox
   ```

2. Start the Docker environment:

   ```bash
   docker compose up -d --build
   ```

3. Run the setup script inside the PHP container:

   ```bash
   docker compose exec php bash -c "bash /var/www/localbox/setup.sh"
   ```

### Manual setup

1. Clone the repository:

   ```bash
   git clone https://github.com/r1pk/localbox.git
   cd localbox
   ```

2. Run the setup script:

   ```bash
   bash setup.sh
   ```

3. Start the local Symfony server:

   ```bash
   symfony server:start
   ```

   Alternatively, configure your web server (e.g., **Nginx** or **Apache**) to point to the `public` directory.

## Usage

Once the setup is complete, open one of the following URLs in your web browser:

- http://127.0.0.1:8000 - to use the application
- http://127.0.0.1:8000/admin - to access the admin panel and manage uploaded files and user accounts

Default credentials for the admin panel: `admin` / `admin`

## Configuration

LocalBox is configured using environment files. For a quick setup, the default configuration is usually sufficient to run the project.

### Upload storage directory

The `UPLOAD_DIRECTORY` environment variable defines the location where uploaded files will be stored.

```
UPLOAD_DIRECTORY=/var/www/storage
```

Make sure the location exists and that the PHP process has permission to read, write, and delete files.

## Author

**Patryk Krawczyk** - [@r1pk](https://github.com/r1pk)

## License

This project is licensed under the [MIT License](LICENSE.md).
