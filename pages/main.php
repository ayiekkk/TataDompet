<?php
include '../controller/index.php';

$transactions = $filteredTransactions ?? [];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <div class="container">
        <!-- Header -->
        <header>
            <h1>Laporan Keuangan</h1>
            <p>Simulasi Pengelola Keuangan Sederhana</p>
        </header>

        <a href="logout.php">Logout</a>


        <!-- Cards -->
        <div class="cards">
            <div class="card balance">
                <div class="card-icon">💰</div>
                <div class="card-content">
                    <p>Saldo</p>
                    <h2><?= rupiah($saldo); ?></h2>
                </div>
            </div>

            <button class="card income" onclick="openModal('pemasukan')">
                <div class="card-icon">+</div>
                <div class="card-content">
                    <p>Pemasukan</p>
                    <h2><?= rupiah($pemasukan); ?></h2>
                </div>
            </button>

            <button class="card expanse" onclick="openModal('pengeluaran')">
                <div class="card-icon">−</div>
                <div class="card-content">
                    <p>Pengeluaran</p>
                    <h2><?= rupiah($pengeluaran) ?></h2>
                </div>
            </button>
        </div>

        <!-- Filter -->

        <div class="filter-section">
            <form method="GET" class="filter-section">
                <h3>Filter Laporan</h3>

                <div class="filter-grid">
                    <div class="filter-group">
                        <label>Bulan</label>
                        <select name="bulan">
                            <option value="">Semua Bulan</option>
                            <?php for ($i = 1; $i <= 12; $i++): ?>
                                <option value="<?= $i ?>" <?= ($_GET['bulan'] ?? '') == $i ? 'selected' : '' ?>>
                                    <?= date('F', mktime(0, 0, 0, $i, 1)) ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label>Tahun</label>
                        <select name="tahun">
                            <option value="">Semua Tahun</option>
                            <?php for ($y = date('Y'); $y >= 2020; $y--): ?>
                                <option value="<?= $y ?>" <?= ($_GET['tahun'] ?? '') == $y ? 'selected' : '' ?>>
                                    <?= $y ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label>Jenis</label>
                        <select name="type">
                            <option value="">Semua</option>
                            <option value="pemasukan" <?= ($_GET['type'] ?? '') === 'pemasukan' ? 'selected' : '' ?>>Pemasukan</option>
                            <option value="pengeluaran" <?= ($_GET['type'] ?? '') === 'pengeluaran' ? 'selected' : '' ?>>Pengeluaran</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn-confirm">Terapkan Filter</button>
            </form>

        </div>

        <!-- Table -->
        <div class="table-section">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Kategori</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!$filteredTransactions): ?>
                        <tr>
                            <td colspan="4" style="text-align:center;">Tidak ada data</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($filteredTransactions as $t): ?>
                            <tr>
                                <td><?= $t['date'] ?></td>
                                <td><?= ucfirst($t['type']) ?></td>
                                <td><?= $t['category'] ?></td>
                                <td><?= rupiah($t['amount']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div id="modal" class="modal">
        <div class="modal-content">
            <h2 id="modalTitle">Tambah Transaksi</h2>
            <form method="POST" action="">
                <input type="hidden" name="type" id="inputType">
                <div class="form-group">
                    <label>Tanggal</label>
                    <input type="date" name="date" id="inputDate" required>
                </div>
                <div class="form-group">
                    <label>Jumlah (Rp)</label>
                    <input type="number" name="amount" id="inputAmount" placeholder="Masukkan jumlah" required>
                </div>
                <div class="form-group">
                    <label>Kategori</label>
                    <input type="text" name="category" id="inputCategory" placeholder="Contoh: Gaji, Makanan, Transport" required>
                </div>
                <div class="form-group">
                    <label>Keterangan</label>
                    <textarea name="note" id="inputDescription" placeholder="Keterangan transaksi"></textarea>
                </div>
                <div class="modal-buttons">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Batal</button>
                    <button type="submit" class="btn btn-primary" name="submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('modal');
        const modalTitle = document.getElementById('modalTitle');
        const inputType = document.getElementById('inputType');



        function openModal(type) {
            modal.classList.add('active');
            modalTitle.textContent = type === 'pemasukan' ? 'Tambah Pemasukan' : 'Tambah Pengeluaran';
            inputType.value = type;
        }

        function closeModal() {
            modal.classList.remove('active');
        }

        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });
    </script>


</body>

</html>