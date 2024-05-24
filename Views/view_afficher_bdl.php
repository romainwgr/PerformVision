<?php
header('Content-Type: text/html; charset=utf-8');

require 'view_begin.php';
require 'view_header.php';
?>
<section class="main">
    <div class="main-body">
        <!-- Affichage des détails du BDL -->
       

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