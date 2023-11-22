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

  <?php
  include("datenbank.php");
  session_start();

  $Benutzer=$_SESSION["benutzer"];
  $s_id=$_SESSION["S_ID"];
  include("svg.php");
  //Einfügen für übergebene Daten
if(isset($_POST["Fach"]) and $_POST["Fach"]!="")
{
//Empfangen der Variablen
 $Fach=$_POST["Fach"];
  $HJ=$_POST["Halbjahr"];
  $Note=$_POST["Note"];

//Einsetzen der Werte für Fach ID Überprüfen über Fach Tabelle

//Fremdskript https://www.codeproject.com/Questions/4380387/How-to-insert-into-table-with-values-from-another
  $sql="INSERT INTO fachhalbjahr (S_ID, F_ID, Halbjahr, Note)
  SELECT ?, fach.F_ID , ? , ?
  FROM fach
  WHERE fach.Fachname = ?";
  $f=$db->prepare($sql);

//SQL Syntax für alle Fächer
$sqlfach="SELECT fach.Fachname from fach,s_f_beziehung WHERE s_f_beziehung.S_ID=? AND s_f_beziehung.F_ID=fach.F_ID";
$c=$db->prepare($sqlfach);
$c->execute([$s_id]);
$allefach=$c->fetchALL(PDO::FETCH_BOTH);

//Sql Syntax für alle prüfungsfächer
$sqlpruef="SELECT fach.Fachname from fach,s_f_beziehung WHERE s_f_beziehung.Pruefungsfach=1 AND s_f_beziehung.F_ID=fach.F_ID AND s_f_beziehung.S_ID=?";
$l=$db->prepare($sqlpruef);
$l->execute([$s_id]);
$allepruef=$l->fetchAll(PDO::FETCH_BOTH);

  $sql2="INSERT INTO pruefung(S_ID, F_ID, Pruefungsnote) SELECT ?,fach.F_ID,? FROM fach WHERE fach.Fachname=?";
  $g=$db->prepare($sql2);
// Bei Eingabe von Halbjahr 5 soll eine Prüfungsnote eingefügt werden
  if($HJ==5)
    {
      $counter=0;
      for ($i= 0; $i  < count($allepruef); $i++) {
        if($Fach==$allepruef[$i][0])
          {
            $counter++;
          }
      }
      if($counter==1)
        {
          $g->execute([$s_id,$Note,$Fach]);
        }
      else{
        echo "<script type='text/javascript'>alert('Kein Prüfungsfach!')</script>";
      }
    }
  else{
    $counter=0;
    for ($i=0; $i < count($allefach); $i++) {
      if($Fach==$allefach[$i][0])
        {
          $counter++;
        }

    }
    if($counter==1){
        $f->execute([$s_id,$HJ,$Note,$Fach]);
    }
    else{
      echo "<script type='text/javascript'>alert('Geben sie ein Fach ein, das sie gewählt haben!')</script>";
    }

}
}
  include("oop.php");

  ?>
  <div class="noteundimg">
  <div class="note">
    <div class="kasten1">
  <h2><?php if(count($test->allefaecher)!=0 or $test->fehler==1)
  {
  echo $test->noteaverage;
  echo "<br>";
  echo $test->pointsblockIaverage+$test->pointsblockIIaverage;
}
else{
  echo "noch keine Noten";
}?></h2>
  <h5>Im Durchschnitt erreichte Note</h5>
  </div>
  </div>

  <div class="img">
    <svg width="640" height="480" xmlns="http://www.w3.org/2000/svg" xmlns:svg="http://www.w3.org/2000/svg">
     <g class="layer">
      <title>Layer 1</title>
      <text fill="red" font-family="Helvetica" font-size="16" font-weight="bold" id="svg_8" stroke="#ffffff" stroke-width="0" text-anchor="middle" x="110" xml:space="preserve" y="30.73295">Sächsicher Durchschnitt</text>
      <text fill="black" font-family="Helvetica" font-size="16" font-weight="bold" id="svg_8" stroke="#ffffff" stroke-width="0" text-anchor="middle" x="320" xml:space="preserve" y="30.73295">eigener Schnitt</text>
      <line class="y-Achse" fill="none" id="svg_5" stroke="#000000" x1="54" x2="54" y1="51.7" y2="444"/>
      <line class="x-achse" fill="none" id="svg_6" stroke="#000000" transform="matrix(1 0 0 1 0 0)" x1="54" x2="600" y1="444" y2="444"/>
      <line fill="none" id="svg_7" stroke="#000000" x1="54" x2="67.038405" y1="52" y2="66.038405"/>
      <line fill="none" id="svg_8" stroke="#000000" x1="600" x2="580.435832" y1="444" y2="426.435832"/>
      <line fill="none" id="svg_9" stroke="#000000" x1="617" x2="616" y1="510" y2="511"/>
      <line fill="none" id="svg_10" stroke="#000000" x1="54" x2="40.252451" y1="52" y2="64.747549"/>
      <line fill="none" id="svg_11" stroke="#000000" x1="596" x2="581.682179" y1="444" y2="458.317821"/>
      <line fill="none" id="svg_12" stroke="#000000" x1="335" x2="336" y1="562" y2="562"/>
      <line class="strich1" fill="none" id="svg_21" stroke="#000000" x1="44" x2="64" y1="421.5" y2="421.5"/>
      <line class="strich2" fill="none" id="svg_13" stroke="#000000" x1="44" x2="64" y1="399" y2="399"/>
      <line class="strich3" fill="none" id="svg_14" stroke="#000000" transform="matrix(1 0 0 1 0 0)" x1="44" x2="64" y1="376.5" y2="376.5"/>
      <line class="strich4" fill="none" id="svg_15" stroke="#000000" x1="44" x2="64" y1="354" y2="354"/>
      <line class="strich5" fill="none" id="svg_1" stroke="#000000" transform="matrix(1 0 0 1 0 0)" x1="44" x2="64" y1="331.5" y2="331.5"/>
      <line class="strich6" fill="none" id="svg_23" stroke="#000000" x1="44" x2="64" y1="309" y2="309"/>
      <line class="strich7" fill="none" id="svg_2" stroke="#000000" transform="matrix(1 0 0 1 0 0)" x1="44" x2="64" y1="286.5" y2="286.5"/>
      <line class="strich8" fill="none" id="svg_3" stroke="#000000" transform="matrix(1 0 0 1 0 0)" x1="44" x2="64" y1="264" y2="264"/>
      <line class="strich9" fill="none" id="svg_4" stroke="#000000" transform="matrix(1 0 0 1 0 0)" x1="44" x2="64" y1="241.5" y2="241.5"/>
      <line class="strich10" fill="none" id="svg_16" stroke="#000000" transform="matrix(1 0 0 1 0 0)" x1="44" x2="64" y1="219" y2="219"/>
      <line class="strich11" fill="none" id="svg_17" stroke="#000000" x1="44" x2="64" y1="196.5" y2="196.5"/>
      <line class="strich12" fill="none" id="svg_18" stroke="#000000" transform="matrix(1 0 0 1 0 0)" x1="44" x2="64" y1="174" y2="174"/>
      <line class="strich13" fill="none" id="svg_19" stroke="#000000" transform="matrix(1 0 0 1 0 0)" x1="44" x2="64" y1="151.5" y2="151.5"/>
      <line class="strich14" fill="none" id="svg_20" stroke="#000000" transform="matrix(1 0 0 1 0 0)" x1="44" x2="64" y1="129" y2="129"/>
      <line class="strich15" fill="none" id="svg_22" stroke="#000000" x1="44" x2="64" y1="106.5" y2="106.5"/>
      <line class="x1" fill="none" id="svg_24" stroke="#000000" transform="matrix(1 0 0 1 0 0)" x1="163" x2="163" y1="434" y2="454"/>
      <line class="x2" fill="none" id="svg_25" stroke="#000000" transform="matrix(1 0 0 1 0 0)" x1="272" x2="272" y1="434" y2="454"/>
      <line class="x3" fill="none" id="svg_26" stroke="#000000" transform="matrix(1 0 0 1 0 0)" x1="381" x2="381" y1="434" y2="454"/>
      <line class="x4" fill="none" id="svg_27" stroke="#000000" x1="490" x2="490" y1="434" y2="454"/>
      <text fill="#000000" font-family="serif" font-size="24" id="svg_28" stroke="#000000" stroke-width="0" text-anchor="middle" x="163" xml:space="preserve" y="473">1</text>
      <text fill="#000000" font-family="serif" font-size="24" id="svg_29" stroke="#000000" stroke-width="0" text-anchor="middle" x="272" xml:space="preserve" y="474">2</text>
      <text fill="#000000" font-family="serif" font-size="24" id="svg_30" stroke="#000000" stroke-width="0" text-anchor="middle" x="381" xml:space="preserve" y="475">3</text>
      <text fill="#000000" font-family="serif" font-size="24" id="svg_31" stroke="#000000" stroke-width="0" text-anchor="middle" x="487.714286" xml:space="preserve" y="475.571429">4</text>
      <text fill="#000000" font-family="serif" font-size="24" id="svg_32" stroke="#000000" stroke-width="0" text-anchor="middle" x="32.714286" xml:space="preserve" y="431">1</text>
      <text fill="#000000" font-family="serif" font-size="24" id="svg_33" stroke="#000000" stroke-width="0" text-anchor="middle" x="32.714286" xml:space="preserve" y="408.142857">2</text>
      <text fill="#000000" font-family="serif" font-size="24" id="svg_34" stroke="#000000" stroke-width="0" text-anchor="middle" x="32.714286" xml:space="preserve" y="385.142857">3</text>
      <text fill="#000000" font-family="serif" font-size="24" id="svg_35" stroke="#000000" stroke-width="0" text-anchor="middle" x="31.714286" xml:space="preserve" y="363.142857">4</text>
      <text fill="#000000" font-family="serif" font-size="24" id="svg_36" stroke="#000000" stroke-width="0" text-anchor="middle" x="31.714286" xml:space="preserve" y="340.142857">5</text>
      <text fill="#000000" font-family="serif" font-size="24" id="svg_37" stroke="#000000" stroke-width="0" text-anchor="middle" x="32.714286" xml:space="preserve" y="317.142857">6</text>
      <text fill="#000000" font-family="serif" font-size="24" id="svg_38" stroke="#000000" stroke-width="0" text-anchor="middle" x="32.714286" xml:space="preserve" y="296.142857">7</text>
      <text fill="#000000" font-family="serif" font-size="24" id="svg_39" stroke="#000000" stroke-width="0" text-anchor="middle" x="32.714286" xml:space="preserve" y="272.142857">8</text>
      <text fill="#000000" font-family="serif" font-size="24" id="svg_40" stroke="#000000" stroke-width="0" text-anchor="middle" x="33.714286" xml:space="preserve" y="249.142857">9</text>
      <text fill="#000000" font-family="serif" font-size="24" id="svg_41" stroke="#000000" stroke-width="0" text-anchor="middle" x="27.714286" xml:space="preserve" y="226.142857">10</text>
      <text fill="#000000" font-family="serif" font-size="24" id="svg_42" stroke="#000000" stroke-width="0" text-anchor="middle" x="27.714286" xml:space="preserve" y="202.142857">11</text>
      <text fill="#000000" font-family="serif" font-size="24" id="svg_43" stroke="#000000" stroke-width="0" text-anchor="middle" x="27.714286" xml:space="preserve" y="180.142857">12</text>
      <text fill="#000000" font-family="serif" font-size="24" id="svg_44" stroke="#000000" stroke-width="0" text-anchor="middle" x="29.714286" xml:space="preserve" y="158">13</text>
      <text fill="#000000" font-family="serif" font-size="24" id="svg_45" stroke="#000000" stroke-width="0" text-anchor="middle" x="29.714286" xml:space="preserve" y="136">14</text>
      <text fill="#000000" font-family="serif" font-size="24" id="svg_46" stroke="#000000" stroke-width="0" text-anchor="middle" x="31.714286" xml:space="preserve" y="114">15</text>
      <line class="einszwei" fill="none" id="svg_49" stroke="#000000" transform="matrix(1 0 0 1 0 0)" x1="163" x2="272" y1="<?php echo $ywerte[0]?>" y2="<?php echo $ywerte[1]?>"/>
      <line class="zweidrei" fill="none" id="svg_53" stroke="#000000" transform="matrix(1 0 0 1 0 0)" x1="272" x2="381" y1="<?php echo $ywerte[1]?>" y2="<?php echo $ywerte[2]?>"/>
      <line class="dreivier" fill="none" id="svg_54" stroke="#000000" x1="381" x2="490" y1="<?php echo $ywerte[2]?>" y2="<?php echo $ywerte[3]?>"/>
      <line class="durchscnitt" fill="none" id="svg_55" stroke="#ea3f3f" x1="54" x2="490" y1="207" y2="207"/>
     </g>
    </svg>
  </div>
  <div class="bestenote">
    <div class="kasten2">
  <h2> <?php
  if(count($test->allefaecher)!=0)
    {echo $test->notebest;
      echo "<br>";
    echo $test->pointsblockIbest+$test->pointsblockIIbest;
    }
  else{ echo "noch keine Noten";}
  ?>
  </h2>
  <h5> Beste erreichbare Note
</h5>
  </div>
  </div>
  </div>

  <div class="two">
  <h3> Noten schnell eintragen!</h3>
  <h6>Beachte: Hier können Noten nicht überarbeitet werden und Prüfungsnoten werden mit Halbjahr 5 angegeben!</h6>
      <form method="post" action="hauptseite.php">
        <input type="text" name="Fach" placeholder="Fach" required >
        <input type="number" name="Halbjahr" placeholder="Halbjahr" required min=1 max=5>
        <input type="number" name="Note" placeholder="Note" required min=0 max=15>
        <input type="submit" >
      </form>
        </div>

    <div class="part3">
    <br>
    <a href="noteneintragen.php"><h3>Gesamte Notenübersicht und schnelles Ändern und Einfügen von Noten</h3></a>
  </div>







  </body>
</html>
