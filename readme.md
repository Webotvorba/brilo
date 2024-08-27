## Spustenie aplikácie

1. K spusteniu aplikácie budeme potrebovať [Docker](https://docs.docker.com/get-started/get-docker/)
2. V terminály / konzole sa presunieme do priečinka s aplikáciou `cd brilo-main`
3. Spustíme `docker compose build --no-cache` na vytvorenie nového imagu aplikácie
4. Spustíme `docker compose up -d --wait` na spustenie služieb
5. Spustíme `docker ps`
6. Zobrazí sa tabuľka s kontajnermi: Nájdeme container s názvom app-php v stĺpci IMAGE `(brilo-main-php-1)`
7. Spustíme `docker exec brilo-main-php-1 php bin/console doctrine:migrations:migrate --no-interaction` na vytvorenie tabuliek v databáze
8. Spustíme `docker exec brilo-main-php-1 php bin/console app:seed-data` na naplnenie tabuliek údajmi
9. **`brilo-main-php-1` – v prípade, že IMAGE z kroku 6. má iný názov nahradiť týmto názvom**
10. Otvoríme `https://localhost` a je potrebné prijať automaticky generovaný SSL certifikát
11. K ukončeniu práce s aplikáciou  `docker compose down --remove-orphans`