<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../components/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = mysqli_prepare(
        $conn,
        "SELECT * FROM users WHERE username = ?"
    );
    mysqli_stmt_bind_param($stmt, 's', $_POST['username']);
    mysqli_stmt_execute($stmt);

    $user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if ($user && password_verify($_POST['password'], $user['password'])) {
        $_SESSION['user_id'] = $user['id_user'];
        header('Location: index.php?=main');
        exit;
    }

    echo "Login gagal";
}
?>

<div class="container-login">
    <div class="wrapper-login">
        <div class="left">
            <img src="../img/TechLifeRemoteLife.png" alt="">
        </div>

        <div class="right">
            <div class="login-card">
                <div class="welcome-text">
                    <h2>Welcome Back</h2>
                </div>

                <button class="google-btn">
                    <span class="material-icon-theme--google"></span>
                    Log In With Google
                </button>

                <form method="POST" action="">
                    <div class="input-group">
                        <div class="input-wrapper">
                            <span class="tabler--user-filled"></span>
                            <input name="username" required placeholder="Username">
                        </div>
                    </div>

                    <div class="input-group">
                        <div class="input-wrapper">
                            <span class="streamline--padlock-square-1-solid"></span>
                            <input type="password" name="password" required placeholder="Password">
                        </div>
                    </div>

                    <div class="forgot-password">
                        <p>Belum punya akun? <a href="index.php?page=register">Register</a></p>
                    </div>

                    <button type="submit" class="login-btn">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>