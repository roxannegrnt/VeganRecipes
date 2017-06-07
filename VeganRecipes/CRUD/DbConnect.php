<?php

define("HOST", "127.0.0.1");
define("DBNAME", "myrecipes");
define("USER", "UserRecipes");
define("PASSWORD", "Super");

class DbConnect {

    private $ps_getRegistration = null;
    private $ps_getTypes = null;
    private $ps_insertRecipe = null;
    private $ps_getRecipes = null;
    private $dbb = null;

    public function __construct() {
        if ($this->dbb === null) {
            try {
                $StringDeConnection = 'mysql:host=' . HOST . ';dbname=' . DBNAME . '';
                $this->dbb = new PDO($StringDeConnection, USER, PASSWORD);
                $this->dbb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                //Prepare statment
                $this->ps_getRegistration = $this->dbb->prepare("SELECT * FROM `utilisateurs` WHERE Username= :user AND Password= :pwd");
                $this->ps_getRegistration->setFetchMode(PDO::FETCH_ASSOC);
                $this->ps_getTypes = $this->dbb->prepare("SELECT NomType FROM types");
                $this->ps_getTypes->setFetchMode(PDO::FETCH_ASSOC);
                $this->ps_insertRecipe = $this->dbb->prepare("INSERT INTO recettes (`IdRecette`,`Titre`,`Ingredient`,`Description`,`Valider`,`NomFichierImg`,`IdUtilisateur`,`IdType`)"
                        . "SELECT '',:title,:ingredients,:descrip,0,:img,:id,IdType FROM types WHERE NomType= :type");
                $this->ps_insertRecipe->setFetchMode(PDO::FETCH_ASSOC);
                $this->ps_getRecipes = $this->dbb->prepare("SELECT * FROM `recettes` WHERE Valider= :Valid");
                $this->ps_getRecipes->setFetchMode(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die("Erreur : " . $e->getMessage());
            }
        }
    }

    function GetRegistration($user, $pwd) {
        $this->ps_getRegistration->bindParam(':user', $user);
        $this->ps_getRegistration->bindParam(':pwd', $pwd);
        $this->ps_getRegistration->execute();
        return $this->ps_getRegistration->fetch();
    }

    function GetTypes() {
        $this->ps_getTypes->execute();
        return $this->ps_getTypes->fetchAll();
    }

    function InsertRecipe($param, $id) {
        $this->ps_insertRecipe->bindParam(':title', $param[0]);
        $this->ps_insertRecipe->bindParam(':ingredients', $param[1]);
        $this->ps_insertRecipe->bindParam(':descrip', $param[2]);
        $this->ps_insertRecipe->bindParam(':type', $param[3]);
        $this->ps_insertRecipe->bindParam(':img', $param[4]);
        $this->ps_insertRecipe->bindParam(':id', $id);
        $this->ps_insertRecipe->execute();
    }
    function GetRecipes($valid){
        $this->ps_getRecipes->bindParam(':Valid', $valid);
        $this->ps_getRecipes->execute();
        return $this->ps_getRecipes->fetchAll();
    }

}
