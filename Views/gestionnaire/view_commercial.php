<!-- Vue permettant de faire la liste d'un type de personne -->
<?php
require 'Views/view_begin.php';
require 'Views/view_header.php';
?>
<section class="main">
    <div class="main-body dispa">
        <div class="search-box">
            <form action="<?= $rechercheLink ?>" method="post" class="search_form">
                <input type="text" placeholder="Rechercher un/une <?= strtolower($title) ?>..." value="<?php if (isset($val_rech)) {
                      echo htmlspecialchars($val_rech);
                  } ?>">
                <div class="search-icon">
                    <i class="fas fa-search"></i>
                </div>
                <div class="cancel-icon">
                    <i class="fas fa-times"></i>
                </div>
                <div class="search-data">
                </div>
            </form>
            <?php if (!empty($buttonLink)): ?>
                <button type="button" class="button-primary font"
                    onclick="window.location='<?= htmlspecialchars($buttonLink) ?>'">Ajouter</button>
            <?php endif; ?>
        </div>
    </div>
    <div class="main-body appa">
        <?php if (!empty($buttonLink)): ?>
            <button type="button" class="button-primary font"
                onclick="window.location='<?= htmlspecialchars($buttonLink) ?>'">Ajouter</button>
        <?php endif; ?>
    </div>

    <!-- <h1><?php if (isset($title)) {
        echo $title;
    } ?></h1> -->

    <div class="row">
        <p>Il y a plus de <span><?= count($person) ?></span> <?= strtolower($title) ?></p>
    </div>
    <h1>
        <!-- TODO Binta tu peux mettre une classe qui affiche ca un peu mieux stp -->
        <?php if (isset($message)) {
            echo $message;
        } ?>
    </h1>

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
                            <a href='<?= $cardLink ?>&id=<?php if (isset($p['id_personne'])):
                                  echo htmlspecialchars($p['id_personne']);
                              endif; ?>' class="block">
                                <h2>
                                    <?php
                                    if (array_key_exists('nom', $p)):
                                        echo htmlspecialchars($p['nom'] . ' ' . $p['prenom']);
                                    endif;
                                    ?>
                                </h2>
                            </a>
                            <span>
                                <?php
                                if (array_key_exists('mail', $p)):
                                    echo htmlspecialchars($p['mail']);
                                endif;
                                ?>
                            </span>

                        </div>
                    </div>
                    <div class="job_action">
                        <span>
                            <?php
                            if (array_key_exists('telephone', $p)):
                                echo htmlspecialchars($p['telephone']);
                            endif;
                            ?>
                        </span>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if (
            ((str_contains($_GET['controller'], 'gestionnaire') || str_contains($_GET['controller'], 'administrateur')) && !isset($_GET['id']))
            || ((str_contains($_GET['controller'], 'prestataire') && isset($person[0]['id_bdl'])))
        ): ?>
        <?php endif; ?>
    </div>
    </div>
</section>

<?php
require 'Views/view_end.php';
?>