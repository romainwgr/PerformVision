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
            $data = ['header' => $headerDashboard, 'dashboard' => $bd->getDashboardPrestataire()];
            return $this->render('prestataire_missions', $data);
        } else {
            echo 'Une erreur est survenue lors du chargement du tableau de bord';
        }
    }


}
