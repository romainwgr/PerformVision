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
            <input type="text" id="" name="recherche" placeholder="Rechercher des <?= strtolower($title) ?>..." value="<?php if(isset($val_rech)){echo $val_rech;}?>">
             <button type="submit">Rechercher</button>
        </form> 
        
    </div>

    <div class="element-block">
    <?php if (is_string($person)): ?>
            <p class=""><?= htmlspecialchars($person); ?></p>
        <?php elseif (isset($person) && !empty($person)): ?>
    <?php foreach ($person as $p): ?>
        <!-- Modification de else echo id a else echo id_personne -->
        <a href='<?= $cardLink ?>&id=<?php if (isset($p['id_personne'])){ echo $p['id_personne'];} ?>'
           class="block">
            <h2><?php
                if (array_key_exists('nom', $p)): echo $p['nom'] . ' ' . $p['prenom']; endif;
                if (array_key_exists('mail', $p)): echo $p['mail']; endif;
                if (array_key_exists('telephone', $p)): echo $p['telephone']; endif;
                ?></h2>
            <h3>
                <!-- 
                    Clique sur voir bdl ca retourne une page du bdl de la personne grace a son id
                    Sinon mettre aucun Bon de livraison
             -->
                <P>Voir BDL</P>
                <?php
                // if (array_key_exists('id_bdl', $p)): echo $p['mois']; endif;
                // if (array_key_exists('nom_client', $p) && !array_key_exists('telephone_client', $p)): echo $p['nom_client']; endif;
                // if (array_key_exists('nom_composante', $p) && !array_key_exists('nom_client', $p)): echo $p['nom_composante']; endif;
                // if (array_key_exists('telephone_client', $p)): echo $p['telephone_client']; endif;
                // ?></h3>
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
require 'Views/view_end.php';
?>
