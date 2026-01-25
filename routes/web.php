<?php


$page = $_GET['page'] ?? 'home';


if ($page === 'login') {
    require 'pages/login.php';
    exit;
}

if ($page === 'logout') {
    session_unset();
    session_destroy();
    header("Location: index.php?page=login");
    exit;
}


if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?page=login");
    exit;
}

if ($page === 'main') {
    require 'controller/controller.php';
    require 'pages/main.php';
} else {
    require 'pages/home.php';
}


