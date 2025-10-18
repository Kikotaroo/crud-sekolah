<?php
$page_title = "Manajemen Kelas";
include '../config.php';
include '../header.php';
// Menangkap kata kunci pencarian dari URL
$search_query = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
?>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        ✅ Data kelas berhasil ditambahkan!
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        ❌ Gagal menambahkan data kelas!
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Manajemen Data Guru</h2>
    <div class="row mb-3">
        <div class="col-md-6">
            <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addModal">➕ Tambah
                Guru</button>
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
                <th>Nama Guru</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM guru";

            // Jika ada kata kunci pencarian, tambahkan kondisi WHERE
            if (!empty($search_query)) {
                $sql .= " WHERE guru.nama_kelas LIKE '%$search_query%'";
            }

            // Selalu tambahkan ORDER BY di akhir
            $sql .= " ORDER BY guru.nama_guru ASC";


            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['nama_guru']}</td>
                        <td>
                            <button class='btn btn-warning btn-sm' data-bs-toggle='modal' data-bs-target='#editModal' data-id='{$row['id']}' data-nama='{$row['nama_guru']}'>Edit</button>
                            <button class='btn btn-danger btn-sm' data-bs-toggle='modal' data-bs-target='#deleteModal' data-id='{$row['id']}' data-nama='{$row['nama_guru']}'>Hapus</button>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='3' class='text-center'>Belum ada data kelas</td></tr>";
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
                    <h5 class="modal-title">Tambah Guru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama Guru</label>
                        <input type="text" name="nama_guru" class="form-control" required>
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
                    <h5 class="modal-title">Edit Guru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama Guru</label>
                        <input type="text" name="nama_guru" id="edit-nama" class="form-control" required>
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
                    <p>Yakin ingin hapus kelas <strong id="delete-nama"></strong>?</p>
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
    // Script untuk mengisi data ke modal
    document.addEventListener('DOMContentLoaded', function () {
        var editModal = document.getElementById('editModal');
        editModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            document.getElementById('edit-id').value = button.getAttribute('data-id');
            document.getElementById('edit-nama').value = button.getAttribute('data-nama');
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