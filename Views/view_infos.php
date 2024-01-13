<?php
require 'view_begin.php';
require 'view_header.php';
?>
    <div class="add-container">
        <div class="form-abs">
            <h1>Mon compte</h1>
            <form action="?controller=<?= $_SESSION['role'] ?>&action=maj_infos" method="post">
                <h2>Informations personnelles</h2>
                <div class="form-names">
                    <input type="text" placeholder="<?= $_SESSION['prenom'] ?>" name="prenom" class="input-case">
                    <input type="text" placeholder="<?= $_SESSION['nom'] ?>" name="nom" class="input-case">
                </div>
                <input type="email" placeholder="<?= $_SESSION['email'] ?>" name='email' id='mail-1' class="input-case">
                <h2>Mot de passe</h2>
                <input type="text" placeholder='Changer de mot de passe' name='client' id='sté' class="input-case">
                <div class="buttons" id="create">
                    <button type="submit">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
<?php
require 'view_end.php';
?><?php
