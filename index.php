<?php

require_once ("./api.php");

// Routage des pages.

try {
    if (!empty($_GET['page'])) {
        $url = explode("/", filter_var($_GET['page'], FILTER_SANITIZE_URL));

        switch ($url[0]) {
            case "recettes":
                getRecipes();
                break;
            case "recette":
                if (!empty($url[1])) {
                    switch ($url[1]) {
                        case "allergene":
                            if (!empty($url[2])) {
                                getRecipesByAllergen($url[2]);
                            } else {
                                throw new Exception("Cette allergène n'existe pas!");
                            }
                            break;

                        case "regime":
                            if (!empty($url[2])) {
                                getRecipesByDiet($url[2]);
                            } else {
                                throw new Exception("Ce régime n'existe pas!");
                            }
                            break;

                        default:
                            throw new Exception("Cette page (recette) n'existe pas!");
                    }
                } else {
                    throw new Exception("Cette page (recette) n'existe pas!");
                }
                break;

            case 'users':
               if (!empty($url[1]) && !empty($url[2])) {
                     verifUsers($url[1], $url[2]);
                } else {
                    throw new Exception("Cette page (users) n'existe pas");
                }
                break;

            case 'user':
                if (!empty($url[1]) && !empty($url[2])) {
                    getUserId($url[1], $url[2]);
                } else {
                    throw new Exception("Cette page(user) n'existe pas");
                }
                break;

            case 'diets':
                getDiets();
                break;

            case 'allergens':
                getAllergens();
                break;

            case 'personalRecipes':
                if (!empty($url[1]) && !empty($url[2])) {
                    getPersonalRecip($url[1], $url[2]);
                } else {
                    throw new Exception("Cette page (Precipe) n'existe pas");
                }
                break;
            default:
                throw new Exception("Cette page (Precipe) n'existe pas!");
        }
    } else {
        throw new Exception("Cette page(page) n'existe pas!");
    }
} catch (Exception $exception) {
    // Tableau d'erreur JSON
    $erreur = [
        "message" => $exception->getMessage(),
        "code" => $exception->getCode()
    ];

    // Envoi de la réponse JSON
    header('Content-Type: application/json');
    echo json_encode($erreur);
}
