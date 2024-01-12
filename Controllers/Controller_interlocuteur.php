<?php

class Controller_interlocuteur extends Controller
{
    /**
     * @inheritDoc
     */
    public function action_default()
    {
        $this->action_dashboard();
    }

    /**
     * Affiche le tableau de bord de l'interlocuteur client en récupérant les informations grâce à son id
     * @return void
     */
    public function action_dashboard()
    {
        session_start();
        if (isset($_SESSION['id'])) {
            $bd = Model::getModel();
            $headerDashboard = ['Nom projet/société', 'Date', 'Préstataire assigné', 'Statut', 'Bon de livraison'];
            $data = ['header' => $headerDashboard, 'dashboard' => $bd->getClientContactDashboardData()];
            return $this->render('interlocuteur', $data);
        } else {
            echo 'Une erreur est survenue lors du chargement du tableau de bord';
        }
    }

    /**
     * Envoie un email au(x) commercial/commerciaux assigné(s) à la mission de l'interlocuteur client
     * @return void
     */
    public function action_envoyer_email()
    {
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
                $this->render('message', [$title => 'Email', $message => 'Le mail a été envoyé !']);
            } else {
                $this->render('message', [$title => 'Email', $message => "Une erreur est survenue lors de l'envoie du mail !"]);
            }
        }
    }

    /**
     * Lecture du fichier correspondant au bon de livraison pour l'envoyer au client
     * @return void
     */
    public function telecharger_bdl()
    {
        $cheminFichier = $cheminBdl . $_GET['id'];

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
            echo "Le fichier n'existe pas.";
        }
    }

    /**
     * Renvoie à la vue du bon de livraison
     * @return void
     */
    public function action_bdl()
    {
        if (isset($_GET['idBdl'])) {
            $bd = Model::getModel();
            $data = ['bdl' => $bd->getBdlInfos($_GET['idBdl'])];
            $this->render('bdl', $data);
        }
    }
}