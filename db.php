<?php
// db.php - اتصال بقاعدة البيانات + إعدادات عامة

$host     = "localhost";
$user     = "root";       // مستخدم MySQL الافتراضي في XAMPP
$password = "";           // كلمة مرور MySQL (افتراضيًا فاضي في XAMPP)
$dbname   = "osr_charger";

// URL الأساس للمشروع (عدّل إذا اختلف المجلد)
define("BASE_URL", "http://localhost/OSR_Car_Charger_UI");

// إعدادات البريد (mail() الافتراضي). يفضّل لاحقًا ضبط SMTP عبر PHPMailer.
define("MAIL_FROM", "no-reply@osrcharger.local");
define("MAIL_FROM_NAME", "OSR Car Charger");

// الاتصال
$conn = mysqli_connect($host, $user, $password, $dbname);
if (!$conn) {
    die("❌ فشل الاتصال بقاعدة البيانات: " . mysqli_connect_error());
}

// تفعيل اللغة العربية
mysqli_set_charset($conn, "utf8mb4");
?>
