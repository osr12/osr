<?php
// checkin.php - اختيار وقت والموقف
session_start();
require_once "db.php";
if (!isset($_SESSION["user_id"])) { header("Location: index.php"); exit; }

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $slot     = intval($_POST["slot"]);
    $duration = max(1, min(360, intval($_POST["duration"]))); // دقيقة إلى 6 ساعات
    $start    = date("Y-m-d H:i:s");
    $end      = date("Y-m-d H:i:s", time() + $duration * 60);
    $email    = $_SESSION["email"];

    // تحقق أن الموقف متاح
    $chk = mysqli_prepare($conn, "SELECT status FROM parking WHERE slot=?");
    mysqli_stmt_bind_param($chk, "i", $slot);
    mysqli_stmt_execute($chk);
    $r = mysqli_stmt_get_result($chk);
    $row = mysqli_fetch_assoc($r);
    if ($row && $row["status"] === "available") {
        $upd = mysqli_prepare($conn, "UPDATE parking SET status='busy', start_time=?, end_time=?, user_email=?, notified_15m=0 WHERE slot=?");
        mysqli_stmt_bind_param($upd, "sssi", $start, $end, $email, $slot);
        mysqli_stmt_execute($upd);
        header("Location: dashboard.php"); exit;
    } else {
        $error = "الموقف المحدد غير متاح حاليًا.";
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>بدء جلسة شحن - OSR Car Charger</title>
  <link rel="stylesheet" href="style.css">
  <script defer src="theme.js"></script>
</head>
<body class="dark-mode">
<div class="card narrow">
  <h2>🔌 بدء جلسة شحن</h2>
  <?php if(!empty($error)): ?><p class="badge error"><?=$error?></p><?php endif; ?>
  <form method="POST" class="form">
    <label>اختر الموقف:</label>
    <select name="slot" required>
      <option value="1">موقف 1</option>
      <option value="2">موقف 2</option>
    </select>

    <label>المدة (1 دقيقة - 6 ساعات):</label>
    <input type="number" name="duration" min="1" max="360" value="60" required>

    <button class="primary">ابدأ الشحن</button>
  </form>
  <div class="links"><a href="dashboard.php">⬅ العودة للوحة</a></div>
  <button class="toggle" id="toggleTheme">🌙</button>
</div>
</body>
</html>
