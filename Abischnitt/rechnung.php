<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="stylish.css">
  <title>Document</title>
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
  <?php
  session_start();
  include("datenbank.php");

$vorherabwahl=$_SESSION["einbring"];
if(isset($_POST["einbring"]))
{
  $abwahlen=$_POST["einbring"];
  if(count($abwahlen)>8)
    {
      echo "<div class='ueberschrift'><h1>Sie können nicht mehr als 8 Fächer abwählen!</h1><br><br><button><a href='noteneintragen.php'>Zurück zu den Noten</a></button></div>";
      die();
    }
}
else $abwahlen=[];
$array=$_POST["arr2"];
$name=$_SESSION["benutzer"];
$s_id=$_SESSION["S_ID"];
$faecher=$_SESSION["faechergk"];

print_r($array);




$updatestate="UPDATE fachhalbjahr,fach SET fachhalbjahr.Note=? WHERE fachhalbjahr.S_ID=? AND fachhalbjahr.Halbjahr=? AND fach.Fachname=? AND fach.F_ID=fachhalbjahr.F_ID";
$insertstate="INSERT INTO fachhalbjahr(S_ID,F_ID,Halbjahr,Note) SELECT ?,fach.F_ID,?,? FROM fach WHERE fach.Fachname=?";
$deletestate="DELETE fachhalbjahr FROM fachhalbjahr,fach WHERE fachhalbjahr.S_ID=? AND fachhalbjahr.F_ID=fach.F_ID AND fach.Fachname=? AND fachhalbjahr.Halbjahr=?";



$zahler=0;


if(isset($_SESSION["bewertungen"]))
{
$grades=$_SESSION["bewertungen"];
}


if(count($grades)<7)
  {
    for($u=count($grades);$u<7;$u++)
      {
        $speich=["","","",""];
        $grades[]=$speich;
      }
  }

for ($i=0; $i <7 ; $i++) {
  for ($u=0; $u <4 ; $u++) {
    if($grades[$i][$u]==$array[$zahler])
      {$zahler++;}
    elseif($grades[$i][$u]=="")
      {
        $x=$db->prepare($insertstate);
        $x->execute([$s_id,$u+1,$array[$zahler],$faecher[$i]]);
        $grades[$i][$u]=$array[$zahler];
        $zahler++;

      }
    elseif($array[$zahler]=="")
      {
        $y=$db->prepare($deletestate);
        $y->execute([$s_id,$faecher[$i],$u+1]);
        $grades[$i][$u]="";
        $zahler++;
      }
    else
      {
        $x=$db->prepare($updatestate);
        $x->execute([$array[$zahler],$s_id,$u+1,$faecher[$i]]);
        $zahler++;
      }

  }
}

//Update Statement für Einbringung der Fächer

$updateeinbr="UPDATE fachhalbjahr,fach SET fachhalbjahr.Einbringen=? WHERE fachhalbjahr.S_ID=? AND fachhalbjahr.Halbjahr=? AND fach.Fachname=? AND fach.F_ID=fachhalbjahr.F_ID";

//Vergleichsarray erstellen

$vergleichsarray=[];
for($i=0;$i<7;$i++)
  {
    $unnarray=["unchecked","unchecked","unchecked","unchecked"];
    $vergleichsarray[]=$unnarray;
  }

foreach($abwahlen as $b) {
  $fach=floor($b/10);
  $halbjahr=($b%10)-1;
  $vergleichsarray[$fach][$halbjahr]="checked";
}


for($k=0;$k<count($vorherabwahl);$k++) {
    for($i=0;$i<4;$i++)
      {
        if($vorherabwahl[$k][$i]==$vergleichsarray[$k][$i])
          {
            continue;
          }
        else {
          if($vergleichsarray[$k][$i]=="unchecked")
            {
              $einfuege=1;
            }
          else
          {
            $einfuege=0;
          }
          $z=$db->prepare($updateeinbr);
          $z->execute([$einfuege,$s_id,$i+1,$faecher[$k]]);
        }
      }
  }

echo "<div class='ueberschrift'><h1>Ihre Eingabe war erfolgreich!</h1><br><br><button><a href='hauptseite.php'>Weiter zur Statistik</a></button></div>";
   ?>


</body>
</html>
