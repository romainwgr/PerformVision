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

    <div class="row">
        <p>Il y a <span><?= isset($person) && is_array($person) ? count($person) : 0 ?></span>
            <?= htmlspecialchars($title) ?></p>
    </div>
    <div id="errorMessage" style="display: none;"></div>

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
                        <h2>
                            <?= htmlspecialchars($p['nom'] . ' ' . $p['prenom']); ?>
                        </h2>
                        <span>
                            <?= htmlspecialchars($p['mail']); ?>
                        </span>
                    </div>
                </div>
                <div class="job_action">
                    <span>
                        <?php if (isset($p['telephone'])): ?>
                            <?= htmlspecialchars($p['telephone']); ?>
                        <?php endif; ?>
                    </span>
                </div>
                <div class="job_salary">
                    <?php if (isset($p['id_personne'])): ?>
                        <a
                            href='?controller=interlocuteur&action=consulterAbsencesPrestataire&id_prestataire=<?= htmlspecialchars($p['id_personne']) ?>'>
                            <i class="fa fa-eye" aria-hidden="true"></i>
                            <span>Absences</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if (
        ((strstr($_GET['controller'], 'gestionnaire') || strstr($_GET['controller'], 'administrateur')) && !isset($_GET['id']))
        || ((strstr($_GET['controller'], 'prestataire') && isset($person[0]['id_bdl'])))
    ): ?>
        <!-- Ajoutez du contenu ici si nécessaire -->
    <?php endif; ?>
</section>

<script>
    <?php if (isset($bdl) && count($bdl) == 0): ?>
        document.getElementById('errorMessage').innerHTML = 'Aucun prestataires trouvé pour cet ID.';
        document.getElementById('errorMessage').style.display = 'block';
    <?php endif; ?>
</script>

<?php require 'Views/view_end.php'; ?>