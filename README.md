# Tasks Organizer

A tool who's primary objective is to help its users to manage their tasks

# Instructions

- ## Start the docker environment

```Shell
docker-compose up -d
```

Link to the project : http://localhost

Link to the PHPMyAdmin : https://localhost:8080

- ## Install the composer packages

```Shell
docker exec -it nfe-114-php bash -c 'composer install'
```

- ## Create the database

```Shell
docker exec -it nfe-114-mysql mysql -u root -proot -e "CREATE DATABASE db_project;"
```

- ## Load the database schema

```Shell
docker exec -it nfe-114-php bash -c './vendor/bin/doctrine orm:schema-tool:update --force'
```

- ## Load the data fixtures

```Shell
docker exec -it nfe-114-php bash -c 'php loadDataFixtures.php'
```

- ## (Optional) Access the PHP container's bash in a terminal

```Shell
docker exec -it nfe-114-php bash
```

./vendor/bin/doctrine
./vendor/bin/doctrine-migrations

# Description

## Functional objectives

The many features of the projects will be added in multiple parts

  - ### Part 1 : User management and simple Task management

    - User management with register, login, profile, edit and delete pages.

    - Tasks management with a list by author which will be the homepage and pages for the creation, profile, update and deletion of tasks.

- ## Technical Objectives


