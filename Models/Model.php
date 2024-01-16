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
        $this->bd->query("SET NAMES 'utf8'");
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
        $req->bindValue(':nom', $nom);
        $req->bindValue(':prenom', $prenom);
        $req->bindValue(':email', $email);
        $req->bindValue(':mdp', $mdp);
        $req->execute();
        return (bool)$req->rowCount();

    }

    /* -------------------------------------------------------------------------
                            Fonction Gestionnaire/Admin
        ------------------------------------------------------------------------*/

    public function getDashboardGestionnaire()
    {
        $req = $this->bd->prepare("SELECT c.nom_client, co.nom_composante, m.nom_mission, COALESCE(p.nom, 'Aucun') AS nom, COALESCE(p.prenom, 'Aucun') AS prenom 
        FROM mission m
            JOIN composante co ON m.id_composante = co.id_composante 
            JOIN client c ON co.id_client = c.id_client 
            LEFT JOIN travailleavec ta ON m.id_mission = ta.id_mission 
            LEFT JOIN personne p ON ta.id_personne = p.id_personne;");
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
        return $req->fetchall()[0];
    }

    /* -------------------------------------------------------------------------
                            Fonction Composante
        ------------------------------------------------------------------------*/

    public function getPrestatairesComposante($id)
    {
        $req = $this->bd->prepare('SELECT DISTINCT id_personne, nom, prenom
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
        $req = $this->bd->prepare('SELECT DISTINCT id_personne, nom, prenom
       FROM PERSONNE JOIN COMMERCIAL USING(id_personne) 
           JOIN ESTDANS USING(id_personne) 
       WHERE id_composante = :id');

        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall();
    }

    public function getInterlocuteursComposante($id)
    {
        $req = $this->bd->prepare('SELECT DISTINCT id_personne, nom, prenom
       FROM PERSONNE JOIN INTERLOCUTEUR USING(id_personne) 
           JOIN DIRIGE USING(id_personne) 
       WHERE id_composante = :id');

        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall();
    }

    public function getInterlocuteursSociete($id)
    {
        $req = $this->bd->prepare('SELECT DISTINCT id_personne, nom, prenom
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
        $req = $this->bd->prepare('SELECT id_composante, nom_composante FROM COMPOSANTE JOIN CLIENT using(id_client) WHERE id_client = :id');
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall();
    }

    public function assignerInterlocuteurComposante($composante, $client, $email)
    {
        $req = $this->bd->prepare("INSERT INTO dirige (id_personne, id_composante) SELECT  (SELECT id_personne FROM PERSONNE WHERE email=:email), (SELECT c.id_composante FROM COMPOSANTE c JOIN CLIENT cl ON c.id_client = cl.id_client WHERE c.nom_composante = :nom_compo  AND cl.nom_client = :nom_client)");
        $req->bindValue(':nom_compo', $composante, PDO::PARAM_STR);
        $req->bindValue(':nom_client', $client, PDO::PARAM_STR);
        $req->bindValue(':email', $email, PDO::PARAM_STR);
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
        $req->bindValue(':email', $email);
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

    public function addComposante($libelleVoie, $cp, $numVoie, $nomVoie, $nom_client, $nom_compo)
    {
        $req = $this->bd->prepare("INSERT INTO ADRESSE(numero, nom_voie, id_type_voie, id_localite) SELECT :num, :nomVoie, (SELECT id_type_voie FROM TypeVoie WHERE libelle = :libelleVoie), (SELECT id_localite FROM localite WHERE cp = :cp)");
        $req->bindValue(':num', $numVoie, PDO::PARAM_STR);
        $req->bindValue(':nomVoie', $nomVoie, PDO::PARAM_STR);
        $req->bindValue(':libelleVoie', $libelleVoie, PDO::PARAM_STR);
        $req->bindValue(':cp', $cp, PDO::PARAM_STR);
        $req->execute();
        $req = $this->bd->prepare("INSERT INTO COMPOSANTE(nom_composante, id_adresse, id_client) SELECT :nom_compo, (SELECT id_adresse FROM adresse ORDER BY id_adresse DESC LIMIT 1), (SELECT id_client FROM CLIENT WHERE nom_client = :nom_client)");
        $req->bindValue(':nom_client', $nom_client, PDO::PARAM_STR);
        $req->bindValue(':nom_compo', $nom_compo, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function addMission($type, $nom, $date, $nom_compo, $nom_client)
    {
        $req = $this->bd->prepare("INSERT INTO MISSION (type_bdl, nom_mission, date_debut, id_composante) SELECT :type, :nom, :date, (SELECT id_composante FROM COMPOSANTE JOIN CLIENT USING(id_client) WHERE nom_client = :nom_client and nom_composante = :nom_composante)");
        $req->bindValue(':nom', $nom);
        $req->bindValue(':type', $type);
        $req->bindValue(':date', $date);
        $req->bindValue(':nom_composante', $nom_compo);
        $req->bindValue(':nom_client', $nom_client);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function addNbHeureActivite($commentaire, $id_bdl, $id_personne, $date_bdl, $nb_heure)
    {
        $req = $this->bd->prepare("INSERT INTO ACTIVITE (commentaire, id_bdl, id_personne, date_bdl) VALUES(:commentaire, :id_bdl, :id_personne, :date_bdl)");
        $req->bindValue(':commentaire', $commentaire);
        $req->bindValue(':id_bdl', $id_bdl);
        $req->bindValue(':id_personne', $id_personne);
        $req->bindValue(':date_bdl', $date_bdl);
        $req->execute();
        $req = $this->bd->prepare("INSERT INTO NB_HEURE SELECT (SELECT id_activite FROM activite ORDER BY id_activite DESC LIMIT 1), :nb_heure");
        $req->bindValue(':nb_heure', $nb_heure);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function addDemiJournee($commentaire, $id_bdl, $id_personne, $date_bdl, $nb_dj)
    {
        $req = $this->bd->prepare("INSERT INTO ACTIVITE (commentaire, id_bdl, id_personne, date_bdl) VALUES(:commentaire, :id_bdl, :id_personne, :date_bdl)");
        $req->bindValue(':commentaire', $commentaire);
        $req->bindValue(':id_bdl', $id_bdl);
        $req->bindValue(':id_personne', $id_personne);
        $req->bindValue(':date_bdl', $date_bdl);
        $req->execute();
        $req = $this->bd->prepare("INSERT INTO DEMI_JOUR SELECT (SELECT id_activite FROM activite ORDER BY id_activite DESC LIMIT 1), :nb_dj");
        $req->bindValue(':nb_dj', $nb_dj);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function addJourneeJour($commentaire, $id_bdl, $id_personne, $date_bdl, $nb_jour)
    {
        $req = $this->bd->prepare("INSERT INTO ACTIVITE (commentaire, id_bdl, id_personne, date_bdl) VALUES(:commentaire, :id_bdl, :id_personne, :date_bdl)");
        $req->bindValue(':commentaire', $commentaire);
        $req->bindValue(':id_bdl', $id_bdl);
        $req->bindValue(':id_personne', $id_personne);
        $req->bindValue(':date_bdl', $date_bdl);
        $req->execute();
        $req = $this->bd->prepare("INSERT INTO JOUR(id_activite, journee) SELECT (SELECT id_activite FROM activite ORDER BY id_activite DESC LIMIT 1), :nb_jour");
        $req->bindValue(':nb_jour', $nb_jour);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function addBdlInMission($nom_mission, $nom_composante, $mois, $id_prestataire)
    {
        try{
            $req = $this->bd->prepare("INSERT INTO BON_DE_LIVRAISON(mois, id_mission, id_prestataire) SELECT :mois, 
                                                                               (SELECT id_mission FROM MISSION JOIN COMPOSANTE USING(id_composante) WHERE nom_mission = :mission and nom_composante = :composante),
                                                                               :id_prestataire");
            $req->bindValue(':mission', $nom_mission);
            $req->bindValue(':composante', $nom_composante);
            $req->bindValue(':mois', $mois);
            $req->bindValue(':id_prestataire', $id_prestataire);
            $req->execute();
            return (bool)$req->rowCount();
        }catch (PDOException $e){
            error_log('Erreur PHP : ' . $e->getMessage());
            echo 'Une des informations est mauvaise';
        }
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

    public function getAllBdlPrestataire($id_pr)
    {
        $req = $this->bd->prepare("SELECT id_bdl, mois, nom_mission FROM bon_de_livraison JOIN prestataire ON id_personne = id_prestataire JOIN MISSION USING(id_mission) WHERE id_personne = :id");
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
        $req->bindValue(':id', $id);
        $req->bindValue(':num', $num);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function setNomVoieAdresse($id, $nom)
    {
        $req = $this->bd->prepare("UPDATE ADRESSE SET nomVoie = :nom WHERE id_adresse = :id");
        $req->bindValue(':id', $id);
        $req->bindValue(':nom', $nom);
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
        $req = $this->bd->prepare("UPDATE TYPEVOIE SET libelle = :libelle WHERE id_type_voie = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':libelle', $libelle, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function setCommentaireActivite($id, $commentaire)
    {
        $req = $this->bd->prepare("UPDATE ACTIVITE SET commentaire = :commentaire WHERE id_activite = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':commentaire', $commentaire, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function setDateBdlActivite($id, $date)
    {
        $req = $this->bd->prepare("UPDATE ACTIVITE SET date_bdl = :date WHERE id_activite = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':date', $date, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }


    public function setNbHeure($id, $heure)
    {
        $req = $this->bd->prepare("UPDATE NB_HEURE SET nb_heure = :heure WHERE id_activite = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':heure', $heure, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function setDebutHeurePlageHoraire($id, $heure)
    {
        $req = $this->bd->prepare("UPDATE PLAGE_HORAIRE SET debut_heure = :heure WHERE id_activite = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':heure', $heure, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function setFinHeurePlageHoraire($id, $heure)
    {
        $req = $this->bd->prepare("UPDATE PLAGE_HORAIRE SET fin_heure = :heure WHERE id_activite = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':heure', $heure, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function setDemiJournee($id, $demi_journee)
    {
        $req = $this->bd->prepare("UPDATE DEMI_JOUR SET nb_demi_journee = :dj WHERE id_activite = :id");
        $req->bindValue(':id', $id);
        $req->bindValue(':dj', $demi_journee);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function setJourneeJour($id, $jour)
    {
        $req = $this->bd->prepare("UPDATE JOUR SET journee = :jour WHERE id_activite = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':jour', $jour, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function setDebutHeureSuppJour($id, $debut)
    {
        $req = $this->bd->prepare("UPDATE JOUR SET debut_heure_supp = :debut WHERE id_activite = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':debut', $debut, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function setFinHeureSuppJour($id, $fin)
    {
        $req = $this->bd->prepare("UPDATE JOUR SET fin_heure_supp = :fin WHERE id_activite = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':fin', $fin, PDO::PARAM_STR);
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

    public function getDashboardPrestataire($id_prestataire)
    {
        $req = $this->bd->prepare('SELECT nom_client, nom_composante, nom_mission, id_mission FROM client JOIN composante c USING(id_client) JOIN mission USING(id_composante) JOIN travailleavec ta USING(id_mission) JOIN PERSONNE p ON ta.id_personne = p.id_personne WHERE ta.id_personne=:id');
        $req->bindValue(':id', $id_prestataire);
        $req->execute();
        return $req->fetchall(PDO::FETCH_ASSOC);
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

    public function getBdlType($id_bdl)
    {
        $req = $this->bd->prepare("SELECT id_bdl, type_bdl FROM BON_DE_LIVRAISON JOIN MISSION USING(id_mission) WHERE id_bdl = :id");
        $req->bindValue(':id', $id_bdl);
        $req->execute();
        return $req->fetch();
    }

    public function getBdlsOfPrestataireByIdMission($id_mission, $id_prestataire)
    {
        $req = $this->bd->prepare("SELECT id_bdl, nom_mission, mois FROM BON_DE_LIVRAISON JOIN MISSION USING(id_mission) WHERE id_mission = :id_mission and id_prestataire = :id_prestataire");
        $req->bindValue(':id_mission', $id_mission);
        $req->bindValue(':id_prestataire', $id_prestataire);
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getIdActivite($date_activite, $id_bdl)
    {
        $req = $this->bd->prepare('SELECT id_activite FROM activite WHERE id_bdl = :id_bdl and date_bdl = :date');
        $req->bindValue(':id_bdl', $id_bdl);
        $req->bindValue(':date', $date_activite);
        $req->execute();
        return $req->fetch()[0];
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
        return $req->fetch()[0] == 't';
    }

    public function checkComposanteExiste($nom_compo, $nom_client)
    {
        $req = $this->bd->prepare('SELECT EXISTS (SELECT 1 FROM COMPOSANTE JOIN CLIENT USING(id_client) WHERE nom_composante = :nom_composante AND nom_client = :nom_client) AS composante_existe');
        $req->bindValue(':nom_composante', $nom_compo);
        $req->bindValue(':nom_client', $nom_client);
        $req->execute();
        return $req->fetch()[0] == 't';
    }

    public function checkSocieteExiste($nom_client)
    {
        $req = $this->bd->prepare('SELECT EXISTS (SELECT 1 FROM CLIENT WHERE nom_client = :nom_client) AS client_existe');
        $req->bindValue(':nom_client', $nom_client);
        $req->execute();
        return $req->fetch()[0] == 't';
    }

    public function checkMissionExiste($nom_mission, $nom_compo)
    {
        $req = $this->bd->prepare('SELECT EXISTS (SELECT 1 FROM MISSION JOIN COMPOSANTE USING(id_composante) WHERE nom_composante = :nom_compo AND nom_mission = :nom_mission) AS mission_existe');
        $req->bindValue(':nom_composante', $nom_compo);
        $req->bindValue(':nom_mission', $nom_mission);
        $req->execute();
        return $req->fetch()[0] == 't';
    }

    public function checkInterlocuteurExiste($email)
    {
        $req = $this->bd->prepare('SELECT EXISTS (SELECT 1 FROM PERSONNE JOIN INTERLOCUTEUR USING(id_personne) WHERE email = :email) AS interlocuteur_existe');
        $req->bindValue(':email', $email);
        $req->execute();
        return $req->fetch()[0] == 't';
    }

    public function checkCommercialExiste($email)
    {
        $req = $this->bd->prepare('SELECT EXISTS (SELECT 1 FROM PERSONNE JOIN COMMERCIAL USING(id_personne) WHERE email = :email) AS commercial_existe');
        $req->bindValue(':email', $email);
        $req->execute();
        return $req->fetch()[0] == 't';
    }

    public function checkPrestataireExiste($email)
    {
        $req = $this->bd->prepare('SELECT EXISTS (SELECT 1 FROM PERSONNE JOIN PRESTATAIRE USING(id_personne) WHERE email = :email) AS prestataire_existe');
        $req->bindValue(':email', $email);
        $req->execute();
        return $req->fetch()[0] == 't';
    }

    public function checkGestionnaireExiste($email)
    {
        $req = $this->bd->prepare('SELECT EXISTS (SELECT 1 FROM PERSONNE JOIN GESTIONNAIRE USING(id_personne) WHERE email = :email) AS gestionnaire_existe');
        $req->bindValue(':email', $email);
        $req->execute();
        return $req->fetch()[0] == 't';
    }

    public function checkActiviteExiste($id_bdl, $date_activite)
    {
        $req = $this->bd->prepare('SELECT EXISTS (SELECT 1 FROM ACTIVITE WHERE id_bdl = :id_bdl and date_bdl = :date_activite)');
        $req->bindValue(':id_bdl', $id_bdl);
        $req->bindValue(':date_activite', $date_activite);
        $req->execute();
        return $req->fetch()[0] == 't';
    }
}
