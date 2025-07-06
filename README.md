# Take home challenge

This project is a Laravel application configured to run in Docker. Follow the steps below to set up the project and start development.

## Table of Contents

-   [Prerequisites](#prerequisites)
-   [Installation](#installation)
-   [Environment Configuration](#environment-configuration)
-   [Troubleshooting](#troubleshooting)

## Prerequisites

Before starting, ensure you have the following installed on your system:

-   [PHP](https://www.php.net/) - 8.2
-   [Git](https://git-scm.com/downloads) - Version control for cloning the repository.
-   [Docker](https://docs.docker.com/get-docker/) - Containerization tool.
-   [Docker Compose](https://docs.docker.com/compose/install/) - Tool for defining and running multi-container Docker applications.

## Installation

1. **Clone the Repository:**
   Clone this project into your `/var/www` folder.

    ```bash
    git clone `https://github.com/fadi06/take_home_challenge.git /var/www/take_home_challenge`
    ```

    - After clone the project go to docker folder in the project directory and follow the `Readme.md` file.

## Environment Configuration

    Modify the `.env` file as per your requirements. Important configurations include:

    DB_CONNECTION=mysql
    DB_HOST=db
    DB_PORT=3306
    DB_DATABASE=take_home_page
    DB_USERNAME=root
    DB_PASSWORD=root

### Application URL:

    APP_URL= `http://localhost:8022`

## Troubleshooting

-   Error Connecting to Database: Ensure the database container is running and check the .env configuration.

-   File Permission Issues: Run the following command to fix file permissions:

```bash
sudo chown -R $USER:$USER ./
sudo chown -R www-data:www-data storage bootstrap/cache
```

## MYSQL Permission:

If you face any database permission error attach the database container via this command

```bash
    docker exec -it take_home_challenge_db bash
```

```bash
    chown -R mysql:mysql /var/lib/mysql
    chmod -R 755 /var/lib/mysql
```

## Docker

To configure docker confguration of this project, you need to follow the `Readme.md` inside the docker folder.

Happy Coding!
