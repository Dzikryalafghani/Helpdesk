<?php
session_start();

if (isset($_SESSION["id_user"])) {
    header("Location: ../index.php");
    exit;
}

$error = $_SESSION["error"] ?? "";
unset($_SESSION["error"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <title>Login - Helpdesk Sekolah</title>
</head>
<body class="auth-page">
    <div class="container">
        <h2>Login Helpdesk Sekolah</h2>

        <?php if ($error): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form action="proses_login.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>

            <button type="submit">Login</button>
        </form>
        
        <p class="auth-footer">
            Belum punya akun?
            <a href="register.php">Daftar sekarang</a>
        </p>
    </div>
</body>
</html>
