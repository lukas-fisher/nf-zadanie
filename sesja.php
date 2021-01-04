<?php

include "klasy.php";
session_start();

print_r($_SESSION["PRZYCHODNIE"]);
print "<br/><br/>";

print_r($_SESSION["PACJENCI"]);
print "<br/><br/>";

print_r($_SESSION["CHOROBY"]);
print "<br/><br/>";

print_r($_SESSION["NFZ"]);
print "<br/><br/>";

print "<a href='reset.php'>zniszcz wszystkie sesje</a><br/>";
print "<a href='zadanie.php'>zadanie</a>";
 ?>
