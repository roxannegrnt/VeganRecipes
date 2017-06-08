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
    private $ps_validateRecipe=null;
    private $ps_removeRecipe=null;
    private $ps_getNomFichier=null;
    private $ps_addFav=null;
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
                 $this->ps_validateRecipe = $this->dbb->prepare("UPDATE recettes SET Valider=1 WHERE IdRecette=:idRecette");
                $this->ps_validateRecipe->setFetchMode(PDO::FETCH_ASSOC);
                 $this->ps_removeRecipe = $this->dbb->prepare("DELETE FROM recettes WHERE IdRecette=:idRecette");
                $this->ps_removeRecipe->setFetchMode(PDO::FETCH_ASSOC);
                 $this->ps_getNomFichier = $this->dbb->prepare("SELECT NomFichierImg FROM recettes WHERE IdRecette=:idRecette");
                $this->ps_getNomFichier->setFetchMode(PDO::FETCH_ASSOC);
                $this->ps_addFav = $this->dbb->prepare("INSERT INTO `favoris`(`IdUtilisateur`, `IdRecette`) VALUES(:uid,:idR)");
                $this->ps_addFav->setFetchMode(PDO::FETCH_ASSOC);
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
    function ValidateRecipe($idR){
        $this->ps_validateRecipe->bindParam(':idRecette', $idR);
        $this->ps_validateRecipe->execute();
    }
    function RemoveRecipe($idR){
        $this->ps_removeRecipe->bindParam(':idRecette', $idR);
        $this->ps_removeRecipe->execute();
    }
    function GetNameFile($idR){
        $this->ps_getNomFichier->bindParam(':idRecette', $idR);
        $this->ps_getNomFichier->execute();
        return $this->ps_getNomFichier->fetch();
    }
    function AddFav($uid,$idR){
        $this->ps_addFav->bindParam(':uid', $uid);
        $this->ps_addFav->bindParam(':idRecette', $idR);
        $this->ps_addFav->execute();
    }

}
