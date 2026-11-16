
<?php
session_start();

if (!isset($_SESSION["id_user"]) || $_SESSION["role"] !== "siswa") {
    header("Location: auth/login.php");
    exit;
}

require_once __DIR__ . "/koneksi.php";

$pesan_error = "";

// Mengambil daftar kategori
$query_kategori = mysqli_query(
    $conn,
    "SELECT * FROM kategori ORDER BY nama_kategori ASC"
);

// Proses ketika form dikirim
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id_pelapor = (int) $_SESSION["id_user"];
    $id_kategori = (int) $_POST["id_kategori"];
    $judul = trim($_POST["judul"]);
    $deskripsi = trim($_POST["deskripsi"]);
    $lokasi = trim($_POST["lokasi"]);

    $foto = null;

    // Validasi input
    if (
        $id_kategori <= 0 ||
        $judul === "" ||
        $deskripsi === "" ||
        $lokasi === ""
    ) {
        $pesan_error = "Semua kolom wajib diisi kecuali foto.";
    } else {

        // Memastikan kategori benar-benar tersedia
        $cek = mysqli_prepare(
            $conn,
            "SELECT id_kategori FROM kategori WHERE id_kategori = ?"
        );

        mysqli_stmt_bind_param($cek, "i", $id_kategori);
        mysqli_stmt_execute($cek);
        mysqli_stmt_store_result($cek);

        if (mysqli_stmt_num_rows($cek) === 0) {
            $pesan_error = "Kategori tidak ditemukan.";
        }

        mysqli_stmt_close($cek);

        // Proses upload foto jika ada
        if (
            $pesan_error === "" &&
            isset($_FILES["foto"]) &&
            $_FILES["foto"]["error"] !== UPLOAD_ERR_NO_FILE
        ) {
            if ($_FILES["foto"]["error"] !== UPLOAD_ERR_OK) {
                $pesan_error = "Upload foto gagal.";
            } elseif ($_FILES["foto"]["size"] > 2 * 1024 * 1024) {
                $pesan_error = "Ukuran foto maksimal 2 MB.";
            } else {
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($finfo, $_FILES["foto"]["tmp_name"]);
                finfo_close($finfo);

                $jenis_foto = [
                    "image/jpeg" => "jpg",
                    "image/png" => "png",
                    "image/webp" => "webp"
                ];

                if (!isset($jenis_foto[$mime])) {
                    $pesan_error = "Foto harus berformat JPG, PNG, atau WEBP.";
                } else {
                    $folder = __DIR__ . "/uploads/tiket";

                    if (!is_dir($folder)) {
                        mkdir($folder, 0755, true);
                    }

                    $nama_file = bin2hex(random_bytes(16))
                        . "." . $jenis_foto[$mime];

                    $tujuan = $folder . "/" . $nama_file;

                    if (move_uploaded_file(
                        $_FILES["foto"]["tmp_name"],
                        $tujuan
                    )) {
                        $foto = "uploads/tiket/" . $nama_file;
                    } else {
                        $pesan_error = "Foto gagal disimpan.";
                    }
                }
            }
        }

        // Simpan pengaduan ke database
        if ($pesan_error === "") {

            $stmt = mysqli_prepare(
                $conn,
                "INSERT INTO tiket
                (id_pelapor, id_kategori, judul, deskripsi, lokasi, foto)
                VALUES (?, ?, ?, ?, ?, ?)"
            );

            mysqli_stmt_bind_param(
                $stmt,
                "iissss",
                $id_pelapor,
                $id_kategori,
                $judul,
                $deskripsi,
                $lokasi,
                $foto
            );


            mysqli_begin_transaction($conn);

            if (mysqli_stmt_execute($stmt)) {

                // Mengambil ID tiket yang baru dibuat
                $id_tiket = mysqli_insert_id($conn);

                // Membuat riwayat status awal
                $status_sebelumnya = null;
                $status_baru = "diajukan";
                $catatan = "Pengaduan berhasil dibuat oleh siswa.";

                $stmt_riwayat = mysqli_prepare(
                    $conn,
                    "INSERT INTO riwayat_status
                    (id_tiket, id_user, status_sebelumnya, status_baru, catatan)
                    VALUES (?, ?, ?, ?, ?)"
                );

                mysqli_stmt_bind_param(
                    $stmt_riwayat,
                    "iisss",
                    $id_tiket,
                    $id_pelapor,
                    $status_sebelumnya,
                    $status_baru,
                    $catatan
                );

                if (mysqli_stmt_execute($stmt_riwayat)) {

                    mysqli_commit($conn);

                    mysqli_stmt_close($stmt_riwayat);
                    mysqli_stmt_close($stmt);

                    header("Location: dashboard_siswa.php?status=berhasil");
                    exit;

                } else {

                    mysqli_rollback($conn);

                    mysqli_stmt_close($stmt_riwayat);

                    if ($foto !== null && file_exists(__DIR__ . "/" . $foto)) {
                        unlink(__DIR__ . "/" . $foto);
                    }

                    $pesan_error = "Riwayat pengaduan gagal disimpan.";
                }

            } else {

                mysqli_rollback($conn);

                if ($foto !== null && file_exists(__DIR__ . "/" . $foto)) {
                    unlink(__DIR__ . "/" . $foto);
                }

                $pesan_error = "Pengaduan gagal disimpan.";
            }

            mysqli_stmt_close($stmt);

        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Pengaduan - Helpdesk</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>

<div class="dashboard-layout">

    <aside class="sidebar">
        <h2>Helpdesk</h2>

        <p>MENU UTAMA</p>
        <a href="dashboard_siswa.php">Dashboard</a>
        <a href="tambah_tiket.php" class="active">Buat Pengaduan</a>
        <a href="riwayat_pengaduan.php">Riwayat Pengaduan</a>

        <p>AKUN</p>
        <a href="auth/logout.php">Keluar</a>
    </aside>

    <main class="main-content">

        <div class="topbar">
            <h1>Buat Pengaduan</h1>
            <span><?= htmlspecialchars($_SESSION["nama"]) ?></span>
        </div>

        <div class="page-card">

            <h2>Form Pengaduan</h2>
            <p>Silakan isi informasi kerusakan fasilitas sekolah.</p>

            <?php if ($pesan_error !== ""): ?>
                <div class="alert-error">
                    <?= htmlspecialchars($pesan_error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">

                <div class="form-group">
                    <label>Kategori Pengaduan</label>

                    <select name="id_kategori" required>
                        <option value="">-- Pilih Kategori --</option>

                        <?php while ($kategori = mysqli_fetch_assoc($query_kategori)): ?>
                            <option
                                value="<?= $kategori["id_kategori"] ?>"
                                <?= (
                                    isset($_POST["id_kategori"]) &&
                                    $_POST["id_kategori"] == $kategori["id_kategori"]
                                ) ? "selected" : "" ?>
                            >
                                <?= htmlspecialchars($kategori["nama_kategori"]) ?>
                            </option>
                        <?php endwhile; ?>

                    </select>
                </div>

                <div class="form-group">
                    <label>Judul Pengaduan</label>
                    <input
                        type="text"
                        name="judul"
                        maxlength="150"
                        placeholder="Contoh: Komputer tidak bisa menyala"
                        value="<?= htmlspecialchars($_POST["judul"] ?? "") ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>Deskripsi Kerusakan</label>
                    <textarea
                        name="deskripsi"
                        rows="5"
                        placeholder="Jelaskan kerusakan yang terjadi..."
                        required
                    ><?= htmlspecialchars($_POST["deskripsi"] ?? "") ?></textarea>
                </div>

                <div class="form-group">
                    <label>Lokasi Kerusakan</label>
                    <input
                        type="text"
                        name="lokasi"
                        maxlength="150"
                        placeholder="Contoh: Lab PPLG 1"
                        value="<?= htmlspecialchars($_POST["lokasi"] ?? "") ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>Foto Pendukung (Opsional)</label>
                    <input
                        type="file"
                        name="foto"
                        accept=".jpg,.jpeg,.png,.webp"
                    >
                    <small>Maksimal 2 MB. Format JPG, PNG, atau WEBP.</small>
                </div>

                <button type="submit" class="btn-primary">
                    Kirim Pengaduan
                </button>

                <a href="dashboard_siswa.php" class="btn-back">
                    Kembali
                </a>

            </form>

        </div>

    </main>

</div>

</body>
</html>
