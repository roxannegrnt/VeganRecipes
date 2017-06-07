<?php

/**
 * Ajoute les bon icônes dépendant du statut de l'utilisateur lors de la connexion
 * @param enum $isadmin 0 si pas admin et 1 si admin
 */
function SignedIn($isadmin) {
    if ($isadmin == 0) {
        echo<<<affichage
        <li><a href="index.php"><span class="glyphicon glyphicon-home"></span></a></li>
        <li><a data-toggle="modal" data-keyboard="false" data-target="#AddModal"><span class="glyphicon glyphicon-plus"></span></a></li>
        <li><a href="disconnect.php"><span class="glyphicon glyphicon-log-out"></span></a></li>
affichage;
    } else {
        echo<<<affichage
       <li><a href="index.php"><span class="glyphicon glyphicon-home"></span></a></li>
        <li><a data-toggle="modal" data-keyboard="false" data-target="#AddModal"><span class="glyphicon glyphicon-plus"></span></a></li>
        <li><a><span class="glyphicon glyphicon-cog"></span></a></li>
        <li><a href="disconnect.php"><span class="glyphicon glyphicon-log-out"></span></a></li>
affichage;
    }
}

/**
 * Garde la modal ouverte si il y a une erreure et que alerte n'est pas vide
 * @param string $alert message d'erreur
 */
function KeepModalOpen($alert) {
    $keepOpen = "";
    if (!empty($alert)) {
        echo "<script> $('#myModal').modal('show'); </script>";
    }
}
function FormatIngredients($ingredients){
    $ingredientsBR=nl2br($ingredients);
    $Newingredients=str_replace('<br />', ' - ', $ingredientsBR);
    $Newingredients=preg_replace( "/(\r|\n)/", "", $Newingredients );
    return substr_replace($Newingredients, "- ", 0, 0);
}
function VerficationAdd($title, $ingredients, $descrip, $type) {
    $param = array($title, $ingredients, $descrip, $type);
    $paramsanitize = array();
    foreach ($param as $value) {
        $newstr = filter_var($value, FILTER_SANITIZE_STRING);
        array_push($paramsanitize, $newstr);
    }
    return $paramsanitize;
}

function VerifyImg($files) {
    $extensions = array("jpeg", "jpg", "gif", "png", "mp4", "mp3");
    $elementsChemin = pathinfo($files['upload']['name']);
    if ($elementsChemin != null) {
        $extensionFichier = $elementsChemin['extension'];
        if (!(in_array($extensionFichier, $extensions))) {
            $valid = FALSE;
        } else {
            $valid = TRUE;
        }
    }
}
