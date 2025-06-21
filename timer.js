let timerDisplay = document.getElementById('timer-display');
let inspectionInterval = null;
let inspectionRemaining = 15;
let timerStart = null;
let timerInterval = null;
let penalty = '';

function startInspection() {
  clearInterval(inspectionInterval);
  inspectionRemaining = 15;
  timerDisplay.classList.remove('red');
  timerDisplay.textContent = inspectionRemaining;
  inspectionInterval = setInterval(() => {
    inspectionRemaining--;
    if (inspectionRemaining >= -2) {
      if (inspectionRemaining === 0) {
        timerDisplay.textContent = 'Stop';
        timerDisplay.classList.add('red');
      } else {
        timerDisplay.textContent = inspectionRemaining;
      }
    } else {
      clearInterval(inspectionInterval);
    }
  }, 1000);
}

function startTimer() {
  clearInterval(timerInterval);
  timerStart = performance.now();
  timerDisplay.classList.remove('red');
  timerInterval = setInterval(() => {
    const elapsed = (performance.now() - timerStart) / 1000;
    timerDisplay.textContent = elapsed.toFixed(3);
  }, 10);
}

function stopTimer() {
  clearInterval(timerInterval);
  if (!timerStart) return;
  const elapsed = (performance.now() - timerStart) / 1000;
  const formatted = elapsed.toFixed(3);
  if (penalty === '+2') {
    const finalTime = (elapsed + 2).toFixed(3);
    timerDisplay.textContent = `${formatted}+2=${finalTime}`;
  } else {
    timerDisplay.textContent = formatted;
    if (penalty === 'DNF') {
      timerDisplay.textContent += ' DNF';
    }
  }
  timerStart = null;
}
