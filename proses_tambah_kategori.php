<?php
session_start();
require_once "koneksi.php";

if (
    !isset($_SESSION["id_user"]) ||
    ($_SESSION["role"] ?? "") !== "sarpras"
) {
    header("Location: auth/login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: tambah_kategori.php");
    exit;
}

$nama = trim($_POST["nama_kategori"] ?? "");
$deskripsi = trim($_POST["deskripsi"] ?? "");

if ($nama === "" || $deskripsi === "") {
    die("Nama kategori dan deskripsi wajib diisi.");
}

$sql = "INSERT INTO kategori (nama_kategori, deskripsi)
        VALUES (?, ?)";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ss", $nama, $deskripsi);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    header("Location: kategori.php");
    exit;
}

mysqli_stmt_close($stmt);
die("Gagal menyimpan kategori. Silakan periksa kembali.");
?>
