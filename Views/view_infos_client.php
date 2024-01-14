<?php
require 'view_begin.php';
require 'view_header.php';
?>
    <div class="composante-container">
        <form action="?controller=<?= $_SESSION['role'] ?>&action=" method="post">
            <div class="infos-composante">
                <h2>Informations Société</h2>
                <div class="form-infos-composante">
                    <input type="text" placeholder="<?= $infos['nom_client'] ?>" name='composante' id='cpt'
                           class="input-case">
                    <input type="number" placeholder="<?= $infos['telephone_client'] ?>" id='sté' name='client'
                           class="input-case">
                </div>
                <div class="buttons" id="create">
                    <button type="submit">Enregistrer</button>
                </div>
            </div>
        </form>

        <div class="infos-container">
            <div class="infos__colonne">
                <h2>Interlocuteurs</h2>
                <a href=""><i class="fa fa-solid fa-user-plus"></i> &nbsp; Ajouter</a>
                <?php foreach($interlocuteurs as $i): ?>
                    <div class="block">
                        <h3><?= $i['nom'] . ' ' . $i['prenom'] ?></h3>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="infos__colonne">
                <h2>Composantes</h2>
                <a href="?controller=gestionnaire&action=ajout_composante_form"><i class="fa fa-solid fa-user-plus"></i> &nbsp; Ajouter</a>
                <?php foreach($composantes as $c): ?>
                    <div class="block">
                        <h3><?= $c['nom_composante'] ?></h3>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php
require 'view_end.php';
?>