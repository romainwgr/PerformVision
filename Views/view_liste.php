<!-- Vue permettant de faire la liste d'un type de personne -->
<?php
require 'view_begin.php';
require 'view_header.php';
?>
<div class='liste-prestataire-contrainer'>
    <h1><?= $title ?> </h1>
    <div class="element-recherche">
        <!-- J'ai mis en commentaire la fonction de recherche car elle n'est pas essentielle 
        et on a besoin de bcp de temps pour la mettre en place 
        (mettre une variable $rechercheLink avec le lien de l'action pour rechercher dans le tableau $data pour chaque action qui render liste et dans chaque controller)
    
        -->
        <!-- <form method="post" action="?= $rechercheLink?>">
            <input type="text" id="" name="recherche" placeholder="Rechercher un ?= $title ?>..." value="?php if(isset($val_rech)){echo $val_rech;}?>">
             <button type="submit">Rechercher</button>
        </form> -->
        
    </div>

    <div class="element-block">
    <?php if (is_string($person)): ?>
            <p class=""><?= htmlspecialchars($person); ?></p>
        <?php elseif (isset($person) && !empty($person)): ?>
        
    <?php foreach ($person as $p): ?>
        <a href='<?= $cardLink ?>&id=<?php if (isset($p['id_bdl'])): echo $p['id_bdl']; else: echo $p['id']; endif; ?>'
           class="block">
            <h2><?php
                if (array_key_exists('id_bdl', $p)): echo $p['nom_mission']; endif;
                if (array_key_exists('nom', $p)): echo $p['nom'] . ' ' . $p['prenom']; endif;
                if (array_key_exists('nom_client', $p) && array_key_exists('telephone_client', $p)): echo $p['nom_client']; endif;
                if (array_key_exists('nom_composante', $p) && array_key_exists('nom_client', $p)): echo $p['nom_composante']; endif;
                ?></h2>
            <h3><?php
                if (array_key_exists('id_bdl', $p)): echo $p['mois']; endif;
                if (array_key_exists('interne', $p)): echo $p['interne'] ? 'Interne' : 'Indépendant'; endif;
                if (array_key_exists('nom_client', $p) && !array_key_exists('telephone_client', $p)): echo $p['nom_client']; endif;
                if (array_key_exists('nom_composante', $p) && !array_key_exists('nom_client', $p)): echo $p['nom_composante']; endif;
                if (array_key_exists('telephone_client', $p)): echo $p['telephone_client']; endif;
                ?></h3>
        </a>
    <?php endforeach; ?>

<?php endif; ?>
<?php if (((str_contains($_GET['controller'], 'gestionnaire') || str_contains($_GET['controller'], 'administrateur')) && !isset($_GET['id']))
            || ((str_contains($_GET['controller'], 'prestataire') && isset($person[0]['id_bdl'])))): ?>
            <button type="submit" class="button-primary"
                    onclick="window.location='<?= $buttonLink ?>'">Ajouter
            </button>
        <?php endif; ?>
    </div>
</div>

<?php
require 'view_end.php';
?>
