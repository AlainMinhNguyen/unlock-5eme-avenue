# docker-compose PHP MariaDB slim4 Boilerplate
```
sudo systemctl stop mysql
```

1. start and get logs

```
docker-compose up
```

2. open an new terminal and get into PHP container

```
docker-compose exec --workdir /app php /bin/bash
```

3. within the PHP container, install compose dependencies

```
composer update
```

4. slim app runs on http://localhost:8080

5. Create database

```
php vendor/bin/doctrine orm:schema-tool:create
```
6. Informations sur le projet
```
    - Pour lancer une partie il faut être sur la page racine 'localhost:8080' et cliquer sur le bouton 'Commencer', crée la base de données pour une nouvelle partie et redirige vers la page de jeu.
    - La partie est créée
    - Pas de gestion des différentes parties, si plusieurs parties sont lancées en même temps, elles se superposent.
    - Pour recommencer une partie il faut vider la base de données et relancer une partie.
    - Si le joueur défausse une carte sans faire exprès il ne peut pas la récupérer.
    - Le joueur peut combiner deux cartes et un numéro / une carte.
    - Le joueur peut piocher une carte dont il ne devrait pas avoir accès.
    
```
