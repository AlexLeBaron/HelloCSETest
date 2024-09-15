# Laravel Dockerized Project

This project is a Laravel application set up with Docker for simplified development and deployment. It includes a PHP environment with Apache and MySQL configured via Docker Compose.

## Prerequisites

Ensure you have the following tools installed on your machine:

- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)

## Installation

### 1. Clone the repository

First, clone the project from GitHub:

```bash
git clone https://github.com/your-username/your-repo.git
cd your-repo
``` 
### 2. Build and start the Docker containers

To build and start the application along with MySQL, run:

```bash
docker compose up -d
``` 
This will build the necessary Docker images and start the containers in the background.

### 3. Install PHP dependencies

Once the containers are up, install the Laravel dependencies via **Composer** inside the **app** container:

```bash
docker compose exec app composer install
``` 
### 4. Set up environment variables

Copy the example environment file and generate an application key:

```bash
cp .env.example .env
docker compose exec app php artisan key:generate
``` 
Make sure to update the **`.env`** file with the correct database information:

```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=root
``` 
### 5. Run migrations

Migrate the database to set up the necessary tables:

```bash
docker compose exec app php artisan migrate
``` 
### 6. Access the application

Once everything is set up, you can access the Laravel application in your browser:

```link
http://localhost:8000
```


If you configured everything correctly, you should see the Laravel welcome page.

## Usage

### Stopping the containers

To stop the running containers:

```bash
docker compose down
``` 
This will stop and remove the containers.

### View logs

To view the logs from the app container:

```bash
docker compose logs app
``` 
For MySQL logs:

```bash
docker compose logs db
``` 
### Running Artisan commands

You can run any Artisan command inside the **app** container:

```bash
docker compose exec app php artisan <command>
``` 
For example, to run migrations:

```bash
docker compose exec app php artisan migrate
``` 
### Running Composer commands

You can also run Composer commands in the **app** container:

```bash
docker compose exec app composer <command>
``` 
For example, to update dependencies:

```bash
docker compose exec app composer update
``` 
## Notes

- Ensure that Docker is running and that your machine can handle the resources required by Docker Compose.
- This setup uses **Apache** as the web server inside the Docker container.
- If you modify the **Dockerfile** or **docker-compose.yml**, rebuild the images with:

```bash
docker compose build
``` 
## Troubleshooting

- If you encounter issues related to file permissions, make sure that the **storage** and **bootstrap/cache** directories are writable:

```bash
docker compose exec app chmod -R 775 storage bootstrap/cache
docker compose exec app chown -R www-data:www-data storage bootstrap/cache
``` 
- If you experience issues with database connections, verify that the `.env` file has the correct database credentials and that the **db** container is running.

## License

This project is open-source and licensed under the [MIT license](https://opensource.org/licenses/MIT).
