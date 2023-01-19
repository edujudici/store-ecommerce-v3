echo "==============================================="
echo "Project - DEPLOYMENT"
echo "==============================================="

ENV_FILE=.env
if [ ! -f "$ENV_FILE" ]; then
    echo "-----------------------------------------------"
    echo "Project - Copy env file config."
    echo "-----------------------------------------------"
    cp src/.env.example src/.env
fi

echo "-----------------------------------------------"
echo "Project - Building steps and up all containers."
echo "-----------------------------------------------"
docker-compose up -d --build

echo "-----------------------------------------------"
echo "Project - Waiting start all containers..."
echo "-----------------------------------------------"
sleep 15

echo "-----------------------------------------------"
echo "Project - Install/update composer dependecies."
echo "-----------------------------------------------"
docker-compose exec app composer install

echo "-----------------------------------------------"
echo "Project - Generating key."
echo "-----------------------------------------------"
docker-compose exec app php artisan key:generate

echo "-----------------------------------------------"
echo "Project - Artisan is migrating DB."
echo "-----------------------------------------------"
docker-compose exec app php artisan migrate

echo "-----------------------------------------------"
echo "Project - Fill data on DB."
echo "-----------------------------------------------"
docker-compose exec app php artisan db:seed

echo "-----------------------------------------------"
echo "Project - Show all containers."
echo "-----------------------------------------------"
docker ps

echo "-----------------------------------------------"
echo "Project - Build assets using mode development."
echo "-----------------------------------------------"
docker-compose run --rm --service-ports npm run development

echo "-----------------------------------------------"
echo "Project - LIVE."
echo "-----------------------------------------------"
