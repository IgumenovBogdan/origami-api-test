
## About Project

These are the instructions for running a test project to work with the Origami API using Laravel and Vue.js

## Requirements
- Composer

## Installation of the test project
After cloning the project, go to the project folder and use the following commands:
```
cp .env.example .env
composer install
npm install
```

## Running the project
```
npm run dev
php artisan serve
```

You should now be able to browse to http://localhost:8000 and see the project.

## Adding test

Open your browser using the link http://localhost:8000/?entity_data_name=test_1&action_type=add

## Editing test

Open your browser using the link http://localhost:8000/?entity_data_name=test_1&action_type=edit&entity_id=65549fb9d6684fb5500606dc
