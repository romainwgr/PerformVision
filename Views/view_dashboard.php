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
                        <?php endif; endforeach; ?>

                    <td style="display: flex; justify-content: space-around;">
                        <div style="text-align: center;">
                            <a href="#"><i class="fa fa-eye" aria-hidden="true"></i></a>
                            <p>Consulter</p>
                        </div>

                        <div style="text-align: center;">
                            <a href="#"><i class="fa fa-download" aria-hidden="true"></i></a>
                            <p>Télécharger</p>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>