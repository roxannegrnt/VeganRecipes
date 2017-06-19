<?php
/**
 * Project: VeganRecipes
 * Author: Roxanne Grant
 * Page: formatFunctions.php
 * Date: Juin 2017
 * Copyright: TPI 2017 - Roxanne Grant © 2017
 */
/**
 * Format textarea with list of ingredients to get "- ingredient - " for upload to database
 * @param string $ingredients all the ingredients of text area
 * @return string new string of all ingredients in right format
 */
function FormatIngredients($ingredients) {
    $ingredientsBR = nl2br($ingredients);
    $Newingredients = str_replace('<br />', ' - ', $ingredientsBR);
    $Newingredients = preg_replace("/(\r|\n)/", "", $Newingredients);
    return substr_replace($Newingredients, "- ", 0, 0);
}

/**
 * Sanitize all fields against injections
 * @param array $param all fields of modal
 * @return array returns all the parameters sanitized
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
 * Verify for signup. Username shouldn't have special characters, 
 * password must be longer than 6 charaters
 * confirmation must be equal to password
 * @param array $param all fields of signup modal
 * @return string returns error if one is found
 */
function VerifySignup($param) {
    $error = "";
    if ($param["pwd"]!=$param["conf"]) {
        $error="<div class=\"alert alert-danger\">The password and the confirmation need to be the same</div>";
    }
    if (preg_match("/^[A-Za-z0-9]+$/", $param["user"]) != true) {
        $error = "<div class=\"alert alert-danger\">The Username must contain only letters and numbers</div>";
    }
    return $error;
}

/**
 * Verify that image has the right extension
 * @param array $files the superglobal $_FILES
 * @return boolean returns true if image is valid otherwise return false
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
/**
 * Upload image to upload folder
 * @param array $files the superglobal $_FILES
 * @return string returns nothing if image uploaded well otherwise returns extension
 */
function MoveImg($files) {
    $unique = "";
    if (!empty($files['upload']['tmp_name'])) {
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
 * Verify if a field is empty
 * @param array  $param all fields of signup modal
 * @return int returns number of empty fields
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
 * Keeps modal open if an error occurs
 * @param string $alert error
 * @param string $modalname modal where the error occured
 */
function KeepModalOpen($alert, $modalname) {
    $keepOpen = "";
    if (!empty($alert)) {
        echo "<script> $('#" . $modalname . "').modal('show'); </script>";
    }
}

/**
 *Format ingredient list from DB to show on page
 * @param string $listIngredients string containing list of ingredients from DB
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
 *Restricts length of description for primary view
 * @param string $descrip description of the recipe
 * @return string returns shorter description
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
/**
 * Deletes image from upload folder
 * @param string $folder the name of the folder where image is stocked
 * @param array $value name of file to remove
 */
function DeleteImg($folder, $value) {
    $dossier_traite = $folder;
    $repertoire = opendir($dossier_traite);
    unlink($dossier_traite . $value["NomFichierImg"]);
    closedir($repertoire);
}
/**
 * Verify if the user has this recipe as a favorite
 * @param array $value current recipe
 * @param array $favorite all favorites of user
 * @return boolean returns true if recipe is a favorite of user, false otherwise
 */
function IsFav($value, $favorite) {
    $Isfav = false;
    foreach ($favorite as $k => $fav) {
        if ($value["IdRecette"] == $fav["IdRecette"]) {
            $Isfav = true;
        }
    }
    return $Isfav;
}
/**
 * Verify if comment belongs to recipe
 * @param array $value current recipe
 * @param array $comment array of all comments from the DB
 * @return array returns array of comments belonging to recipe
 */
function ShowComment($value, $comment) {
    $comments = array();
    foreach ($comment as $k => $com) {
        if ($value["IdRecette"] == $com["IdRecette"]) {
            $comments[] = $com;
        }
    }
    return $comments;
}
function IsConnected(){
    if ((isset($_SESSION["uid"]))&&(!empty($_SESSION["uid"]))) {
       return true; 
    }
    else return FALSE;
}
