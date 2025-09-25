<?php
// forgot.php - طلب رابط إعادة التعيين
require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $stmt = mysqli_prepare($conn, "SELECT id, username FROM users WHERE email=?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($u = mysqli_fetch_assoc($res)) {
        $token = bin2hex(random_bytes(32));
        $exp   = date("Y-m-d H:i:s", time() + 3600);
        $upd   = mysqli_prepare($conn, "UPDATE users SET reset_token=?, reset_expiry=? WHERE id=?");
        mysqli_stmt_bind_param($upd, "ssi", $token, $exp, $u["id"]);
        mysqli_stmt_execute($upd);

        $link = BASE_URL . "/reset.php?token=" . $token;
        $subject = "إعادة تعيين كلمة المرور - OSR Car Charger";
        $html = file_get_contents("email_template.html");
        $html = str_replace(["{{name}}","{{reset_link}}"], [$u["username"], $link], $html);

        // محاولة إرسال بريد عبر mail() (يمكن لاحقًا استبداله بـ PHPMailer SMTP)
        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8\r\n";
        $headers .= "From: ".MAIL_FROM_NAME." <".MAIL_FROM.">\r\n";

        if (@mail($email, $subject, $html, $headers)) {
            $success = "📩 تم إرسال رابط إعادة التعيين إلى بريدك.";
        } else {
            $error = "⚠️ تعذر إرسال البريد عبر mail(). يُنصح بضبط SMTP عبر PHPMailer.";
        }
    } else {
        $error = "❌ البريد غير مسجل";
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>استرجاع كلمة المرور - OSR Car Charger</title>
  <link rel="stylesheet" href="style.css">
  <script defer src="theme.js"></script>
</head>
<body class="dark-mode">
<div class="card narrow">
  <h2>استرجاع كلمة المرور</h2>
  <?php if(!empty($error)): ?><p class="badge error"><?=$error?></p><?php endif; ?>
  <?php if(!empty($success)): ?><p class="badge success"><?=$success?></p><?php endif; ?>
  <form method="POST" class="form">
    <input type="email" name="email" placeholder="أدخل بريدك" required>
    <button class="primary">إرسال الرابط</button>
  </form>
  <div class="links"><a href="index.php">عودة لتسجيل الدخول</a></div>
  <button class="toggle" id="toggleTheme">🌙</button>
</div>
</body>
</html>
