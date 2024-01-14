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
            $data = ['menu' => $this->action_getNavBar(), 'buttonLink' => $buttonLink, 'header' => $headerDashboard, 'dashboard' => $bd->getDashboardGestionnaire()];
            return $this->render('gestionnaire_missions', $data);
        } else {
            echo 'Une erreur est survenue lors du chargement du tableau de bord';
        }
    }

    /**
     * Action qui retourne les éléments du menu pour le gestionnaire
     * @return array[]
     */
    public function action_getNavBar()
    {
        return [['link' => '?controller=gestionnaire&action=clients', 'name' => 'Société'],
            ['link' => '?controller=gestionnaire&action=missions', 'name' => 'Missions'],
            ['link' => '?controller=gestionnaire&action=interlocuteurs', 'name' => 'Composantes'],
            ['link' => '?controller=gestionnaire&action=prestataires', 'name' => 'Prestataires'],
            ['link' => '?controller=gestionnaire&action=commerciaux', 'name' => 'Commerciaux']];
    }

    public function action_infos(){
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->render('infos', ['menu' => $this->action_getNavBar()]);
    }

    public function action_maj_infos(){
        action_maj_infos();
    }

    /**
     * @return void
     */
    public function action_interlocuteurs()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['id'])) {
            $bd = Model::getModel();
            $buttonLink = '?controller=gestionnaire&action=ajout_interlocuteur_form';
            $title = 'Interlocuteurs Client';
            $data = ['title' => $title, 'person' => $bd->getAllInterlocuteurs(), 'buttonLink' => $buttonLink, 'menu' => $this->action_getNavBar()];
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
            $buttonLink = '?controller=gestionnaire&action=ajout_mission_form';
            $data = ['title' => 'Société', 'buttonLink' => $buttonLink, 'person' => $bd->getAllClients(), 'menu' => $this->action_getNavBar()];
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
            $data = ['title' => 'Prestataires', "buttonLink" => $buttonLink, "person" => $bd->getAllPrestataires(), 'menu' => $this->action_getNavBar()];
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
            $data = ['title' => 'Commerciaux', 'buttonLink' => $buttonLink, "person" => $bd->getAllCommerciaux(), 'menu' => $this->action_getNavBar()];
            $this->render("liste", $data);
        }
    }

    public function action_assigner_prestataire()
    {
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

    public function action_assigner_commecial_mission()
    {
        $bd = Model::getModel();
        if (isset($_POST['email']) && isset($_POST['mission'])) {
            $bd->assignerCommercial($_POST['email'], $_POST['mission']);
        }
        $this->action_interlocuteurs();
    }

    public function action_gestionnaire_supprimer_prestataire()
    {
        $bd = Model::getModel();
        if (isset($_POST['supprimer'])) {
            $bd->removePrestataireForGestionnaire($_POST['supprimer']);
        }
        $this->render("gestionnarie_prestataires");
    }

    public function action_gestionnaire_supprimer_interlocuteur()
    {
        $bd = Model::getModel();
        if (isset($_POST['supprimer'])) {
            $bd->removeInterlocuteurForGestionnaire($_POST['supprimer']);
        }
        $this->render("gestionnarie_clients");
    }

    public function action_gestionnaire_supprimer_commercial()
    {
        $bd = Model::getModel();
        if (isset($_POST['supprimer'])) {
            $bd->removeCommercialForGestionnaire($_POST['supprimer']);
        }
        $this->render("gestionnarie_commerciaux");
    }

    /*--------------------------------------------------------------------------------------*/
    /*                                Formulaires d'ajout                                  */
    /*--------------------------------------------------------------------------------------*/

    public function action_ajout_interlocuteur_form()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $data = ['menu' => $this->action_getNavBar()];
        $this->render('ajout_interlocuteur', $data);
    }

    public function action_ajout_prestataire_form()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $data = ['menu' => $this->action_getNavBar()];
        $this->render('ajout_prestataire', $data);
    }

    public function action_ajout_mission_form()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $data = ['menu' => $this->action_getNavBar()];
        $this->render('ajout_mission', $data);
    }

    public function action_ajout_commercial_form()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $data = ['menu' => $this->action_getNavBar()];
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

    public function action_ajout_interlocuteur_dans_composante()
    {
        $bd = Model::getModel();
        if (isset($_POST['composante']) && isset($_POST['client'])) {
            $bd->addInterlocuteurForGestionnaire($_POST['composante'], $_POST['client']);
        }
        $this->action_interlocuteurs();
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
        if (isset($_POST['composante']) && isset($_POST['email'])) {
            $bd->addCommercialForGestionnaire($_POST['composante'], $_POST['email']);
        }
        $this->action_commerciaux();
    }

    /* À mettre dans Controller_Client
    public function action_ajout_client()
    {
        $bd = Model::getModel();
        if (isset($_POST['client']) && isset($_POST['telephone']) && isset($_POST['composante']) && isset($_POST['addresse']) && isset($_POST['email'])) {
            $bd->addClientForGestionnaire($_POST['client'], $_POST['telephone'], $_POST['composante'], $_POST['addresse'], $_POST['email']);
        }
        $this->render("gestionnaire_clients");
    }
    */
}

?>
