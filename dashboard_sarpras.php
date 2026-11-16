<?php
session_start();

if (!isset($_SESSION["id_user"]) || $_SESSION["role"] !== "sarpras") {
    header("Location: login.php");
    exit;
}

$nama = htmlspecialchars($_SESSION["nama"]);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin Sarpras - Helpdesk</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>

<div class="dashboard-layout">

    <aside class="sidebar">
        <h2>Helpdesk</h2>

        <p>MENU UTAMA</p>
        <a href="dashboard_sarpras.php" class="active">Dashboard</a>
        <a href="kategori.php">Data Kategori</a>

        <p>AKUN</p>
        <a href="auth/logout.php">Keluar</a>
    </aside>

    <main class="main-content">

        <div class="topbar">
            <h1>Dashboard Admin Sarpras</h1>
            <span><?= $nama ?></span>
        </div>

        <div class="welcome-card">
            <h2>Selamat datang, <?= $nama ?>!</h2>
            <p>
                Kelola data kategori, pengaduan, dan penugasan
                teknisi melalui sistem Helpdesk Sekolah.
            </p>
        </div>

        <div class="info-grid">

            <div class="info-card">
                <p>Total Pengaduan</p>
                <h2>0</h2>
            </div>

            <div class="info-card">
                <p>Pengaduan Diproses</p>
                <h2>0</h2>
            </div>

            <div class="info-card">
                <p>Pengaduan Selesai</p>
                <h2>0</h2>
            </div>

        </div>

        <div class="page-card">
            <h2>Menu Pengelolaan</h2>

            <p style="margin-bottom: 18px;">
                Pilih menu berikut untuk mengelola data sistem.
            </p>

            <a href="kategori.php" class="btn">
                Kelola Data Kategori
            </a>
        </div>

    </main>

</div>

</body>
</html>