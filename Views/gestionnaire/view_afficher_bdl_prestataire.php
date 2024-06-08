<?php
require 'Views/view_begin.php';
require 'Views/view_header.php';
?>
<section class="main">
    <div class="main-body">

        <div class="row">
            <p>Il y a plus de <span><?= count($bdls) ?></span> bdl</p>
        </div>
        <div class="element-block">
            <?php if (!empty($bdls)): ?>
                <?php foreach ($bdls as $bdl): ?>
                    <div class="job_card">
                        <div class="job_details">
                            <div class="img">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="text">
                                <h2>BDL N°: <?= htmlspecialchars($bdl['id_bdl']) ?></h2>
                                <span>Mois: <?= htmlspecialchars($bdl['mois']) ?></span><br>
                            </div>
                        </div>
                        <div class="job_salary">
                            <a
                                href='?controller=gestionnaire&action=afficher_bdl&id_bdl=<?= htmlspecialchars($bdl['id_bdl'] ?? $bdl['id']) ?>'>
                                <i class="fa fa-eye" aria-hidden="true"></i>
                                <span>Voir PDF</span>
                            </a>

                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?php
        require 'Views/view_end.php';
        ?>