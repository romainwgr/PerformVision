<?php

class Controller_interlocuteur extends Controller
{
    /**
     * @inheritDoc
     */
    public function action_default()
    {
        $this->action_accueil();
    }

    public function action_accueil()
    {
        sessionstart(); // Fonction dans Utils pour lancer la session si elle n'est pas lancée 
        if (isset($_SESSION['role'])) {
            unset($_SESSION['role']);
        }
        $_SESSION['role'] = 'gestionnaire';
        if (isset($_SESSION['id'])) {
            $bd = Model::getModel();
            $data = [
                'menu' => $this->action_get_navbar(),
                'bdlLink' => '?controller=gestionnaire&action=mission_bdl',
                'buttonLink' => '?controller=gestionnaire&action=ajout_mission_form',
                'header' => [
                    'Société',
                    'Composante',
                    'Nom Mission',
                    'Préstataire assigné',
                    'Bon de livraison'
                ],
                // 'dashboard' => $bd->getDashboardInterlocuteur($_SESSION['id'])
            ];
            $this->render('accueil', $data);
        }
        $this->render('accueil');
    }

    public function action_missions()
    {
        // Redirection vers l'action dashboard
        $this->action_dashboard();
    }
    /**
     * Affiche le tableau de bord de l'interlocuteur client en récupérant les informations grâce à son id
     * @return void
     */
    public function action_dashboard()
    {
        sessionstart();
        if (isset($_SESSION['id'])) {
            $bd = Model::getModel();
            $data = [
                'header' => [
                    'Nom projet/société',
                    'Date',
                    'Prestataire assigné',
                    'Bon de livraison'
                ],
                'menu' => $this->action_get_navbar(),
                'bdlLink' => '?controller=interlocuteur&action=mission_bdl',

                'dashboard' => $bd->getClientContactDashboardData()
            ];
            return $this->render('interlocuteur', $data);
        } else {
            // TODO Réaliser un render de l'erreur
            echo 'Une erreur est survenue lors du chargement du tableau de bord';
        }
    }

    /**
     * Action qui retourne les éléments du menu pour l'interlocuteur
     * @return array[]
     */
    public function action_get_navbar()
    {
        return [['link' => '?controller=interlocuteur&action=dashboard', 'name' => 'Mes prestataires']];
    }

    /**
     * Renvoie la vue qui montre les informations de l'utilisateur connecté
     * @return void
     */
    public function action_infos()
    {
        sessionstart();
        $data = [
            'role' => 'interlocuteur',
            'menu' => $this->action_get_navbar()
        ];
        $this->render('infos', $data);
    }

    public function action_maj_infos()
    {
        maj_infos_personne(); // fonction dans Utils
        $this->action_infos();
    }

    /**
     * Met à jour les informations de l'utilisateur connecté
     * @return void
     */
    public function action_mission_bdl()
    {
        $bd = Model::getModel();
        sessionstart();
        if (isset($_GET['id']) && isset($_GET['id-prestataire'])) {
            $data = [
                'title' => 'Bons de livraison',
                'menu' => $this->action_get_navbar(),
                'cardLink' => '?controller=interlocuteur&action=consulter_bdl',
                'person' => $bd->getBdlsOfPrestataireByIdMission(e($_GET['id']), e($_GET['id-prestataire']))
            ];
            $this->render('liste', $data);
        }
    }

    /**
     * Vérifie qu'il existe dans l'url l'id qui fait référence au bon de livraison et renvoie la vue qui permet de consulter le bon de livraison
     * @return void
     */
    public function action_consulter_bdl()
    {
        $bd = Model::getModel();
        sessionstart();
        if (isset($_GET['id'])) {
            $typeBdl = $bd->getBdlTypeAndMonth($_GET['id']);
            if ($typeBdl['type_bdl'] == 'Heure') {
                $activites = $bd->getAllNbHeureActivite($_GET['id']);
            }
            if ($typeBdl['type_bdl'] == 'Demi-journée') {
                $activites = $bd->getAllDemiJourActivite($_GET['id']);
            }
            if ($typeBdl['type_bdl'] == 'Journée') {
                $activites = $bd->getAllJourActivite($_GET['id']);
            }

            $data = [
                'bdl' => $typeBdl,
                'menu' => $this->action_get_navbar(),
                'activites' => $activites
            ];
            $this->render("consulte_bdl", $data);
        } else {
            // TODO Réaliser un render de l'erreur
            echo 'Une erreur est survenue lors du chargement de ce bon de livraison';
        }
    }

    /**
     * Met à jour la colonne valide de la table BON_DE_LIVRAISON pour indiquer que le bon de livraison est validé
     * @return void
     */
    public function action_valider_bdl()
    {
        $bd = Model::getModel();
        sessionstart();
        if (isset($_GET['id']) && isset($_GET['valide'])) {
            $bd->setEstValideBdl(e($_GET['id']), $_SESSION['id'], e($_GET['valide']));
            $this->action_consulter_bdl();
        } else {
            // TODO Réaliser un render de l'erreur
            echo 'Une erreur est survenue lors de la validation de ce bon de livraison';
        }
    }


    /**
     * Envoie un email au(x) commercial/commerciaux assigné(s) à la mission de l'interlocuteur client
     * @return void
     */
    public function action_envoyer_email()
    {
        // DONE title mit dans $data pour envoyer sur view_message
        session_start();
        $bd = Model::getModel();
        if (isset($_SESSION['id']) && $bd->getComponentCommercialsEmails($_SESSION['id'])) {
            $destinatairesEmails = implode(', ', array_column($bd->getComponentCommercialsEmails($_SESSION['id']), 'email'));
            $emetteur = $_SESSION['email'];
            $objet = $_POST['objet'];
            $message = e($_POST['message']);

            //header pour l'envoie du mail
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= 'From: <' . $emetteur . '>' . "\r\n";

            if (mail($destinatairesEmails, $objet, $message, $headers)) {
                $data = [
                    'title' => 'Email',
                    'message' => 'Le mail a été envoyé !'
                ];
                $this->render('message', $data);
            } else {
                $data = [
                    'title' => 'Email',
                    'message' => "Une erreur est survenue lors de l'envoie du mail !"
                ];
                $this->render('message', $data);
            }
        }
    }


    // TODO Fonction qui télécharge un fichier ? A tester
    /**
     * Lecture du fichier correspondant au bon de livraison pour l'envoyer au client
     * @return void
     */
    public function telecharger_bdl()
    {
        // FIXME Gérer l'erreur cheminbdl pas déclaré
        $cheminBdl = '../BDL/';
        $cheminFichier = $cheminBdl . e($_GET['id']);

        // Vérifiez si le fichier existe
        if (file_exists($cheminFichier)) {
            // Définir les en-têtes HTTP pour le téléchargement
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($cheminFichier) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($cheminFichier));

            // Lire et renvoyer le contenu du fichier
            readfile($cheminFichier);
            exit;
        } else {
            // Le fichier n'existe pas
            // TODO Réaliser un render de l'erreur
            echo "Le fichier n'existe pas.";
        }
    }
}
