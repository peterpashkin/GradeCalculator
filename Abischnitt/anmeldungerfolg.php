<html>
<head>
    <link rel="stylesheet" href="stylish.css">
</head>
<body>
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
<?php
include("datenbank.php");
session_start();
$benutzer=$_POST["benutzer"];
$passwort=$_POST["passwort"];
$_SESSION["benutzer"]=$benutzer;

$query = "SELECT Passwort FROM schueler WHERE Benutzername=?";
$x = $db->prepare($query);
$x->execute([$benutzer]);


$y = $x->fetch(PDO::FETCH_BOTH);

if($y["Passwort"] == $passwort)
{
    echo "<div class='ueberschrift'><h1>Sie wurden erfolgreich angemeldet!";
    echo '<br><br><button><a href="hauptseite.php">Weiter zur Statistik</a></button></h1></div>';
}
else
{
  echo "<div class='ueberschrift'><h1>Fehler ungültiges Passwort oder Benutzername!";
  echo '<br><br><button><a href="anmeldung.php">Zurück zur Anmeldung</a></button></h1></div>';
}

//Der Session die Schüler ID übergeben für weitere Vorgehen
$id="SELECT S_ID FROM schueler WHERE Benutzername=?";
$v=$db->prepare($id);
$v->execute([$benutzer]);
$s_i=$v->fetch(PDO::FETCH_ASSOC);
$_SESSION["S_ID"]=$s_i["S_ID"];

?>
</body>
</html>
