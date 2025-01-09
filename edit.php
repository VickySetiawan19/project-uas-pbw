<?php
include "load.php";

session_start();

// Periksa apakah user sudah login
if (!isset($_SESSION['isLoggedIn']) || !$_SESSION['isLoggedIn']) {
    header("location: login.php");
    exit();
}

// Ambil ID buku dari query string
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$id) {
    echo "<script>alert('ID tidak ditemukan!');</script>";
    header("Location: home.php");
    exit();
}

try {
    // Ambil data buku berdasarkan ID
    $stmt = $koneksi->prepare("
        SELECT buku.*, penulis.nama AS nama_penulis 
        FROM buku 
        LEFT JOIN penulis ON buku.penulis_id = penulis.id 
        WHERE buku.id = ? AND buku.isdel = 0
    ");
    $stmt->execute([$id]);

    if ($stmt->rowCount() === 1) {
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        echo "<script>alert('Data Tidak Ditemukan!');</script>";
        header("Location: home.php");
        exit();
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Data Buku</title>
</head>
<body>
    <h1>Edit Data Buku</h1>
    <form method="POST" action="input_data.php">
        <label for="judul">Judul:</label>
        <input type="text" name="judul" id="judul" value="<?php echo htmlspecialchars($data['judul']); ?>" required> <br>

        <label for="tahun">Tahun Terbit:</label>
        <input type="number" name="tahun" id="tahun" value="<?php echo htmlspecialchars($data['tahun']); ?>" required> <br>

        <label for="penulis">Nama Penulis:</label>
        <input type="text" name="penulis" id="penulis" value="<?php echo htmlspecialchars($data['nama_penulis']); ?>" required> <br>

        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
        <button type="submit">Simpan</button>
    </form>
</body>
</html>
