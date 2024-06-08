<?php
require 'Views/view_begin.php';
require 'Views/view_header.php';
?>
<div class="add-container">
    <div class="form-abs">
        <h1>Absences du Prestataire</h1>
        <?php if (!empty($absences)): ?>
            <div class="slideshow-container">
                <?php foreach ($absences as $index => $absence): ?>
                    <div class="slide <?= $index === 0 ? 'active' : '' ?>">
                        <div class="input-group">
                            <label for="date_absence">Date d'absence :</label>
                            <?php
                            // Convertit la date d'absence au format j-m-a
                            $date_absence = date("j-m-Y", strtotime($absence['date_absence']));
                            ?>
                            <input type="text" id="date_absence" name="date_absence"
                                value="<?= htmlspecialchars($date_absence) ?>" class="input-case" disabled>
                        </div>
                        <div class="input-group">
                            <label for="motif">Motif :</label>
                            <textarea id="motif" name="motif" class="input-case"
                                disabled><?= htmlspecialchars($absence['motif']) ?></textarea>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <a class="prev" onclick="plusSlides(-1)" style="display:none">&#10094;</a>
            <a class="next" onclick="plusSlides(1)" <?php if (count($absences) == 1)
                echo 'style="display:none"'; ?>>&#10095;</a>
        <?php else: ?>
            <p>Aucune absence trouvée pour ce prestataire.</p>
        <?php endif; ?>
    </div>
</div>
<script>
    var slideIndex = 1;
    showSlides(slideIndex);

    function plusSlides(n) {
        showSlides(slideIndex += n);
    }

    function showSlides(n) {
        var i;
        var slides = document.getElementsByClassName("slide");
        if (n > slides.length) { slideIndex = slides.length }
        if (n < 1) { slideIndex = 1 }
        for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }
        slides[slideIndex - 1].style.display = "block";

        var prevButton = document.querySelector('.prev');
        var nextButton = document.querySelector('.next');

        if (slideIndex === 1) {
            prevButton.style.display = "none";
        } else {
            prevButton.style.display = "block";
        }

        if (slideIndex === slides.length) {
            nextButton.style.display = "none";
        } else {
            nextButton.style.display = "block";
        }
    }
</script>

<?php
require 'Views/view_end.php';
?>