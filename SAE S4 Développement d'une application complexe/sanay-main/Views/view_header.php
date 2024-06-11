<!-- Vue stockant le header personnalisé pour chaque fonction -->
<header>
    <nav class="header-navbar">
        <div class="logo"><a href="?controller=login&action=accueil" class ="lien" style="color:white;">Perform Vision</a></div>
        <?php if (isset($menu)): ?>
            <ul class="menu-list" id="menu-list">
                <?php foreach ($menu as $m): ?>
                    <li><a href="<?= $m['link'] ?>"><?= $m['name'] ?></a></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <div class="right-container">
            <button class="hamburger" id="hamburger">
                <i class="fa fa-bars" aria-hidden="true"></i>
            </button>
            <ul class="right-elt">
                <li>
                    <a href="?controller=<?= $_GET['controller'] ?>&action=infos" id="username">
                        <i class="fa fa-user-circle" aria-hidden="true"></i>
                        <?php if (isset($_SESSION)): ?>
                            &nbsp;<?= $_SESSION['nom'] ?><br>&nbsp;<?= $_SESSION['prenom'] ?>
                        <?php endif; ?>
                    </a>
                </li>
                <li><a href="?controller=login"><i class="fa fa-sign-out" aria-hidden="true"></i></a></li>
            </ul>
        </div>
    </nav>
</header>

<script>
    document.getElementById('hamburger').addEventListener('click', function() {
        document.getElementById('menu-list').classList.toggle('show');
    });
</script>


