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
        if (!array_key_exists('role', $_SESSION)) {
            $_SESSION['role'] = 'commercial';
        }
        if (isset($_SESSION['id'])) {
            $bd = Model::getModel();
            $headerDashboard = ['Société', 'Composante','Nom Mission' ,'Préstataire assigné', 'Statut', 'Bon de livraison'];
            $data = ['menu'=>$this->action_get_navbar(),'header' => $headerDashboard, 'dashboard' => $bd->getdashboardCommercial($_SESSION['id'])];
            return $this->render('commercial_missions', $data);
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
            $buttonLink = '?controller=commercial&action=ajout_client_form';
            $cardLink = '?controller=commercial&action=infos_client';
            $data = ['title' => 'Société', 'buttonLink' => $buttonLink, 'cardLink' => $cardLink, 'person' => $bd->getClientContactDashboardData(), 'menu' => $this->action_get_navbar()];
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
            $this->render("commercial_interlocuteurs",$data);
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
            $this->render("commercial_prestataires", $data);
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
            $data=[$bd->getBdlPrestaForCommercial($_POST['prestataire'],$_SESSION['id'])];
            $this->render("commercial_presta_bdl",$data);
        }
        else 
        {
            echo 'Une erreur est survenue lors du chargement des bdl.';
        }
    }

    public function action_commercial_ajouter_interlocuteur(){
        $bd=Model::getModel();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if(isset($_POST['composante']) && isset($_POST['client'])){
            $bd->addInterlocuteurForCommercial($_POST['composante'],$_POST['email'], $_POST['client']);
        }
        $this->render("commercial_clients");
    }

    //Ajouter interlocuteur
    public function action_ajout_client_form()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $data = ['menu' => $this->action_get_navbar()];
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
    

}
