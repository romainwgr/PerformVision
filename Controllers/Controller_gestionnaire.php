<?php

class Controller_gestionnaire extends Controller
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
            $_SESSION['role'] = 'gestionnaire';
        }
        if (isset($_SESSION['id'])) {
            $bd = Model::getModel();
            $buttonLink = '?controller=gestionnaire&action=ajout_mission_form';
            $headerDashboard = ['Société', 'Composante', 'Nom Mission', 'Préstataire assigné', 'Statut', 'Bon de livraison'];
            $data = ['menu' => $this->action_get_navbar(), 'buttonLink' => $buttonLink, 'header' => $headerDashboard, 'dashboard' => $bd->getDashboardGestionnaire()];
            return $this->render('gestionnaire_missions', $data);
        } else {
            echo 'Une erreur est survenue lors du chargement du tableau de bord';
        }
    }

    /**
     * Action qui retourne les éléments du menu pour le gestionnaire
     * @return array[]
     */
    public function action_get_navbar()
    {
        return [['link' => '?controller=gestionnaire&action=clients', 'name' => 'Société'],
            ['link' => '?controller=gestionnaire&action=composantes', 'name' => 'Composantes'],
            ['link' => '?controller=gestionnaire&action=missions', 'name' => 'Missions'],
            ['link' => '?controller=gestionnaire&action=prestataires', 'name' => 'Prestataires'],
            ['link' => '?controller=gestionnaire&action=commerciaux', 'name' => 'Commerciaux']];
    }

    public function action_infos()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->render('infos', ['menu' => $this->action_get_navbar()]);
    }

    /*--------------------------------------------------------------------------------------*/
    /*                                Fonctions de mise à jour                              */
    /*--------------------------------------------------------------------------------------*/

    public function action_maj_infos()
    {
        maj_infos_personne(); // fonction dans Utils
        $this->action_infos();
    }

    public function action_maj_infos_client()
    {
        maj_infos_client();
        $this->action_infos_client();
    }

    public function action_maj_infos_personne(){
        maj_infos_personne();
        $this->action_infos_personne();
    }

    public function action_infos_personne(){
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_GET['id'])) {
            $bd = Model::getModel();
            $data = ['person' => $bd->getInfosPersonne($_GET['id']), 'menu' => $this->action_get_navbar()];
            $this->render("infos_personne", $data);
        }
    }

    /**
     * @return void
     */
    public function action_composantes()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['id'])) {
            $bd = Model::getModel();
            $buttonLink = '?controller=gestionnaire&action=ajout_composante_form';
            $title = 'Composantes';
            $cardLink = '?controller=gestionnaire&action=infos_composante';
            $data = ['title' => $title, 'person' => $bd->getAllComposantes(), 'buttonLink' => $buttonLink, 'cardLink' => $cardLink, 'menu' => $this->action_get_navbar()];
            $this->render("liste", $data);
        }
    }

    public function action_clients()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['id'])) {
            $bd = Model::getModel();
            $buttonLink = '?controller=gestionnaire&action=ajout_client_form';
            $cardLink = '?controller=gestionnaire&action=infos_client';
            $data = ['title' => 'Société', 'buttonLink' => $buttonLink, 'cardLink' => $cardLink, 'person' => $bd->getAllClients(), 'menu' => $this->action_get_navbar()];
            $this->render("liste", $data);
        }
    }

    public function action_prestataires()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['id'])) {
            $bd = Model::getModel();
            $buttonLink = '?controller=gestionnaire&action=ajout_prestataire_form';
            $data = ['title' => 'Prestataires', "buttonLink" => $buttonLink, "person" => $bd->getAllPrestataires(), 'menu' => $this->action_get_navbar()];
            $this->render("liste", $data);
        }
    }

    public function action_commerciaux()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['id'])) {
            $bd = Model::getModel();
            $buttonLink = '?controller=gestionnaire&action=ajout_commercial_form';
            $data = ['title' => 'Commerciaux', 'buttonLink' => $buttonLink, "person" => $bd->getAllCommerciaux(), 'menu' => $this->action_get_navbar()];
            $this->render("liste", $data);
        }
    }

    public function action_bdl()/*utilisé sur la page missions pour montré les bdl de cette mission*/
    {
        if (isset($_POST['mission'], $_POST['prestataire'])) {
            $data = ["bdl" => $bd->getBdlPrestaForGestionnaire($_POST['prestataire'], $_POST['mission'])];
            $this->render("bdl", $data);
        }
    }

    public function action_assigner_prestataire()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $bd = Model::getModel();
        if (isset($_POST['email'])) {
            $bd->assignerPrestataire($_POST['email'], $_POST['mission']);
        }
        $this->action_dashboard();
    }

    public function action_assigner_commercial_interlocuteur()
    {
        $bd = Model::getModel();
        if (isset($_POST['email']) && isset($_POST['client'])) {
            $bd->assignerCommercial($_POST['email'], $_POST['client']);
        }
        $this->action_dashboard();
    }

    public function action_gestionnaire_supprimer_prestataire()
    {
        $bd = Model::getModel();
        if (isset($_POST['supprimer'])) {
            $bd->removePrestataireForGestionnaire($_POST['supprimer']);
        }
        $this->action_prestataires();
    }

    public function action_gestionnaire_supprimer_interlocuteur()
    {
        $bd = Model::getModel();
        if (isset($_POST['supprimer'])) {
            $bd->removeInterlocuteurForGestionnaire($_POST['supprimer']);
        }
        $this->action_prestataires();
    }

    public function action_gestionnaire_supprimer_commercial()
    {
        $bd = Model::getModel();
        if (isset($_POST['supprimer'])) {
            $bd->removeCommercialForGestionnaire($_POST['supprimer']);
        }
        $this->action_commerciaux();
    }

    /*--------------------------------------------------------------------------------------*/
    /*                                Formulaires d'ajout                                  */
    /*--------------------------------------------------------------------------------------*/

    public function action_ajout_interlocuteur_form()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $data = ['menu' => $this->action_get_navbar()];
        $this->render('ajout_interlocuteur', $data);
    }

    public function action_ajout_composante_form()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $data = ['menu' => $this->action_get_navbar()];
        $this->render('ajout_composante', $data);
    }

    public function action_ajout_prestataire_form()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $data = ['menu' => $this->action_get_navbar()];
        $this->render('ajout_prestataire', $data);
    }

    public function action_ajout_mission_form()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $data = ['menu' => $this->action_get_navbar()];
        $this->render('ajout_mission', $data);
    }

    public function action_ajout_client_form()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $data = ['menu' => $this->action_get_navbar()];
        $this->render('ajout_client', $data);
    }

    public function action_ajout_commercial_form()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $data = ['menu' => $this->action_get_navbar()];
        $this->render('ajout_commercial', $data);
    }

    public function action_ajout_gestionnaire()
    {
        $bd = Model::getModel();
        if (isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['email'])) {
            $mdp = genererMdp();
            $bd->createPersonne($_POST['nom'], $_POST['prenom'], $_POST['email'], $mdp);
            if ($bd->addGestionnaire($_POST['email'])) {
                $data = ['title' => "Ajout d'un gestionnaire", 'message' => "Le gestionnaire a été ajouté !"];
            } else {
                $data = ['title' => "Ajout d'un gestionnaire", 'message' => "Echec lors de l'ajout du gestionnaire !"];
            }
            $this->return('message', $data);
        }
    }

    public function action_ajout_client()
    {
        $bd = Model::getModel();
        if (isset($_POST['client']) &&
            isset($_POST['tel']) &&
            isset($_POST['mission']) &&
            isset($_POST['type-bdl']) &&
            isset($_POST['date-mission']) &&
            isset($_POST['composante']) &&
            isset($_POST['numero-voie']) &&
            isset($_POST['type-voie']) &&
            isset($_POST['nom-voie']) &&
            isset($_POST['cp']) &&
            isset($_POST['ville']) &&
            isset($_POST['prenom-interlocuteur']) &&
            isset($_POST['nom-interlocuteur']) &&
            isset($_POST['email-interlocuteur']) &&
            isset($_POST['prenom-commercial']) &&
            isset($_POST['nom-commercial']) &&
            isset($_POST['email-commercial'])) {

            $bd->addClient($_POST['client'], $_POST['tel']);
            $this->action_ajout_composante();
        }
        $this->action_ajout_client_form();
    }

    public function action_ajout_personne($nom, $prenom, $email)
    {
        $bd = Model::getModel();
        if (!$bd->checkPersonneExiste($email)) {
            $bd->createPersonne($nom, $prenom, $email, genererMdp());
        }
    }

    public function action_ajout_interlocuteur()
    {
        $bd = Model::getModel();
        $this->action_ajout_personne($_POST['nom-interlocuteur'], $_POST['prenom-interlocuteur'], $_POST['email-interlocuteur']);
        $bd->addInterlocuteur($_POST['email-interlocuteur']);
    }

    public function action_ajout_composante(){
        $bd = Model::getModel();
        $bd->addComposante($_POST['type-voie'],
            $_POST['cp'],
            $_POST['numero-voie'],
            $_POST['nom-voie'],
            $_POST['client'],
            $_POST['composante']);
        $this->action_ajout_interlocuteur();
        $this->action_ajout_interlocuteur_dans_composante();
        $this->action_ajout_commercial_dans_composante();
        $this->action_ajout_mission();
    }

    public function action_ajout_mission(){
        $bd = Model::getModel();
        $bd->addMission($_POST['type-bdl'],
            $_POST['mission'],
            $_POST['date-mission'],
            $_POST['composante'],
            $_POST['client']);
    }

    public function action_ajout_interlocuteur_dans_composante()
    {
        $bd = Model::getModel();
        if (isset($_POST['composante']) && isset($_POST['client']) && $_POST['email-interlocuteur']) {
            $bd->assignerInterlocuteurComposante($_POST['composante'], $_POST['client'], $_POST['email-interlocuteur']);
        }
    }

    public function action_ajout_prestataire_dans_mission()
    {
        $bd = Model::getModel();
        if (isset($_POST['mission']) && isset($_POST['email'])) {
            $bd->addPrestataireForGestionnaire($_POST['mission'], $_POST['email']);
        }
        $this->action_prestataires();
    }

    public function action_ajout_commercial_dans_composante()
    {
        $bd = Model::getModel();
        if (isset($_POST['composante']) && isset($_POST['email-commercial']) && isset($_POST['client'])) {
            $bd->assignerCommercial($_POST['email-commercial'], $_POST['composante'], $_POST['client']);
        }
    }

    public function action_infos_composante()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_GET['id'])) {
            $bd = Model::getModel();
            $infos = $bd->getInfosComposante($_GET['id']);
            $prestataires = $bd->getPrestatairesComposante($_GET['id']);
            $commerciaux = $bd->getCommerciauxComposante($_GET['id']);
            $interlocuteurs = $bd->getInterlocuteursComposante($_GET['id']);
            $bdl = $bd->getBdlComposante($_GET['id']);
            $data = ['infos' => $infos,
                'prestataires' => $prestataires,
                'commerciaux' => $commerciaux,
                'interlocuteurs' => $interlocuteurs,
                'bdl' => $bdl,
                'menu' => $this->action_get_navbar()];
            $this->render('infos_composante', $data);
        }
    }

    public function action_infos_client()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_GET['id'])) {
            $bd = Model::getModel();
            $infos = $bd->getInfosSociete($_GET['id']);
            $composantes = $bd->getComposantesSociete($_GET['id']);
            $interlocuteurs = $bd->getInterlocuteursSociete($_GET['id']);
            $data = ['infos' => $infos,
                'composantes' => $composantes,
                'interlocuteurs' => $interlocuteurs,
                'menu' => $this->action_get_navbar()];
            $this->render('infos_client', $data);
        }
    }
}

?>
