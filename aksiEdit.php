<?php
include "load.php";

session_start();

// Periksa apakah user sudah login
if (!isset($_SESSION['isLoggedIn']) || !$_SESSION['isLoggedIn']) {
    header("Location: login.php");
    exit();
}

// Ambil data dari form
$id = isset($_POST['id']) ? intval($_POST['id']) : null;
$judul = isset($_POST['judul']) ? trim($_POST['judul']) : null;
$tahun = isset($_POST['tahun']) ? intval($_POST['tahun']) : null;
$penulis_id = isset($_POST['penulis_id']) ? intval($_POST['penulis_id']) : null;

// Validasi data
if (!$id || !$judul || !$tahun || !$penulis_id) {
    die("Semua data wajib diisi!");
}

try {
    // Update data buku
    $stmt = $koneksi->prepare("
        UPDATE buku 
        SET judul = ?, tahun = ?, penulis_id = ?, updated_by = ?, updated_at = ? 
        WHERE id = ? AND isdel = 0
    ");
    $result = $stmt->execute([
        $judul,
        $tahun,
        $penulis_id,
        $_SESSION['userid'],         // User yang mengupdate
        date("Y-m-d H:i:s"),         // Waktu update
        $id                          // ID buku
    ]);

    if ($result) {
        echo "<script>alert('Data berhasil diperbarui!');</script>";
    } else {
        print_r($stmt->errorInfo());
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Redirect kembali ke halaman utama
header("Location: home.php");
exit();
?>
