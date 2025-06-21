let start = Date.now();

function formatTime(ms) {
  const select = document.getElementById('timeLimit');
  const value = select.value;
  const max = value === 'Infinity' ? Infinity : Number(value);
  const total = Math.min(ms, max);
  const minutes = Math.floor(total / 60000);
  const seconds = Math.floor((total % 60000) / 1000);
  return `${minutes}:${seconds.toString().padStart(2, '0')}`;
}

function update() {
  const elapsed = Date.now() - start;
  document.getElementById('display').textContent = formatTime(elapsed);
  requestAnimationFrame(update);
}

update();
