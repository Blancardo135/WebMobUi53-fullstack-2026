# HEIG-VD WebMobUi - Projet fullstack printemps 2026

## Pré-requis

Afin de lancer ce projet, une stack compatible avec Laravel, est requise.

Voici les pré-requis nécessaires :

- PHP >= 8.0.
- Composer.
- Node.js et npm.
- Une base de données (MySQL, PostgreSQL, SQLite, etc.).
- Un serveur web (Apache, Nginx, etc.).

[Laravel Herd](https://helm.sh/docs/charts/laravel/) est recommandé pour une installation facile de Laravel et de ses dépendances.

## Développement local

Pour développer et tester le mini-projet en local, voici les étapes à suivre :

1. Forker ce dépôt

2. Installer les dépendances avec npm et Composer :

    ```bash
    npm install && npm run build

    composer install
    ```

3. Copier le fichier `.env.example` en `.env`.
4. Modifier les variables d'environnement si nécessaire (optionnel).
5. Générer la clé d'application Laravel :

    ```bash
    php artisan key:generate
    ```

6. Créer le lien symbolique pour les fichiers téléversés :

    ```bash
    php artisan storage:link
    ```

7. Créer la base de données et exécuter les migrations :

    ```bash
    php artisan migrate
    ```

    S'il est nécessaire de réinitialiser la base de données, utiliser la commande `php artisan migrate:reset` puis `php artisan migrate` à nouveau.

8. Optionnel : en mode développement, il est possible de peupler la base de données avec des données fictives :

    ```bash
    php artisan db:seed
    ```

9. Démarrer le serveur de développement Laravel :

    ```bash
    composer run dev
    ```

L'application sera accessible à l'adresse <http://127.0.0.1:8000>.

-------------------------------------------------------------------
# Directives nécessaires et choix techniques
## Accéder aux sondages
URL : http://127.0.0.1:8000/polls/dashboard
Compte : john.doe@example.com
Mot de passe : password

## Stack technique 
- Backend : Laravel 12
- Frontend : Vue.js 3 et Tailwind CSS
(Aucune librairie externe côté frontend.)

## Architecture frontend
J'ai choisi une architecture multi-app, avec deux applications distinctes, montées sur deux pages Blade séparées plutôt qu'une SPA unique. J'ai fais ce choix pour me faciliter la tâche et afin de pouvoir gérer séparement mon dashboard et mes votes. Cela facilite notamment l'accès, qui est parfois permis sans authentification sur les pages de votes.

## Choix techniques
- Navigation gérée par un store (route.js)
- Isolation de la logique de vote dans un store (voteStore.js) pour faciliter la réutilisation
- Utilisation d'emit + v-model dans PollForm.vue
    -> PollCreate et PollEdit partagent le même formulaire via PollForm.vue. La communication parent->enfant se fait via props et l'enfant remonte les changements via emit('update:...'), qui permet l'usage du v-model côté parent. Cela me permet d'éviter de la duplication.
- BaseButton.vue et BaseModal.vue sont deux composants réutilisés à plusieurs endroits dans l'application. Leur implémentation me donne une base réutilisable pour le reste.

## Endpoints API
| Méthode | Route | Auth | Description |
|---|---|---|---|
| GET | `/api/v1/polls` | Oui | Liste mes sondages |
| POST | `/api/v1/polls` | Oui | Créer un sondage |
| PUT | `/api/v1/polls/{poll}` | Oui | Modifier (brouillon uniquement) |
| DELETE | `/api/v1/polls/{poll}` | Oui | Supprimer |
| GET | `/api/v1/polls/{token}` | Non | Afficher par token |
| POST | `/api/v1/polls/{poll}/vote` | Oui | Voter |
| GET | `/api/v1/polls/{token}/results` | Non | Résultats (publics ou propriétaire) |

## Utilisation de l'intelligence artificielle
L'IA a été utilisée dans ce projet, notamment Claude Opus 4.7 et Claude Haiku 4.5 (via le mode ask). Cela m'a été utile pour gagner du temps sur des parties chronophages et pour faire de la gouvernance afin de s'assûrer qu'il n'y ait pas d'oubli.

### Bonus
J'ai implémenté cela côté backend avec allow_vote_change dans ApiVoteController en autorisant le changement de vote lors de la création ou modification d'un sondage.

