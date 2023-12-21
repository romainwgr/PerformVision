<?php
require 'view_begin.php';
?>

    <div class="container">

        <h1>Connectez-vous !</h1>
        <h3>Accéder au site de Perform Vision</h3>
        <form action="?controller=login&action=check_pswd" method="post">

            <input class="input-login" type="text" name="mail" placeholder="Email">

            <div style="display: flex;"><a class="oublie" href="#">Adresse email oubliée ?</a></div>

            <input class="input-login" type="password" name="password" placeholder="Mot de passe">

            <div style="display: flex;"><a class="oublie" href="#">Mot de passe oublié ?</a></div>

            <button class='button'
                    type="submit">Connexion
            </button>
        </form>
        <?php
        if (isset($data['response'])) {
            echo '<p>' . $data['response'] . '</p>';
        }
        ?>
    </div>

<?php
require 'view_end.php';
?>