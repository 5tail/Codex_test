<!DOCTYPE html>
<html lang="zh-Hant">
<head>
<meta charset="UTF-8">
<title>Cubing Timer</title>
<style>
  body {font-family: Arial, sans-serif; text-align:center; background:#111; color:#eee; }
  #timer {font-size:60px; margin-top:40px;}
  #scramble {margin-top:20px; font-size:20px; word-wrap:break-word; width:90%; margin-left:auto; margin-right:auto;}
  #status {margin-top:10px; height:30px;}
  .yellow {color:yellow;}
  .green {color:#0f0;}
  .big-yellow {color:yellow; font-size:40px;}
  .big-red {color:red; font-size:50px;}
</style>
</head>
<body>
<h1>Cubing Timer</h1>
<div id="timer">00:00.000</div>
<div id="status"></div>
<div id="scramble"></div>
<div style="margin-top:20px;">
<label><input type="checkbox" id="hideTime"> 隱藏計時</label>
<select id="scrambleType">
  <option value="3">3x3</option>
  <option value="2">2x2</option>
  <option value="4">4x4</option>
  <option value="5">5x5</option>
  <option value="6">6x6</option>
  <option value="7">7x7</option>
  <option value="pyra">Pyraminx</option>
  <option value="mega">Megaminx</option>
  <option value="skewb">Skewb</option>
  <option value="sq1">Square-1</option>
  <option value="clock">Clock</option>

</select>
<label><input type="checkbox" id="oneHour"> 1小時模式</label>
</div>
<script src="https://unpkg.com/cubejs/lib/cube.min.js"></script>
<script>
const timerDisplay = document.getElementById('timer');
const statusDisplay = document.getElementById('status');
const scrambleDisplay = document.getElementById('scramble');
const hideTimeCheck = document.getElementById('hideTime');
const scrambleTypeSelect = document.getElementById('scrambleType');
const oneHourCheck = document.getElementById('oneHour');

let timerInterval = null;
let startTime = 0;
let holding = false;
let holdTimeout = null;
let ready = false;
let inspectionStart = 0;
let inspectionInterval = null;
let penalty = '';



function pad(n, width){
  let s = n+''; if(s.length < width) s = '0'.repeat(width - s.length)+s; return s;
}

function formatTime(ms){
  const max = oneHourCheck.checked ? 3600000 : 600000;
  if(ms > max) ms = max;
  const m = Math.floor(ms/60000);
  const s = Math.floor((ms%60000)/1000);
  const cs = ms%1000;
  return `${pad(m,2)}:${pad(s,2)}.${pad(cs,3)}`;
}

function generateScramble(){
  if(puzzle){
    scrambleDisplay.textContent = cubejs.scramble(puzzle);
  } else {
    scrambleDisplay.textContent = '';
  }
}

generateScramble();

function startInspection(){
  inspectionStart = Date.now();
  statusDisplay.textContent = '';
  statusDisplay.className = '';
  inspectionInterval = setInterval(()=>{
    const t = (Date.now()-inspectionStart)/1000;
    if(t>=8 && t<12) {statusDisplay.textContent = '8 Seconds'; statusDisplay.className='big-yellow';}
    else if(t>=12 && t<15){statusDisplay.textContent='12 Seconds'; statusDisplay.className='big-red';}
    else if(t>=15){statusDisplay.textContent='\u5df2\u8d85\u6642'; statusDisplay.className='big-red';}
  },100);
}

function stopInspection(){
  clearInterval(inspectionInterval); inspectionInterval=null;
}

function startTimer(){
  startTime = Date.now();
  timerInterval = setInterval(updateTimer,10);
  ready = false;
  statusDisplay.textContent='';
  statusDisplay.className='';
  if(hideTimeCheck.checked) timerDisplay.textContent='\u9084\u539f\u4e2d';
}

function updateTimer(){
  const now = Date.now();
  let elapsed = now - startTime;
  if(!hideTimeCheck.checked) timerDisplay.textContent = formatTime(elapsed);
}

function stopTimer(){
  clearInterval(timerInterval); timerInterval=null;
  const elapsed = Date.now() - startTime;
  let show = formatTime(elapsed);
  if(penalty==='+2') show += ' +2';
  if(penalty==='DNF') show += ' DNF';
  timerDisplay.textContent = show;
  penalty='';
  generateScramble();
  startInspection();
}

document.body.addEventListener('keydown', e => {
  if(e.code==='Space' && !e.repeat){
    if(!holding && !timerInterval){
      holding = true;
      ready = false;
      statusDisplay.textContent = '\u25A0';
      statusDisplay.className = 'yellow';
      holdTimeout = setTimeout(() => {
        ready = true;
        statusDisplay.textContent = '\u25A0';
        statusDisplay.className = 'green';
      }, 800);
    } else if(timerInterval){
      stopTimer();
    }
  }
});

function computePenalty(){
  const t=(Date.now()-inspectionStart)/1000;
  if(t>=17) penalty='DNF';
  else if(t>=15) penalty='+2';
}

document.body.addEventListener('keyup', e => {
  if(e.code==='Space'){
    if(holding){
      clearTimeout(holdTimeout);
      holding = false;
      if(ready && !timerInterval){
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

scrambleTypeSelect.addEventListener('change', generateScramble);
startInspection();
</script>
</body>
</html>
