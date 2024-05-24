<!-- Vue permettant de faire la liste d'un type de personne -->
<?php
require 'Views/view_begin.php';
require 'Views/view_header.php';
?>
<div class='liste-prestataire-contrainer'>
    <h1><?= $title ?> </h1>
    <div class="element-recherche">
        <!-- J'ai mis en commentaire la fonction de recherche car elle n'est pas essentielle 
        et on a besoin de bcp de temps pour la mettre en place 
        (mettre une variable $rechercheLink avec le lien de l'action pour rechercher dans le tableau $data pour chaque action qui render liste et dans chaque controller)
    
        -->
        <form method="post" action="<?= $rechercheLink?>">
            <input type="text" id="" name="recherche" placeholder="Rechercher une <?= strtolower($title) ?>..." value="<?php if(isset($val_rech)){echo $val_rech;}?>">
             <button type="submit">Rechercher</button>
        </form> 
        
    </div>

    <div class="element-block">
    <?php if (is_string($person)): ?>
            <p class=""><?= htmlspecialchars($person); ?></p>
        <?php elseif (isset($person) && !empty($person)): ?>
    <?php foreach ($person as $p): ?>
        <!-- Modification de else echo id a else echo id_personne -->
        <a href='<?= $cardLink ?>&id=<?php if (isset($p['id_client'])){echo $p['id_client'];}?>'
           class="block">
            <h2><?php
                if (array_key_exists('nom_client', $p) && array_key_exists('telephone_client', $p)): echo $p['nom_client']; endif;
                ?></h2>
            <h3><?php
                if (array_key_exists('telephone_client', $p)): echo $p['telephone_client']; endif;
                ?></h3>
        </a>
    <?php endforeach; ?>

<?php endif; ?>
<?php if (((strstr($_GET['controller'], 'gestionnaire') || strstr($_GET['controller'], 'administrateur')) && !isset($_GET['id']))
            || ((strstr($_GET['controller'], 'prestataire') && isset($person[0]['id_bdl'])))): ?>
            <button type="submit" class="button-primary"
                    onclick="window.location='<?= $buttonLink ?>'">Ajouter
            </button>
        <?php endif; ?>
    </div>
</div>

<?php
require 'Views/view_end.php';
?>
