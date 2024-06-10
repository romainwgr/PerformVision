<?php
header('Content-Type: text/html; charset=utf-8');
// sessionstart();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



/**
 * @brief Classe du prestataire contenant toutes les fonctionnalités du prestataire
 * 
 */

class Controller_prestataire extends Controller
{
    /**
     * @inheritDoc
     */
    public function action_default()
    {
        $this->action_liste_bdl();
    }

    public function action_accueil()
    {
        // sessionstart(); // Fonction dans Utils pour lancer la session si elle n'est pas lancée 
        if (isset($_SESSION['role'])) {
            unset($_SESSION['role']);
        }
        $_SESSION['role'] = 'prestataire';

        if (isset($_SESSION['id'])) {
            $bd = Model::getModel();
            $data = [
                'menu' => $this->action_get_navbar(),
                'bdlLink' => '?controller=prestataire&action=mission_bdl',
                'header' => [
                    'Société',
                    'Composante',
                    'Bon de livraison'
                ],
                'dashboard' => $bd->getDashboardPrestataire($_SESSION['id'])
            ];
            return $this->render('prestataire_missions', $data);
        } else {
            // TODO Réaliser un render de l'erreur
            echo 'Une erreur est survenue lors du chargement du tableau de bord';
        }
    }


    /**
     * Renvoie le tableau de bord du prestataire avec les variables adéquates
     * @return void
     */
    

    /**
     * Action qui retourne les éléments du menu pour le prestataire
     * @return array[]
     */
    public function action_get_navbar()
    {
        return [

            // ['link' => '?controller=prestataire&action=dashboard', 'name' => 'Missions'],
            ['link' => '?controller=prestataire&action=liste_bdl', 'name' => 'Bons de livraison'],
            ['link' => '?controller=prestataire&action=absence', 'name' => 'Declaration absence']
        ];
    }

    /**
     * Renvoie la vue qui montre les informations de l'utilisateur connecté
     * @return void
     */
    public function action_infos()
    {
        // sessionstart();
        $this->render('infos', ['menu' => $this->action_get_navbar()]);
    }

    // TEST


    /**
     * Renvoie la vue qui lui permet de remplir son bon de livraion avec le bon type
     * @return void
     */
    public function action_afficher_bdl()
    {
        $bd = Model::getModel();
        sessionstart();
        // Vérifiez si l'ID du BDL est passé en GET
        if (isset($_GET['id'])) {
            // Stockez l'ID du BDL dans la session
            $_SESSION['id_bdl'] = $_GET['id'];
        }
    
        // Récupérez l'ID du BDL et du prestataire depuis la session
        $id_bdl = isset($_SESSION['id_bdl']) ? $_SESSION['id_bdl'] : null;
        $id_prestataire = isset($_SESSION['id']) ? $_SESSION['id'] : null;
    
        if ($id_bdl !== null && $id_prestataire !== null) {
            // Récupérez les détails du BDL en utilisant l'ID du prestataire et l'ID du BDL
            $bdl = $bd->getBdlPrestataireBybdlId($id_bdl);
            $interlocuteur = $bd->getInterlocuteurByIdBDL($id_bdl);
            $prestataire = $bd->getPrestataireByIdBDL($id_bdl);
            $gestionnaire = $bd->getGestionnaireById($id_bdl);
    
            if ($bdl) {
                // Inclure la bibliothèque FPDF
                require_once('libraries/fpdf/fpdf.php');
    
                // Créer un nouvel objet FPDF
                $pdf = new FPDF();
                $pdf->AddPage();
                $pdf->SetMargins(20, 20, 20);
    
                // Ajouter les polices UTF-8 compatibles
                $pdf->AddFont('FreeSerif', '', 'FreeSerif.php');
                $pdf->AddFont('FreeSerif', 'B', 'FreeSerifBold.php');
                $pdf->AddFont('FreeSerif', 'I', 'FreeSerifItalic.php');
                $pdf->SetFont('FreeSerif', '', 12);
    
                // Ajouter un logo
                $pdf->Image('Content/images/logo3.png', 170, 10, 20);
    
                // Titre du document
                $pdf->SetFont('FreeSerif', 'B', 24);
                $pdf->SetTextColor(0, 153, 204); // Couleur bleue ciel
                $pdf->Cell(0, 20, iconv('UTF-8', 'ISO-8859-1', 'Bon de livraison'), 0, 1, 'L');
                $pdf->Ln(5);
                $pdf->SetDrawColor(0, 153, 204);
                $pdf->SetLineWidth(1);
                $pdf->Line(20, 35, 190, 35);
                $pdf->Ln(10);
    
                // Détails de l'entreprise
                $pdf->SetFont('FreeSerif', 'B', 12);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1', 'SAS Perform Vision'), 0, 1, 'L');
                $pdf->SetFont('FreeSerif', '', 12);
                $pdf->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1', 'Président: Slim ELLOUZE'), 0, 1, 'L');
                $pdf->Ln(10);
    
                // Détails du bon de livraison
                $pdf->SetFont('FreeSerif', 'B', 12);
                $pdf->Cell(95, 10, iconv('UTF-8', 'ISO-8859-1', 'Bon de livraison N°: ') . htmlspecialchars($bdl['id_bdl']), 0, 0);
                $pdf->Cell(95, 10, iconv('UTF-8', 'ISO-8859-1', 'Composante: ') . iconv('UTF-8', 'ISO-8859-1', htmlspecialchars($bdl['nom_composante'])), 0, 1);
                $pdf->SetFont('FreeSerif', '', 12);
                $pdf->Cell(95, 10, iconv('UTF-8', 'ISO-8859-1', 'Date : ') . date('d/m/Y'), 0, 0);
                $pdf->Cell(95, 10, iconv('UTF-8', 'ISO-8859-1', htmlspecialchars($bdl['nom_client'])), 0, 1);
                $pdf->Cell(95, 10, iconv('UTF-8', 'ISO-8859-1', 'Lieu : ') . iconv('UTF-8', 'ISO-8859-1', htmlspecialchars($bdl['adresse_livraison'])), 0, 0);
                $pdf->Cell(95, 10, iconv('UTF-8', 'ISO-8859-1', htmlspecialchars($bdl['adresse_livraison'])), 0, 1);
                $pdf->Cell(95, 10, iconv('UTF-8', 'ISO-8859-1', 'Numéro de commande : ') . htmlspecialchars($bdl['id_bdl']), 0, 0);
                $pdf->Cell(95, 10, iconv('UTF-8', 'ISO-8859-1', 'Téléphone : ') . htmlspecialchars($bdl['telephone_client']), 0, 1);
                $pdf->Ln(10);
    
                // Informations sur l'interlocuteur à gauche et le prestataire à droite
                $pdf->SetFont('FreeSerif', 'B', 12);
                $pdf->Cell(95, 10, iconv('UTF-8', 'ISO-8859-1', 'Interlocuteur'), 0, 0, 'L');
                $pdf->Cell(95, 10, iconv('UTF-8', 'ISO-8859-1', 'Prestataire'), 0, 1, 'L');
                $pdf->SetFont('FreeSerif', '', 12);
                $pdf->Cell(95, 10, iconv('UTF-8', 'ISO-8859-1', 'Nom: ') . htmlspecialchars($interlocuteur['prenom'] . ' ' . $interlocuteur['nom']), 0, 0, 'L');
                $pdf->Cell(95, 10, iconv('UTF-8', 'ISO-8859-1', 'Nom: ') . htmlspecialchars($prestataire['prenom'] . ' ' . $prestataire['nom']), 0, 1, 'L');
                $pdf->Cell(95, 10, iconv('UTF-8', 'ISO-8859-1', 'Téléphone: ') . htmlspecialchars($interlocuteur['telephone']), 0, 0, 'L');
                $pdf->Cell(95, 10, iconv('UTF-8', 'ISO-8859-1', 'Téléphone: ') . htmlspecialchars($prestataire['telephone']), 0, 1, 'L');
                $pdf->Cell(95, 10, iconv('UTF-8', 'ISO-8859-1', 'Email: ') . htmlspecialchars($interlocuteur['mail']), 0, 0, 'L');
                $pdf->Cell(95, 10, iconv('UTF-8', 'ISO-8859-1', 'Email: ') . htmlspecialchars($prestataire['mail']), 0, 1, 'L');
                $pdf->Ln(10);
    
                // Informations sur le gestionnaire en dessous de l'interlocuteur
                $pdf->SetFont('FreeSerif', 'B', 12);
                $pdf->Cell(95, 10, iconv('UTF-8', 'ISO-8859-1', 'Gestionnaire'), 0, 1, 'L');
                $pdf->SetFont('FreeSerif', '', 12);
                $pdf->Cell(95, 10, iconv('UTF-8', 'ISO-8859-1', 'Nom: ') . htmlspecialchars($gestionnaire['prenom'] . ' ' . $gestionnaire['nom']), 0, 1, 'L');
                $pdf->Cell(95, 10, iconv('UTF-8', 'ISO-8859-1', 'Téléphone: ') . htmlspecialchars($gestionnaire['telephone']), 0, 1, 'L');
                $pdf->Cell(95, 10, iconv('UTF-8', 'ISO-8859-1', 'Email: ') . htmlspecialchars($gestionnaire['mail']), 0, 1, 'L');
                $pdf->Ln(10);
    
                
    
                
                // Récupérer les heures du BDL
                $hours = $bd->getHoursByIdBDL($id_bdl);

                // Afficher les heures dans un tableau dans le PDF
                $pdf->SetFont('Arial', 'B', 12);
                $pdf->SetTextColor(0, 51, 102);
                $pdf->Cell(0, 10, 'Heures travaillées :', 0, 1, 'L');
                $pdf->Ln(5);

                $pdf->SetFont('Arial', '', 12);
                $pdf->SetTextColor(0);
                $pdf->SetFillColor(224, 235, 255); // Couleur de fond bleue claire
                $pdf->Cell(30, 10, 'Jour', 1, 0, 'C', true);
                $pdf->Cell(40, 10, 'Nombre d\'heures', 1, 1, 'C', true);

                foreach ($hours as $hour) {
                    $pdf->Cell(30, 10, $hour['jour'], 1, 0, 'C');
                    $pdf->Cell(40, 10, $hour['hours_worked'], 1, 1, 'C');
                }
                $pdf->Ln(10);
            
                $pdf->Cell(0, 10, 'Total des heures : ' . htmlspecialchars($bdl['heures']), 0, 1, 'L');
                $pdf->Cell(0, 10, 'Commentaire : ' . iconv('UTF-8', 'ISO-8859-1', htmlspecialchars($bdl['commentaire'])), 0, 1, 'L');
    
                // Ajouter un espacement avant les signatures
                $pdf->Ln(20);
                // Vérifiez si le prestataire a signé
                $signature_prestataire = $bdl['signature_prestataire'] ? htmlspecialchars($bdl['nom_client']) : '__________________';
    
                // Signatures
                $pdf->Cell(95, 10, iconv('UTF-8', 'ISO-8859-1', 'Signature du client:  ') . $signature_prestataire, 0, 0);
                $pdf->Cell(95, 10, iconv('UTF-8', 'ISO-8859-1', 'Signature du fournisseur: __________________'), 0, 1);
                $pdf->Ln(20);
    
                // Sauvegarder le PDF dans une variable
                $pdf_content = $pdf->Output('', 'S');
                // Retourne le contenu du PDF en tant que chaîne
    
                // Passer les données des BDLs et le contenu du PDF à la vue
                $data = [
                    'menu' => $this->action_get_navbar(),
                    'title' => 'Affichage des BDLs',
                    'bdl' => $bdl, // Passer les données du BDL à la vue
                    'pdf_content' => $pdf_content // Passer le contenu du PDF à la vue
                ];
    
                // Rendre la vue avec les données
                $this->render('afficher_bdl', $data);
            } else {
                echo "<script>alert('Aucun BDL trouvé pour cet ID.'); window.location.href = '?controller=prestataire&action=liste_bdl';</script>";
                exit;
            }
        } else {
            echo "ID BDL ou ID Prestataire non défini.";
        }
    }
    


    /**
     * Vérifie d'avoir les informations nécessaire pour renvoyer la vue liste avec les bonnes variables pour afficher la liste des bons de livraisons du prestataire en fonction de la mission
     * @return void
     */
    // public function action_mission_bdl()
    // {
    //     $bd = Model::getModel();
    //     // sessionstart();
    //     if (isset($_GET['id'])) {
    //         $data = [
    //             'title' => 'Bons de livraison',
    //             'buttonLink' => '?controller=prestataire&action=ajout_bdl_form',
    //             'cardLink' => '?controller=prestataire&action=afficher_bdl',
    //             'menu' => $this->action_get_navbar(),
    //             'person' => $bd->getBdlsOfPrestataireByIdMission($_GET['id'], $_SESSION['id'])
    //         ];
    //         $this->render('liste', $data);
    //     }
    // }

    

   
    public function action_validerbdl()
    {
        $bd = Model::getModel();
        $id_bdl = $_SESSION['id_bdl'];

        if (isset($id_bdl)) {
            $result = $bd->setSignTruePrestataireId($id_bdl);
            if ($result) {
                if (isset($_SESSION['id'])) {
                    $person = $bd->getAllBdlPrestataire($_SESSION['id']); // Récupérer les données nécessaires

                    // Définir les données et l'indicateur de redirection dans la session
                    $_SESSION['redirect'] = true;
                    // $_SESSION['data'] = [
                    //     'title' => 'Mes Bons de livraison',
                    //     'buttonLink' => '?controller=prestataire&action=ajout_bdl_form',
                    //     'cardLink' => '?controller=prestataire&action=afficher_bdl',
                    //     'menu' => $this->action_get_navbar(),
                    //     'rechercheLink' => '',
                    //     'person' => $person
                    // ];
                    header('Location: ?controller=prestataire&action=liste_bdl');
                    exit();
                } else {
                    $this->render('form_bdl', ['error' => 'Une erreur est survenue lors de la validation du bon de livraison.']);
                }
            } else {
                $this->render('form_bdl', ['error' => 'ID de bon de livraison non valide.']);
            }
        } else {
            $this->render('form_bdl', ['error' => 'ID de bon de livraison non défini.']);
        }
    }


    public function action_liste_bdl()
    {
        // Vérifier l'indicateur de redirection et les données dans la session
        $redirect = isset($_SESSION['redirect']) ? $_SESSION['redirect'] : false;
        // $data = isset($_SESSION['data']) ? $_SESSION['data'] : [];

        unset($_SESSION['redirect']); // Supprimer l'indicateur après l'avoir récupéré
        // unset($_SESSION['data']); // Supprimer les données après les avoir récupérées

        $bd = Model::getModel();

        if (isset($_SESSION['id'])) {
            if (empty($data)) {
                $person = $bd->getAllBdlPrestataire($_SESSION['id']);

                $data = [
                    'title' => 'Mes Bons de livraison',
                    'buttonLink' => '?controller=prestataire&action=ajout_bdl_form',
                    'cardLink' => '?controller=prestataire&action=afficher_bdl',
                    'menu' => $this->action_get_navbar(),
                    'rechercheLink' => '',
                    'person' => $person,
                    'redirect' => $redirect // Ajouter l'indicateur de redirection aux données
                ];
            } else {
                // Ajouter l'indicateur de redirection aux données récupérées de la session
                $data['redirect'] = $redirect;
            }
            $this->render("liste", $data);
        } else {
            $this->render('form_bdl', ['error' => 'Utilisateur non authentifié.']);
        }
    }

    
    public function action_afficherFormulaire()
    {
        $bd = Model::getModel();
       
        // Vérifiez si l'ID du BDL est passé en GET
        if (isset($_GET['id_bdl'])) {
            // Récupérez l'ID du BDL
            $id_bdl = $_GET['id_bdl'];
            $_SESSION["id_bdl"] = $id_bdl;

            // Utilisez l'ID du BDL pour récupérer les informations de la base de données
            $bdl_info = $bd->getBdlPrestataireBybdlId($id_bdl); // Remplacez cette fonction par celle qui récupère les informations du BDL

            // Vérifiez si des informations ont été récupérées
            if ($bdl_info) {
                // Si oui, passez les informations à la vue
                $data = [
                    'client' => $bdl_info['nom_client'],
                    'composante' => $bdl_info['nom_composante'],
                    'mois' => $bdl_info['mois'],
                    'menu' => $this->action_get_navbar(),

                ];

                // Rendez la vue avec les données
                $this->render('form_bdl', $data);
            } else {
                // Si aucune information n'a été trouvée pour l'ID du BDL, affichez un message d'erreur ou redirigez l'utilisateur
                echo "Aucune information trouvée pour l'ID du BDL.";
            }
        } else {
            // Si l'ID du BDL n'est pas passé en GET, affichez un message d'erreur ou redirigez l'utilisateur
            echo "ID du BDL non spécifié.";
        }
    }




    public function action_addBdl()
    {
        $bd = Model::getModel();
        $id_bdl = $_SESSION["id_bdl"]; // Utilisez $_POST['key'] au lieu de $_POST('key')
        $jour = $_POST['nombre_jour'];
        $heures = $_POST['nombre_heures'];


        $resultat = $bd->insertDailyHours($id_bdl, $jour, $heures); // Corrigez l'appel de méthode
        $nbHours= $bd->getTotalHoursByIdBDL($id_bdl);
        $bd->updateHoursByIdBDL($id_bdl, $nbHours);
        if ($resultat == true) {
            $message = "l'Ajout a été effectuer avec succès";
        } else {
            $message = "Une erreur est survenue lors de l'ajout";
        }
        // Rétablir les données pour remplir à nouveau le formulaire
        $client = $_POST['client'];
        $composante = $_POST['composante'];
        $mois = $_POST['mois'];

        $this->render('form_bdl', ['message' => $message, 'client' => $client, 'composante' => $composante, 'mois' => $mois, 'menu' => $this->action_get_navbar(),]);


    }

    public function action_addHalfDay()
    {
        $bd = Model::getModel();
        $id_bdl = $_SESSION["id_bdl"];
        $jour = $_POST['nombre_jour'];
        $demi_journees = ($_POST['nombre_demi_journees'] * 4);

        $resultat = $bd->insertDailyHours($id_bdl, $jour, $demi_journees);
        $nbHours= $bd->getTotalHoursByIdBDL($id_bdl);
        $bd->updateHoursByIdBDL($id_bdl, $nbHours);
        $message = $resultat ? 'L\'ajout de la demi-journée a été effectué avec succès.' : 'Erreur lors de l\'ajout de la demi-journée.';
        // Rétablir les données pour remplir à nouveau le formulaire
        $client = $_POST['client'];
        $composante = $_POST['composante'];
        $mois = $_POST['mois'];

        $this->render('form_bdl', ['message' => $message, 'client' => $client, 'composante' => $composante, 'mois' => $mois, 'menu' => $this->action_get_navbar(),]);

    }

    public function action_addHourWithoutDay()
    {

        $bd = Model::getModel();
        $id_bdl = $_SESSION["id_bdl"];
        $heures_sans_jour = $_POST['nombre_heures_sans_jour'];

        $resultat = $bd->insertDailyHours($id_bdl, 0, $heures_sans_jour);
        $nbHours= $bd->getTotalHoursByIdBDL($id_bdl);
        $bd->updateHoursByIdBDL($id_bdl, $nbHours);
        $message = $resultat ? 'L\'ajout des heures sans jour a été effectué avec succès.' : 'Erreur lors de l\'ajout des heures sans jour.';
        // Rétablir les données pour remplir à nouveau le formulaire
        $client = $_POST['client'];
        $composante = $_POST['composante'];
        $mois = $_POST['mois'];


        $this->render('form_bdl', ['message' => $message, 'client' => $client, 'composante' => $composante, 'mois' => $mois, 'menu' => $this->action_get_navbar(),]);

    }

    /**
     * Ajoute dans la base de données la date à laquelle le prestataire est absent
     * @return void
     */
    public function action_absence()
    {
        $redirect = isset($_SESSION['redirect']) ? $_SESSION['redirect'] : false;
        unset($_SESSION['redirect']); // Supprimer l'indicateur après l'avoir récupéré

        // Si l'indicateur de redirection est vrai, rediriger vers une autre page
        if ($redirect) {
            header("Location: index.php?controller=prestataire&action=absence");
            exit(); // Assurez-vous d'arrêter l'exécution du script après la redirection
        }

        $bd = Model::getModel();
        $message = isset($_SESSION['message']) ? $_SESSION['message'] : null;
        unset($_SESSION['message']);
        // Récupère les absences pour l'utilisateur
        $id_personne = $_SESSION['id'];
        $absences = $bd->getAbsencesByPersonId($id_personne);
        $data = [
            'menu' => $this->action_get_navbar(),
            'message' => $message,
            'absences' => $absences,
            'rechercheLink' => '',
            'redirect' => $redirect // Ajouter l'indicateur de redirection aux données
        ];
        $this->render('absences', $data);
    }

    public function action_creer_absence()
    {
        $bd = Model::getModel();

        if (isset($_SESSION['id']) && isset($_POST['date']) && isset($_POST['motif'])) {
            $_SESSION['redirect'] = true;
            $id_personne = $_SESSION['id'];
            $date_absence = $_POST['date'];
            $motif = $_POST['motif'];

            $resultat = $bd->addAbsenceForPrestataire($id_personne, $date_absence, $motif);
            if ($resultat == true) {
                $_SESSION['message'] = "L'absence a été déclarée avec succès.";
            } else {
                $_SESSION['message'] = "Échec de la déclaration de l'absence. Veuillez réessayer.";
            }
        } else {
            $_SESSION['message'] = "Données incomplètes. Veuillez remplir tous les champs.";
        }
        $this->action_absence();
    }
    public function action_infosAbsence()
    {
        $bd = Model::getModel();
        $id_absence = $_GET['id_absence'];

        // Récupère les détails de l'absence par ID
        $absence = $bd->getAbsenceById($id_absence);

        // Passe les détails de l'absence à la vue
        $this->render('infosAbsence', [
            'menu' => $this->action_get_navbar(),
            'absence' => $absence
        ]);
    }



}