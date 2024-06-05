<?php
require 'Views/view_begin.php';
require 'Views/view_header.php';
?>
<div class="add-container">
    <div class="form-abs">
        <span class="close-icon" id="close-form" onclick="closeFormajout()"> <!-- Ajout de l'ID -->
            <i class="fas fa-times"></i>
        </span>
        <h1 class="text-center">Ajout Gestionnaire</h1>

        <?php if (isset($success)): ?>
            <p class="success-message"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <p class="error-message"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form action="?controller=administrateur&action=ajouter_gestionnaire" method="post" class="form">
            <!-- Steps -->
            <div class="form-step form-step-active">
                <h2>Informations personnelles</h2>
                <div class="form-names ensemble">
                    <div class="input-group">
                        <label for="prenom">Prénom</label>
                        <input type="text" placeholder="Prénom" id="prenom" name="prenom" class="input-case" required>
                    </div>
                    <div class="input-group">
                        <label for="nom">Nom</label>
                        <input type="text" placeholder="Nom" id="nom" name="nom" class="input-case" required>
                    </div>
                </div>
                <div class="input-group">
                    <label for="email-gestionnaire">Email</label>
                    <input type="email" placeholder="Adresse email" id="email-gestionnaire" name="email-gestionnaire"
                        class="input-case" required>
                </div>
                <div class="form-names ensemble">
                    <div class="input-group">
                        <label for="mot_de_passe">Mot de passe</label>
                        <input type="password" placeholder="Mot de passe" id="mot_de_passe" name="mot_de_passe"
                            class="input-case" required>
                    </div>
                    <div class="input-group">
                        <label for="telephone">Téléphone</label>
                        <input type="tel" placeholder="Numéro de téléphone" id="telephone" name="telephone"
                            class="input-case">
                    </div>
                </div>
            </div>
            <div class="btns-group">
                <input type="submit" value="Créer" class="btn">
            </div>
        </form>
    </div>
</div>
<?php
require 'Views/view_end.php';
?>