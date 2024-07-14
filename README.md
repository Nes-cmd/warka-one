# Installation instruction for this project.

### Note that the project requires PHP >8.1,  node >16, npm >9, composer git ofcourse.

### The project is built on the top of laravel(php framework), react(js framework), inertia js and filamentphp are major tools used in the project.

## To begin installation run the following commands
## Step 1

    git clone https://github.com/Nes-cmd/warka-one.git
    
    cd warka-one/core

    composer install

    npm install

## Step 2

### create .env file and copy .env-example to .env

### fill your database information there. regarding about other parameters, I will provide in .env-example

## Step 3

import the database proveded in this repo to your database (don't run php artisan migrate)

    npm run dev

    php artsan serve --port=9000

## Step 4

### To start the project make sure that you first started the `warka-one` project, Because this project uses it for authentication. And it is recomended to start the project on port `9000` 

## If everything is ok, browse `localhost:9000`