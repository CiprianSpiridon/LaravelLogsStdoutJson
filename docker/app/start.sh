#!/bin/sh

# Function to cleanup processes on exit
cleanup() {
    echo "Stopping processes..."
    # Get PIDs of all background jobs and kill them
    # Then kill the main Octane process which is the parent of this script
    if [ -n "$(jobs -p)" ]; then
        kill $(jobs -p)
    fi
    # The exec call replaces the shell process with octane, 
    # so a SIGTERM to this script's original PID (if it were not exec'd)
    # or to the parent of the actual octane process is needed.
    # The trap is inherited by child processes, but killing jobs -p is most direct.
    exit 0
}

# Trap SIGTERM and SIGINT to call the cleanup function
trap cleanup SIGTERM SIGINT

# Ensure storage/logs directory exists
mkdir -p storage/logs

# Clear any existing horizon.pid (optional, Horizon manages its own pid)
# rm -f storage/logs/horizon.pid 

# Wait for the database to be ready (important for Horizon and Octane)
# This is a basic example; consider a more robust wait-for-it script or healthcheck in docker-compose
# echo "Waiting for database connection..."
# RETRIES=10
# while ! php artisan db:table --quiet && [ $RETRIES -gt 0 ]; do
#   RETRIES=$((RETRIES-1))
#   echo "Waiting for database to be ready... ($RETRIES retries left)"
#   sleep 3
# done

# if [ $RETRIES -eq 0 ]; then
#   echo "Database not ready after multiple attempts. Exiting."
#   exit 1
# fi
# echo "Database connected."

# # Run migrations (optional, but often needed on startup)
# php artisan migrate --force

# Start Horizon in the background
# Outputting to stdout/stderr so it goes to Docker logs
echo "Starting Horizon..."
php artisan horizon &

# Start Octane with FrankenPHP in the foreground
# This will be the main process for the container
echo "Starting Octane with FrankenPHP..."
exec php artisan octane:start --server=frankenphp --host=0.0.0.0 --port=80 --admin-port=2019 --workers=auto --task-workers=auto --log-level=debug 