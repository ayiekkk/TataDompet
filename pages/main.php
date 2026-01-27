<?php

$transactions = $filteredTransactions ?? [];

$edit = $_SESSION['edit'] ?? null;

?>
<?php require_once 'components/header.php'; ?>
<?php require_once 'components/navbar.php'; ?>
<div class="container">
    <!-- Header -->
    <header>
        <h1>Laporan Keuangan</h1>
        <p>Simulasi Pengelola Keuangan Sederhana</p>
    </header>

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

    <div class="filter-container">
        <form method="GET" action="index.php?page=main" class="filter-section">
            <input type="hidden" name="page" value="main">
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
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($filteredTransactions)): ?>
                    <tr>
                        <td colspan="5" style="text-align:center; padding:40px;">
                            <div class="empty-state">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    width="96"
                                    height="96"
                                    viewBox="0 0 16 16"
                                    aria-hidden="true">
                                    <path fill="#8b8a8a"
                                        d="M13.5 5.88c-.28 0-.5-.22-.5-.5V1.5c0-.28-.22-.5-.5-.5h-9c-.28 0-.5.22-.5.5v2c0 .28-.22.5-.5.5S2 3.78 2 3.5v-2C2 .67 2.67 0 3.5 0h9c.83 0 1.5.67 1.5 1.5v3.88c0 .28-.22.5-.5.5" />
                                    <path fill="#8b8a8a"
                                        d="M14.5 16h-13C.67 16 0 15.33 0 14.5v-10C0 3.67.67 3 1.5 3h4.75c.16 0 .31.07.4.2L8 5h6.5c.83 0 1.5.67 1.5 1.5v8c0 .83-.67 1.5-1.5 1.5" />
                                    <path fill="#8b8a8a"
                                        d="M5.5 13h-2c-.28 0-.5-.22-.5-.5s.22-.5.5-.5h2c.28 0 .5.22.5.5s-.22.5-.5.5" />
                                </svg>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($filteredTransactions as $t): ?>
                        <tr>
                            <td><?= htmlspecialchars($t['date']) ?></td>
                            <td><?= ucfirst(htmlspecialchars($t['type'])) ?></td>
                            <td><?= htmlspecialchars($t['category']) ?></td>
                            <td><?= rupiah($t['amount']) ?></td>
                            <td>
                                <form method="POST" style="display:inline">
                                    <input type="hidden" name="id" value="<?= $t['id_transaction'] ?>">
                                    <button name="action" value="edit" class="edt-btn">Edit</button>
                                </form>

                                <form method="POST" style="display:inline">
                                    <input type="hidden" name="id" value="<?= $t['id_transaction'] ?>">
                                    <button name="action" value="delete" class="del-btn"
                                        onclick="return confirm('Hapus?')">Hapus</button>
                                </form>
                            </td>
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
            <input type="hidden" name="action" value="<?= $edit ? 'update' : 'insert' ?>">
            <input type="hidden" name="id" value="<?= $edit['id_transaction'] ?? '' ?>">
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

    <?php if ($edit): ?>
        openModal("<?= $edit['type'] ?>");
        document.getElementById('inputDate').value = "<?= $edit['date'] ?>";
        document.getElementById('inputCategory').value = "<?= $edit['category'] ?>";
        document.getElementById('inputAmount').value = "<?= $edit['amount'] ?>";
        document.getElementById('inputDescription').value = "<?= $edit['note'] ?>";
    <?php endif; ?>

    function closeModal() {
        modal.classList.remove('active');
    }

    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });
</script>
<?php unset($_SESSION['edit']); ?>

<?php require_once 'components/footer.php'; ?>