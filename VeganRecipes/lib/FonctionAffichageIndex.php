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
        <li><a onclick="GetRecipesToValidate();"><span class="glyphicon glyphicon-cog"></span></a></li>
        <li><a href="disconnect.php"><span class="glyphicon glyphicon-log-out"></span></a></li>
affichage;
    }
}

/**
 * Garde la modal ouverte si il y a une erreure et que alerte n'est pas vide
 * @param string $alert message d'erreur
 */
function KeepModalOpen($alert,$modalname) {
    $keepOpen = "";
    if (!empty($alert)) {
        echo "<script> $('#".$modalname."').modal('show'); </script>";
    }
}
/**
 * Format la liste d'ingreédient pour qu'elle soit affichée à l'utilisateur
 * @param string $listIngredients le string contenant tout les ingrédients
 */
function ListIngredients($listIngredients) {
    $Ingredients = explode(" - ", $listIngredients);
    unset($Ingredients[0]);
    foreach ($Ingredients as $value) {
        echo "<ul>";
        echo "<li class=\"col-md-10 ingredients\">" . $value . "</li>";
        echo "</ul>";
    }
}
/**
 * Permet d'avoir qu'un certain nombre de caractère de la description afficher
 * @param string $descrip la description de la recette
 * @return string Retourne la description plus courte
 */
function RestrictLengthDescrip($descrip) {
    if (strlen($descrip) > 400) {

        // truncate string
        $stringCut = substr($descrip, 0, 400);

        // make sure it ends in a word so assassinate doesn't become ass...
        $descrip = substr($stringCut, 0, strrpos($stringCut, ' '));
        return $descrip;
    }
}

