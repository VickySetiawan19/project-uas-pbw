<?php
session_start();
if (!isset($_SESSION['isLoggedIn']) || !$_SESSION['isLoggedIn']) {
    header("Location: login.php");
    exit();
}
echo "Selamat Datang ", $_SESSION['username'], " - ", $_SESSION['userid'];

require_once 'load.php';

try {
    // Mengambil data dari tabel buku beserta nama penulis
    $stmt = $koneksi->query(
        "SELECT buku.id, buku.judul, buku.tahun, penulis.nama AS penulis 
         FROM buku 
         LEFT JOIN penulis ON buku.penulis_id = penulis.id 
         WHERE buku.isdel = 0"
    );
} catch (PDOException $e) {
    die("Gagal mengambil data: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container my-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>Data Buku</h1>
            <div>
                <a href="logout.php" class="btn btn-danger">Log Out</a>
                <a href="input.php" class="btn btn-primary">Tambah Data</a>
            </div>
        </div>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Tahun Terbit</th>
                    <th>Penulis</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                while ($bukus = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <tr>
                        <td><?php echo $no; ?></td>
                        <td><?php echo htmlspecialchars($bukus['judul']); ?></td>
                        <td><?php echo htmlspecialchars($bukus['tahun']); ?></td>
                        <td><?php echo htmlspecialchars($bukus['penulis']); ?></td>
                        <td>
                            <a href="edit.php?id=<?php echo urlencode($bukus['id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete.php?id=<?php echo urlencode($bukus['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?');">Hapus</a>
                        </td>
                    </tr>
                    <?php
                    $no++;
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
