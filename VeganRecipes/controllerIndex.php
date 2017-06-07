<?php

session_start();
require './CRUD/DbConnect.php';
require './lib/formatFunctions.php';

//Assignation des valeurs d'erreur
$signin_error = "";
$img_error = "";
$add_error="";
//Initialisation de la classe DB
$DB = new DbConnect();
$types = $DB->GetTypes();
//Si l'utilisateur veut se logger
if (isset($_REQUEST["login"])) {
    $exist = $DB->GetRegistration($_REQUEST["user"], sha1($_REQUEST["pwd"]));
    if (!empty($exist)) {
        $_SESSION["uid"] = $exist["IdUtilisateur"];
        $_SESSION["IsAdmin"] = $exist["IsAdmin"];
    } else {
        $signin_error = "Oops... There must be an error with your username or your password";
    }
    header('Location: index.php');
}
if (isset($_REQUEST["Add"])) {
    $ingredients = FormatIngredients($_REQUEST["ingredients"]);
    $cpt = IsEmpty($_REQUEST["title"], $ingredients, $_REQUEST["recipe"], $_REQUEST["type"]);
    if ($cpt = 0) {
        $param = VerficationAdd($_REQUEST["title"], $ingredients, $_REQUEST["recipe"], $_REQUEST["type"]);
        $IsVerified = VerifyImg($_FILES);
        if ($IsVerified) {
            $unique = uniqid("FILE_");
            if (move_uploaded_file($_FILES['upload']['tmp_name'], "upload/" . $unique)) {
                array_push($param, $unique);
                $DB->InsertRecipe($param, $_SESSION["uid"]);
            }
        } else {
            $img_error = "Veuillez ajouter une image";
        }
    }else{
        $add_error="Veuillez remplir tous les champs";
    }

    header('Location: index.php');
}
