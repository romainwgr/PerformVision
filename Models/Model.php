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

    public function createPersonne($nom, $prenom, $email, $mdp)
    {
        $req = $this->bd->prepare('INSERT INTO PERSONNE(nom, prenom, email, mdp) VALUES(:nom, :prenom, :email, :mdp);');
        $req->bindValue(':nom', (int)$nom, PDO::PARAM_STR);
        $req->bindValue(':prenom', (int)$prenom, PDO::PARAM_STR);
        $req->bindValue(':email', (int)$email, PDO::PARAM_STR);
        $req->bindValue(':mdp', (int)$mdp, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();

    }

    /* -------------------------------------------------------------------------
                            Fonction Gestionnaire/Admin
        ------------------------------------------------------------------------*/

    public function getDashboardGestionnaire()
    {
        $req = $this->bd->prepare('SELECT nom_client, nom_composante, nom_mission, nom, prenom FROM client JOIN composante USING(id_client) JOIN mission USING(id_composante) JOIN travailleavec ta USING(id_mission) JOIN PERSONNE p ON ta.id_personne = p.id_personne');
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllInterlocuteur()
    {
        $req = $this->bd->prepare('SELECT nom, prenom, nom_client FROM dirige JOIN composante USING(id_composante) JOIN client USING(id_client) JOIN personne USING(id_personne);');
        $req->execute();
        return $req->fetchall();
    }

    public function getAllCommercial()
    {
        $req = $this->bd->prepare('SELECT nom, prenom, nom_composante FROM estdans JOIN composante USING(id_composante) JOIN personne USING(id_personne);');
        $req->execute();
        return $req->fetchall();
    }

    public function getAllPrestataire()
    {
        $req = $this->bd->prepare('SELECT nom, prenom, interne FROM PERSONNE p JOIN PRESTATAIRE pr ON p.id_personne =  pr.id_personne;');
        $req->execute();
        return $req->fetchall();
    }

    public function removePrestataire($id_pr)
    {
        $req = $this->bd->prepare("DELETE FROM ACTIVITE WHERE id_personne = :id");
        $req->bindValue(':id', (int)$id_pr, PDO::PARAM_INT);
        $req->execute();
        $req = $this->bd->prepare("DELETE FROM travailleAvec WHERE id_personne = :id");
        $req->bindValue(':id', (int)$id_pr, PDO::PARAM_INT);
        $req->execute();
        $req = $this->bd->prepare("DELETE FROM PRESTATAIRE WHERE id_personne = :id");
        $req->bindValue(':id', (int)$id_pr, PDO::PARAM_INT);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function removeInterlocuteur($id_in)
    {
        $req = $this->bd->prepare("DELETE FROM BON_DE_LIVRAISON WHERE id_personne = :id");
        $req->bindValue(':id', (int)$id_in, PDO::PARAM_INT);
        $req->execute();
        $req = $this->bd->prepare("DELETE FROM dirige WHERE id_personne = :id");
        $req->bindValue(':id', (int)$id_in, PDO::PARAM_INT);
        $req->execute();
        $req = $this->bd->prepare("DELETE FROM INTERLOCUTEUR WHERE id_personne = :id");
        $req->bindValue(':id', (int)$id_in, PDO::PARAM_INT);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function removeCommercial($id_co)
    {
        $req = $this->bd->prepare("DELETE FROM estDans WHERE id_personne = :id_personne");
        $req->bindValue(':id', (int)$id_co, PDO::PARAM_INT);
        $req->execute();
        $req = $this->bd->prepare("DELETE FROM COMMERCIAL WHERE id_personne = :id_personne");
        $req->bindValue(':id', (int)$id_co, PDO::PARAM_INT);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function addInterlocuteur($composante, $client)
    {
        $req = $this->bd->prepare("INSERT INTO interlocuteur (id_personne) SELECT id_personne FROM personne ORDER BY id_personne DESC LIMIT 1");
        $req->execute();
        $req = $this->bd->prepare("INSERT INTO dirige (id_personne, id_composante) SELECT  (SELECT id_personne FROM interlocuteur ORDER BY id_personne DESC LIMIT 1), (SELECT c.id_composante FROM COMPOSANTE c JOIN CLIENT cl ON c.id_client = cl.id_client WHERE c.nom_composante = ':nom_compo'  AND cl.nom_client = ':nom_client')");
        $req->bindValue(':nom_compo', $composante, PDO::PARAM_INT);
        $req->bindValue(':nom_client', $client, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function addPrestataire($mission, $mail)
    {
        $req = $this->bd->prepare("INSERT INTO prestataire (id_personne) SELECT id_personne FROM personne ORDER BY id_personne DESC LIMIT 1");
        $req->execute();
        $req = $this->bd->prepare("INSERT INTO travailleAvec (id_personne, id_mission) SELECT  (SELECT p.id_personne FROM PERSONNE p WHERE p.email = :email), (SELECT m.id_mission FROM MISSION m JOIN COMPOSANTE USING(id_composante) WHERE nom_mission = :nom_mission");
        $req->bindValue(':nom_mission', $mission, PDO::PARAM_STR);
        $req->execute();
        $req->bindValue(':email', $mail, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function addClient($client, $tel, $composante, $id_adresse, $email)
    {
        $req = $this->bd->prepare("INSERT INTO client(nom_client, telephone_client) VALUES( :nom_client, :tel)");
        $req->bindValue(':nom_client', $client, PDO::PARAM_STR);
        $req->bindValue(':tel', $tel, PDO::PARAM_STR);
        $req->execute();
        $req = $this->bd->prepare("INSERT INTO COMPOSANTE (id_client, nom_composante, id_adresse) SELECT (SELECT id_client FROM client ORDER BY id_client DESC LIMIT 1),:nom_compo, :id_adresse");
        $req->bindValue(':nom_compo', $composante, PDO::PARAM_STR);
        $req->bindValue(':id_adresse', $id_adresse, PDO::PARAM_INT);
        $req->execute();
        $req = $this->bd->prepare("INSERT INTO estDans(id_personne, id_composante) SELECT (SELECT p.id_personne FROM PERSONNE p WHERE p.email = :email'),  (SELECT id_composante FROM composante ORDER BY id_composante DESC LIMIT 1)");
        $req->bindValue(':email', $email, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function assignerPrestataire($email, $composante)
    {
        $req = $this->bd->prepare("INSERT INTO travailleAvec (id_personne, id_mission) SELECT  (SELECT p.id_personne FROM PERSONNE p WHERE p.email = :email), (SELECT m.id_mission FROM MISSION m JOIN COMPOSANTE USING(id_composante) WHERE nom_mission = :nom_mission')");
        $req->bindValue(':email', $email, PDO::PARAM_STR);
        $req->bindValue(':nom_mission', $composante, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function getBdlPresta($id_pr)
    {
        $req = $this->bd->prepare("SELECT id_bdl, mois, nom_mission FROM BON_DE_LIVRAISON bdl JOIN MISSION m USING(id_mission) JOIN travailleAvec ta USING(id_mission) WHERE ta.id_personne = :id");
        $req->bindValue(':id', $id_pr, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall();
    }

    /* -------------------------------------------------------------------------
                            Fonction Commercial
        ------------------------------------------------------------------------*/

    public function getDashboardCommercial($id_co)
    {
        $req = $this->bd->prepare('SELECT nom_client, nom_composante, nom_mission, nom, prenom FROM client JOIN composante c USING(id_client) JOIN mission USING(id_composante) JOIN travailleavec ta USING(id_mission) JOIN PERSONNE p ON ta.id_personne = p.id_personne JOIN estDans ed on ed.id_composante = c.id_composante WHERE ed.id_personne=:id');
        $req->bindValue(':id', $id_co, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall();
    }

    public function getInterlocuteurForCommercial($id_co)
    {
        $req = $this->bd->prepare('SELECT nom, prenom, nom_client, nom_composante FROM dirige JOIN composante USING(id_composante) JOIN client USING(id_client) JOIN personne USING(id_personne) JOIN estDans ed USING(id_composante) WHERE ed.id_personne = :id');
        $req->bindValue(':id', $id_co, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall();
    }

    public function getPrestataireForCommercial($id_co)
    {
        $req = $this->bd->prepare('SELECT nom, prenom, nom_client, nom_composante, nom_mission FROM client JOIN composante USING(id_client) JOIN mission USING(id_composante) JOIN travailleavec ta USING(id_mission) JOIN PERSONNE p ON ta.id_personne = p.id_personne JOIN estDans ed USING(id_composante) WHERE ed.id_personne = :id');
        $req->bindValue(':id', $id_co, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall();
    }

    public function getBdlPrestaForCommercial($id_pr, $id_co)
    {
        $req = $this->bd->prepare("SELECT id_bdl, mois, nom_mission FROM BON_DE_LIVRAISON bdl JOIN MISSION m USING(id_mission) JOIN travailleAvec ta USING(id_mission) JOIN COMPOSANTE USING(id_composante) JOIN estDans ed USING(id_composante) WHERE ta.id_personne = :id_pr AND ed.id_personne = :id_com");
        $req->bindValue(':id_pr', $id_pr, PDO::PARAM_INT);
        $req->bindValue(':id_com', $id_co, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall();
    }

    public function addInterlocuteurForCommercial($composante, $email, $client)
    {
        $req = $this->bd->prepare("INSERT INTO interlocuteur (id_personne) SELECT id_personne FROM personne WHERE email=:email");
        $req->bindValue(':email', $email, PDO::PARAM_STR);
        $req->execute();
        $req = $this->bd->prepare("INSERT INTO dirige (id_personne, id_composante) SELECT  (SELECT id_personne FROM interlocuteur ORDER BY id_personne DESC LIMIT 1), (SELECT c.id_composante FROM COMPOSANTE c JOIN CLIENT cl ON c.id_client = cl.id_client WHERE c.nom_composante = ':nom_compo'  AND cl.nom_client = ':nom_client');");
        $req->bindValue(':nom_compo', $composante, PDO::PARAM_INT);
        $req->bindValue(':nom_client', $client, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }


    /* -------------------------------------------------------------------------
                        Fonction Interlocuteur
    ------------------------------------------------------------------------*/

    public function dashboardInterlocuteur($id_in)
    {
        $req = $this->bd->prepare("SELECT nom_mission, date_debut, nom, prenom, id_bdl FROM mission m JOIN travailleAvec USING(id_mission) JOIN personne p USING(id_personne) JOIN bon_de_livraison bdl ON m.id_mission= bdl.id_mission WHERE bdl.id_personne = :id");
        $req->bindValue(':id', $id_in, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall();
    }

    public function getEmailCommercialForInterlocuteur($id_in)
    {
        $req = $this->bd->prepare("SELECT email FROM dirige d JOIN estDans ed USING(id_composante) JOIN personne com ON ed.id_personne = com.id_personne WHERE d.id_personne = :id");
        $req->bindValue(':id', $id_in, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall();
    }

    /**
     * Récupère les informations de l'interlocuteur client par rapport à sa mission
     * @return array|false
     */
    public function getClientContactDashboardData()
    {
        $req = $this->bd->prepare('SELECT nom_mission, date_debut, nom, prenom, id_bdl FROM mission m JOIN travailleAvec USING(id_mission) JOIN personne p USING(id_personne) JOIN bon_de_livraison bdl ON m.id_mission= bdl.id_mission WHERE bdl.id_personne = :id;');
        $req->bindValue(':id', $_SESSION['id']);
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Renvoie la liste des emails des commerciaux assignées à la mission de l'interlocuteur client
     * @param $idClientContact
     * @return void
     */
    public function getComponentCommercialsEmails($idClientContact)
    {
        $req = $this->bd->prepare('SELECT email FROM dirige d JOIN estDans ed USING(id_composante) JOIN personne com ON ed.id_personne = com.id_personne WHERE d.id_personne = :id;');
        $req->bindValue(':id', $idClientContact);
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère le mail dans la base de données grâce à l'identifiant de la personne
     * @param $id
     * @return void
     */
    function getEmailById($id)
    {
        $req = $this->bd->prepare('SELECT email FROM personne WHERE id_personne = :id;');
        $req->bindValue(':id', $id);
        $req->execute();
        $req->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Méthode permettant de vérifier que le mail saisi existe bien.
     * @param $mail
     * @return integer
     **/
    public function mailExists($mail)
    {
        $req = $this->bd->prepare('SELECT email FROM PERSONNE WHERE email = :mail;');
        $req->bindValue(':mail', $mail);
        $req->execute();
        $email = $req->fetch(PDO::FETCH_ASSOC);
        return sizeof($email) != 0;
    }

    public function getBdlPrestaForInterlocuteur($id_pr, $id_in)
    {
        $req = $this->bd->prepare("SELECT id_bdl, mois, nom_mission FROM BON_DE_LIVRAISON bdl JOIN MISSION m USING(id_mission) JOIN travailleAvec ta USING(id_mission) JOIN COMPOSANTE USING(id_composante) JOIN dirige d USING(id_composante) WHERE ta.id_personne = :id_pres AND d.id_personne = :id_inter");
        $req->bindValue(':id_inter', $id_pr, PDO::PARAM_INT);
        $req->bindValue(':id_pres', $id_in, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall();
    }

    /**
     * Vérifie que le mot de passe correspond bien au mail. Si ils correspondent, une session avec les informations de la personne lié au mail débute.
     **/
    public function checkMailPassword($mail, $password)
    {
        $req = $this->bd->prepare('SELECT * FROM PERSONNE WHERE email = :mail');
        $req->bindValue(':mail', $mail);
        $req->execute();
        $realPassword = $req->fetchAll(PDO::FETCH_ASSOC);

        if ($realPassword) {
            if ($realPassword[0]['mdp'] == $password) {
                if (isset($_SESSION)) {
                    session_destroy();
                }
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                if (isset($_SESSION['id'])) {
                    unset($_SESSION['id']);
                }
                $_SESSION['id'] = $realPassword[0]['id_personne'];
                $_SESSION['nom'] = $realPassword[0]['nom'];
                $_SESSION['prenom'] = $realPassword[0]['prenom'];
                $_SESSION['email'] = $realPassword[0]['email'];
                return true;
            }
        }
        return false;
    }

    /**
     * Méthode vérifiant les rôles de la personne. Si il n'y a qu'un seul rôle elle retourne simplement le nom de ce rôle. Si il y a plusieurs rôles, une liste des rôles sous forme de tableau.
     **/
    public function hasSeveralRoles()
    {
        $roles = [];
        $req = $this->bd->prepare('SELECT * FROM PRESTATAIRE WHERE id_personne = :id');
        $req->bindValue(':id', $_SESSION['id']);
        $req->execute();
        if ($req->fetch(PDO::FETCH_ASSOC)) {
            $roles[] = 'prestataire';
        }

        $req = $this->bd->prepare('SELECT * FROM GESTIONNAIRE WHERE id_personne = :id');
        $req->bindValue(':id', $_SESSION['id']);
        $req->execute();
        if ($req->fetch(PDO::FETCH_ASSOC)) {
            $roles[] = 'gestionnaire';
        }

        $req = $this->bd->prepare('SELECT * FROM COMMERCIAL WHERE id_personne = :id');
        $req->bindValue(':id', $_SESSION['id']);
        $req->execute();
        if ($req->fetch(PDO::FETCH_ASSOC)) {
            $roles[] = 'commercial';
        }

        $req = $this->bd->prepare('SELECT * FROM INTERLOCUTEUR WHERE id_personne = :id');
        $req->bindValue(':id', $_SESSION['id']);
        $req->execute();
        if ($req->fetch(PDO::FETCH_ASSOC)) {
            $roles[] = 'interlocuteur';
        }

        $req = $this->bd->prepare('SELECT * FROM ADMINISTRATEUR WHERE id_personne = :id');
        $req->bindValue(':id', $_SESSION['id']);
        $req->execute();
        if ($req->fetch(PDO::FETCH_ASSOC)) {
            $roles[] = 'administrateur';
        }

        if (sizeof($roles) > 1) {
            return ['roles' => $roles];
        }

        return $roles[0];
    }
}
