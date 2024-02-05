<?php

require_once("./getConnect.php");
//Fonction qui crée un nouveau règime en base de donnée.
function postDiet($diet){
    try{
        $pdo = getConnect();
        if($pdo){

         //Vérification si le regime existe déjà
         $checkDietStmt = $pdo->prepare("SELECT COUNT(*) from diet WHERE 
         diet_name = :diet");
         $checkDietStmt->bindParam(':diet',$diet,PDO::PARAM_STR);
         $checkDietStmt->execute();
         $dietExists = (bool)$checkDietStmt->fetchColumn();
 
         if($dietExists){
            echo json_encode(["success"=>"ce régime existe déjà."]);
         }else{
             $req = "INSERT INTO diet (diet_name) VALUES (:diet)";
             $stmt = $pdo->prepare($req);
             $stmt->bindParam(':diet', $diet, PDO::PARAM_STR);
             $stmt->execute();
             $stmt->closeCursor();
                echo json_encode(["success"=>"Régime ajouté avec succès"]);
         }
        }else{
                throw new Exception("La connexion à la base de données a échoué.");
        }
                
        }catch(Exception $e){
            echo json_encode (["error"=>"probleme"]);    
        }finally {
             // Fermeture de la connexion PDO.
            if ($pdo) {
                $pdo = null;
            }
        }
}

$diet = json_decode(file_get_contents("php://input"), true);

// Vérification si les données nécessaires sont présentes
if (isset($diet)) {
    postDiet($diet);
     
} else {
    // Géstion du cas où des données requises sont manquantes
       echo json_encode(["error" => "Paramètres manquants"]);
}
