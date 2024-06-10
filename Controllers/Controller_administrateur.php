<?php
/**
 * @brief Classe du gestionnaire contenant toutes les fonctionnalités du gestionnaire
 * 
 * Pas encore fonctionnel
 *
 */
class Controller_administrateur extends Controller
{
    /**
     * @inheritDoc
     * Action par défaut qui appelle l'action clients
     */
    public function action_default()
    {
        $this->action_ajouter_gestionnaire();
    }

    /**
     * Action qui retourne les éléments du menu pour le gestionnaire
     * @return array[]
     */

    public function action_get_navbar()
    {
        return [
            ['link' => '?controller=administrateur&action=ajouter_gestionnaire', 'name' => 'Ajout Gestionnaire']
        ];
    }

    public function action_ajouter_gestionnaire()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!empty($_POST['nom']) && !empty($_POST['email'])) {

                $this->render('ajout_gestionnaire', [
                    'success' => 'Gestionnaire ajouté avec succès.',
                    'menu' => $this->action_get_navbar()
                ], "administrateur");
            } else {
                $this->render('ajout_gestionnaire', [
                    'error' => 'Tous les champs sont requis.',
                    'menu' => $this->action_get_navbar()
                ], "administrateur");
            }
        } else {
            $this->render('ajout_gestionnaire', [
                'menu' => $this->action_get_navbar()
            ], "administrateur");
        }
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
     * Met à jour les informations de la personne
     * @return void
     */
    public function action_maj_infos_personne()
    {
        maj_infos_personne(); // fonction dans Utils
        $this->action_infos_personne();
    }

    /**
     * Met à jour les informations de la composante
     * @return void
     */
    public function action_maj_infos_composante()
    {
        maj_infos_composante(); // fonction dans Utils
        $this->action_infos_composante();
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
                'person' => $clientsData,
                'buttonLink' => '?controller=gestionnaire&action=ajout_composante_form',
                'rechercheLink' => '?controller=gestionnaire&action=rechercher&role=client&composante=t',
                'cardLink' => '?controller=gestionnaire&action=infos_composante',
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
            $data = [
                'title' => 'Société',
                'buttonLink' => '?controller=gestionnaire&action=ajout_client_form',
                'rechercheLink' => '?controller=gestionnaire&action=rechercher&role=client&composante=f',
                'cardLink' => '?controller=gestionnaire&action=infos_client',
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

        $data = [
            'menu' => $this->action_get_navbar()
        ];
        $this->render('ajout_composante', $data, 'gestionnaire');
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
    public function action_ajout_client_form()
    {

        $data = [
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
                $_SESSION['client'] = $_POST['client'];
                $_SESSION['tel'] = $_POST['tel'];
                $response = ['success' => true];
            } else {
                $response = ['success' => false, 'message' => 'La société existe déjà.'];
            }
        } else {
            $response = ['success' => false, 'message' => 'Informations manquantes.'];
        }
        echo json_encode($response);
    }


    // public function action_is_composante()
    // {
    // TODO en ajax
    // }


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

    /**
     * Vérifie d'avoir un id dans l'url qui fait référence à la composante et renvoie la vue qui affiche les informations de la composante
     * @return void
     */
    public function action_infos_composante()
    {

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
                // TODO faire la fonction de recupération pour la composante et la société
                $users = $m->$fonction_recuperation($ids);



                if ($_GET['role'] == 'client' && $_GET['composante'] == 'f') {

                    $data = [
                        'title' => 'Société',
                        'buttonLink' => '?controller=gestionnaire&action=ajout_client_form',
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