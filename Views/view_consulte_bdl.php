<?php
require 'view_begin.php';
require 'view_header.php';
?>
<div class="bdl-container">
    <table class="bdl-table">
        <thead>
            <tr>
                <th>Date</th>
                <th><?= $bdl['type_bdl'] ?></th>
                <th>Commentaire</th>
            </tr>
        </thead>
        <tbody id="joursTableBody">
            <?php foreach ($activites as $activite) : ?>
                <tr>
                    <td><?= $activite["date_bdl"] ?></td>
                    <td><?php
                        if (isset($activite['journee'])): if($activite['journee']): echo 'Présent';
                        else: echo 'Absent'; endif; endif;
                        if (isset($activite['nb_heure'])): echo $activite['nb_heure']; endif;
                        if (isset($activite['nb_demi_journee'])): echo $activite['nb_demi_journee']; endif;
                        ?></td>
                    <td><?= $activite["commentaire"] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <button type="submit" id="button-get-data">Valider</button>
</div>
<?php
require 'view_end.php';
?>
