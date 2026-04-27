## Période d'avancée - 27.04.2026
### Général
J'ai tenté de poser un peu les bases du projet.Je suis parti sur une base multi-app avec différentes app par page et pas sur une SPA, en essayant déjà de comprendre les différentes couches.
### Implémentations backend
Pour ajouter la possibilité de supprimer les sondages, j'ai implémenté la route destroy et la méthode correspondante dans le ApiPollController.

Ensuite, j'ai aussi gérer la logique de la méthode store et sa route associée pour pouvoir créer un nouveau sondage.
### Implémentations frontend
J'ai utilisé le fichier PollTable.vue pour gérer la logique d'affichage du dashboard, ensuite lié à AppPollDashboard.vue. Au sein de PollTable.vue, j'ai implémenté également (pour le moment) la fonction de suppression d'une ligne. Cela sera peut-être amené à changer.

J'ai également créé PollEditor.vue qui me sert de formulaire de création sur ma page, qui va venir cacher le dashboard et afficher un form de création pour ajouter des sondages. La structure de ce fichier devrait me re servir pour la fonctionnalité future de modification d'un sondage.