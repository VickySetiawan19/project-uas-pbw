<?php
include "load.php";

session_start();

if (!isset($_SESSION['isLoggedIn']) || !$_SESSION['isLoggedIn']) {
    header("Location: login.php");
    exit();
}

$id = isset($_POST['id']) ? intval($_POST['id']) : null;
$judul = isset($_POST['judul']) ? trim($_POST['judul']) : null;
$tahun = isset($_POST['tahun']) ? intval($_POST['tahun']) : null;
$penulis_id = isset($_POST['penulis_id']) ? intval($_POST['penulis_id']) : null;

if (!$id || !$judul || !$tahun || !$penulis_id) {
    die("Semua data wajib diisi!");
}

try {
    $stmt = $koneksi->prepare("
        UPDATE buku 
        SET judul = ?, tahun = ?, penulis_id = ?, updated_by = ?, updated_at = ? 
        WHERE id = ? AND isdel = 0
    ");
    $result = $stmt->execute([
        $judul,
        $tahun,
        $penulis_id,
        $_SESSION['userid'],         
        date("Y-m-d H:i:s"),         
        $id                          
    ]);

    if ($result) {
        echo "<script>alert('Data berhasil diperbarui!');</script>";
    } else {
        print_r($stmt->errorInfo());
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

header("Location: home.php");
exit();
?>
