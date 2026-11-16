
<?php
session_start();

if (!isset($_SESSION["id_user"])) {
    header("Location: auth/login.php");
    exit;
}

switch ($_SESSION["role"]) {
    case "siswa":
        header("Location: dashboard_siswa.php");
        break;

    case "teknisi":
        header("Location: dashboard_teknisi.php");
        break;

    case "sarpras":
        header("Location: dashboard_sarpras.php");
        break;

    default:
        session_unset();
        session_destroy();
        header("Location: auth/login.php");
        break;
}

exit;
?>
