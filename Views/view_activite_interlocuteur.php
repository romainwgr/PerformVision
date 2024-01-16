<?php
require 'view_begin.php';
require 'view_header.php';
?>
<div class="bdl-container">
    <table class="bdl-table">
        <thead>
            <tr>
                <th>Date</th>
                <th><?= $bdl['type_bdl']?></th>
                <th>Commentaire</th>
                <!-- Ajoute ici d'autres entêtes de colonnes selon tes besoins -->
            </tr>
        </thead>
        <tbody id="joursTableBody">
            <!-- Les lignes pour les jours seront ajoutées dynamiquement ici -->
            <!-- $tab = ["date","infos-jour","commentaire"] -->
            <!-- $tab -->
            <?php foreach($tab["infos-jour"] as $activite) :?>
                <tr>
                    <th><?$tab["date"]?></th>
                    <th><?$activite?></th>
                    <th><input type="text" placeholder="<?$tab["commentaire"]?>"></th>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <button type="button" onclick="getTableData()" id="button-get-data">Valider</button>
</div>
<?php
require 'view_end.php';
?>
