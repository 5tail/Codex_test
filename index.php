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
let scrambleType = '3';

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

function scrambleNxN(n){
  const base = ['U','D','L','R','F','B'];
  let moves = base.slice();
  if(n >= 4){
    moves = moves.concat(['Uw','Dw','Lw','Rw','Fw','Bw']);
  }
  const mods = ['', "'", '2'];
  const lengthMap = {2:11,3:20,4:40,5:60,6:80,7:100};
  const len = lengthMap[n] || 20;
  let lastAxis = '';
  const out = [];
  for(let i=0;i<len;i++){
    let m = moves[Math.floor(Math.random()*moves.length)];
    while(m[0]===lastAxis) m = moves[Math.floor(Math.random()*moves.length)];
    lastAxis = m[0];
    m += mods[Math.floor(Math.random()*mods.length)];
    out.push(m);
  }
  return out.join(' ');
}

function scramblePyraminx(){
  const moves = ['U','L','R','B'];
  const mods = ['', "'"];
  const len = 9;
  let last = '';
  const out = [];
  for(let i=0;i<len;i++){
    let m = moves[Math.floor(Math.random()*moves.length)];
    while(m===last) m = moves[Math.floor(Math.random()*moves.length)];
    last = m;
    m += mods[Math.floor(Math.random()*mods.length)];
    out.push(m);
  }
  ['u','l','r','b'].forEach(t=>{if(Math.random()<0.5) out.push(t+mods[Math.floor(Math.random()*mods.length)]);});
  return out.join(' ');
}

function scrambleSkewb(){
  const moves = ['U','L','R','B'];
  const mods = ['', "'"];
  const len = 10;
  let last = '';
  const out = [];
  for(let i=0;i<len;i++){
    let m = moves[Math.floor(Math.random()*moves.length)];
    while(m===last) m = moves[Math.floor(Math.random()*moves.length)];
    last = m;
    m += mods[Math.floor(Math.random()*mods.length)];
    out.push(m);
  }
  return out.join(' ');
}

function scrambleMegaminx(){
  const moves = ['R++','R--','D++','D--'];
  const len = 70;
  const out = [];
  for(let i=0;i<len;i++) out.push(moves[Math.floor(Math.random()*moves.length)]);
  return out.join(' ');
}

function scrambleSq1(){
  const len = 15;
  const out = [];
  for(let i=0;i<len;i++){
    const a = Math.floor(Math.random()*12)-5;
    const b = Math.floor(Math.random()*12)-5;
    out.push(`(${a},${b})`);
    if(i!==len-1) out.push('/');
  }
  return out.join(' ');
}

function scrambleClock(){
  const faces = ['UR','DR','DL','UL','U','R','L','D','ALL'];
  const turns = [-5,-4,-3,-2,-1,1,2,3,4,5];
  const out = [];
  for(let i=0;i<12;i++){
    const f = faces[Math.floor(Math.random()*faces.length)];
    const t = turns[Math.floor(Math.random()*turns.length)];
    out.push(f+t);
  }
  out.push('y2');
  return out.join(' ');
}

function generateScramble(){
  scrambleType = scrambleTypeSelect.value;
  if(['2','3','4','5','6','7'].includes(scrambleType)){
    scrambleDisplay.textContent = scrambleNxN(parseInt(scrambleType,10));
  } else if(scrambleType==='pyra'){
    scrambleDisplay.textContent = scramblePyraminx();
  } else if(scrambleType==='mega'){
    scrambleDisplay.textContent = scrambleMegaminx();
  } else if(scrambleType==='skewb'){
    scrambleDisplay.textContent = scrambleSkewb();
  } else if(scrambleType==='sq1'){
    scrambleDisplay.textContent = scrambleSq1();
  } else if(scrambleType==='clock'){
    scrambleDisplay.textContent = scrambleClock();
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
      statusDisplay.textContent = '\u9ec3';
      statusDisplay.className = 'yellow';
      holdTimeout = setTimeout(() => {
        ready = true;
        statusDisplay.textContent = '\u7da0';
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
