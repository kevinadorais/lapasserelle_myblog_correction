Pour installer le projet :

1. git clone ADRESSE_DU_REPO
2. Creer votre .env.local avec les données de votre base de données
3. composer i
4. php bin/console doctrine:database:create
5. php bin/console make:migration
6. php bin/console doctrine:migrations:migrate

Bonne navigation ...
