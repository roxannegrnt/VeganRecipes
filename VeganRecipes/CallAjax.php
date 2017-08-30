<?php
/**
 * Project: VeganRecipes
 * Author: Roxanne Grant
 * Page: CallAjax.php
 * Date: Juin 2017
 * Copyright: TPI 2017 - Roxanne Grant © 2017
 */
include_once './controllerIndex.php';
//If user typed something in search bar
if (!empty($_REQUEST["keyword"])) {
    //sanitize search
    $search=filter_var($_REQUEST["keyword"], FILTER_SANITIZE_STRING);
    $resultAuto = $DB->Autocomplete($search);
    //Show autocomplete results in list
    AutocompleteResult($resultAuto);
}
//if user wants to add a comment to specific recipe
if (isset($_REQUEST["commenttext"])) {
    //Get id in name
    $idR = substr($_REQUEST["idRecipe"], 15);
    //sanitize comment
    $comments = filter_var($_REQUEST["commenttext"], FILTER_SANITIZE_STRING);
    //Don't add a comment if empty
    if (!empty($comments)) {
        $DB->insertComment($comments, $_SESSION["uid"], $idR);
        $comments = $DB->GetCommentById($idR);
        AfficherComment($comments,$_SESSION["IsAdmin"]);
    }
}

//If admin wants to remove a comment
if (isset($_REQUEST["idComment"])) {
    $DB->removeComment($_REQUEST["idComment"]);
    //gets all comments for recipe
    $comments = $DB->GetCommentById($_REQUEST["idR"]);
    AfficherComment($comments,$_SESSION["IsAdmin"]);
}
//If admin wants to remove a recipe
if (isset($_REQUEST["remove"])) {
    $file = $DB->GetRecipesByIdR($_REQUEST["remove"]);
    $DB->RemoveRecipe($_REQUEST["remove"]);
    //If there was an image, delete image from upload folder
    if ($file["NomFichierImg"] != "") {
        DeleteImg('upload/', $file);
    }
}
if (isset($_REQUEST["edit"])) {
  $parameters= $DB->GetRecipesByIdR($_REQUEST["edit"]);
  $types=$DB->GetTypes();
  EditModal($parameters,$types);
}

    