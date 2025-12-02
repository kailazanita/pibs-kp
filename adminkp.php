<?php
require_once 'koneksi.php';

// Handle form submissions
$edit_mode = false;
$edit_data = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create':
                $nim = $_POST['nim'];
                $nama = $_POST['nama'];
                $prodi = $_POST['prodi'];
                
                $stmt = $conn->prepare("INSERT INTO mahasiswa (nim, nama, prodi) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $nim, $nama, $prodi);
                
                if ($stmt->execute()) {
                    echo "<script>alert('Data berhasil ditambahkan!');</script>";
                } else {
                    echo "<script>alert('Error: " . $stmt->error . "');</script>";
                }
                $stmt->close();
                break;
                
            case 'update':
                $nim = $_POST['nim'];
                $nama = $_POST['nama'];
                $prodi = $_POST['prodi'];
                
                $stmt = $conn->prepare("UPDATE mahasiswa SET nama = ?, prodi = ? WHERE nim = ?");
                $stmt->bind_param("sss", $nama, $prodi, $nim);
                
                if ($stmt->execute()) {
                    echo "<script>alert('Data berhasil diupdate!'); window.location.href = 'adminkp.php';</script>";
                } else {
                    echo "<script>alert('Error: " . $stmt->error . "');</script>";
                }
                $stmt->close();
                break;
                
            case 'delete':
                $nim = $_POST['nim'];
                
                $stmt = $conn->prepare("DELETE FROM mahasiswa WHERE nim = ?");
                $stmt->bind_param("s", $nim);
                
                if ($stmt->execute()) {
                    echo "<script>alert('Data berhasil dihapus!');</script>";
                } else {
                    echo "<script>alert('Error: " . $stmt->error . "');</script>";
                }
                $stmt->close();
                break;
        }
    }
}

// Handle edit request
if (isset($_GET['edit'])) {
    $edit_mode = true;
    $nim = $_GET['edit'];
    
    $stmt = $conn->prepare("SELECT * FROM mahasiswa WHERE nim = ?");
    $stmt->bind_param("s", $nim);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $edit_data = $result->fetch_assoc();
    }
    $stmt->close();
}

// Fetch all data for display
$result = $conn->query("SELECT * FROM mahasiswa ORDER BY nim");
?>

<!DOCTYPE html>
<html lang="id" class="admin-page">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Kelola Data Mahasiswa</title>
    <link rel="stylesheet" href="styles.css?v=2">
</head>
<body class="admin-page">
    <header>
        <a href="index.html">
            <img src="assets/images/upj.webp" alt="Logo">
        </a>
        <div class="title">
            <h1>Kerja Profesi</h1>
            <h2>Website Profil Kelompok</h2>
        </div>
        <div class="admin-btn">
            <a href="index.html">Home</a>
        </div>
    </header>
    
    <div class="admin-container">
        <h1>Admin Panel - Kelola Data Mahasiswa</h1>
        
        <!-- Form Section -->
        <div class="form-section <?= $edit_mode ? 'edit-mode' : '' ?>">
            <h2><?= $edit_mode ? 'Edit Data Mahasiswa' : 'Tambah Data Mahasiswa' ?></h2>
            <form method="POST">
                <input type="hidden" name="action" value="<?= $edit_mode ? 'update' : 'create' ?>">
                
                <div class="form-group">
                    <label for="nim">NIM:</label>
                    <input type="text" 
                           id="nim" 
                           name="nim" 
                           value="<?= $edit_mode ? htmlspecialchars($edit_data['nim']) : '' ?>" 
                           <?= $edit_mode ? 'readonly' : '' ?> 
                           required>
                </div>
                
                <div class="form-group">
                    <label for="nama">Nama:</label>
                    <input type="text" 
                           id="nama" 
                           name="nama" 
                           value="<?= $edit_mode ? htmlspecialchars($edit_data['nama']) : '' ?>" 
                           required>
                </div>
                
                <div class="form-group">
                    <label for="prodi">Program Studi:</label>
                    <input type="text" 
                           id="prodi" 
                           name="prodi" 
                           value="<?= $edit_mode ? htmlspecialchars($edit_data['prodi']) : '' ?>" 
                           required>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn <?= $edit_mode ? 'btn-success' : 'btn-primary' ?>">
                        <?= $edit_mode ? 'Update Data' : 'Tambah Data' ?>
                    </button>
                    
                    <?php if ($edit_mode): ?>
                        <a href="adminkp.php" class="btn btn-secondary">Batal</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
        
        <!-- Table Section -->
        <div class="table-section">
            <h2>Data Mahasiswa</h2>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Program Studi</th>
                        <th>Kelola</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php $no = 1; ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($row['nim']) ?></td>
                                <td><?= htmlspecialchars($row['nama']) ?></td>
                                <td><?= htmlspecialchars($row['prodi']) ?></td>
                                <td class="action-buttons">
                                    <a href="adminkp.php?edit=<?= urlencode($row['nim']) ?>" class="btn btn-warning">Ubah</a>
                                    
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="nim" value="<?= htmlspecialchars($row['nim']) ?>">
                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 20px; color: #666;">Tidak ada data mahasiswa</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <footer>
        <p>&copy; 2024 Kelompok Kerja Profesi. All rights reserved.</p>
    </footer>
    
    <script>
        // Auto-hide alerts after 3 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('script');
            alerts.forEach(alert => {
                if (alert.textContent.includes('alert')) {
                    alert.style.display = 'none';
                }
            });
        }, 3000);
    </script>
</body>
</html>

<?php
$conn->close();
?>