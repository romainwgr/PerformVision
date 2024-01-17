<?php
require_once "Models/Model.php";


// Fonction pour chiffrer un mot de passe avec la clé publique
// encryptWithPublicKey($password, $publicKey);

// Générer une paire de clés (clé publique et clé privée)
$resource = openssl_pkey_new(array(
    'private_key_bits' => 2048,
    'private_key_type' => OPENSSL_KEYTYPE_RSA,
));

openssl_pkey_export($resource, $privateKey);
$publicKey = openssl_pkey_get_details($resource)['key'];

// Fonction pour chiffrer avec la clé publique
function encryptWithPublicKey($data, $publicKey)
{
    openssl_public_encrypt($data, $encrypted, $publicKey);
    return base64_encode($encrypted);
}
$bd = Model::getModel();

$nomTable = 'ANIMAL';

$query = "SELECT * FROM $nomTable";
$req = $bd->prepare($query);
$req->execute();
$donnees = $req->fetchAll(PDO::FETCH_ASSOC);

// Chiffrement des données
foreach ($donnees as &$row) {
    foreach ($row as $colonne => &$valeur) {
        // Chiffrer la valeur de chaque colonne
        $valeurChiffree = encryptWithPublicKey($valeur, $publicKey);
        // Remplacer la valeur dans la table par la valeur chiffrée
        $valeur = base64_encode($valeurChiffree);
    }
}