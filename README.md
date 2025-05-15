

## Custom Docker & JSON Logging Setup

This repository has been configured to run the Laravel application using Docker. It also demonstrates how to set up Laravel to output logs as single-line JSON to `stdout`, which is ideal for containerized environments and log aggregation.

### Key Features Implemented:

*   **Dockerized Environment**: Uses Docker Compose with services for the PHP application (FPM), Nginx web server, and MySQL database.
    *   Custom `Dockerfile` located in `./docker/Dockerfile`.
    *   Nginx configuration in `./docker/nginx/conf.d/app.conf`.
    *   PHP custom settings in `./docker/php/local.ini` (configured to direct PHP native errors to stderr).
*   **JSON Logging to stdout**: Laravel's logging is configured to output all logs (including exceptions with stack traces) as single-line JSON to `stdout`.
    *   The custom logging channel `stdout_json` is defined in `config/logging.php`.
    *   The logic for this channel is in `app/Logging/CreateJsonStdoutLogger.php`.
*   **Test Endpoint**: A route `GET /test-logs` (defined in `routes/web.php` and `app/Http/Controllers/TestLogController.php`) can be used to trigger various log messages (including an exception) to verify the logging setup.

### How to Run This Repository

1.  **Clone the Repository**:
    ```bash
    git clone https://github.com/CiprianSpiridon/LaravelLogsStdoutJson.git
    cd LaravelLogsStdoutJson
    ```

2.  **Create `.env` File**: Copy the contents from the example provided by the assistant during setup or create your own. Ensure the following are set correctly:
    ```env
    APP_NAME=Laravel
    APP_ENV=local
    APP_KEY= # Will be generated in a later step
    APP_DEBUG=true
    APP_URL=http://localhost

    LOG_CHANNEL=stdout_json
    LOG_LEVEL=debug

    DB_CONNECTION=mysql
    DB_HOST=db # Must match the database service name in docker-compose.yml
    DB_PORT=3306
    DB_DATABASE=laravel # Or your preferred DB name
    DB_USERNAME=sail    # Or your preferred DB username
    DB_PASSWORD=password # Or your preferred DB password
    ```
    *(Ensure you have other standard Laravel .env variables as well.)*

3.  **Build and Start Containers**:
    ```bash
    docker-compose up --build -d
    ```

4.  **Generate Application Key**:
    ```bash
    docker-compose exec app php artisan key:generate
    ```

5.  **Run Database Migrations**:
    ```bash
    docker-compose exec app php artisan migrate
    ```

6.  **Access the Application**:
    *   Open your browser and go to `http://localhost:8000` (or the port mapped for Nginx in `docker-compose.yml`).

7.  **Test Logging**: 
    *   Access `http://localhost:8000/test-logs` in your browser.
    *   View the JSON logs in your terminal by running:
        ```bash
        docker-compose logs -f app
        ```
