<!DOCTYPE html>
<html lang="en">
    <?php include "../components/header.php"; ?>
<body>
    
    <?php include "../components/navbar.php"; ?>

    <?php define('ROOT_PATH', dirname(__DIR__, 2)); 
require ROOT_PATH . '/route/web.php'; ?>

    <?php include "../components/footer.php"; ?>

</body>
</html>