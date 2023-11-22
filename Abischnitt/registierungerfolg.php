<html>
<?php
session_start();
include(datenbank.php);
$P1=$_POST["P1"];
$P2=$_POST["P2"];
$P3=$_POST["P3"];
$P4=$_POST["P4"];
$P5=$_POST["P5"];
$G1=$_POST["G1"];
$G2=$_POST["G2"];
$G3=$_POST["G3"];
$G4=$_POST["G4"];
$G5=$_POST["G5"];
$G6=$_POST["G6"];
$G7=$_POST["G7"];
$benutzer=$_Session["benutzername"];

$query = "INSERT INTO daten(P1,P2,P3,P4,P5,G1,G2,G3,G4,G5,G6,G7) VALUES (?,?,?,?,?,?,?,?,?,?,?,?) WHERE Benutzername=?";
$x = $db->prepare($query);
$x->bind_param("sssssssssssss", $P1, $P2, $P3, $P4, $P5, $G1 ,$G2 ,$G3, $G4, $G5, $G6, $G7 ,$benutzer );
$x->execute();

echo "Ihre Regsitrierung war erfolgreich!";