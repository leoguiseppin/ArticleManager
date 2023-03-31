# Gestion d'articles de blog - API REST

Cette API REST permet la gestion des articles de blog, y compris la publication, la consultation, la modification, la suppression, les likes/dislikes, et l'authentification des utilisateurs. L'API est conçue pour s'appuyer sur une architecture orientée ressources et est implémentée en utilisant des JSON Web Tokens (JWT) pour l'authentification.

## Accès à l'API REST

L'API REST n'est pas disponible en ligne, vous devez donc la télécharger et l'exécuter en local sur votre ordinateur.

### Installation de l'API REST

1. Téléchargez les fichiers PHP de l'API REST depuis le dépôt Github.
2. Déplacez les fichiers dans votre dossier www si vous utilisez WAMP, ou dans le dossier htdocs si vous utilisez XAMPP.
3. Importez la base de données fournie dans le fichier database.sql

### Utilisation de l'API REST

Une fois l'installation terminée, vous pouvez accéder à l'API REST en utilisant l'URL http://localhost/votre-dossier/ArticleManager/.
L'API REST peut être testée en envoyant des requêtes HTTP à partir de Postman ou d'un autre outil de votre choix.

Pour utiliser les fonctionnalités de l'API REST, vous devez d'abord vous authentifier. Pour cela, utilisez la méthode POST avec les paramètres username et password sur l'URL http://localhost/votre-dossier/ArticleManager/login.php. Le serveur renverra un token JWT qui doit être inclus dans le header de chaque requête ultérieure.

## Fonctionnalités principales
### Publication, consultation, modification et suppression des articles de blogs

Pour publier un nouvel article, envoyez une requête POST à l'URL /article. Les paramètres de l'article doivent être inclus dans le corps de la requête. 
Pour consulter tous les articles, envoyez une requête GET à l'URL /article. 
Pour consulter un article spécifique, envoyez une requête GET à l'URL /article/{id} en remplaçant {id} par l'identifiant de l'article que vous souhaitez consulter. 
Pour modifier un article existant, envoyez une requête PUT à l'URL /article/{id} avec les paramètres mis à jour inclus dans le corps de la requête. 
Pour supprimer un article, envoyez une requête DELETE à l'URL /article/{id} en remplaçant {id} par l'identifiant de l'article que vous souhaitez supprimer.

### Authentification des utilisateurs

Pour vous connecter à l'API, envoyez une requête POST à l'URL /login avec vos informations d'identification incluses dans le corps de la requête. Si vos informations d'identification sont correctes, vous recevrez un token JWT valide en réponse. Ce token doit être inclus dans le header de chaque requête que vous envoyez à l'API, dans le champ Authorization.

## Gestion des erreurs

L'API renvoie des erreurs avec des codes d'état HTTP appropriés pour signaler les erreurs. Si une erreur se produit, l'API renvoie un objet JSON contenant une description de l'erreur.
