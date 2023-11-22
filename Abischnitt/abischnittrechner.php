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



    $punkteverteilung=[822,804,786,768,750,732,714,696,678,660,642,624,606,588,570,552,534,516,498,480,462,444,426,408,390,372,354,336,318,300];

    $s_id=$_SESSION["S_ID"];

    //alle Noten des Schülers bestimmen mit Leistungskurs, damit diese in der Rechnung beachtet werden kann
    $name=$_SESSION["benutzer"];
    $sql="SELECT fachhalbjahr.Note,s_f_beziehung.Leistungskurs,s_f_beziehung.Pruefungsfach,fachhalbjahr.Einbringen from fachhalbjahr,s_f_beziehung WHERE s_f_beziehung.S_ID=? AND fachhalbjahr.S_ID=? AND s_f_beziehung.F_ID=fachhalbjahr.F_ID ORDER BY fachhalbjahr.F_ID DESC";
    $f=$db->prepare($sql);
    $f->execute([$s_id,$s_id]);
    $x=$f->fetchAll(PDO::FETCH_BOTH);

    // rechnet die Halbjahre zusammen und rechnet Leistungskurse doppelt
    $count=0;
    $sum=0;
    for($u=0;$u<count($x);$u++)
      {
        if($x[$u][3]==1)
        {
        if($x[$u][1]==1)
          {
            $count+=2;
            $sum=$sum+2*$x[$u]["Note"];
          }
        else
          {
            $sum+=$x[$u]["Note"];
            $count++;
          }
        }
        else {
          continue;
        }

      }

    //Rechnung der Punkte von Block 1
    if($count>0)
    {
      $points=round(($sum/$count)*40);


    //alle prüfungsfächer bestimmen
      $pruef="SELECT fach.Fachname from fach,s_f_beziehung WHERE s_f_beziehung.S_ID=? AND s_f_beziehung.Pruefungsfach=1 AND fach.F_ID=s_f_beziehung.F_ID";
      $noten=$db->prepare($pruef);
      $noten->execute([$s_id]);
      $u = $noten->fetchAll(PDO::FETCH_BOTH);

    //array vorbereiten für speichern von Noten und alle Prüfungsnoten bestimmen
      $speicherstat=[];
      $sqlueber="SELECT Pruefungsnote FROM fach,pruefung WHERE pruefung.S_ID=? AND fach.F_ID=pruefung.F_ID AND fach.Fachname=?";
      $z=$db->prepare($sqlueber);

    //Noten für das jeweilige Fach in das vorbereitete Array packen, dabei Stellen ohne Note mit dem Namen des Faches auffüllen
      foreach($u as $fach)
        {
          $z->execute([$s_id,$fach["Fachname"]]);
          $ergebnis=$z->fetch(PDO::FETCH_BOTH);
          if($ergebnis[0]=="")
            {
              $speicherstat[]=$fach["Fachname"];
            }
          else {
            //als int definieren, da es sonst Probleme gibt
            $speicherstat[]=(int)$ergebnis[0];
          }
        }

    //berechnet und rundet den Notendurchschnitt eines Arrays
        function average($note)
          {
            $sum=0;
            foreach($note as $a)
              {
                $sum+=$a[0];
              }
              $aver=round($sum/count($note));
              return $aver;

          }

    //SQL Syntax für Bestimmung von den Halbjahresnoten falls es keine Note gibt
        $average="SELECT fachhalbjahr.Note from fachhalbjahr,fach WHERE fachhalbjahr.S_ID=? AND fachhalbjahr.F_ID=fach.F_ID AND fach.Fachname=?";
        $b=$db->prepare($average);
        $sum=0;

    //Punkte ausrechnen für gegebene Noten und Durchschnitt für Fächer die keinen haben Durchschnittsbildung
        foreach($speicherstat as $s)
          {
            if(is_string($s))
              {
                $b->execute([$s_id,$s]);
                $note=$b->fetchAll(PDO::FETCH_BOTH);

                if(count($note)>0)
                {
                $durchschnitt=average($note);

                $sum+=$durchschnitt*4;
                }
                else {
                  //Durchschnittsbildung von 11 Punkten falls es noch keine Noten in einem Fach gibt
                  $sum+=44;
                }
              }
            else {

              $sum+=$s*4;
            }
          }
          $gesamt=$points+$sum;

    //gesamte punkte ausrechnen und mit dem Punkteverteilung Array die resultierende Note bestimmen
    function note($punkte,$punkteverteilung)
    {
        if($punkte<300)
          {
            return "Durchgefallen";
          }
        $note=1;
        foreach($punkteverteilung as $p)
          {
            if ($punkte>$p) {
              break;
            }
            else {
              $note+=0.1;
            }
          }
        return $note;
    }
  //Best-Case Szenario

    //Funktion die restliche Noten mit 15 auffüllt und Durchschnitt ausrechnet
    function pgesbest($arr)
      {
        $speicher=0;
        $summe=0;
      if(count($arr)<4)
        {
          for($i=count($arr);$i<4;$i++)
            {
              $speicher+=15;
            }
        }
        for($a=0;$a<count($arr);$a++)
          {
            if($arr[$a][1]==0)
              {
                continue;
              }
            else{
            $summe+=$arr[$a][0];
          }
          }
        $summe+=$speicher;
        return $summe;
      }



    //alle fächer des Benutzers bestimmen
    $fachsyntax="SELECT fach.Fachname,s_f_beziehung.Leistungskurs from fach,s_f_beziehung WHERE s_f_beziehung.S_ID=? AND fach.F_ID=s_f_beziehung.F_ID";
    $b=$db->prepare($fachsyntax);
    $b->execute([$s_id]);
    $allefaecher=$b->fetchAll(PDO::FETCH_BOTH);

    //Sytax für alle Noten für das jeweilige Fach vorbereiten
    $indinoten="SELECT fachhalbjahr.Note,fachhalbjahr.Einbringen from fachhalbjahr,fach WHERE fachhalbjahr.S_ID=? AND fach.Fachname=? AND fachhalbjahr.F_ID=fach.F_ID";
    $h=$db->prepare($indinoten);

    $pointsbest=0;
    $gegen=0;
    foreach($allefaecher as $fach2)
      {
        $h->execute([$s_id,$fach2["Fachname"]]);
        $belegtesfach=$h->fetchAll(PDO::FETCH_BOTH);
        foreach($belegtesfach as $b)
          {
            if($b[1]==0)
              {
                $gegen++;
              }
          }
        $pgesamt=pgesbest($belegtesfach);
        if($fach2[1]==1)
          {
            $pgesamt*=2;
          }
        $pointsbest+=$pgesamt;
      }
      $pointsblockI=($pointsbest/(56-$gegen))*40;

      //Prüfungsnoten zusammenrechnen und für keine 15 einsetzen

      $indipruef="SELECT Pruefungsnote FROM pruefung WHERE S_ID=?";
      $ax=$db->prepare($indipruef);
      $ax->execute([$s_id]);
      $pruefnoten=$ax->fetchAll(PDO::FETCH_BOTH);
    $pointsblockII=0;
      foreach($pruefnoten as $p)
        {
          $pointsblockII+=$p[0]*4;
        }

      if(count($pruefnoten)<4)
        {
          for($k=count($pruefnoten);$k<=4;$k++)
            {
              $pointsblockII+=60;
            }
        }
      $gesamtbest=round($pointsblockI+$pointsblockII);

}



   ?>
</body>
</html>
