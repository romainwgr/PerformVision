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
        if(!array_key_exists('role', $_SESSION)){
            $_SESSION['role'] = 'prestataire';
        }
        if (isset($_SESSION['id'])) {
            $bd = Model::getModel();
            $headerDashboard = ['Société', 'Composante', 'Nom Mission', 'Statut', 'Bon de livraison'];
            $data = ['menu' => $this->action_getNavBar(),'header' => $headerDashboard, 'dashboard' => $bd->getDashboardPrestataire($_SESSION['id'])];
            return $this->render('prestataire_missions', $data);
        } else {
            echo 'Une erreur est survenue lors du chargement du tableau de bord';
        }
    }

    public function action_getNavBar()
    {
        return [
            ['link' => '?controller=prestataire&action=dashboard', 'name' => 'Missions'],
            ['link' => '?controller=prestataire&action=prestataires_clients', 'name' => 'Clients'],
            ['link' => '?controller=prestataire&action=bdl', 'name' => 'Bons de livraison']];
    }


    public function action_prestataire_creer_absences(){
        $bd=Model::getModel();
        if(isset($_POST['prenom']) && isset($_POST['nom']) && isset($_POST['email']) && isset($_POST['Date']) && isset($_POST['motif'])){
            $bd->addAbsenceForPrestataire($_POST['prenom'],$_POST['nom'],$_POST['email'],$_POST['Date'],$_POST['motif']);
        } else{
            $this->action_error("données incomplètes");
        }
    }
    

    public function action_prestataire_Statut(){
        $bd=Model::getModel();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['id'])){
            if (isset($_POST['jour']) && isset($_POST['mission'])){
                $bd->setAbsenceForPrestataire($_POST['jour'],$_POST['mission'],$_SESSION['id']);
            }
        }else{
            echo "Une erreur est survenue lors du chargement de l'absence de ce jour";
        }
    }

    public function action_prestataire_clients(){
        $bd=Model::getModel();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['id'])){
            $data=["tableau"=>$bd->getInterlocuteurForPrestataire($_SESSION['id'])];
            $this->render("prestataire_interlocuteurs",$data);
        }else{
            echo 'Une erreur est survenue lors du chargement des clients';
        }
    }

    public function action_prestataire_bdl(){
        $bd=Model::getModel();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['id']) && isset($_POST['mission'])){
            $data=["bdl"=>$bd->getBdlPrestaForPrestataire($_SESSION['id'],$_POST['mission'])];
            $this->render("bdl",$data);
        }else{
            echo 'Une erreur est survenue lors du chargement de ce bon de livraison';
        }
    }
    
    public function action_prestataire_Allbdl()
    {
        $bd = Model::getModel();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['id'])) {
            $data = ["bdl" => $bd->getBdlPresta($_SESSION['id'])];
            $this->render("bdl", $data);
        }
    }
        

    public function action_prestataire_creer_bdl(){
        $bd=Model::getModel();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['id']) && isset($_POST['mission'])){
            $bd->addBdlForPrestataire($_SESSION['id'],$_POST['mission']);
        }else{
            echo 'Une erreur est survenue lors de la création du bon de livraison';
        }
    }

    public function action_ajout_prestataire()
    {
        $bd = Model::getModel();
        if (isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['email'])) {
            $mdp = genererMdp();
            $bd->createPersonne($_POST['nom'], $_POST['prenom'], $_POST['email'], $mdp);
            if($bd->addPrestataire($_POST['email'], $_POST['client'])){
                $data = ['title' => "Ajout d'un prestataire", 'message' => "Le prestataire a été ajouté !"];
            }else{
                $data = ['title' => "Ajout d'un prestataire", 'message' => "Echec lors de l'ajout du prestataire !"];
            }
            $this->return('message', $data);
        }
    }
}
