<?php
session_start();
unset($_SESSION["recipe"]);
header('Location: index.php');

