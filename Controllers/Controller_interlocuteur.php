<?php

class Controller_interlocuteur extends Controller
{
    /**
     * @inheritDoc
     */
    public function action_default()
    {
        $this->action_afficher_prestataire();
    }


    /**
     * Action qui retourne les éléments du menu pour le prestataire
     * @return array[]
     */
    public function action_get_navbar()
    {
        return [
            ['link' => '?controller=interlocuteur&action=afficher_prestataire', 'name' => 'Mes Prestataires'],
            ['link' => '?controller=interlocuteur&action=liste_bdl', 'name' => 'Bons de livraison']
        ];
    }

    /**
     * Renvoie la vue qui montre les informations de l'utilisateur connecté
     * @return void
     */
    public function action_infos()
    {
        $this->render('infos', ['menu' => $this->action_get_navbar()]);
    }

    // TEST

    /**
     * Ajoute dans la base de données la date à laquelle le prestataire est absent
     * @return void
     */
    public function action_prestataire_creer_absences()
    {
        $bd = Model::getModel();
        if (
            isset($_POST['prenom']) &&
            isset($_POST['nom']) &&
            isset($_POST['email']) &&
            isset($_POST['Date']) &&
            isset($_POST['motif'])
        ) {
            // FIXME Fonction non déclaré dans le modèle
            $bd->addAbsenceForPrestataire($_POST['prenom'], $_POST['nom'], $_POST['email'], $_POST['Date'], $_POST['motif']);
        } else {
            $this->action_error("données incomplètes");
        }
    }

    public function action_afficher_bdl()
    {
        $bd = Model::getModel();

        // Vérifiez si l'ID du BDL est passé en GET
        if (isset($_GET['id_bdl'])) {
            // Stockez l'ID du BDL dans la session
            $_SESSION['id_bdl'] = $_GET['id_bdl'];
        }

        // Récupérez l'ID du BDL et du prestataire depuis la session
        $id_bdl = isset($_SESSION['id_bdl']) ? $_SESSION['id_bdl'] : null;
        $id_prestataire = isset($_SESSION['id']) ? $_SESSION['id'] : null;

        if ($id_bdl !== null && $id_prestataire !== null) {
            // Récupérez les détails du BDL en utilisant l'ID du prestataire et l'ID du BDL
            $bdl = $bd->getBdlPrestataireBybdlId($id_bdl);
            $id_interlocuteur = $bdl['id_interlocuteur'];
            // Récupérer le nom et prenom de l'interlocuteur
            $nom = $bd->getInterlocuteurNameById($id_interlocuteur);
            $interlocuteur = $bd->getInterlocuteurByIdBDL($id_bdl);
            $prestataire = $bd->getPrestataireByIdBDL($id_bdl);
            $gestionnaire = $bd->getGestionnaireById($id_bdl);

            if ($bdl) {
                // Inclure la bibliothèque FPDF
                require_once ('libraries/fpdf/fpdf.php');

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
                // Ajuster la cellule pour couvrir la largeur du tableau et centrer le texte
                $pdf->Cell(180, 10, iconv('UTF-8', 'ISO-8859-1', 'Heures travaillées'), 0, 1, 'C');
                $pdf->Ln(5);
                // Tableau des heures travaillées et des commentaires
                $pdf->SetFont('FreeSerif', 'B', 12);
                $pdf->SetFillColor(224, 235, 255); // Couleur de fond bleue claire
                $pdf->Cell(90, 10, iconv('UTF-8', 'ISO-8859-1', 'Jour'), 1, 0, 'C', true);
                $pdf->Cell(90, 10, iconv('UTF-8', 'ISO-8859-1', 'Nombre d\'heures'), 1, 1, 'C', true);


                foreach ($hours as $hour) {
                    $pdf->SetFont('FreeSerif', '', 12);
                    $pdf->Cell(90, 10, htmlspecialchars($hour['jour']), 1, 0, 'C');
                    $pdf->Cell(90, 10, iconv('UTF-8', 'ISO-8859-1', htmlspecialchars($hour['hours_worked'])), 1, 1, 'C');
                }
                $pdf->Ln(10);
                $pdf->Cell(0, 10, 'Total des heures : ' . htmlspecialchars($bdl['heures']), 0, 1, 'L');
                $pdf->Cell(0, 10, 'Commentaire : ' . iconv('UTF-8', 'ISO-8859-1', htmlspecialchars($bdl['commentaire'])), 0, 1, 'L');

                // Ajouter un espacement avant les signatures
                $pdf->Ln(20);
                $signature_prestataire = $bdl['signature_prestataire'] ? htmlspecialchars($prestataire['nom']) : '__________________';
                $signature_interlocuteur = $bdl['signature_interlocuteur'] ? htmlspecialchars($nom) : '__________________';

                // Signatures
                $pdf->Cell(95, 10, iconv('UTF-8', 'ISO-8859-1', 'Signature du prestataire:  ') . $signature_prestataire, 0, 0);
                $pdf->Cell(95, 10, iconv('UTF-8', 'ISO-8859-1', "Signature de l'interlicuteur:") . $signature_interlocuteur, 0, 1);
                $pdf->Ln(20);

                // Sauvegarder le PDF dans une variable
                //         $pdf_content = $pdf->Output('', 'S');
                //         // Retourne le contenu du PDF en tant que chaîne

                //         // Passer les données des BDLs et le contenu du PDF à la vue
                //         $data = [
                //             'menu' => $this->action_get_navbar(),
                //             'title' => 'Affichage des BDLs',
                //             'bdl' => $bdl, // Passer les données du BDL à la vue
                //             'pdf_content' => $pdf_content // Passer le contenu du PDF à la vue
                //         ];

                //         // Rendre la vue avec les données
                //         $this->render('afficher_bdl', $data);
                //     } else {
                //         echo "<script>alert('Aucun BDL trouvé pour cet ID.'); window.location.href = '?controller=prestataire&action=liste_bdl';</script>";
                //         exit;
                //     }
                // } else {
                //     echo "ID BDL ou ID Prestataire non défini.";
                // }
                $pdf->Output('I', 'bon_de_livraison.pdf');
            } else {
                echo "Détails du bon de livraison introuvables.";
            }
        } else {
            echo "ID du bon de livraison ou prestataire manquant.";
        }
    }


    public function action_afficher_prestataire()
    {
        $bd = Model::getModel();
        $person = $bd->getPrestataireByComposante($_SESSION['id']);

        ($_SESSION['id']);
        $data = [
            'title' => 'Mes Prestataires',
            'buttonLink' => '?controller=interlocuteur&action=ajout_bdl_form',
            'cardLink' => '?controller=interlocuteur&action=afficher_bdl',
            'menu' => $this->action_get_navbar(),
            'rechercheLink' => '',
            'person' => $person
        ];
        $this->render('prestataire', $data, "interlocuteur");
    }

    public function action_consulterAbsencesPrestataire()
    {
        $m = Model::getModel();
        $id_prestataire = $_GET['id_prestataire'] ?? null;

        if ($id_prestataire) {
            $absences = $m->getAbsencesByPersonId($id_prestataire);
            $this->render('afficher_absences_prestataire', ['absences' => $absences, 'menu' => $this->action_get_navbar()], 'gestionnaire');
        } else {
            echo "ID du prestataire manquant.";
        }
    }


    public function action_liste_bdl()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $bd = Model::getModel();

        if (isset($_SESSION['id'])) {
            $person = $bd->getAllBdlInterlocuteur($_SESSION['id']);
            $data = [
                'title' => 'Mes Bons de livraison',
                'buttonLink' => '?controller=interlocuteur&action=ajout_bdl_form',
                'cardLink' => '?controller=interlocuteur&action=afficher_bdl',
                'menu' => $this->action_get_navbar(),
                'rechercheLink' => '',
                'person' => $person
            ];
            $this->render('listeBDL_interlocuteur', $data, "interlocuteur");
        }
    }



    /**
     * Vérifie d'avoir les informations nécessaires pour créer un bon de livraison
     * @return void
     */
    public function action_prestataire_creer_bdl()
    {
        $bd = Model::getModel();
        if (isset($_SESSION['id']) && isset($_POST['mission'])) {
            // FIXME Fonction non déclaré dans le modèle 
            $bd->addBdlForPrestataire($_SESSION['id'], e($_POST['mission']));
        } else {
            // TODO Réaliser un render de l'erreur
            echo 'Une erreur est survenue lors de la création du bon de livraison';
        }
    }

    /**
     * Récupère le tableau renvoyé par le JavaScript et rempli les lignes du bon de livraison en fonction de son type
     * @return void
     */
    // TODO a tester
    public function action_completer_bdl()
    {
        $bd = Model::getModel();
        // Récupérer les données depuis la requête POST
        $data = json_decode(file_get_contents("php://input"), true);

        // Vérifier si les données sont présentes
        if ($data && is_array($data)) {
            // Parcourir chaque ligne du tableau
            foreach ($data as $row) {
                // Vérifier si l'activite existe avant de l'ajouter, sinon la modifier
                if ($bd->checkActiviteExiste($_GET['id'], $row[0])) {
                    $id_activite = $bd->getIdActivite($row[0], $_GET['id']);
                    if ($row[1] && $_GET['type'] == 'Heure') {
                        $bd->setNbHeure($id_activite, (int) $row[1]);
                    } elseif ($row[1] >= 0 && $row[1] <= 1 && $_GET['type'] == 'Journée') {
                        $bd->setJourneeJour($id_activite, (int) $row[1]);
                    } elseif ($row[1] >= 0 && $row[1] <= 2 && $_GET['type'] == 'Demi-journée') {
                        $bd->setDemiJournee($id_activite, (int) $row[1]);
                    }
                    if ($row[2]) {
                        $bd->setCommentaireActivite($id_activite, $row[2]);
                    }
                } elseif ($row[1]) {
                    if ($row[1] && $_GET['type'] == 'Heure') {
                        $bd->addNbHeureActivite($row[2], $_GET['id'], $_SESSION['id'], $row[0], (int) $row[1]);
                    } elseif ($row[1] >= 0 && $row[1] <= 1 && $_GET['type'] == 'Journée') {
                        $bd->addJourneeJour($row[2], $_GET['id'], $_SESSION['id'], $row[0], (int) $row[1]);
                    } elseif ($row[1] >= 0 && $row[1] <= 2 && $_GET['type'] == 'Demi-journée') {
                        $bd->addDemiJournee($row[2], $_GET['id'], $_SESSION['id'], $row[0], (int) $row[1]);
                    }
                }
            }
        }
        $this->render('dashboard');
    }
    public function action_afficherFormulaire()
    {
        $bd = Model::getModel();

        // Vérifiez si l'ID du BDL est passé en GET
        if (isset($_GET['id_bdl'])) {
            // Récupérez l'ID du BDL
            if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
                $_SESSION['id'] = null;
            }
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
        $nbHours = $bd->getTotalHoursByIdBDL($id_bdl);
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

        $this->render('form_bdl', ['message' => $message, 'client' => $client, 'composante' => $composante, 'mois' => $mois]);


    }

    public function action_addHalfDay()
    {
        $bd = Model::getModel();
        $id_bdl = $_SESSION["id_bdl"];
        $jour = $_POST['nombre_jour'];
        $demi_journees = ($_POST['nombre_demi_journees'] * 4);

        $resultat = $bd->insertDailyHours($id_bdl, $jour, $demi_journees);
        $nbHours = $bd->getTotalHoursByIdBDL($id_bdl);
        $bd->updateHoursByIdBDL($id_bdl, $nbHours);
        $message = $resultat ? 'L\'ajout de la demi-journée a été effectué avec succès.' : 'Erreur lors de l\'ajout de la demi-journée.';
        // Rétablir les données pour remplir à nouveau le formulaire
        $client = $_POST['client'];
        $composante = $_POST['composante'];
        $mois = $_POST['mois'];

        $this->render('form_bdl', ['message' => $message, 'client' => $client, 'composante' => $composante, 'mois' => $mois]);

    }

    public function action_addHourWithoutDay()
    {

        $bd = Model::getModel();
        $id_bdl = $_SESSION["id_bdl"];
        $heures_sans_jour = $_POST['nombre_heures_sans_jour'];

        $resultat = $bd->insertDailyHours($id_bdl, 0, $heures_sans_jour);
        $nbHours = $bd->getTotalHoursByIdBDL($id_bdl);
        $bd->updateHoursByIdBDL($id_bdl, $nbHours);
        $message = $resultat ? 'L\'ajout des heures sans jour a été effectué avec succès.' : 'Erreur lors de l\'ajout des heures sans jour.';
        // Rétablir les données pour remplir à nouveau le formulaire
        $client = $_POST['client'];
        $composante = $_POST['composante'];
        $mois = $_POST['mois'];

        $this->render('form_bdl', ['message' => $message, 'client' => $client, 'composante' => $composante, 'mois' => $mois]);

    }

    /**
     * Vérifie d'avoir les informations nécessaire pour ajouter un bon de livraison à une mission
     * @return void
     */


    public function action_validerbdl()
    {
        $bd = Model::getModel();

        // Ajoutez cette ligne pour voir ce qui est envoyé dans $_POST
        error_log(print_r($_POST, true));

        if (isset($_POST['id_bdl'])) {
            $id_bdl = $_POST['id_bdl'];
            $_SESSION['id_bdl'] = $id_bdl;

            // Vérifier que l'ID est défini et est un entier valide
            if (!empty($id_bdl)) {
                $result = $bd->setSignTrueInterlocuteurId($id_bdl);
                if ($result) {
                    $this->action_liste_bdl();
                } else {
                    $this->render('listeBDL_interlocuteur', ['error' => 'Une erreur est survenue lors de la validation du bon de livraison.'], "interlocuteur");
                }
            } else {
                $this->render('listeBDL_interlocuteur', ['error' => 'ID de bon de livraison non valide.'], "interlocuteur");
            }
        } else {
            $this->render('listeBDL_interlocuteur', ['error' => 'ID de bon de livraison non défini.'], "interlocuteur");
        }
    }
    public function action_ajouter_commentaire()
    {
        $bd = Model::getModel();

        if (isset($_POST['id_bdl']) && isset($_POST['commentaire'])) {
            $id_bdl = $_POST['id_bdl'];
            $commentaire = $_POST['commentaire'];

            // Vérifier que l'ID est défini et est un entier valide
            if (!empty($id_bdl) && !empty($commentaire)) {
                $result = $bd->makeCommentBDL($id_bdl, $commentaire);
                if ($result) {
                    $this->action_liste_bdl();
                } else {
                    $this->render('listeBDL_interlocuteur', ['error' => 'Une erreur est survenue lors de l\'ajout du commentaire.'], "interlocuteur");
                }
            } else {
                $this->render('listeBDL_interlocuteur', ['error' => 'ID de bon de livraison ou commentaire non valide.'], "interlocuteur");
            }
        } else {
            $this->render('listeBDL_interlocuteur', ['error' => 'ID de bon de livraison ou commentaire non défini.'], "interlocuteur");
        }
    }

    public function action_maj_infos()
    {
        maj_infos_personne(); // fonction dans Utils
        $this->action_infos();
    }





}

