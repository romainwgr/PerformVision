<div class='dashboard__table'>
    <table>
        <thead>
            <tr>
                <?php foreach ($header as $title): ?>
                    <th><?= $title ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dashboard as $row): ?>
            <tr>
                <?php foreach ($row as $cle => $value): ?>
                <?php if ($cle == 'prenom' or $cle == 'nom'): ?>
                    <td><?= $row['prenom'] . ' ' . $row['nom'] ?></td>
                    <?php break; else: ?>
                    <td><?= $value ?></td>
                <?php endif; endforeach;?>

                <td>
                    <div class="statut vert">L</div>
                    <div class="statut orange">M</div>
                    <div class="statut vert">M</div>
                    <div class="statut orange">J</div>
                    <div class="statut vert">V</div>
                </td>

                <td style="display: flex; justify-content: space-around;">
                    <div style="text-align: center;">
                        <a href="#"><img src="Content/images/icons8-visible-50.png"
                                         style="padding-bottom: 5px;"></a>
                        <p>Consulter</p>
                    </div>

                    <div style="text-align: center;">
                        <a href="#"><img src="/Content/images/icons8-install-58.png"
                                         style="padding-bottom: 5px;"></a>
                        <p>Télécharger</p>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>