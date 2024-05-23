<!-- Vue permettant de faire la liste des composantes avec leurs prestataires -->
<?php
require 'Views/view_begin.php';
require 'Views/view_header.php';
?>
<section class="main">
    <div class="main-body">
        <div class="search_bar">
            <form method="post" action="<?= $rechercheLink ?>" class="search_form">
                <input type="text" name="recherche" placeholder="Rechercher des <?= strtolower($title) ?>..." value="<?php if (isset($val_rech)) { echo htmlspecialchars($val_rech); } ?>" class="search_input">
                <button type="submit" class="search_button">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>

        <div class="element-block">
            <?php if (is_string($person)): ?>
                <p class=""><?= htmlspecialchars($person); ?></p>
            <?php elseif (isset($person) && !empty($person)): ?>
                <?php foreach ($person as $client): ?>
                    <h2>Client: <?= htmlspecialchars($client['nom_client']); ?></h2>
                    <?php foreach ($client['composantes'] as $composante): ?>
                        <div class="composante-block">
                            <div class="composante-details">
                                <div class="img">
                                    <i class="fas fa-cube"></i>
                                </div>
                                <div class="text">
                                    <h3>Composante: <?= htmlspecialchars($composante['nom_composante']); ?></h3>
                                    <?php if (!empty($composante['prestataires'])): ?>
                                        <ul>
                                            <?php foreach ($composante['prestataires'] as $prestataire): ?>
                                                <li><?= htmlspecialchars($prestataire['nom'] . ' ' . htmlspecialchars($prestataire['prenom'])); ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        <p>Aucun prestataire trouvé pour cette composante.</p>
                                    <?php endif; ?>
                                    <a href='<?= $cardLink ?>&id=<?= htmlspecialchars($composante['id_composante']); ?>' class="block">
                                        <h4>Voir détails</h4>
                                    </a>
                                    <?php if (((str_contains($_GET['controller'], 'gestionnaire') || str_contains($_GET['controller'], 'administrateur')) && !isset($_GET['id']))): ?>
                                        <button type="submit" class="button-primary" onclick="window.location='<?= htmlspecialchars($buttonLink) ?>'">Ajouter</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php
require 'Views/view_end.php';
?>
