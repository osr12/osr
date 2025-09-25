<?php
// dashboard.php - لوحة المواقف
session_start();
require_once "db.php";
if (!isset($_SESSION["user_id"])) { header("Location: index.php"); exit; }

// جلب المواقف
$slots = mysqli_query($conn, "SELECT * FROM parking ORDER BY slot ASC");
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>لوحة التحكم - OSR Car Charger</title>
  <link rel="stylesheet" href="style.css">
  <script defer src="theme.js"></script>
  <script>
  // عداد تنازلي للمتبقي
  document.addEventListener('DOMContentLoaded', ()=>{
    const timers = document.querySelectorAll('[data-remaining]');
    setInterval(()=>{
      timers.forEach(el=>{
        let s = parseInt(el.dataset.remaining,10);
        if (s > 0) {
          s--; el.dataset.remaining = s;
          const h = Math.floor(s/3600), m=Math.floor((s%3600)/60), sec=s%60;
          el.textContent = `${h.toString().padStart(2,'0')}:${m.toString().padStart(2,'0')}:${sec.toString().padStart(2,'0')}`;
          // تنبيه بصري عندما يبقى أقل من 15 دقيقة
          if (s === 900) { el.closest('.slot').classList.add('warn'); }
        } else {
          el.textContent = "00:00:00";
        }
      });
    },1000);
  });
  </script>
</head>
<body class="dark-mode">
<header class="topbar">
  <div>⚡ OSR Car Charger</div>
  <nav>
    <a href="checkin.php">بدء شحن</a>
    <a href="logout.php">خروج</a>
  </nav>
  <button class="toggle" id="toggleTheme">🌙</button>
</header>

<main class="container">
  <h2>المواقف</h2>
  <div class="grid">
    <?php while($row = mysqli_fetch_assoc($slots)): 
      $status = $row["status"];
      $end = $row["end_time"];
      $remaining = 0;
      if ($status === "busy" && $end) {
        $remaining = max(0, strtotime($end) - time());
      }
    ?>
    <div class="slot <?=($status==='busy'?'busy':'available')?>">
      <h3>موقف <?=$row["slot"]?></h3>
      <?php if($status==='busy'): ?>
        <img src="assets/car.png" class="car" alt="car">
        <img src="assets/cable_green.gif" class="cable" alt="charging">
        <div class="state">⚡ قيد الشحن</div>
        <div class="timer" data-remaining="<?=$remaining?>">
          <!-- سيتم تحديث الوقت بالـ JS -->
        </div>
      <?php else: ?>
        <img src="assets/slot.png" class="car" alt="empty">
        <img src="assets/cable_gray.png" class="cable" alt="cable">
        <div class="state ok">🟢 متاح</div>
      <?php endif; ?>
    </div>
    <?php endwhile; ?>
  </div>
</main>
</body>
</html>
