## Spustenie aplikácie

1. K spusteniu aplikácie budeme potrebovať [Docker](https://docs.docker.com/get-started/get-docker/)
2. V terminály / konzole sa presunieme do priečinka s aplikáciou `cd brilo`
3. Spustíme `docker compose build --no-cache` na vytvorenie nového imagu aplikácie
4. Spustíme `docker compose up -d --wait` na spustenie služieb
5. Súbor `compose.yaml` je nakonfigurovaný tak, aby spustil migrácie a seed databázy `php bin/console doctrine:migrations:migrate --no-interaction && php bin/console app:seed-data`
6. Otvoríme `https://localhost` a je potrebné prijať automaticky generovaný SSL certifikát
7. K ukončeniu práce s aplikáciou  `docker compose down --remove-orphans`