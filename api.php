<?php

require_once ("./getConnect.php");

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Credentials: true");

//Fonction sendJSON.
function sendJson($recipe){
    $data = json_encode ($recipe,JSON_UNESCAPED_UNICODE);
    echo $data;
}

//Fonction qui récupére les informations compléte de l'utilisateur(allergene et regime compris).
function getUserID($name, $firstname){
    try{
        $pdo = getConnect();
    if($pdo){
        $req = "SELECT  u.user_id,name, firstname, GROUP_CONCAT(DISTINCT d.diet_name)as regime, GROUP_CONCAT(DISTINCT a.allergen_name)as allergene from  user u 
        LEFT JOIN user_diet ud on u.user_id = ud.user_id
        LEFT JOIN diet d on ud.diet_id = d.diet_id
        LEFT JOIN user_allergen ua on u.user_id = ua.user_id
        LEFT JOIN allergen a on ua.allergen_id = a.allergen_id
        WHERE u.name = :name and u.firstname = :firstname
        GROUP BY u.user_id,u.name,u.firstname";
        $stmt = $pdo->prepare($req);
        $stmt->bindValue(':name', $name,PDO::PARAM_STR);
        $stmt->bindValue(':firstname', $firstname,PDO::PARAM_STR);
        $stmt->execute();
        $userId = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        sendJson($userId);
        
    }else{
        throw new Exception("La connexion à la base de données a échoué.");
    }
} catch (Exception $e) {
    sendJson(['error' => "erreur" . $e->getMessage()]);
} finally {
        // Fermeture de la connexion PDO 
        if ($pdo) {
        $pdo = null;
       }
   }
}
//Fonction récuperation de toutes les recettes avec allergène et régime.
function getRecipes(){
    try{
        $pdo = getConnect();
    if($pdo){
        $req = "SELECT * from recipe r  where r.isVisible = 0 ";
        $stmt = $pdo->prepare($req);
        $stmt->execute();
        $recipe = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        sendJson($recipe);
    }else{
        throw new Exception("La connexion à la base de données a échoué.");
    }
} catch (Exception $e) {
    sendJson(['error' => "erreur" . $e->getMessage()]);
} finally {
        // Fermeture de la connexion PDO 
        if ($pdo) {
        $pdo = null;
       }
   }
}

//Fonction qui récupére les recettes en fonction de l'utilisateur.
function getPersonalRecip($name , $firstname){
    try{
        $pdo = getConnect();  
    if($pdo){
        $req = "SELECT
        r.title,
        r.recipe_id,
        u.name,
        u.firstname,
        r.description,
        r.preparation_time,
        r.break_time,
        r.cooking_time,
        r.ingredient,
        r.step,
        n.note,
        n.opinion,
        GROUP_CONCAT(DISTINCT d.diet_name) AS regime,
        GROUP_CONCAT(DISTINCT a.allergen_name) AS allergen
    FROM
        recipe r
        INNER JOIN recipe_diet rd ON r.recipe_id = rd.recipe_id
        INNER JOIN diet d ON rd.diet_id = d.diet_id
        INNER JOIN user_diet ud ON d.diet_id = ud.diet_id
        INNER JOIN `user` u ON ud.user_id = u.user_id
        INNER JOIN recipe_allergen ra ON r.recipe_id = ra.recipe_id
        INNER JOIN allergen a ON ra.allergen_id = a.allergen_id
        LEFT JOIN notice n ON r.recipe_id = n.recipe_id
        LEFT JOIN `user` user ON n.user_id = user.user_id
        WHERE u.name = :name AND u.firstname = :firstname
        AND d.diet_id IN (
            SELECT diet_id
            FROM user_diet
            WHERE user_id = u.user_id
        )
        AND r.recipe_id NOT IN (
            SELECT recipe_id
            FROM recipe_allergen ra2
            INNER JOIN user_allergen ua ON ra2.allergen_id = ua.allergen_id
            WHERE ua.user_id = u.user_id
        )
        
    GROUP BY
        r.recipe_id, r.title,u.name, u.firstname, r.description, r.preparation_time,r.break_time,r.cooking_time,r.ingredient,r.step,n.note,n.opinion;";
        
        $stmt = $pdo->prepare($req);
        $stmt->bindValue(':name', $name,PDO::PARAM_STR);
        $stmt->bindValue(':firstname', $firstname,PDO::PARAM_STR);
        $stmt->execute();
        $personalRecipe = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        sendJson($personalRecipe);
    }else{
        throw new Exception("La connexion à la base de données a échoué.");
    }
} catch (Exception $e) {
    sendJson(['error' => "erreur" . $e->getMessage()]);
} finally {
        // Fermeture de la connexion PDO 
        if ($pdo) {
        $pdo = null;
       }
   }
}

//Fonction recettes filtrées par regime.
function getRecipesByDiet($diet){
    try{
        $pdo = getConnect();

     if($pdo){
        $req = "SELECT title, description, preparation_time, break_time, cooking_time,
        ingredient, step  ,GROUP_CONCAT(DISTINCT d.diet_name) as regime from recipe r 
        inner join recipe_diet rd on r.recipe_id = rd.recipe_id 
        inner join diet d on rd.diet_id = d.diet_id
        WHERE d.diet_id = :diet
        GROUP BY title";
        $stmt = $pdo->prepare($req);
        $stmt->bindValue(":diet", $diet,PDO::PARAM_INT);
        $stmt->execute();
        $recipe = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        sendJson($recipe);
    }else{
        throw new Exception("La connexion à la base de données a échoué.");
    }
} catch (Exception $e) {
    sendJson(['error' => "erreur" . $e->getMessage()]);
} finally {
        // Fermeture de la connexion PDO 
        if ($pdo) {
        $pdo = null;
       }
   }
}
//Fonction recettes filtrées par allergéne.
function getRecipesByAllergen($allergen){
    try{
        $pdo = getConnect();
    
    if($pdo){
        $req = "SELECT * FROM recipe r 
        inner join recipe_allergen ra on ra.recipe_id = r.recipe_id
        inner join allergen a on a.allergen_id = ra.allergen_id
        WHERE a.allergen_id = :allergen";
        $stmt = $pdo->prepare($req);
        $stmt->bindValue(":allergen",$allergen,PDO::PARAM_INT);
        $stmt->execute();
        $recipe = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        sendJson($recipe);
    }else{
        throw new Exception("La connexion à la base de données a échoué.");
    }
 } catch (Exception $e) {
    sendJson(['error' => "erreur" . $e->getMessage()]);
} finally {
        // Fermeture de la connexion PDO 
        if ($pdo) {
        $pdo = null;
       }
   }
}
//Fonction qui retourne tous les régimes.
function getDiets() {
    try {
        $pdo = getConnect();

        if ($pdo) {
            $req = "SELECT * FROM diet";
            $stmt = $pdo->prepare($req);
            $stmt->execute();
            $diet = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            sendJson($diet);
        } else {
            throw new Exception("La connexion à la base de données a échoué.");
        }
    } catch (Exception $e) {
        sendJson(['error' => "erreur" . $e->getMessage()]);
    } finally {
        // Fermeture de la connexion PDO
        if ($pdo) {
            $pdo = null;
        }
    }
}
//Fonction qui retourne la liste des allergène.
function getAllergens() {
    try {
        $pdo = getConnect();

        if ($pdo) {
            $req = "SELECT * FROM allergen";
            $stmt = $pdo->prepare($req);
            $stmt->execute();
            $diet = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            sendJson($diet);
        } else {
            throw new Exception("La connexion à la base de données a échoué.");
        }
    } catch (Exception $e) {
       sendJson(['error' => "erreur" . $e->getMessage()]);
    } finally {
        // Fermeture de la connexion PDO
        if ($pdo) {
            $pdo = null;
        }
    }
}
//Fonction qui recupére les utilisateurs.
function verifUsers($name, $firstName){
    try {
        $pdo = getConnect();
        if($pdo){
            $req = "SELECT count(*) as nbutilisateur FROM user u WHERE u.name = :name AND u.firstname = :firstname";
            $stmt = $pdo->prepare($req);
            $stmt->bindValue(":name", $name, PDO::PARAM_STR);
            $stmt->bindValue(":firstname", $firstName, PDO::PARAM_STR);
            $stmt->execute();
            $nbUser = $stmt->fetchColumn();
            sendJson($nbUser);
        } else {
            throw new Exception("La connexion à la base de données a échoué.");
        }
    } catch (Exception $e) {
         sendJson(['error' => 'Une erreur s\'est produite lors de la vérification de l\'utilisateur.']);
    } finally {
        // Fermeture de la connexion PDO 
        if ($pdo) {
            $pdo = null;
        }
    }
};
