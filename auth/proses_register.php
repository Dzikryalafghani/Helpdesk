
<?php
session_start();
require_once __DIR__ . "/../koneksi.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: register.php");
    exit;
}

$nama = trim($_POST["nama"] ?? "");
$username = trim($_POST["username"] ?? "");
$password = $_POST["password"] ?? "";
$konfirmasi = $_POST["konfirmasi_password"] ?? "";

if ($nama === "" || $username === "" || $password === "") {
    $_SESSION["error"] = "Semua kolom wajib diisi.";
    header("Location: register.php");
    exit;
}

if (strlen($password) < 8) {
    $_SESSION["error"] = "Password minimal 8 karakter.";
    header("Location: register.php");
    exit;
}

if ($password !== $konfirmasi) {
    $_SESSION["error"] = "Konfirmasi password tidak cocok.";
    header("Location: register.php");
    exit;
}

$sql = "SELECT id_user FROM users WHERE username = ? LIMIT 1";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    mysqli_stmt_close($stmt);
    $_SESSION["error"] = "Username sudah digunakan.";
    header("Location: register.php");
    exit;
}
mysqli_stmt_close($stmt);

$hash = password_hash($password, PASSWORD_DEFAULT);
$role = "siswa";

$sql = "INSERT INTO users (nama, username, password, role)
        VALUES (?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ssss",
    $nama, $username, $hash, $role
);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    $_SESSION["success"] = "Registrasi berhasil. Silakan login.";
    header("Location: login.php");
    exit;
}

mysqli_stmt_close($stmt);
$_SESSION["error"] = "Registrasi gagal. Coba lagi.";
header("Location: register.php");
exit;
?>
