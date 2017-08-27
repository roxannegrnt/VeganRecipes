<?php
/**
 * Project: VeganRecipes
 * Author: Roxanne Grant
 * Page: controllerIndex.php
 * Date: Juin 2017
 * Copyright: TPI 2017 - Roxanne Grant © 2017
 */
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
//Initialisation of classe DB
$DB = new DbConnect();
//Get the types of meals
$types = $DB->GetTypes();
$parameters = array();
$favorite = array();
$Isfav = false;
$resultAuto = "";
//Get all validated recipes
$recipes = $DB->GetRecipes(1);
//Get all comments on validated recipes
$comment = $DB->GetComment();
$IndexHome = true;
//Get favorites if user is connected
if (!empty($_SESSION["uid"])) {
    $favorite = $DB->getFavByID($_SESSION["uid"]);
}


//Si user clicked login
if (isset($_REQUEST["login"])) {
    //See if user-pass combination exists
    $exist = $DB->GetRegistration($_REQUEST["user"], sha1($_REQUEST["pwd"]));
    if (!empty($exist)) {
        //If it exists, add info in $_SESSION to keep user logged
        $_SESSION["uid"] = $exist["IdUtilisateur"];
        $_SESSION["IsAdmin"] = $exist["IsAdmin"];
        $favorite = $DB->getFavByID($_SESSION["uid"]);
    } else {
        $signin_error = "<div class=\"alert alert-danger\">Oops... There must be an error with your username or your password</div>";
    }
}
//If user wants to signup
if (isset($_REQUEST["signup"])) {
    //Throw everything in an array
    $parameters = array("user" => $_REQUEST["Newuser"], "pwd" => $_REQUEST["Newpwd"], "conf" => $_REQUEST["confirmation"]);
    //Check if a field is empty
    $cpt = IsEmpty($parameters);
    //Verify signup parameters
    $error = VerifySignup($parameters);
    if (empty($error)) {
        if ($cpt == 0) {
            //Sanitize all parameters
            $param = VerficationAdd($parameters);
            //Verify if user doesn't already exist
            $registration = $DB->GetUser($_REQUEST["Newuser"]);
            if (empty($registration)) {
                //register in database and log right away
                $id=$DB->Register($_REQUEST["Newuser"], sha1($_REQUEST["Newpwd"]));
                $_SESSION["uid"] = $id;
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

//If user wants to add a recipe
if (isset($_REQUEST["Add"])) {
    //Throw everything in array
    $parameters = array("title" => $_REQUEST["title"], "ingredients" => $_REQUEST["ingredients"], "descrip" => $_REQUEST["recipe"], "type" => $_REQUEST["type"]);
    //Format ingredients for database insertion
    $ingredients = FormatIngredients($_REQUEST["ingredients"]);
    //Verify that no field is empty
    $cpt = IsEmpty($parameters);
    if ($cpt == 0) {
        //Verify if there is an image
        $unique = MoveImg($_FILES);
        if ($unique == "extension") {
            //if image doesn't have the right extension display error message
            $img_error = "<div class=\"alert alert-danger\">The image doesn't have the right extension</div>";
        } else {
            $parameters["ingredients"] = $ingredients;
            //Sanitize all fields
            $param = VerficationAdd($parameters);
            //Insert new recipe in databse
            $DB->InsertRecipe($param, $_SESSION["uid"], $unique);
            $parameters = array();
            //Show sucess message
            $add_success = "<div class=\"alert alert-success\">Recipe added sucessfully</div>";
            $recipes = $DB->GetRecipes(1);
        }
    } else {
        $add_error = "<div class=\"alert alert-danger\">Please fill out all inputs</div>";
    }
}
//If admin wants to get recipes to validate
if (isset($_REQUEST["tovalidate"])) {
    $recipes = $DB->GetRecipes(0);
    $IndexHome = 0;
}
//If admin wants to validate a certain recipe
if (isset($_REQUEST["validate"])) {
    $DB->ValidateRecipe($_REQUEST["validate"]);
    $recipes = $DB->GetRecipes(0);
    $IndexHome = 0;
}
//If user wants to display his favorite recipes
if (isset($_REQUEST["myFav"])) {
    $recipes = $DB->GetRecipesByFav($_SESSION["uid"]);
    $IndexHome = 1;
}

//If user wants to display his recipes
if (isset($_REQUEST["myrecipes"])) {
    $recipes = $DB->GetRecipesById($_SESSION["uid"]);
    if (empty($recipes)) {
        //If no recipe is found show message
        $none_error = "<div class=\"alert alert-success\">You haven't created any recipes</div>";
    }
    $IndexHome = 3;
}
//If user wants to mark a recipe as favorite
if (isset($_REQUEST["favorite"])) {
    $idR = substr($_REQUEST["favorite"], 4);
    $DB->AddFav($_SESSION["uid"], $idR);
}
//If user wants to unmark recipe as favorite
if (isset($_REQUEST["Unfavorite"])) {
    $idR = substr($_REQUEST["Unfavorite"], 4);
    $DB->removeFav($_SESSION["uid"], $idR);
}
//uf user is using filters
if (isset($_REQUEST["AjaxFilter"])) {
    //If user is on his recipe page, add id to query
    if ($_REQUEST["isMyRecipes"]) {
        $uid=$_SESSION["uid"];
        $Valid="";
    }
    else{
        $uid="";
        $Valid=1;
    }
    //If admin is on his validate page add valid is 0 to query
    if ($_REQUEST["isNotValid"]) {
        $Valid=0;
        $IndexHome = 0;
    }
    $recipes = $DB->filterSearchByCriterea($_REQUEST["searchKeyWord"], $_REQUEST["Filtertype"], $_REQUEST["FilterDate"],$uid,$Valid);
}
//Get search results of search bar
if (isset($_REQUEST["search"])) {
    $recipes = $DB->Autocomplete($_REQUEST["search"]);
}
