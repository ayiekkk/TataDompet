<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];

/**
 * FILTER (GET)
 */
$bulan = $_GET['bulan'] ?? '';
$tahun = $_GET['tahun'] ?? '';
$type  = $_GET['type'] ?? '';

$where  = ["id_user = ?"];
$params = [$userId];
$types  = "i";

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

$filteredTransactions = mysqli_fetch_all(
    mysqli_stmt_get_result($stmt),
    MYSQLI_ASSOC
);

/**
 * HITUNG TOTAL
 */
$pemasukan = 0;
$pengeluaran = 0;

foreach ($filteredTransactions as $t) {
    if ($t['type'] === 'pemasukan') {
        $pemasukan += $t['amount'];
    } else {
        $pengeluaran += $t['amount'];
    }
}

$saldo = $pemasukan - $pengeluaran;

/**
 * INSERT
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $date     = $_POST['date'];
    $typePost = $_POST['type'];
    $category = $_POST['category'];
    $amount   = (int)$_POST['amount'];
    $note     = $_POST['note'] ?? '';

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO transactions 
        (id_user, date, type, category, amount, note)
        VALUES (?, ?, ?, ?, ?, ?)"
    );

    mysqli_stmt_bind_param(
        $stmt,
        'isssis',
        $userId,
        $date,
        $typePost,
        $category,
        $amount,
        $note
    );

    mysqli_stmt_execute($stmt);
    header('Location: main.php');
    exit;
}

function rupiah($n) {
    return 'Rp. ' . number_format($n, 0, ',', '.');
}
