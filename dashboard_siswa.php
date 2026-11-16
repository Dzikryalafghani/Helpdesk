<?php
session_start();

if (!isset($_SESSION["id_user"]) || $_SESSION["role"] !== "siswa") {
    header("Location: login.php");
    exit;
}

$nama = htmlspecialchars($_SESSION["nama"]);

require_once __DIR__ . "/koneksi.php";

$id_user = (int) $_SESSION["id_user"];

$query_total = mysqli_prepare(
    $conn,
    "SELECT COUNT(*) AS total
     FROM tiket
     WHERE id_pelapor = ?"
);

mysqli_stmt_bind_param($query_total, "i", $id_user);
mysqli_stmt_execute($query_total);

$hasil_total = mysqli_stmt_get_result($query_total);
$data_total = mysqli_fetch_assoc($hasil_total);

$total_pengaduan = $data_total["total"];

$query_diproses = mysqli_prepare(
    $conn,
    "SELECT COUNT(*) AS total
     FROM tiket
     WHERE id_pelapor = ? AND status = 'diproses'"
);

mysqli_stmt_bind_param($query_diproses, "i", $id_user);
mysqli_stmt_execute($query_diproses);

$data_diproses = mysqli_fetch_assoc(
    mysqli_stmt_get_result($query_diproses)
);

$total_diproses = $data_diproses["total"];

$query_selesai = mysqli_prepare(
    $conn,
    "SELECT COUNT(*) AS total
     FROM tiket
     WHERE id_pelapor = ? AND status = 'selesai'"
);

mysqli_stmt_bind_param($query_selesai, "i", $id_user);
mysqli_stmt_execute($query_selesai);

$data_selesai = mysqli_fetch_assoc(
    mysqli_stmt_get_result($query_selesai)
);

$total_selesai = $data_selesai["total"];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa - Helpdesk</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>

<div class="dashboard-layout">

    <aside class="sidebar">
        <h2>Helpdesk</h2>

        <p>MENU UTAMA</p>
        <a href="dashboard_siswa.php" class="active">Dashboard</a>
        <a href="tambah_tiket.php">Buat Pengaduan</a>
        <a href="riwayat_pengaduan.php">Riwayat Pengaduan</a>

        <p>AKUN</p>
        <a href="auth/logout.php">Keluar</a>
    </aside>

    <main class="main-content">

        <div class="topbar">
            <h1>Dashboard Siswa</h1>
            <span><?= $nama ?></span>
        </div>

        <div class="welcome-card">
            <h2>Selamat datang, <?= $nama ?>!</h2>
            <p>
                Selamat datang di sistem Helpdesk Sekolah.
                Di sini kamu dapat mengakses layanan pengaduan
                fasilitas dan sarana prasarana sekolah.
            </p>
        </div>

        <div class="info-grid">

            <div class="info-card">
                <p>Pengaduan</p>
                <h2><?= $total_pengaduan ?></h2>
            </div>

            <div class="info-card">
                <p>Diproses</p>
                <h2><?= $total_diproses ?></h2>
            </div>

            <div class="info-card">
                <p>Selesai</p>
                <h2><?= $total_selesai ?></h2>
            </div>

        </div>

        <div class="page-card">
            <h2>Informasi</h2>
            <p>
                Fitur pengaduan dan riwayat pengaduan
                akan tersedia setelah proses pengembangan berikutnya.
            </p>
        </div>

    </main>

</div>

</body>
</html>