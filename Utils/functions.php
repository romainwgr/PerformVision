<?php
/**
 * Fonction échappant les caractères html dans $message
 * @param string $message chaîne à échapper
 * @return string chaîne échappée
 */
function e($message)
{
    return htmlspecialchars($message, ENT_QUOTES);
}

/**
 * Fonction qui génère un mot de passe de 12 caractères
 * @return false|string
 */
function genererMdp()
{
    // chaine de caractères qui sera mis dans le désordre:
    $Chaine = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";

    // on mélange la chaine avec la fonction str_shuffle(), propre à PHP
    $Chaine = str_shuffle($Chaine);

    // ensuite on coupe à la longueur voulue avec la fonction substr(), propre à PHP aussi
    return substr($Chaine, 0, 12);
}

function sessionstart()
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

// function maj_infos_personne()
// {
//     sessionstart();
//     $id = $_SESSION['id'];
//     if(isset($_GET['id'])){
//         $id = $_GET['id'];
//     }
//     $bd = Model::getModel();
//     if(isset($_POST['nom']) && !preg_match('/^ *$/', $_POST['nom'])){
//         $bd->setNomPersonne($id, $_POST['nom']);
//     }
//     if(isset($_POST['prenom']) && !preg_match('/^ *$/', $_POST['prenom'])){
//         $bd->setPrenomPersonne($id, $_POST['prenom']);
//     }
//     if(isset($_POST['email']) && !preg_match('/^ *$/', $_POST['email'])){
//         $bd->setEmailPersonne($id, $_POST['email']);
//     }
//     if(isset($_POST['mdp']) && !preg_match('/^ *$/', $_POST['mdp'])){
//         // FIXME mettre en place le chiffrage
//         $bd->setMdpPersonne($id, $_POST['mdp']);
//     }
// }

function maj_infos_client()
{
    $bd = Model::getModel();
    if (isset($_GET['id'])) {
        if (isset($_POST['client']) && !preg_match('/^ *$/', $_POST['client'])) {
            $bd->setNomClient($_GET['id'], $_POST['client']);
        }
        if (isset($_POST['telephone-client']) && !preg_match('/^ *$/', $_POST['telephone-client'])) {
            $bd->setTelClient($_GET['id'], $_POST['telephone-client']);
        }
    }
}

// function maj_infos_composante(){
//     $bd = Model::getModel();
//     if(isset($_GET['id'])){
//         if(isset($_POST['composante']) && !preg_match('/^ *$/', $_POST['composante'])){
//             $bd->setNomComposante($_GET['id'], $_POST['composante']);
//         }
//         if(isset($_POST['client']) && !preg_match('/^ *$/', $_POST['client'])){
//             $bd->setTelClient($_GET['id'], $_POST['telephone-client']);
//         }
//         if(isset($_POST['numero-voie']) && !preg_match('/^ *$/', $_POST['numero-voie'])){
//             $bd->setNumeroAdresse($_GET['id'], $_POST['numero-voie']);
//         }
//         if(isset($_POST['type-voie']) && !preg_match('/^ *$/', $_POST['type-voie'])){
//             $bd->setLibelleTypevoie($_GET['id'], $_POST['type-voie']);
//         }
//         if(isset($_POST['nom-voie']) && !preg_match('/^ *$/', $_POST['nom-voie'])){
//             $bd->setNomVoieAdresse($_GET['id'], $_POST['nom-voie']);
//         }
//         if(isset($_POST['cp']) && !preg_match('/^ *$/', $_POST['cp'])){
//             $bd->setCpLocalite($_GET['id'], $_POST['cp']);
//         }
//         if(isset($_POST['ville']) && !preg_match('/^ *$/', $_POST['ville'])){
//             $bd->setVilleLocalite($_GET['id'], $_POST['ville']);
//         }
//     }
/**
 * Valide le numéro de téléphone.
 *
 * @param string $phone Le numéro de téléphone à valider.
 * @return bool True si le numéro de téléphone est valide, false sinon.
 */
function isValidPhoneNumber($phone)
{
    $phoneRegex = '/^[+]?[(]?[0-9]{1,4}[)]?[-\s\.]?[0-9]{1,4}[-\s\.]?[0-9]{1,4}$/';
    return preg_match($phoneRegex, $phone) === 1;
}

/**
 * Valide le code postal (CP).
 *
 * @param string $cp Le code postal à valider.
 * @return bool True si le code postal est valide, false sinon.
 */
function isValidCp($cp)
{
    $cpRegex = '/^[0-9]{5}$/';
    return preg_match($cpRegex, $cp) === 1;
}

/**
 * Valide l'adresse.
 *
 * @param string $adresse L'adresse à valider.
 * @return string|bool True si l'adresse est valide, message d'erreur sinon.
 */
function isValidAdresse($adresse)
{
    // Vérifier que l'adresse commence par un nombre
    if (!preg_match('/^[0-9]+/', $adresse)) {
        return "Le nombre doit être au début.";
    }

    // Vérifier qu'il y a un espace après le nombre
    if (!preg_match('/[0-9]+\s/', $adresse)) {
        return "Manque l'espace après le nombre.";
    }

    // Vérifier qu'il y a un type de rue suivi d'un espace
    if (!preg_match('/[0-9]+\s\w+\s/', $adresse)) {
        return "Manque l'espace et le type de rue.";
    }

    // Vérifier qu'il y a un nom de rue après le type de rue
    if (!preg_match('/[0-9]+\s\w+\s[^\d\s]+/', $adresse)) {
        return "Manque le nom de la rue.";
    }

    // Vérifier que l'adresse ne se termine pas par un nombre
    if (preg_match('/\d+$/', $adresse)) {
        return "L'adresse ne doit pas se terminer par un nombre.";
    }

    if (!preg_match('/^[0-9]+\s[a-zA-Z]+\s[a-zA-Z\s-]+$/', $adresse)) {
        return "Format incorrect";
    }

    // Vérifier le format général de l'adresse
    $regex = '/^[0-9]+(?:[ -][0-9]+)?\s\w+((?:-|\s)?[^\d\s]+)*\s[^\d\s]+/';
    if (!preg_match($regex, $adresse)) {
        return "Format incorrect.";
    }

    return true;
}

/**
 * Valide l'adresse email.
 *
 * @param string $email L'adresse email à valider.
 * @return bool True si l'adresse email est valide, false sinon.
 */
function isValidEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Valide le nom.
 *
 * @param string $name Le nom à valider.
 * @return bool True si le nom est valide, false sinon.
 */
function isValidName($name)
{
    // Define the regex pattern
    $pattern = '/^[\p{L}\p{M}\s\-.,&()\'"]+$/u';
    return preg_match($pattern, $name) === 1;
}



?>