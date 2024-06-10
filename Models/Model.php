<?php
/**
 * @brief Classe Model contenant le modèle de l'application permettant d'effectuer des requêtes sur la base de données
 *
 */
class Model
{

    /**
     * @var PDO $bd Instance de PDO pour la connexion à la base de données.
     */
    private $bd;


    /**
     * @var Model $instance Instance unique de la classe Model.
     */
    private static $instance = null;

    /**
     * Constructeur privé pour implémenter le pattern Singleton.
     *
     * Établit la connexion à la base de données en utilisant les informations
     * d'identification définies dans le fichier credentials.php.
     */
    private function __construct()
    {
        include "credentials.php";
        $this->bd = new PDO($dsn, $login, $mdp);
        $this->bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->bd->query("SET NAMES 'utf8'");
    }

    /**
     * Méthode statique pour récupérer l'unique instance de Model.
     *
     * Cette méthode crée l'instance si elle n'existe pas encore et la retourne.
     *
     * @return Model L'unique instance de la classe Model.
     */
    public static function getModel()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    public function getPDO()
    {
        return $this->bd;
    }
    /**
     * Démarre une transaction.
     *
     * @return bool
     */
    public function beginTransaction()
    {
        return $this->bd->beginTransaction();
    }

    /**
     * Valide une transaction.
     *
     * @return bool
     */
    public function commit()
    {
        return $this->bd->commit();
    }

    /**
     * Annule une transaction.
     *
     * @return bool
     */
    public function rollBack()
    {
        return $this->bd->rollBack();
    }

    /**
     * Méthode permettant d'insérer une ligne dans la table personne
     * @param $nom
     * @param $prenom
     * @param $mail
     * @param $mot_de_passe
     * @param $telephone
     * @return bool
     */
    // TODO finit
    public function createPersonne($nom, $prenom, $mail, $mot_de_passe, $telephone)
    {
        $req = $this->bd->prepare('INSERT INTO Personne(nom, prenom, mail, mot_de_passe,telephone) VALUES(:nom, :prenom, :mail, :mot_de_passe, :telephone);');
        $req->bindValue(':nom', $nom);
        $req->bindValue(':prenom', $prenom);
        $req->bindValue(':mail', $mail);
        $req->bindValue(':mot_de_passe', $mot_de_passe);
        $req->bindValue(':telephone', $telephone);
        $req->execute();
        return (bool) $req->rowCount();  // This returns true if at least one row was affected
    }


    /* -------------------------------------------------------------------------
                            Méthodes DashBoard
        ------------------------------------------------------------------------*/

    /**
     * Méthode permettant de récupérer toutes les informations des missions en fonction de la composante, la société et les prestataires assignés
     * @return array|false 
     */
    //TODO A faire
    public function getDashboardGestionnaire()
    {
        $req = $this->bd->prepare("
        SELECT 
                nom_client, 
                co.nom_composante, 
                bdl.annee, 
                bdl.mois, 
                bdl.commentaire, 
                bdl.heures, 
                COALESCE(p.nom, 'Aucun') AS nom_prestataire, 
                COALESCE(p.prenom, 'Aucun') AS prenom_prestataire
            FROM 
                 bdl
            JOIN 
                Composante co ON bdl.id_composante = co.id_composante
            JOIN 
                Client cl ON co.id_client = cl.id_client
            LEFT JOIN 
                Personne p ON bdl.id_prestataire = p.id_personne
            LEFT JOIN 
                Prestataire pr ON p.id_personne = pr.id_personne
            ");
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }


    /* -------------------------------------------------------------------------
                         Méthodes getAll...
     ------------------------------------------------------------------------*/
    /**
     * Méthode permettant de récupérer la liste des composantes
     * @return array|string Un tableau du résultat de la requête sinon un message d'erreur
     */
    public function getAllComposantes()
    {
        $req = $this->bd->prepare('SELECT id_composante AS id, nom_composante, nom_client FROM Client JOIN Composante USING(id_client);');
        $req->execute();
        $result = $req->fetchAll();
        if (empty($result)) {
            return 'Il n\'y a aucune composante.';
        }
        return $result;
    }


    /**
     * Méthode permettant de récupérer la liste de tous les commerciaux
     * @return array|string Un tableau du résultat de la requête sinon un message d'erreur
     */
    public function getAllCommerciaux()
    {
        // Préparation de la requête SQL
        $req = $this->bd->prepare('
            SELECT p.id_personne, p.nom, p.prenom, p.mail, p.telephone
            FROM Personne p
            JOIN Commercial c ON p.id_personne = c.id_personne;
        ');

        // Exécution de la requête
        $req->execute();

        // Récupération des résultats
        $result = $req->fetchAll(PDO::FETCH_ASSOC);
        if (empty($result)) {
            return 'Il n\'y a aucun commercial.';
        }
        return $result;
    }



    /**
     * Méthode permettant de récupérer la liste de tous les prestataires avec leur bon de livraison associé
     * @return array|string Un tableau du résultat de la requête sinon un message d'erreur
     */
    public function getAllPrestataires()
    {
        // TODO Faire une jointure avec BDL pour
        // Préparation de la requête SQL pour récupérer tous les prestataires
        $req = $this->bd->prepare('
            SELECT p.id_personne, p.nom, p.prenom, p.mail, p.telephone
            FROM Personne p
            JOIN Prestataire pr ON p.id_personne = pr.id_personne;
        ');

        // Exécution de la requête
        $req->execute();

        // Récupération des résultats
        $result = $req->fetchAll(PDO::FETCH_ASSOC);
        if (empty($result)) {
            return 'Il n\'y a aucun prestataire.';
        }
        return $result;
    }




    /**
     * Méthode permettant de récupérer la liste de toutes les sociétés
     * @return array|string Un tableau du résultat de la requête sinon un message d'erreur
     */
    public function getAllClients()
    {
        // Préparation de la requête SQL pour récupérer tous les clients
        $req = $this->bd->prepare('SELECT id_client, nom_client, telephone_client FROM Client;');

        // Exécution de la requête
        $req->execute();

        // Récupération des résultats
        $result = $req->fetchAll(PDO::FETCH_ASSOC);
        if (empty($result)) {
            return 'Il n\'y a aucune société.';
        }
        return $result;
    }



    /**
     * Méthode permettant de récupérer la liste de tous les gestionnaires
     * @return array|string Un tableau du résultat de la requête sinon un message d'erreur
     */
    public function getAllGestionnaires()
    {
        // Préparation de la requête SQL pour récupérer tous les gestionnaires
        $req = $this->bd->prepare('
            SELECT p.id_personne, p.nom, p.prenom, p.mail, p.telephone
            FROM Personne p
            JOIN Gestionnaire g ON p.id_personne = g.id_personne;
        ');

        // Exécution de la requête
        $req->execute();

        // Récupération des résultats
        $result = $req->fetchAll(PDO::FETCH_ASSOC);
        if (empty($result)) {
            return 'Il n\'y a aucun gestionnaire.';
        }
        return $result;
    }



    /**
     * Méthode permettant de récupérer le nom, prenom et mail d'une personne en fonction de son identifiant
     * @param int $id 
     * @return array|string Un tableau du résultat de la requête sinon un message d'erreur
     */
    //TODO j'ai Ajouter et a tester
    public function getInfosPersonne($id)
    {
        // Préparation de la requête SQL pour récupérer les informations d'une personne spécifique
        $req = $this->bd->prepare('SELECT id_personne, nom, prenom, mail, telephone FROM Personne WHERE id_personne = :id');

        // Lier le paramètre 'id' à la valeur de $id pour éviter les injections SQL
        $req->bindValue(':id', $id, PDO::PARAM_INT);

        // Exécution de la requête
        $req->execute();

        // Récupération du résultat
        $result = $req->fetch(PDO::FETCH_ASSOC);
        if (empty($result)) {
            return 'Aucune information disponible pour l\'identifiant fourni.';
        }
        return $result;
    }
    /**
     * Méthode permettant de récupérer une ou plusieurs personnes ç l'aide d'un tableau de Ids
     * @param array @ids Tableau d'Ids
     * @return array|string Un tableau du résultat de la requête sinon un message d'erreur
     */
    public function getPersonnesByIds($ids)
    {
        // Vérification initiale si le tableau est vide
        if (empty($ids)) {
            return 'Aucune personne';
        }

        // Convertir le tableau d'ID en une chaîne de caractères séparée par des virgules
        $idsString = implode(',', array_map('intval', $ids));

        // Préparation de la requête SQL
        $req = $this->bd->prepare('SELECT id_personne, nom, prenom, mail, telephone FROM Personne WHERE id_personne IN (' . $idsString . ')');

        // Exécution de la requête
        $req->execute();

        // Récupération des résultats
        $result = $req->fetchAll(PDO::FETCH_ASSOC);
        if (empty($result)) {
            return 'Aucune personne trouvée avec les identifiants fournis.';
        }
        return $result;
    }

    /**
     * Méthode permettant de récupérer les informations sur un client à partir de son nom
     * @param string $name
     * @return array|false
     */
    public function getClientByName($name)
    {
        if (empty($name)) {
            return false;
        }

        $req = $this->bd->prepare("
            SELECT 
                id_client, nom_client, telephone_client
            FROM 
                Client 
            WHERE 
                nom_client = :name
        ");

        $req->bindParam(':name', $name, PDO::PARAM_STR);
        $req->execute();
        $client = $req->fetch(PDO::FETCH_ASSOC);

        return $client ? $client : false;
    }




    /* -------------------------------------------------------------------------
                            Méthodes Composante
       ------------------------------------------------------------------------*/
    /**
     * Méthode permettant de récupérer l'id d'un composant à l'aide de son nom et la société à laquelle il appartient
     * @param string $composante nom de la composante
     * @param string $client nom du client
     * @return array|string Un tableau du résultat de la requête sinon un message d'erreur
     */
    public function getIdComposante($nom_composante, $nom_client)
    {
        // Préparation de la requête SQL
        $req = $this->bd->prepare('
            SELECT c.id_composante
            FROM Composante c
            JOIN Client cl ON c.id_client = cl.id_client
            WHERE c.nom_composante = :nom_composante AND cl.nom_client = :nom_client;
        ');

        // Liaison des paramètres pour éviter les injections SQL
        $req->bindValue(':nom_composante', $nom_composante);
        $req->bindValue(':nom_client', $nom_client);

        // Exécution de la requête
        $req->execute();

        // Récupération de l'identifiant de la composante
        $result = $req->fetch(PDO::FETCH_ASSOC);
        if (empty($result)) {
            return 'Aucune composante trouvée avec les critères spécifiés.';
        }
        return $result['id_composante'];
    }


    /**
     * Méthode permettant de récupérer les informations d'une composante à partir de son identifiant
     * @param int $id identifiant de la composante
     * @return array|string Un tableau du résultat de la requête sinon un message d'erreur
     */
    public function getInfosComposante($id)
    {
        // Préparation de la requête SQL
        $req = $this->bd->prepare('
    SELECT 
        c.id_composante, 
        c.nom_composante, 
        cl.nom_client, 
        a.adresse, 
        a.code_postal, 
        a.ville,
        a.type_de_voie
    FROM 
        Composante c
    JOIN 
        Client cl ON c.id_client = cl.id_client
    JOIN 
        Adresse a ON c.id_adresse = a.id_adresse
    WHERE 
        c.id_composante = :id;
        ');

        // Liaison du paramètre id pour éviter les injections SQL
        $req->bindValue(':id', $id, PDO::PARAM_INT);

        // Exécution de la requête
        $req->execute();

        // Récupération des résultats
        $result = $req->fetch(PDO::FETCH_ASSOC);
        if (empty($result)) {
            return 'Aucune composante trouvée pour cet identifiant.';
        }
        return $result;
    }


    /**
     * Méthode permettant de récupérer la liste des prestataires à partir de l'identifiant de la composante
     * @param int $id identifiant de la composante
     * @return array|string Un tableau du résultat de la requête sinon un message d'erreur
     */
    public function getPrestatairesComposante($id)
    {
        $req = $this->bd->prepare('
            SELECT p.id_personne, p.nom, p.prenom
            FROM Personne p
            JOIN Prestataire pr ON p.id_personne = pr.id_personne
            JOIN bdl ON pr.id_personne = bdl.id_prestataire
            WHERE bdl.id_composante = :id;
        ');
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->execute();
        $result = $req->fetchAll(PDO::FETCH_ASSOC);
        if (empty($result)) {
            return 'Aucun prestataire trouvé pour cette composante.'; // Aucun prestataire trouvé
        }
        return $result;
    }


    /**
     * Méthode permettant de récupérer la liste des commerciaux à partir de l'identifiant d'une composante
     * @param int $id identifiant de la composante
     * @return array|string Un tableau du résultat de la requête sinon un message d'erreur
     */
    public function getCommerciauxComposante($id)
    {
        // Préparation de la requête SQL
        $req = $this->bd->prepare('
            SELECT p.id_personne, p.nom, p.prenom
            FROM Personne p
            JOIN Commercial com ON p.id_personne = com.id_personne
            JOIN Affecte a ON com.id_personne = a.id_personne
            WHERE a.id_composante = :id;
        ');

        // Liaison du paramètre id pour éviter les injections SQL
        $req->bindValue(':id', $id, PDO::PARAM_INT);

        // Exécution de la requête
        $req->execute();

        // Récupération des résultats
        $result = $req->fetchAll(PDO::FETCH_ASSOC);
        if (empty($result)) {
            return 'Aucun commercial trouvé pour cette composante'; // Aucun commercial trouvé
        }
        return $result;
    }
    /**
     * Méthode ajoutant un commercial à une composante
     * @param int $id_personne identifiant du commercial
     * @param int $id_composante identifiant de la composante
     * @return bool|string
     */
    public function addCommercialToComposante($id_personne, $id_composante)
    {
        if (empty($id_personne) || empty($id_composante)) {
            return 'Paramètres invalides'; // Invalid parameters
        }

        try {
            $req = $this->bd->prepare("
                INSERT INTO Affecte (id_personne, id_composante)
                VALUES (:id_personne, :id_composante)
            ");

            $req->bindValue(':id_personne', $id_personne, PDO::PARAM_INT);
            $req->bindValue(':id_composante', $id_composante, PDO::PARAM_INT);

            $req->execute();

            return true;
        } catch (Exception $e) {
            echo 'Erreur : ' . $e->getMessage();
            return 'Erreur lors de l\'ajout du commercial';
        }
    }
    public function addPrestataireToComposante($id_personne, $id_composante)
    {

    }



    /**
     * Méthode permettant de récupérer la liste des interlocuteurs à partir de l'identifiant d'une composante
     * @param int $id identifiant de la composante
     * @return array|string Un tableau du résultat de la requête sinon un message d'erreur
     */
    // TODO ils ont ajouté distinct mais jsp si c utile
    public function getInterlocuteursComposante($id)
    {
        // Préparation de la requête SQL
        $req = $this->bd->prepare('
            SELECT p.id_personne, p.nom, p.prenom
            FROM Personne p
            JOIN Interlocuteur i ON p.id_personne = i.id_personne
            JOIN Represente r ON i.id_personne = r.id_personne
            WHERE r.id_composante = :id;
        ');

        // Liaison du paramètre id pour éviter les injections SQL
        $req->bindValue(':id', $id, PDO::PARAM_INT);

        // Exécution de la requête
        $req->execute();

        // Récupération des résultats
        $result = $req->fetchAll(PDO::FETCH_ASSOC);
        if (empty($result)) {
            return 'Aucun interlocuteur trouvé pour cette composante'; // Aucun interlocuteur trouvé
        }
        return $result;
    }
    /**
     * Méthode permettant d'ajouter un interlocuteur à une composante
     * @param int $id_personne
     * @param int $id_composante
     * @return bool
     */
    public function addInterlocuteurToComposante($id_personne, $id_composante)
    {
        // Check if the provided IDs are valid integers
        if (empty($id_personne) || empty($id_composante)) {
            return false;
        }

        // Préparation de la requête SQL pour insérer dans la table Represente
        $req = $this->bd->prepare('
            INSERT INTO Represente (id_personne, id_composante)
            VALUES (:id_personne, :id_composante)
        ');

        // Bind parameters
        $req->bindParam(':id_personne', $id_personne, PDO::PARAM_INT);
        $req->bindParam(':id_composante', $id_composante, PDO::PARAM_INT);

        // Exécuter la requête
        try {
            $req->execute();
            return true;
        } catch (PDOException $e) {
            // Gestion des erreurs
            error_log('Erreur SQL: ' . $e->getMessage());
            return false;
        }
    }



    /**
     * Méthode permettant de récupérer la liste des bons de livraison liés à partir de l'indentifiant d'une composante
     * @param int $id_composante identifiant d'une composante
     * @return array|string Un tableau du résultat de la requête sinon un message d'erreur
     */
    public function getBdlComposante($id_composante)
    {
        // Préparation de la requête SQL
        $req = $this->bd->prepare('
            SELECT annee, mois, signature_interlocuteur, signature_prestataire, commentaire, heures
            FROM Bdl
            WHERE id_composante = :id_composante;
        ');

        // Liaison du paramètre id_composante pour éviter les injections SQL
        $req->bindValue(':id_composante', $id_composante, PDO::PARAM_INT);

        // Exécution de la requête
        $req->execute();

        // Récupération des résultats
        $result = $req->fetchAll(PDO::FETCH_ASSOC);
        if (empty($result)) {
            return 'Aucun bon de livraison trouvé pour cette composante '; // Aucun bon de livraison trouvé
        }
        return $result;
    }


    /* -------------------------------------------------------------------------
                                Méthodes Societe
       ------------------------------------------------------------------------*/
    /**
     * Méthode peremettant de récupérer la liste des interlocuteurs de l'identifiant d'une société
     * @param int $id identifiant d'une société
     * @return array|string Un tableau du résultat de la requête sinon un message d'erreur
     */
    public function getInterlocuteursSociete($id)
    {
        // Préparation de la requête SQL
        $req = $this->bd->prepare('
            SELECT DISTINCT p.id_personne, p.nom, p.prenom
            FROM Personne p
            JOIN Interlocuteur i ON p.id_personne = i.id_personne
            JOIN Represente r ON i.id_personne = r.id_personne
            JOIN Composante c ON r.id_composante = c.id_composante
            WHERE c.id_client = :id;
        ');

        // Liaison du paramètre id pour éviter les injections SQL
        $req->bindValue(':id', $id, PDO::PARAM_INT);

        // Exécution de la requête
        $req->execute();

        // Récupération des résultats
        $result = $req->fetchAll(PDO::FETCH_ASSOC);
        if (empty($result)) {
            return 'Aucun interlocuteur trouvé pour cette société';
        }
        return $result;
    }


    /**
     * Méthode permettant de récupérer les informations d'une société
     * @param int $id id de la société
     * @return array|string Un tableau du résultat de la requête sinon un message d'erreur
     */
    public function getInfosSociete($id)
    {
        $req = $this->bd->prepare('
            SELECT id_client, nom_client, telephone_client
    FROM Client
    WHERE id_client = :id;
    
        ');

        // Liaison du paramètre id pour éviter les injections SQL
        $req->bindValue(':id', $id, PDO::PARAM_INT);

        // Exécution de la requête
        $req->execute();

        // Récupération des résultats
        $result = $req->fetch(PDO::FETCH_ASSOC);
        if (!$result) {
            return 'Aucune information trouvée pour cette société';
        }
        return $result;
    }


    /**
     * Méthode permettant de récupérer la liste des composantes d'une société
     * @param $id
     * @return array|string Un tableau du résultat de la requête sinon un message d'erreur
     */
    public function getComposantesSociete($id)
    {
        // Préparation de la requête SQL
        $req = $this->bd->prepare('
            SELECT c.id_composante, c.nom_composante,
            a.adresse,
                a.code_postal

            FROM Composante c
            JOIN 
                Client cl ON c.id_client = cl.id_client
            JOIN 
                Adresse a ON c.id_adresse = a.id_adresse
            WHERE c.id_client = :id;
        ');

        // Liaison du paramètre id pour éviter les injections SQL
        $req->bindValue(':id', $id, PDO::PARAM_INT);

        // Exécution de la requête
        $req->execute();

        // Récupération des résultats
        $result = $req->fetchAll(PDO::FETCH_ASSOC);
        if (empty($result)) {
            return 'Aucune composante trouvée pour cette société';
        }
        return $result;
    }


    /* -------------------------------------------------------------------------
                            Méthodes assigner...
       ------------------------------------------------------------------------*/
    //Suppression de assignerInterloceteurComposante car elle n'est pas utilisé
    /**
     * Méthode permettant d'assigner un interlocuteur à une composante en connaissant l'identifiant de la composante
     * @param ont $id_composante
     * @param string $mail
     * @return bool
     */
    // ca marche
    public function assignerInterlocuteurComposanteByIdComposante($id_composante, $mail)
    {
        try {
            // Préparation de la requête SQL
            $req = $this->bd->prepare("
                INSERT INTO Represente (id_personne, id_composante)
                SELECT id_personne, :id_composante
                FROM Personne
                WHERE mail = :mail
            ");

            // Liaison des paramètres pour éviter les injections SQL
            $req->bindValue(':id_composante', $id_composante, PDO::PARAM_INT);
            $req->bindValue(':mail', $mail, PDO::PARAM_STR);

            // Exécution de la requête
            $req->execute();

            // Vérifier si l'insertion a réussi
            if ($req->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo 'Erreur : ' . $e->getMessage();
            return false;
        }
    }


    /**
     * Méthode permettant d'assigner un interlocuteur à une composante en connaissant le nom de la composante et l'identifiant de la société
     * @param int $id_client
     * @param string $mail
     * @param string $composante
     * @return bool
     */
    // ca marche
    public function assignerInterlocuteurComposanteByIdClient($id_client, $mail, $composante)
    {
        try {
            // Préparation de la requête SQL
            $req = $this->bd->prepare("
                INSERT INTO Represente (id_personne, id_composante)
                SELECT id_personne, 
                       (SELECT id_composante 
                        FROM Composante 
                        WHERE nom_composante = :composante AND id_client = :id_client)
                FROM Personne
                WHERE mail = :mail
            ");

            // Liaison des paramètres pour éviter les injections SQL
            $req->bindValue(':id_client', $id_client, PDO::PARAM_INT);
            $req->bindValue(':composante', $composante, PDO::PARAM_STR);
            $req->bindValue(':mail', $mail, PDO::PARAM_STR);

            // Exécution de la requête
            $req->execute();

            // Vérifier si l'insertion a réussi
            if ($req->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo 'Erreur : ' . $e->getMessage();
            return false;
        }
    }


    /* -------------------------------------------------------------------------
                                Méthodes add...
       ------------------------------------------------------------------------*/
    /**
     * Méthode permettant d'ajouter une personne dans la table prestataire en connaissant son mail
     * @param string $mail
     * @return bool
     */
    public function addPrestataire($mail)
    {
        try {
            // Préparation de la requête SQL pour insérer dans la table Prestataire
            $req = $this->bd->prepare("
                INSERT INTO Prestataire (id_personne)
                SELECT id_personne 
                FROM Personne 
                WHERE mail = :mail
            ");

            // Liaison des paramètres pour éviter les injections SQL
            $req->bindValue(':mail', $mail, PDO::PARAM_STR);

            // Exécution de la requête
            $req->execute();

            // Vérifier si l'insertion a réussi
            if ($req->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo 'Erreur : ' . $e->getMessage();
            return false;
        }
    }


    /**
     * Méthode permettant d'ajouter une personne dans la table interlocuteur en connaissant son mail
     * @param string  $mail
     * @return bool
     */
    public function addInterlocuteur($mail)
    {
        try {
            // Préparation de la requête SQL pour insérer dans la table Interlocuteur
            $req = $this->bd->prepare("
                INSERT INTO Interlocuteur (id_personne)
                SELECT id_personne 
                FROM Personne 
                WHERE mail = :mail
            ");

            // Liaison des paramètres pour éviter les injections SQL
            $req->bindValue(':mail', $mail, PDO::PARAM_STR);

            // Exécution de la requête
            $req->execute();

            // Vérifier si l'insertion a réussi
            if ($req->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo 'Erreur : ' . $e->getMessage();
            return false;
        }
    }
    /**
     * Méthode permettant de récupérer l'id de l'interlocuteur à partir de son email
     * @param string $email
     * @return int|false
     */
    public function getInterlocuteurIdByEmail($email)
    {
        if (empty($email)) {
            return false;
        }

        // Préparation de la requête SQL
        $req = $this->bd->prepare('
            SELECT p.id_personne
            FROM Personne p
            JOIN Interlocuteur i ON p.id_personne = i.id_personne
            WHERE p.mail = :email
        ');

        // Bind the email parameter
        $req->bindParam(':email', $email, PDO::PARAM_STR);

        // Execute the query
        $req->execute();

        // Fetch the result
        $result = $req->fetch(PDO::FETCH_ASSOC);

        // Check if an id_personne was found and return it, otherwise return false
        return $result ? $result['id_personne'] : false;
    }




    /**
     * Méthode permettant d'ajouter une personne dans la table commercial en connaissant son mail
     * @param string $mail
     * @return bool
     */
    public function addCommercial($mail)
    {
        $req = $this->bd->prepare("INSERT INTO Commercial (id_personne) SELECT id_personne FROM personne WHERE mail = :mail");
        $req->bindValue(':mail', $mail, PDO::PARAM_STR);
        $req->execute();
        return (bool) $req->rowCount();
    }

    /**
     * Méthode permettant d'ajouter une personne dans la table gestionnaire en connaissant son mail
     * @param string $mail
     * @return bool
     */
    public function addGestionnaire($mail)
    {
        $req = $this->bd->prepare("INSERT INTO Gestionnaire (id_personne) SELECT id_personne FROM personne WHERE mail = :mail");
        $req->bindValue(':mail', $mail, PDO::PARAM_STR);
        $req->execute();
        return (bool) $req->rowCount();
    }

    /**
     * Méthode permettant d'ajouter un client dans la table client avec ses informations
     * @param string $client
     * @param string $tel
     * @return bool
     */
    public function addClient($client, $tel)
    {
        $req = $this->bd->prepare("INSERT INTO client(nom_client, telephone_client) VALUES( :nom_client, :tel)");
        $req->bindValue(':nom_client', $client, PDO::PARAM_STR);
        $req->bindValue(':tel', $tel, PDO::PARAM_STR);
        $req->execute();
        return (bool) $req->rowCount();
    }

    // AJouté fonction pour ajouter l'adresse qui retourne l'id de l'adresse pour ensuite ajouter la composante
    /**
     * Méthode permettant de créer une adresse
     * @param string $adresse
     * @param int $code_postal
     * @param string $ville
     * @param string $type_de_voie
     * @return int|false
     */
    public function addAdresse($adresse, $code_postal, $ville, $type_de_voie)
    {
        try {
            $req = $this->bd->prepare("
                INSERT INTO Adresse (adresse, code_postal, ville, type_de_voie)
                VALUES (:adresse, :code_postal, :ville, :type_de_voie)
                RETURNING id_adresse
            ");

            $req->bindValue(':adresse', $adresse, PDO::PARAM_STR);
            $req->bindValue(':code_postal', $code_postal, PDO::PARAM_STR);
            $req->bindValue(':ville', $ville, PDO::PARAM_STR);
            $req->bindValue(':type_de_voie', $type_de_voie, PDO::PARAM_STR);

            $req->execute();

            return $req->fetchColumn(); // Retourner l'id_adresse généré
        } catch (Exception $e) {
            echo 'Erreur : ' . $e->getMessage();
            return false;
        }
    }

    /**
     * Méthode ajoutant une composante 
     * @param string $nom_composante
     * @param int $id_adresse
     * @param int $id_client
     * @return int|false
     */
    public function addComposante($nom_composante, $id_adresse, $id_client)
    {
        try {
            $req = $this->bd->prepare("
            INSERT INTO Composante (nom_composante, id_adresse, id_client)
            VALUES (:nom_composante, :id_adresse, :id_client)
            RETURNING id_composante
        ");

            $req->bindValue(':nom_composante', $nom_composante, PDO::PARAM_STR);
            $req->bindValue(':id_adresse', $id_adresse, PDO::PARAM_INT);
            $req->bindValue(':id_client', $id_client, PDO::PARAM_INT);

            $req->execute();

            // Fetch the returned id_composante
            $result = $req->fetch(PDO::FETCH_ASSOC);

            return $result ? $result['id_composante'] : false;
        } catch (Exception $e) {
            echo 'Erreur : ' . $e->getMessage();
            return false;
        }
    }





    // /**
    //  * Méthode permettant d'ajouter un bon de livraison dans la table BON_DE_LIVRAISON avec seulement les informations comme le mois, la mission et le prestataire.
    //  * @param $nom_mission
    //  * @param $nom_composante
    //  * @param $mois
    //  * @param $id_prestataire
    //  * @return bool|void
    //  */
    // TODO
    /**
     * Méthode permettant d'ajouter un bon de livraison (BDL)
     * @param int $id_composante
     * @param int $id_prestataire
     * @param int $annee
     * @param string $mois
     * @param bool|false $signature_interlocuteur
     * @param bool|false $signature_prestataire
     * @param string|'' $commentaire
     * @param float|0 $heures
     * @return bool
     */
    public function addBdl($id_composante, $id_prestataire, $id_gestionnaire, $id_interlocuteur, $mois, $annee = 2024, $signature_interlocuteur = false, $signature_prestataire = null, $commentaire = "", $heures = 0)
    {
        try {
            // Préparation de la requête SQL pour insérer dans la table Bon_de_livraison
            $req = $this->bd->prepare("
            INSERT INTO bdl (id_composante, id_personne_2, annee, mois, signature_interlocuteur, signature_prestataire, commentaire, heures,id_interlocuteur,id_gestionnaire)
            VALUES (:id_composante, :id_prestataire, :annee, :mois, :signature_interlocuteur, :signature_prestataire, :commentaire, :heures, :id_interlocuteur,:id_gestionnaire)
        ");

            // Liaison des paramètres pour éviter les injections SQL
            $req->bindValue(':id_composante', $id_composante, PDO::PARAM_INT);
            $req->bindValue(':id_prestataire', $id_prestataire, PDO::PARAM_INT);
            $req->bindValue(':annee', $annee, PDO::PARAM_INT);
            $req->bindValue(':mois', $mois, PDO::PARAM_STR);
            $req->bindValue(':signature_interlocuteur', $signature_interlocuteur, PDO::PARAM_NULL);
            $req->bindValue(':signature_prestataire', $signature_prestataire, PDO::PARAM_NULL);
            $req->bindValue(':commentaire', $commentaire, PDO::PARAM_NULL);
            $req->bindValue(':heures', $heures, PDO::PARAM_NULL);
            $req->bindValue(':id_interlocuteur', $id_interlocuteur, PDO::PARAM_INT);
            $req->bindValue(':id_gestionnaire', $id_gestionnaire, PDO::PARAM_INT);



            // Exécution de la requête
            $req->execute();

            // Vérifier si l'insertion a réussi
            if ($req->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo 'Erreur : ' . $e->getMessage();
            return false;
        }
    }




    /**
     * Méthode permettant de récupérer tous les bons de livraison associés à un prestataire
     * @param int $id_pr
     * @return array|false
     */
    public function getAllBdlPrestataire($id_pr)
    {
        try {
            // Préparation de la requête SQL pour récupérer les bons de livraison associés à un prestataire
            $req = $this->bd->prepare("
                SELECT bdl.id_bdl, bdl.id_composante, bdl.annee, bdl.mois, bdl.signature_interlocuteur, 
                       bdl.signature_prestataire, bdl.commentaire, bdl.heures,
                       c.nom_composante, cl.nom_client, cl.telephone_client
                FROM bdl
                JOIN Prestataire pr ON bdl.id_prestataire = pr.id_personne
                JOIN Composante c ON bdl.id_composante = c.id_composante
                JOIN Client cl ON c.id_client = cl.id_client
                WHERE pr.id_personne = :id_pr
            ");

            // Liaison des paramètres pour éviter les injections SQL
            $req->bindValue(':id_pr', $id_pr, PDO::PARAM_INT);

            // Exécution de la requête
            $req->execute();

            // Récupération des résultats
            $result = $req->fetchAll(PDO::FETCH_ASSOC);

            // Vérifier si des résultats ont été trouvés
            if (!empty($result)) {
                return $result;
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo 'Erreur : ' . $e->getMessage();
            return false;
        }
    }

    /**
     * Méthode récupérant les prestataires à partir de l'identifiant d'un bon de livraison
     * @param int $id_bdl
     */
    // public function getBdlPrestataireBybdlId($id_bdl)
    // {
    //     $req = $this->bd->prepare("SELECT * FROM BDL 
    //     JOIN prestataire ON BDL.id_prestataire = Prestataire.id_personne 
    //     JOIN Composante USING (id_composante) 
    //     join Client Using(id_client) 
    //     Join Personne On Prestataire.id_personne = Personne.id_personne
    //     WHERE BDL.id_bdl = :idb");
    //     $req->bindValue(':idb', $id_bdl, PDO::PARAM_INT);
    //     $req->execute();
    //     return $req->fetch(PDO::FETCH_ASSOC);
    // }

    public function getBdlPrestataireBybdlId($id_bdl)
    {
        $req = $this->bd->prepare("
            SELECT BDL.*, Prestataire.*, Composante.nom_composante, Client.nom_client, Client.telephone_client, Adresse.adresse AS adresse_livraison
            FROM BDL 
            JOIN Prestataire ON BDL.id_prestataire = Prestataire.id_personne 
            JOIN Composante USING (id_composante) 
            JOIN Client USING (id_client)
            JOIN Adresse ON Composante.id_adresse = Adresse.id_adresse
            WHERE BDL.id_bdl = :idb
        ");
        $req->bindValue(':idb', $id_bdl, PDO::PARAM_INT);
        $req->execute();
        return $req->fetch(PDO::FETCH_ASSOC);
    }
    public function getInterlocuteurByIdBDL($id_bdl){
        $req = $this->bd->prepare("SELECT personne.prenom, personne.nom, personne.telephone ,personne.mail from personne join BDL on bdl.id_interlocuteur = personne.id_personne where id_bdl= :id");
        $req->bindValue(':id',$id_bdl);
        $req->execute();
        return $req->fetch(PDO::FETCH_ASSOC); 
    }

    public function getPrestataireByIdBDL($id_bdl){
        $req = $this->bd->prepare("SELECT personne.prenom, personne.nom, personne.telephone ,personne.mail from personne join BDL on bdl.id_prestataire = personne.id_personne where id_bdl= :id");
        $req->bindValue(':id',$id_bdl);
        $req->execute();
        return $req->fetch(PDO::FETCH_ASSOC);
    }
    public function getGestionnaireById($id_bdl){
        $req = $this->bd->prepare("SELECT personne.prenom, personne.nom, personne.telephone ,personne.mail from personne join BDL on bdl.id_gestionnaire = personne.id_personne where id_bdl= :id");
        $req->bindValue(':id',$id_bdl);
        $req->execute();
        return $req->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getBdlInterlocuteurBybdlId($id_bdl)
    {
        $req = $this->bd->prepare(" SELECT * FROM BDL
        JOIN interlocuteur ON BDL.id_interlocuteur = Interlocuteur.id_personne
        JOIN Composante USING (id_composante)
        join Client Using(id_client)
        Join Personne On Interlocuteur.id_personne = Personne.id_personne
        WHERE BDL.id_bdl = :idb");
        $req->bindValue(':idb', $id_bdl, PDO::PARAM_INT);
        $req->execute();
        return $req->fetch(PDO::FETCH_ASSOC);
    }

    public function setSignTruePrestataireId($id_bdl)
    {
        $req = $this->bd->prepare("UPDATE BDL SET signature_prestataire = true WHERE id_bdl = :id");

        $req->bindValue(':id', $id_bdl, PDO::PARAM_INT);
        $req->execute();

        if ($req->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function makeCommentBDL($id_bdl,$com)
    {
        $req = $this->bd->prepare("UPDATE BDL SET commentaire= :com WHERE id_bdl = :id");

        $req->bindValue(':id', $id_bdl, PDO::PARAM_INT);
        $req->bindValue(':com', $com);
        $req->execute();

        if ($req->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function setSignTrueInterlocuteurId($id_bdl)
    {
        $req = $this->bd->prepare("UPDATE BDL SET signature_interlocuteur = true WHERE id_bdl = :id");
    
        $req->bindValue(':id', $id_bdl, PDO::PARAM_INT);
        $req->execute();
        
        if ($req->rowCount() > 0) {
            return true; 
        } else {
            return false; 
        }
    }
    


    /**
     * Méthode récupérant les heures réalisées à partir de l'identifiant du bon de livraison
     * @param int $id_bdl
     * @return array
     */
    public function getHoursByIdBDL($id_bdl)
    {
        $req = $this->bd->prepare("SELECT * FROM dailyhours where id_bdl = :id");
        $req->bindValue(':id', $id_bdl, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall(PDO::FETCH_ASSOC);

    }

        public function getTotalHoursByIdBDL($id_bdl)
    {
        $req = $this->bd->prepare("SELECT SUM(hours_worked) as total_hours FROM dailyhours WHERE id_bdl = :id");
        $req->bindValue(':id', $id_bdl, PDO::PARAM_INT);
        $req->execute();
        $result = $req->fetch(PDO::FETCH_ASSOC);

        return $result['total_hours'];
    }

    public function updateHoursByIdBDL($id_bdl, $new_hours)

    {
        $req = $this->bd->prepare("UPDATE bdl SET heures = :heures WHERE id_bdl = :id");
        $req->bindValue(':heures', $new_hours, PDO::PARAM_INT);
        $req->bindValue(':id', $id_bdl, PDO::PARAM_INT);
        return $req->execute();
    }





    // public function setEstValideBdl($id_bdl, $id_interlocuteur, $valide)
    // {
    //     $req = $this->bd->prepare("UPDATE BON_DE_LIVRAISON SET est_valide = :valide, id_interlocuteur = :id_interlocuteur WHERE id_bdl = :id_bdl");
    //     $req->bindValue(':id_interlocuteur', $id_interlocuteur);
    //     $req->bindValue(':id_bdl', $id_bdl);
    //     $req->bindValue(':valide', $valide);
    //     $req->execute();
    //     return (bool)$req->rowCount();

    // }

    /**
     * Méthode permettant de changer le nom d'une personne
     * @param  int $id
     * @param string $nom
     * @return bool
     */
    public function setNomPersonne($id, $nom)
    {
        $req = $this->bd->prepare("UPDATE Personne SET nom = :nom WHERE id_personne = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':nom', $nom, PDO::PARAM_STR);
        $req->execute();
        return (bool) $req->rowCount();
    }
    /**
     * Méthode permettant de changer le prénom d'une personne
     * @param  int $id
     * @param string $prenom
     * @return bool
     */
    public function setPrenomPersonne($id, $prenom)
    {
        $req = $this->bd->prepare("UPDATE Personne SET prenom = :prenom WHERE id_personne = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':prenom', $prenom, PDO::PARAM_STR);
        $req->execute();
        return (bool) $req->rowCount();
    }
    /**
     * Méthode permettant de changer le mail d'une personne
     * @param  int $id
     * @param string $mail
     * @return bool
     */
    public function setmailPersonne($id, $mail)
    {
        $req = $this->bd->prepare("UPDATE Personne SET mail = :mail WHERE id_personne = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':mail', $mail, PDO::PARAM_STR);
        $req->execute();
        return (bool) $req->rowCount();
    }

    /**
     * Méthode permettant de changer le mot de passe d'une personne
     * @param  int $id
     * @param string $mot_de_passe
     * @return bool
     */
    public function setmot_de_passePersonne($id, $mot_de_passe)
    {
        $req = $this->bd->prepare("UPDATE Personne SET mot_de_passe = :mot_de_passe WHERE id_personne = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':mot_de_passe', $mot_de_passe, PDO::PARAM_STR);
        $req->execute();
        return (bool) $req->rowCount();
    }
    /**
     * Méthode permettant de changer le nom d'un client
     * @param  int $id
     * @param string $nom
     * @return bool
     */
    public function setNomClient($id, $nom)
    {
        $req = $this->bd->prepare("UPDATE Client SET nom_client = :nom WHERE id_client = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':nom', $nom, PDO::PARAM_STR);
        $req->execute();
        return (bool) $req->rowCount();
    }

    /**
     * Méthode permettant de changer le téléphone d'un client
     * @param  int $id
     * @param string $tel
     * @return bool
     */
    public function setTelClient($id, $tel)
    {
        $req = $this->bd->prepare("UPDATE Client SET telephone_client = :tel WHERE id_client = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':tel', $tel, PDO::PARAM_STR);
        $req->execute();
        return (bool) $req->rowCount();
    }

    /**
     * Méthode permettant de changer le nom d'une composante
     * @param  int $id
     * @param string $nom
     * @return bool
     */
    public function setNomComposante($id, $nom)
    {
        $req = $this->bd->prepare("UPDATE Composante SET nom_composante = :nom WHERE id_composante = :id");
        $req->bindValue(':id', $id);
        $req->bindValue(':nom', $nom);
        $req->execute();
        return (bool) $req->rowCount();
    }

    public function setNumeroAdresse($id, $num)
    // TODO
    {
        $req = $this->bd->prepare("UPDATE ADRESSE SET numero = :num WHERE id_adresse = (SELECT id_adresse FROM ADRESSE JOIN COMPOSANTE USING(id_adresse) WHERE id_composante = :id)");
        $req->bindValue(':id', $id);
        $req->bindValue(':num', $num);
        $req->execute();
        return (bool) $req->rowCount();
    }

    public function setNomVoieAdresse($id, $nom)
    // TODO
    {
        $req = $this->bd->prepare("UPDATE ADRESSE SET nom_voie = :nom WHERE id_adresse = (SELECT id_adresse FROM ADRESSE JOIN COMPOSANTE USING(id_adresse) WHERE id_composante = :id)");
        $req->bindValue(':id', $id);
        $req->bindValue(':nom', $nom);
        $req->execute();
        return (bool) $req->rowCount();
    }

    public function setcode_postalLocalite($id, $code_postal)
    // TODO
    {
        $req = $this->bd->prepare("UPDATE ADRESSE SET id_localite = (SELECT id_localite FROM LOCALITE WHERE code_postal = :code_postal)
               WHERE id_adresse = (SELECT id_adresse FROM ADRESSE JOIN COMPOSANTE USING(id_adresse) WHERE id_composante = :id)");
        $req->bindValue(':id', $id);
        $req->bindValue(':code_postal', $code_postal);
        $req->execute();
        return (bool) $req->rowCount();
    }

    public function setVilleLocalite($id, $ville)
    // TODO
    {
        $req = $this->bd->prepare("UPDATE ADRESSE SET id_localite = (SELECT id_localite FROM LOCALITE WHERE LOWER(ville) = LOWER(:ville))
               WHERE id_adresse = (SELECT id_adresse FROM ADRESSE JOIN COMPOSANTE USING(id_adresse) WHERE id_composante = :id)");
        $req->bindValue(':id', $id);
        $req->bindValue(':ville', $ville);
        $req->execute();
        return (bool) $req->rowCount();
    }

    public function setLibelletype_de_voie($id, $libelle)
    // TODO
    {
        $req = $this->bd->prepare("UPDATE ADRESSE SET id_type_voie = (SELECT id_type_voie FROM type_de_voie WHERE LOWER(libelle) = LOWER(:libelle))
               WHERE id_adresse = (SELECT id_adresse FROM COMPOSANTE JOIN ADRESSE USING(id_adresse) WHERE id_composante = :id)");
        $req->bindValue(':id', $id);
        $req->bindValue(':libelle', $libelle);
        $req->execute();
        return (bool) $req->rowCount();
    }

    public function setClientComposante($id, $client)
    // TODO pas utilisé
    {
        $req = $this->bd->prepare("UPDATE COMPOSANTE SET id_client = (SELECT id_client FROM CLIENT WHERE LOWER(nom_client) = LOWER(:client))
                  WHERE id_composante = :id");
        $req->bindValue(':id', $id);
        $req->bindValue(':client', $client);
        $req->execute();
        return (bool) $req->rowCount();
    }


    /**
     * Méthode permettant de mettre à jour le commentaire et les heures d'un bon de livraison
     * @param int $id_composante
     * @param int $annee
     * @param string $mois
     * @param string $commentaire
     * @param float $heures
     * @return bool
     */
    public function updateBdl($id_composante, $annee, $mois, $commentaire, $heures)
    {
        try {
            // Préparation de la requête SQL pour mettre à jour le commentaire et les heures
            $req = $this->bd->prepare("
            UPDATE Bdl
            SET commentaire = :commentaire, heures = :heures
            WHERE id_composante = :id_composante
            AND annee = :annee
            AND mois = :mois
        ");

            // Liaison des paramètres pour éviter les injections SQL
            $req->bindValue(':id_composante', $id_composante, PDO::PARAM_INT);
            $req->bindValue(':annee', $annee, PDO::PARAM_INT);
            $req->bindValue(':mois', $mois, PDO::PARAM_STR);
            $req->bindValue(':commentaire', $commentaire, PDO::PARAM_STR);
            $req->bindValue(':heures', $heures, PDO::PARAM_STR);

            // Exécution de la requête
            $req->execute();

            // Vérifier si la mise à jour a réussi
            return (bool) $req->rowCount();
        } catch (Exception $e) {
            echo 'Erreur : ' . $e->getMessage();
            return false;
        }
    }
    // TODO fonction qui update l'adresse d'une composante

    /* -------------------------------------------------------------------------
                            Fonction Commercial
        ------------------------------------------------------------------------*/

    // public function getDashboardCommercial($id_co)
    // // TODO elle n'est pas appelé
    // {
    //     $req = $this->bd->prepare('SELECT nom_client, nom_composante, nom_mission, nom, prenom, ta.id_mission, id_bdl, id_prestataire FROM client JOIN composante c USING(id_client) JOIN mission USING(id_composante) JOIN travailleavec ta USING(id_mission) JOIN PERSONNE p ON ta.id_personne = p.id_personne JOIN estDans ed on ed.id_composante = c.id_composante JOIN BON_DE_LIVRAISON on id_prestataire = ta.id_personne WHERE ed.id_personne=:id');
    //     $req->bindValue(':id', $id_co, PDO::PARAM_INT);
    //     $req->execute();
    //     return $req->fetchall(PDO::FETCH_ASSOC);
    // }

    public function getDashboardPrestataire($id_prestataire)
    {
        // Préparation de la requête SQL
        $req = $this->bd->prepare('
            SELECT cl.nom_client, c.nom_composante, c.id_composante
            FROM Client cl
            JOIN Composante c ON cl.id_client = c.id_client
            WHERE cl.id_client = :id;
        ');

        // Liaison du paramètre id pour éviter les injections SQL
        $req->bindValue(':id', $id_prestataire, PDO::PARAM_INT);

        // Exécution de la requête
        $req->execute();

        // Récupération des résultats
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }


    // public function getInterlocuteurForCommercial($id_co)
    // {
    //     $req = $this->bd->prepare('SELECT nom, prenom, nom_client, nom_composante FROM dirige JOIN composante USING(id_composante) JOIN client USING(id_client) JOIN personne USING(id_personne) JOIN estDans ed USING(id_composante) WHERE ed.id_personne = :id');
    //     $req->bindValue(':id', $id_co, PDO::PARAM_INT);
    //     $req->execute();
    //     return $req->fetchall();
    // }

    /**
     * Méthode permettant de récupérer la liste de tous les prestataires d'un commercial
     * @param int $id_commercial L'ID du commercial
     * @return array|string Un tableau du résultat de la requête sinon un message d'erreur
     */
    public function getPrestataireForCommercial($id_commercial)
    {
        // Préparation de la requête SQL pour récupérer les prestataires d'un commercial
        $req = $this->bd->prepare('
        SELECT DISTINCT p.id_personne, p.nom, p.prenom, p.mail, p.telephone
        FROM Personne p
        JOIN Prestataire pr ON p.id_personne = pr.id_personne
        JOIN BDL b ON pr.id_personne = b.id_prestataire
        JOIN Composante c ON b.id_composante = c.id_composante
        JOIN Affecte a ON c.id_composante = a.id_composante
        JOIN Commercial co ON a.id_personne = co.id_personne
        WHERE co.id_personne = :id_commercial;
    ');

        // Liaison du paramètre
        $req->bindParam(':id_commercial', $id_commercial, PDO::PARAM_INT);

        // Exécution de la requête
        $req->execute();

        // Récupération des résultats
        $result = $req->fetchAll(PDO::FETCH_ASSOC);
        if (empty($result)) {
            return 'Il n\'y a aucun prestataire pour ce commercial.';
        }
        return $result;
    }


    // public function getComposantesForCommercial($id_commercial)
    // {
    //     $req = $this->bd->prepare('SELECT id_composante AS id, nom_composante, nom_client FROM CLIENT JOIN COMPOSANTE using(id_client) JOIN estDans USING(id_composante) WHERE id_personne = :id');
    //     $req->bindValue(':id', $id_commercial);
    //     $req->execute();
    //     return $req->fetchall(PDO::FETCH_ASSOC);
    // }

    // public function getBdlTypeAndMonth($id_bdl)
    // {
    //     $req = $this->bd->prepare("SELECT id_bdl, type_bdl, mois FROM BON_DE_LIVRAISON JOIN MISSION USING(id_mission) WHERE id_bdl = :id");
    //     $req->bindValue(':id', $id_bdl);
    //     $req->execute();
    //     return $req->fetch();
    // }

    // public function getBdlsOfPrestataireByIdMission($id_mission, $id_prestataire)
    // {
    //     $req = $this->bd->prepare("SELECT id_bdl, nom_mission, mois FROM BON_DE_LIVRAISON JOIN MISSION USING(id_mission) WHERE id_mission = :id_mission and id_prestataire = :id_prestataire");
    //     $req->bindValue(':id_mission', $id_mission);
    //     $req->bindValue(':id_prestataire', $id_prestataire);
    //     $req->execute();
    //     return $req->fetchAll(PDO::FETCH_ASSOC);
    // }

    // public function getIdActivite($date_activite, $id_bdl)
    // {
    //     $req = $this->bd->prepare('SELECT id_activite FROM activite WHERE id_bdl = :id_bdl and date_bdl = :date');
    //     $req->bindValue(':id_bdl', $id_bdl);
    //     $req->bindValue(':date', $date_activite);
    //     $req->execute();
    //     return $req->fetch()[0];
    // }

    /* -------------------------------------------------------------------------
                        Fonction Interlocuteur
    ------------------------------------------------------------------------*/

    // public function dashboardInterlocuteur($id_in)
    // {
    //     $req = $this->bd->prepare("SELECT nom_mission, date_debut, nom, prenom, id_bdl FROM mission m JOIN travailleAvec USING(id_mission) JOIN personne p USING(id_personne) JOIN bon_de_livraison bdl ON m.id_mission= bdl.id_mission WHERE bdl.id_personne = :id");
    //     $req->bindValue(':id', $id_in, PDO::PARAM_INT);
    //     $req->execute();
    //     return $req->fetchall();
    // }

    // public function getmailCommercialForInterlocuteur($id_in)
    // {
    //     $req = $this->bd->prepare("SELECT mail FROM dirige d JOIN estDans ed USING(id_composante) JOIN personne com ON ed.id_personne = com.id_personne WHERE d.id_personne = :id");
    //     $req->bindValue(':id', $id_in, PDO::PARAM_INT);
    //     $req->execute();
    //     return $req->fetchall();
    // }

    // /**
    //  * Récupère les informations de l'interlocuteur client par rapport à sa mission
    //  * @return array|false
    //  */
    // public function getClientContactDashboardData()
    // {
    //     $req = $this->bd->prepare('SELECT nom_mission, date_debut, nom, prenom, id_bdl, ta.id_mission, ta.id_personne as id_prestataire FROM mission m JOIN travailleAvec ta USING(id_mission) JOIN personne p USING(id_personne) JOIN bon_de_livraison bdl ON m.id_mission= bdl.id_mission WHERE bdl.id_interlocuteur = :id;');
    //     $req->bindValue(':id', $_SESSION['id']);
    //     $req->execute();
    //     return $req->fetchAll(PDO::FETCH_ASSOC);
    // }



    // /**
    //  * Renvoie la liste des mails des commerciaux assignées à la mission de l'interlocuteur client
    //  * @param $idClientContact
    //  * @return void
    //  */
    // public function getComponentCommercialsmails($idClientContact)
    // {
    //     $req = $this->bd->prepare('SELECT mail FROM dirige d JOIN estDans ed USING(id_composante) JOIN personne com ON ed.id_personne = com.id_personne WHERE d.id_personne = :id;');
    //     $req->bindValue(':id', $idClientContact);
    //     $req->execute();
    //     return $req->fetchAll(PDO::FETCH_ASSOC);
    // }

    /**
     * Récupère le mail dans la base de données grâce à l'identifiant de la personne
     * @param $id
     * @return void
     */
    function getmailById($id)
    {
        $req = $this->bd->prepare('SELECT mail FROM personne WHERE id_personne = :id;');
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
        $req = $this->bd->prepare('SELECT mail FROM PERSONNE WHERE mail = :mail;');
        $req->bindValue(':mail', $mail);
        $req->execute();
        $mail = $req->fetch(PDO::FETCH_ASSOC);
        return sizeof($mail) != 0;
    }

    // public function getBdlPrestaForInterlocuteur($id_pr, $id_in)
    // {
    //     $req = $this->bd->prepare("SELECT id_bdl, mois, nom_mission FROM BON_DE_LIVRAISON bdl JOIN MISSION m USING(id_mission) JOIN travailleAvec ta USING(id_mission) JOIN COMPOSANTE USING(id_composante) JOIN dirige d USING(id_composante) WHERE ta.id_personne = :id_pres AND d.id_personne = :id_inter");
    //     $req->bindValue(':id_inter', $id_pr, PDO::PARAM_INT);
    //     $req->bindValue(':id_pres', $id_in, PDO::PARAM_INT);
    //     $req->execute();
    //     return $req->fetchall();
    // }

    /* -------------------------------------------------------------------------
                            Fonction Prestataire
        ------------------------------------------------------------------------*/
    // TODO
    // public function getInterlocuteurForPrestataire($id_pr)
    // {
    //     $req = $this->bd->prepare('SELECT nom, prenom, nom_client, nom_composante FROM dirige d JOIN composante USING(id_composante) JOIN client USING(id_client) JOIN personne p ON p.id_personne = d.id_personne  JOIN MISSION m USING(id_composante) JOIN travailleAvec ta USING(id_mission) WHERE ta.id_personne = :id');
    //     $req->bindValue(':id', $id_pr, PDO::PARAM_INT);
    //     $req->execute();
    //     return $req->fetchall();
    // }

    /* -------------------------------------------------------------------------
                            AUTRE
        ------------------------------------------------------------------------*/
    /**
     * Vérifie que le mot de passe correspond bien au mail. Si ils correspondent, une session avec les informations de la personne lié au mail débute.
     **/
    // TODO mettre les sessions dans le controller ?
    public function checkMailPassword($mail, $password)
    {
        $req = $this->bd->prepare('SELECT * FROM Personne WHERE mail = :mail');
        $req->bindValue(':mail', $mail);
        $req->execute();
        $realPassword = $req->fetchAll(PDO::FETCH_ASSOC);

        if ($realPassword) {
            if ($realPassword[0]['mot_de_passe'] == $password) {
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
                $_SESSION['mail'] = $realPassword[0]['mail'];
                return true;
            }
        }
        return false;
    }

    /**
     * Méthode vérifiant les rôles de la personne. Si il n'y a qu'un seul rôle elle retourne simplement le nom de ce rôle. Si il y a plusieurs rôles, une liste des rôles sous forme de tableau.
     * @return array|string
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
    /**
     * Méthode vérifiant si une personne existe ou non avec son mail
     * @param string $mail
     * @return bool
     */
    public function checkPersonneExiste($mail)
    {
        $req = $this->bd->prepare('SELECT EXISTS (SELECT 1 FROM PERSONNE WHERE mail = :mail) AS personne_existe;');
        $req->bindValue(':mail', $mail);
        $req->execute();
        return $req->fetch()[0] == 't';
    }
    /**
     * Méthode vérifiant si une composante existe
     * @param string $nom_compo
     * @param string $nom_client
     * @return bool 
     */
    public function checkComposanteExiste($nom_compo, $nom_client)
    {
        $req = $this->bd->prepare('SELECT EXISTS (SELECT 1 FROM COMPOSANTE JOIN CLIENT USING(id_client) WHERE nom_composante = :nom_composante AND nom_client = :nom_client) AS composante_existe');
        $req->bindValue(':nom_composante', $nom_compo);
        $req->bindValue(':nom_client', $nom_client);
        $req->execute();
        return $req->fetch()[0] == 't';
    }

    /**
     * Méthode vérifiant si une société existe
     * @param string $nom_client
     * @return bool 
     */
    public function checkSocieteExiste($nom_client)
    {
        $req = $this->bd->prepare('SELECT EXISTS (SELECT 1 FROM CLIENT WHERE nom_client = :nom_client)');
        $req->bindValue(':nom_client', $nom_client, PDO::PARAM_STR);
        $req->execute();
        $result = $req->fetch(PDO::FETCH_NUM);
        // return $result[0] === 't';
        if ($result[0] == 't') {
            return true;
        }
        return false;
    }



    /**
     * Méthode vérifiant si un interlocuteur existe
     * @param string $mail
     * @return bool 
     */
    public function checkInterlocuteurExiste($mail)
    {
        $req = $this->bd->prepare('SELECT EXISTS (SELECT 1 FROM PERSONNE JOIN INTERLOCUTEUR USING(id_personne) WHERE mail = :mail) AS interlocuteur_existe');
        $req->bindValue(':mail', $mail);
        $req->execute();
        return $req->fetch()[0] == 't';
    }

    /**
     * Méthode vérifiant si un commercial existe
     * @param string $mail
     * @return bool 
     */
    public function checkCommercialExiste($mail)
    {
        $req = $this->bd->prepare('SELECT EXISTS (SELECT 1 FROM PERSONNE JOIN COMMERCIAL USING(id_personne) WHERE mail = :mail) AS commercial_existe');
        $req->bindValue(':mail', $mail);
        $req->execute();
        return $req->fetch()[0] == 't';
    }
    /**
     * Méthode vérifiant si un prestataire existe
     * @param string $mail
     * @return bool 
     */
    public function checkPrestataireExiste($mail)
    {
        $req = $this->bd->prepare('SELECT EXISTS (SELECT 1 FROM PERSONNE JOIN PRESTATAIRE USING(id_personne) WHERE mail = :mail) AS prestataire_existe');
        $req->bindValue(':mail', $mail);
        $req->execute();
        return $req->fetch()[0] == 't';
    }

    /**
     * Méthode vérifiant si un gestionnaire existe
     * @param string $mail
     * @return bool 
     */
    public function checkGestionnaireExiste($mail)
    {
        $req = $this->bd->prepare('SELECT EXISTS (SELECT 1 FROM PERSONNE JOIN GESTIONNAIRE USING(id_personne) WHERE mail = :mail) AS gestionnaire_existe');
        $req->bindValue(':mail', $mail);
        $req->execute();
        return $req->fetch()[0] == 't';
    }



    //Ajout rechercher_prestataire 10/05 Romain
    /**
     * Méthode permettant de rechercher un prestataire à partir de l'entrée de l'utilisateur
     * @param string $recherche
     * @return array|false
     */
    public function recherchePrestataire($recherche)
    {
        $req = $this->bd->prepare("
            SELECT
                p.id_personne 
            FROM 
                PERSONNE p
            JOIN PRESTATAIRE pr ON 
                p.id_personne = pr.id_personne
            WHERE 
                p.nom LIKE :recherche OR p.prenom LIKE :recherche"
        );
        // Modification ici: Ajoutez '%' à la fin de la chaîne de recherche pour permettre la recherche de tout texte commençant par 'recherche'
        $req->bindValue(':recherche', '%' . $recherche . '%', PDO::PARAM_STR);

        if ($req->execute()) {
            return $req->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return false; // ou retourner un message d'erreur spécifique ou lever une exception
        }
    }
    /**
     * Méthode permettant de récupérer les informations d'un bon de livraison à partir de son id
     * @param int $id
     * @return array
     */
    public function getBdlInfoById($id)
    {
        $req = $this->bd->prepare(" SELECT * From BDL where id_bdl=:id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall();

    }


    /**
     * Méthode permettant de récupérer les informations sur les prestataires à partir d'une liste d'identifiants
     * @param array $ids
     * @return array
     */
    public function getPrestataireByIds($ids)
    {
        $idsString = implode(',', array_map('intval', $ids));
        if (empty($ids)) {
            return 'Aucun prestataire';
        }

        $req = $this->bd->prepare("
        SELECT p.id_personne, p.nom, p.prenom, p.mail, p.telephone
        FROM Personne p
        JOIN Prestataire pr ON p.id_personne = pr.id_personne
            WHERE 
                p.id_personne IN ($idsString)
        ");

        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }
    /**
     * Méthode permettant de rechercher un commercial à partir de l'entrée de l'utilisateur
     * @param string $recherche
     * @return array|false
     */
    public function rechercheCommercial($recherche)
    {
        $req = $this->bd->prepare("
            SELECT
                p.id_personne 
            FROM 
                PERSONNE p
            JOIN Commercial c ON 
                p.id_personne = c.id_personne
            WHERE 
                p.nom LIKE :recherche OR p.prenom LIKE :recherche"
        );
        // Modification ici: Ajoutez '%' à la fin de la chaîne de recherche pour permettre la recherche de tout texte commençant par 'recherche'
        $req->bindValue(':recherche', '%' . $recherche . '%', PDO::PARAM_STR);

        if ($req->execute()) {
            return $req->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return false; // ou retourner un message d'erreur spécifique ou lever une exception
        }
    }
    /**
     * Méthode permettant de rechercher un client à partir de l'entrée de l'utilisateur
     * @param string $recherche
     * @return array|false
     */
    public function rechercheClient($recherche)
    {
        $req = $this->bd->prepare("
            SELECT 
                c.id_client
            FROM 
                Client c
            WHERE 
                c.nom_client LIKE :recherche
        ");
        $req->bindValue(':recherche', '%' . $recherche . '%', PDO::PARAM_STR);
        if ($req->execute()) {
            return $req->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return false; // ou retourner un message d'erreur spécifique ou lever une exception
        }
    }
    /**
     * Méthode permettant de récupérer les informations sur les clients  à partir d'une liste d'identifiants
     * @param array $ids
     * @return array
     */
    public function getClientByIds($ids)
    {
        $idsString = implode(',', array_map('intval', $ids));
        if (empty($ids)) {
            return [];
        }

        $req = $this->bd->prepare("
        SELECT 
            id_client, nom_client,telephone_client
        FROM 
            Client 
        WHERE 
            id_client IN ($idsString)
        ");

        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }



    /**
     * Méthode permettant de récupérer les informations sur les commerciaux à partir d'une liste d'identifiants
     * @param array $ids
     * @return array
     */
    public function getCommercialByIds($ids)
    {
        $idsString = implode(',', array_map('intval', $ids));
        if (empty($ids)) {
            return 'Aucun commercial';
        }

        $req = $this->bd->prepare("
        SELECT p.id_personne, p.nom, p.prenom, p.mail, p.telephone
        FROM Personne p
        JOIN Commercial c ON p.id_personne = c.id_personne
            WHERE 
                p.id_personne IN ($idsString)
        ");

        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Fonction permettant d'ajouter des heures pour le jour et bon de livraison spéficié
     * @param int $id_bdl
     * @param int $jour 
     * @param flaot $hours_worked 
     */
    public function insertDailyHours($id_bdl, $jour, $hours_worked)
    {
        try {
            $query = "INSERT INTO DailyHours (id_bdl, jour, hours_worked) VALUES (:id_bdl, :jour, :hours_worked)";
            $stmt = $this->bd->prepare($query);
            $stmt->bindParam(':id_bdl', $id_bdl);
            $stmt->bindParam(':jour', $jour);
            $stmt->bindParam(':hours_worked', $hours_worked);
            $stmt->execute();
            return true; // Succès de l'insertion
        } catch (PDOException $e) {
            return false; // Échec de l'insertion
        }
    }

    /**
     * Méthode permettant de récupérer la liste de tous les clients d'un commercial
     * @param int $id_commercial L'ID du commercial
     * @return array|string Un tableau du résultat de la requête sinon un message d'erreur
     */
    public function getClientsForCommercial($id_commercial)
    {
        // Préparation de la requête SQL pour récupérer les clients d'un commercial
        $req = $this->bd->prepare('
        SELECT DISTINCT cl.id_client, cl.nom_client, cl.telephone_client
        FROM Client cl
        JOIN Composante c ON cl.id_client = c.id_client
        JOIN Affecte a ON c.id_composante = a.id_composante
        JOIN Commercial co ON a.id_personne = co.id_personne
        WHERE co.id_personne = :id_commercial;
    ');

        // Liaison du paramètre
        $req->bindParam(':id_commercial', $id_commercial, PDO::PARAM_INT);

        // Exécution de la requête
        $req->execute();

        // Récupération des résultats
        $result = $req->fetchAll(PDO::FETCH_ASSOC);
        if (empty($result)) {
            return 'Il n\'y a aucun client pour ce commercial.';
        }
        return $result;
    }

    public function addAbsenceForPrestataire($id_personne, $date_absence, $motif)
    {
        $req = $this->bd->prepare('INSERT INTO Absences(id_personne, date_absence, motif) VALUES(:id_personne, :date_absence, :motif);');
        $req->bindValue(':id_personne', $id_personne, PDO::PARAM_INT);
        $req->bindValue(':date_absence', $date_absence);
        $req->bindValue(':motif', $motif);
        $req->execute();
        return (bool) $req->rowCount(); // Retourne true si au moins une ligne a été affectée
    }

    public function getAbsencesByPersonId($id_personne)
    {
        $req = $this->bd->prepare('SELECT * FROM Absences WHERE id_personne = :id_personne ORDER BY date_absence DESC');
        $req->bindValue(':id_personne', $id_personne, PDO::PARAM_INT);
        $req->execute();
        $absences = $req->fetchAll(PDO::FETCH_ASSOC);
        // var_dump($absences); // Ajouter ceci pour débogage
        return $absences;
    }
    public function getAbsenceById($id_absence)
    {
        $req = $this->bd->prepare('
            SELECT A.id, A.date_absence, A.motif, P.prenom, P.nom
            FROM Absences A
            JOIN Personne P ON A.id_personne = P.id_personne
            WHERE A.id = :id_absence
        ');
        $req->bindValue(':id_absence', $id_absence, PDO::PARAM_INT);
        $req->execute();
        return $req->fetch(PDO::FETCH_ASSOC);
    }
    // Méthode pour obtenir tous les BDLs
    public function getBDLsByPrestataireId($id_prestataire)
    {
        $stmt = $this->bd->prepare("SELECT * FROM bdl WHERE id_prestataire = :id_prestataire");
        $stmt->execute(['id_prestataire' => $id_prestataire]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // public function getAbsencesByPersonId($id_personne)
    // {
    //     $stmt = $this->bd->prepare("SELECT * FROM absences WHERE id_personne = :id_personne");
    //     $stmt->execute(['id_personne' => $id_personne]);
    //     return $stmt->fetchAll(PDO::FETCH_ASSOC);
    // }

   

 

    public function getAllBdlInterlocuteur($id_pr)
    {
        try {
            // Préparation de la requête SQL pour récupérer les bons de livraison associés à un prestataire
            $req = $this->bd->prepare("
                SELECT bdl.id_bdl, bdl.id_composante, bdl.annee, bdl.mois, bdl.signature_interlocuteur, 
                       bdl.signature_prestataire, bdl.commentaire, bdl.heures,
                       c.nom_composante, cl.nom_client, cl.telephone_client
                FROM bdl
                JOIN Interlocuteur pr ON bdl.id_interlocuteur = pr.id_personne
                JOIN Composante c ON bdl.id_composante = c.id_composante
                JOIN Client cl ON c.id_client = cl.id_client
                WHERE pr.id_personne = :id_pr
                and signature_prestataire = true
            ");

            // Liaison des paramètres pour éviter les injections SQL
            $req->bindValue(':id_pr', $id_pr, PDO::PARAM_INT);

            // Exécution de la requête
            $req->execute();

            // Récupération des résultats
            $result = $req->fetchAll(PDO::FETCH_ASSOC);

            // Vérifier si des résultats ont été trouvés
            if (!empty($result)) {
                return $result;
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo 'Erreur : ' . $e->getMessage();
            return false;
        }
    }

    // public function getPrestataireByComposante($id_interlocuteur)
    // {
    //     $req = $this->bd->prepare(" SELECT distinct nom, prenom , mail , telephone from bdl JOIN Prestataire pr ON bdl.id_prestataire = pr.id_personne join Personne per on bdl.id_prestataire = per.id_personne where bdl.id_interlocuteur = :id ");
    //     // Liaison des paramètres pour éviter les injections SQL
    //     $req->bindValue(':id', $id_interlocuteur, PDO::PARAM_INT);

    //     // Exécution de la requête
    //     $req->execute();

    //     // Récupération des résultats
    //     $result = $req->fetchAll(PDO::FETCH_ASSOC);
    //     return $result;
    // }
    public function getPrestataireByComposante($id_interlocuteur)
    {
        $req = $this->bd->prepare("SELECT DISTINCT pr.id_personne, nom, prenom, mail, telephone FROM bdl JOIN Prestataire pr ON bdl.id_prestataire = pr.id_personne JOIN Personne per ON bdl.id_prestataire = per.id_personne WHERE bdl.id_interlocuteur = :id");
        $req->bindValue(':id', $id_interlocuteur, PDO::PARAM_INT);
        $req->execute();
        $result = $req->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }


   

    public function getInterlocuteurNameById($id_interlocuteur)
    {
        // Préparation de la requête
        $req = $this->bd->prepare("SELECT nom, prenom FROM Personne INNER JOIN Interlocuteur ON Personne.id_personne = Interlocuteur.id_personne WHERE Interlocuteur.id_personne = :id_interlocuteur");

        // Exécution de la requête avec le paramètre
        $req->execute(array(':id_interlocuteur' => $id_interlocuteur));

        // Récupération du résultat
        $interlocuteur = $req->fetch(PDO::FETCH_ASSOC);

        // Vérifiez si l'interlocuteur existe et concaténez nom et prenom
        if ($interlocuteur) {
            return $interlocuteur['nom'] . ' ' . $interlocuteur['prenom'];
        } else {
            return null; // ou une valeur par défaut si l'interlocuteur n'existe pas
        }
    }

}