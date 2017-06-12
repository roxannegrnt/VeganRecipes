<?php

session_start();
require './CRUD/DbConnect.php';
include_once './lib/formatFunctions.php';
//Assignation des valeurs d'erreur
$signin_error = "";
$img_error = "";
$add_error = "";
$add_success = "";
//Initialisation de la classe DB
$DB = new DbConnect();
//Recherche des types
$types = $DB->GetTypes();
$parameters = array();
$favorite = array();
$Isfav = false;
$recipes = $DB->GetRecipes(1);
$comment = $DB->GetComment();
$IndexHome = true;
if (!empty($_SESSION["uid"])) {
    $favorite = $DB->getFavByID($_SESSION["uid"]);
}
//Si l'utilisateur veut se logger
if (isset($_REQUEST["login"])) {
    $exist = $DB->GetRegistration($_REQUEST["user"], sha1($_REQUEST["pwd"]));
    if (!empty($exist)) {
        $_SESSION["uid"] = $exist["IdUtilisateur"];
        $_SESSION["IsAdmin"] = $exist["IsAdmin"];
        $favorite = $DB->getFavByID($_SESSION["uid"]);
    } else {
        $signin_error = "<div class=\"alert alert-danger\">Oops... There must be an error with your username or your password</div>";
    }
}
//Si l'utilisateur veut s'incrire
if (isset($_REQUEST["signup"])) {
    $DB->Register($_REQUEST["Newuser"], sha1($_REQUEST["Newpwd"]));
    $_SESSION["uid"] = $_REQUEST["Newuser"];
    $_SESSION["IsAdmin"] = 0;
}

//Si l'utilisateur veut ajouter une recette
if (isset($_REQUEST["Add"])) {
    $parameters = array("title" => $_REQUEST["title"], "ingredients" => $_REQUEST["ingredients"], "descrip" => $_REQUEST["recipe"], "type" => $_REQUEST["type"]);
    $ingredients = FormatIngredients($_REQUEST["ingredients"]);
    $cpt = IsEmpty($parameters);
    if ($cpt == 0) {
        $unique = MoveImg($_FILES);
        if ($unique == "extension") {
            $img_error = "<div class=\"alert alert-danger\">The image doesn't have the right extension</div>";
        } else {
            $parameters["ingredients"] = $ingredients;
            $param = VerficationAdd($parameters);
            $DB->InsertRecipe($param, $_SESSION["uid"], $unique);
            $parameters = array();
            $add_success = "<div class=\"alert alert-success\">Recipe added sucessfully</div>";
        }
    } else {
        $add_error = "<div class=\"alert alert-danger\">Please fill out all inputs</div>";
    }
}
//Admin veut afficher les recettes à valider
if (isset($_REQUEST["tovalidate"])) {
    $recipes = $DB->GetRecipes(0);
    $IndexHome = 0;
}
//Si l'admin veut valider la recette
if (isset($_REQUEST["validate"])) {
    $DB->ValidateRecipe($_REQUEST["validate"]);
    $recipes = $DB->GetRecipes(0);
    $IndexHome = 0;
}
//Si l'admin veut supprimer la recette
if (isset($_REQUEST["remove"])) {
    $file = $DB->GetNameFile($_REQUEST["remove"]);
    $DB->RemoveRecipe($_REQUEST["remove"]);
    $IndexHome = $_REQUEST["indexhome"];
    switch ($IndexHome) {
        case 1:$recipes = $DB->GetRecipes(1);
            break;
        case 0:$recipes = $DB->GetRecipes(0);
        default: $recipes = $DB->GetRecipesById($_SESSION["uid"]);
            break;
    }
    if ($file["NomFichierImg"] != "") {
        DeleteImg('upload/', $file);
    }
}
//si l'utilisateur veut obtenir ces propres recettes
if (isset($_REQUEST["myrecipes"])) {
    $recipes = $DB->GetRecipesById($_SESSION["uid"]);
    $IndexHome=3;
}
//si l'utilisateur veut mettre en favori
if (isset($_REQUEST["favorite"])) {
    $idR = substr($_REQUEST["favorite"], 4);
    $DB->AddFav($_SESSION["uid"], $idR);
}
//si l'utilisateur enkève un favori
if (isset($_REQUEST["Unfavorite"])) {
    $idR = substr($_REQUEST["Unfavorite"], 4);
    $DB->removeFav($_SESSION["uid"], $idR);
}
//Si l'utilisateur veut ajouter un commentaire
if (isset($_REQUEST["commenttext"])) {
    $idR = substr($_REQUEST["idRecipe"], 15);
    $comments = filter_var($_REQUEST["commenttext"], FILTER_SANITIZE_STRING);
    $DB->insertComment($comments, $_SESSION["uid"], $idR);
    $comment = $DB->GetComment();
}
//Si l'admin veut supprimer un commentaire 
if (isset($_REQUEST["idComment"])) {
    $DB->removeComment($_REQUEST["idComment"]);
    $comment = $DB->GetComment();
}
