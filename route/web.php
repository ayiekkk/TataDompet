<?php
$page = $_GET['page'] ?? 'home';

switch ($page) {
    case 'home':
        require ROOT_PATH . '/front-end/pages/home.php';
        break;

    case 'main':
        require ROOT_PATH . '/front-end/pages/main.php';
        break;

    default:
        http_response_code(404);
        echo "404 - Page not found";
}
