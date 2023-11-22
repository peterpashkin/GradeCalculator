<html>
<head>
  <link rel="stylesheet" href="stylish.css">
</head>
<body>
  <?php
  session_start();
if(isset($_SESSION["S_ID"]))
  {
    $_SESSION["S_ID"]="";
  }
   ?>
  <div class="gesamt">
    <div class="one">
      <div class="svgcontainer">
      <svg width="400" height="80" xmlns="http://www.w3.org/2000/svg" xmlns:svg="http://www.w3.org/2000/svg">
   <g class="layer">
    <title>Layer 1</title>
    <rect fill="#1a659e" height="378" id="svg_2" stroke="#f71d1d" stroke-width="0" width="468" x="-5.97512" y="-13.22228"/>
    <line fill="none" id="svg_4" stroke="#ffffff" stroke-width="5" x1="145" x2="195" y1="22.73295" y2="22.73295"/>
    <text fill="#ffffff" font-family="Helvetica" font-size="24" font-weight="bold" id="svg_8" stroke="#ffffff" stroke-width="0" text-anchor="middle" x="110" xml:space="preserve" y="30.73295">ABI</text>
    <text fill="#ffffff" font-family="Helvetica" font-size="32" font-weight="bold" id="svg_9" stroke="#f71d1d" stroke-width="0" text-anchor="middle" x="110" xml:space="preserve" y="61.73295">RECHNER</text>
    <line fill="none" id="svg_1" stroke="#ffffff" stroke-width="5" x1="25" x2="75" y1="22.8693" y2="22.8693"/>
   </g>
  </svg>
  </div>
    </div>
  </div>
<div class="ueberschrift">
  <h1>
Der Abiturnotenrechner
</h1>
</div>

<div class="p">

<p>
Der Abiturnotenrechner zum Berechnen von deinem Abiturschnitt und einer einfachen Veranschaulichung deiner Noten, mithilfe von Grafiken und Daten
</p>
</div>

<div class="buttons">


<button class="bu1">
<a href="registrierung.php">Registrieren</a>
</button>
<br>
<button class="bu2">
<a href="anmeldung.php">Anmelden</a>
</button>
</div>
