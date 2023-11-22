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
session_start();
include("datenbank.php");

$arr=$_POST["arr"];
$arr2=$_SESSION["warten"];




//Ermitteln von doppelten Fächern
for($i=0;$i<count($arr);$i++)
  {
    for ($u=0; $u < count($arr); $u++) {
      if($u==$i)
        {
          continue;
        }
      else{
        if($arr[$i]==$arr[$u])
          {
            echo "<div class='ueberschrift'><h1>Sie können Fächer nicht doppelt belegen! </h1><br><br><button><a href='registrierung.php'>Zurück zur Registrierung</a></button></div>";
            die();
          }
      }
    }
  }


  $query = "INSERT INTO schueler(Vorname,Nachname,Benutzername,Passwort) VALUES (?,?,?,?)";
  $x = $db->prepare($query);
  $x->execute([$arr2[0], $arr2[1], $arr2[2], $arr2[3]]);

  // Rausfinden der zugewiesenen Schueler-ID
  $id="SELECT S_ID FROM schueler WHERE Benutzername=?";
  $v=$db->prepare($id);
  $v->execute([$arr2[2]]);
  $s_i=$v->fetch(PDO::FETCH_ASSOC);


$_SESSION["S_ID"]=$s_i["S_ID"];



//Ermitteln der Fach ID für das jeweilige Fach
$sql1="SELECT F_ID from fach WHERE Fachname=?";
$u1=$db->prepare($sql1);
//Schleife für jedes angegebene Fach Bestimmung der ID und Zuweisung Pruefungsfach und Leistungkurs
for($i=0;$i<count($arr);$i++)
{
  $u1->execute([$arr[$i]]);
  $f_id=$u1->fetch(PDO::FETCH_ASSOC);


  $p=0;
  $l=0;
  if($i<5)
      {
        $p=1;
      }
  if($i<2)
    {
      $l=1;
    }
$query = "INSERT INTO s_f_beziehung(S_ID,F_ID,Pruefungsfach,Leistungskurs) VALUES (?,?,?,?)";
$x = $db->prepare($query);
$x->execute([$s_i["S_ID"],$f_id["F_ID"],$p,$l] );
}
echo "<div class='ueberschrift'><h1>Ihre Registrierung war erfolgreich </h1><br><br><button><a href='hauptseite.php'>Weiter zur Statistik</a></button></div>";
?>

</body>
</html>
