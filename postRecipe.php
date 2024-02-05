<?php

require_once("./getConnect.php");
//Fonction qui crée une nouvelle recette en base de donnée.
function postRecipe($title, $description, $preparationTime, $breakTime, $cookingTime, $ingredient, $step, $isVisible, $selectedDiets, $selectedAllergens)
{
    try {
        $pdo = getConnect();
        if ($pdo) {
            // Vérification si le titre de la recette existe déjà
            $checkRecipeStmt = $pdo->prepare("SELECT COUNT(*) FROM recipe WHERE title = :title");
            $checkRecipeStmt->bindParam(':title', $title, PDO::PARAM_STR);
            $checkRecipeStmt->execute();
            $recipeExists = (bool) $checkRecipeStmt->fetchColumn();

            if ($recipeExists) {
                echo json_encode(["success" =>"Cette recette existe déjà."]);
            } else {
                // Insertion des données de base de la recette
                $req = "INSERT INTO recipe(title, description, preparation_time, break_time, cooking_time, ingredient, step, isVisible) VALUES (:title, :description, :preparationTime, :breakTime, :cookingTime, :ingredient, :step, :isVisible)";
                $stmt = $pdo->prepare($req);
                $stmt->bindParam(':title', $title, PDO::PARAM_STR);
                $stmt->bindParam(':description', $description, PDO::PARAM_STR);
                $stmt->bindParam(':preparationTime', $preparationTime, PDO::PARAM_INT);
                $stmt->bindParam(':breakTime', $breakTime, PDO::PARAM_INT);
                $stmt->bindParam(':cookingTime', $cookingTime, PDO::PARAM_INT);
                $stmt->bindParam(':ingredient', $ingredient, PDO::PARAM_STR);
                $stmt->bindParam(':step', $step, PDO::PARAM_STR);
                $stmt->bindParam(':isVisible', $isVisible, PDO::PARAM_INT);
                $stmt->execute();

                // Récupération de l'ID de la recette créée
                $newRecipeId = $pdo->lastInsertId();

                // Insertion des allergènes associés à cette recette
                $stmtAllergen = $pdo->prepare("INSERT INTO recipe_allergen (recipe_id, allergen_id) VALUES (:recipe_id, :allergen_id)");
                foreach ($selectedAllergens as $allergenId) {
                    $allergenId = (int) $allergenId;
                    $stmtAllergen->bindParam(':recipe_id', $newRecipeId, PDO::PARAM_INT);
                    $stmtAllergen->bindParam(':allergen_id', $allergenId, PDO::PARAM_INT);
                    $stmtAllergen->execute();
                }

                // Insertion des régimes alimentaires associés à cette recette
                $stmtDiet = $pdo->prepare("INSERT INTO recipe_diet (recipe_id, diet_id) VALUES (:recipe_id, :diet_id)");
                foreach ($selectedDiets as $dietId) {
                    $dietId = (int) $dietId;
                    $stmtDiet->bindParam(':recipe_id', $newRecipeId, PDO::PARAM_INT);
                    $stmtDiet->bindParam(':diet_id', $dietId, PDO::PARAM_INT);
                    $stmtDiet->execute();
                }
                $stmt->closeCursor();
                echo json_encode(["success" => "Recette ajoutée avec succès."]);
             
            }
        } else {
            throw new Exception("La connexion à la base de données a échoué.");
        }
    } catch (Exception $e) {
        echo json_encode(["error" => "Une erreur s'est produite lors de l'ajout de la recette."]);
    } finally {
        // Fermeture de la connexion PDO
        if ($pdo) {
            $pdo = null;
        }
    }
}

$data = json_decode(file_get_contents("php://input"), true);

// Vérification si les données nécessaires sont présentes
if (isset($data['title'],$data['description'],$data['preparationTime'],$data['breakTime'],
$data['cookingTime'],$data['ingredient'],$data['step'],$data['isVisible'],$data['selectedDiets'],$data['selectedAllergens'])) {

    $title = $data['title'];
    $description =$data['description'];
    $preparationTime = $data['preparationTime'];
    $breakTime =$data['breakTime'];
    $cookingTime = $data['cookingTime'];
    $ingredient =$data['ingredient'];
    $step = $data['step'];
    $isVisible = $data['isVisible'];
    $selectedDiets = $data['selectedDiets'];
    $selectedAllergens= $data['selectedAllergens'];
    
    postRecipe($title,$description,$preparationTime,$breakTime,$cookingTime,$ingredient,$step,$isVisible,$selectedDiets,$selectedAllergens);
   
} else {
    // Géstion du cas où des données requises sont manquantes
    echo json_encode(["error" => "Parametres manquants"]);
}
