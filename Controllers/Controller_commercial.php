<?php

class Controller_commercial extends Controller
{
    public function action_default()
    {
        $this->action_dashboard();
    }
    public function action_dashboard()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['role'] = 'commercial';
        if (isset($_SESSION['id'])) {
            $bd = Model::getModel();
            $headerDashboard = ['Société', 'Composante','Nom Mission' ,'Préstataire assigné', 'Statut', 'Bon de livraison'];
            $data = ['menu'=>$this->action_get_navbar(),'header' => $headerDashboard, 'dashboard' => $bd->getdashboardCommercial($_SESSION['id'])];
            return $this->render('prestataire_missions', $data);
        } 
        else 
        {
            echo 'Une erreur est survenue lors du chargement du tableau de bord';
        }
    }


    /**
     * Action qui retourne les éléments du menu pour le commercial
     * @return array[]
     */
    public function action_get_navbar()
    {
        return [
            ['link' => '?controller=commercial&action=dashboard', 'name' => 'Missions'],
            ['link' => '?controller=commercial&action=composantes', 'name' => 'Composantes'],
            ['link' => '?controller=commercial&action=clients', 'name' => 'Clients'],
            ['link' => '?controller=commercial&action=prestataires', 'name' => 'Prestataires'],
            ];
    }
    public function action_clients()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['id'])) {
            $bd = Model::getModel();
            $buttonLink = '?controller=commercial&action=ajout_interlocuteur_form';
            $cardLink = '?controller=commercial&action=infos_client';
            $data = ['title' => 'Société', 'buttonLink' => $buttonLink, 'cardLink' => $cardLink, 'person' => $bd->getClientContactDashboardData(), 'menu' => $this->action_get_navbar()];
            $this->render("liste", $data);
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
            
            $title = 'Composantes';
            $cardLink = '?controller=commercial&action=infos_composante';
            $data = ['title' => $title, 'person' => $bd->getInterlocuteurForCommercial($_SESSION['id']), 'cardLink' => $cardLink, 'menu' => $this->action_get_navbar()];
            $this->render("liste", $data);
        }
    }


    public function action_commercial_interlocuteurs(){
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['id'])){
            $bd = Model::getModel();
            $data=[$bd->getInterlocuteurForCommercial($_SESSION['id'])];
            $this->render("liste",$data);
        }
        else 
        {
            echo 'Une erreur est survenue lors du chargement des clients.';
        }
    }

    public function action_prestataires(){
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['id'])){
            $bd = Model::getModel();
            
            $cardLink = "?controller=commercial&action=infos_personne";
            $data = ['title' => 'Prestataires', 'cardLink' => $cardLink, "person" => $bd->getPrestataireForCommercial($_SESSION['id']), 'menu' => $this->action_get_navbar()];
            $this->render("liste", $data);
        }
        else 
        {
            echo 'Une erreur est survenue lors du chargement des prestataire.';
        }
    }


    public function action_bdl_prestataire(){
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['id'])){
            $bd = Model::getModel();
            $data=[$bd->getBdlPrestaForCommercial($_GET['prestataire'],$_SESSION['id'])];
            $this->render("commercial_presta_bdl",$data);
        }
        else 
        {
            echo 'Une erreur est survenue lors du chargement des bdl.';
        }
    }

    public function action_ajout_interlocuteur()
    {
        $bd = Model::getModel();
        if (isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['email'])) {
            $mdp = genererMdp();
            $bd->createPersonne($_POST['nom'], $_POST['prenom'], $_POST['email'], $mdp);
            if ($bd->addInterlocuteur($_POST['email']) &&
                $bd->addInterlocuteurDansComposante($_POST['email'], $_POST['client'], $_POST['composante'])) {
                $data = ['title' => "Ajout d'un interlocuteur", 'message' => "L'interlocuteur a été ajouté !"];
            } else {
                $data = ['title' => "Ajout d'un interlocuteur", 'message' => "Echec lors de l'ajout de l'interlocuteur !"];
            }
            $this->render('message', $data);
        }
    }

    //Ajouter interlocuteur
    
    public function action_ajout_interlocuteur_form()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $ajoutInterlocuteurLink = '?controller=commercial&action=ajout_interlocuteur'; //le lien que tu dois modifier pour qu'il renvoie à l'action du commercial adéquate
        $data = ['ajoutInterlocuteurLink' => $ajoutInterlocuteurLink, 'menu' => $this->action_get_navbar()];
        $this->render('ajout_interlocuteur', $data);
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

    public function action_infos_personne()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_GET['id'])) {
            $bd = Model::getModel();
            $data = ['person' => $bd->getInfosPersonne($_GET['id']), 'menu' => $this->action_get_navbar()];
            $this->render("infos_personne", $data);
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

}
