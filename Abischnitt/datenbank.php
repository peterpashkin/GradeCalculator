<html>


<?php
try {
$db = new PDO('mysql:host=localhost;dbname=datenbank', "root", "");
}
catch (PDOException $e) {
   print "Error!: " . $e->getMessage() . "<br/>";
   die();} // Fremdcode

?>
