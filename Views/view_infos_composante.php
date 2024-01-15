<?php
require 'view_begin.php';
require 'view_header.php';
?>
    <div class="composante-container">
        <form action="?controller=<?= $_SESSION['role'] ?>&action=" method="post">
            <div class="infos-composante">
                <h2>Informations composante</h2>
                <div class="form-infos-composante">
                    <input type="text" placeholder="<?= $infos['nom_composante'] ?>" name='composante' id='cpt'
                           class="input-case">
                    <input type="text" placeholder="<?= $infos['nom_client'] ?>" id='sté' name='client'
                           class="input-case">
                </div>
                <h4>Adresse</h4>
                <div class="form-address">
                    <input type="number" placeholder="<?= $infos['numero'] ?>" name="numero-voie"
                           class="input-case form-num-voie">
                    <input type="text" placeholder="<?= $infos['libelle'] ?>" name="type-voie"
                           class="input-case form-type-voie">
                    <input type="text" placeholder="<?= $infos['nom_voie'] ?>" name="nom-voie"
                           class="input-case form-nom-voie">
                </div>
                <div class="form-address">
                    <input type="number" placeholder="<?= $infos['cp'] ?>" name="cp" class="input-case form-cp">
                    <input type="text" placeholder="<?= $infos['ville'] ?>" name="ville" class="input-case form-ville">
                </div>
                <div class="buttons" id="create">
                    <button type="submit">Enregistrer</button>
                </div>
            </div>
        </form>

        <div class="infos-container">
            <div class="infos__colonne">
                <h2>Interlocuteurs</h2>
                <a href="?controller=gestionnaire&action=ajout_interlocuteur_form&id-composante=<?= $_GET['id'] ?>" class="ajout"><i class="fa fa-solid fa-user-plus"></i> &nbsp; Ajouter</a>
                <?php foreach($interlocuteurs as $i): ?>
                    <a href="?controller=gestionnaire&action=infos_personne&id=<?= $i['id_personne']?>" class="block">
                        <h3><?= $i['nom'] . ' ' . $i['prenom'] ?></h3>
                    </a>
                <?php endforeach; ?>
            </div>
            <div class="infos__colonne">
                <h2>Commerciaux</h2>
                <a href="?controller=gestionnaire&action=ajout_commercial_form&id-composante=<?= $_GET['id'] ?>" class="ajout"><i class="fa fa-solid fa-user-plus"></i> &nbsp; Ajouter</a>
                <?php foreach($commerciaux as $c): ?>
                    <a href="?controller=gestionnaire&action=infos_personne&id=<?= $c['id_personne']?>" class="block">
                        <h3><?= $c['nom'] . ' ' . $c['prenom'] ?></h3>
                    </a>
                <?php endforeach; ?>
            </div>
            <div class="infos__colonne">
                <h2>Prestataires</h2>
                <a href="?controller=gestionnaire&action=ajout_prestataire_form&id-composante=<?= $_GET['id'] ?>" class="ajout"><i class="fa fa-solid fa-user-plus"></i> &nbsp; Ajouter</a>
                <?php foreach($prestataires as $p): ?>
                    <a href="?controller=gestionnaire&action=infos_personne&id=<?= $p['id_personne']?>" class="block">
                        <h3><?= $p['nom'] . ' ' . $p['prenom'] ?></h3>
                    </a>
                <?php endforeach; ?>
            </div>
            <div class="infos__colonne">
                <h2>Bons de livraison</h2>
                <a class="ajout"> &nbsp;</a>
                <?php foreach($bdl as $b): ?>
                    <a href="" class="block">
                        <h3><?= $b['nom'] . ' ' . $b['prenom'] ?></h3>
                        <p><?= $b['mois'] ?></p>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php
require 'view_end.php';
?>