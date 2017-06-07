<?php
session_start();
require './FonctionIndex.php';
require './CRUD/DbConnect.php';
$alert = "";
$DB = new DbConnect();
$types = $DB->GetTypes();
if (isset($_REQUEST["login"])) {
    $exist = $DB->GetRegistration($_REQUEST["user"], sha1($_REQUEST["pwd"]));
    if (!empty($exist)) {
        $_SESSION["uid"] = $exist["IdUtilisateur"];
        $_SESSION["IsAdmin"] = $exist["IsAdmin"];
    } else {
        $alert = "Oops... There must be an error with your username or your password";
    }
}
if (isset($_REQUEST["Add"])) {
    $ingredients=FormatIngredients($_REQUEST["ingredients"]);
    $param=VerficationAdd($_REQUEST["title"], $ingredients, $_REQUEST["recipe"], $_REQUEST["type"]);
    $img = VerifyImg($_FILES);
    $unique = uniqid("FILE_");
    if (move_uploaded_file($_FILES['upload']['tmp_name'], "upload/" . $unique)) {
        array_push($param, $unique);
        $DB->InsertRecipe($param,$_SESSION["uid"]);
    }
    
}
