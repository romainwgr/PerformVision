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
        if (isset($_SESSION['id'])) {
            $bd = Model::getModel();
            $nav = [['link' => '?controller=gestionnaire&action=missions', 'name' => 'Missions'],
                ['link' => '?controller=gestionnaire&action=interlocuteurs', 'name' => 'Clients'],
                ['link' => '?controller=gestionnaire&action=prestataires', 'name' => 'Prestataires'],
                ['link' => '?controller=gestionnaire&action=commerciaux', 'name' => 'Commerciaux']];
            $headerDashboard = ['Société', 'Composante','Nom Mission' ,'Préstataire assigné', 'Statut', 'Bon de livraison'];
            $data = ['menu' => $nav, 'header' => $headerDashboard, 'dashboard' => $bd->getDashboardGestionnaire()];
            return $this->render('gestionnaire_missions', $data);
        } else {
            echo 'Une erreur est survenue lors du chargement du tableau de bord';
        }
    }

    public function action_interlocuteurs(){
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['id'])){
            $bd = Model::getModel();
            $data=['title' => 'Interlocuteurs','person' => $bd->getInterlocuteurForGestionnaire()];
            $this->render("liste",$data);
        }
    }

    public function action_assigner_prestataire(){
        $bd = Model::getModel();
        if(isset($_POST['email'])){
            $bd->assignerPrestataire($_POST['email'],$_POST['mission']);
        }
        $this->action_dashboard();
    }

    public function action_assigner_commercial_interlocuteur(){
        $bd = Model::getModel();
        if(isset($_POST['email']) && isset($_POST['client'])){
            $bd->assignerCommercial($_POST['email'],$_POST['client']);
        }
        $this->action_dashboard();
    }

    public function action_assigner_commecial_mission(){
        $bd = Model::getModel();
        if(isset($_POST['email']) && isset($_POST['mission'])){
            $bd->assignerCommercial($_POST['email'], $_POST['mission']);
        }
        $this->action_interlocuteurs();
    }

    public function action_gestionnaire_prestataires(){
        $bd=Model::getModel();
        if (isset($_SESSION['id'])){
            $data=[
                "tableau"=>$bd->getPrestataireForGestionnaire()
            ];
            $this->render("gestionnaire_prestataires",$data);
        }
    }

    public function action_gestionnaire_supprimer_prestataire(){
        $bd = Model::getModel();
        if(isset($_POST['supprimer'])){
            $bd->removePrestataireForGestionnaire($_POST['supprimer']);
        }
        $this->render("gestionnarie_prestataires");
    }

    public function action_gestionnaire_supprimer_interlocuteur(){
        $bd = Model::getModel();
        if(isset($_POST['supprimer'])){
            $bd->removeInterlocuteurForGestionnaire($_POST['supprimer']);
        }
        $this->render("gestionnarie_clients");
    }

    public function action_gestionnaire_supprimer_commercial(){
        $bd = Model::getModel();
        if(isset($_POST['supprimer'])){
            $bd->removeCommercialForGestionnaire($_POST['supprimer']);
        }
        $this->render("gestionnarie_commerciaux");
    }

    public function action_gestionnaire_ajouter_interlocuteur(){
        $bd=Model::getModel();
        if(isset($_POST['composante']) && isset($_POST['client'])){
            $bd->addInterlocuteurForGestionnaire($_POST['composante'],$_POST['client']);
        }
        $this->render("gestionnaire_clients");
    }

    public function action_ajouter_prestataire(){
        $bd=Model::getModel();
        if(isset($_POST['mission']) && isset($_POST['email'])){
            $bd->addPrestataireForGestionnaire($_POST['mission'],$_POST['email']);
        }
        $this->render("gestionnaire_prestataire");
    }

    public function action_gestionnaire_ajouter_Commercial(){
        $bd=Model::getModel();
        if(isset($_POST['composante']) && isset($_POST['email'])){
            $bd->addCommercialForGestionnaire($_POST['composante'],$_POST['email']);
        }
        $this->render("gestionnaire_commerciaux");
    }

    public function action_gestionnaire_ajouter_Client(){
        $bd=Model::getModel();
        if(isset($_POST['client']) && isset($_POST['telephone']) && isset($_POST['composante']) && isset($_POST['addresse']) &&isset($_POST['email'])){
            $bd->addClientForGestionnaire($_POST['client'], $_POST['telephone'], $_POST['composante'], $_POST['addresse'],$_POST['email']);
        }
        $this->render("gestionnaire_clients");
    }
}
?>
