<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
  <link rel="stylesheet" href="stylish.css">
</head>
<body>
  <div class="ueberschrift">

  <h1>Übersicht der Grundkursfächer</h1>
  </div>
  <p class="Bemerkung">
    Man Beachte, die kleinen Checkboxen sollen für die Halbjahre ausgewählt werden, die nicht eingebracht werden!
  </p>
  <?php
  include("datenbank.php");
  session_start();
$array=$_POST["array"];
$name=$_SESSION["benutzer"];
$s_id=$_SESSION["S_ID"];

$noten=$_SESSION["grades"];
$faecher=$_SESSION["faecher"];

$pruefuebergabe=$_POST["pruef"];
$pruefvorher=$_SESSION["pruefvorher"];

//Prüfungs Noten eintragen
$deletestatement="DELETE pruefung FROM pruefung,fach WHERE pruefung.S_ID=? AND pruefung.F_ID=fach.F_ID AND fach.Fachname=?";
$insertstatement="INSERT INTO pruefung(F_ID,S_ID,Pruefungsnote) SELECT fach.F_ID,?,? FROM fach WHERE fach.Fachname=?";
$updatestatement="UPDATE pruefung,fach SET pruefung.Pruefungsnote=? WHERE pruefung.F_ID=fach.F_ID AND pruefung.S_ID=? AND fach.Fachname=?";

for ($i=0; $i < count($pruefuebergabe); $i++) {
  if($pruefuebergabe[$i]==$pruefvorher[$i])
    {
      continue;
    }
  elseif($pruefuebergabe[$i]=="")
    {
      $x=$db->prepare($deletestatement);
      $x->execute([$s_id,$faecher[$i][0]]);
      $pruefvorher[$i]="";
    }
  elseif ($pruefvorher[$i]=="") {
    $y=$db->prepare($insertstatement);
    $y->execute([$s_id,$pruefuebergabe[$i],$faecher[$i][0]]);
    $pruefvorher[$i]=$pruefuebergabe[$i];
  }
  else {
    $z=$db->prepare($updatestatement);
    $z->execute([$pruefuebergabe[$i],$s_id,$faecher[$i][0]]);
    $pruefvorher[$i]=$pruefuebergabe[$i];
  }
}
$_SESSION["pruefvorher"]=$pruefvorher;

$updatestate="UPDATE fachhalbjahr,fach SET fachhalbjahr.Note=? WHERE fachhalbjahr.S_ID=? AND fachhalbjahr.Halbjahr=? AND fach.Fachname=? AND fach.F_ID=fachhalbjahr.F_ID";
$insertstate="INSERT INTO fachhalbjahr(S_ID,F_ID,Halbjahr,Note) SELECT ?,fach.F_ID,?,? FROM fach WHERE fach.Fachname=?";
$deletestate="DELETE fachhalbjahr FROM fachhalbjahr,fach WHERE fachhalbjahr.S_ID=? AND fachhalbjahr.F_ID=fach.F_ID AND fach.Fachname=? AND fachhalbjahr.Halbjahr=?";
// TODO: DELETE


$zahler=0;
//eingegebene Werte empfangen und mit Werten von davor vergleichen und dann updaten oder inserten
if(isset($_POST["array"]))
{
for ($i=0; $i <5 ; $i++) {
  for ($u=0; $u <4 ; $u++) {
    if($noten[$i][$u]==$array[$zahler])
      {$zahler++;}
    elseif($noten[$i][$u]=="")
      {
        $x=$db->prepare($insertstate);
        $x->execute([$s_id,$u+1,$array[$zahler],$faecher[$i][0]]);
        $noten[$i][$u]=$array[$zahler];
        $zahler++;
      }
    elseif($array[$zahler]=="")
      {
        $y=$db->prepare($deletestate);
        $y->execute([$s_id,$faecher[$i][0],$u+1]);
        $noten[$i][0]="";
        $zahler++;
      }
    else
      {
        $x=$db->prepare($updatestate);
        $x->execute([$array[$zahler],$s_id,$u+1,$faecher[$i][0]]);
        $zahler++;
      }

  }
}
}

//Damit keine Fehler auftreten wenn die Seite aktualisiert wird, werden die alten Noten durch die neuen Noten überschrieben
$_SESSION["grades"]=$noten;

//speichert alle Noten Fachnamen und Halbjahre von Grundkursen
$sql="SELECT fachhalbjahr.Note,fach.Fachname,fachhalbjahr.Halbjahr FROM fach,fachhalbjahr,s_f_beziehung WHERE fachhalbjahr.S_ID=? AND s_f_beziehung.S_ID=?
      AND fach.F_ID=fachhalbjahr.F_ID AND fachhalbjahr.F_ID=s_f_beziehung.F_ID AND s_f_beziehung.Pruefungsfach=? ORDER BY fach.Fachname DESC";
$speicher=$db->prepare($sql);
$speicher->execute([$s_id,$s_id,0]);
$infos=$speicher->fetchAll(PDO::FETCH_BOTH);

//speichert sie als Session um sie später zu vergleichen
$_SESSION["infos"]=$infos;


$fachnote=[];
$fach="";
//Schleife für alle Werte die man gerade bekommen hat
for ($i=0; $i <count($infos); $i++) {
  if($infos[$i][1]!=$fach)
    {
      //wenn ein neues Fach kommt, wird ein Array mit 4 Freistellen gebildet und an den richtigen Stellen wird die Note eingesetzt und wird in $fachnote gespeichert, das wird noch später gebraucht
      $fachnote[]=$infos[$i][1];
      $fach=$infos[$i][1];
      $array=["","","",""];
      $platz=$infos[$i][2]-1;
      $note=$infos[$i][0];
      $array[$platz]=$note;
    }
  else {
    //wennn das gleiche Fach kommt, wird wieder an die gleiche Stelle die jeweilige Note eingetragen
      $platz=$infos[$i][2]-1;
      $note=$infos[$i][0];
      $array[$platz]=$note;
  }
  if(isset($infos[$i+1][0]))
  {
    // wenn das nächste Fach ein anderes ist, wird das Array mit den richtigen Noten und den leeren Stellen in das Gesamtarray gespeichert
  if($infos[$i+1][1]!=$fach)
    {
      $grades[]=$array;
    }
  }
//wenn das Array an der nächsten Stelle vorbei ist, dann wird auch hier das Array im Gesamt-Array gespeichert
  else {
    $grades[]=$array;
  }
}

//Übergabe der Ursprungsnoten an die nächste Datei
if(isset($grades))
{
$_SESSION["bewertungen"]=$grades;
}
//Wenn noch gar keine Noten existieren, wird ein Array mit 7 freien Arrays erstellt, für den Vergleich
else {
  $useless=[];
  $x=["","","",""];
  for($i=0;$i<7;$i++)
    {
      $useless[]=$x;
    }
    $_SESSION["bewertungen"]=$useless;
}

//man holt alle Grundkursfächer
$fachsyn="SELECT fach.Fachname from fach,s_f_beziehung WHERE s_f_beziehung.F_ID=fach.F_ID  AND s_f_beziehung.S_ID=? AND s_f_beziehung.Pruefungsfach=0 ORDER BY fach.Fachname DESC";
$v=$db->prepare($fachsyn);
$v->execute([$s_id]);
$allefach=$v->fetchAll(PDO::FETCH_BOTH);

//Für alle Grundkursfächer wird überprüft ob es dieses Fach in $fachnote gibt
for ($i=0; $i < count($allefach); $i++) {
  $counter=0;
  for ($u=0; $u < count($fachnote); $u++) {
    //Falls ja, wird die Schleife unterbochen und das nächste Fach überprüft
    if($allefach[$i][0]==$fachnote[$u])
      {
        break;
      }
    else {
      //wenn nicht wird der Counter erhöht
      $counter++;
    }
  }
  //wenn der Counter so hoch ist wie die Anzahl der Fächer in $fachnote, heißt das dieses Fach ist noch nicht drin und wird hinzugefügt damit in der späteren Ausgabe alle Fächer stehen
  if($counter==count($fachnote))
    {
      $fachnote[]=$allefach[$i][0];
    }
}
$_SESSION["faechergk"]=$fachnote;

echo "<br>";

//Bestimmung ob Fach eingebracht wird oder nicht und Konvertierung in string als checked oder

//Vorbereiten des SQL Statements
$einbring="SELECT fachhalbjahr.Einbringen,fachhalbjahr.Halbjahr FROM fachhalbjahr,fach WHERE fachhalbjahr.S_ID=? AND fachhalbjahr.F_ID=Fach.F_ID AND fach.Fachname=?";
$xx=$db->prepare($einbring);
$alleeinbring=[];

for($i=0;$i<count($fachnote);$i++)
  {
    //bestimmt Halbjahr und ob das Halbjahr eingebracht wird für alle Halbjahre des jeweiligen Faches
    $fach=$fachnote[$i];
    $xx->execute([$s_id,$fach]);
    $einbring=$xx->fetchAll(PDO::FETCH_BOTH);
    $zwischarray=["unchecked","unchecked","unchecked","unchecked"];
    for($u=0;$u<count($einbring);$u++)
      {
        if($einbring[$u][0]==0)
          {
            $halbjahr=$einbring[$u][1]-1;
            $zwischarray[$halbjahr]="checked";
          }
      }
    //Das Array in dem jetzt für jedes Fach für jedes Halbjahr checked oder unchecked steht wird in ein gesamtarray gespeichert
    $alleeinbring[]=$zwischarray;
  }

//Übergabe für Update auf der nächsten Seite
  $_SESSION["einbring"]=$alleeinbring;
?>





     <!-- Formular für das Eintragen von den Grundkursnoten die bisherige Note wird aus dem grades Array bestimmt, die Checkboxen dafür, ob das Fach abgewählt wird übergeben eine Zahl, die erste Ziffer steht für den Platz im Fachnoten Array und
   die zweite Ziffer für das Halbjahr, die beiden Ziffern können folgend mit Modulo und Rundungen bestimmt werden-->
   <div class="inputs">

     <div class="Halbjahre2">
       <div class="H1">
         Halbjahr 1</div>
       <div class="H2">
         Halbjahr 2</div>
       <div class="H3">
         Halbjahr 3</div>
       <div class="H4">
         Halbjahr 4</div>
     </div>


     <form class="" action="rechnung.php" method="post">

<div class="input">
     <div class="php"><?php echo $fachnote[0]; ?></div>
     <input type="number" name="arr2[]" value="<?php echo $grades[0][0]; ?>"  >
     <div class="check"><input type="checkbox" name="einbring[]" value=1  <?php  echo $alleeinbring[0][0];?>></div>
     <input type="number" name="arr2[]" value="<?php echo $grades[0][1]; ?>"  >
     <div class="check"><input type="checkbox" name="einbring[]" value=2  <?php  echo $alleeinbring[0][1];?>></div>
     <input type="number" name="arr2[]" value="<?php echo $grades[0][2]; ?>"  >
     <div class="check"><input type="checkbox" name="einbring[]" value=3  <?php  echo $alleeinbring[0][2];?>></div>
     <input type="number" name="arr2[]" value="<?php echo $grades[0][3]; ?>"  >
     <div class="check"><input type="checkbox" name="einbring[]" value=4  <?php  echo $alleeinbring[0][3];?>></div>
     <br>
   </div>
<div class="input">
     <div class="php"><?php echo $fachnote[1]; ?></div>
     <input type="number" name="arr2[]" value="<?php echo $grades[1][0]; ?>"  >
     <div class="check"><input type="checkbox" name="einbring[]" value=11 <?php  echo $alleeinbring[1][0];?>></div>
     <input type="number" name="arr2[]" value="<?php echo $grades[1][1]; ?>"  >
     <div class="check"><input type="checkbox" name="einbring[]" value=12 <?php  echo $alleeinbring[1][1];?>></div>
     <input type="number" name="arr2[]" value="<?php echo $grades[1][2]; ?>"  >
     <div class="check"><input type="checkbox" name="einbring[]" value=13 <?php  echo $alleeinbring[1][2];?>></div>
     <input type="number" name="arr2[]" value="<?php echo $grades[1][3]; ?>"  >
     <div class="check"><input type="checkbox" name="einbring[]" value=14 <?php  echo $alleeinbring[1][3];?>></div>
     <br>
     </div>
<div class="input">
     <div class="php"><?php echo $fachnote[2]; ?></div>
     <input type="number" name="arr2[]" value="<?php echo $grades[2][0]; ?>"  >
     <div class="check"><input type="checkbox" name="einbring[]" value=21 <?php  echo $alleeinbring[2][0];?>></div>
     <input type="number" name="arr2[]" value="<?php echo $grades[2][1]; ?>"  >
     <div class="check"><input type="checkbox" name="einbring[]" value=22 <?php  echo $alleeinbring[2][1];?>></div>
     <input type="number" name="arr2[]" value="<?php echo $grades[2][2]; ?>"  >
     <div class="check"><input type="checkbox" name="einbring[]" value=23 <?php  echo $alleeinbring[2][2];?>></div>
     <input type="number" name="arr2[]" value="<?php echo $grades[2][3]; ?>"  >
     <div class="check"><input type="checkbox" name="einbring[]" value=24 <?php  echo $alleeinbring[2][3];?>></div>
     <br>
     </div>
<div class="input">
     <div class="php"><?php echo $fachnote[3]; ?></div>
     <input type="number" name="arr2[]" value="<?php echo $grades[3][0]; ?>"  >
     <div class="check"><input type="checkbox" name="einbring[]" value=31 <?php  echo $alleeinbring[3][0];?>></div>
     <input type="number" name="arr2[]" value="<?php echo $grades[3][1]; ?>"  >
     <div class="check"><input type="checkbox" name="einbring[]" value=32 <?php  echo $alleeinbring[3][1];?>></div>
     <input type="number" name="arr2[]" value="<?php echo $grades[3][2]; ?>"  >
     <div class="check"><input type="checkbox" name="einbring[]" value=33 <?php  echo $alleeinbring[3][2];?>></div>
     <input type="number" name="arr2[]" value="<?php echo $grades[3][3]; ?>"  >

     <div class="check"><input type="checkbox" name="einbring[]" value=34 <?php  echo $alleeinbring[3][3];?>></div>
     <br>
     </div>
<div class="input">
     <div class="php"><?php echo $fachnote[4]; ?></div>
     <input type="number" name="arr2[]" value="<?php echo $grades[4][0]; ?>"  >
     <div class="check"><input type="checkbox" name="einbring[]" value=41 <?php  echo $alleeinbring[4][0];?>></div>
     <input type="number" name="arr2[]" value="<?php echo $grades[4][1]; ?>"  >
     <div class="check"><input type="checkbox" name="einbring[]" value=42 <?php  echo $alleeinbring[4][1];?>></div>
     <input type="number" name="arr2[]" value="<?php echo $grades[4][2]; ?>"  >
     <div class="check"><input type="checkbox" name="einbring[]" value=43 <?php  echo $alleeinbring[4][2];?>></div>
     <input type="number" name="arr2[]" value="<?php echo $grades[4][3]; ?>"  >
     <div class="check"><input type="checkbox" name="einbring[]" value=44 <?php  echo $alleeinbring[4][3];?>></div>
     <br>
     </div>
<div class="input">
     <div class="php"><?php echo $fachnote[5]; ?></div>
     <input type="number" name="arr2[]" value="<?php echo $grades[5][0]; ?>"  >
     <div class="check"><input type="checkbox" name="einbring[]" value=51 <?php  echo $alleeinbring[5][0];?>></div>
     <input type="number" name="arr2[]" value="<?php echo $grades[5][1]; ?>"  >
     <div class="check"><input type="checkbox" name="einbring[]" value=52 <?php  echo $alleeinbring[5][1];?>></div>
     <input type="number" name="arr2[]" value="<?php echo $grades[5][2]; ?>"  >
     <div class="check"><input type="checkbox" name="einbring[]" value=53 <?php  echo $alleeinbring[5][2];?>></div>
     <input type="number" name="arr2[]" value="<?php echo $grades[5][3]; ?>"  >
     <div class="check"><input type="checkbox" name="einbring[]" value=54 <?php  echo $alleeinbring[5][3];?>></div>
     <br>
     </div>
<div class="input">
     <div class="php"><?php echo $fachnote[6]; ?></div>
     <input type="number" name="arr2[]" value="<?php echo $grades[6][0]; ?>"  >
     <div class="check"><input type="checkbox" name="einbring[]" value=61 <?php  echo $alleeinbring[6][0];?>></div>
     <input type="number" name="arr2[]" value="<?php echo $grades[6][1]; ?>"  >
     <div class="check"><input type="checkbox" name="einbring[]" value=62 <?php  echo $alleeinbring[6][1];?>></div>
     <input type="number" name="arr2[]" value="<?php echo $grades[6][2]; ?>"  >
     <div class="check"><input type="checkbox" name="einbring[]" value=63 <?php  echo $alleeinbring[6][2];?>></div>
     <input type="number" name="arr2[]" value="<?php echo $grades[6][3]; ?>"  >
     <div class="check"><input type="checkbox" name="einbring[]" value=64 <?php  echo $alleeinbring[6][3];?>></div>
     <br>
     </div>
     <input type="submit" class="sub" name="" value="Bestätigen der Änderungen">
   </form>
 </div>
</body>
</html>
