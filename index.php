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
  #countdown {margin-top:5px; height:30px; line-height:30px;}
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
<div id="countdown">請按一下空白鍵開始</div>
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
<select id="timeMode">
  <option value="600000">10分鐘</option>
  <option value="3600000">1小時</option>
  <option value="Infinity">自由</option>
</select>
</div>
<script src="https://unpkg.com/cubejs/lib/cube.min.js"></script>
<script type="module">
import './timer.js';
</script>
</body>
</html>
