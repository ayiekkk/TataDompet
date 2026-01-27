<?php

require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=login');
    exit;
}

if (($_GET['page'] ?? '') === 'login') {
    return;
}


$userId = $_SESSION['user_id'];

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $bulan = $_GET['bulan'] ?? '';
    $tahun = $_GET['tahun'] ?? '';
    $type  = $_GET['type'] ?? '';

    $where = ["id_user = ?"];
    $params = [$userId];
    $types = "i";

    if ($bulan !== '') {
        $where[] = "MONTH(date) = ?";
        $params[] = (int)$bulan;
        $types .= "i";
    }
    if ($tahun !== '') {
        $where[] = "YEAR(date) = ?";
        $params[] = (int)$tahun;
        $types .= "i";
    }
    if ($type !== '') {
        $where[] = "type = ?";
        $params[] = $type;
        $types .= "s";
    }

    $sql = "SELECT * FROM transactions WHERE " . implode(" AND ", $where) . " ORDER BY date DESC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, $types, ...$params);
    mysqli_stmt_execute($stmt);
    $filteredTransactions = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
}


$pemasukan = $pengeluaran = 0;
foreach ($filteredTransactions as $t) {
    $t['type'] === 'pemasukan'
        ? $pemasukan += $t['amount']
        : $pengeluaran += $t['amount'];
}
$saldo = $pemasukan - $pengeluaran;


if (($_POST['action'] ?? '') === 'delete') {
    $id = (int)$_POST['id'];

    $stmt = mysqli_prepare(
        $conn,
        "DELETE FROM transactions WHERE id_transaction = ? AND id_user = ?"
    );
    mysqli_stmt_bind_param($stmt, 'ii', $id, $userId);
    mysqli_stmt_execute($stmt);

    header('Location: index.php?page=main');
    exit;
}

$action = $_POST['action'] ?? '';


if ($action === 'edit') {
    $id = (int) $_POST['id'];

    $stmt = mysqli_prepare(
        $conn,
        "SELECT * FROM transactions WHERE id_transaction = ? AND id_user = ?"
    );
    mysqli_stmt_bind_param($stmt, 'ii', $id, $userId);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);

    if ($data) {
        $_SESSION['edit'] = $data;
    }

    header('Location: index.php?page=main');
    exit;
}


if ($action === 'update') {
    $id       = (int) $_POST['id'];
    $date     = $_POST['date'];
    $type     = $_POST['type'];
    $category = $_POST['category'];
    $amount   = (int) $_POST['amount'];
    $note     = $_POST['note'];

    $stmt = mysqli_prepare(
        $conn,
        "UPDATE transactions
         SET date=?, type=?, category=?, amount=?, note=?
         WHERE id_transaction=? AND id_user=?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        'sssisii',
        $date,
        $type,
        $category,
        $amount,
        $note,
        $id,
        $userId
    );

    mysqli_stmt_execute($stmt);
    header('Location: index.php?page=main');
    exit;
}


if ($action === 'insert') {
    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO transactions (id_user, date, type, category, amount, note)
        VALUES (?, ?, ?, ?, ?, ?)"
    );

    $date = $_POST['date'];
    $typePost = $_POST['type'];
    $category = $_POST['category'];
    $amount = (int) $_POST['amount'];
    $note = $_POST['note'];
    mysqli_stmt_bind_param($stmt, 'isssis', $userId, $date, $typePost, $category, $amount, $note);

    mysqli_stmt_execute($stmt);
    header('Location: index.php?page=main');
    exit;
}
