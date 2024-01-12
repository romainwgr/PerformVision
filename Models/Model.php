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

/* -------------------------------------------------------------------------
                        Fonction Gestionnaire
    ------------------------------------------------------------------------*/

    public function getDashboardGestionnaire()
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

    public function addInterlocuteurForGestionnaire($composante, $client)
    {
        $requete = $this->bd->prepare("INSERT INTO interlocuteur (id_personne) SELECT id_personne FROM personne ORDER BY id_personne DESC LIMIT 1");
        $requete->execute();
        $requete = $this->bd->prepare("INSERT INTO dirige (id_personne, id_composante) SELECT  (SELECT id_personne FROM interlocuteur ORDER BY id_personne DESC LIMIT 1), (SELECT c.id_composante FROM COMPOSANTE c JOIN CLIENT cl ON c.id_client = cl.id_client WHERE c.nom_composante = ':nom_compo'  AND cl.nom_client = ':nom_client')");
        $requete->bindValue(':nom_compo', $composante, PDO::PARAM_INT);
        $requete->bindValue(':nom_client', $client, PDO::PARAM_STR);
        $requete->execute();
        return (bool) $requete->rowCount();
    }

    public function addPrestataireForGestionnaire($mission,$mail)
    {
        $requete = $this->bd->prepare("INSERT INTO prestataire (id_personne) SELECT id_personne FROM personne ORDER BY id_personne DESC LIMIT 1");
        $requete->execute();
        $requete = $this->bd->prepare("INSERT INTO travailleAvec (id_personne, id_mission) SELECT  (SELECT p.id_personne FROM PERSONNE p WHERE p.email = :email), (SELECT m.id_mission FROM MISSION m JOIN COMPOSANTE USING(id_composante) WHERE nom_mission = :nom_mission");
        $requete->bindValue(':nom_mission', $mission, PDO::PARAM_STR);
        $requete->execute();
        $requete->bindValue(':email', $mail, PDO::PARAM_STR);
        $requete->execute();
        return (bool) $requete->rowCount();
    }

    public function addClientForGestionnaire($client,$tel,$composante,$id_adresse,$email)
    {
        $requete = $this->bd->prepare("INSERT INTO client(nom_client, telephone_client) VALUES( :nom_client, :tel)");
        $requete->bindValue(':nom_client', $client, PDO::PARAM_STR);
        $requete->bindValue(':tel', $tel, PDO::PARAM_STR);
        $requete->execute();
        $requete = $this->bd->prepare("INSERT INTO COMPOSANTE (id_client, nom_composante, id_adresse) SELECT (SELECT id_client FROM client ORDER BY id_client DESC LIMIT 1),:nom_compo, :id_adresse");
        $requete->bindValue(':nom_compo', $composante, PDO::PARAM_STR);
        $requete->bindValue(':id_adresse', $id_adresse, PDO::PARAM_INT);
        $requete->execute();
        $requete = $this->bd->prepare("INSERT INTO estDans(id_personne, id_composante) SELECT (SELECT p.id_personne FROM PERSONNE p WHERE p.email = :email'),  (SELECT id_composante FROM composante ORDER BY id_composante DESC LIMIT 1)");
        $requete->bindValue(':email', $email, PDO::PARAM_STR);
        $requete->execute();
        return (bool) $requete->rowCount();
    }

    public function assignerPrestataire($email, $composante)
    {
        $requete = $this->bd->prepare("INSERT INTO travailleAvec (id_personne, id_mission) SELECT  (SELECT p.id_personne FROM PERSONNE p WHERE p.email = :email), (SELECT m.id_mission FROM MISSION m JOIN COMPOSANTE USING(id_composante) WHERE nom_mission = :nom_mission')");
        $requete->bindValue(':email', $email, PDO::PARAM_STR);
        $requete->bindValue(':nom_mission', $composante, PDO::PARAM_STR);
        $requete->execute();
        return (bool) $requete->rowCount();
    }
    
      public function getBdlPrestaForGestionnaire($id_pr)
    {
        $requete = $this->bd->prepare("SELECT id_bdl, mois, nom_mission FROM BON_DE_LIVRAISON bdl JOIN MISSION m USING(id_mission) JOIN travailleAvec ta USING(id_mission) WHERE ta.id_personne = :id");
        $requete->bindValue(':id', $id_pr, PDO::PARAM_INT);
        $requete->execute();
        return $req->fetchall();
    }


    






}
