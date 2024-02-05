<?php

require_once ("./getConnect.php");
//Fonction qui crée un nouvel avis en base de donnée.
function postNotice($notice){
    try{
        $pdo = getConnect();
        if($pdo){
            $req = "INSERT INTO notice (recipe_id,user_id,note,opinion) VALUES (:recipeId,:userId,:note,:opinion)";
            $stmt = $pdo->prepare($req);
            $stmt->bindParam(':recipeId',$notice['recipeId'], PDO::PARAM_INT);
            $stmt->bindParam(':userId',$notice['user'], PDO::PARAM_INT);
            $stmt->bindParam(':note',$notice['note'], PDO::PARAM_INT);
            $stmt->bindParam(':opinion',$notice['opinion'], PDO::PARAM_STR);
            $stmt->execute();
            $stmt->closeCursor();

            echo json_encode(["succes" =>"Envoyé"]);
        }else{
            throw new Exception("La connexion à la base de données a échoué.");
        }

    }catch(Exception $e){
        return ["error" => "problème"];    
}finally {
    // Fermeture de la connexion PDO si elle est ouverte.
    if ($pdo) {
        $pdo = null;
    }
  }
}

$notice = json_decode(file_get_contents("php://input"), true);

// Vérifiez si les données nécessaires sont présentes
if (isset($notice)) {
    postNotice($notice);
   
} else {
    // Gérez le cas où des données requises sont manquantes
    echo json_encode(["error" => "Paramètres manquants"]);
}