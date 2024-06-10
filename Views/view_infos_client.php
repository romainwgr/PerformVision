<!-- Vue permettant de voir les informations de la société -->
<?php
require 'view_begin.php';
require 'view_header.php';
?>

<!-- Vue pour afficher les interlocuteurs et les composantes -->
<section class="main">
    <div class="main-body">
        <div class="composante-container">
            <h2 class="marge">Informations sur la Société</h2>
            <div class="job_card">
                <div class="job_details">
                    <div class="img">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="text">
                        <h2><?= $infos['nom_client'] ?></h2>
                        <span><?= $infos['telephone_client'] ?></span>
                    </div>
                </div>
                <div class="job_action">
                    <!-- Actions supplémentaires peuvent être ajoutées ici si nécessaire -->
                </div>
            </div>
        </div>

        <div class="infos-container">
            <div class="infos__colonne">
                <h2>Interlocuteurs</h2>
                <?php if (!empty($interlocuteurs) && is_array($interlocuteurs)): ?>
                    <table>
                        <tr>
                            <?php foreach ($interlocuteurs as $i): ?>
                                <td>
                                    <a href="?controller=<?= $_GET['controller'] ?>&action=infos_personne&id=<?= $i['id_personne'] ?>"
                                        class="block">
                                        <h3><?= $i['nom'] . ' ' . $i['prenom'] ?></h3>
                                    </a>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    </table>
                <?php else: ?>
                    <p>Aucun interlocuteur trouvé.</p>
                <?php endif; ?>
            </div>
            <div class="infos__colonne">
                <h2>Composantes</h2>
                <?php if (!empty($composantes) && is_array($composantes)): ?>
                    <table>
                        <tr>
                            <?php foreach ($composantes as $c): ?>
                                <td>
                                    <a href='?controller=<?= $_GET['controller'] ?>&action=infos_composante&id=<?= $c['id_composante'] ?>'
                                        class="block">
                                        <h3><?= htmlspecialchars($c['nom_composante']) ?></h3>
                                    </a>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    </table>
                <?php else: ?>
                    <p>Aucun composante trouvé.</p>
                <?php endif; ?>
                <?php if (!str_contains('commercial', $_GET['controller'])): ?>
                    <a href="?controller=<?= $_GET['controller'] ?>&action=ajout_autre_composante&client=<?= $infos['id_client'] ?>"
                        class="ajout"><i class="fa fa-solid fa-user-plus"></i>
                        &nbsp; Ajouter composante</a>
                <?php else: ?>
                    <a href="" class="ajout"></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php require 'view_end.php'; ?>