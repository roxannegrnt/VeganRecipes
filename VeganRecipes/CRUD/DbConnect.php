<?php

define("HOST", "127.0.0.1");
define("DBNAME", "myrecipes");
define("USER", "UserRecipes");
define("PASSWORD", "Super");

class DbConnect {

    private $ps_getRegistration = null;
    private $ps_getUser = null;
    private $ps_register = null;
    private $ps_getTypes = null;
    private $ps_insertRecipe = null;
    private $ps_getRecipes = null;
    private $ps_getByRecipesId = null;
    private $ps_validateRecipe = null;
    private $ps_removeRecipe = null;
    private $ps_getNomFichier = null;
    private $ps_addFav = null;
    private $ps_getFavByID = null;
    private $ps_getRecetteByFav = null;
    private $ps_removeFav = null;
    private $ps_insertComment = null;
    private $ps_getComment = null;
     private $ps_getCommentbyId = null;
    private $ps_removeComment = null;
    private $ps_filterbyTypeA = null;
    private $ps_filterbyTypeD = null;
    private $ps_autocomplete = null;
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
                $this->ps_getUser = $this->dbb->prepare("SELECT * FROM `utilisateurs` WHERE Username= :user");
                $this->ps_getUser->setFetchMode(PDO::FETCH_ASSOC);
                $this->ps_register = $this->dbb->prepare("INSERT INTO utilisateurs VALUES('',:user,:pwd,0)");
                $this->ps_register->setFetchMode(PDO::FETCH_ASSOC);
                $this->ps_getTypes = $this->dbb->prepare("SELECT NomType FROM types");
                $this->ps_getTypes->setFetchMode(PDO::FETCH_ASSOC);
                $this->ps_insertRecipe = $this->dbb->prepare("INSERT INTO recettes (`IdRecette`,`Titre`,`Ingredient`,`Description`,`Valider`,`NomFichierImg`,`IdUtilisateur`,`IdType`)"
                        . "SELECT '',:title,:ingredients,:descrip,0,:img,:id,IdType FROM types WHERE NomType= :type");
                $this->ps_insertRecipe->setFetchMode(PDO::FETCH_ASSOC);
                $this->ps_getRecipes = $this->dbb->prepare("SELECT * FROM `recettes` WHERE Valider= :Valid");
                $this->ps_getRecipes->setFetchMode(PDO::FETCH_ASSOC);
                $this->ps_getByRecipesId = $this->dbb->prepare("SELECT * FROM `recettes` WHERE IdUtilisateur= :uid");
                $this->ps_getByRecipesId->setFetchMode(PDO::FETCH_ASSOC);
                $this->ps_getRecetteByFav = $this->dbb->prepare("SELECT * FROM `favoris` JOIN recettes USING (IdRecette) WHERE favoris.IdUtilisateur = :uid");
                $this->ps_getRecetteByFav->setFetchMode(PDO::FETCH_ASSOC);
                $this->ps_validateRecipe = $this->dbb->prepare("UPDATE recettes SET Valider=1 WHERE IdRecette=:idRecette");
                $this->ps_validateRecipe->setFetchMode(PDO::FETCH_ASSOC);
                $this->ps_removeRecipe = $this->dbb->prepare("DELETE FROM recettes WHERE IdRecette=:idRecette");
                $this->ps_removeRecipe->setFetchMode(PDO::FETCH_ASSOC);
                $this->ps_getNomFichier = $this->dbb->prepare("SELECT NomFichierImg FROM recettes WHERE IdRecette=:idRecette");
                $this->ps_getNomFichier->setFetchMode(PDO::FETCH_ASSOC);
                $this->ps_addFav = $this->dbb->prepare("INSERT INTO `favoris`(`IdUtilisateur`, `IdRecette`) VALUES(:uid,:idR)");
                $this->ps_addFav->setFetchMode(PDO::FETCH_ASSOC);
                $this->ps_getFavByID = $this->dbb->prepare("SELECT IdRecette FROM favoris WHERE IdUtilisateur=:uid");
                $this->ps_getFavByID->setFetchMode(PDO::FETCH_ASSOC);
                $this->ps_removeFav = $this->dbb->prepare("DELETE FROM favoris WHERE IdRecette=:idR AND IdUtilisateur=:uid");
                $this->ps_removeFav->setFetchMode(PDO::FETCH_ASSOC);
                $this->ps_insertComment = $this->dbb->prepare("INSERT INTO commentaires VALUES ('',:comment,:uid,:idR)");
                $this->ps_insertComment->setFetchMode(PDO::FETCH_ASSOC);
                $this->ps_getComment = $this->dbb->prepare("SELECT IdCommentaire, Commentaire, IdRecette, Username FROM commentaires NATURAL JOIN utilisateurs");
                $this->ps_getComment->setFetchMode(PDO::FETCH_ASSOC);
                $this->ps_getCommentbyId = $this->dbb->prepare("SELECT IdCommentaire, Commentaire, IdRecette, Username FROM commentaires NATURAL JOIN utilisateurs WHERE IdRecette=:idR");
                $this->ps_getCommentbyId->setFetchMode(PDO::FETCH_ASSOC);
                $this->ps_removeComment = $this->dbb->prepare("DELETE FROM commentaires WHERE IdCommentaire=:idC");
                $this->ps_removeComment->setFetchMode(PDO::FETCH_ASSOC);
                $this->ps_filterbyType = $this->dbb->prepare("SELECT * FROM `recettes` NATURAL JOIN types WHERE NomType= :type AND Valider=1");
                $this->ps_filterbyType->setFetchMode(PDO::FETCH_ASSOC);
                $this->ps_filterbyDateA = $this->dbb->prepare("SELECT * FROM `recettes` WHERE Valider=1 ORDER BY DateTimeInsert ASC");
                $this->ps_filterbyDateA->setFetchMode(PDO::FETCH_ASSOC);
                $this->ps_filterbyDateD = $this->dbb->prepare("SELECT * FROM `recettes`WHERE Valider=1 ORDER BY DateTimeInsert DESC");
                $this->ps_filterbyDateD->setFetchMode(PDO::FETCH_ASSOC);
                $this->ps_autocomplete = $this->dbb->prepare("select * from recettes WHERE Valider=1 AND Titre like :keyword ORDER BY titre LIMIT 0,6");
                $this->ps_autocomplete->setFetchMode(PDO::FETCH_ASSOC);
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

    function GetUser($user) {
        $this->ps_getUser->bindParam(':user', $user);
        $this->ps_getUser->execute();
        return $this->ps_getUser->fetch();
    }

    function Register($user, $pwd) {
        $this->ps_register->bindParam(':user', $user);
        $this->ps_register->bindParam(':pwd', $pwd);
        $this->ps_register->execute();
    }

    function GetTypes() {
        $this->ps_getTypes->execute();
        return $this->ps_getTypes->fetchAll();
    }

    function InsertRecipe($param, $id, $img) {
        $this->dbb->beginTransaction();
        $this->ps_insertRecipe->bindParam(':title', $param[0]);
        $this->ps_insertRecipe->bindParam(':ingredients', $param[1]);
        $this->ps_insertRecipe->bindParam(':descrip', $param[2]);
        $this->ps_insertRecipe->bindParam(':type', $param[3]);
        $this->ps_insertRecipe->bindParam(':img', $img);
        $this->ps_insertRecipe->bindParam(':id', $id);
        if ($this->ps_insertRecipe->execute()) {
            $this->dbb->commit();
        } else {
            $this->dbb->rollBack();
        }
    }

    function GetRecipes($valid) {
        $this->ps_getRecipes->bindParam(':Valid', $valid);
        $this->ps_getRecipes->execute();
        return $this->ps_getRecipes->fetchAll();
    }

    function GetRecipesById($uid) {
        $this->ps_getByRecipesId->bindParam(':uid', $uid);
        $this->ps_getByRecipesId->execute();
        return $this->ps_getByRecipesId->fetchAll();
    }
    function GetRecipesByFav($uid){
        $this->ps_getRecetteByFav->bindParam(':uid', $uid);
        $this->ps_getRecetteByFav->execute();
        return $this->ps_getRecetteByFav->fetchAll();
    }

    function ValidateRecipe($idR) {
        $this->ps_validateRecipe->bindParam(':idRecette', $idR);
        $this->ps_validateRecipe->execute();
    }

    function RemoveRecipe($idR) {
        $this->ps_removeRecipe->bindParam(':idRecette', $idR);
        $this->ps_removeRecipe->execute();
    }

    function GetNameFile($idR) {
        $this->ps_getNomFichier->bindParam(':idRecette', $idR);
        $this->ps_getNomFichier->execute();
        return $this->ps_getNomFichier->fetch();
    }

    function AddFav($uid, $idR) {
        $this->dbb->beginTransaction();
        $this->ps_addFav->bindParam(':uid', $uid);
        $this->ps_addFav->bindParam(':idR', $idR);
        if ($this->ps_addFav->execute()) {
            $this->dbb->commit();
        } else {
            $this->dbb->rollBack();
        }
    }

    function getFavByID($uid) {
        $this->ps_getFavByID->bindParam(':uid', $uid);
        $this->ps_getFavByID->execute();
        return $this->ps_getFavByID->fetchAll();
    }

    function removeFav($uid, $idR) {
        $this->ps_removeFav->bindParam(':uid', $uid);
        $this->ps_removeFav->bindParam(':idR', $idR);
        $this->ps_removeFav->execute();
    }

    function insertComment($comment, $uid, $idR) {
        $this->dbb->beginTransaction();
        $this->ps_insertComment->bindParam(':comment', $comment);
        $this->ps_insertComment->bindParam(':uid', $uid);
        $this->ps_insertComment->bindParam(':idR', $idR);
        if ($this->ps_insertComment->execute()) {
            $this->dbb->commit();
        } else {
            $this->dbb->rollBack();
        }
    }

    function GetComment() {
        $this->ps_getComment->execute();
        return $this->ps_getComment->fetchAll();
    }
    function GetCommentById($idR) {
        $this->ps_getCommentbyId->bindParam(':idR', $idR);
        $this->ps_getCommentbyId->execute();
        return $this->ps_getCommentbyId->fetchAll();
    }

    function removeComment($idC) {
        $this->ps_removeComment->bindParam(':idC', $idC);
        $this->ps_removeComment->execute();
    }

    function filterByType($type) {
        $this->ps_filterbyType->bindParam(':type', $type);
        $this->ps_filterbyType->execute();
        return $this->ps_filterbyType->fetchAll();
    }

    function filterByDateA() {
        $this->ps_filterbyDateA->execute();
        return $this->ps_filterbyDateA->fetchAll();
    }

    function filterByDateD() {
        $this->ps_filterbyDateD->execute();
        return $this->ps_filterbyDateD->fetchAll();
    }
    function filterSearchByCriterea($searchKeyWord, $type, $sort){
        $searchKeyWord.='%';
        $query="SELECT * FROM `recettes` NATURAL JOIN types WHERE (NomType= :type OR :type = '')  AND (Titre like :search OR :search = '') AND Valider=1";
        if ($sort=="Last added") {
            $query.= " ORDER BY DateTimeInsert DESC";
        }
        else{
            $query.= " ORDER BY DateTimeInsert ASC";
        }
        $requete=$this->dbb->prepare($query);
        $requete->bindParam(':type', $type);
        $requete->bindParam(':search', $searchKeyWord);
        
         $requete->execute();
        return $requete->fetchAll();
    }
            function Autocomplete($keyword) {
        $keyword = $keyword . '%';
        $this->ps_autocomplete->bindParam(':keyword', $keyword);
        $this->ps_autocomplete->execute();
        return $this->ps_autocomplete->fetchAll();
    }

}
