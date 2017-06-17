<?php
/**
 * Project: VeganRecipes
 * Author: Roxanne Grant
 * Page: disconnect.php
 * Date: Juin 2017
 * Copyright: TPI 2017 - Roxanne Grant © 2017
 */
//Destroy user session on disconnect
session_start();
$_SESSION=array();
session_destroy();
header('Location: index.php');

