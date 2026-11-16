
<?php
session_start();

if (isset($_SESSION["id_user"])) {
    header("Location: ../index.php");
    exit;
}

$error = $_SESSION["error"] ?? "";
unset($_SESSION["error"]);

$success = $_SESSION["success"] ?? "";
unset($_SESSION["success"]);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Helpdesk Sekolah</title>
    <link rel="stylesheet" href="../assets/css/register.css">
</head>
<body class="auth-page">

<div class="container">
    <h2>Daftar Akun</h2>
    <p class="subtitle">Buat akun Helpdesk Sekolah</p>

    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if ($success): ?>
        <p style="color: green; margin-bottom: 15px;">
            <?= htmlspecialchars($success) ?>
        </p>
    <?php endif; ?>

    <form action="proses_register.php" method="post">
        <label for="nama">Nama Lengkap</label>
        <input type="text" id="nama" name="nama"
               placeholder="Masukkan nama lengkap" required>

        <label for="username">Username</label>
        <input type="text" id="username" name="username"
               placeholder="Buat username" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password"
               placeholder="Minimal 8 karakter" minlength="8" required>

        <label for="konfirmasi_password">Konfirmasi Password</label>
        <input type="password" id="konfirmasi_password"
               name="konfirmasi_password"
               placeholder="Ulangi password" minlength="8" required>

        <button type="submit">Register</button>
    </form>

    <p style="text-align: center; margin-top: 18px; font-size: 14px;">
        Sudah punya akun?
        <a href="login.php">Login di sini</a>
    </p>
</div>

</body>
</html>
