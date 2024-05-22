<?php
header('Content-Type: text/html; charset=utf-8');

require 'view_begin.php';
require 'view_header.php';
?>
<section class="main">
    <div class="main-body">
        <!-- Affichage des détails du BDL -->
        <?php if (isset($bdl)): ?>
            <div class="row">
                <p>ID BDL: <?php echo htmlspecialchars($bdl['id_bdl']); ?></p>
                <!-- <p>Nom Prestataire: <?php echo htmlspecialchars($bdl['nom'] . ' ' . $bdl['prenom']); ?></p> -->
                <p>Nom Client: <?php echo htmlspecialchars($bdl['nom_client']); ?></p>
                <p>Nom Composante: <?php echo htmlspecialchars($bdl['nom_composante']); ?></p>
                <p>Mois: <?php echo htmlspecialchars($bdl['mois']); ?></p>
                <!-- Affichez d'autres détails du BDL au besoin -->
            </div>
        <?php else: ?>
            <div class="row">
                <p>Aucun détail disponible pour ce BDL.</p>
            </div>
        <?php endif; ?>

        <!-- Afficher le PDF directement -->
        <div class="pdf-container">
            <h2>PDF du BDL</h2>
            <?php if (isset($pdf_content)): ?>
                <embed src="data:application/pdf;base64,<?php echo base64_encode($pdf_content); ?>" type="application/pdf"
                    width="100%" height="600px" />
            <?php else: ?>
                <p>Le PDF n'a pas pu être généré.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php
require 'view_end.php';
?>