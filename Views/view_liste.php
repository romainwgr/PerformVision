<?php
require 'view_begin.php';
require 'view_header.php';
?>
<div class='liste-prestataire-contrainer'>
    <h1><?= $title ?> </h1>
    <div class="element-recherche">
        <input type="text" id="recherche" name="recherche" placeholder="Rechercher un <?= $title ?>...">
        <button type="submit" class="button-primary">Ajouter</button>
        <button type="submit" class="button-delete">Supprimer</button>
    </div>

    <div class="element-block">
        <?php foreach ($person as $p): ?>
            <div class="block">
                <h2><?= $p['nom'] . ' ' . $p['prenom'] ?></h2>
                <h3><?php
                    if (isset($p['interne'])): if($p['interne']): echo 'Interne'; else: echo 'Indépendant'; endif; endif;
                    if (isset($p['nom_client'])): echo $p['nom_client']; endif;
                    ?></h3>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php
require 'view_end.php';
?>
