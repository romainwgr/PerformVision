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
            $bdlLink = '?controller=commercial&action=mission_bdl';
            $headerDashboard = ['Société', 'Composante','Nom Mission' ,'Préstataire assigné', 'Bon de livraison'];
            $data = ['menu'=>$this->action_get_navbar(), 'bdlLink' => $bdlLink, 'header' => $headerDashboard, 'dashboard' => $bd->getdashboardCommercial($_SESSION['id'])];
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

    public function action_mission_bdl(){
        $bd = Model::getModel();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if(isset($_GET['id']) && isset($_GET['id-prestataire'])){
            $cardLink = '?controller=commercial&action=consulter_bdl';
            $data = ['title' => 'Bons de livraison', 'cardLink' => $cardLink, 'menu' => $this->action_get_navbar(), 'person' => $bd->getBdlsOfPrestataireByIdMission($_GET['id'], $_GET['id-prestataire'])];
            $this->render('liste', $data);
        }
    }

    public function action_consulter_bdl(){
        $bd = Model::getModel();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_GET['id'])) {
            $typeBdl = $bd->getBdlTypeAndMonth($_GET['id']);
            if($typeBdl['type_bdl'] == 'Heure'){
                $activites = $bd->getAllNbHeureActivite($_GET['id']);
            }
            if($typeBdl['type_bdl'] == 'Demi-journée'){
                $activites = $bd->getAllDemiJourActivite($_GET['id']);
            }
            if($typeBdl['type_bdl'] == 'Journée'){
                $activites = $bd->getAllJourActivite($_GET['id']);
            }

            $data = ['menu' => $this->action_get_navbar(), 'bdl' => $typeBdl, 'activites' => $activites];
            $this->render("consulte_bdl", $data);
        } else {
            echo 'Une erreur est survenue lors du chargement de ce bon de livraison';
        }
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
            $data = ['title' => 'Société', 'buttonLink' => $buttonLink, 'cardLink' => $cardLink, 'person' => $bd->getClientForCommercial(), 'menu' => $this->action_get_navbar()];
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
            $data = ['title' => $title, 'person' => $bd->getComposantesForCommercial($_SESSION['id']), 'cardLink' => $cardLink, 'menu' => $this->action_get_navbar()];
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
        if (isset($_POST['nom-interlocuteur']) && isset($_POST['prenom-interlocuteur']) && isset($_POST['email-interlocuteur'])) {
            $mdp = genererMdp();
            $bd->createPersonne($_POST['nom-interlocuteur'], $_POST['prenom-interlocuteur'], $_POST['email-interlocuteur'], $mdp);
            if ($bd->addInterlocuteur($_POST['email-interlocuteur']) &&
                $bd->addInterlocuteurDansComposante($_POST['email-interlocuteur'], $_GET['id'])) {
                $data = ['title' => "Ajout d'un interlocuteur", 'message' => "L'interlocuteur a été ajouté !"];
            } else {
                $data = ['title' => "Ajout d'un interlocuteur", 'message' => "Echec lors de l'ajout de l'interlocuteur !"];
            }
            $this->render('message', $data);
        }
    }

    public function action_ajout_personne($nom, $prenom, $email)
    {
        $bd = Model::getModel();
        if (!$bd->checkPersonneExiste($email)) {
            $bd->createPersonne($nom, $prenom, $email, genererMdp());
        }
    }

    public function action_ajout_interlocuteur_dans_composante()
    {
        $bd = Model::getModel();
        if (isset($_GET['id-composante']) && isset($_POST['email-interlocuteur']) && isset($_POST['nom-interlocuteur']) && isset($_POST['prenom-interlocuteur'])) {
            if (!$bd->checkInterlocuteurExiste($_POST['email-interlocuteur'])) {
                $this->action_ajout_personne($_POST['nom-interlocuteur'], $_POST['prenom-interlocuteur'], $_POST['email-interlocuteur']);
                $bd->addInterlocuteur($_POST['email-interlocuteur']);
            }
            $bd->assignerInterlocuteurComposanteByIdComposante($_GET['id-composante'], $_POST['email-interlocuteur']);
            $this->action_composantes();
        }
        if (isset($_GET['id-client']) && isset($_POST['email-interlocuteur']) && isset($_POST['nom-interlocuteur']) && isset($_POST['prenom-interlocuteur']) && isset($_POST['composante'])) {
            if (!$bd->checkInterlocuteurExiste($_POST['email-interlocuteur'])) {
                $this->action_ajout_personne($_POST['nom-interlocuteur'], $_POST['prenom-interlocuteur'], $_POST['email-interlocuteur']);
                $bd->addInterlocuteur($_POST['email-interlocuteur']);
            }
            $bd->assignerInterlocuteurComposanteByIdClient($_GET['id-client'], $_POST['email-interlocuteur'], $_POST['composante']);
            $this->action_clients();
        }
    }

    //Ajouter interlocuteur

    public function action_ajout_interlocuteur_form()
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
