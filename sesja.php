<?php

include "klasy.php";
session_start();

print "PRZYCHODNIE<br/>";
foreach ($_SESSION["PRZYCHODNIE"] as $klucze => $wartosci) {
  print "klucz ".$klucze." wartosc: ".$wartosci."<br/>";
  print_r($wartosci);
  print "<br/><br/>";
}
print "<br/><br/>";

print "PACJENCI<br/>";
foreach ($_SESSION["PACJENCI"] as $klucze => $wartosci) {
  print "klucz ".$klucze." wartosc: ".$wartosci."<br/>";
  print_r($wartosci);
  print "<br/><br/>";
}
print "<br/><br/>";

print "CHOROBY<br/>";
foreach ($_SESSION["CHOROBY"] as $klucze => $wartosci) {
  print "klucz ".$klucze." wartosc: ".$wartosci."<br/>";
  print_r($wartosci);
  print "<br/><br/>";
}
print "<br/><br/>";

print "NFZ: ".$_SESSION["NFZ"];
print "<br/><br/>";

print "<a href='reset.php'>zniszcz wszystkie sesje</a><br/>";
print "<a href='zadanie.php'>zadanie</a>";
 ?>
