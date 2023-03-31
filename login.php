<?php

include "jwt_utils.php";

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
    case "POST":
        $postedData = file_get_contents('php://input');
        $data = json_decode($postedData, true);
        $nom = $data['nom'];
        $mot_de_passe = $data['mot_de_passe'];
        $req = $linkpdo->prepare("SELECT * FROM utilisateur WHERE nom = :nom AND mot_de_passe = :mot_de_passe");
        $req->execute(array('nom' => $nom,
                            'mot_de_passe' => $mot_de_passe));
        // Vérification du résultat
        if($req->rowCount() > 0) {
            // L'utilisateur existe dans la base de données
            while ($data = $req->fetch()) {
                $username = $data['nom'];
                $role = $data['role'];
            }
            $headers = array('alg'=>"HS256","typ"=>"JWT");
            $payload = array("username"=>$username, "role"=>$role, "exp"=>(time() + 60));
            $jwt = generate_jwt($headers,$payload,'iutinfo');
            deliver_response(200, "Utilisateur autorisé !", $jwt);
        } else {
            // L'utilisateur n'existe pas dans la base de données
            deliver_response(401, "Utilisateur non-autorisé !", null);
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