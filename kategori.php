
<?php
session_start();
require_once __DIR__ . "/koneksi.php";


if (!isset($_SESSION["id_user"]) || $_SESSION["role"] !== "sarpras") {
    header("Location: login.php");
    exit;
}

$pesan = "";
$tipe_pesan = "";


if (isset($_POST["tambah"])) {
    $nama = trim($_POST["nama_kategori"] ?? "");
    $deskripsi = trim($_POST["deskripsi"] ?? "");

    if ($nama === "") {
        $pesan = "Nama kategori wajib diisi.";
        $tipe_pesan = "error";
    } else {
        $sql = "INSERT INTO kategori (nama_kategori, deskripsi) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $nama, $deskripsi);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: kategori.php?status=tambah");
            exit;
        } else {
            $pesan = "Gagal menambahkan kategori.";
            $tipe_pesan = "error";
        }
        mysqli_stmt_close($stmt);
    }
}


if (isset($_POST["edit"])) {
    $id = (int) $_POST["id_kategori"];
    $nama = trim($_POST["nama_kategori"] ?? "");
    $deskripsi = trim($_POST["deskripsi"] ?? "");

    if ($nama === "") {
        $pesan = "Nama kategori wajib diisi.";
        $tipe_pesan = "error";
    } else {
        $sql = "UPDATE kategori
                SET nama_kategori = ?, deskripsi = ?
                WHERE id_kategori = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssi", $nama, $deskripsi, $id);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: kategori.php?status=edit");
            exit;
        } else {
            $pesan = "Gagal mengubah kategori.";
            $tipe_pesan = "error";
        }
        mysqli_stmt_close($stmt);
    }
}


if (isset($_POST["hapus"])) {
    $id = (int) $_POST["id_kategori"];

    $sql = "DELETE FROM kategori WHERE id_kategori = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: kategori.php?status=hapus");
        exit;
    } else {
        $pesan = "Kategori tidak dapat dihapus. Mungkin masih digunakan oleh data pengaduan.";
        $tipe_pesan = "error";
    }
    mysqli_stmt_close($stmt);
}


if (isset($_GET["status"])) {
    if ($_GET["status"] === "tambah") {
        $pesan = "Kategori berhasil ditambahkan.";
    } elseif ($_GET["status"] === "edit") {
        $pesan = "Kategori berhasil diperbarui.";
    } elseif ($_GET["status"] === "hapus") {
        $pesan = "Kategori berhasil dihapus.";
    }

    $tipe_pesan = "success";
}


$data_edit = null;

if (isset($_GET["edit"])) {
    $id_edit = (int) $_GET["edit"];

    $stmt = mysqli_prepare(
        $conn,
        "SELECT * FROM kategori WHERE id_kategori = ?"
    );
    mysqli_stmt_bind_param($stmt, "i", $id_edit);
    mysqli_stmt_execute($stmt);

    $hasil_edit = mysqli_stmt_get_result($stmt);
    $data_edit = mysqli_fetch_assoc($hasil_edit);

    mysqli_stmt_close($stmt);
}

// PAGINATION
$batas = 5;
$halaman = isset($_GET["halaman"]) ? (int) $_GET["halaman"] : 1;

if ($halaman < 1) {
    $halaman = 1;
}

$query_total = mysqli_query($conn, "SELECT COUNT(*) AS total FROM kategori");
$data_total = mysqli_fetch_assoc($query_total);

$total_data = (int) $data_total["total"];
$total_halaman = (int) ceil($total_data / $batas);

if ($total_halaman > 0 && $halaman > $total_halaman) {
    $halaman = $total_halaman;
}

$posisi = ($halaman - 1) * $batas;

$stmt = mysqli_prepare(
    $conn,
    "SELECT * FROM kategori
     ORDER BY id_kategori ASC
     LIMIT ? OFFSET ?"
);

mysqli_stmt_bind_param($stmt, "ii", $batas, $posisi);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$nama_admin = htmlspecialchars($_SESSION["nama"] ?? "Admin");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kategori - Helpdesk</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>

<div class="dashboard-layout">


    <aside class="sidebar">
        <h2>Helpdesk</h2>

        <p>MENU UTAMA</p>
        <a href="dashboard_sarpras.php">Dashboard</a>
        <a href="kategori.php" class="active">Data Kategori</a>

        <p>AKUN</p>
        <a href="logout.php">Keluar</a>
    </aside>


    <main class="main-content">

        <div class="topbar">
            <h1>Data Kategori</h1>
            <span><?= $nama_admin ?></span>
        </div>

        <div class="category-header">
            <div>
                <h1>Kelola Kategori</h1>
                <p>Tambah, ubah, dan hapus kategori pengaduan.</p>
            </div>
        </div>

        <!-- NOTIFIKASI -->
        <?php if ($pesan !== ""): ?>
            <div class="alert-<?= $tipe_pesan ?>">
                <?= htmlspecialchars($pesan) ?>
            </div>
        <?php endif; ?>


        <div class="category-card" id="form-kategori">

            <h2>
                <?= $data_edit ? "Edit Kategori" : "Tambah Kategori" ?>
            </h2>

            <form method="POST" class="category-form">

                <?php if ($data_edit): ?>
                    <input
                        type="hidden"
                        name="id_kategori"
                        value="<?= (int) $data_edit["id_kategori"] ?>"
                    >
                <?php endif; ?>

                <div class="form-group">
                    <label>Nama Kategori</label>
                    <input
                        type="text"
                        name="nama_kategori"
                        placeholder="Masukkan nama kategori"
                        value="<?= htmlspecialchars($data_edit["nama_kategori"] ?? "") ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea
                        name="deskripsi"
                        placeholder="Masukkan deskripsi kategori"
                    ><?= htmlspecialchars($data_edit["deskripsi"] ?? "") ?></textarea>
                </div>

                <?php if ($data_edit): ?>
                    <button type="submit" name="edit" class="btn">
                        Simpan Perubahan
                    </button>

                    <a href="kategori.php" class="btn btn-danger">
                        Batal
                    </a>
                <?php else: ?>
                    <button type="submit" name="tambah" class="btn">
                        + Tambah Kategori
                    </button>
                <?php endif; ?>

            </form>
        </div>


        <div class="category-card">

            <h2>Daftar Kategori</h2>

            <div class="table-wrapper">
                <table class="category-table">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Kategori</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if ($result && mysqli_num_rows($result) > 0): ?>

                            <?php while ($row = mysqli_fetch_assoc($result)): ?>

                                <tr>
                                    <td>
                                        <?= (int) $row["id_kategori"] ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row["nama_kategori"]) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row["deskripsi"] ?? "") ?>
                                    </td>

                                    <td>
                                        <div class="category-actions">

                                            <a
                                                href="kategori.php?edit=<?= (int) $row["id_kategori"] ?>"
                                                class="btn-edit"
                                            >
                                                Edit
                                            </a>

                                            <form method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">

                                                <input
                                                    type="hidden"
                                                    name="id_kategori"
                                                    value="<?= (int) $row["id_kategori"] ?>"
                                                >

                                                <button
                                                    type="submit"
                                                    name="hapus"
                                                    class="btn-delete"
                                                >
                                                    Hapus
                                                </button>

                                            </form>

                                        </div>
                                    </td>
                                </tr>

                            <?php endwhile; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="4" style="text-align:center;">
                                    Belum ada data kategori.
                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>
            </div>
            <!-- PAGINATION -->
            <?php if ($total_halaman > 1): ?>

            <div class="pagination">

                <?php if ($halaman > 1): ?>
                    <a href="?halaman=<?= $halaman - 1 ?>">
                        Sebelumnya
                    </a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_halaman; $i++): ?>

                    <a
                        href="?halaman=<?= $i ?>"
                        class="<?= ($halaman == $i) ? 'active' : '' ?>"
                    >
                        <?= $i ?>
                    </a>

                <?php endfor; ?>

                <?php if ($halaman < $total_halaman): ?>
                    <a href="?halaman=<?= $halaman + 1 ?>">
                        Berikutnya
                    </a>
                <?php endif; ?>

            </div>

            <?php endif; ?>     
        </div>

    </main>

</div>

</body>
</html>
