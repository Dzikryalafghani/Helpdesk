<?php
session_start();
require_once "koneksi.php";
if (!isset($_SESSION["id_user"]) || ($_SESSION["role"] ?? "") !== "sarpras") {
    header("Location: auth/login.php");
    exit;
}
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
if (!$id || $id < 1) { header("Location: kategori.php"); exit; }
$sql = "SELECT * FROM kategori WHERE id_kategori = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$kategori = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);
if (!$kategori) { header("Location: kategori.php"); exit; }
$nama_user = htmlspecialchars($_SESSION["nama"] ?? "Pengguna", ENT_QUOTES, "UTF-8");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kategori | Helpdesk</title>
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
        <div class="topbar"><span class="breadcrumb">Data Master / Kategori / Edit</span><span class="topbar-user">Admin Sarpras</span></div>
        <h1 class="page-heading">Edit Kategori</h1>
        <p class="page-subtitle">Perbarui informasi kategori yang dipilih.</p>
        <section class="content-card form-card">
            <div class="card-heading"><div><h2>Informasi kategori</h2><p>Ubah data sesuai kebutuhan.</p></div></div>
            <form action="proses_edit_kategori.php" method="POST">
                <input type="hidden" name="id_kategori" value="<?= (int) $kategori['id_kategori'] ?>">
                <label for="nama_kategori">Nama Kategori</label>
                <input type="text" id="nama_kategori" name="nama_kategori" value="<?= htmlspecialchars($kategori['nama_kategori'], ENT_QUOTES, 'UTF-8') ?>" required>
                <label for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" required><?= htmlspecialchars($kategori['deskripsi'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                <div class="form-actions"><button type="submit">Simpan Perubahan</button><a class="btn btn-secondary" href="kategori.php">Batal</a></div>
            </form>
        </section>
    </main>
</div>
</body>
</html>
