<?php

require_once ("./getConnect.php");
//Fonction qui crée un nouvel utilisateur en base de donnée.
function postUser($name, $firstName,$selectedDiets, $selectedAllergens) {
    
    try {
        $pdo = getConnect();
        if ($pdo) {
                    
            // Vérification si l'utilisateur existe déjà
            $checkUserStmt = $pdo->prepare("SELECT COUNT(*) FROM `user` WHERE name = :name AND firstname = :firstName");
            $checkUserStmt->bindParam(':name', $name, PDO::PARAM_STR);
            $checkUserStmt->bindParam(':firstName', $firstName, PDO::PARAM_STR);
            $checkUserStmt->execute();
            $userExists = (bool)$checkUserStmt->fetchColumn();
            
            if ($userExists) {
                // Vérification que l'utilisateur n"existe pas déjà.
                echo json_encode(["success"=>"L'utilisateur existe déjà"]);
            }else{
                $req = "INSERT INTO `user` (name, firstname) VALUES (:name, :firstName)";
                $stmt = $pdo->prepare($req);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':firstName', $firstName, PDO::PARAM_STR);
                $stmt->execute();
                
                // Récupération de l'id du nouvel utilisateur
                $newUserId = $pdo->lastInsertId();
                
                // Insertion des allergènes associés à l'utilisateur
                $stmt = $pdo->prepare("INSERT INTO user_allergen (user_id, allergen_id) VALUES (:user_id, :allergen_id)");
                foreach ($selectedAllergens as $allergenId) {
                    $allergenId = (int)$allergenId;
                    $stmt->bindParam(':user_id', $newUserId, PDO::PARAM_INT);
                    $stmt->bindParam(':allergen_id', $allergenId, PDO::PARAM_INT);
                    $stmt->execute();
                }
                //Insertion du régime alimentaire associés à l'utilisateur
                $stmt = $pdo->prepare("INSERT INTO user_diet(user_id, diet_id) VALUES (:user_id, :diet_id)");
                foreach ($selectedDiets as $dietId) {
                    $dietId = (int)$dietId;
                    $stmt->bindParam(':user_id', $newUserId, PDO::PARAM_INT);
                    $stmt->bindParam(':diet_id', $dietId, PDO::PARAM_INT);
                    $stmt->execute();
                }
                $stmt->closeCursor();
                echo json_encode(["success"=>"Utilisateur ajouté avec succès"]);
            }
               
        } else {
            throw new Exception("La connexion à la base de données a échoué.");
        }
    } catch (Exception $e) {
        // Message d'erreur
       echo json_encode(["error"=>"probleme"]);
    } finally {
        // Fermeture de la connexion PDO.
        if ($pdo) {
            $pdo = null;
        }
    }
}

$data = json_decode(file_get_contents("php://input"), true);

// Vérification si les données nécessaires sont présentes
if (isset($data['name'], $data['firstName'], $data['selectedDiets'], $data['selectedAllergens'])) {
    
    $name = $data['name'];
    $firstName = $data['firstName'];
    $selectedDiets = $data['selectedDiets'];
    $selectedAllergens = $data['selectedAllergens'];
     
    postUser($name,$firstName,$selectedDiets,$selectedAllergens);
    
} else {
    // Géstion du cas où des données requises sont manquantes
    echo json_encode(["error" => "Parametres manquants"]);
}


