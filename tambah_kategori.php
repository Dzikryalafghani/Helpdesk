<?php
session_start();
require_once "koneksi.php";
if (!isset($_SESSION["id_user"]) || ($_SESSION["role"] ?? "") !== "sarpras") {
    header("Location: auth/login.php");
    exit;
}
$nama_user = htmlspecialchars($_SESSION["nama"] ?? "Pengguna", ENT_QUOTES, "UTF-8");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kategori | Helpdesk</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
<div class="app-layout">
    <aside class="sidebar">
        <div class="brand"><div class="brand-mark">H</div><div><span class="brand-name">Helpdesk</span><span class="brand-caption">Sistem Pengaduan Sekolah</span></div></div>
        <div class="nav-label">Menu utama</div>
        <nav><a class="nav-link" href="dashboard_sarpras.php"><span class="nav-icon">▦</span>Dashboard</a><a class="nav-link active" href="kategori.php"><span class="nav-icon">▤</span>Kelola Kategori</a></nav>
        <div class="sidebar-bottom"><div class="profile-mini"><span class="profile-name"><?= $nama_user ?></span><span class="profile-role">Admin Sarpras</span></div><a class="logout-link" href="auth/logout.php">↪ Keluar</a></div>
    </aside>
    <main class="main-content">
        <div class="topbar"><span class="breadcrumb">Data Master / Kategori / Tambah</span><span class="topbar-user">Admin Sarpras</span></div>
        <h1 class="page-heading">Tambah Kategori</h1>
        <p class="page-subtitle">Tambahkan kategori baru untuk laporan pengaduan.</p>
        <section class="content-card form-card">
            <div class="card-heading"><div><h2>Informasi kategori</h2><p>Lengkapi data berikut dengan benar.</p></div></div>
            <form action="proses_tambah_kategori.php" method="POST">
                <label for="nama_kategori">Nama Kategori</label>
                <input type="text" id="nama_kategori" name="nama_kategori" placeholder="Contoh: Komputer" required>
                <label for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" placeholder="Jelaskan kategori ini..." required></textarea>
                <div class="form-actions"><button type="submit">Simpan Kategori</button><a class="btn btn-secondary" href="kategori.php">Batal</a></div>
            </form>
        </section>
    </main>
</div>
</body>
</html>
