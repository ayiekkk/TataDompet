<div class="container">
        <!-- Header -->
        <div class="header">
            <h1>Laporan Keuangan</h1>
            <p>Simulasi Pengelola Keuangan Sederhana</p>
        </div>

        <!-- Cards -->
        <div class="cards">
            <div class="card balance">
                <div class="card-icon">💰</div>
                <div class="card-content">
                    <p>Saldo</p>
                    <h2 id="totalSaldo">Rp. 0,00</h2>
                </div>
            </div>

            <button class="card income" onclick="openModal('pemasukan')">
                <div class="card-icon">+</div>
                <div class="card-content">
                    <p>Pemasukan</p>
                    <h2 id="totalPemasukan">Rp. 2.800.000,00</h2>
                </div>
            </button>

            <button class="card expense" onclick="openModal('pengeluaran')">
                <div class="card-icon">−</div>
                <div class="card-content">
                    <p>Pengeluaran</p>
                    <h2 id="totalPengeluaran">Rp. 2.800.000,00</h2>
                </div>
            </button>
        </div>

        <!-- Filter -->
        <div class="filter-section">
            <h3>Filter Laporan</h3>
            <div class="filter-grid">
                <div class="filter-group">
                    <label>Bulan</label>
                    <select id="filterBulan">
                        <option value="">Semua Bulan</option>
                        <option value="1">Januari</option>
                        <option value="2">Februari</option>
                        <option value="3">Maret</option>
                        <option value="4">April</option>
                        <option value="5">Mei</option>
                        <option value="6">Juni</option>
                        <option value="7">Juli</option>
                        <option value="8">Agustus</option>
                        <option value="9">September</option>
                        <option value="10">Oktober</option>
                        <option value="11">November</option>
                        <option value="12">Desember</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label>Tahun</label>
                    <select id="filterTahun">
                        <option value="">Semua Tahun</option>
                        <option value="2024">2024</option>
                        <option value="2023">2023</option>
                        <option value="2022">2022</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label>Tampilan</label>
                    <select id="filterTampilan">
                        <option value="">Semua</option>
                        <option value="pemasukan">Pemasukan</option>
                        <option value="pengeluaran">Pengeluaran</option>
                    </select>
                </div>
            </div>
            <button class="btn-confirm" onclick="applyFilter()">Konfirmasi</button>
            <div style="clear: both;"></div>
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
                <tbody id="tableBody">
                    <!-- Data will be inserted here -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div id="modal" class="modal">
        <div class="modal-content">
            <h2 id="modalTitle">Tambah Transaksi</h2>
            <form id="transactionForm">
                <div class="form-group">
                    <label>Tanggal</label>
                    <input type="date" id="inputDate" required>
                </div>
                <div class="form-group">
                    <label>Jumlah (Rp)</label>
                    <input type="number" id="inputAmount" placeholder="Masukkan jumlah" required>
                </div>
                <div class="form-group">
                    <label>Kategori</label>
                    <input type="text" id="inputCategory" placeholder="Contoh: Gaji, Makanan, Transport" required>
                </div>
                <div class="form-group">
                    <label>Keterangan</label>
                    <textarea id="inputDescription" placeholder="Keterangan transaksi"></textarea>
                </div>
                <div class="modal-buttons">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    let pemasukan = 0;
    let pengeluaran = 0;
    let currentModalType = '';

    let transactions = [];

    function getSaldo() {
        return pemasukan - pengeluaran;
    }

    function formatRupiah(amount) {
        return 'Rp. ' + amount.toLocaleString('id-ID');
    }

    function updateTotals() {
        document.getElementById('totalSaldo').textContent =
            formatRupiah(getSaldo());
        document.getElementById('totalPemasukan').textContent =
            formatRupiah(pemasukan);
        document.getElementById('totalPengeluaran').textContent =
            formatRupiah(pengeluaran);
    }

    function renderTable(data = transactions) {
        const tbody = document.getElementById('tableBody');

        if (data.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="4" class="empty-state">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p>Tidak ada transaksi tersedia</p>
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = data.map(t => `
            <tr>
                <td>${t.date}</td>
                <td>
                    <span class="badge ${t.type}">
                        ${t.type === 'pemasukan' ? 'Pemasukan' : 'Pengeluaran'}
                    </span>
                </td>
                <td>${t.category}</td>
                <td>${formatRupiah(t.amount)}</td>
            </tr>
        `).join('');
    }

    function openModal(type) {
        // UX guard (opsional tapi masuk akal)
        if (type === 'pengeluaran' && getSaldo() <= 0) {
            alert('Saldo masih 0. Tidak bisa menambah pengeluaran.');
            return;
        }

        currentModalType = type;
        document.getElementById('modalTitle').textContent =
            `Tambah ${type.charAt(0).toUpperCase() + type.slice(1)}`;
        document.getElementById('transactionForm').reset();
        document.getElementById('inputDate').valueAsDate = new Date();
        document.getElementById('modal').classList.add('active');
    }

    function closeModal() {
        document.getElementById('modal').classList.remove('active');
    }

    function applyFilter() {
        const bulan = document.getElementById('filterBulan').value;
        const tahun = document.getElementById('filterTahun').value;
        const tampilan = document.getElementById('filterTampilan').value;

        const filtered = transactions.filter(t => {
            const date = new Date(t.date);

            if (bulan && date.getMonth() + 1 !== Number(bulan)) return false;
            if (tahun && date.getFullYear() !== Number(tahun)) return false;
            if (tampilan && t.type !== tampilan) return false;

            return true;
        });

        renderTable(filtered);
    }

    document.getElementById('transactionForm')
        .addEventListener('submit', function (e) {
            e.preventDefault();

            const amount = Number(document.getElementById('inputAmount').value);
            const category = document.getElementById('inputCategory').value;
            const description = document.getElementById('inputDescription').value;
            const date = document.getElementById('inputDate').value;

            const saldoSaatIni = getSaldo();

            // VALIDASI KERAS
            if (currentModalType === 'pengeluaran') {
                if (saldoSaatIni <= 0) {
                    alert('Saldo habis. Tidak bisa menambah pengeluaran.');
                    return;
                }
                if (amount > saldoSaatIni) {
                    alert('Saldo tidak mencukupi.');
                    return;
                }
                pengeluaran += amount;
            } else {
                pemasukan += amount;
            }

            transactions.unshift({
                id: Date.now(),
                date,
                type: currentModalType,
                amount,
                category,
                description
            });

            updateTotals();
            renderTable();
            closeModal();
        });

    document.getElementById('modal')
        .addEventListener('click', function (e) {
            if (e.target === this) closeModal();
        });

    updateTotals();
    renderTable();
</script>