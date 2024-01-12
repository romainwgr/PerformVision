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
        session_start();
        if (isset($_SESSION['id'])) {
            $bd = Model::getModel();
            $headerDashboard = ['Société', 'Composante','Nom Mission' ,'Préstataire assigné', 'Statut', 'Bon de livraison'];
            $data = ['header' => $headerDashboard, 'dashboard' => $bd->getDashboardGestionnaire()];
            return $this->render('gestionnaire_missions', $data);
        } else {
            echo 'Une erreur est survenue lors du chargement du tableau de bord';
        }
    }

    public function action_interlocuteurs(){
        if (isset($_SESSION['id'])){
            $bd = Model::getModel();
            $data=['title' => 'Interlocuteurs','liste'=>$bd->getInterlocuteurForGestionnaire()];
            $this->render("liste",$data);
        }
    }

    public function action_ajouter_prestataire($supplier){
        $bd = Model::getModel();
        $bd->add_supllier($supplier);
        $this->render("ajout_prestataire", $data);
    }
}
?>
