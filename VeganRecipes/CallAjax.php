<?php
include_once './controllerIndex.php';
if (!empty($_REQUEST["keyword"])) {
    $resultAuto = $DB->Autocomplete($_REQUEST["keyword"]);
    AutocompleteResult($resultAuto);
}
//Si l'utilisateur veut ajouter un commentaire
if (isset($_REQUEST["commenttext"])) {
    $idR = substr($_REQUEST["idRecipe"], 15);
    $comments = filter_var($_REQUEST["commenttext"], FILTER_SANITIZE_STRING);
    if (!empty($comments)) {
        $DB->insertComment($comments, $_SESSION["uid"], $idR);
        $comments = $DB->GetCommentById($idR);
        AfficherComment($comments,$_SESSION["IsAdmin"]);
    }
}

//Si l'admin veut supprimer un commentaire 
if (isset($_REQUEST["idComment"])) {
    $DB->removeComment($_REQUEST["idComment"]);
    $comments = $DB->GetCommentById($_REQUEST["idR"]);
    AfficherComment($comments,$_SESSION["IsAdmin"]);
}
//Si l'admin veut supprimer la recette
if (isset($_REQUEST["remove"])) {
    $file = $DB->GetNameFile($_REQUEST["remove"]);
    $DB->RemoveRecipe($_REQUEST["remove"]);
    if ($file["NomFichierImg"] != "") {
        DeleteImg('upload/', $file);
    }
}

    