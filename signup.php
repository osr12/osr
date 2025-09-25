<?php
// signup.php - إنشاء حساب جديد
require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username   = trim($_POST["username"] ?? "");
    $email      = trim($_POST["email"] ?? "");
    $password   = $_POST["password"] ?? "";
    $confirm    = $_POST["confirm"] ?? "";
    $phone      = trim($_POST["phone"] ?? "");
    $car_number = trim($_POST["car_number"] ?? "");

    if ($password !== $confirm) {
        $error = "❌ كلمة المرور غير متطابقة";
    } else {
        // تأكد من فريدية الاسم والإيميل
        $check = mysqli_prepare($conn, "SELECT 1 FROM users WHERE username=? OR email=?");
        mysqli_stmt_bind_param($check, "ss", $username, $email);
        mysqli_stmt_execute($check);
        $res = mysqli_stmt_get_result($check);
        if (mysqli_num_rows($res) > 0) {
            $error = "❌ اسم المستخدم أو البريد الإلكتروني مستخدم بالفعل";
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $ins = mysqli_prepare($conn, "INSERT INTO users (username,email,password,phone,car_number) VALUES (?,?,?,?,?)");
            mysqli_stmt_bind_param($ins, "sssss", $username,$email,$hash,$phone,$car_number);
            if (mysqli_stmt_execute($ins)) {
                header("Location: index.php");
                exit;
            } else {
                $error = "⚠️ حدث خطأ أثناء إنشاء الحساب";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>إنشاء حساب - OSR Car Charger</title>
  <link rel="stylesheet" href="style.css">
  <script defer src="theme.js"></script>
</head>
<body class="dark-mode">
<div class="card narrow">
  <h2>إنشاء حساب</h2>
  <?php if(!empty($error)): ?><p class="badge error"><?=$error?></p><?php endif; ?>
  <form method="POST" class="form">
    <input name="username"   placeholder="اسم المستخدم" required>
    <input type="email" name="email" placeholder="البريد الإلكتروني" required>
    <input type="password" name="password" placeholder="كلمة المرور (٨+ أحرف)" minlength="8" required>
    <input type="password" name="confirm" placeholder="تأكيد كلمة المرور" minlength="8" required>
    <input name="phone" placeholder="رقم الهاتف" required>
    <input name="car_number" placeholder="رقم السيارة" required>
    <button class="primary">تسجيل</button>
  </form>
  <div class="links"><a href="index.php">لديك حساب؟ تسجيل الدخول</a></div>
  <button class="toggle" id="toggleTheme">🌙</button>
</div>
</body>
</html>
