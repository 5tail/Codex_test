// timer.js - timer logic module

const timerDisplay = document.getElementById('timer');
const statusDisplay = document.getElementById('status');
const scrambleDisplay = document.getElementById('scramble');
const hideTimeCheck = document.getElementById('hideTime');
const scrambleTypeSelect = document.getElementById('scrambleType');
const timeModeSelect = document.getElementById('timeMode');

let timerInterval = null;
let startTime = 0;
let holding = false;
let holdTimeout = null;
let ready = false;
let inspectionStart = 0;
let inspectionInterval = null;
let penalty = '';
let scrambleType = '3';

function pad(n, width) {
  let s = n + '';
  if (s.length < width) s = '0'.repeat(width - s.length) + s;
  return s;
}

export function formatTime(ms) {
  let max;
  switch (timeModeSelect.value) {
    case '600000':
      max = 600000;
      break;
    case '3600000':
      max = 3600000;
      break;
    default:
      max = Infinity;
  }
  if (ms > max) ms = max;
  const m = Math.floor(ms / 60000);
  const s = Math.floor((ms % 60000) / 1000);
  const cs = ms % 1000;
  return `${pad(m, 2)}:${pad(s, 2)}.${pad(cs, 3)}`;
}

export function generateScramble() {
  scrambleType = scrambleTypeSelect.value;
  const map = {
    '2': '222',
    '3': '333',
    '4': '444',
    '5': '555',
    '6': '666',
    '7': '777',
    'pyra': 'pyram',
    'mega': 'minx',
    'skewb': 'skewb',
    'sq1': 'sq1',
    'clock': 'clock'
  };

  const puzzle = map[scrambleType];
  let scramble = '';
  if (puzzle) {
    try {
      scramble = cubejs.scramble(puzzle);
    } catch (e) {
      scramble = 'Scramble not available';
    }
  } else {
    scramble = 'Scramble not available';
  }
  scrambleDisplay.textContent = scramble;
}

export function startInspection() {
  inspectionStart = Date.now();
  statusDisplay.textContent = '';
  statusDisplay.className = '';
  clearInterval(inspectionInterval);
  inspectionInterval = setInterval(() => {
    const elapsed = (Date.now() - inspectionStart) / 1000;
    const remaining = 15 - elapsed;

    if (elapsed >= 17) {
      statusDisplay.textContent = 'DNF';
      statusDisplay.className = 'big-red';
    } else {
      statusDisplay.textContent = remaining.toFixed(1);
      if (elapsed >= 12) {
        statusDisplay.className = 'big-red';
      } else if (elapsed >= 8) {
        statusDisplay.className = 'big-yellow';
      } else {
        statusDisplay.className = '';
      }
    }
  }, 100);
}

export function stopInspection() {
  clearInterval(inspectionInterval);
  inspectionInterval = null;
}

export function startTimer() {
  startTime = Date.now();
  timerInterval = setInterval(updateTimer, 10);
  ready = false;
  statusDisplay.textContent = '';
  statusDisplay.className = '';
  if (hideTimeCheck.checked) timerDisplay.textContent = '\u9084\u539f\u4e2d';
}

export function updateTimer() {
  const now = Date.now();
  const elapsed = now - startTime;
  if (!hideTimeCheck.checked) timerDisplay.textContent = formatTime(elapsed);
}

export function stopTimer() {
  clearInterval(timerInterval);
  timerInterval = null;
  const elapsed = Date.now() - startTime;
  let show = formatTime(elapsed);
  if (penalty === '+2') show += ' +2';
  if (penalty === 'DNF') show += ' DNF';
  timerDisplay.textContent = show;
  penalty = '';
  generateScramble();
  startInspection();
}

export function computePenalty() {
  const t = (Date.now() - inspectionStart) / 1000;
  if (t >= 17) penalty = 'DNF';
  else if (t >= 15) penalty = '+2';
}

document.body.addEventListener('keydown', e => {
  if (e.code === 'Space' && !e.repeat) {
    if (!holding && !timerInterval) {
      holding = true;
      ready = false;
      statusDisplay.textContent = '\u25A0';
      statusDisplay.className = 'yellow';
      holdTimeout = setTimeout(() => {
        ready = true;
        statusDisplay.textContent = '\u25A0';
        statusDisplay.className = 'green';
      }, 800);
    } else if (timerInterval) {
      stopTimer();
    }
  }
});

document.body.addEventListener('keyup', e => {
  if (e.code === 'Space') {
    if (holding) {
      clearTimeout(holdTimeout);
      holding = false;
      if (ready && !timerInterval) {
        computePenalty();
        stopInspection();
        startTimer();
      } else {
        statusDisplay.textContent = '';
        statusDisplay.className = '';
      }
    }
  }
});

scrambleTypeSelect.addEventListener('change', () => {
  generateScramble();
  startInspection();
});

