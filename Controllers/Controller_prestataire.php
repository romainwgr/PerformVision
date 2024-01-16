<?php

class Controller_prestataire extends Controller
{
    /**
     * @inheritDoc
     */
    public function action_default()
    {
        $this->action_dashboard();
    }

    public function action_dashboard()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!array_key_exists('role', $_SESSION)) {
            $_SESSION['role'] = 'prestataire';
        }
        if (isset($_SESSION['id'])) {
            $bd = Model::getModel();
            $headerDashboard = ['Société', 'Composante', 'Nom Mission', 'Bon de livraison'];
            $data = ['menu' => $this->action_get_navbar(), 'header' => $headerDashboard, 'dashboard' => $bd->getDashboardPrestataire($_SESSION['id'])];
            return $this->render('prestataire_missions', $data);
        } else {
            echo 'Une erreur est survenue lors du chargement du tableau de bord';
        }
    }

    public function action_get_navbar()
    {
        return [
            ['link' => '?controller=prestataire&action=dashboard', 'name' => 'Missions'],
            ['link' => '?controller=prestataire&action=liste_bdl', 'name' => 'Bons de livraison']];
    }


    public function action_prestataire_creer_absences()
    {
        $bd = Model::getModel();
        if (isset($_POST['prenom']) && isset($_POST['nom']) && isset($_POST['email']) && isset($_POST['Date']) && isset($_POST['motif'])) {
            $bd->addAbsenceForPrestataire($_POST['prenom'], $_POST['nom'], $_POST['email'], $_POST['Date'], $_POST['motif']);
        } else {
            $this->action_error("données incomplètes");
        }
    }


    public function action_prestataire_Statut()
    {
        $bd = Model::getModel();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['id'])) {
            if (isset($_POST['jour']) && isset($_POST['mission'])) {
                $bd->setAbsenceForPrestataire($_POST['jour'], $_POST['mission'], $_SESSION['id']);
            }
        } else {
            echo "Une erreur est survenue lors du chargement de l'absence de ce jour";
        }
    }

    public function action_prestataire_clients()
    {
        $bd = Model::getModel();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['id'])) {
            $data = ["tableau" => $bd->getInterlocuteurForPrestataire($_SESSION['id'])];
            $this->render("prestataire_interlocuteurs", $data);
        } else {
            echo 'Une erreur est survenue lors du chargement des clients';
        }
    }

    public function action_afficher_bdl()
    {
        $bd = Model::getModel();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_GET['id'])) {
            $data = ['menu' => $this->action_get_navbar(), 'bdl' => $bd->getBdlType($_GET['id'])];
            $this->render("activite", $data);
        } else {
            echo 'Une erreur est survenue lors du chargement de ce bon de livraison';
        }
    }

    public function action_mission_bdl(){
        $bd = Model::getModel();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if(isset($_GET['id'])){
            $headerDashboard = ['Société', 'Composante', 'Nom Mission', 'Bon de livraison'];
            $buttonLink = '?controller=prestataire&action=ajout_bdl_form';
            $cardLink = '?controller=prestataire&action=afficher_bdl';
            $data = ['title' => 'Bons de livraison', 'buttonLink' => $buttonLink, 'cardLink' => $cardLink, 'menu' => $this->action_get_navbar(), 'header' => $headerDashboard, 'person' => $bd->getBdlsOfPrestataireByIdMission($_GET['id'], $_SESSION['id'])];
            $this->render('liste', $data);
        }
    }

    public function action_liste_bdl()
    {
        $bd = Model::getModel();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['id'])) {
            $cardLink = '?controller=prestataire&action=afficher_bdl';
            $buttonLink = '?controller=prestataire&action=ajout_bdl_form';
            $data = ['title' => 'Mes Bons de livraison', 'buttonLink' => $buttonLink, 'cardLink' => $cardLink, 'menu' => $this->action_get_navbar(), "person" => $bd->getAllBdlPrestataire($_SESSION['id'])];
            $this->render("liste", $data);
        }
    }


    public function action_prestataire_creer_bdl()
    {
        $bd = Model::getModel();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['id']) && isset($_POST['mission'])) {
            $bd->addBdlForPrestataire($_SESSION['id'], $_POST['mission']);
        } else {
            echo 'Une erreur est survenue lors de la création du bon de livraison';
        }
    }

    public function action_completer_bdl()
    {
        $bd = Model::getModel();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // Récupérer les données depuis la requête POST
        $data = json_decode(file_get_contents("php://input"), true);

        // Vérifier si les données sont présentes
        if ($data && is_array($data)) {
            // Parcourir chaque ligne du tableau
            foreach ($data as $row) {
                // Vérifier si l'activite existe avant de l'ajouter, sinon la modifier
                if ($bd->checkActiviteExiste($_GET['id'], $row[0])) {
                    $id_activite = $bd->getIdActivite($row[0], $_GET['id']);
                    if ($row[1] && $_GET['type'] == 'Heure') {
                        $bd->setNbHeure($id_activite, (int)$row[1]);
                    } elseif ($row[1] >= 0 && $row[1] <= 1 && $_GET['type'] == 'Journée') {
                        $bd->setJourneeJour($id_activite, (int)$row[1]);
                    } elseif ($row[1] >= 0 && $row[1] <= 2 && $_GET['type'] == 'Demi-journée') {
                        $bd->setDemiJournee($id_activite, (int)$row[1]);
                    }
                    if ($row[2]) {
                        $bd->setCommentaireActivite($id_activite, $row[2]);
                    }
                } elseif ($row[1]) {
                    if ($row[1] && $_GET['type'] == 'Heure') {
                        $bd->addNbHeureActivite($row[2], $_GET['id'], $_SESSION['id'], $row[0], (int)$row[1]);
                    } elseif ($row[1] >= 0 && $row[1] <= 1 && $_GET['type'] == 'Journée') {
                        $bd->addJourneeJour($row[2], $_GET['id'], $_SESSION['id'], $row[0], (int)$row[1]);
                    } elseif ($row[1] >= 0 && $row[1] <= 2 && $_GET['type'] == 'Demi-journée') {
                        $bd->addDemiJournee($row[2], $_GET['id'], $_SESSION['id'], $row[0], (int)$row[1]);
                    }
                }
            }
        }
        $this->render('dashboard');
    }

    public function action_ajout_prestataire()
    {
        $bd = Model::getModel();
        if (isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['email'])) {
            $mdp = genererMdp();
            $bd->createPersonne($_POST['nom'], $_POST['prenom'], $_POST['email'], $mdp);
            if ($bd->addPrestataire($_POST['email'], $_POST['client'])) {
                $data = ['title' => "Ajout d'un prestataire", 'message' => "Le prestataire a été ajouté !"];
            } else {
                $data = ['title' => "Ajout d'un prestataire", 'message' => "Echec lors de l'ajout du prestataire !"];
            }
            $this->render('message', $data);
        }
    }

    public function action_ajout_bdl_form()
    {
        $data = ['menu' => $this->action_get_navbar()];
        $this->render('ajout_bdl', $data);
    }

    public function action_ajout_bdl(){
        $bd = Model::getModel();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if($_POST['mission'] && $_POST['mois'] && $_POST['composante']){
            $bd->addBdlInMission($_POST['mission'], $_POST['composante'], $_POST['mois'], $_SESSION['id']);
        }
        $this->action_ajout_bdl_form();
    }
}
