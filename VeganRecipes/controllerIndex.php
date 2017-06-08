<?php

session_start();
require './CRUD/DbConnect.php';
include_once './lib/formatFunctions.php';
//Assignation des valeurs d'erreur
$signin_error = "";
$img_error = "";
$add_error = "";
//Initialisation de la classe DB
$DB = new DbConnect();
//Recherche des types
$types = $DB->GetTypes();

$recipes = $DB->GetRecipes(1);
$IndexHome = true;
//Si l'utilisateur veut se logger
if (isset($_REQUEST["login"])) {
    $exist = $DB->GetRegistration($_REQUEST["user"], sha1($_REQUEST["pwd"]));
    if (!empty($exist)) {
        $_SESSION["uid"] = $exist["IdUtilisateur"];
        $_SESSION["IsAdmin"] = $exist["IsAdmin"];
    } else {
        $signin_error = "<div class=\"alert alert-danger\">Oops... There must be an error with your username or your password</div>";
    }
}
//Si l'utilisateur veut ajouter une recette
if (isset($_REQUEST["Add"])) {
    $ingredients = FormatIngredients($_REQUEST["ingredients"]);
    $cpt = IsEmpty($_REQUEST["title"], $ingredients, $_REQUEST["recipe"], $_REQUEST["type"]);
    if ($cpt == 0) {
        $param = VerficationAdd($_REQUEST["title"], $ingredients, $_REQUEST["recipe"], $_REQUEST["type"]);
        $IsVerified = VerifyImg($_FILES);
        if ($IsVerified) {
            $unique = uniqid("FILE_");
            if (move_uploaded_file($_FILES['upload']['tmp_name'], "upload/" . $unique)) {
                array_push($param, $unique);
                $DB->InsertRecipe($param, $_SESSION["uid"]);
            }
        } else {
            $img_error = "<div class=\"alert alert-danger\">Veuillez ajouter une image</div>";
        }
    } else {
        $add_error = "<div class=\"alert alert-danger\">Veuillez remplir tous les champs</div>";
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
    $recipes = $DB->GetRecipes(0);
    $IndexHome = false;
}
