let timer;
let timeLeft = 300; // 5 دقائق افتراضي

document.getElementById("startBtn").addEventListener("click", () => {
  document.getElementById("statusText").textContent = "✅ الشحن نشط";
  clearInterval(timer);
  timeLeft = 300;
  timer = setInterval(updateTimer, 1000);
});

document.getElementById("stopBtn").addEventListener("click", () => {
  document.getElementById("statusText").textContent = "❌ الشحن متوقف";
  clearInterval(timer);
  document.getElementById("timer").textContent = "00:00";
});

function updateTimer() {
  if (timeLeft <= 0) {
    clearInterval(timer);
    document.getElementById("statusText").textContent = "✅ الشحن مكتمل";
    return;
  }
  timeLeft--;
  let minutes = Math.floor(timeLeft / 60);
  let seconds = timeLeft % 60;
  document.getElementById("timer").textContent =
    (minutes < 10 ? "0" : "") + minutes + ":" + (seconds < 10 ? "0" : "") + seconds;
}
