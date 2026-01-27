<nav class="navbar" id="navbar">
    <div class="nav-container">
        <a href="#" class="nav-logo">TATA DOMPET</a>

        <ul class="nav-menu">
            <li><a href="index.php" class="nav-link">Beranda</a></li>
            <li><a href="index.php?page=main" class="nav-link">Laporan</a></li>
            <li><a href="#" class="nav-link">Pengaturan</a></li>
        </ul>
        <div class="nav-auth">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="index.php?page=logout" class="btn-signin">Log out</a>
            <?php else: ?>
                <a href="index.php?page=login" class="btn-signin">Sign In</a>
                <a href="index.php?page=register" class="btn-signup">Sign Up</a>
            <?php endif; ?>
        </div>
    </div>
</nav>