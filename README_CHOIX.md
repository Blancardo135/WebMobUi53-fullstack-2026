## Période d'avancée - 27.04.2026
### Général
J'ai tenté de poser un peu les bases du projet.Je suis parti sur une base multi-app avec différentes app par page et pas sur une SPA, en essayant déjà de comprendre les différentes couches.
### Implémentations backend
Pour ajouter la possibilité de supprimer les sondages, j'ai implémenté la route destroy et la méthode correspondante dans le ApiPollController.

Ensuite, j'ai aussi gérer la logique de la méthode store et sa route associée pour pouvoir créer un nouveau sondage.
### Implémentations frontend
J'ai utilisé le fichier PollTable.vue pour gérer la logique d'affichage du dashboard, ensuite lié à AppPollDashboard.vue. Au sein de PollTable.vue, j'ai implémenté également (pour le moment) la fonction de suppression d'une ligne. Cela sera peut-être amené à changer.

J'ai également créé PollCreate.vue qui me sert de formulaire de création sur ma page, qui va venir cacher le dashboard et afficher un form de création pour ajouter des sondages. La structure de ce fichier devrait me re servir pour la fonctionnalité future de modification d'un sondage.

## Période d'avancée - 28.04.2026
### Implémentations frontend
Avec peu de temps devant moi, je me suis occupé d'implémenter la fonction et le bouton nécessaire pour copier le lien fictif du sondage. J'ai décidé de l'implémenter comme ceci pour le moment et de l'ajouter dans la colonne action de mon tableau. Je réfléchis aussi pour la prochaine fois à créer un composant pour les boutons, à voir.

## Période d'avancée - 29.04.26
### Implémentations frontend
J'ai créé un nouveau composant PollEdit.vue qui me permet de mettre à jour mon formulaire. Pour l'instant il ressemble vachement à PollCreate.vue et je réfléchis à créer un composant PollForm.vue pour centraliser la logique.

### Implémentations backend
J'ai aussi ajouté une route qui correspond avec la méthode PUT et j'ai modifié le ApiPollController pour qu'il corresponde égalment avec une méthode qui fonctionne. J'ai également modifié la façon de récupérer le poll en ajoutant withOptions pour pallier à un bug et être sur de pouvoir modifier.

## Période d'avancée - 01.05.26
### Implémentation frontend
J'ai décidé de rajouter une vue blade qui serait "vote.blade.php" en plus de celle concernant le dashboard. Ensuite, j'ai repris le pattern existant de poll-dashboard.js et AppPollDashboard.vue pour faire monter l'appplication et avoir un point d'entrée.

Pour ce faire j'ai créé vote.js (point d'entrée) et ensuite AppVotePage.vue (autre app pour un affichage optimale).

J'ai (enfin) décidé de créer un PollForm.vue pour recentraliser la gestion de mes sondages, en y utilisant emit pour essayer de pallier à ces problèmes de PollCreate et PollEdit.
### Implémentation backend
Pour afficher un sondage à partir d'un lien, j'ai déjé créé "VoteController" afin de pouvoir afficher un sondage, avec une unique fonction "show" qui me retourne la vue concernant un vote. En parrallèle, j'ai implémenté une nouvelle route (sans middleware) pour que la vue soit accessible par tout le monde.

## Période d'avancée - 02.05.26
### Implémentation frontend
J'ai continué d'implémenter mon fichier PollForm.vue, qui me sert maintenant de composant principal pour le sondage. J'ai utilisé emit afin de pouvoir envoyer des informations depuis l'enfant (pollform) vers le parent PollCreate.vue que j'ai refactoré avec l'utilisation de PollForm. J'ai donc implémenté des v-model pour transmettre les valeurs et écouter les changements.

## Période d'avancée - 03.05.26
### Implémentations frontend
Puisque je ne peux pas avancer sur le reste de la structure de modification, j'en ai profité pour regarder la structure d'un composant PollVote.vue, qui contiendra la logique de vote.
### Implémentations backend
En attendant le retour du prof sur mes questions front, j'en ai profité pour implémenter les questions backend qui concernent les choix multiples, les changements de votes etc..
Pour ce faire, j'ai du ajouter une route "vote" que j'ai ensuite développer en fonction dans ApiPollController. Cela me permet d'enregistrer côté backend les aspects de votes multiples et de changement de votes.