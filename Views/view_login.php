<!-- Vue permettant de voir la page de connexion afin de se connecter et accéder au site -->
<?php
require 'view_begin.php';
?>
<div class="center-container">
    <div class="login-container" <?= (isset($data['response']['roles'])) ? "background-blur" : "" ?>>
        <div class="container">
            <input type="checkbox" id="flip">
            <div class="cover">
                <div class="front">
                    <img src="Content/images/logo2.webp" alt="">
                    <div class="text">
                        <span class="text-1">Simplifiez la gestion des <br>bons de livraison</span>
                        <span class="text-2">Connectez-vous</span>
                    </div>
                </div>

            </div>
            <div class="forms">
                <div class="form-content">
                    <div class="login-form">
                        <div class="title">Connectez-vous !</div>
                        <form action="?controller=login&action=check_pswd" method="post">
                            <div class="input-boxes">
                                <div class="input-box">
                                    <i class="fas fa-envelope"></i>
                                    <input class="input-login" type="text" name="mail" placeholder="Email" required>
                                </div>
                                <div class="input-box">
                                    <i class="fas fa-lock"></i>
                                    <input class="input-login" type="password" name="password"
                                        placeholder="Mot de passe" required>
                                </div>
                                <div class="text"><a href="#">Mot de passe oublié ?</a></div>

                                <div class="button input-box">
                                    <input type="submit" value="Connexion">
                                </div>
                                <div class="text sign-up-text">Adresse email oubliée ? <label for="flip">Envoyer un
                                        message</label></div>
                            </div>
                        </form>
                    </div>
                    <div class="signup-form">
                        <div class="title">Message</div>
                        <form action="#">
                            <div class="input-boxes">
                                <div class="input-box">
                                    <i class="fas fa-user"></i>
                                    <input type="text" placeholder="Entrez votre nom" required>
                                </div>
                                <div class="input-box">
                                    <i class="fas fa-user"></i>
                                    <input type="text" placeholder="Entrez votre prénom" required>
                                </div>

                                <div class="input-box textarea-box">
                                    <textarea id="message" name="message" placeholder="Entrez votre message"
                                        required></textarea>
                                </div>

                                <div class="button input-box">
                                    <input type="submit" value="Envoyer">
                                </div>
                                <div class="text sign-up-text">Vous avez déjà un compte ? <label
                                        for="flip">Connectez-vous</label></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
if (isset($data['response']) && isset($data['response']['roles'])) {
    require 'view_login_popup.php';
}
?>

<?php
require 'view_end.php';
?>