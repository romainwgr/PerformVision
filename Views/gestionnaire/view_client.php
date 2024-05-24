<!-- Vue permettant de faire la liste d'un type de personne -->
<?php
require 'Views/view_begin.php';
require 'Views/view_header.php';
?>
<section class="main">
    <div class="main-body">
        <div class="box">
            <form action="<?= $rechercheLink ?>" method="post" class="search_form">
                <input type="checkbox" id="check">
                <div class="search-box">
                    <?php if (!empty($buttonLink)): ?>
                        <button type="button" class="button-primary font"
                            onclick="window.location='<?= htmlspecialchars($buttonLink) ?>'">Ajouter</button>
                    <?php endif; ?>
                    <input type="text" placeholder="Rechercher une <?= strtolower($title) ?>..." value="<?php if (isset($val_rech)) {
                          echo htmlspecialchars($val_rech);
                      } ?>">
                    <label for="check" class="icon">
                        <i class="fas fa-search"></i>
                    </label>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <p>Il y a plus de <span><?= count($person) ?></span> <?= strtolower($title) ?></p>
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
        <?php endif; ?>
    </div>
    </div>

    <?php
    require 'Views/view_end.php';
    ?>