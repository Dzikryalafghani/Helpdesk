
<?php
session_start();
require_once __DIR__ . "/../koneksi.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.php");
    exit;
}

$username = trim($_POST["username"] ?? "");
$password = $_POST["password"] ?? "";

if ($username === "" || $password === "") {
    $_SESSION["error"] = "Username dan password wajib diisi.";
    header("Location: login.php");
    exit;
}

$sql = "SELECT id_user, nama, username, password, role
        FROM users
        WHERE username = ?
        LIMIT 1";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Query gagal disiapkan.");
}

mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$user || !password_verify($password, $user["password"])) {
    $_SESSION["error"] = "Username atau password salah.";
    header("Location: login.php");
    exit;
}

session_regenerate_id(true);

$_SESSION["id_user"] = $user["id_user"];
$_SESSION["nama"] = $user["nama"];
$_SESSION["role"] = $user["role"];

switch ($user["role"]) {
    case "siswa":
        header("Location: ../dashboard_siswa.php");
        break;

    case "teknisi":
        header("Location: ../dashboard_teknisi.php");
        break;

    case "sarpras":
        header("Location: ../dashboard_sarpras.php");
        break;

    default:
        session_unset();
        session_destroy();
        header("Location: login.php");
        break;
}

exit;
?>
