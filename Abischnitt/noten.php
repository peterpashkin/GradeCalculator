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
  <div class="php">
<?php
//Auffangen der Daten, Speichern des Benutzernamen in einer Session
session_start();
include("datenbank.php");
$vorname=$_POST["vorname"];
$nachname=$_POST["nachname"];
$benutzername=$_POST["benutzername"];
$_SESSION["benutzer"]=$benutzername;
$passwort=$_POST["passwort"];
$sql = "";

$ueberpruefung="SELECT S_ID from Schueler WHERE Benutzername=?";
$kd=$db->prepare($ueberpruefung);
$kd->execute([$benutzername]);
$w=$kd->fetchAll(PDO::FETCH_BOTH);

//bei Benutzernamen die es bereits gibt, wird das Programm geschlossen und es kommt eine Fehlermeldung
if(count($w)>0)
  {

    echo "Dieser Benutzername existiert bereits";
    echo "<br><br>";
    echo "<a href='registrierung.php'><p>Hier geht es zurück zur Registrierung</p></a>";
    echo "</div>";
    die();

  }

// Eingabedaten speichern und übergeben damit erst eintragen nachdem die Registrierung durch ist


$array=[$vorname,$nachname,$benutzername,$passwort];
$_SESSION["warten"]=$array;



?>
</div>
<div class="inputs">
<h1>Bitte geben sie Ihre Kurswahl an:</h1>


<form action="registrierungerfolg.php" method="POST">
  <table style="width:100%">
<tr>
<td><label for="arr[]">Prüfungsfach 1/Leistungkurs 1</label></td>
<td><select name="arr[]" >
  <option value="Physik">Physik</option>
  <option value="Chemie">Chemie</option>
  <option value="Biologie">Biologie</option>
  <option value="Geschichte">Geschichte</option>
	<option value="Kunst">Kunst</option>
	<option value="Englisch">Englisch</option>
	<option value="Französisch">Französisch</option>

</select>
</td>
</div>
</tr>
<tr>
<td><label for="P2">Prüfungsfach 2/Leistungskurs 2</label></td>
<td>
<select name="arr[]" >
  <option value="Mathe">Mathe</option>
  <option value="Deutsch">Deutsch</option>
</select>
</td>
</tr>
<tr>
<td>
<label for="P3">Prüfungsfach 3</label>
</td>
<td>
<select name="arr[]">
  <option value="Deutsch">Deutsch</option>
  <option value="Mathe">Mathe</option>
</select>
</td>
</tr>
<tr>
  <td>
<label for="P4">Prüfungsfach 4</label></td>
<td>
<select name="arr[]" >
	<option value="Informatik">Informatik</option>
	<option value="Musik">Musik</option>
  <option value="Physik">Physik</option>
  <option value="Chemie">Chemie</option>
  <option value="Biologie">Biologie</option>
  <option value="Geschichte">Geschichte</option>
	<option value="Kunst">Kunst</option>
	<option value="Englisch">Englisch</option>
	<option value="Französisch">Französisch</option>
	<option value="Geschichte">Geschichte</option>
	<option value="Geografie">Geografie</option>
	<option value="GRW">GRW</option>
	<option value="Religion">Religion</option>
	<option value="Ethik">Ethik</option>
  <option value="Philosophie">Philosophie</option>
  <option value="Darstellendes Spiel">Darstellendes Spiel</option>
</select>
</td>
</tr>
<tr>
  <td>
<label for="P5">Prüfungsfach 5</label></td>
<td>
<select name="arr[]" >
  <option value="Geschichte">Geschichte</option>
  <option value="Physik">Physik</option>
  <option value="Chemie">Chemie</option>
  <option value="Biologie">Biologie</option>
	<option value="Kunst">Kunst</option>
	<option value="Englisch">Englisch</option>
	<option value="Französisch">Französisch</option>
	<option value="Geschichte">Geschichte</option>
	<option value="Geografie">Geografie</option>
	<option value="GRW">GRW</option>
	<option value="Religion">Religion</option>
	<option value="Ethik">Ethik</option>
  <option value="Informatik">Informatik</option>
  <option value="Musik">Musik</option>
  <option value="Darstellendes Spiel">Darstellendes Spiel</option>
  <option value="Philosophie">Philosophie</option>
</select>
</td>
</tr>
<tr>
  <td>
<label for="G1">Grundkursfach 1</label></td>
<td>
<select name="arr[]" >
  <option value="Chemie">Chemie</option>
  <option value="Biologie">Biologie</option>
  <option value="Geschichte">Geschichte</option>
	<option value="Kunst">Kunst</option>
	<option value="Englisch">Englisch</option>
	<option value="Französisch">Französisch</option>
	<option value="Geschichte">Geschichte</option>
	<option value="Geografie">Geografie</option>
	<option value="GRW">GRW</option>
	<option value="Sport">Sport</option>
	<option value="Religion">Religion</option>
	<option value="Ethik">Ethik</option>
  <option value="Informatik">Informatik</option>
	<option value="Musik">Musik</option>
  <option value="Physik">Physik</option>
  <option value="Darstellendes Spiel">Darstellendes Spiel</option>
  <option value="Philosophie">Philosophie</option>
</select>
</td>
</tr>
<tr>
  <td>
<label for="G2">Grundkursfach 2</label></td>
<td>
<select name="arr[]" >
  <option value="Biologie">Biologie</option>
  <option value="Geschichte">Geschichte</option>
	<option value="Kunst">Kunst</option>
	<option value="Englisch">Englisch</option>
	<option value="Französisch">Französisch</option>
	<option value="Geschichte">Geschichte</option>
	<option value="Geografie">Geografie</option>
	<option value="GRW">GRW</option>
	<option value="Sport">Sport</option>
	<option value="Religion">Religion</option>
	<option value="Ethik">Ethik</option>
  <option value="Informatik">Informatik</option>
	<option value="Musik">Musik</option>
  <option value="Physik">Physik</option>
  <option value="Chemie">Chemie</option>
  <option value="Darstellendes Spiel">Darstellendes Spiel</option>
  <option value="Philosophie">Philosophie</option>
</select>
</td>
</tr>
<tr>
  <td>
<label for="G3">Grundkursfach 3</label></td>
<td>
<select name="arr[]" >
  <option value="Musik">Musik</option>
  <option value="Geschichte">Geschichte</option>
	<option value="Kunst">Kunst</option>
	<option value="Englisch">Englisch</option>
	<option value="Französisch">Französisch</option>
	<option value="Geschichte">Geschichte</option>
	<option value="GRW">GRW</option>
	<option value="Sport">Sport</option>
	<option value="Religion">Religion</option>
	<option value="Ethik">Ethik</option>
  <option value="Informatik">Informatik</option>
  <option value="Physik">Physik</option>
  <option value="Chemie">Chemie</option>
  <option value="Biologie">Biologie</option>
  <option value="Darstellendes Spiel">Darstellendes Spiel</option>
  <option value="Philosophie">Philosophie</option>
</select>
</td>
</tr>
<tr>
  <td>
<label for="G4">Grundkursfach 4</label></td>
<td>
<select name="arr[]" >
	<option value="Englisch">Englisch</option>
	<option value="Französisch">Französisch</option>
	<option value="Geschichte">Geschichte</option>
	<option value="Geografie">Geografie</option>
	<option value="GRW">GRW</option>
	<option value="Sport">Sport</option>
	<option value="Religion">Religion</option>
	<option value="Ethik">Ethik</option>
  <option value="Informatik">Informatik</option>
	<option value="Musik">Musik</option>
  <option value="Physik">Physik</option>
  <option value="Chemie">Chemie</option>
  <option value="Biologie">Biologie</option>
  <option value="Geschichte">Geschichte</option>
	<option value="Kunst">Kunst</option>
  <option value="Darstellendes Spiel">Darstellendes Spiel</option>
  <option value="Philosophie">Philosophie</option>
</select></td>
</tr>
<tr>
  <td>
<label for="G5">Grundkursfach 5</label></td>
<td>
<select name="arr[]" >
	<option value="Geografie">Geografie</option>
	<option value="GRW">GRW</option>
	<option value="Sport">Sport</option>
	<option value="Religion">Religion</option>
	<option value="Ethik">Ethik</option>
  <option value="Informatik">Informatik</option>
	<option value="Musik">Musik</option>
  <option value="Physik">Physik</option>
  <option value="Chemie">Chemie</option>
  <option value="Biologie">Biologie</option>
  <option value="Geschichte">Geschichte</option>
	<option value="Kunst">Kunst</option>
	<option value="Englisch">Englisch</option>
	<option value="Französisch">Französisch</option>
	<option value="Geschichte">Geschichte</option>
  <option value="Darstellendes Spiel">Darstellendes Spiel</option>
  <option value="Philosophie">Philosophie</option>
</select></td>
</tr>
<tr>
  <td>
<label for="G6">Grundkursfach 6</label></td>
<td>
<select name="arr[]" >
	<option value="Sport">Sport</option>
	<option value="Religion">Religion</option>
	<option value="Ethik">Ethik</option>
  <option value="Informatik">Informatik</option>
	<option value="Musik">Musik</option>
  <option value="Physik">Physik</option>
  <option value="Chemie">Chemie</option>
  <option value="Biologie">Biologie</option>
  <option value="Geschichte">Geschichte</option>
	<option value="Kunst">Kunst</option>
	<option value="Englisch">Englisch</option>
	<option value="Französisch">Französisch</option>
	<option value="Geschichte">Geschichte</option>
	<option value="Geografie">Geografie</option>
	<option value="GRW">GRW</option>
  <option value="Darstellendes Spiel">Darstellendes Spiel</option>
  <option value="Philosophie">Philosophie</option>
</select></td>
</tr>
<tr>
  <td>
<label for="G7">Grundkursfach 7</label></td>
<td>
<select name="arr[]" >
	<option value="Religion">Religion</option>
	<option value="Ethik">Ethik</option>
  <option value="Informatik">Informatik</option>
	<option value="Musik">Musik</option>
  <option value="Physik">Physik</option>
  <option value="Chemie">Chemie</option>
  <option value="Biologie">Biologie</option>
  <option value="Geschichte">Geschichte</option>
	<option value="Kunst">Kunst</option>
	<option value="Englisch">Englisch</option>
	<option value="Französisch">Französisch</option>
	<option value="Geschichte">Geschichte</option>
	<option value="Geografie">Geografie</option>
	<option value="GRW">GRW</option>
	<option value="Sport">Sport</option>
  <option value="Darstellendes Spiel">Darstellendes Spiel</option>
  <option value="Philosophie">Philosophie</option>
</select></td>
</tr>
<tr>
  <td>
    <div class="submitt">
      <input type="submit" class="submitter" value="Registrierung bestätigen"></input>
    </div>

</td>
</table>
</form>
</div>
</body>
</html>
