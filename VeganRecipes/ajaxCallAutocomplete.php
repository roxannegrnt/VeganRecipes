<?php
include_once './controllerIndex.php';
$resultAuto = $DB->Autocomplete($_REQUEST["keyword"]);
if (!empty($resultAuto)) {
        echo "<ul id=\"recipeTitle-list\">";
        foreach ($resultAuto as $title) {
            $titre = $title["Titre"];
            echo "<li onClick=\"selectTitle('$titre');\">";
            echo $titre;
            echo "</li>";
        }
    }
    echo "</ul>";
