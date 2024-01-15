<?php

class Controller_administrateur extends Controller_gestionnaire
{
    public function action_dashboard()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!array_key_exists('role', $_SESSION)) {
            $_SESSION['role'] = 'administrateur';
        }
        if (isset($_SESSION['id'])) {
            $bd = Model::getModel();
            $buttonLink = '?controller=administrateur&action=ajout_mission_form';
            $headerDashboard = ['Société', 'Composante', 'Nom Mission', 'Préstataire assigné', 'Statut', 'Bon de livraison'];
            $data = ['menu' => $this->action_get_navbar(), 'buttonLink' => $buttonLink, 'header' => $headerDashboard, 'dashboard' => $bd->getDashboardAdministrateur()];
            return $this->render('gestionnaire_missions', $data);
        } else {
            echo 'Une erreur est survenue lors du chargement du tableau de bord';
        }
    }
    public function action_get_navbar()
    {
        return [['link' => '?controller=administrateur&action=clients', 'name' => 'Société'],
            ['link' => '?controller=administrateur&action=composantes', 'name' => 'Composantes'],
            ['link' => '?controller=administrateur&action=missions', 'name' => 'Missions'],
            ['link' => '?controller=administrateur&action=prestataires', 'name' => 'Prestataires'],
            ['link' => '?controller=administrateur&action=commerciaux', 'name' => 'Commerciaux'],
            ['link' => '?controller=administrateur&action=gestionnaires', 'name' => 'Gestionnaires']];
    }

    public function action_gestionnaires(){
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['id'])) {
            $bd = Model::getModel();
            $buttonLink = '?controller=administrateur&action=ajout_gestionnaire_form';
            $data = ['title' => 'Gestionnaires', 'buttonLink' => $buttonLink, "person" => $bd->getAllCommerciaux(), 'menu' => $this->action_get_navbar()];
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
            $buttonLink = '?controller=administrateur&action=ajout_client_form';
            $cardLink = '?controller=administrateur&action=infos_client';
            $data = ['title' => 'Société', 'buttonLink' => $buttonLink, 'cardLink' => $cardLink, 'person' => $bd->getAllClients(), 'menu' => $this->action_get_navbar()];
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
            $buttonLink = '?controller=administrateur&action=ajout_prestataire_form';
            $data = ['title' => 'Prestataires', "buttonLink" => $buttonLink, "person" => $bd->getAllPrestataires(), 'menu' => $this->action_get_navbar()];
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
            $buttonLink = '?controller=administrateur&action=ajout_commercial_form';
            $data = ['title' => 'Commerciaux', 'buttonLink' => $buttonLink, "person" => $bd->getAllCommerciaux(), 'menu' => $this->action_get_navbar()];
            $this->render("liste", $data);
        }
    }



}
