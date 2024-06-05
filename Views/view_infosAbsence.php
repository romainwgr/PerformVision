<?php
require 'view_begin.php';
require 'view_header.php';
?>

<div class="add-container">
    <div class="form-abs">
        <h1>Détails de l'absence</h1>
        <?php if (!empty($absence)): ?>
            <form action="?controller=prestataire&action=absence" method="post" class="form">
                <div class="form-names ensemble">
                    <div class="input-group">
                        <label for="prenom">Prénom :</label>
                        <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($absence['prenom']) ?>"
                            class="input-case" disabled>
                    </div>
                    <div class="input-group">
                        <label for="nom">Nom :</label>
                        <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($absence['nom']) ?>"
                            class="input-case" disabled>
                    </div>
                </div><br>
                <div class="input-group">
                    <label for="date_absence">Date d'absence :</label>
                    <?php
                    // Convertit la date d'absence au format j-m-a
                    $date_absence = date("j-m-Y", strtotime($absence['date_absence']));
                    ?>
                    <input type="text" id="date_absence" name="date_absence" value="<?= htmlspecialchars($date_absence) ?>"
                        class="input-case" disabled>
                </div>
                <div class="input-group">
                    <label for="motif">Motif :</label>
                    <textarea id="motif" name="motif" class="input-case"
                        disabled><?= htmlspecialchars($absence['motif']) ?></textarea>
                </div>
                <div class="btns-group">
                    <a href="?controller=prestataire&action=absence" class="btn">Retour</a>
                </div>
            </form>
        <?php else: ?>
            <p>Absence non trouvée.</p>
        <?php endif; ?>
    </div>
</div>

<?php
require 'view_end.php';
?>