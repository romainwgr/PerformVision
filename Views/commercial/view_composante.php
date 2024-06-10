<!-- Vue permettant de faire la liste des composantes avec leurs interlocuteurs -->
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
    <h1><?php if (isset($title)) {
        echo $title;
    } ?></h1>
    <div class="row">
        <p>Il y a plus de <span><?= count($person) ?></span> <?= strtolower($title) ?></p>
    </div>
    <?php if (isset($message)) {
        echo $message;
    } ?>

    <div class="element-block">
        <?php if (is_string($person)): ?>
            <p class=""><?= htmlspecialchars($person); ?></p>
        <?php elseif (isset($person) && !empty($person)): ?>
            <?php foreach ($person as $client): ?>
                <h2 class="client-title">Client: <?= htmlspecialchars($client['nom_client']); ?></h2>
                <?php foreach ($client['composantes'] as $composante): ?>
                    <div class="composante-block job_card">
                        <div class="composante-details job_details">
                            <div class="img">
                                <i class="fas fa-cube"></i>
                            </div>
                            <div class="text">
                                <h2><?= htmlspecialchars($composante['nom_composante']); ?>
                                </h2>
                                <?php if (!empty($composante['interlocuteurs'])): ?>
                                    <a href='<?= $cardLink ?>&id=<?= htmlspecialchars($composante['id_composante']); ?>'
                                        class="action_link block">
                                        <h4>Voir détails</h4>
                                    </a>
                                    <!-- <ul class="interlocuteurs-list">
                                            <?php foreach ($composante['interlocuteurs'] as $interlocuteur): ?>
                                                <li><?= htmlspecialchars($interlocuteur['nom'] . ' ' . $interlocuteur['prenom']); ?></li>
                                            <?php endforeach; ?>
                                        </ul> -->
                                <?php else: ?>
                                    <p>Aucun interlocuteur trouvé pour cette composante.</p>
                                <?php endif; ?>


                            </div>
                        </div>
                        <div class="job_action">
                            <span>Adresse: <?= htmlspecialchars($composante['adresse']) ?></span><br>
                            <span>Code postal: <?= htmlspecialchars($composante['code_postal']) ?></span><br>
                            <?php
                            $uniqueinterlocuteurs = [];
                            ?>

                            <span>Interlocuteurs:
                                <!-- <select>
                                    <?php foreach ($composante['interlocuteurs'] as $interlocuteur): ?>
                                        <option><?= htmlspecialchars($interlocuteur['nom'] . ' ' . $interlocuteur['prenom']); ?></option>
                                    <?php endforeach; ?>
                                </select> -->
                                <select>
                                    <?php foreach ($composante['interlocuteurs'] as $interlocuteur): ?>
                                        <?php
                                        $nom = htmlspecialchars($interlocuteur['nom'] . ' ' . $interlocuteur['prenom']);
                                        if (!in_array($nom, $uniqueinterlocuteurs)) {
                                            $uniqueinterlocuteurs[] = $nom;
                                            ?>
                                            <option><?= $nom; ?></option>
                                        <?php } ?>
                                    <?php endforeach; ?>
                                </select>
                            </span>
                        </div>
                        <div class="job_action">
                            <button type="button" class="button-primary"
                                onclick="window.location='<?= htmlspecialchars($buttonLink) ?>'">Ajouter</button>
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