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
        $req = $this->bd->prepare('SELECT nom_client AS id, nom_composante, nom_mission, nom, prenom FROM client JOIN composante USING(id_client) JOIN mission USING(id_composante) JOIN travailleavec ta USING(id_mission) JOIN PERSONNE p ON ta.id_personne = p.id_personne');
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllInterlocuteurs()
    {
        $req = $this->bd->prepare('SELECT personne.id_personne AS id, nom, prenom, nom_client FROM dirige JOIN composante USING(id_composante) JOIN client USING(id_client) JOIN personne USING(id_personne);');
        $req->execute();
        return $req->fetchall();
    }

    public function getAllCommerciaux()
    {
        $req = $this->bd->prepare('SELECT personne.id_personne AS id, nom, prenom, nom_composante FROM estdans JOIN composante USING(id_composante) JOIN personne USING(id_personne);');
        $req->execute();
        return $req->fetchall();
    }

    public function getAllPrestataires()
    {
        $req = $this->bd->prepare('SELECT p.id_personne AS id, nom, prenom, interne FROM PERSONNE p JOIN PRESTATAIRE pr ON p.id_personne =  pr.id_personne;');
        $req->execute();
        return $req->fetchall();
    }

    public function getAllClients()
    {
        $req = $this->bd->prepare('SELECT id_client AS id, nom_client, telephone_client FROM CLIENT;');
        $req->execute();
        return $req->fetchall();
    }

    public function getInfosPersonne($id)
    {
        $req = $this->bd->prepare('SELECT id_personne, nom, prenom, email FROM PERSONNE WHERE id_personne = :id');
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall();
    }

    /* -------------------------------------------------------------------------
                            Fonction Composante
        ------------------------------------------------------------------------*/

    public function getPrestatairesComposante($id)
    {
        $req = $this->bd->prepare('SELECT DISTINCT id_personne AS id, nom, prenom
       FROM PERSONNE JOIN PRESTATAIRE USING(id_personne) 
           JOIN TRAVAILLEAVEC USING(id_personne) 
           JOIN MISSION USING(id_mission)
       WHERE id_composante = :id');

        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall();
    }

    public function getCommerciauxComposante($id)
    {
        $req = $this->bd->prepare('SELECT DISTINCT id_personne AS id, nom, prenom
       FROM PERSONNE JOIN COMMERCIAL USING(id_personne) 
           JOIN ESTDANS USING(id_personne) 
       WHERE id_composante = :id');

        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall();
    }

    public function getInterlocuteursComposante($id)
    {
        $req = $this->bd->prepare('SELECT DISTINCT id_personne AS id, nom, prenom
       FROM PERSONNE JOIN INTERLOCUTEUR USING(id_personne) 
           JOIN DIRIGE USING(id_personne) 
       WHERE id_composante = :id');

        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall();
    }

    public function getInterlocuteursSociete($id)
    {
        $req = $this->bd->prepare('SELECT DISTINCT id_personne AS id, nom, prenom
       FROM PERSONNE JOIN INTERLOCUTEUR USING(id_personne) 
           JOIN DIRIGE USING(id_personne) JOIN COMPOSANTE USING(id_composante) JOIN CLIENT using(id_client) WHERE id_client = :id');

        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall();
    }

    public function getBdlComposante($id)
    {
        $req = $this->bd->prepare('SELECT DISTINCT personne.id_personne AS id, id_bdl, nom, prenom, mois
       FROM PERSONNE JOIN PRESTATAIRE USING(id_personne) 
           JOIN TRAVAILLEAVEC USING(id_personne)
           JOIN MISSION USING(id_mission) 
           JOIN BON_DE_LIVRAISON USING(id_mission)
       WHERE id_composante = :id');

        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall();
    }

    public function getAllComposantes()
    {
        $req = $this->bd->prepare('SELECT id_composante AS id, nom_composante, nom_client FROM CLIENT JOIN COMPOSANTE using(id_client)');
        $req->execute();
        return $req->fetchall();
    }

    public function getInfosComposante($id)
    {
        $req = $this->bd->prepare('SELECT id_composante, nom_composante, nom_client, numero, nom_voie, cp, ville, libelle
       FROM CLIENT JOIN COMPOSANTE using(id_client) JOIN ADRESSE USING(id_adresse) JOIN LOCALITE USING(id_localite) JOIN TYPEVOIE USING(id_type_voie) WHERE id_composante = :id');
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall()[0];
    }

    public function getInfosSociete($id)
    {
        $req = $this->bd->prepare('SELECT id_client, nom_client, telephone_client FROM CLIENT WHERE id_client = :id');
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall()[0];
    }

    public function getComposantesSociete($id)
    {
        $req = $this->bd->prepare('SELECT id_composante AS id, nom_composante FROM COMPOSANTE JOIN CLIENT using(id_client) WHERE id_client = :id');
        $req->bindValue(':id', $id, PDO::PARAM_INT);
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

    public function assignerInterlocuteurComposante($composante, $client, $email)
    {
        $req = $this->bd->prepare("INSERT INTO dirige (id_personne, id_composante) SELECT  (SELECT id_personne FROM PERSONNE WHERE email=:email), (SELECT c.id_composante FROM COMPOSANTE c JOIN CLIENT cl ON c.id_client = cl.id_client WHERE c.nom_composante = ':nom_compo'  AND cl.nom_client = ':nom_client')");
        $req->bindValue(':nom_compo', $composante, PDO::PARAM_STR);
        $req->bindValue(':nom_client', $client, PDO::PARAM_STR);
        $req->bindValue(':email', $client, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function addPrestataire($email)
    {
        $req = $this->bd->prepare("INSERT INTO PRESTATAIRE (id_personne) SELECT id_personne FROM personne WHERE email = :email");
        $req->bindValue(':email', $email, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function addInterlocuteur($email)
    {
        $req = $this->bd->prepare("INSERT INTO INTERLOCUTEUR (id_personne) SELECT id_personne FROM personne WHERE email = :email");
        $req->bindValue(':email', $email, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function addCommercial($email)
    {
        $req = $this->bd->prepare("INSERT INTO COMMERCIAL (id_personne) SELECT id_personne FROM personne WHERE email = :email");
        $req->bindValue(':email', $email, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function addClient($client, $tel)
    {
        $req = $this->bd->prepare("INSERT INTO client(nom_client, telephone_client) VALUES( :nom_client, :tel)");
        $req->bindValue(':nom_client', $client, PDO::PARAM_STR);
        $req->bindValue(':tel', $tel, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function addComposante($libelle, $ville, $cp, $numVoie, $nomVoie, $nom_client, $nom_compo)
    {
        $req = $this->bd->prepare("INSERT INTO TYPEVOIE(libelle) VALUES(:libelle)");
        $req->bindValue(':libelle', $libelle, PDO::PARAM_STR);
        $req->execute();
        $req = $this->bd->prepare("INSERT INTO LOCALITE(cp, ville) VALUES(:cp, :ville)");
        $req->bindValue(':ville', $ville, PDO::PARAM_STR);
        $req->bindValue(':cp', $cp, PDO::PARAM_STR);
        $req->execute();
        $req = $this->bd->prepare("INSERT INTO ADRESSE(numero, nomVoie, id, id_localite) SELECT :num, :nomVoie, (SELECT id_typevoie FROM TypeVoie ORDER BY id_typevoie DESC LIMIT 1), (SELECT id_localite FROM localite ORDER BY id_localite DESC LIMIT 1)");
        $req->bindValue(':num', $numVoie, PDO::PARAM_STR);
        $req->bindValue(':nomVoie', $nomVoie, PDO::PARAM_STR);
        $req->execute();
        $req = $this->bd->prepare("INSERT INTO COMPOSANTE(nom_composante, id_adresse, id_client) SELECT :nom_compo, (SELECT id_adresse FROM adresse ORDER BY id_adresse DESC LIMIT 1), (SELECT id_client FROM CLIENT WHERE nom_client = :nom_client)");
        $req->bindValue(':nom_client', $nom_client, PDO::PARAM_STR);
        $req->bindValue(':nom_compo', $nom_compo, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function addMission($type, $nom, $date, $nom_compo, $nom_client)
    {
        $req = $this->bd->prepare("INSERT INTO MISSION (type_bdl, nom_mission, date_debut, id_composante) SELECT :type, :nom, :date, (SELECT nom_composante FROM COMPOSANTE JOIN CLIENT USING(id_client) WHERE nom_client = :nom_client and :nom_composante)");
        $req->bindValue(':nom', $nom, PDO::PARAM_STR);
        $req->bindValue(':type', $type, PDO::PARAM_STR);
        $req->bindValue(':date', $date, PDO::PARAM_STR);
        $req->bindValue(':nom_compo', $nom_compo, PDO::PARAM_STR);
        $req->bindValue(':nom_client', $nom_client, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function assignerPrestataire($email, $mission)
    {
        $req = $this->bd->prepare("INSERT INTO travailleAvec (id_personne, id_mission) SELECT  (SELECT p.id_personne FROM PERSONNE p WHERE p.email = :email), (SELECT m.id_mission FROM MISSION m JOIN COMPOSANTE USING(id_composante) WHERE nom_mission = :nom_mission')");
        $req->bindValue(':email', $email, PDO::PARAM_STR);
        $req->bindValue(':nom_mission', $mission, PDO::PARAM_STR);
        $req->execute();
        $req = $this->bd->prepare("INSERT INTO BON_DE_LIVRAISON(id_personne, id_mission)  SELECT  (SELECT p.id_personne FROM PERSONNE p WHERE p.email = :email),  (SELECT m.id_mission FROM MISSION m JOIN COMPOSANTE USING(id_composante) WHERE nom_mission = :nom_mission')");
        $req->bindValue(':email', $email, PDO::PARAM_STR);
        $req->bindValue(':nom_mission', $mission, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function assignerCommercial($email, $composante, $client)
    {
        $req = $this->bd->prepare("INSERT INTO estDans (id_personne, id_composante) SELECT  (SELECT p.id_personne FROM PERSONNE p WHERE p.email = :email), (SELECT c.id_composante FROM COMPOSANTE JOIN CLIENT USING(id_client) WHERE nom_composante = :composante AND nom_client = :client')");
        $req->bindValue(':email', $email, PDO::PARAM_STR);
        $req->bindValue(':composante', $composante, PDO::PARAM_STR);
        $req->bindValue(':client', $client, PDO::PARAM_STR);
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

    public function setNomPersonne($id, $nom)
    {
        $req = $this->bd->prepare("UPDATE PERSONNE SET nom = :nom WHERE id_personne = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':nom', $nom, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function setPrenomPersonne($id, $prenom)
    {
        $req = $this->bd->prepare("UPDATE PERSONNE SET prenom = :prenom WHERE id_personne = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':prenom', $prenom, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function setEmailPersonne($id, $email)
    {
        $req = $this->bd->prepare("UPDATE PERSONNE SET email = :email WHERE id_personne = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':email', $email, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function setMdpPersonne($id, $mdp)
    {
        $req = $this->bd->prepare("UPDATE PERSONNE SET mdp = :mdp WHERE id_personne = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':mdp', $mdp, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function setNomClient($id, $nom)
    {
        $req = $this->bd->prepare("UPDATE CLIENT SET nom_client = :nom WHERE id_client = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':nom', $nom, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function setTelClient($id, $tel)
    {
        $req = $this->bd->prepare("UPDATE CLIENT SET telephone_client = :tel WHERE id_client = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':tel', $tel, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function setNomComposante($id, $nom)
    {
        $req = $this->bd->prepare("UPDATE PERSONNE SET nom_composante = :nom WHERE id_composante = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':nom', $nom, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function setNumeroAdresse($id, $num)
    {
        $req = $this->bd->prepare("UPDATE ADRESSE SET numero = :num WHERE id_adresse = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':num', $num, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function setNomVoieAdresse($id, $num)
    {
        $req = $this->bd->prepare("UPDATE ADRESSE SET nomVoie = :nom WHERE id_adresse = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':num', $num, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function setCpLocalite($id, $cp)
    {
        $req = $this->bd->prepare("UPDATE LOCALITE SET cp = :cp WHERE id_adresse = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':cp', $cp, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function setVilleLocalite($id, $ville)
    {
        $req = $this->bd->prepare("UPDATE LOCALITE SET ville = :ville WHERE id_adresse = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':ville', $ville, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function setLibelleTypevoie($id, $libelle)
    {
        $req = $this->bd->prepare("UPDATE TYPEVOIE SET libelle = :libelle WHERE id_adresse = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':libelle', $libelle, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
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


    /* -------------------------------------------------------------------------
                            Fonction Prestataire
        ------------------------------------------------------------------------*/

    public function getInterlocuteurForPrestataire($id_pr)
    {
        $req = $this->bd->prepare('SELECT nom, prenom, nom_client, nom_composante FROM dirige d JOIN composante USING(id_composante) JOIN client USING(id_client) JOIN personne p ON p.id_personne = d.id_personne  JOIN MISSION m USING(id_composante) JOIN travailleAvec ta USING(id_mission) WHERE ta.id_personne = :id');
        $req->bindValue(':id', $id_pr, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall();
    }

    /* -------------------------------------------------------------------------
                            AUTRE
        ------------------------------------------------------------------------*/
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

    public function checkPersonneExiste($email)
    {
        $req = $this->bd->prepare('SELECT EXISTS (SELECT 1 FROM PERSONNE WHERE email = :email) AS personne_existe;');
        $req->bindValue(':email', $email);
        $req->execute(); 
        return $req->fetchall();
    }
}
