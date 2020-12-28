<?php
session_start();

if (!isset($_SESSION["NFZ"]))
 {
   $_SESSION["NFZ"] = "15";
 }
 // ustawiam ręcznie oddział nfz dla późniejszej klasy abstrakcyjnej, aby móc skalować.

 abstract class Nfz {//clasa abstrakcyjna
  public $oddzial;
 }



?>
