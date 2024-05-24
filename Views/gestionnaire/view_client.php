<!-- Vue permettant de faire la liste d'un type de personne -->
<?php
require 'Views/view_begin.php';
require 'Views/view_header.php';
?>
<section class="main">
    <div class="main-body">
        <div class="search_bar">
<<<<<<< HEAD
            <form action="#" method="GET" class="search_form">
                <input type="search" name="search" id="search" class="search_input" placeholder="Search here...">
=======
            <form method="post" action="<?= $rechercheLink ?>" class="search_form">
                <input type="text" name="recherche" placeholder="Rechercher une <?= strtolower($title) ?>..." value="<?php if (isset($val_rech)) { echo htmlspecialchars($val_rech); } ?>" class="search_input">
>>>>>>> d33881af4552de5d4bdb1c89b72e6b2c95a40c4b
                <button type="submit" class="search_button">
                    <i class="fas fa-search"></i>
                </button>
            </form>
<<<<<<< HEAD
            <?php if (!empty($buttonLink)): ?>
                <button type="button" class="button-primary"
                    onclick="window.location='<?= htmlspecialchars($buttonLink) ?>'">Ajouter</button>
            <?php endif; ?>
        </div>

        <div class="row">
            <p>Il y a plus de <span><?= count($person) ?></span> entrées</p>
        </div>

        <div class="element-block">
            <?php if (is_string($person)): ?>
                <p class=""><?= htmlspecialchars($person); ?></p>
            <?php elseif (isset($person) && !empty($person)): ?>
                <?php foreach ($person as $p): ?>
                    <div class="job_card">
                        <div class="job_details">
                            <div class="img">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="text">
                                <a href='<?= $cardLink ?>&id=<?php if (isset($p['id_client'])):
                                      echo htmlspecialchars($p['id_client']);
                                  endif; ?>' class="block">
                                    <h2>
                                        <?php if (array_key_exists('nom_client', $p) && array_key_exists('telephone_client', $p)): ?>
                                            <?= htmlspecialchars($p['nom_client']); ?>
                                        <?php endif; ?>
                                    </h2>
                                </a>
                                <span>
                                    <?php if (array_key_exists('telephone_client', $p)): ?>
                                        <?= htmlspecialchars($p['telephone_client']); ?>
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
=======
        </div>

        <div class="element-block">
            <?php if (is_string($person)): ?>
                <p class=""><?= htmlspecialchars($person); ?></p>
            <?php elseif (isset($person) && !empty($person)): ?>
                <?php foreach ($person as $p): ?>
                    <a href='<?= $cardLink ?>&id=<?php if (isset($p['id_client'])){echo htmlspecialchars($p['id_client']);}?>' class="block">
                        <div class="job_details">
                            <div class="img">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="text">
                                <h2>
                                    <?php
                                    if (array_key_exists('nom_client', $p) && array_key_exists('telephone_client', $p)): echo htmlspecialchars($p['nom_client']); endif;
                                    ?>
                                </h2>
                                <h3>
                                    <?php
                                    if (array_key_exists('telephone_client', $p)): echo htmlspecialchars($p['telephone_client']); endif;
                                    ?>
                                </h3>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (((str_contains($_GET['controller'], 'gestionnaire') || str_contains($_GET['controller'], 'administrateur')) && !isset($_GET['id']))
                || ((str_contains($_GET['controller'], 'prestataire') && isset($person[0]['id_bdl'])))): ?>
                <button type="submit" class="button-primary" onclick="window.location='<?= htmlspecialchars($buttonLink) ?>'">Ajouter</button>
>>>>>>> d33881af4552de5d4bdb1c89b72e6b2c95a40c4b
            <?php endif; ?>
        </div>
    </div>
</section>

<?php
require 'Views/view_end.php';
?>