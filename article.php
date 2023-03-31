<?php

include "C:\wamp64\www\ArticleManager\jwt_utils.php";

// Connexion au serveur MySQL
try {
    $linkpdo = new PDO(
        "mysql:host=localhost;dbname=gestion_articles",
        "root",
        ""
    );
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

header("Content-Type:application/json");

$http_method = $_SERVER['REQUEST_METHOD'];

switch ($http_method) {
    case "GET":
        $bearer_token = '';
        $bearer_token = get_bearer_token();
        // Si l'authentification est réussie
        if(is_jwt_valid($bearer_token)) {
            if(return_role($bearer_token) == "moderator") {
                if (!empty($_GET['id'])) {
                    $id = $_GET['id'];
                    $req = $linkpdo->prepare("SELECT auteur, date_publication, contenu FROM article WHERE id_article = ?");
                    $req->execute(array($id));
                    $row = $req->fetch();
                    deliver_response(200, "Article récupéré avec succès !", $row);
                } else {
                    $req = $linkpdo->prepare("SELECT auteur, date_publication, contenu FROM article");
                    $req->execute();
                    while ($row = $req->fetch()) {  
                        $article = array(
                            'auteur' => $row['auteur'],
                            'date_publication' => $row['date_publication'],
                            'contenu' => $row['contenu']
                        );
                        $articles[] = $article;
                    }
                    deliver_response(200, "Voici la liste des articles !", $articles);
                }
            } elseif(return_role($bearer_token) == "publisher") {
                if (!empty($_GET['id'])) {
                    $id = $_GET['id'];
                    $req = $linkpdo->prepare("SELECT auteur, date_publication, contenu FROM article WHERE id_article = ?");
                    $req->execute(array($id));
                    $row = $req->fetch();
                    deliver_response(200, "Article récupéré avec succès !", $row);
                } else {
                    $req = $linkpdo->prepare("SELECT auteur, date_publication, contenu FROM article");
                $req->execute();
                while ($row = $req->fetch()) {  
                    $article = array(
                        'auteur' => $row['auteur'],
                        'date_publication' => $row['date_publication'],
                        'contenu' => $row['contenu']
                    );
                    $articles[] = $article;
                }
                deliver_response(200, "Voici la liste des articles !", $articles);
                }
            }
        // Si l'utilisateur n'est pas authentifié
        } else {
            if (!empty($_GET['id'])) {
                $id = $_GET['id'];
                $req = $linkpdo->prepare("SELECT auteur, date_publication, contenu FROM article WHERE id_article = ?");
                $req->execute(array($id));
                $row = $req->fetch();  
                if(is_null($row)) {
                    deliver_response(404, "L'article n'éxiste pas !", null);
                } else {
                    deliver_response(200, "Article récupéré avec succès !", $row);
                }
            } else {
                $req = $linkpdo->prepare("SELECT auteur, date_publication, contenu FROM article");
                $req->execute();
                while ($row = $req->fetch()) {  
                    $article = array(
                        'auteur' => $row['auteur'],
                        'date_publication' => $row['date_publication'],
                        'contenu' => $row['contenu']
                    );
                    $articles[] = $article;
                }
                deliver_response(200, "Voici la liste des articles !", $articles);
            }
        }
        break;

    case "POST":
        $bearer_token = '';
        $bearer_token = get_bearer_token();
        // Si l'authentification est réussie
        if(is_jwt_valid($bearer_token)) {
            if(return_role($bearer_token) == "publisher") {
                $postedData = file_get_contents('php://input');
                $data = json_decode($postedData, true);
                $date_publication = date("Y-m-d");
                $auteur = $data['auteur'];
                $contenu = $data['contenu'];    
                $req = $linkpdo->prepare("INSERT INTO article(date_publication, auteur, contenu) VALUES (:date_publication, :auteur, :contenu)");
                $req->execute(array('date_publication' => $date_publication,
                                    'auteur' => $auteur,
                                    'contenu' => $contenu));
                $id = $linkpdo->lastInsertId();
                $req = $linkpdo->prepare("SELECT * FROM article WHERE id_article = ?");
                $req->execute(array($id));
                $row = $req->fetch();
                deliver_response(201, "Article créé avec succès !", $row);
            } else {
                deliver_response(403, "Vous n'êtes pas autorisé à publier un article.", null);
            }
        } else {
            deliver_response(401, "Vous n'êtes pas authentifié.", null);
        }   
        break;

    case "PUT":
        $bearer_token = '';
        $bearer_token = get_bearer_token();
        // Si l'authentification est réussie
        if(is_jwt_valid($bearer_token)) {
            $postedData = file_get_contents('php://input');
            $data = json_decode($postedData, true);
            $id = $data['id'];
            $contenu = $data['contenu'];
            // Si l'utilisateur est un moderateur
            if(return_role($bearer_token) == "moderator") {
                $req = $linkpdo->prepare("UPDATE article SET contenu = :nvcontenu WHERE id_article = :nvid_article");
                $req->execute(array('nvcontenu' => $contenu, 'nvid_article' => $id));
                $req = $linkpdo->prepare("SELECT * FROM article WHERE id_article = ?");
                $req->execute(array($id));
                $row = $req->fetch();
                deliver_response(200, "Article modifié avec succès !", $row);
            // Si l'utilisateur est un publisher
            } elseif(return_role($bearer_token) == "publisher") {
                $req = $linkpdo->prepare("SELECT nom FROM article WHERE id_article = ?");
                $req->execute(array($id));
                while ($data = $req->fetch()) {
                    $username = $data['nom'];
                    // Si l'article appartient au publisher qui souhaite le modifier
                    if(return_username($bearer_token) == $username) {
                        $req = $linkpdo->prepare("UPDATE article SET contenu = :nvcontenu WHERE id_article = :nvid_article");
                        $req->execute(array('nvcontenu' => $contenu, 'nvid_article' => $id));
                        $req = $linkpdo->prepare("SELECT * FROM article WHERE id_article = ?");
                        $req->execute(array($id));
                        $row = $req->fetch();
                        deliver_response(200, "Article modifié avec succès !", $row);
                    } else {
                        deliver_response(403, "Vous n'êtes pas l'auteur de cet article.", null);
                    }
                }
            }
        } else {
            deliver_response(403, "Vous n'êtes pas autorisé à modifier un article.", null);
        }
        break;

    case "DELETE":
        $bearer_token = '';
        $bearer_token = get_bearer_token();
        // Si l'authentification est réussie
        if(is_jwt_valid($bearer_token)) {
            // Si l'utilisateur est un moderateur
            if(return_role($bearer_token) == "moderator") {
                if (!empty($_GET['id'])) {
                    $id = $_GET['id'];
                    $req = $linkpdo->prepare("DELETE FROM article WHERE id_article = ?");
                    $req->execute(array($id));
                    deliver_response(200, "Article supprimé avec succès !", null);
                } else {
                    deliver_response(400, "Requête invalide : veuillez spécifier un ID d'article à supprimer !", null);
                }
            }
        // Si l'utilisateur est un publisher
        } elseif(return_role($bearer_token) == "publisher") {
            $req = $linkpdo->prepare("SELECT nom FROM article WHERE id_article = ?");
            $req->execute(array($id));
            while ($data = $req->fetch()) {
                $username = $data['nom'];
                // Si l'article appartient au publisher qui souhaite le supprimer
                if(return_username($bearer_token) == $username) {
                    if (!empty($_GET['id'])) {
                        $id = $_GET['id'];
                        $req = $linkpdo->prepare("DELETE FROM article WHERE id_article = ?");
                        $req->execute(array($id));
                        deliver_response(200, "Article supprimé avec succès !", null);
                    } else {
                        deliver_response(400, "Requête invalide : veuillez spécifier un ID d'article à supprimer !", null);
                    } 
                } else {
                    deliver_response(403, "Vous n'êtes pas l'auteur de cet article.", null);
                }
            }
        }
        break;
}

function deliver_response($status, $status_message, $data) {
    header("HTTP/1.1 $status $status_message");
    $response['status'] = $status;
    $response['status_message'] = $status_message;
    $response['data'] = $data;
    $json_response = json_encode($response);
    echo $json_response;
}

?>