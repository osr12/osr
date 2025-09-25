<?php
// reset.php - تنفيذ إعادة التعيين
require_once "db.php";

$valid = false; $token = $_GET["token"] ?? "";
if ($token) {
    $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE reset_token=? AND reset_expiry > NOW()");
    mysqli_stmt_bind_param($stmt, "s", $token);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($res)) {
        $uid = $row["id"]; $valid = true;
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $p1 = $_POST["password"] ?? ""; $p2 = $_POST["confirm"] ?? "";
            if ($p1 === $p2 && strlen($p1) >= 8) {
                $hash = password_hash($p1, PASSWORD_BCRYPT);
                $upd = mysqli_prepare($conn, "UPDATE users SET password=?, reset_token=NULL, reset_expiry=NULL WHERE id=?");
                mysqli_stmt_bind_param($upd, "si", $hash, $uid);
                mysqli_stmt_execute($upd);
                $success = "تم تغيير كلمة المرور. <a href='index.php'>تسجيل الدخول</a>";
                $valid = false;
            } else {
                $error = "❌ تأكد من التطابق وأن الطول 8 أحرف على الأقل.";
            }
        }
    } else {
        $error = "⚠️ رابط غير صالح أو منتهي.";
    }
} else {
    $error = "⚠️ رابط مفقود.";
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>إعادة التعيين - OSR Car Charger</title>
  <link rel="stylesheet" href="style.css">
  <script defer src="theme.js"></script>
</head>
<body class="dark-mode">
<div class="card narrow">
  <h2>إعادة تعيين كلمة المرور</h2>
  <?php if(!empty($error)): ?><p class="badge error"><?=$error?></p><?php endif; ?>
  <?php if(!empty($success)): ?><p class="badge success"><?=$success?></p><?php endif; ?>

  <?php if($valid): ?>
  <form method="POST" class="form">
    <input type="password" name="password" placeholder="كلمة مرور جديدة (٨+)" minlength="8" required>
    <input type="password" name="confirm" placeholder="تأكيد كلمة المرور" minlength="8" required>
    <button class="primary">تغيير</button>
  </form>
  <?php endif; ?>
</div>
</body>
</html>
