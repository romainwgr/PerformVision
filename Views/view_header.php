<!-- Vue stockant le header personnalisé pour chaque fonction -->
<aside class="sidebar">
    <div class="logo">
        <a href="?controller=<?= $_GET['controller'] ?>&action=default" class="logo">
            <img src="Content/images/logo3.png" alt="logo">
            <h2>Perform Vision</h2>
        </a>
    </div>
    <ul class="links">
        <h4>Menu Principal</h4>
        <?php if (isset($menu)): ?>
            <?php foreach ($menu as $m): ?>
                <li>
                    <?php if ($m['name'] == 'Société'): ?>
                        <span class="material-symbols-outlined">business</span>
                    <?php elseif ($m['name'] == 'Composantes'): ?>
                        <span class="material-symbols-outlined">apps</span>
                    <?php elseif ($m['name'] == 'Missions'): ?>
                        <span class="material-symbols-outlined">assignment</span>
                    <?php elseif ($m['name'] == 'Prestataires'): ?>
                        <span class="material-symbols-outlined">work</span>
                    <?php elseif ($m['name'] == 'Commerciaux'): ?>
                        <span class="material-symbols-outlined">bar_chart</span>

                    <?php elseif ($m['name'] == 'Gestionnaires'): ?>
                        <span class="material-symbols-outlined">supervisor_account</span>
                    <?php elseif ($m['name'] == 'Mes prestataires'): ?>
                        <span class="material-symbols-outlined">work</span>
                    <?php elseif ($m['name'] == 'Bons de livraison'): ?>
                        <span class="material-symbols-outlined">local_shipping</span>
                    <?php elseif ($m['name'] == 'Clients'): ?>
                        <span class="material-symbols-outlined">person</span>
                    <?php else: ?>
                        <span class="material-symbols-outlined">help</span> <!-- Icône par défaut -->
                    <?php endif; ?>

                    <a href="<?= $m['link'] ?>">
                        <span class="nav-item"><?= $m['name'] ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
        <hr>
        <h4>Compte</h4>
        <li>
            <a href="?controller=<?= $_GET['controller'] ?>&action=infos" class="right-elt" id="username">
                <?php if (isset($_SESSION)): ?>

                    <span class="material-symbols-outlined">settings</span>
                    <span class="nav-item">&nbsp;<?= $_SESSION['nom']; ?>
                        &nbsp;<?= $_SESSION['prenom']; ?></span>
                <?php endif; ?>
            </a>
        </li>
        <li>
            <a href="?controller=login">
                <span class="material-symbols-outlined">logout</span>
                <span class="nav-item">Déconnexion</span>
            </a>
        </li>
    </ul>
</aside>