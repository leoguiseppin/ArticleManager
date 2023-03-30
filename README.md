# Gestion d'articles de blog - API REST

Cette API REST permet la gestion des articles de blog, y compris la publication, la consultation, la modification, la suppression, les likes/dislikes, et l'authentification des utilisateurs. L'API est conçue pour s'appuyer sur une architecture orientée ressources et est implémentée en utilisant des JSON Web Tokens (JWT) pour l'authentification.

## URL d'accès à l'API REST

L'URL d'accès à l'API REST est : https://votre-domaine.com/api/v1/

## Authentification

Pour utiliser les fonctionnalités de l'API, vous devez vous authentifier en utilisant un token JWT valide. Pour ce faire, vous devez effectuer une demande de connexion en envoyant votre nom d'utilisateur et votre mot de passe. Si les informations d'identification sont correctes, vous recevrez un token JWT valide en réponse. Ce token doit être inclus dans le header de chaque requête que vous envoyez à l'API, dans le champ Authorization.

Voici un exemple de header de requête avec le token JWT :

makefile

Authorization: Bearer <votre-token-JWT>

## Fonctionnalités principales
### Publication, consultation, modification et suppression des articles de blogs

Pour publier un nouvel article, envoyez une requête POST à l'URL /articles. Les paramètres de l'article doivent être inclus dans le corps de la requête. Pour consulter tous les articles, envoyez une requête GET à l'URL /articles. Pour consulter un article spécifique, envoyez une requête GET à l'URL /articles/{id} en remplaçant {id} par l'identifiant de l'article que vous souhaitez consulter. Pour modifier un article existant, envoyez une requête PUT à l'URL /articles/{id} avec les paramètres mis à jour inclus dans le corps de la requête. Pour supprimer un article, envoyez une requête DELETE à l'URL /articles/{id} en remplaçant {id} par l'identifiant de l'article que vous souhaitez supprimer.

### Authentification des utilisateurs

Pour vous connecter à l'API, envoyez une requête POST à l'URL /auth/login avec vos informations d'identification incluses dans le corps de la requête. Si vos informations d'identification sont correctes, vous recevrez un token JWT valide en réponse. Ce token doit être inclus dans le header de chaque requête que vous envoyez à l'API, dans le champ Authorization.

### Likes/dislikes des articles

Pour liker ou disliker un article, envoyez une requête POST à l'URL /articles/{id}/likes ou /articles/{id}/dislikes en remplaçant {id} par l'identifiant de l'article que vous souhaitez liker ou disliker.

## Gestion des erreurs

L'API renvoie des erreurs avec des codes d'état HTTP appropriés pour signaler les erreurs. Si une erreur se produit, l'API renvoie un objet JSON contenant une description de l'erreur.

Voici un exemple de réponse d'erreur :

css

HTTP/1.1 404 Not Found
Content-Type: application/json

{
  "error": {
    "code": "not_found",
    "message": "L
