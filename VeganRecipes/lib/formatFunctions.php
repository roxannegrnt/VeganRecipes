<?php

/**
 * Formate le textarea qui contient les ingrédients pour qu'il soit en string avec un - qui sépare les éléments
 * @param type $ingredients
 * @return type
 */
function FormatIngredients($ingredients) {
    $ingredientsBR = nl2br($ingredients);
    $Newingredients = str_replace('<br />', ' - ', $ingredientsBR);
    $Newingredients = preg_replace("/(\r|\n)/", "", $Newingredients);
    return substr_replace($Newingredients, "- ", 0, 0);
}

/**
 * Nettoient les champs pour qu'il n'y ait pas d'injection
 * @param string $title le titre de la recette
 * @param string $ingredients la liste des ingrédients
 * @param string $descrip la description de la recette
 * @param string $type le type de recette
 * @return array Retourne un tableau avec tous les paramètres
 */
function VerficationAdd($param) {
    $paramsanitize = array();
    foreach ($param as $key => $value) {
        $newstr = filter_var($value, FILTER_SANITIZE_STRING);
        array_push($paramsanitize, $newstr);
    }
    return $paramsanitize;
}

/**
 * Vérfier l'image ajouté qu'elle a la bonne extension
 * @param array $files la superglobal $_FILES
 */
function VerifyImg($files) {
    $valid = FALSE;
    $extensions = array("jpeg", "jpg", "gif", "png", "mp4", "mp3");
    $elementsChemin = pathinfo($files['upload']['name']);
        $extensionFichier = $elementsChemin['extension'];
        if ((in_array($extensionFichier, $extensions))) {
            $valid = TRUE;
        } 
    return $valid;
}
function MoveImg($files){
    $unique = ""; 
    if (!empty($files)) {
            $IsVerified = VerifyImg($files);
            if ($IsVerified) {
                $unique = uniqid("FILE_");
                if (!move_uploaded_file($files['upload']['tmp_name'], "upload/" . $unique))
                    $unique = "";
            } else
                $unique = "extension";
        }
        return $unique;
}
/**
 * Vérifie si un des champs est vide
 * @param string $title le titre de la recette
 * @param string $ingredients la liste des ingrédients
 * @param string $descrip la description de la recette
 * @param string $type le type de recette
 * @return int retourne la valeur du nombre de champs vide
 */
function IsEmpty($param) {
    $cpt = 0;
    foreach ($param as $key => $value) {
        if (empty($value)) {
            $cpt++;
        }
    }
    return $cpt;
}

/**
 * Garde la modal ouverte si il y a une erreure et que alerte n'est pas vide
 * @param string $alert message d'erreur
 */
function KeepModalOpen($alert, $modalname) {
    $keepOpen = "";
    if (!empty($alert)) {
        echo "<script> $('#" . $modalname . "').modal('show'); </script>";
    }
}

/**
 * Format la liste d'ingreédient pour qu'elle soit affichée à l'utilisateur
 * @param string $listIngredients le string contenant tout les ingrédients
 */
function ListIngredients($listIngredients) {
    $Ingredients = explode(" - ", $listIngredients);
    unset($Ingredients[0]);
    foreach ($Ingredients as $value) {
        echo "<ul>";
        echo "<li class=\"col-md-10 ingredients\">" . $value . "</li>";
        echo "</ul>";
    }
}

/**
 * Permet d'avoir qu'un certain nombre de caractère de la description afficher
 * @param string $descrip la description de la recette
 * @return string Retourne la description plus courte
 */
function RestrictLengthDescrip($descrip) {
    if (strlen($descrip) > 400) {

        // truncate string
        $stringCut = substr($descrip, 0, 400);

        // make sure it ends in a word so assassinate doesn't become ass...
        $descrip = substr($stringCut, 0, strrpos($stringCut, ' '));
        return $descrip;
    }
}

function DeleteImg($dossier, $value) {
    $dossier_traite = $dossier;
    $repertoire = opendir($dossier_traite);
    unlink($dossier_traite . $value["NomFichierImg"]);
    closedir($repertoire);
}

function IsFav($value, $favorite) {
    $Isfav = false;
    foreach ($favorite as $k => $fav) {
        if ($value["IdRecette"] == $fav["IdRecette"]) {
            $Isfav = true;
        }
    }
    return $Isfav;
}
