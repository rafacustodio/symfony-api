# Symfony API

Users API users entirely using Symfony allowing users to be created/updated and deleted.  
It's also using SQLite as the main database, but if you need another source it can be easily changed on `.env` file.

## Built

- Symfony 6
- API Platform
- Docker
- Composer
- PHPUnit

## Run

```
docker-compose up --build -d
docker exec -it symfony-users-api_php_1 /usr/bin/composer install
docker exec -it symfony-users-api_php_1 php bin/console doctrine:database:create
docker exec -it symfony-users-api_php_1 php bin/console doctrine:migrations:migrate
```
Access it at http://localhost:8000

## API

### Listing users
- **GET /users** - Listing all users

### Creating users
- **POST /users**
```json
{
    "email": "rafael@rafael.com",
    "firstName": "rafael",
    "lastName": "rafael"
}
```

### Updating users
- **PUT /users/$ID**
```json
{
    "email": "rafael@rafael.com",
    "firstName": "rafael",
    "lastName": "rafael"
}
```

### Deleting users
- **DEL /users/$ID**


## Author

- Rafael Custódio