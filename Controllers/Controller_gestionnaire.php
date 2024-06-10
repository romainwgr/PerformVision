<?php
/**
 * @brief Classe du gestionnaire contenant toutes les fonctionnalités du gestionnaire
 * 
 */

class Controller_gestionnaire extends Controller
{
    /**
     * @inheritDoc
     * Action par défaut qui appelle l'action clients
     */
    public function action_default()
    {
        $this->action_clients();
    }

    /**
     * Action qui retourne les éléments du menu pour le gestionnaire
     * @return array[]
     */
    public function action_get_navbar()
    {
        return [
            ['link' => '?controller=gestionnaire&action=clients', 'name' => 'Société'],
            ['link' => '?controller=gestionnaire&action=composantes', 'name' => 'Composantes'],
            ['link' => '?controller=gestionnaire&action=prestataires', 'name' => 'Prestataires'],
            ['link' => '?controller=gestionnaire&action=commerciaux', 'name' => 'Commerciaux']
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

    /*--------------------------------------------------------------------------------------*/
    /*                                Fonctions de mise à jour                              */
    /*--------------------------------------------------------------------------------------*/

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
            $clients = $bd->getAllClients();

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
                    $prestataires = $bd->getPrestatairesComposante($composanteId); // GetPrestataireByIdComposante

                    // Vérifier que $prestataires est un tableau
                    if (!is_array($prestataires)) {
                        $prestataires = [];
                    }

                    $composante['prestataires'] = $prestataires;
                }

                // Assurer que 'composantes' est toujours un tableau
                $client['composantes'] = $composantes;
                $clientsData[] = $client;
            }

            // Préparer les données pour la vue
            $data = [
                'title' => 'Composantes',
                'tab1' => $client['composantes'],
                'person' => $clientsData,
                'buttonLink' => '?controller=gestionnaire&action=ajout_composante_form',
                'rechercheLink' => '?controller=gestionnaire&action=rechercher&role=client&composante=t',
                'cardLink' => '?controller=gestionnaire&action=infos_composante',
                'addcomp' => '?controller=gestionnaire&action=ajout_autre_composante',
                'menu' => $this->action_get_navbar()
            ];

            // Rendre la vue avec les données
            $this->render("composante", $data, 'gestionnaire');
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
            if (isset($_POST['message'])) {
                $message = "Le client a été ajouté";
            }
            $data = [
                'title' => 'Société',
                'buttonLink' => '?controller=gestionnaire&action=ajout_client',
                'rechercheLink' => '?controller=gestionnaire&action=rechercher&role=client&composante=f',
                'cardLink' => '?controller=gestionnaire&action=infos_client',
                'message' => $message ?? null,
                'person' => $bd->getAllClients(),
                'menu' => $this->action_get_navbar()
            ];
            $this->render("client", $data, 'gestionnaire');
        }
    }

    /**
     * Renvoie la liste de tous les prestataires
     * La vérification de l'identifiant de Session permet de s'assurer que la personne est connectée en faisant partie de la base de données
     * @return void
     */

    public function action_prestataires()
    {

        if (isset($_SESSION['id'])) {
            $bd = Model::getModel();
            if (isset($_GET['message'])) {
                $message = "Le prestatataire a été ajouté";
            }
            $data = [
                'title' => 'Prestataires',
                'cardLink' => "?controller=gestionnaire&action=infos_personne",
                "buttonLink" => '?controller=gestionnaire&action=ajout_prestataire_form',
                'rechercheLink' => '?controller=gestionnaire&action=rechercher&role=prestataire',
                'message' => $message ?? null,
                "person" => $bd->getAllPrestataires(),
                'menu' => $this->action_get_navbar()
            ];
            // pas liste mais prestataire
            $this->render("prestataire", $data, 'gestionnaire');
        }
    }
    /**
     * Renvoie la liste de tous les commerciaux
     * La vérification de l'identifiant de Session permet de s'assurer que la personne est connectée en faisant partie de la base de données
     * @return void
     */

    public function action_commerciaux()
    {

        if (isset($_SESSION['id'])) {
            $bd = Model::getModel();

            if (isset($_GET['message'])) {
                $message = "Le commercial a été ajouté";
            }
            $data = [
                'title' => 'Commerciaux',
                'cardLink' => "?controller=gestionnaire&action=infos_personne",
                'buttonLink' => '?controller=gestionnaire&action=ajout_commercial_form',
                'rechercheLink' => '?controller=gestionnaire&action=rechercher&role=commercial',
                'message' => $message ?? null,
                "person" => $bd->getAllCommerciaux(),
                'menu' => $this->action_get_navbar()
            ];
            $this->render("commercial", $data, 'gestionnaire');
        }
    }


    /*--------------------------------------------------------------------------------------*/
    /*                                Formulaires d'ajout                                  */
    /*--------------------------------------------------------------------------------------*/

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
     * Renvoie la vue du formulaire pour l'ajout d'une composante
     * @return void
     */
    public function action_ajout_composante_form()
    {
        $bd = Model::getModel();

        if (isset($_GET['composante'])) {
            $composante = $bd->getInfosComposante($_GET['composante']);
        }
        $data = [
            'menu' => $this->action_get_navbar(),
            'prestataire' => $bd->getPrestatairesToAdd($_GET['composante']),
            'form' => '?controller=gestionnaire&action=ajouter_prestataire_a_comp',
            'cardLink' => '',
            "composante" => $composante
        ];
        $this->render('ajout_prestataireacomp', $data, 'gestionnaire');
    }
    /**
     * Renvoie la vue du formulaire pour l'ajout d'un prestataire
     * @return void
     */
    public function action_ajout_prestataire_form()
    {

        $data = [
            'menu' => $this->action_get_navbar()
        ];
        $this->render('ajout_prestataire', $data, 'gestionnaire');
    }
    public function action_ajouter_prestataire_a_comp()
    {
        if (isset($_SESSION['id'])) {
            $_SESSION['redirect'] = true;
            $bd = Model::getModel();
            $id_composante = $_POST['composante'];
            $interlocuteurs = $bd->getInterlocuteursComposante($id_composante);

            if (!is_array($interlocuteurs) || empty($interlocuteurs)) {
                $data = [
                    'title' => 'Erreur de données',
                    'message' => 'Aucun interlocuteur trouvé pour cette composante.'
                ];
                $this->render('message', $data);
                return;
            }

            // Choisir le premier interlocuteur trouvé
            $id_interlocuteur = null;
            foreach ($interlocuteurs as $interlocuteur) {
                if (isset($interlocuteur['id_personne'])) {
                    $id_interlocuteur = $interlocuteur['id_personne'];
                    break;
                }
            }

            if (is_null($id_interlocuteur)) {
                $data = [
                    'title' => 'Erreur de données',
                    'message' => 'Aucun ID d\'interlocuteur valide trouvé pour cette composante.'
                ];
                $this->render('message', $data);
                return;
            }

            if (isset($_POST['id_personne'], $_POST['composante']) && !empty($_POST['id_personne'])) {
                $errorMessages = [];

                foreach ($_POST['id_personne'] as $ids) {
                    try {
                        $bdl = $bd->addPrestataireToComposante($ids, $id_composante, $id_interlocuteur, $_SESSION['id'], date('F'), date('Y'));

                        if ($bdl === true) {
                            continue; // Ajout réussi, continuer à l'itération suivante
                        } else {
                            $errorMessages[] = "Erreur lors de l'ajout du BDL pour le prestataire $ids et la composante $id_composante.";
                        }
                    } catch (Exception $e) {
                        $errorMessages[] = $e->getMessage();
                    }
                }

                if (empty($errorMessages)) {
                    $this->action_composantes(); // Rediriger ou afficher la page de succès
                } else {
                    $data = [
                        'title' => 'Erreurs lors de l\'ajout',
                        'message' => implode("<br>", $errorMessages)
                    ];
                    $this->render('message', $data);
                }
            } else {
                $data = [
                    'title' => 'Erreur de données',
                    'message' => 'Les données du formulaire ne sont pas valides.'
                ];
                $this->render('message', $data);
            }
        } else {
            $data = [
                'title' => 'Erreur de session',
                'message' => 'Session non valide.'
            ];
            $this->render('message', $data);
        }
    }

    /**
     * Renvoie la vue du formulaire pour l'ajout d'une mission
     * @return void
     */
    public function action_ajout_mission_form()
    {

        $data = [
            'menu' => $this->action_get_navbar()
        ];
        $this->render('ajout_mission', $data, 'gestionnaire');
    }

    /**
     * Renvoie la vue du formulaire pour l'ajout d'un client
     * @return void
     */
    public function action_ajout_client()
    {

        $bd = Model::getModel();
        $data = [
            'rechercheLink' => '?controller=gestionnaire&action=rechercher&role=commercial',
            'gestionnaire' => $bd->getAllCommerciaux(),
            'cardLink' => '?controller=gestionnaire&action=infos_client',
            'menu' => $this->action_get_navbar()
        ];
        $this->render('ajout_client', $data, 'gestionnaire');
    }

    /**
     * Renvoie la vue du formulaire pour l'ajout d'un commercial
     * @return void
     */
    public function action_ajout_commercial_form()
    {

        $data = [
            'menu' => $this->action_get_navbar()
        ];
        $this->render('ajout_commercial', $data, 'gestionnaire');
    }

    /**
     * Vérifie d'avoir les informations nécessaire et que le commercial n'existe pas en tant que personne et commercial avant de l'ajouter
     * @return void
     */
    public function action_ajout_commercial()
    {
        $bd = Model::getModel();

        if (isset($_POST['prenom'], $_POST['nom'], $_POST['email'], $_POST['tel'])) {
            $prenom = $_POST['prenom'];
            $nom = $_POST['nom'];
            $email = $_POST['email'];
            $tel = $_POST['tel'];
            if (!$bd->checkPersonneExiste($email)) {
                if ($bd->createPersonne($nom, $prenom, $email, genererMdp(), $tel)) {
                    if ($bd->addCommercial($email)) {
                        $validation = 'added';
                        $response = ['success' => true, 'url' => 'index.php?controller=gestionnaire&action=commerciaux&message=' . $validation];
                    } else {
                        $response = ['success' => false, 'message' => 'Erreur lors de l\'ajout du commercial.'];
                    }
                } else {
                    $response = ['success' => false, 'message' => "Erreur de création de la personne"];
                }

            } else {
                $response = ['success' => false, 'message' => "L'adresse email est déjà utilisé!", 'field' => 'email'];
            }
            // Validation et ajout du prestataire

        } else {
            $response = ['success' => false, 'message' => 'Informations manquantes.'];
        }

        echo json_encode($response);
    }
    /**
     * Action permettant de savoir si un client existe déjà ou non, réalisée avec AJAX.
     *
     * Cette méthode vérifie si les informations du client sont fournies via une requête POST.
     * Si le client n'existe pas, il ajoute les informations du client à la session et retourne un succès.
     * Sinon, il retourne un message indiquant que le client existe déjà.
     * Si les informations du client sont manquantes, un message d'erreur est retourné.
     *
     * @return void La méthode retourne une réponse JSON contenant :
     * - `success` : Booléen indiquant le succès ou l'échec de l'opération.
     * - `message` : Un message d'erreur en cas d'échec.
     */
    public function action_is_client()
    {

        $bd = Model::getModel();


        if (isset($_POST['client'], $_POST['tel'])) {
            if (!$bd->checkSocieteExiste($_POST['client'])) {
                // $bd->addClient($_POST['client'], $_POST['tel']);
                $_SESSION['company'] = ['client' => $_POST['client'], 'tel' => $_POST['tel']];

                $response = ['success' => true];
            } else {
                $response = ['success' => false, 'message' => 'La société existe déjà.'];
            }
        } else {
            $response = ['success' => false, 'message' => 'Informations manquantes.'];
        }
        echo json_encode($response);
    }

    /**
     * Action permettant de savoir si une personne existe et la créée si ce n'est pas le cas
     * @param $nom
     * @param $prenom
     * @param $email
     * @return void
     */
    public function action_ajout_personne($nom, $prenom, $email, $tel)
    {
        $bd = Model::getModel();
        if (!$bd->checkPersonneExiste($email)) {
            // FIXME chiffrer le mot de passe et ucfirst sur nom prenom
            $bd->createPersonne($nom, $prenom, $email, genererMdp(), $tel);
        }
        // TODO que faire si elle existe dejà?
    }

    /**
     * Vérifie d'avoir un id dans l'url qui fait référence à la composante et renvoie la vue qui affiche les informations de la composante
     * @return void
     */
    public function action_infos_composante()
    {
        if (isset($_SESSION['role'])) {
            unset($_SESSION['role']);
        }
        $_SESSION['role'] = 'gestionnaire';
        if (isset($_GET['id'])) {
            $bd = Model::getModel();
            $data = [
                'infos' => $bd->getInfosComposante(e($_GET['id'])),
                'prestataires' => $bd->getPrestatairesComposante(e($_GET['id'])),
                'commerciaux' => $bd->getCommerciauxComposante(e($_GET['id'])),
                'interlocuteurs' => $bd->getInterlocuteursComposante(e($_GET['id'])),
                'bdl' => $bd->getBdlComposante(e($_GET['id'])),
                'cardLink' => '?controller=gestionnaire',
                'menu' => $this->action_get_navbar()
            ];
            $this->render('infos_composante', $data);
        }
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
    // public function getCommercial()
    // {

    // }

    // Méthode pour consulter les BDLs des prestataires
    public function action_consulterBDLPrestataire()
    {
        $m = Model::getModel();
        $id_prestataire = $_GET['id_prestataire'] ?? null;

        if ($id_prestataire) {
            $bdls = $m->getBDLsByPrestataireId($id_prestataire);
            $this->render('afficher_bdl_prestataire', [
                'bdls' => $bdls,
                'menu' => $this->action_get_navbar()
            ], 'gestionnaire');
        } else {
            echo "ID du prestataire manquant.";
        }
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
            $interlocuteur = $bd->getInterlocuteurByIdBDL($id_bdl);
            $prestataire = $bd->getPrestataireByIdBDL($id_bdl);
            $gestionnaire = $bd->getGestionnaireById($id_bdl);
            $id_interlocuteur = $bdl['id_interlocuteur'];
            // Récupérer le nom et prenom de l'interlocuteur
            $nom = $bd->getInterlocuteurNameById($id_interlocuteur);

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
                $pdf->Cell(95, 10, iconv('UTF-8', 'ISO-8859-1', htmlspecialchars($bdl['nom_composante'])), 0, 1);
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
                $pdf->Ln(20);

                // Ajouter un espacement avant les signatures
                // $pdf->Ln(20);
                // Vérifiez si le prestataire a signé
                $signature_prestataire = $bdl['signature_prestataire'] ? htmlspecialchars($prestataire['nom']) : '__________________';
                $signature_interlocuteur = $bdl['signature_interlocuteur'] ? htmlspecialchars($nom) : '__________________';

                // Signatures
                $pdf->Cell(95, 10, iconv('UTF-8', 'ISO-8859-1', 'Signature du prestataire:  ') . $signature_prestataire, 0, 0);
                $pdf->Cell(95, 10, iconv('UTF-8', 'ISO-8859-1', "Signature de l'interlicuteur:") . $signature_interlocuteur, 0, 1);
                $pdf->Ln(20);

                // Sauvegarder le PDF dans une variable
                // $pdf_content = $pdf->Output('', 'S');
                // Retourne le contenu du PDF en tant que chaîne

                // Passer les données des BDLs et le contenu du PDF à la vue
                //             $data = [
                //                 'menu' => $this->action_get_navbar(),
                //                 'title' => 'Affichage des BDLs',
                //                 'bdl' => $bdl, // Passer les données du BDL à la vue
                //                 'pdf_content' => $pdf_content // Passer le contenu du PDF à la vue
                //             ];

                //             // Rendre la vue avec les données
                //             $this->render('afficher_bdl', $data);
                //         } else {
                //             echo "<script>alert('Aucun BDL trouvé pour cet ID.'); window.location.href = '?controller=prestataire&action=liste_bdl';</script>";
                //             exit;
                //         }
                //     } else {
                //         echo "ID BDL ou ID Prestataire non défini.";
                //     }
                // }
                // Sortie du PDF
                $pdf->Output('I', 'bon_de_livraison.pdf');
            } else {
                echo "Détails du bon de livraison introuvables.";
            }
        } else {
            echo "ID du bon de livraison ou prestataire manquant.";
        }
    }


    /**
     * Renvoie la vue du formulaire pour l'ajout d'un interlocuteur
     * @return void
     */


    public function action_ajout_autre_composante()
    {
        if (isset($_GET['client'])) {
            $bd = Model::getModel();
            $data = [
                'client' => $_GET['client'],
                'gestionnaire' => $bd->getAllCommerciaux(),
                'menu' => $this->action_get_navbar(),
                'cardLink' => ''
            ];
            $this->render('ajout_autrecomp', $data, 'gestionnaire');
        }
    }

    /**
     * Supprime les variables de session spécifiques au formulaire.
     */
    public function clearFormSession()
    {

        // Supprimer les variables de session spécifiques au formulaire
        unset($_SESSION['company']);
        unset($_SESSION['composante']);
        unset($_SESSION['adresse']);
        unset($_SESSION['interlocuteur']);
        unset($_SESSION['commerciaux']);

        // Retourner une réponse JSON avec success = true, même si les variables n'existent pas
        echo json_encode(['success' => true]);
    }

    public function action_save_data()
    {

        $bd = Model::getModel();
        ob_start(); // Démarrer la capture de sortie

        $step = $_POST['step'] ?? null;
        $response = ['success' => false, 'message' => 'Données manquantes ou invalides'];

        if ($step) {
            switch ($step) {
                case 1:
                    if (isset($_POST['client'], $_POST['tel'])) {
                        $_SESSION['company'] = ['client' => $_POST['client'], 'tel' => $_POST['tel']];
                        $response = ['success' => true];
                    } else {
                        $response['message'] = 'Nom de la société ou numéro de téléphone invalide';
                    }
                    break;

                case 2:
                    if (isset($_POST['composante']) && isValidName($_POST['composante'])) {
                        $_SESSION['composante'] = $_POST['composante'];
                        $response = ['success' => true];
                    } else {
                        $response['message'] = 'Nom de la composante invalide';
                    }
                    break;

                case 3:
                    if (
                        isset($_POST['adresse'], $_POST['voie'], $_POST['cp'], $_POST['ville']) &&
                        isValidAdresse($_POST['adresse']) &&
                        isValidName($_POST['voie']) &&
                        isValidCp($_POST['cp']) &&
                        isValidName($_POST['ville'])
                    ) {
                        $_SESSION['adresse'] = [
                            'adresse' => $_POST['adresse'],
                            'voie' => $_POST['voie'],
                            'cp' => $_POST['cp'],
                            'ville' => $_POST['ville']
                        ];
                        $response = ['success' => true];
                    } else {
                        $response['message'] = 'Adresse, voie, code postal ou ville invalide';
                    }
                    break;

                case 4:
                    if (
                        isset($_POST['prenom_int'], $_POST['nom_int'], $_POST['mail_int'], $_POST['tel_int']) &&
                        isValidName($_POST['prenom_int']) &&
                        isValidName($_POST['nom_int']) &&
                        isValidEmail($_POST['mail_int']) &&
                        isValidPhoneNumber($_POST['tel_int'])
                    ) {
                        $_SESSION['interlocuteur'] = [
                            'prenom_int' => $_POST['prenom_int'],
                            'nom_int' => $_POST['nom_int'],
                            'mail_int' => $_POST['mail_int'],
                            'tel_int' => $_POST['tel_int']
                        ];
                        $response = ['success' => true];
                    } else {
                        $response['message'] = 'Prénom, nom, email ou téléphone de l\'interlocuteur invalide';
                    }
                    break;

                case 5:
                    if (isset($_POST['idsCommerciaux']) && !empty($_POST['idsCommerciaux'])) {
                        $_SESSION['commerciaux'] = [];
                        foreach ($_POST['idsCommerciaux'] as $idCommercial) {
                            if (!in_array($idCommercial, $_SESSION['commerciaux'])) {
                                $_SESSION['commerciaux'][] = $idCommercial;
                            }
                        }

                        try {
                            $bd->beginTransaction();

                            $bd->addClient($_SESSION['company']['client'], $_SESSION['company']['tel']);
                            $client = $bd->getClientByName($_SESSION['company']['client']);
                            $idAdresse = $bd->addAdresse(
                                $_SESSION['adresse']['adresse'],
                                $_SESSION['adresse']['cp'],
                                $_SESSION['adresse']['ville'],
                                $_SESSION['adresse']['voie']
                            );
                            $id_comp = $bd->addComposante($_SESSION['composante'], $idAdresse, $client['id_client']);
                            $bd->createPersonne(
                                $_SESSION['interlocuteur']['nom_int'],
                                $_SESSION['interlocuteur']['prenom_int'],
                                $_SESSION['interlocuteur']['mail_int'],
                                genererMdp(),
                                $_SESSION['interlocuteur']['tel_int']
                            );
                            $bd->addInterlocuteur($_SESSION['interlocuteur']['mail_int']);
                            $id_int = $bd->getInterlocuteurIdByEmail($_SESSION['interlocuteur']['mail_int']);
                            $bd->addInterlocuteurToComposante($id_int, $id_comp);

                            foreach ($_SESSION['commerciaux'] as $comm) {
                                $bd->addCommercialToComposante($comm, $id_comp);
                            }

                            $bd->commit();

                            unset($_SESSION['company']);
                            unset($_SESSION['composante']);
                            unset($_SESSION['adresse']);
                            unset($_SESSION['interlocuteur']);
                            unset($_SESSION['commerciaux']);

                            $response = ['success' => true, 'message' => 'Données enregistrées avec succès'];
                        } catch (Exception $e) {
                            $bd->rollBack();
                            $response = ['success' => false, 'message' => 'Erreur lors de l\'enregistrement des données'];
                        }
                    } else {
                        $response['message'] = 'IDs commerciaux manquants ou invalides';
                    }
                    break;

                default:
                    $response['message'] = 'Étape invalide';
                    break;
            }
        }

        $output = ob_get_clean(); // Récupérer et nettoyer la sortie

        if (!empty($output)) {
            error_log("Unexpected output: $output");
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function action_save_data2()
    {

        $bd = Model::getModel();
        ob_start(); // Démarrer la capture de sortie

        $step = $_POST['step'] ?? null;
        $response = ['success' => false, 'message' => 'Données manquantes ou invalides'];

        if ($step) {
            switch ($step) {
                case 1:
                    if (isset($_POST['societe'], $_POST['composante']) && isValidName($_POST['composante'])) {
                        $_SESSION['company'] = ['societe' => $_POST['societe']];
                        $_SESSION['composante'] = $_POST['composante'];
                        $response = ['success' => true];
                    } else {
                        $response['message'] = 'Nom de la société ou composante invalide';
                    }
                    break;

                case 2:
                    if (
                        isset($_POST['adresse'], $_POST['voie'], $_POST['cp'], $_POST['ville']) &&
                        isValidAdresse($_POST['adresse']) &&
                        isValidName($_POST['voie']) &&
                        isValidCp($_POST['cp']) &&
                        isValidName($_POST['ville'])
                    ) {
                        $_SESSION['adresse'] = [
                            'adresse' => $_POST['adresse'],
                            'voie' => $_POST['voie'],
                            'cp' => $_POST['cp'],
                            'ville' => $_POST['ville']
                        ];
                        $response = ['success' => true];
                    } else {
                        $response['message'] = 'Adresse, voie, code postal ou ville invalide';
                    }
                    break;

                case 3:
                    if (
                        isset($_POST['prenom_int'], $_POST['nom_int'], $_POST['mail_int'], $_POST['tel_int']) &&
                        isValidName($_POST['prenom_int']) &&
                        isValidName($_POST['nom_int']) &&
                        isValidEmail($_POST['mail_int']) &&
                        isValidPhoneNumber($_POST['tel_int'])
                    ) {
                        $_SESSION['interlocuteur'] = [
                            'prenom_int' => $_POST['prenom_int'],
                            'nom_int' => $_POST['nom_int'],
                            'mail_int' => $_POST['mail_int'],
                            'tel_int' => $_POST['tel_int']
                        ];
                        $response = ['success' => true];
                    } else {
                        $response['message'] = 'Prénom, nom, email ou téléphone de l\'interlocuteur invalide';
                    }
                    break;

                case 4:
                    if (isset($_POST['idsCommerciaux']) && !empty($_POST['idsCommerciaux'])) {
                        $_SESSION['commerciaux'] = [];
                        foreach ($_POST['idsCommerciaux'] as $idCommercial) {
                            if (!in_array($idCommercial, $_SESSION['commerciaux'])) {
                                $_SESSION['commerciaux'][] = $idCommercial;
                            }
                        }

                        try {
                            $bd->beginTransaction();

                            // Ensure the client exists
                            $client = $bd->getClientById($_SESSION['company']['societe']);
                            if (!$client) {
                                throw new Exception('Client not found');
                            }

                            $idAdresse = $bd->addAdresse(
                                $_SESSION['adresse']['adresse'],
                                $_SESSION['adresse']['cp'],
                                $_SESSION['adresse']['ville'],
                                $_SESSION['adresse']['voie']
                            );

                            $id_comp = $bd->addComposante($_SESSION['composante'], $idAdresse, $client['id_client']);

                            $bd->createPersonne(
                                $_SESSION['interlocuteur']['nom_int'],
                                $_SESSION['interlocuteur']['prenom_int'],
                                $_SESSION['interlocuteur']['mail_int'],
                                genererMdp(),
                                $_SESSION['interlocuteur']['tel_int']
                            );

                            $bd->addInterlocuteur($_SESSION['interlocuteur']['mail_int']);
                            $id_int = $bd->getInterlocuteurIdByEmail($_SESSION['interlocuteur']['mail_int']);
                            $bd->addInterlocuteurToComposante($id_int, $id_comp);

                            foreach ($_SESSION['commerciaux'] as $comm) {
                                $bd->addCommercialToComposante($comm, $id_comp);
                            }

                            $bd->commit();

                            // Clear session data after successful transaction
                            unset($_SESSION['company']);
                            unset($_SESSION['composante']);
                            unset($_SESSION['adresse']);
                            unset($_SESSION['interlocuteur']);
                            unset($_SESSION['commerciaux']);

                            $response = ['success' => true, 'message' => 'Données enregistrées avec succès'];
                        } catch (Exception $e) {
                            $bd->rollBack();
                            $response = ['success' => false, 'message' => 'Erreur lors de l\'enregistrement des données: ' . $e->getMessage()];
                        }
                    } else {
                        $response['message'] = 'IDs commerciaux manquants ou invalides';
                    }
                    break;

                default:
                    $response['message'] = 'Étape invalide';
                    break;
            }
        }

        $output = ob_get_clean(); // Récupérer et nettoyer la sortie

        if (!empty($output)) {
            error_log("Unexpected output: $output");
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function action_is_interlocuteur()
    {
        $bd = Model::getModel();
        if (isset($_POST['mail'])) {
            if (!$bd->checkPersonneExiste($_POST['mail'])) {
                $_SESSION['mail'] = $_POST['mail'];
                $response = ['success' => true];
            } else {
                $response = ['success' => false, 'message' => 'Le mail est déjà utilisé.'];
            }
        } else {
            $response = ['success' => false, 'message' => 'Informations manquantes.'];
        }
        echo json_encode($response);
    }

    public function action_view_after_save()
    {
        if (isset($_SESSION['id'])) {
            $bd = Model::getModel();

            $data = [
                'title' => 'Société',
                'buttonLink' => '?controller=gestionnaire&action=ajout_client',
                'rechercheLink' => '?controller=gestionnaire&action=rechercher&role=client&composante=f',
                'cardLink' => '?controller=gestionnaire&action=infos_client',
                'message' => "Le client a été ajouté",
                'person' => $bd->getAllClients(),
                'menu' => $this->action_get_navbar()
            ];
            $this->render("client", $data, 'gestionnaire');
        }
    }



    // public function action_is_composante()
    // {
    //     //TODO en ajax
    // }

    /**
     * Vérifie d'avoir toutes les informations d'un prestataire pour ensuite créer la personne et l'ajouter en tant que prestataire.
     *
     * Cette méthode est appelée via une requête AJAX et vérifie si toutes les informations nécessaires du prestataire sont présentes
     * dans la requête POST. Elle procède ensuite à la création de la personne et à son ajout en tant que prestataire.
     *
     * @return void La méthode retourne une réponse JSON contenant :
     * - `success` : Booléen indiquant le succès ou l'échec de l'opération.
     * - `url` : L'URL de redirection en cas de succès.
     * - `message` : Un message d'erreur en cas d'échec.
     * - `field` : Le champ qui a causé l'erreur, le cas échéant.
     *
     */
    public function action_ajout_prestataire()
    {
        $bd = Model::getModel();

        if (isset($_POST['prenom'], $_POST['nom'], $_POST['email'], $_POST['tel'])) {
            $prenom = $_POST['prenom'];
            $nom = $_POST['nom'];
            $email = $_POST['email'];
            $tel = $_POST['tel'];

            // Validation de l'existence de la personne
            if (!$bd->checkPersonneExiste($email)) {
                // Création de la personne
                if ($bd->createPersonne($nom, $prenom, $email, genererMdp(), $tel)) {
                    // Ajout en tant que prestataire
                    if ($bd->addPrestataire($email)) {
                        $validation = 'added';
                        $response = ['success' => true, 'url' => 'index.php?controller=gestionnaire&action=prestataires&message=' . $validation];
                    } else {
                        $response = ['success' => false, 'message' => 'Erreur lors de l\'ajout du prestataire.'];
                    }
                } else {
                    $response = ['success' => false, 'message' => "Erreur de création de la personne"];
                }
            } else {
                $response = ['success' => false, 'message' => "L'adresse email est déjà utilisé!", 'field' => 'email'];
            }
        } else {
            $response = ['success' => false, 'message' => 'Informations manquantes.'];
        }

        echo json_encode($response);
    }



    // Ajout d'une fonction pour rechercher un prestataire 10/05 Romain
    // ca recherche pas un gestionnaire mais obligé de mettre ça car leur site est cassé
    /**
     * Recherche des personnes selon l'entrée de l'utilisateur dans la barre de recherche
     * 
     * L'action récupère le rôle pour rechercher des personnes selon leur rôle 
     * Par exemple dans la page des prestataires, cette méthode est appelée avec 'role=prestataire' dans la barre de recherche, ce qui recherche uniquement des prestataires
     * 
     * Cette action renvoie la vue correspondant au role
     * @return void
     */
    public function action_rechercher()
    {
        $m = Model::getModel();
        if (isset($_GET['role'], $_POST['recherche'])) {
            $roles = ['client', 'prestataire', 'commercial'];
            if (in_array($_GET['role'], $roles)) {

                $recherche = '';
                $role = ucfirst($_GET['role']);

                $fonction_recherche = "recherche{$role}";
                $fonction_recuperation = "get{$role}ByIds";

                $recherche = ucfirst(strtolower($_POST['recherche']));

                $resultat = $m->$fonction_recherche($recherche);

                if ($_GET['role'] == 'commercial' || $_GET['role'] == 'prestataire') {
                    $ids = array_column($resultat, 'id_personne');

                } else {
                    $ids = array_column($resultat, 'id_client');

                }
                $users = $m->$fonction_recuperation($ids);



                if ($_GET['role'] == 'client' && $_GET['composante'] == 'f') {

                    $data = [
                        'title' => 'Société',
                        'buttonLink' => '?controller=gestionnaire&action=ajout_client',
                        'rechercheLink' => '?controller=gestionnaire&action=rechercher&role=client&composante=f',
                        'cardLink' => '?controller=gestionnaire&action=infos_client',
                        'person' => $users,
                        'val_rech' => $recherche,
                        'menu' => $this->action_get_navbar()
                    ];
                    $this->render("client", $data, 'gestionnaire');
                } else if ($_GET['composante'] == 't') {

                    $clients = $users;

                    // Organiser les données hiérarchiquement
                    $clientsData = [];
                    foreach ($clients as $client) {
                        $clientId = $client['id_client'];
                        $composantes = $m->getComposantesSociete($clientId); // GetComposanteByClientId

                        // Vérifier que $composantes est un tableau
                        if (!is_array($composantes)) {
                            $composantes = [];
                        }

                        foreach ($composantes as &$composante) {
                            $composanteId = $composante['id_composante'];
                            $prestataires = $m->getPrestatairesComposante($composanteId); // GetPrestataireByIdComposante

                            // Vérifier que $prestataires est un tableau
                            if (!is_array($prestataires)) {
                                $prestataires = [];
                            }

                            $composante['prestataires'] = $prestataires;
                        }

                        // Assurer que 'composantes' est toujours un tableau
                        $client['composantes'] = $composantes;
                        $clientsData[] = $client;
                    }

                    // Préparer les données pour la vue
                    $data = [
                        'title' => 'Composantes',
                        'person' => $clientsData,
                        'buttonLink' => '?controller=gestionnaire&action=ajout_composante_form',
                        'rechercheLink' => '?controller=gestionnaire&action=rechercher&role=client&composante=t',
                        'val_rech' => $recherche,
                        'cardLink' => '?controller=gestionnaire&action=infos_composante',
                        'menu' => $this->action_get_navbar()
                    ];
                    $this->render('composante', $data, 'gestionnaire');

                } else {
                    $data = [
                        "title" => ucfirst($_GET['role']),
                        'cardLink' => "?controller=gestionnaire&action=infos_personne",
                        "buttonLink" => '?controller=gestionnaire&action=ajout_' . $_GET['role'] . '_form',
                        'rechercheLink' => '?controller=gestionnaire&action=rechercher&role=' . $_GET['role'],
                        "person" => $users,
                        "val_rech" => $recherche,
                        'menu' => $this->action_get_navbar()
                    ];

                    $this->render($_GET['role'], $data, 'gestionnaire');
                }

            } else {
                $this->render('message', [
                    'title' => 'Erreur de recherche',
                    'message' => 'Ne modifiez pas l\'url'
                ]);
            }
        } else {
            $this->render('message', [
                'title' => 'Erreur de recherche',
                'message' => 'EVITE DE MODIFIER L\'URL' . $_GET['role'],
                $_POST['recherche']
            ]);
        }

    }


}

?>