<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>
<body>
  <?php
  include("datenbank.php");
  $s_id=$_SESSION["S_ID"];
  //Vorbereiten aller Fächer mit dementsprechenden Noten Informationen über das Einbringen und dem dazugehörigen Fach
  $sql="SELECT fachhalbjahr.Note,s_f_beziehung.Leistungskurs,fachhalbjahr.Einbringen,fach.Fachname FROM fach,fachhalbjahr,s_f_beziehung WHERE s_f_beziehung.S_ID=? AND fachhalbjahr.S_ID=? AND s_f_beziehung.F_ID=fachhalbjahr.F_ID AND fachhalbjahr.F_ID=fach.F_ID ORDER BY fachhalbjahr.F_ID DESC";
  $f=$db->prepare($sql);
  $f->execute([$s_id,$s_id]);
  $x=$f->fetchAll(PDO::FETCH_BOTH);
//Vorbereiten aller Prüfungsnoten mit dem entsprechendem Fachnamen
  $sql2="SELECT pruefung.Pruefungsnote,fach.Fachname FROM fach,pruefung WHERE pruefung.S_ID=? AND pruefung.F_ID=fach.F_ID";
  $v=$db->prepare($sql2);
  $v->execute([$s_id]);
  $y=$v->fetchAll(PDO::FETCH_BOTH);
//Vorbereiten aller Prüfungsfächer
  $sql3="SELECT fach.Fachname from fach,s_f_beziehung WHERE s_f_beziehung.S_ID=? AND s_f_beziehung.Pruefungsfach=1 AND s_f_beziehung.F_ID=fach.F_ID";
  $n=$db->prepare($sql3);
  $n->execute([$s_id]);
  $z=$n->fetchAll(PDO::FETCH_BOTH);

class Noten
{
  public $pointsblockIaverage=0;
  public $pointsblockIIaverage=0;
  public $pointsblockIbest=0;
  public $pointsblockIIbest=0;
  public $punkteverteilung=[822,804,786,768,750,732,714,696,678,660,642,624,606,588,570,552,534,516,498,480,462,444,426,408,390,372,354,336,318,300];
  public $allefaecher;
  public $allepruef;
  public $prueffach;
  public $noteaverage;
  public $notebest;
  public $fehler;
//weist die oben deklarierten Variablen der Klasse zu
  public function __construct($allefaecher,$allepruef,$prueffach)
  {
    $this->allefaecher=$allefaecher;
    $this->allepruef=$allepruef;
    $this->prueffach=$prueffach;
  }
//berechnet die Punkte für den ersten Block im Average Case
  function pointsblockIaverage()
    {
//wenn es noch keine Noten gibt wird die Funktion unterbrochen, Fehlerbehebung auf der Hauptseite
    if(count($this->allefaecher)==0)
      {
        return "Noch keine Noten";
      }
    else {
//ansonstern für jedes Fach überprüfen
      $sum=0;
      $count=0;
      for ($i=0; $i < count($this->allefaecher); $i++) {
        //ob es eingebracht wird
        if($this->allefaecher[$i][2]==1)
        {
          //ob es ein Leistungskurs ist
        if($this->allefaecher[$i][1]==1)
          {
            //dementsprechend Halbajhre doppelt zählen
            $count+=2;
            $sum+=$this->allefaecher[$i][0]*2;
          }
        else {
          $count++;
          $sum+=$this->allefaecher[$i][0];
        }
      }
    }
    if($count>0)
      {
        $this->pointsblockIaverage=($sum/$count)*40;
      }
    else{
      $this->fehler=1;
    }
    }
  }
//alle Halbjahres Noten eines Faches bestimmen
  function allefach($fachnamen)
  {
    $array=[];
    for ($i=0; $i < count($this->allefaecher); $i++) {
      if($this->allefaecher[$i][3]==$fachnamen)
        {
          $array[]=$this->allefaecher[$i][0];
        }
    }
    return $array;
  }

//Den Durchschnitt eines Arrays rausfinden
  function average($array)
    {
      $sum=0;
      foreach($array as $a)
        {
          $sum+=$a;
        }
      $aver=$sum/count($array);
      return $aver;
    }
//Berechnen der Punkte für den 2. Block im Average Case
  function pointsblockIIaverage()
  {
    $sum=0;
    //Jede Prüfungsnote die vorhanden ist in die Summe mitsummieren
    for ($i=0; $i < count($this->allepruef); $i++) {
      $sum+=$this->allepruef[$i][0]*4;
    }
    //Für den Rest wird das Fach bestimmmt
    for ($u=0; $u < count($this->prueffach); $u++) {
      $counter=0;
      for ($k=0; $k <count($this->allepruef) ; $k++) {
        if($this->prueffach[$u][0]==$this->allepruef[$k][1])
          {
            $counter++;
            break;
          }
      }
      // Für diese werden dann die Durchschnitte aus den anderen Halbjahren gebildet
      if($counter==0)
        {
          $array=$this->allefach($this->prueffach[$u][0]);
          if(count($array)>0)
          {
            $x=$this->average($array);
            $sum+=$x*4;
          }

          else{
            //Wenn es noch keine Noten in dem Fach gibt, wird von einer Note von 11 ausgegangen
            $sum+=44;
          }
        }
    }
    $this->pointsblockIIaverage=$sum;
  }

//Berechnung der Punkte von Block 1 im Best Case
function pointsblockIbest()
  {
    $sum=0;
    $zaehler=0;
    $lk=0;
    for ($i=0; $i <count($this->allefaecher) ; $i++) {
      //Überprüfen ob das Halbjahr eingebracht wird
      if($this->allefaecher[$i][2]==1)
        {
          //Überprüfen ob es ein Leistungskurs ist
          if($this->allefaecher[$i][1]==1)
          {
            //dementsprechend doppelt eintragen
            $sum+=$this->allefaecher[$i][0]*2;
            $lk++;
          }
          else {
            $sum+=$this->allefaecher[$i][0];
          }
        }
      else {
        if($this->allefaecher[$i][1])
          {
            $zaehler+=2;
            $lk++;
          }
        else {
          $zaehler++;
        }

      }
    }
    //Für alle restlichen Halbjahre einsetzen von 15 Punkten
    for ($g=0; $g < 8-$lk; $g++) {
      $sum+=30;
    }
    for ($z=count($this->allefaecher)-$lk+8; $z < 48; $z++) {
      $sum+=15;
    }
    $this->pointsblockIbest=($sum/(56-$zaehler))*40;
  }
//Berechnung von den Punkten in Block 2 im Best Case
  function pointsblockIIbest(){
    $sum=0;
    //für alle vorhandenen Prüfungsnoten einfach reinaddieren
    for ($i=0; $i <count($this->allepruef) ; $i++) {
      $sum+=$this->allepruef[$i][0]*4;
    }
    //Für den Rest eintragen von 15
    for ($u=count($this->allepruef); $u <5 ; $u++) {
      $sum+=60;
    }
    $this->pointsblockIIbest=$sum;
  }
//Berechnen der Note aus den Punkten
  function berechnennoten(){
    //addieren von Block 1 und Block 2
    $speicher1=$this->pointsblockIaverage+$this->pointsblockIIaverage;
    $speicher2=$this->pointsblockIbest+$this->pointsblockIIbest;
    $note=1;
    //mithilfe der Punteverteilung rausfinden an welcher Stelle die Punkte sind
    foreach($this->punkteverteilung as $p)
      {

        if($speicher1>$p)
          {
            $this->noteaverage=$note;
            break;
          }
        else {
          $note+=0.1;
        }
      }
    $note=1;
    foreach($this->punkteverteilung as $p)
            {

              if($speicher2>$p)
                {
                  $this->notebest=$note;
                  break;
                }
              else {
                $note+=0.1;
              }

      }
  }
}

$test = new Noten($x,$y,$z);
$test->pointsblockIaverage();
$test->pointsblockIIaverage();
$test->pointsblockIbest();
$test->pointsblockIIbest();
$test->berechnennoten();
   ?>
</body>
</html>
