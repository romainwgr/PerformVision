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
        if (isset($_SESSION['id'])) {
            $bd = Model::getModel();
            $headerDashboard = ['Société', 'Composante', 'Nom Mission', 'Statut', 'Bon de livraison'];
            $data = ['header' => $headerDashboard, 'dashboard' => $bd->getDashboardPrestataire($_SESSION['id'])/*ses missions*/];
            return $this->render('prestataire_missions', $data);
        } else {
            echo 'Une erreur est survenue lors du chargement du tableau de bord';
        }
    }

    public function action_prestataire_creer_absences(){
        $bd=Model::getModel();
        if(isset($_POST['prenom']) && isset($_POST['nom']) && isset($_POST['email']) && isset($_POST['Date']) && isset($_POST['motif'])){
            $bd->addAbsenceForPrestataire($_POST['prenom'],$_POST['nom'],$_POST['email'],$_POST['Date'],$_POST['motif']);
        } else{
            $this->action_error("données incomplètes");
        }
    }


}
