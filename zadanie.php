<?php
session_start();

if (!isset($_SESSION["NFZ"]))
 { // ustawiam ręcznie oddział nfz dla późniejszej klasy abstrakcyjnej, aby móc skalować.
   $_SESSION["NFZ"] = "15";
 }

 if (!isset($_SESSION["PRZYCHODNIE"])
  {//tworzę bazę dla przychodni.
   //dla lepszego skalowania na bazy dancyh jest to jedna tablica sesji do póżniejszego przeniesienie na mysql
   $_SESSION["PRZYCHODNIE"] = [];
  }
 if (!isset($_SESSION["PACJENCI"])
  {//dla pacjentów.
   //dla lepszego skalowania na bazy dancyh jest to jedna tablica sesji do póżniejszego przeniesienie na mysql
   $_SESSION["PACJENCI"] = [];
  }
 if (!isset($_SESSION["CHOROBY"])
  {//dla chorób.
   //dla lepszego skalowania na bazy dancyh jest to jedna tablica sesji do póżniejszego przeniesienie na mysql
   $_SESSION["CHOROBY"] = [];
  }

 class Nfz {//klasa powinna być abstrakcyjna?
  public $oddzial_nfz;

 }

class Przychodnia extends Nfz {
  public $nazwa;
  public $miasto;
  public $ulica;
  public $numer_kontaktowy;
  public $lista_pacjentow = [];

  public function __construct($nazwa, $miasto, $ulica, $numer_kontaktowy, $lista_pacjentow) {
    $this->nazwa = $nazwa;
    $this->miasto = $miasto;
    $this->ulica = $ulica;
    $this->numer_kontaktowy = $numer_kontaktowy;
    $this->lista_pacjentow = $lista_pacjentow;
  }
}

class Pacjent extends Przychodnia {
  public $nazwisko;
  public $imie;
  public $pesel;
  public $choroby = [];

  public function __construct($nazwisko,$imie,$pesel,$choroba){
    $this->nazwisko = $nazwisko;
    $this->imie = $imie;
    $this->pesel = $pesel;
    $this->choroby = $choroba;
  }
}

class Choroba extends Pacjent {
  public $kod_choroby;
  public $nazwa_choroby;

  public function __construct($kod, $nazwa){
    $this->kod_choroby = $kod;
    $this->nazwa_choroby = $nazwa;
  }
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
// kolumna 2

 if (isset($_GET['przychodnia']))
  {
    print "<p>przychodnia menu</p>";

    if ($_GET['przychodnia'] == "lista")
     {
       print "<p>lista przychodni</p>";
     }
    else if ($_GET['przychodnia'] == "dodaj")
     {
       print "<p>dodaj przychodnię</p>";
     }
    else if ($_GET['przychodnia'] == "przypisz")
     {
       print "<p>ocdczytuje pacjentów nieprzypisanych</p>";
     }
  }
else if (isset($_GET['pacjent']))
 {
   print "<p>pacjent menu</p>";

   if ($_GET['pacjent'] == "lista")
    {
      print "<p>lista pacjentów</p>";
    }
   else if ($_GET['pacjent'] == "dodaj")
    {
      print "<p>dodaj pacjenta</p>";
    }
 }
?>
</div>
<div>
<?php
//kolumna 3

if (isset($_GET['przychodnia']))
 {
   if ($_GET['przychodnia'] == "lista")
    {
      print "<p>mechniaka list</p>";
    }
   else if ($_GET['przychodnia'] == "dodaj")
    {
      print "<p>dodawanie przychodni</p>";
    }
   else if ($_GET['przychodnia'] == "przypisz")
    {
      print "<p>lista ludzików</p>";
    }
 }
else if (isset($_GET['pacjent']))
{
  if ($_GET['pacjent'] == "lista")
   {
     print "<p>lista wszytskich pacentów</p>";
   }
  else if ($_GET['pacjent'] == "dodaj")
   {
     print "<p>formularz dodawania pacjenta</p>";
   }
}
?>
</div>

</html>
