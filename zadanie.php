<?php
session_start();

if (!isset($_SESSION["NFZ"]))
 {
   $_SESSION["NFZ"] = "15";
 }
 // ustawiam ręcznie oddział nfz dla późniejszej klasy abstrakcyjnej, aby móc skalować.

 class Nfz {//klasa powinna być abstrakcyjna?
  public $oddzial_nfz;

 }

class Przychodnia extends Nfz {
  public $nazwa;
  public $miasto;
  public $lista_pacjentow;
}

class Pacjent extends Przychodnia {
  public $nazwisko;
  public $imie;
  public $pesel;
  public $choroby;
}


?>

<html  lang='pl'>
<head>
  <title>system NFZ</title>
  <meta charset='UTF-8' />
  <link rel='stylesheet' href='style.css'>
</head>

<div>
 <p>NFZ:
  <ul>Przychodnie:
    <li><a href='?przychodnia=lista'>pokaż listę</a></li>
    <li><a href='?przychodnia=dodaj'>dodaj przychodnię</a></li>
    <li><a href='?przychodnia=przypisz'>przypisz pacjenta</a></li>
  </ul>
  <ul>Pacjenci
    <li><a href='?pacjent=lista'>pokaż listę</a></li>
    <li><a href='?pacjent=dodaj'>dodaj pacjenta</a></li>

  </ul>
 </p>
</div>
<div>
<?php
 if (isset($_GET['przychodnia']))
  {
    print "przychodnia menu";
  }
else if (isset($_GET['pacjent']))
 {
   print "pacjent menu";
 }
?>
</div>
<div>
<?php

?>
</div>

</html>
