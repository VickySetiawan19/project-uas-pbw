<?php
include "load.php";
session_start();

if (!isset($_SESSION['isLoggedIn']) || !$_SESSION['isLoggedIn']) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $judul = isset($_POST['judul']) ? trim($_POST['judul']) : null;
    $tahun = isset($_POST['tahun']) ? trim($_POST['tahun']) : null;
    $penulis = isset($_POST['penulis']) ? trim($_POST['penulis']) : null;

    if (empty($judul) || empty($tahun) || empty($penulis)) {
        die("Judul, Tahun, dan Penulis tidak boleh kosong!");
    }

    try {
        // Cek apakah penulis sudah ada di tabel penulis
        $stmt = $koneksi->prepare("SELECT id FROM penulis WHERE nama = ?");
        $stmt->execute([$penulis]);
        $penulisData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$penulisData) {
            // Jika penulis belum ada, tambahkan ke tabel penulis
            $stmt = $koneksi->prepare("INSERT INTO penulis (nama, created_by, created_at) VALUES (?, ?, ?)");
            $stmt->execute([
                $penulis,
                $_SESSION['userid'],
                date("Y-m-d H:i:s")     
            ]);
            // Ambil ID penulis yang baru saja ditambahkan
            $penulisId = $koneksi->lastInsertId();
        } else {
            // Jika penulis sudah ada, gunakan ID-nya
            $penulisId = $penulisData['id'];
        }

        $stmt = $koneksi->prepare("INSERT INTO buku (judul, tahun, penulis_id, created_by, created_at) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $judul,
            $tahun,
            $penulisId,
            $_SESSION['userid'],
            date("Y-m-d H:i:s")
        ]);

        header("Location: home.php");
        exit();
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>
