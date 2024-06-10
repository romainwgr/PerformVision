<?php
require 'Views/view_begin.php';
require 'Views/view_header.php';
?>

<div class="add-container">
    <div class="form-abs">
        <div class="step form-step form-step-active">
            <h2>Informations prestataire</h2>

            <hr>
            <?php if (isset($prestataire) && !empty($prestataire)): ?>
                <form action="<?= htmlspecialchars($form) ?>" method="post">
                    <input type="hidden" name="composante" value="<?= htmlspecialchars($composante['id_composante']) ?>">
                    <?php foreach ($prestataire as $p): ?>
                        <div class="job_card commercial-item">
                            <div class="job_details">
                                <div class="img">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="text">
                                    <a href='<?= $cardLink ?>&id=<?php if (isset($p['id_personne'])):
                                          echo htmlspecialchars($p['id_personne']);
                                      endif; ?>' class="block">
                                        <h2>
                                            <?php if (array_key_exists('nom', $p)):
                                                echo htmlspecialchars($p['nom'] . ' ' . $p['prenom']);
                                            endif; ?>
                                        </h2>
                                    </a>
                                    <span>
                                        <?php if (array_key_exists('mail', $p)):
                                            echo htmlspecialchars($p['mail']);
                                        endif; ?>
                                    </span>
                                </div>
                            </div>
                            <div class="job_action">
                                <input type="checkbox" name="id_personne[]" class="select-commercial large-checkbox" value="<?php if (isset($p['id_personne'])):
                                    echo htmlspecialchars($p['id_personne']);
                                endif; ?>">
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div id="commercial-error" class="error-message" style="color: red; display: none;"></div>
                    <button type="submit" class="next-btn btn">Créer</button>
                </form>
            <?php else: ?>
                <p>Aucun prestataire à ajouter.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
require 'Views/view_end.php';
?>