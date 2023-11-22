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
    <a class="a" href="index.php">Abmelden</a>
  </div>

  <div class="ueberschrift2">

  <h1>Übersicht der Prüfungsfächer</h1>
</div>

<?php
include("datenbank.php");
session_start();







  $grades=[];
  $name=$_SESSION["benutzer"];
  $s_id=$_SESSION["S_ID"];
//Alle Fachnamen und Pruefungsnoten für den Benutzer rausfinden
  $befehl="SELECT fach.Fachname,pruefung.Pruefungsnote from pruefung,fach WHERE pruefung.S_ID=? AND fach.F_ID=pruefung.F_ID";
  $zwischen=$db->prepare($befehl);
  $zwischen->execute([$s_id]);
  $pruefung=$zwischen->fetchAll(PDO::FETCH_BOTH);

//Speichern aller Pruefungsfächer des Benutzers
  $query = "SELECT fach.Fachname FROM fach,s_f_beziehung WHERE s_f_beziehung.Pruefungsfach=? AND s_f_beziehung.S_ID=? AND s_f_beziehung.F_ID=fach.F_ID ORDER BY s_f_beziehung.Leistungskurs DESC";
  $z = $db->prepare($query);
  $z->execute([1,$s_id]);
  $fach = $z->fetchAll(PDO::FETCH_BOTH);

  //Übergabe an nächste Datei für das Einfügen
  $_SESSION["faecher"]=$fach;

$pruefungspeicher=[];

//Speichern der Prüfungsnoten in einem Array, falls es keine Note gibt, wird ein leeres Feld eingefügt um bei der Ausgabe später nacheinander durchgehen zu können
for($k=0;$k<count($fach);$k++)
  {
    $zaehler=0;
    for($z=0;$z<count($pruefung);$z++)
      {

        if($fach[$k][0]==$pruefung[$z][0])
          {
            $zaehler++;
            $speicher=$z;
          }

      }
      if($zaehler==1)
        {
          $pruefungspeicher[]=$pruefung[$speicher][1];
        }
      else
        {
          $pruefungspeicher[]="";
        }

  }

//Für jedes Fach werden 4 Halbjahre durchrotiert und die Noten werden in einem Array in einem Array gespeichert zum späteren druchrotieren
  for($x=0;$x<5;$x++)
  {
    $grade=[];
  $fach1=$fach[$x][0];
  for($i=1;$i<=4;$i++)
  {
    $sql="SELECT fachhalbjahr.Note from fach,fachhalbjahr WHERE  fachhalbjahr.S_ID=? AND fachhalbjahr.Halbjahr=? AND fachhalbjahr.F_ID=fach.F_ID AND fach.Fachname=?";
    $f = $db->prepare($sql);
    $f->execute([$s_id,$i,$fach1]);
    $noten= $f->fetch(PDO::FETCH_BOTH);
    $note=$noten[0];
    $grade[]=$note;
  }
  $grades[]=$grade;
}
// Übergabe der Noten für den Vergleich in der nächsten Datei
$_SESSION["grades"]=$grades;
$_SESSION["pruefvorher"]=$pruefungspeicher;
  ?>
  <div class="inputs">

<div class="Halbjahre">
  <div class="H1">
    Halbjahr 1</div>
  <div class="H2">
    Halbjahr 2</div>
  <div class="H3">
    Halbjahr 3</div>
  <div class="H4">
    Halbjahr 4</div>
  <div class="P">
    Prüfungsnote</div>
</div>

  <form class="" action="noteneintragen2.php" method="post">

<div class="input">

  <div class="php"><?php echo $fach[0][0]; ?></div>
  <input type="number" name="array[]" value="<?php echo $grades[0][0]?>" min=0 max=15>
  <input type="number" name="array[]" value="<?php echo $grades[0][1]?>" min=0 max=15>
  <input type="number" name="array[]" value="<?php echo $grades[0][2]?>" min=0 max=15>
  <input type="number" name="array[]" value="<?php echo $grades[0][3]?>" min=0 max=15>
  <input type="number" name="pruef[]" value="<?php echo $pruefungspeicher[0] ?>" min=0 max=15>
  <br>
  </div>
<div class="input">

  <div class="php"><?php echo $fach[1][0]; ?></div>
  <input type="number" name="array[]" value="<?php echo $grades[1][0]?>" min=0 max=15>
  <input type="number" name="array[]" value="<?php echo $grades[1][1]?>" min=0 max=15>
  <input type="number" name="array[]" value="<?php echo $grades[1][2]?>" min=0 max=15>
  <input type="number" name="array[]" value="<?php echo $grades[1][3]?>" min=0 max=15>
  <input type="number" name="pruef[]" value="<?php echo $pruefungspeicher[1] ?>" min=0 max=15>
  <br>
  </div>
<div class="input">

  <div class="php"><?php echo $fach[2][0]; ?></div>
  <input type="number" name="array[]" value="<?php echo $grades[2][0]?>" min=0 max=15>
  <input type="number" name="array[]" value="<?php echo $grades[2][1]?>" min=0 max=15>
  <input type="number" name="array[]" value="<?php echo $grades[2][2]?>" min=0 max=15>
  <input type="number" name="array[]" value="<?php echo $grades[2][3]?>" min=0 max=15>
  <input type="number" name="pruef[]" value="<?php echo $pruefungspeicher[2] ?>" min=0 max=15>
  <br>
  </div>
<div class="input">

  <div class="php"><?php echo $fach[3][0]; ?></div>
  <input type="number" name="array[]" value="<?php echo $grades[3][0]?>" min=0 max=15>
  <input type="number" name="array[]" value="<?php echo $grades[3][1]?>" min=0 max=15>
  <input type="number" name="array[]" value="<?php echo $grades[3][2]?>" min=0 max=15>
  <input type="number" name="array[]" value="<?php echo $grades[3][3]?>" min=0 max=15>
  <input type="number" name="pruef[]" value="<?php echo $pruefungspeicher[3] ?>" min=0 max=15>
  <br>
  </div>
<div class="input">

  <div class="php"><?php echo $fach[4][0]; ?></div>
  <input  type="number" name="array[]" value="<?php echo $grades[4][0]?>" min=0 max=15>
  <input type="number" name="array[]" value="<?php echo $grades[4][1]?>" min=0 max=15>
  <input type="number" name="array[]" value="<?php echo $grades[4][2]?>" min=0 max=15>
  <input type="number" name="array[]" value="<?php echo $grades[4][3]?>" min=0 max=15>
  <input type="number" name="pruef[]" value="<?php echo $pruefungspeicher[4] ?>" min=0 max=15>
  <br>
  </div>

<input class="sub" type="submit" name="" value="Weiter zu den Grundkursen"></input>
</form>

</div>

</body>
</html>
