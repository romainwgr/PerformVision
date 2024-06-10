<?php

/**
 * @brief Classe du commercial contenant toutes les fonctionnalités du commercial
 * 
 * Pas encore fonctionnelle
 * 
 */
class Controller_commercial extends Controller
{

    /**
     * Action par défaut qui appelle l'action accueil
     */
    public function action_default()
    {
        $this->action_prestataires();
    }
    public function action_prestataires()
    {

        if (isset($_SESSION['id'])) {
            $bd = Model::getModel();
            $data = [
                'title' => 'Prestataires',
                'rechercheLink' => '?controller=commercial&action=rechercher&role=prestataire',
                'cardLink' => '?controller=commercial&action=infos_client',
                'person' => $bd->getPrestataireForCommercial($_SESSION['id']),
                'menu' => $this->action_get_navbar()
            ];
            $this->render("prestataire", $data, "commercial");
        }
    }
    /**
     * Renvoie la liste de toutes les clients avec leurs différentes composantes et les prestataires assigné avec la possibilité d'ajouter des prestataires
     * La vérification de l'identifiant de Session permet de s'assurer que la personne est connectée en faisant partie de la base de données
     * @return void
     */
    public function action_composantes()
    {

        if (isset($_SESSION['id'])) {
            $bd = Model::getModel();

            // Récupérer tous les clients
            $clients = $bd->getClientsForCommercial($_SESSION['id']);

            // Organiser les données hiérarchiquement
            $clientsData = [];
            foreach ($clients as $client) {
                $clientId = $client['id_client'];
                $composantes = $bd->getComposantesSociete($clientId); // GetComposanteByClientId

                // Vérifier que $composantes est un tableau
                if (!is_array($composantes)) {
                    $composantes = [];
                }

                foreach ($composantes as &$composante) {
                    $composanteId = $composante['id_composante'];
                    $interlocuteurs = $bd->getInterlocuteursComposante($composanteId); // GetPrestataireByIdComposante

                    // Vérifier que $prestataires est un tableau
                    if (!is_array($interlocuteurs)) {
                        $interlocuteurs = [];
                    }

                    $composante['interlocuteurs'] = $interlocuteurs;
                }

                // Assurer que 'composantes' est toujours un tableau
                $client['composantes'] = $composantes;
                $clientsData[] = $client;
            }

            // Préparer les données pour la vue
            $data = [
                'title' => 'Composantes',
                'person' => $clientsData,
                // TODO a faire 
                'buttonLink' => '?controller=commercial&action=ajout_interlocuteur_form',
                'rechercheLink' => '?controller=commercial&action=rechercher&role=client&composante=t',
                'cardLink' => '?controller=commercial&action=infos_composante',
                'menu' => $this->action_get_navbar()
            ];

            // Rendre la vue avec les données
            $this->render("composante", $data, 'commercial');
        }
    }
    /**
     * Renvoie la liste de tous les clients
     * La vérification de l'identifiant de Session permet de s'assurer que la personne est connectée en faisant partie de la base de données
     * @return void
     */
    public function action_clients()
    {

        if (isset($_SESSION['id'])) {
            $bd = Model::getModel();
            $data = [
                'title' => 'Société',
                'buttonLink' => '?controller=commercial&action=ajout_client_form',
                'rechercheLink' => '?controller=commercial&action=rechercher&role=client&composante=f',
                'cardLink' => '?controller=commercial&action=infos_client',
                'person' => $bd->getClientsForCommercial($_SESSION['id']),
                'menu' => $this->action_get_navbar()
            ];
            $this->render("client", $data, 'commercial');
        }
    }

    /**
     * Action qui retourne les éléments du menu pour le commercial
     * @return array[]
     */
    public function action_get_navbar()
    {
        return [
            ['link' => '?controller=commercial&action=clients', 'name' => 'Clients'],
            ['link' => '?controller=commercial&action=composantes', 'name' => 'Composantes'],
            ['link' => '?controller=commercial&action=prestataires', 'name' => 'Prestataires'],
        ];
    }
    /**
     * Met à jour les informations de l'utilisateur connecté
     * @return void
     */
    public function action_maj_infos()
    {
        maj_infos_personne(); // fonction dans Utils
        $this->action_infos();
    }

    /**
     * Met à jour les informations du client
     * @return void
     */
    public function action_maj_infos_client()
    {
        maj_infos_client(); // fonction dans Utils
        $this->action_infos_client();
    }

    /**
     * Met à jour les informations de la personne
     * @return void
     */
    public function action_maj_infos_personne()
    {
        maj_infos_personne(); // fonction dans Utils
        $this->action_infos_personne();
    }
    //Ajouter interlocuteur

    /**
     * Renvoie la vue du formulaire pour l'ajout d'un interlocuteur
     * @return void
     */
    public function action_ajout_interlocuteur_form()
    {

        $data = [
            'menu' => $this->action_get_navbar()
        ];
        $this->render('ajout_interlocuteur', $data, 'Ajouts');
    }

    /**
     * Renvoie la vue qui montre les informations de l'utilisateur connecté
     * @return void
     */
    public function action_infos()
    {

        $this->render('infos', ['menu' => $this->action_get_navbar()]);
    }

    /**
     * Vérifie qu'il existe dans l'url l'id qui fait référence au client et renvoie la vue qui affiche les informations sur le client
     * @return void
     */
    public function action_infos_client()
    {

        if (isset($_GET['id'])) {
            $bd = Model::getModel();
            $data = [
                'infos' => $bd->getInfosSociete(e($_GET['id'])),
                'composantes' => $bd->getComposantesSociete(e($_GET['id'])),
                'interlocuteurs' => $bd->getInterlocuteursSociete(e($_GET['id'])),
                'menu' => $this->action_get_navbar()
            ];
            $this->render('infos_client', $data);
        }
    }

    /**
     * Vérifie qu'il existe un id qui fait référence à une personne de la base de données et renvoie la vue qui affiche les données
     * @return void
     */
    public function action_infos_personne()
    {

        if (isset($_GET['id'])) {
            $bd = Model::getModel();
            $data = [
                'person' => $bd->getInfosPersonne(e($_GET['id'])),
                'menu' => $this->action_get_navbar()
            ];
            $this->render("infos_personne", $data);
        }
    }

}
