<?php
// index.php - صفحة الدخول
session_start();
require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email    = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    $stmt = mysqli_prepare($conn, "SELECT id, email, password, username FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($res)) {
        if (password_verify($password, $row["password"])) {
            $_SESSION["user_id"] = $row["id"];
            $_SESSION["email"]   = $row["email"];
            $_SESSION["name"]    = $row["username"];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "❌ كلمة المرور غير صحيحة";
        }
    } else {
        $error = "❌ البريد الإلكتروني غير موجود";
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>OSR Car Charger ⚡ - تسجيل الدخول</title>
  <link rel="stylesheet" href="style.css">
  <script defer src="theme.js"></script>
</head>
<body class="dark-mode">
<div class="card narrow">
  <h1>⚡ OSR Car Charger</h1>
  <?php if(!empty($error)): ?><p class="badge error"><?=$error?></p><?php endif; ?>
  <form method="POST" class="form">
    <input type="email"    name="email"    placeholder="البريد الإلكتروني" required>
    <input type="password" name="password" placeholder="كلمة المرور" required>
    <button type="submit" class="primary">تسجيل الدخول</button>
  </form>
  <div class="links">
    <a href="signup.php">إنشاء حساب</a> •
    <a href="forgot.php">نسيت كلمة المرور؟</a>
  </div>

  <button class="toggle" id="toggleTheme">🌙</button>
</div>
</body>
</html>
