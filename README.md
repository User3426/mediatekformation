# Mediatekformation
## Présentation
Ce projet est une extension de l'application Mediatekformation originale.<br>
Le dépôt d'origine est accessible ici : [lien_vers_depot_origine]<br>
Le README du dépôt d'origine contient la présentation complète de l'application de base (fonctionnalités front office, structure de la base de données, etc.).<br>
## Fonctionnalités ajoutées au front office
Cette version évoluée du site Mediatekformation ajoute un back office complet permettant aux administrateurs de gérer l'ensemble du contenu du site (formations, playlists et catégories). Les fonctionnalités du front office ont également été enrichies.
### Nouvelle colonne : nombre de formations
Dans la page Playlists, une colonne supplémentaire indique le nombre total de formations associées à chaque playlist.<br>
Cette colonne est triable, comme les autres colonnes du tableau. Le nombre de formation est également affiché dans la page d'une playlist.<br>
<img width="1304" height="821" alt="Capture d’écran 2025-12-01 144216" src="https://github.com/user-attachments/assets/807dc0cf-a3d7-4bf1-8b69-263de864a2fb" />
## Le back office
Le back office est accessible uniquement aux administrateurs authentifiés via l'URL /admin.<br>
Un système de connexion sécurisé protège l'accès aux fonctionnalités d'administration.<br>
Un lien de déconnexion est présent sur toutes les pages du back office.<br>
<img width="1328" height="501" alt="image" src="https://github.com/user-attachments/assets/490c8d5a-1d3f-4d37-a5e0-36835212c31a" />
### Page de gestion des formations
Cette page permet de gérer l'ensemble des formations disponibles sur le site.<br>
Le tableau de gestion affiche toutes les formations avec les mêmes fonctionnalités de tri et filtrage que le front office. Chaque formation dispose d'un bouton "Editer" permettant d'accéder au formulaire de modification, et d'un bouton "Supprimer" avec demande de confirmation. La suppression retire automatiquement la formation de sa playlist. Un bouton "Ajouter une formation" permet de créer une nouvelle formation.<br>
<img width="1315" height="684" alt="image" src="https://github.com/user-attachments/assets/ca8c13e8-a261-4632-9012-d5649c96fea9" />
### Formulaire d'ajout/modification d'une formation
Le formulaire permet de saisir le titre (obligatoire), l'ID vidéo YouTube obligatoire (exemple : pour l'URL https://www.youtube.com/watch?v=dQw4w9WgXcQ, l'ID est dQw4w9WgXcQ), la sélection obligatoire d'une playlist parmi les playlists existantes, une sélection multiple optionnelle de catégories, la date de parution via calendrier qui ne peut pas être postérieure à la date du jour, et une description en texte libre optionnel.<br>
Le formulaire de modification est identique mais prérempli avec les données existantes de la formation.<br>
<img width="1376" height="835" alt="image" src="https://github.com/user-attachments/assets/06eeec86-99a8-40ea-8163-cb227d03f284" />
### Page de gestion des playlists
Cette page permet de gérer l'ensemble des playlists du site.<br>
Le tableau de gestion affiche toutes les playlists avec les fonctionnalités de tri et filtrage. Chaque playlist dispose d'un bouton "Editer" et d'un bouton "Supprimer" avec contrôle de sécurité : une playlist ne peut être supprimée que si elle ne contient aucune formation. Un bouton "Ajouter une playlist" permet d'accéder au formulaire pour ajouter une nouvelle playlist.<br>
<img width="1414" height="818" alt="image" src="https://github.com/user-attachments/assets/b77adea0-a71b-43ca-b7bb-e7279f998530" />
### Formulaire d'ajout/modification
Le formulaire permet de saisir le nom (obligatoire) et la description.<br>
Dans le formulaire de modification, les formations rattachées à la playlist sont affichées en lecture seule. L'ajout ou le retrait de formations dans une playlist se fait exclusivement via le formulaire de gestion des formations.<br>
<img width="1337" height="455" alt="image" src="https://github.com/user-attachments/assets/096d0bb1-966e-4760-86d1-17e099efd9bc" />
<img width="1375" height="769" alt="image" src="https://github.com/user-attachments/assets/664edf80-b6ca-43f2-9b75-46dc9abd6f26" />
### Page de gestion des catégories
Cette page permet de gérer les catégories utilisées pour classer les formations. <br>
Pour chaque catégorie, un bouton permet de la supprimer. Attention, une catégorie ne peut être supprimée que si elle n'est rattachée à aucune formation. <br>
Dans la même page, un mini formulaire permet de saisir et d'ajouter directement une nouvelle catégorie, à condition que le nom de la catégorie n'existe pas déjà. <br>
<img width="1353" height="666" alt="image" src="https://github.com/user-attachments/assets/29905beb-a4ec-4054-a8c8-5f01184551fc" />
### Test de l'application en local
- Vérifier que Composer, Git et Wamserver (ou équivalent) sont installés sur l'ordinateur.
- Télécharger le code et le dézipper dans www de Wampserver (ou dossier équivalent) puis renommer le dossier en "mediatekformation".<br>
- Ouvrir une fenêtre de commandes en mode admin, se positionner dans le dossier du projet et taper "composer install" pour reconstituer le dossier vendor.<br>
- Dans phpMyAdmin, se connecter à MySQL en root sans mot de passe et créer la BDD 'mediatekformation'.<br>
- Récupérer le fichier mediatekformation.sql en racine du projet et l'utiliser pour remplir la BDD (si vous voulez mettre un login/pwd d'accès, il faut créer un utilisateur, lui donner les droits sur la BDD et il faut le préciser dans le fichier ".env" en racine du projet).<br>
- De préférence, ouvrir l'application dans un IDE professionnel. L'adresse pour la lancer est : http://localhost/mediatekformation/public/index.php<br>
### Test de l'application en ligne
L'application est accessible en ligne à l'adresse suivante : https://mediatekformat.kesug.com/mediatekformation/public/
Pour accéder au back office : https://mediatekformat.kesug.com/mediatekformation/public/admin







