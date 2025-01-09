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
</head>
<body>
    <h1>Data Buku</h1>
    <table border="1">
        <tr>
            <th>No</th>
            <th>Judul</th>
            <th>Tahun Terbit</th>
            <th>Penulis</th>
            <th>Aksi</th>
        </tr>

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
                    <a href="edit.php?id=<?php echo urlencode($bukus['id']); ?>">Edit</a> |
                    <a href="delete.php?id=<?php echo urlencode($bukus['id']); ?>" onclick="return confirm('Yakin ingin menghapus data ini?');">Hapus</a>
                </td>
            </tr>
            <?php
            $no++;
        }
        ?>
    </table>

    <br>
    <a href="logout.php">Log Out</a> | 
    <a href="input.php">Tambah Data</a>
</body>
</html>
