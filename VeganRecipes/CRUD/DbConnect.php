<?php
define("HOST", "127.0.0.1");
define("DBNAME", "myrecipes");
define("USER", "UserRecipes");
define("PASSWORD", "Super");

class DbConnect {

    private $ps_getRegistration = null;
    private $ps_getTypes = null;
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
    function GetTypes(){
        $this->ps_getTypes->execute();
        return $this->ps_getTypes->fetch(); 
    }

}
