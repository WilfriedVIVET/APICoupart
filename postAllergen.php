<?php

require_once("./getConnect.php");
//Fonction qui crée un nouvel allergène en base de donnée.
function postAllergen($allergen){
    try{
        $pdo = getConnect();
        if($pdo){

        //Vérifier si l'allergène existe déjà
        $checkAllergenStmt = $pdo->prepare("SELECT COUNT(*) from allergen WHERE 
        allergen_name = :allergen");
        $checkAllergenStmt->bindParam(':allergen',$allergen,PDO::PARAM_STR);
        $checkAllergenStmt->execute();
        $allergenExists = (bool)$checkAllergenStmt->fetchColumn();

        if($allergenExists){
            echo json_encode(["success" =>"Cet allergène existe déjà"]);
        }else{
           $req = "INSERT INTO allergen (allergen_name) VALUES (:allergen)";
           $stmt = $pdo->prepare($req);
           $stmt->bindParam(':allergen', $allergen, PDO::PARAM_STR);
           $stmt->execute();
           $stmt->closeCursor();
                
                echo json_encode(["success" =>"Allergène ajouté avec succès"]);
            }
            }else{
                throw new Exception("La connexion à la base de données a échoué.");
            }
            
    }catch(Exception $e){
        echo json_encode(["error"=>"probléme"]);    
}finally {
    // Fermeture de la connexion PDO 
    if ($pdo) {
        $pdo = null;
    }
 }
}

$allergen = json_decode(file_get_contents("php://input"), true);

// Vérifiez si les données nécessaires sont présentes
if (isset($allergen)) {
    postAllergen($allergen);
} else {
    // Géstion du cas où des données requises sont manquantes
    echo json_encode(["error" => "Paramètres manquants"]);
}
