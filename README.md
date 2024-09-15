# Laravel Dockerized Project

This project is a Laravel application set up with Docker for simplified development and deployment. It includes a PHP environment with Apache and MySQL configured via Docker Compose.

## Prerequisites

Ensure you have the following tools installed on your machine:

- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)

## Installation

### 1. Clone the repository

Clone the project from GitHub:

```bash
git clone https://github.com/your-username/your-repo.git
cd your-repo
``` 
### 2. Build and start the Docker containers

Simply run:

```bash
docker compose up -d
``` 
That's it! The Laravel application, along with the MySQL database, will be built and started. The application key will be generated, and migrations will be applied automatically.

### 3. Access the application

Once everything is set up, you can access the Laravel application in your browser:

```link
http://localhost:8000
```

## Notes

- The application runs on **Apache** inside the Docker container.
- If you need to manually run any **Artisan** or **Composer** commands, you can do so inside the **app** container:

```bash
docker compose exec app php artisan <command>
docker compose exec app composer <command>
``` 
- If you modify the **Dockerfile** or **docker-compose.yml**, rebuild the images with:

```bash
docker compose build
``` 
## License

This project is open-source and licensed under the [MIT license](https://opensource.org/licenses/MIT).
