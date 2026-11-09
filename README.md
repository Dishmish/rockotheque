# Rockothèque — TP3

Projet réalisé par Dmytro Makarenko dans le cadre du cours
Programmation Web avancée.

Application PHP orientée objet utilisant une architecture MVC,
Twig et MySQL pour gérer une collection d’albums rock.

## Liens

- Site : https://rockotheque-dmitriy.free.je
- GitHub : https://github.com/Dishmish/rockotheque

## Fonctionnalités

- Consultation des albums, artistes, genres et notes moyennes.
- Connexion et déconnexion avec sessions.
- Deux rôles : client et administrateur.
- Publication d’avis par les utilisateurs connectés.
- Ajout, modification et suppression des albums par l’administrateur.
- Tableau de bord et journal des visites réservés à l’administrateur.
- Téléversement et affichage des pochettes d’albums.

## Sécurité

Les mots de passe sont hachés avec password_hash et vérifiés
avec password_verify. Les droits sont contrôlés côté serveur.

Les formulaires POST utilisent un jeton CSRF. Les requêtes SQL
avec des paramètres utilisent PDO et des requêtes préparées.
Twig assure l’échappement HTML des données affichées.

Les pochettes sont réservées à l’administrateur. Le serveur vérifie
le type MIME, les dimensions et la taille du fichier :
JPEG ou PNG, maximum 2 Mo et 5000 × 5000 pixels.
Un nom aléatoire est généré pour chaque image.

## Organisation

- app/Models : accès aux données.
- app/Controllers : traitement des requêtes et validation.
- app/Views : vues Twig.
- app/Security : vérification de la connexion et des rôles.
- app/Services : téléversement des pochettes.
- public/index.php : contrôleur frontal et routes.
- public/css : styles.
- public/uploads/covers : pochettes.
- config : configuration de la connexion MySQL.
- migrations : modifications de la structure de la base.
- scripts : création des utilisateurs et réinitialisation du mot de passe administrateur en ligne de commande.

## Installation locale

1. Disposer de PHP 8.4 ou supérieur, MySQL et Composer.
   Extensions PHP nécessaires : pdo_mysql, mbstring et fileinfo.
2. Installer les dépendances :

```bash
composer install
```

3. Créer une base MySQL nommée rockotheque avec le jeu de caractères
   utf8mb4, puis importer database.sql dans cette base.

   Attention : cet export remplace les tables qu’il contient.
   Utiliser une base dédiée et sauvegarder les données existantes.

4. Copier config/database.example.php vers config/database.local.php
   et renseigner les paramètres de connexion.
5. Démarrer le serveur depuis la racine du projet :

```bash
php -S localhost:8001 -t public
```

6. Ouvrir http://localhost:8001.

L’export SQL contient déjà la structure du TP3.
Ne pas appliquer les migrations après son import complet.

## Comptes utilisateurs

Les accès de démonstration sont fournis dans le rapport de remise.
Les mots de passe en clair ne sont pas publiés dans ce dépôt.

Pour créer un autre compte depuis un terminal :

```bash
php scripts/create-user.php
```

Pour changer le mot de passe du compte admin :

```bash
php scripts/reset-admin-password.php
```

Ces scripts sont destinés au terminal Ubuntu et ne peuvent pas
être exécutés depuis le navigateur.

## Hébergement Apache

Utiliser public comme racine du site lorsque cela est possible.

Si le projet est placé entièrement dans htdocs, le fichier
.htaccess à la racine dirige les requêtes vers le contrôleur
frontal et permet de servir les styles et les pochettes.

Apache doit autoriser les fichiers .htaccess et la réécriture
des URL. Le dossier public/uploads/covers doit être accessible
en écriture par PHP.

Installer les dépendances avant le transfert :

```bash
composer install --no-dev --optimize-autoloader
```

Transférer également le dossier vendor.
Conserver la configuration propre au serveur dans
config/database.local.php et ne pas la publier dans Git.
