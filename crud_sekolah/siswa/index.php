<?php
$page_title = "Manajemen Siswa";
include '../config.php';
include '../header.php';

// Menangkap kata kunci pencarian dari URL
$search_query = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Mengambil daftar kelas untuk dropdown di dalam modal
$kelas_list = mysqli_query($conn, "SELECT id, nama_kelas FROM kelas ORDER BY nama_kelas ASC");
?>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        ✅ Data siswa berhasil ditambahkan!
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        ❌ Gagal menambahkan data siswa!
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Manajemen Data Siswa</h2>

    <div class="row mb-3">
        <div class="col-md-6">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">➕ Tambah Siswa</button>
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
                <th>ID</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Alamat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Query dasar untuk mengambil data siswa beserta nama kelasnya
            $sql = "SELECT siswa.id, siswa.nama, siswa.alamat, kelas.nama_kelas, siswa.kelas_id
                    FROM siswa
                    JOIN kelas ON siswa.kelas_id = kelas.id";

            // Jika ada kata kunci pencarian, tambahkan kondisi WHERE
            if (!empty($search_query)) {
                $sql .= " WHERE siswa.nama LIKE '%$search_query%'
                          OR kelas.nama_kelas LIKE '%$search_query%'
                          OR siswa.alamat LIKE '%$search_query%'";
            }

            // Selalu tambahkan urutan di akhir query
            $sql .= " ORDER BY siswa.nama ASC";

            $result = mysqli_query($conn, $sql);

            // Periksa apakah ada hasil
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['nama']}</td>
                        <td>{$row['nama_kelas']}</td>
                        <td>{$row['alamat']}</td>
                        <td>
                            <button class='btn btn-warning btn-sm' data-bs-toggle='modal' data-bs-target='#editModal'
                                    data-id='{$row['id']}'
                                    data-nama='{$row['nama']}'
                                    data-alamat='{$row['alamat']}'
                                    data-kelasid='{$row['kelas_id']}'>
                                Edit
                            </button>
                            <button class='btn btn-danger btn-sm' data-bs-toggle='modal' data-bs-target='#deleteModal'
                                    data-id='{$row['id']}'
                                    data-nama='{$row['nama']}'>
                                Hapus
                            </button>
                        </td>
                    </tr>";
                }
            } else {
                // Tampilkan pesan yang sesuai jika tidak ada data
                if (!empty($search_query)) {
                    echo "<tr><td colspan='5' class='text-center'>Tidak ada siswa yang cocok dengan pencarian '<strong>" . htmlspecialchars($search_query) . "</strong>'.</td></tr>";
                } else {
                    echo "<tr><td colspan='5' class='text-center'>Belum ada data siswa.</td></tr>";
                }
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
                    <h5 class="modal-title">Tambah Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama Siswa</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Alamat</label>
                        <textarea name="alamat" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Kelas</label>
                        <select name="kelas_id" class="form-select" required>
                            <option value="">-- Pilih Kelas --</option>
                            <?php
                            mysqli_data_seek($kelas_list, 0); // Reset pointer
                            while ($k = mysqli_fetch_assoc($kelas_list)) {
                                echo "<option value='{$k['id']}'>{$k['nama_kelas']}</option>";
                            }
                            ?>
                        </select>
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
                    <h5 class="modal-title">Edit Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama Siswa</label>
                        <input type="text" name="nama" id="edit-nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Alamat</label>
                        <textarea name="alamat" id="edit-alamat" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Kelas</label>
                        <select name="kelas_id" id="edit-kelasid" class="form-select" required>
                            <option value="">-- Pilih Kelas --</option>
                            <?php
                            mysqli_data_seek($kelas_list, 0); // Reset pointer
                            while ($k = mysqli_fetch_assoc($kelas_list)) {
                                echo "<option value='{$k['id']}'>{$k['nama_kelas']}</option>";
                            }
                            ?>
                        </select>
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
                    <p>Yakin ingin hapus siswa <strong id="delete-nama"></strong>? Semua data nilai yang terkait akan
                        ikut terhapus.</p>
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
            document.getElementById('edit-nama').value = button.getAttribute('data-nama');
            document.getElementById('edit-alamat').value = button.getAttribute('data-alamat');
            document.getElementById('edit-kelasid').value = button.getAttribute('data-kelasid');
        });

        var deleteModal = document.getElementById('deleteModal');
        deleteModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            document.getElementById('delete-id').value = button.getAttribute('data-id');
            document.getElementById('delete-nama').textContent = button.getAttribute('data-nama');
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