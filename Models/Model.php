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

/* Fonction Gestionnaire*/
    public function dashboardGestionnaire()
    {
        $req = $this->bd->prepare('SELECT nom_client, nom_composante, nom_mission, nom, prenom FROM client JOIN composante USING(id_client) JOIN mission USING(id_composante) JOIN travailleavec ta USING(id_mission) JOIN PERSONNE p ON ta.id_personne = p.id_personne');
        $req->execute();
        return $req->fetchall();
    }

    public function getInterlocuteurForGestionnaire()
    {
        $req = $this->bd->prepare('SELECT nom, prenom, nom_client FROM dirige JOIN composante USING(id_composante) JOIN client USING(id_client) JOIN personne USING(id_personne)
');
        $req->execute();
        return $req->fetchall();
    }

     public function getCommercialForGestionnaire()
    {
        $req = $this->bd->prepare('SELECT nom, prenom, nom_composante FROM estdans JOIN composante USING(id_composante) JOIN personne USING(id_personne)
');
        $req->execute();
        return $req->fetchall();
    }

    public function removePrestataireForGestionnaire($id_pr)
    {
        $requete = $this->bd->prepare("DELETE FROM ACTIVITE WHERE id_personne = :id");
        $requete->bindValue(':id', (int) $id_pr, PDO::PARAM_INT);
        $requete->execute();
        $requete = $this->bd->prepare("DELETE FROM travailleAvec WHERE id_personne = :id");
        $requete->bindValue(':id', (int) $id_pr, PDO::PARAM_INT);
        $requete->execute();
        $requete = $this->bd->prepare("DELETE FROM PRESTATAIRE WHERE id_personne = :id");
        $requete->bindValue(':id', (int) $id_pr, PDO::PARAM_INT);
        $requete->execute();
        return (bool) $requete->rowCount();
    }

    public function removeInterlocuteurForGestionnaire($id_in)
    {
        $requete = $this->bd->prepare("DELETE FROM BON_DE_LIVRAISON WHERE id_personne = :id");
        $requete->bindValue(':id', (int) $id_in, PDO::PARAM_INT);
        $requete->execute();
        $requete = $this->bd->prepare("DELETE FROM dirige WHERE id_personne = :id");
        $requete->bindValue(':id', (int) $id_in, PDO::PARAM_INT);
        $requete->execute();
        $requete = $this->bd->prepare("DELETE FROM INTERLOCUTEUR WHERE id_personne = :id");
        $requete->bindValue(':id', (int) $id_in, PDO::PARAM_INT);
        $requete->execute();
        return (bool) $requete->rowCount();
    }

    public function removeCommercialForGestionnaire($id_co)
    {
        $requete = $this->bd->prepare("DELETE FROM estDans WHERE id_personne = :id_personne");
        $requete->bindValue(':id', (int) $id_co, PDO::PARAM_INT);
        $requete->execute();
        $requete = $this->bd->prepare("DELETE FROM COMMERCIAL WHERE id_personne = :id_personne");
        $requete->bindValue(':id', (int) $id_co, PDO::PARAM_INT);
        $requete->execute();
        return (bool) $requete->rowCount();
    }




}
