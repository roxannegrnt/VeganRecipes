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
 * Affichage quand on clique sur Home
 * @param array $value le tableau retourné par la base de donnée qui contient la recette
 * @param string $descripShort la version courte de la description de la recette
 */
function IndexHome($value, $descripShort, $fav) {
    echo<<<affichage
                    <article class="well col-md-6 col-md-offset-3">
                    <div class="pull-right star">
affichage;
    if (!$fav) {
        echo " <i class=\"glyphicon glyphicon-star-empty\" id=\"Star$value[IdRecette]\" onclick=Favorite(this) onmouseover=OnHoverChangeIcon(this) onmouseout=OnHoverOutChangeIcon(this)></i>";
    echo "<div class=\"loader\" id=\"$value[IdRecette]\"></div>";
        
    } else {
        echo " <i class=\"glyphicon glyphicon-star\" id=\"Star$value[IdRecette]\" onclick=UnFavorite(this)></i>";
    }
    echo<<<reste
    </div>
    <img class = "col-md-3" src = "upload/$value[NomFichierImg]" id = "imgrecipe">
    <h3 class = "col-md-9">$value[Titre]</h3>
    <p class = "col-md-9 descrip">
    $descripShort
    <a data-toggle = "modal" data-target = ".$value[IdRecette]" >Read more...</a>
    </p>
    <button type = "button" class = "btn btn-primary pull-right" class = "Getcomment" data-toggle = "collapse" data-target = "#collapseComment$value[IdRecette]" aria-expanded = "false" aria-controls = "collapseExample">Ajouter un commentaire</button>
    <div class = "collapse" id = "collapseComment$value[IdRecette]">
    <input type = "text" class = "form-control" name = "comment">
    <input class = "btn btn-default" type = "submit" name = "sendComment">
    </div>
    </article >
reste;
}

/**
 * Affichage quand on clique sur l'écrou pour que l'admin valide les recettes
 * @param array $value le tableau retourné par la base de donnée qui contient la recette
 * @param string $descripShort la version courte de la description de la recette
 */
function IndexAdmin($value, $descripShort) {
    echo<<<affichage
                    <article class="well col-md-6 col-md-offset-3">
   <img class="col-md-3" src="upload/$value[NomFichierImg]" id="imgrecipe">
                    <h3 class="col-md-9">$value[Titre]</h3>
                    <p class="col-md-9 descrip">
                     $descripShort
                     <a data-toggle="modal" data-target=".$value[IdRecette]" >Read more...</a>
                    </p>
        </article>
        <div class="YesNo col-lg-3" id="$value[IdRecette]">
        <button onclick="ValidateRecipe(this)" class="btn btn-default btn-circle btn-xs accept"><i class="glyphicon glyphicon-ok"></i></button>
        <button onclick="RemoveRecipe(this)" class="btn btn-default btn-circle btn-xs" id="refuse"><i class="glyphicon glyphicon-remove"></i></button>
        </div>
affichage;
}
