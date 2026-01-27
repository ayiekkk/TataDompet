<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../components/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO users (username, password) VALUES (?, ?)"
    );
    mysqli_stmt_bind_param($stmt, 'ss', $username, $password);
    mysqli_stmt_execute($stmt);

    header('Location: index.php?=login');
    exit;
}
?>
<?php require_once 'components/header.php'; ?>
<div class="container-login">
    <div class="wrapper-login">
        <div class="left">
            <img src="../img/TechLifeRemoteLife.png" alt="">
        </div>

        <div class="right">
            <div class="login-card">
                <div class="welcome-text">
                    <h2>Welcome</h2>
                </div>

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

                    <button type="submit" class="login-btn">Register</button>
                </form>
            </div>
        </div>
    </div>
</div>





<form method="POST">
    <h2>Register</h2>
    <input name="username" required placeholder="Username">
    <input type="password" name="password" required placeholder="Password">
    <button type="submit">Register</button>
</form>