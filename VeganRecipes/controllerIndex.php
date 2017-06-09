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
    $IndexHome = false;
}
//Si l'admin veut valider la recette
if (isset($_REQUEST["validate"])) {
    $DB->ValidateRecipe($_REQUEST["validate"]);
    $recipes = $DB->GetRecipes(0);
    $IndexHome = false;
}
//Si l'admin veut supprimer la recette
if (isset($_REQUEST["remove"])) {
    $file = $DB->GetNameFile($_REQUEST["remove"]);
    $DB->RemoveRecipe($_REQUEST["remove"]);
    $recipes = $DB->GetRecipes(0);
    if ($file["NomFichierImg"] != "") {
        DeleteImg('upload/', $file);
    }
    $IndexHome = false;
}

//si l'utilisateur veut mettre en favori
if (isset($_REQUEST["favorite"])) {
    $idR = substr($_REQUEST["favorite"], 4);
    $DB->AddFav($_SESSION["uid"], $idR);
}
//si l'utilisateur enklève un favori
if (isset($_REQUEST["Unfavorite"])) {
    $idR = substr($_REQUEST["Unfavorite"], 4);
    $DB->removeFav($_SESSION["uid"], $idR);
}