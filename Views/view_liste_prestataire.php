<?php
require 'view_begin.php';

require 'view_header.php';
$prestataire = [['nom'=>'Dupont', 'prenom' => 'Jean', 'interne'=>'t'],
    ['nom'=>'Dupont', 'prenom' => 'Jean', 'interne'=>'f'],
    ['nom'=>'Dupont', 'prenom' => 'Jean', 'interne'=>'t'],
    ['nom'=>'Dupont', 'prenom' => 'Jean', 'interne'=>'f'],
    ['nom'=>'Dupont', 'prenom' => 'Jean', 'interne'=>'t'],
    ['nom'=>'Dupont', 'prenom' => 'Jean', 'interne'=>'t']];
?>
<div class='liste-prestataire-contrainer'>
    <h1> Prestataire </h1>
    <div class="element-recherche">
        <input type="text" id="recherche" name="recherche" placeholder="Rechercher un Prestataire...">
        <button type="submit" id="ajouter">Ajouter prestataire</button>
        <button type="submit" id="supprimer">Supprimer</button>
    </div>

    <div class="element-block">
        <?php foreach ($prestataire as $p): ?>
        <div class="block">
            <h2><?= $p['nom'] . ' ' . $p['prenom'] ?></h2>
            <h3><?= $p['interne'] == 't' ? 'Interne' : 'Indépendant' ?></h3>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php
require 'view_end.php';
?>
