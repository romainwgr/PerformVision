<?php

class Controllers_gestionnaire extends Controller
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
        if (isset($_SESSION['id'])) {
            $bd = Model::getModel();
            $headerDashboard = ['Société', 'Composante','Nom Mission' ,'Préstataire assigné', 'Statut', 'Bon de livraison'];
            $data = ['header' => $headerDashboard, 'dashboard' => $bd->getdashboardGestionnaire(), ""];
            return $this->render('gestionnaire_missions', $data);
        } else {
            echo 'Une erreur est survenue lors du chargement du tableau de bord';
        }
    }
    public function action_gestionnaire_interlocuteurs(){
        if (isset($_SESSION['id'])){
            $bd = Model::getModel();
            $data=[$bd->getInterlocuteurForGestionnaire()];
            $this->render("gestionnaire_interlocuteurs",$data);
        }
    }
    public function action_assigner_prestataire(){
        $bd = Model::getModel();
        if(isset($_POST['email'])){
            $bd->assignerPrestataire($_POST['email'],$_POST['mission']);
        }
        $this->action_dashboard();
    }
    public function action_assigner_commercialmission(){
        $bd = Model::getModel();
        if(isset($_POST['email'])){
            $bd->assignerCommercial($_POST['email'],);
        }
        $this->action_dashboard();
    }

    public function action_assigner_commecialinterlocuteur(){
        $bd = Model::getModel();
        if(isset($_POST['email'])){
            $bd->assignerCommercial($_POST['email'],;
        }
        $this->action_interlocuteurs();
    }

    public function action_gestionnaire_prestataires(){
        $bd=Model::getModel();
        if ($_SESSION['id']){
            $data=[
                "tableau"=>$bd->getPrestataireForGestionnaire()
            ];
            $this->render("gestionnaire_prestataires",$data);
        }

    }
    public function action_gestionnaire_supprimer_prestataire(){
        $bd = Model::getModel();
        if($_POST['supprimer']){
            $bd->removePrestataireForGestionnaire($_POST['supprimer']);
        }
        $this->render("gestionnarie_prestataires");
    }
    public function action_gestionnaire_supprimer_interlocuteur(){
        $bd = Model::getModel();
        if($_POST['supprimer']){
            $bd->removeInterlocuteurForGestionnaire($_POST['supprimer']);
        }
        $this->render("gestionnarie_clients");
    }

    public function action_gestionnaire_supprimer_commercial(){
        $bd = Model::getModel();
        if($_POST['supprimer']){
            $bd->removeCommercialForGestionnaire($_POST['supprimer']);
        }
        $this->render("gestionnarie_commercial");
    }

    public function action_ajouter_prestataire(){
        $bd=Model::getModel();
        if($_POST['add']){
            $bd->addPrestataireForGestionnaire($_POST['']);
        }
    }

    public function action_ajouter_prestataire(){
        $bd=Model::getModel();
        if($_POST['']);
    }

}
?>

