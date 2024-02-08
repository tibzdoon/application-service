
## Start up
```
docker compose up -d 
php bin/console doctrine:migrations:migrate
php bin/console init:users
php bin/console lexik:jwt:generate-keypair
symfony server:start --port=8000
```

## Requests
## Client
### Get JWT for CLIENT
```bash
curl --location 'http://127.0.0.1:8000/auth' \
--header 'Content-Type: application/json' \
--data-raw '{
    "email": "client1@mail.com",
    "password": "password"
}'
```

### Create new application
```bash
curl --location 'http://127.0.0.1:8000/api/applications' \
--header 'Content-Type: application/ld+json' \
--header 'Authorization: Bearer %TOKEN%' \
--data '{
    "topic": "test",
    "message": "text"
}'
```

### Get client`s applications
```bash
curl --location 'http://127.0.0.1:8000/api/applications' \
--header 'Authorization: Bearer %TOKEN%'
```

### Get client`s application by ID
```bash
curl --location 'http://127.0.0.1:8000/api/applications/%ID%' \
--header 'Authorization: Bearer %TOKEN%'
```

## Manager
### Get JWT for MANAGER
```bash
curl --location 'http://127.0.0.1:8000/auth' \
--header 'Content-Type: application/json' \
--data-raw '{
    "email": "manager1@mail.com",
    "password": "password"
}'
```

### Get all applications
```bash
curl --location 'http://127.0.0.1:8000/api/applications' \
--header 'Authorization: Bearer %TOKEN%'
```

### Update application status 
status: in_progress, completed
```bash 
curl --location --request PUT 'http://127.0.0.1:8000/api/applications/8' \
--header 'Content-Type: application/ld+json' \
--header 'Authorization: Bearer %TOKEN%' \
--data '{
    "comment": "text3",
    "status": "in_progress"
}'
```
