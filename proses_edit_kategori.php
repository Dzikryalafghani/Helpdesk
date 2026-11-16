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
    header("Location: kategori.php");
    exit;
}

$id = filter_input(INPUT_POST, "id_kategori", FILTER_VALIDATE_INT);
$nama = trim($_POST["nama_kategori"] ?? "");
$deskripsi = trim($_POST["deskripsi"] ?? "");

if (!$id || $id < 1 || $nama === "" || $deskripsi === "") {
    die("Data kategori tidak valid atau belum lengkap.");
}

$sql = "UPDATE kategori
        SET nama_kategori = ?, deskripsi = ?
        WHERE id_kategori = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ssi", $nama, $deskripsi, $id);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    header("Location: kategori.php");
    exit;
}

mysqli_stmt_close($stmt);
die("Gagal memperbarui kategori.");
?>
