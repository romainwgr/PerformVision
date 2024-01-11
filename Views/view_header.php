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
            <li><a href="#"><img src="Content/images/profile.svg"></a></li>
            <li><a href="#"><?php if(isset($_SESSION)): echo $_SESSION['nom']; endif; ?> <br> <?php if(isset($_SESSION)): echo $_SESSION['prenom']; endif; ?></a></li>
            <li><a href="#"><img src="Content/images/door.svg"></a></li>
        </ul>
    </nav>
</header>