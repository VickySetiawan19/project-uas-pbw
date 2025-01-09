<?php
include "load.php";

session_start();

// Periksa apakah user sudah login
if (!isset($_SESSION['isLoggedIn']) || !$_SESSION['isLoggedIn']) {
    header("Location: login.php");
    exit();
}

// Ambil ID buku dari query string
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$id) {
    die("ID buku tidak ditemukan!");
}

try {
    // Pastikan ID buku valid dengan mengambil data buku dan relasinya
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

    // Update kolom isdel untuk buku tanpa menyentuh updated_at
    $stmt = $koneksi->prepare("
        UPDATE buku 
        SET isdel = ?, deleted_by = ?, deleted_at = ? 
        WHERE id = ?
    ");
    $stmt->execute([
        1,                          // Menandai sebagai dihapus
        $_SESSION['userid'],        // ID pengguna yang menghapus
        date("Y-m-d H:i:s"),        // Tanggal penghapusan
        $id                         // ID buku
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
        // Soft delete penulis jika tidak ada buku lain
        $stmt = $koneksi->prepare("
            UPDATE penulis 
            SET isdel = ?, deleted_by = ?, deleted_at = ?
            WHERE id = ?
        ");
        $stmt->execute([
            1,                          // Menandai sebagai dihapus
            $_SESSION['userid'],        // ID pengguna yang menghapus
            date("Y-m-d H:i:s"),        // Tanggal penghapusan
            $buku['penulis_id']         // ID penulis
        ]);
    }

    // Redirect ke halaman utama setelah berhasil menghapus
    header("Location: home.php");
    exit();
} catch (PDOException $e) {
    die("Error saat menghapus data: " . $e->getMessage());
}
?>
