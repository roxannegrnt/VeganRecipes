<?php
include_once './controllerIndex.php';
if (!empty($_REQUEST["keyword"])) {
$resultAuto = $DB->Autocomplete($_REQUEST["keyword"]);
        echo "<ul id=\"recipeTitle-list\" class=\"col-lg-8\">";
        foreach ($resultAuto as $title) {
            $titre = $title["Titre"];
            echo "<li  onClick=\"selectTitle('$titre');\">";
            echo $titre;
            echo "</li>";
        }
    }
    echo "</ul>";
