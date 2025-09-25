// theme.js - تبديل الثيم (Dark/Light) مع حفظ الاختيار
document.addEventListener("DOMContentLoaded", () => {
  const toggle = document.getElementById('toggleTheme');
  const saved  = localStorage.getItem('theme') || 'dark';
  document.body.classList.remove('dark-mode','light-mode');
  document.body.classList.add(saved === 'light' ? 'light-mode' : 'dark-mode');
  if (toggle) toggle.textContent = saved === 'light' ? '☀️' : '🌙';

  if (toggle) toggle.addEventListener('click', () => {
    const now = document.body.classList.contains('dark-mode') ? 'light' : 'dark';
    document.body.classList.toggle('dark-mode');
    document.body.classList.toggle('light-mode');
    localStorage.setItem('theme', now);
    toggle.textContent = now === 'light' ? '☀️' : '🌙';
  });
});
