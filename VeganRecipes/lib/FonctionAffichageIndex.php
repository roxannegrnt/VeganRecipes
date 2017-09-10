<?php

/**
 * Project: VeganRecipes
 * Author: Roxanne Grant
 * Page: controllerIndex.php
 * Date: Juin 2017
 * Copyright: TPI 2017 - Roxanne Grant © 2017
 */

/**
 * Add icons in navbar depending on user status
 * @param enum $isadmin 0 if not an admin and 1 if admin
 */
function SignedIn($isadmin) {
    if ($isadmin == 0) {
        echo<<<affichage
        <li><a href="index.php"><span class="glyphicon glyphicon-home"></span></a></li>
        <li><a data-toggle="modal" data-keyboard="false" data-target="#AddModal"><span class="glyphicon glyphicon-plus"></span></a></li>
        <li><a onclick="GetMyFav()"><span class="glyphicon glyphicon-star"></span></a></li>
        <li><a onclick="GetMyRecipes()"><span class="glyphicon glyphicon-list-alt"></span></a></li>
        <li><a href="disconnect.php"><span class="glyphicon glyphicon-log-out"></span></a></li>
affichage;
    } else {
        echo<<<affichage
       <li><a href="index.php"><span class="glyphicon glyphicon-home"></span></a></li>
        <li><a data-toggle="modal" data-keyboard="false" data-target="#AddModal"><span class="glyphicon glyphicon-plus"></span></a></li>
        <li><a onclick="GetMyFav()"><span class="glyphicon glyphicon-star"></span></a></li>
        <li><a onclick="GetRecipesToValidate();"><span class="glyphicon glyphicon-cog"></span></a></li>
        <li><a href="disconnect.php"><span class="glyphicon glyphicon-log-out"></span></a></li>
affichage;
    }
}

/**
 * View when home
 * @param array $value array containing recipe from databse
 * @param string $descripShort short version of recipe description
 */
function IndexHome($value, $descripShort, $fav, $comments, $isadmin) {
    echo<<<affichage
                    <article class="well col-md-6 col-md-offset-3">
                    <div class="pull-right YesNo" id=$value[IdRecette]>
affichage;
    AdmincrossRecipe($isadmin);
    if ($value["Valider"] == 0) {
        echo "<button onclick=\"EditRecipe(this)\" class=\"btn btn-default btn-circle btn-xs\" id=\"edit\"><i class=\"glyphicon glyphicon-pencil\"></i></button>";
    }
    echo<<<reste
    </div>
    <img class = "col-md-3" src = "upload/$value[NomFichierImg]" id = "imgrecipe">
    <h3 class = "col-md-9">$value[Titre]</h3>
    <p class = "col-md-9 descrip">
    $descripShort
    <a data-toggle = "modal" data-target = ".$value[IdRecette]" >Read more...</a>
    </p>
reste;
    echo "<div class=\"Allcomments\">";
    AfficherComment($comments, $isadmin);
    echo "</div>";
    if (IsConnected()) {
        echo<<<reste
    <button type = "button" class = "btn btn-primary pull-right Getcomment" data-toggle = "collapse" data-target = "#collapseComment$value[IdRecette]" aria-expanded = "false" aria-controls = "collapseExample">Add a comment</button>
    <div class = "collapse" id = "collapseComment$value[IdRecette]">
    <input type = "text" class = "form-control" name=comment id="inputComment" autofocus>
    <input class = "btn btn-default" type = "button" value = "send comment" onclick=AddComment(this)>
    </div>
reste;
        if (!$fav) {
            echo " <i class=\"glyphicon glyphicon-star-empty pull-left\" id=\"Star$value[IdRecette]\" onclick=Favorite(this) onmouseover=OnHoverChangeIcon(this) onmouseout=OnHoverOutChangeIcon(this)></i>";
            echo "<div class=\"loader\" id=\"$value[IdRecette]\"></div>";
        } else {
            echo " <i class=\"glyphicon glyphicon-star pull-left\" id=\"Star$value[IdRecette]\" onclick=UnFavorite(this)></i>";
            echo "<div class=\"loader\" id=\"$value[IdRecette]\"></div>";
        }
    }
    echo "<div class=\"loader\"></div>";

    echo "</article>";
}

/**
 * View when icon cog is clicked for admin recipe validation
 * @param array $value array containing recipe from databse
 * @param string $descripShort short version of recipe description
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
        
        <div class="YesNo col-lg-3" id="$value[IdRecette]">
        <button onclick="ValidateRecipe(this)" class="btn btn-default btn-circle btn-xs accept"><i class="glyphicon glyphicon-ok"></i></button>
        <button onclick="RemoveRecipe(this,0)" class="btn btn-default btn-circle btn-xs" id="refuse"><i class="glyphicon glyphicon-remove"></i></button>
        <button onclick="EditRecipe(this)" class="btn btn-default btn-circle btn-xs" id="edit"><i class="glyphicon glyphicon-pencil"></i></button>     
   </div>
            </article>
affichage;
}

/**
 * Show cross to remove comment if user is an admin
 * @param bool $IsAdmin true if user is an admin, false otherwise
 */
function AdminCross($IsAdmin) {
    if ($IsAdmin) {
        echo "<div class=\"pull-right\">";
        echo "<button type=\"button\" class=\"close\" onclick=RemoveComment(this) data-dismiss=\"modal\">&times;</button>";
        echo "</div>";
    }
}

/**
 * Show cross to remove recipe if user is an admin
 * @param bool $IsAdmin true if user is an admin, false otherwise
 */
function AdmincrossRecipe($IsAdmin) {
    if ($IsAdmin) {
        echo "<div class=\"pull-right\">";
        echo "<button type=\"button\" class=\"close\" onclick=RemoveRecipe(this,1) data-dismiss=\"modal\">&times;</button>";
        echo "</div>";
    }
}

/**
 * Shows all comments of a certain recipe
 * @param array $comments array of all comments for a specific recipe
 * @param bool $isadmin true if user is an admin, false otherwise
 */
function AfficherComment($comments, $isadmin) {
    foreach ($comments as $k => $v) {
        echo "<article class=\"well well-sm col-md-12\">";
        AdminCross($isadmin);
        echo "<h4 class=\"col-lg-3\">" . $v["Username"] . "</h4>";
        echo "<p id=$v[IdCommentaire] class=\"col-lg-12 Usercomment\">" . $v["Commentaire"] . "</p>";
        echo "</article>";
    }
}

/**
 * Shows all autocomplete results under search bar
 * @param array $resultAuto array of all autocomplete results
 */
function AutocompleteResult($resultAuto) {
    echo "<ul id=\"recipeTitle-list\" class=\"col-lg-8\">";
    foreach ($resultAuto as $title) {
        $titre = $title["Titre"];
        echo "<li  onClick=\"selectTitle('$titre');\">";
        echo $titre;
        echo "</li>";
    }
    echo "</ul>";
}

function EditModal($param, $types) {
    echo "<label for=\"editImg\" id=\"labelImg\">";
    if ($param["NomFichierImg"] != "") {
        echo "<img class=\"col-lg-offset-5\" id=\"AddPhoto\"  alt=\"Add a picture\" src=\"upload/" . $param["NomFichierImg"] . "\">";
    } else {
        echo "<img class=\"col-lg-offset-5\" id=\"AddPhoto\" alt=\"Add a picture\" src=\"upload/add.ico\">";
    }
    echo "</label>";
    echo "<input type=\"file\" class=\"col-xs-3 form-control-file\" accept=\"image/*\" name=\"upload\" id=\"editImg\" class=\"FileInput\">";
    echo "<section class=\"form-group inputT\">";
    echo "<input class=\"frm form-control\" type=\"text\" name=\"title\" id=\"title\" placeholder=\"Title\" value=\"";
    echo (empty($param["Titre"])) ? "" : $param["Titre"] . "\">";
    echo "</section>";
    echo "<textarea class=\" frm form-control\" rows=\"8\" id=\"LIngredients\" name=\"ingredients\" placeholder=\"List of Ingredients\">";
    echo (empty($param["Ingredient"])) ? "" : $param["Ingredient"] . "</textarea>";
    echo "<select name=\"type\" class=\"form-control frm\">";

    //Show types from DB
    foreach ($types as $key => $value) {
        if ($value["IdType"] == $param["IdType"]) {
            echo "<option selected=\"selected\">" . $value["NomType"] . "</option>";
        } else {
            echo "<option>" . $value["NomType"] . "</option>";
        }
    }

    echo "</select>";
    echo "<section class=\"form-group textareaD\">";
    echo "<textarea class=\"frm form-control\" rows=\"5\" name=\"recipe\" id=\"descrip\" placeholder=\"Description de la recette\">";
    echo (empty($param["Description"])) ? "" : $param["Description"] . "</textarea>";
    echo "</section>";
    echo "<input type=\"text\" hidden name=\"id\" value=\"" . $param["IdRecette"] . "\">";
    echo "<button type=\"submit\" class=\"btn btn-primary btn-block frm\" name=\"Add\">Edit Recipe</button>";
}
