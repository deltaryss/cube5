# Vide Grenier en Ligne

Ce Readme.md est à destination des futurs repreneurs du site-web Vide Grenier en Ligne.

## Mise en place du projet back-end

1. Créez un VirtualHost pointant vers le dossier /public du site web (Apache)
2. Importez la base de données MySQL (sql/import.sql)
3. Connectez le projet et la base de données via les fichiers de configuration
4. Lancez la commande `composer install` pour les dépendances

## Mise en place du projet front-end
1. Lancez la commande `npm install` pour installer node-sass
2. Lancez la commande `npm run watch` pour compiler les fichiers SCSS

## Routing

Le [Router](Core/Router.php) traduit les URLs. 

Les routes sont ajoutées via la méthode `add`. 

En plus des **controllers** et **actions**, vous pouvez spécifier un paramètre comme pour la route suivante:

```php
$router->add('product/{id:\d+}', ['controller' => 'Product', 'action' => 'show']);
```


## Vues

Les vues sont rendues grâce à **Twig**. 
Vous les retrouverez dans le dossier `App/Views`. 

```php
View::renderTemplate('Home/index.html', [
    'name'    => 'Toto',
    'colours' => ['rouge', 'bleu', 'vert']
]);
```
## Models

Les modèles sont utilisés pour récupérer ou stocker des données dans l'application. Les modèles héritent de `Core
\Model
` et utilisent [PDO](http://php.net/manual/en/book.pdo.php) pour l'accès à la base de données. 

```php
$db = static::getDB();
```

## Lancer le projet avec xampp  
Pour lancer le projet avec xampp, il faut:   
mettre le dossier du projet dans le dossier htdocs de xampp;  
mettre "DB_HOST=localhost" dans le fichier /App/Config.php;  
lançer xampp et lancer apache et mysql;  
aller sur http://localhost/phpmyadmin et créer une nouvelle base de données nommée "videgrenier";  
importer le fichier ``sql/import.sql`` dans la base de données videgrenier;  
aller sur http://localhost/videgrenier/public/ pour accéder au site;  

## Lancer le projet avec docker
Pour lancer le projet avec docker, il faut:  
mettre "DB_HOST=database" dans le fichier /App/Config.php si ce n'est pas déjà le cas;  
lancer docker puis lancer la commande ``docker-compose -f docker-compose.main.yml up -d --build`` dans le dossier du projet (changer le fichier docker-compose.main.yml en docker-compose.dev.yml pour lancer le projet en mode developpement)(la commande peux prendre du temps ou echouer du aux dépot debian, il faut la relancer jusqu'à ce qu'elle fonctionne);  
Se rendre sur http://localhost pour accéder au site;  
La base de données est déjà configurée et remplie, vous pouvez y accéder avec phpmyadmin sur http://localhost:8080;

## Lancer les tests
Pour lancer les tests en local, il faut:  
Ouvrir un terminal dans le dossier du projet;
Executer la commande ``.\vendor\bin\phpunit tests --color ``