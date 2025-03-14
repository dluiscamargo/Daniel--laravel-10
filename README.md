
# Setup Docker Laravel 10 com PHP 8.1

### Passo a passo
Clone Repositório
```sh
git clone -b https://github.com/dluiscamargo/Daniel--laravel-10.git
```
```sh
cd Daniel--laravel-10
```


Crie o Arquivo .env
```sh
cp .env.example .env
```


Atualize as variáveis de ambiente do arquivo .env
```dosini
APP_NAME="DanielTest"
APP_URL=http://localhost:8989

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=root

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379
```


Suba os containers do projeto
```sh
docker-compose up -d
```


Acesse o container app
```sh
docker-compose exec app bash
```


Instale as dependências do projeto
```sh
composer install
```


Gere a key do projeto Laravel
```sh
php artisan key:generate
```

Acesse o projeto
```sh
http://localhost:8989
```

Acesse o projeto crud fornecedor mvc
```sh
http://localhost:8989/fornecedores?page=1&
```

Acesse o projeto api REST crud fornecedor (endpoints: Thunder Client VScode)
```sh
POST:http://localhost:8989/api/fornecedores
```
```sh
GET:http://localhost:8989/api/fornecedores/2
```
```sh
DELETE:http://localhost:8989/api/fornecedores/2
```
```sh
PUT:http://localhost:8989/api/fornecedores/2
```
