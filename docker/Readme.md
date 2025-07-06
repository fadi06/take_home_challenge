# Take home challenge

## Overview

This project is a Laravel application configured to run in Docker. Follow the steps below to set up the project and start development.

## Prerequisites

Before you begin, ensure you have the following installed:

-   Docker
-   Docker Compose

## Environment Variables

Ensure your `.env` file is configured correctly. The required environment variables are already set, but you can modify them as needed.

## Setup Instructions

Follow these steps to set up the project:

1. **Navigate to Your Project Directory**

    Open your terminal and navigate to the project directory where your `docker-compose.yml` file is located.

    ```bash
    cd /path/to/your/project/docker
    ```

2. **Build the Docker Containers**

    Run the following command to build the Docker containers:

    ```bash
    docker-compose build
    ```

3. **Start the Docker Containers**

    Start the containers in detached mode using:

    ```bash
    docker-compose up -d
    ```

4. **Accessing the Docker Container**

    If you need to enter the PHP service container, use the following command:

    ```bash
    docker exec -it take_home_challenge_php_service bash
    ```

5. **Run Migrations and Seed the Database**

    Once inside the container, you can run your migrations and seed the database with the following commands:

    ```bash
    php artisan migrate
    php artisan db:seed
    ```

## Supervisor Configuration

Supervisor is included in this configuration. You can add additional configuration files to the supervisor directory. A sample config file is already included to help you get started.
