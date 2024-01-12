<header>
    <nav class="header-navbar">
        <div class="logo">Perform Vision</div>
        <?php if (isset($menu)): ?>
            <ul class="menu-list">
                <?php foreach ($menu as $title): ?>
                    <li><a href=<?= $title['link'] ?>><?= $title['name'] ?></li></a>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <ul>
            <li><a href="#" class='right-elt'><i class="fa fa-user-circle" aria-hidden="true"></i></a></li>
            <li><a href="#" id="username" class='right-elt'><?php if(isset($_SESSION)): echo $_SESSION['nom']; endif; ?> <br> <?php if(isset($_SESSION)): echo $_SESSION['prenom']; endif; ?></a></li>
            <li><a href="../index.php?controller=login" class='right-elt'><i class="fa fa-sign-out" aria-hidden="true"></i></a></li>
        </ul>
    </nav>
</header>
