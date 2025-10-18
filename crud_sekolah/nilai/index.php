<?php
$page_title = "Manajemen Nilai";
include '../config.php';
include '../header.php';


// Menangkap kata kunci pencarian dari URL
$search_query = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Ambil daftar siswa untuk dropdown
$siswa_list = mysqli_query($conn, "SELECT id, nama FROM siswa ORDER BY nama ASC");
$mapel_list = mysqli_query($conn, "SELECT id, mata_pelajaran FROM mapel ORDER BY mata_pelajaran ASC");
?>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        ✅ Data nilai berhasil ditambahkan!
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        ❌ Gagal menambahkan data nilai!
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Manajemen Data Nilai</h2>
    <div class="row mb-3">
        <div class="col-md-6">
            <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addModal">➕ Tambah
                Nilai</button>
        </div>
        <div class="col-md-6">
            <form action="index.php" method="GET">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama, kelas, atau alamat..."
                        value="<?php echo htmlspecialchars($search_query); ?>">
                    <button class="btn btn-primary" type="submit">🔍 Cari</button>
                </div>
            </form>
        </div>

    </div>


    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Nama Siswa</th>
                <th>Mata Pelajaran</th>
                <th>Nilai</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Query JOIN untuk mendapatkan nama siswa
            $sql ="SELECT 
                     nilai.id,
                     nilai.siswa_id,
                     siswa.nama AS nama_siswa,
                     nilai.mapel_id,
                     mapel.mata_pelajaran AS nama_mapel,
                     nilai.nilai
                    FROM nilai
                    JOIN siswa ON nilai.siswa_id = siswa.id
                    JOIN mapel ON nilai.mapel_id = mapel.id";

            // Jika ada kata kunci pencarian, tambahkan kondisi WHERE
            if (!empty($search_query)) {
                $sql .= " WHERE nilai.mata_pelajaran LIKE '%$search_query%'
              OR nilai.nilai LIKE '%$search_query%'
              OR siswa.nama LIKE '%$search_query%'";
            }

            // Selalu tambahkan urutan di akhir query
            $sql .= " ORDER BY siswa.nama, mapel.mata_pelajaran ASC";

            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                        <td>{$row['nama_siswa']}</td>
                        <td>{$row['nama_mapel']}</td>
                        <td>{$row['nilai']}</td>
                        <td>
                            <button class='btn btn-warning btn-sm' data-bs-toggle='modal' data-bs-target='#editModal' 
                                    data-id='{$row['id']}' 
                                    data-siswaid='{$row['siswa_id']}' 
                                    data-mapel='{$row['nama_mapel']}' 
                                    data-nilai='{$row['nilai']}'>
                                Edit
                            </button>
                            <button class='btn btn-danger btn-sm' data-bs-toggle='modal' data-bs-target='#deleteModal' 
                                    data-id='{$row['id']}' 
                                    data-info='{$row['nama_siswa']} - {$row['nama_mapel']}'>
                                Hapus
                            </button>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='4' class='text-center'>Belum ada data nilai</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="create.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Nilai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Siswa</label>
                        <select name="siswa_id" class="form-select" required>
                            <option value="">-- Pilih Siswa --</option>
                            <?php
                            mysqli_data_seek($siswa_list, 0);
                            while ($s = mysqli_fetch_assoc($siswa_list)) {
                                echo "<option value='{$s['id']}'>{$s['nama']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Mata Pelajaran</label>
                        <select name="mapel_id" class="form-select" required>
                            <option value="">-- Pilih mapel --</option>
                            <?php
                            mysqli_data_seek($mapel_list, 0);
                            while ($m = mysqli_fetch_assoc($mapel_list)) {
                                echo "<option value='{$m['id']}'>{$m['mata_pelajaran']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Nilai</label>
                        <input type="number" name="nilai" class="form-control" min="0" max="100" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="update.php" method="POST">
                <input type="hidden" name="id" id="edit-id">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Nilai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Siswa</label>
                        <select name="siswa_id" id="edit-siswaid" class="form-select" required>
                            <?php
                            mysqli_data_seek($siswa_list, 0);
                            while ($s = mysqli_fetch_assoc($siswa_list)) {
                                echo "<option value='{$s['id']}'>{$s['nama']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Mata Pelajaran</label>
                        <select name="mapel_id" id="edit-siswaid" class="form-select" required>
                            <?php
                            mysqli_data_seek($mapel_list, 0);
                            while ($s = mysqli_fetch_assoc($mapel_list)) {
                                echo "<option value='{$s['id']}'>{$s['mata_pelajaran']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Nilai</label>
                        <input type="number" name="nilai" id="edit-nilai" class="form-control" min="0" max="100"
                            required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="delete.php" method="POST">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Yakin ingin hapus nilai <strong id="delete-info"></strong>?</p>
                    <input type="hidden" name="id" id="delete-id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var editModal = document.getElementById('editModal');
        editModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            document.getElementById('edit-id').value = button.getAttribute('data-id');
            document.getElementById('edit-siswaid').value = button.getAttribute('data-siswaid');
            document.getElementById('edit-mapel').value = button.getAttribute('data-mapel');
            document.getElementById('edit-nilai').value = button.getAttribute('data-nilai');
        });

        var deleteModal = document.getElementById('deleteModal');
        deleteModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            document.getElementById('delete-id').value = button.getAttribute('data-id');
            document.getElementById('delete-info').textContent = button.getAttribute('data-info');
        });
    });

    setTimeout(() => {
        let alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.classList.remove('show');
            alert.classList.add('fade');
            setTimeout(() => alert.remove(), 500);
        });
    }, 2000);
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>