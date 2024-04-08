# About Track Time Master Manager API

This project is the backbone of the Track Time Master Manager project. It represents the back-end, comprising a Laravel-based API and a MySQL database.

## Quick Start - Docker

To run this project with only a few commands, you'll need Docker installed in your machine.

Start by cloning this repository into a directory of your choice.

First, you'll need to install the `composer` dependencies. Run the following command:

```
docker run --rm -v $(pwd):/app composer/composer update --ignore-platform-reqs
```
Before you build and run the containers, you'll need to create a network:

```
docker network create ttmm_network
```

To build the container images and deploy a multi-group container comprising all of the components **for the first time**, run the following command in the root of the cloned repository:

```
docker compose up --build -d
```

For subsequent deploys, run this command instead:

```
docker compose up -d
```

### Generating the database

You'll need to start by creating the tables which are required for the API to properly work.

For that, with the containers up and running, run the following command:

```
docker compose exec ttmm_api php artisan migrate:fresh
```

You'll also need to generate the authentication keys:

```
docker compose exec ttmm_api php artisan passport:install
```

If you want the database to be populated with test data, run the following command:

```
docker compose exec ttmm_api php artisan db:seed
```

**Congrats!** Your API should now be running!

If you haven't already, check out the [Track Time Master Manager](https://github.com/AlSilDev/TrackTimeMasterManager) project, where you'll find everything to get the front-end and web sockets services running!
