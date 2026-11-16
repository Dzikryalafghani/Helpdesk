
<?php
session_start();

if (!isset($_SESSION["id_user"]) || $_SESSION["role"] !== "siswa") {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . "/koneksi.php";

$id_user = (int) $_SESSION["id_user"];

$query = mysqli_prepare(
    $conn,
    "SELECT tiket.id_tiket, tiket.judul,
            tiket.status, tiket.tanggal_lapor,
            tiket.foto,
            kategori.nama_kategori
     FROM tiket
     JOIN kategori
        ON tiket.id_kategori = kategori.id_kategori
     WHERE tiket.id_pelapor = ?
     ORDER BY tiket.tanggal_lapor DESC"
);

mysqli_stmt_bind_param($query, "i", $id_user);
mysqli_stmt_execute($query);

$result = mysqli_stmt_get_result($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pengaduan - Helpdesk</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>

<div class="dashboard-layout">

    <aside class="sidebar">
        <h2>Helpdesk</h2>

        <p>MENU UTAMA</p>
        <a href="dashboard_siswa.php">Dashboard</a>
        <a href="tambah_tiket.php">Buat Pengaduan</a>
        <a href="riwayat_pengaduan.php" class="active">
            Riwayat Pengaduan
        </a>

        <p>AKUN</p>
        <a href="auth/logout.php">Keluar</a>
    </aside>

    <main class="main-content">

        <div class="topbar">
            <h1>Riwayat Pengaduan</h1>
            <span><?= htmlspecialchars($_SESSION["nama"]) ?></span>
        </div>

        <div class="page-card">

            <h2>Daftar Pengaduan Saya</h2>
            <p>Berikut adalah daftar pengaduan yang pernah kamu buat.</p>

            <div class="table-wrapper">
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Foto</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0): ?>

                            <?php while ($tiket = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td>
                                        #<?= $tiket["id_tiket"] ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($tiket["judul"]) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($tiket["nama_kategori"]) ?>
                                    </td>

                                    <td>
                                        <?php if (!empty($tiket["foto"])): ?>
                                            <img
                                                src="<?= htmlspecialchars($tiket["foto"]) ?>"
                                                alt="Foto pengaduan"
                                                class="foto-pengaduan"
                                            >
                                        <?php else: ?>
                                            <span>-</span>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <td>
                                        <?= date(
                                            "d-m-Y H:i",
                                            strtotime($tiket["tanggal_lapor"])
                                        ) ?>
                                    </td>

                                    <td>
                                        <span class="status-label">
                                            <?= htmlspecialchars($tiket["status"]) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endwhile; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="6" class="empty-data">
                                    Belum ada pengaduan.
                                </td>
                            </tr>

                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>

    </main>

</div>

</body>
</html>

<?php
mysqli_stmt_close($query);
?>
