<?php

class Model
{
    /**
     * Attribut contenant l'instance PDO
     */
    private $bd;

    /**
     * Attribut statique qui contiendra l'unique instance de Model
     */
    private static $instance = null;

    /**
     * Constructeur : effectue la connexion à la base de données.
     */
    private function __construct()
    {
        include "credentials.php";
        $this->bd = new PDO($dsn, $login, $mdp);
        $this->bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->bd->query("SET nameS 'utf8'");
    }

    /**
     * Méthode permettant de récupérer un modèle car le constructeur est privé (Implémentation du Design Pattern Singleton)
     */
    public static function getModel()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function mailExists($mail){
        $req = $this->bd->prepare('SELECT email FROM PERSONNE WHERE email = :mail;');
        $req->bindValue(':mail', $mail);
        $req->execute();
        $email = $req->fetch(PDO::FETCH_ASSOC);
        return sizeof($email) != 0;
    }

    public function checkMailPassword($mail, $password){
        $req = $this->bd->prepare('SELECT mdp FROM PERSONNE WHERE email = :mail');
        $req->bindValue(':mail', $mail);
        $req->execute();
        $realPassword = $req->fetch(PDO::FETCH_ASSOC);
        return $realPassword['mdp'] == $password;
    }
}
