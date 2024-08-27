## Spustenie aplikácie

1. K spusteniu aplikácie budeme potrebovať [Docker](https://docs.docker.com/get-started/get-docker/)
2. V terminály / konzole sa presunieme do priečinka s aplikáciou `cd brilo-main`
3. Spustíme `docker compose build --no-cache` na vytvorenie nového imagu aplikácie
4. Spustíme `docker compose up -d --wait` na spustenie služieb
5. Spustíme `migrations` na vytvorenie tabuliek v databáze
6. Spustíme `seed` na naplnenie tabuliek údajmi
7. Otvoríme `https://localhost` a je potrebné prijať automaticky generovaný SSL certifikát
8. K ukončeniu práce s aplikáciou  `docker compose down --remove-orphans`