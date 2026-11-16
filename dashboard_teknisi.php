<?php
session_start();

if (!isset($_SESSION["id_user"]) || $_SESSION["role"] !== "teknisi") {
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
    <title>Dashboard Teknisi - Helpdesk</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>

<div class="dashboard-layout">

    <aside class="sidebar">
        <h2>Helpdesk</h2>

        <p>MENU UTAMA</p>
        <a href="dashboard_teknisi.php" class="active">Dashboard</a>

        <p>AKUN</p>
        <a href="auth/logout.php">Keluar</a>
    </aside>

    <main class="main-content">

        <div class="topbar">
            <h1>Dashboard Teknisi</h1>
            <span><?= $nama ?></span>
        </div>

        <div class="welcome-card">
            <h2>Selamat datang, <?= $nama ?>!</h2>
            <p>
                Selamat datang di dashboard teknisi.
                Halaman ini digunakan untuk mengelola tugas
                penanganan pengaduan fasilitas sekolah.
            </p>
        </div>

        <div class="info-grid">

            <div class="info-card">
                <p>Total Tugas</p>
                <h2>0</h2>
            </div>

            <div class="info-card">
                <p>Dalam Proses</p>
                <h2>0</h2>
            </div>

            <div class="info-card">
                <p>Selesai</p>
                <h2>0</h2>
            </div>

        </div>

        <div class="page-card">
            <h2>Informasi Tugas</h2>
            <p>
                Fitur daftar tugas teknisi akan dikembangkan
                pada tahap berikutnya.
            </p>
        </div>

    </main>

</div>

</body>
</html>