<?php
require 'view_begin.php';
require 'view_header.php';
?>
    <div class="add-container">
        <div class="form-abs">
            <h1>Ajout Mission</h1>
            <form action="">
                <h2>Informations interlocuteur</h2>
                <div class="form-names">
                    <input type="text" placeholder="Prénom" name="prenom-interlocuteur" class="input-case">
                    <input type="text" placeholder="Nom" name="nom-interlocuteur" class="input-case">
                </div>
                <input type="email" placeholder="Adresse email" name='email-interlocuteur' id='mail-1' class="input-case">
                <h2>Informations commercial</h2>
                <div class="form-names">
                    <input type="text" placeholder="Prénom" name="prenom-name" class="input-case">
                    <input type="text" placeholder="Nom" name="nom" class="input-case">
                </div>
                <input type="email" placeholder="Adresse email" name='email-commercial'id='mail-1' class="input-case">
                <h2>Informations mission</h2>
                <input type="text" placeholder="Nom de la mission" name='mission' class="input-case">
                <input type="text" placeholder="Société" id='sté' name='client' class="input-case">
                <input type="text" placeholder="Composante" name='composante' id='cpt' class="input-case">
                <div class="buttons" id="create">
                    <button type="submit">Créer client</button>
                </div>
            </form>
        </div>
    </div>
<?php
require 'view_end.php';
?>