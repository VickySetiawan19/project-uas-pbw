<?php
include "load.php";

session_start();

if (!isset($_SESSION['isLoggedIn']) || !$_SESSION['isLoggedIn']) {
    header("Location: login.php");
    exit();
}

$id = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$id) {
    die("ID buku tidak ditemukan!");
}

try {
    $stmt = $koneksi->prepare("
        SELECT buku.id, buku.judul, buku.penulis_id, penulis.nama as penulis
        FROM buku
        LEFT JOIN penulis ON buku.penulis_id = penulis.id
        WHERE buku.id = ? AND buku.isdel = 0
    ");
    $stmt->execute([$id]);
    $buku = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$buku) {
        die("Buku dengan ID ini tidak ditemukan atau sudah dihapus!");
    }

    $stmt = $koneksi->prepare("
        UPDATE buku 
        SET isdel = ?, deleted_by = ?, deleted_at = ? 
        WHERE id = ?
    ");
    $stmt->execute([
        1,                          
        $_SESSION['userid'],        
        date("Y-m-d H:i:s"),        
        $id                         
    ]);

    // Cek apakah penulis tidak memiliki buku lain
    $stmt = $koneksi->prepare("
        SELECT COUNT(*) 
        FROM buku 
        WHERE penulis_id = ? AND isdel = 0
    ");
    $stmt->execute([$buku['penulis_id']]);
    $jumlahBuku = $stmt->fetchColumn();

    if ($jumlahBuku == 0) {

        $stmt = $koneksi->prepare("
            UPDATE penulis 
            SET isdel = ?, deleted_by = ?, deleted_at = ?
            WHERE id = ?
        ");
        $stmt->execute([
            1,                          
            $_SESSION['userid'],        
            date("Y-m-d H:i:s"),        
            $buku['penulis_id']         
        ]);
    }

    header("Location: home.php");
    exit();
} catch (PDOException $e) {
    die("Error saat menghapus data: " . $e->getMessage());
}
?>
