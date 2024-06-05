<?php
require 'view_begin.php';
require 'view_header.php';
?>

<section class="main">
    <div class="main-body">
        <div class="search-box">
            <form action="<?= $rechercheLink ?>" method="post" class="search_form">
                <input name="recherche" type="text" placeholder="Rechercher une absence..." value="<?php if (isset($val_rech)) {
                    echo htmlspecialchars($val_rech);
                } ?>">
                <div class="search-icon">
                    <i class="fas fa-search"></i>
                </div>
                <div class="cancel-icon">
                    <i class="fas fa-times"></i>
                </div>
                <div class="search-data"></div>
            </form>
            <a href="#caheaffiche" class="job-card-link">
                <button type="button" class="button-primary font">
                    Ajouter
                </button>
            </a>
        </div>
    </div>
    <?php
    // Fonction de comparaison pour trier les absences par leur ID
    function compareAbsences($a, $b)
    {
        return $a['id'] - $b['id'];
    }

    // Trier les absences par leur ID
    usort($absences, 'compareAbsences');
    ?>

    <div class="row">
        <p>Il y a plus de <span><?= isset($absences) && is_array($absences) ? count($absences) : 0 ?></span> absences
            déclarées</p>
    </div>
    <h1>
        <!-- < TODO Binta tu peux mettre une classe qui affiche ca un peu mieux stp -- -->
        <?php if (isset($message)) {
            echo $message;
        } ?>
    </h1>
    <?php if (is_string($absences)): ?>
        <p class=""><?= htmlspecialchars($absences); ?></p>
    <?php elseif (isset($absences) && !empty($absences)): ?>
        <?php
        $absenceCounter = 1; // Initialiser le compteur d'absences
        foreach ($absences as $absence):
            ?>
            <div class="job_card">
                <div class="job_details">
                    <div class="img">
                        <i class="fas fa-user"></i>
                    </div>
                    <a href="?controller=prestataire&action=infosAbsence&id_absence=<?= htmlspecialchars($absence['id']) ?>"
                        class="block">
                        <div class="text">
                            <h2><?= 'Absence ' . $absenceCounter ?></h2>
                            <span><?= htmlspecialchars($absence['date_absence']) ?></span>
                        </div>
                    </a>
                </div>
            </div>
            <?php
            $absenceCounter++;
        endforeach;
        ?>
    <?php endif; ?>
</section>

<div class="add-container" id="caheaffiche" style="display: none;">
    <div class="form-abs">
        <span class="close-icon" id="close-form" onclick="closeFormajout()"> <!-- Ajout de l'ID -->
            <i class="fas fa-times"></i>
        </span>
        <form action="?controller=prestataire&action=creer_absence" method="post" class="form">
            <div class="form-step form-step-active">
                <h2>Déclarer une absence</h2>
                <div class="input-group">
                    <label for="sté">Date de l'absence:</label>
                    <input type="date" name='date' id='sté' class="input-case" require>
                </div>
                <div class="input-group">
                    <label for="sté">Motif:</label>
                    <textarea type="text" id="sté" name="motif" class="input-case" require></textarea>
                </div>
            </div>
            <div class="btns-group">
                <input type="submit" value="Déclarer l'absence" class="btn">
            </div>
        </form>

    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var form = document.querySelector('.form');

        form.addEventListener('submit', function (event) {
            var date = document.querySelector('#sté[name="date"]').value;
            var motif = document.querySelector('#sté[name="motif"]').value;

            if (date.trim() === '' || motif.trim() === '') {
                event.preventDefault(); // Empêcher la soumission du formulaire si un champ est vide
                alert('Veuillez remplir tous les champs.');
            }
        });

        // <?php if (isset($message)): ?>
            //     alert("<?= htmlspecialchars_decode($message, ENT_QUOTES) ?>");
            // <?php endif; ?>
        <?php if (count($absences) == 0): ?>
            document.getElementById('errorMessage').innerHTML = 'Aucune absence trouvée pour cet ID.';
            document.getElementById('errorMessage').style.display = 'block';
        <?php endif; ?>
    });

</script>

<?php
require 'view_end.php';
?>