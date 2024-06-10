<!-- Vue permettant de voir les informations d'une composante -->
<?php
require 'view_begin.php';
require 'view_header.php';
?>
<section class="main">
    <div class="composante-container">
        <h2 class="marge">Informations composante</h2>
        <div class="job_card">
            <div class="job_details">
                <div class="img">
                    <i class="fas fa-building"></i>
                </div>
                <div class="text">
                    <h2><?= $infos['nom_composante'] ?></h2>
                    <span><?= $infos['nom_client'] ?></span>
                </div>
            </div>
            <div class="job_action">
            </div>
        </div>
    </div>

    <div class="infos-container">
        <div class="infos__colonne">
            <h2>Interlocuteurs</h2>

            <table>
                <tr>
                    <?php if (!empty($interlocuteurs) && is_array($interlocuteurs)): ?>
                        <?php foreach ($interlocuteurs as $i): ?>
                            <td>
                                <a href='?controller=<?= $_SESSION['role'] ?>&action=infos_personne&id=<?= $i['id_personne'] ?>'
                                    class="block">
                                    <h3><?= $i['nom'] . ' ' . $i['prenom'] ?></h3>
                                </a>
                            </td>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <table class="diffencice">
                            <tr>
                                <td>
                                    <p>Aucun Interlocuteurs trouvé.</p>
                                </td>
                            </tr>
                        </table>
                    <?php endif; ?>
                </tr>
            </table>

        </div>

        <div class="infos__colonne">
            <h2>Commerciaux</h2>
            <?php if (!str_contains($_GET['controller'], 'commercial')): ?>
                <a href="<?= $cardLink ?>&action=ajout_commercial_form&id=<?= $_GET['id'] ?>" class="ajout">
                    <i class="fa fa-solid fa-user-plus"></i> &nbsp; Ajouter
                </a>
            <?php else: ?>
                <a class="ajout"> &nbsp;</a>
            <?php endif; ?>
            <table>
                <tr>
                    <?php if (!empty($commerciaux) && is_array($commerciaux)): ?>
                        <?php foreach ($commerciaux as $c): ?>
                            <td>
                                <a href='?controller=<?= $_GET['controller'] ?>&action=infos_personne&id=<?= $c['id_personne'] ?>'
                                    class="block">
                                    <h3><?= $c['nom'] . ' ' . $c['prenom'] ?></h3>
                                </a>
                            </td>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <table class="diffencice">
                            <tr>
                                <td>
                                    <p>Aucun Commerciaux trouvé.</p>
                                </td>
                            </tr>
                        </table>
                    <?php endif; ?>
                </tr>
            </table>

        </div>

        <div class="infos__colonne">
            <h2>Prestataires</h2>
            <?php if (!str_contains($_GET['controller'], 'commercial')): ?>
                <a href="?controller=<?= $_GET['controller'] ?>&action=ajout_prestataire_form&id=<?= $_GET['id'] ?>"
                    class="ajout">
                    <i class="fa fa-solid fa-user-plus"></i> &nbsp; Ajouter
                </a>
            <?php else: ?>
                <a class="ajout"> &nbsp;</a>
            <?php endif; ?>
            <table>
                <tr>
                    <?php
                    $uniquePrestataires = [];
                    if (!empty($prestataires) && is_array($prestataires)): ?>
                        <?php foreach ($prestataires as $p):
                            $nom = htmlspecialchars($p['nom'] . ' ' . $p['prenom']);
                            if (!in_array($nom, $uniquePrestataires)) {
                                $uniquePrestataires[] = $nom; ?>
                                <td>
                                    <a href="?controller=<?= htmlspecialchars($_GET['controller']) ?>&action=infos_personne&id=<?= htmlspecialchars($p['id_personne']) ?>"
                                        class="block">
                                        <h3><?= $nom; ?></h3>
                                    </a>
                                </td>
                            <?php }endforeach; ?>
                    <?php else: ?>
                        <table class="diffencice">
                            <tr>
                                <td>
                                    <p>Aucun prestataires trouvé.</p>
                                </td>
                            </tr>
                        </table>
                    <?php endif; ?>
                </tr>
            </table>

        </div>

        <div class="infos__colonne">
            <h2>Bons de livraison</h2>
            <table>
                <tr>
                    <?php if (isset($b['id_bdl'], $b['nom'], $b['prenom'], $b['mois'])): ?>
                        <?php foreach ($bdl as $b): ?>
                            <td>
                                <a href="?controller=<?= $_GET['controller'] ?>&action=consulter_bdl&id=<?= $b['id_bdl'] ?>"
                                    class="block">
                                    <h3><?= htmlspecialchars($b['nom']) . ' ' . htmlspecialchars($b['prenom']) . '<br>' . htmlspecialchars($b['mois']) ?>
                                    </h3>
                                </a>
                            </td>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <table class="diffencice">
                            <tr>
                                <td>
                                    <p>Aucun bons de livraison pour ce composante</p>
                                </td>
                            </tr>
                        </table>
                    <?php endif; ?>
                </tr>
            </table>
        </div>
    </div>
    </div>
</section>

<?php
require 'view_end.php';
?>