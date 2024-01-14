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
function genererMdp(){
    // chaine de caractères qui sera mis dans le désordre:
    $Chaine = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";

    // on mélange la chaine avec la fonction str_shuffle(), propre à PHP
    $Chaine = str_shuffle($Chaine);

    // ensuite on coupe à la longueur voulue avec la fonction substr(), propre à PHP aussi
    return substr($Chaine,0, 12);
}

function action_maj_infos()
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    $bd = Model::getModel();
    if(isset($_POST['nom']) && !preg_match('/^ *$/', $_POST['nom'])){
        $bd->setNomPersonne($_SESSION['id'], $_POST['nom']);
    }
    if(isset($_POST['prenom']) && !preg_match('/^ *$/', $_POST['prenom'])){
        $bd->setPrenomPersonne($_SESSION['id'], $_POST['prenom']);
    }
    if(isset($_POST['email']) && !preg_match('/^ *$/', $_POST['email'])){
        $bd->setEmailPersonne($_SESSION['id'], $_POST['email']);
    }
    if(isset($_POST['mdp']) && !preg_match('/^ *$/', $_POST['mdp'])){
        $bd->setMdpPersonne($_SESSION['id'], $_POST['mdp']);
    }
}