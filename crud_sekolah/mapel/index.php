<?php
$page_title = "Manajemen Nilai";
include '../config.php';
include '../header.php';


// Menangkap kata kunci pencarian dari URL
$search_query = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Ambil daftar siswa untuk dropdown
$guru_list = mysqli_query($conn, "SELECT id, nama_guru FROM guru ORDER BY nama_guru ASC");

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
    <h2 class="text-center mb-4">Manajemen Data Mapel</h2>
    <div class="row mb-3">
        <div class="col-md-6">
            <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addModal">➕ Tambah
                Mapel</button>
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
                <th>Nama Guru</th>
                <th>Mata Pelajaran</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Query JOIN untuk mendapatkan nama siswa
            $sql ="SELECT 
            mapel.id AS mapel_id,
            mapel.guru_id,
            mapel.mata_pelajaran AS nama_mapel,
            guru.id,
            guru.nama_guru AS nama_guru
           FROM mapel
           JOIN guru ON mapel.guru_id = guru.id";


            // Jika ada kata kunci pencarian, tambahkan kondisi WHERE
            if (!empty($search_query)) {
                $sql .= " WHERE nilai.mata_pelajaran LIKE '%$search_query%'
              OR nilai.nilai LIKE '%$search_query%'
              OR siswa.nama LIKE '%$search_query%'";
            }

            // Selalu tambahkan urutan di akhir query
            $sql .= " ORDER BY mapel.mata_pelajaran, guru.nama_guru ASC";

            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                        <td>{$row['nama_guru']}</td>
                        <td>{$row['nama_mapel']}</td>
                        <td>
                            <button class='btn btn-warning btn-sm' data-bs-toggle='modal' data-bs-target='#editModal' 
                                    data-id='{$row['mapel_id']}' 
                                    data-guruid='{$row['guru_id']}' 
                                    data-mapel='{$row['nama_mapel']}' 
                                    data-guru='{$row['nama_guru']}'>
                                Edit
                            </button>
                            <button class='btn btn-danger btn-sm' data-bs-toggle='modal' data-bs-target='#deleteModal' 
                                    data-id='{$row['mapel_id']}' 
                                    data-info='{$row['nama_guru']} - {$row['nama_mapel']}'>
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
                        <label>guru</label>
                        <select name="guru_id" class="form-select" required>
                            <option value="">-- Pilih guru --</option>
                            <?php
                            mysqli_data_seek($guru_list, 0);
                            while ($g = mysqli_fetch_assoc($guru_list)) {
                                echo "<option value='{$g['id']}'>{$g['nama_guru']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>mata pelajaran</label>
                        <input type="text" name="mata_pelajaran" class="form-control" min="0" max="100" required>
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
                        <label>guru</label>
                        <select name="guru_id" id="edit-siswaid" class="form-select" required>
                            <?php
                            mysqli_data_seek($guru_list, 0);
                            while ($g = mysqli_fetch_assoc($guru_list)) {
                                echo "<option value='{$g['id']}'>{$g['nama_guru']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>mata pelajaran</label>
                        <input type="text" name="mata_pelajaran" id="edit-nilai" class="form-control" min="0" max="100"
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
            document.getElementById('edit-guruid').value = button.getAttribute('data-guruid');
            document.getElementById('edit-mapel').value = button.getAttribute('data-mapel');
            document.getElementById('edit-guru').value = button.getAttribute('data-guru');
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