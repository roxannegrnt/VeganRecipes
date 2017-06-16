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
$none_error = "";
//Initialisation de la classe DB
$DB = new DbConnect();
//Recherche des types
$types = $DB->GetTypes();
$parameters = array();
$favorite = array();
$Isfav = false;
$resultAuto = "";
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
    $parameters = array("user" => $_REQUEST["Newuser"], "pwd" => $_REQUEST["Newpwd"], "conf" => $_REQUEST["confirmation"]);
    $cpt = IsEmpty($parameters);
    $error = VerifySignup($parameters);
    if (empty($error)) {
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
    } else {
        $signup_error = $error;
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
            $recipes = $DB->GetRecipes(1);
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

if (isset($_REQUEST["myFav"])) {
    $recipes = $DB->GetRecipesByFav($_SESSION["uid"]);
    $IndexHome = 1;
}

//si l'utilisateur veut obtenir ces propres recettes
if (isset($_REQUEST["myrecipes"])) {
    $recipes = $DB->GetRecipesById($_SESSION["uid"]);
    if (empty($recipes)) {
        $none_error = "<div class=\"alert alert-success\">You haven't created any recipes</div>";
    }
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

if (isset($_REQUEST["AjaxFilter"])) {
    $recipes = $DB->filterSearchByCriterea($_REQUEST["searchKeyWord"], $_REQUEST["Filtertype"], $_REQUEST["FilterDate"]);
}
if (isset($_REQUEST["search"])) {
    $recipes = $DB->Autocomplete($_REQUEST["search"]);
}
