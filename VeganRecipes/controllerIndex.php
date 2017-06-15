<?php
session_start();
require './CRUD/DbConnect.php';
include_once './lib/formatFunctions.php';
require_once './lib/FonctionAffichageIndex.php';
//Declaring variables for errors
$signin_error = "";
$signup_error = "";
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
$resultAuto = "";
if (isset($_SESSION["recipe"])) {
    $recipes = $_SESSION["recipe"];
} else {
    $recipes = $DB->GetRecipes(1);
}

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
    $parameters = array("user" => $_REQUEST["Newuser"], "pwd" => $_REQUEST["Newpwd"]);
    $cpt = IsEmpty($parameters);
    if ($cpt == 0) {
        $param = VerficationAdd($parameters);
        $registration = $DB->GetUser($_REQUEST["Newuser"]);
        if (empty($registration)) {
            $DB->Register($_REQUEST["Newuser"], sha1($_REQUEST["Newpwd"]));
            $_SESSION["uid"] = $_REQUEST["Newuser"];
            $_SESSION["IsAdmin"] = 0;
        } else {
            $signup_error = "<div class=\"alert alert-danger\">This user is already used on the site</div>";
        }
    } else {
        $signup_error = "<div class=\"alert alert-danger\">Please fill out the required fields</div>";
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
            $recipes=$DB->GetRecipes(1);
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

//si l'utilisateur veut obtenir ces propres recettes
if (isset($_REQUEST["myrecipes"])) {
    $recipes = $DB->GetRecipesById($_SESSION["uid"]);
    $IndexHome = 3;
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
//Si l'utilisateur veut filtrer par date de post
if (!empty($_REQUEST["date"])) {
    switch ($_REQUEST["date"]) {
        case "Last added": $recipes = $DB->filterByDateD();
            break;
        case "Oldest post": $recipes = $DB->filterByDateA();
            break;
        default :$recipes = $DB->GetRecipes(1);
    }
}
if (!empty($_REQUEST["Filtertype"])) {
    if ($_REQUEST["Filtertype"] == "All") {
        $recipes = $DB->GetRecipes(1);
    } else {
        $recipes = $DB->filterByType($_REQUEST["Filtertype"]);
    }
}
if (isset($_REQUEST["keyword"])) {
    $resultAuto = $DB->Autocomplete($_REQUEST["keyword"]);
}
if (isset($_REQUEST["search"])) {
    $recipes = $DB->Autocomplete($_REQUEST["search"]);
}
$_SESSION["recipe"] = $recipes;
