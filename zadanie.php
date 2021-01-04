<?php
session_start();

if (!isset($_SESSION["NFZ"]))
 { // ustawiam ręcznie oddział nfz dla późniejszej klasy abstrakcyjnej, aby móc skalować.
   $_SESSION["NFZ"] = "15";
 }

 if (!isset($_SESSION["PRZYCHODNIE"]))
  {//tworzę bazę dla przychodni.
   //dla lepszego skalowania na bazy dancyh jest to jedna tablica sesji do póżniejszego przeniesienie na mysql
   $_SESSION["PRZYCHODNIE"] = [];
  }
 if (!isset($_SESSION["PACJENCI"]))
  {//dla pacjentów.
   //dla lepszego skalowania na bazy dancyh jest to jedna tablica sesji do póżniejszego przeniesienie na mysql
   $_SESSION["PACJENCI"] = [];
  }
 if (!isset($_SESSION["CHOROBY"]))
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

  public function __construct($nazwa, $miasto, $ulica, $numer_kontaktowy) {
    $this->nazwa = $nazwa;
    $this->miasto = $miasto;
    $this->ulica = $ulica;
    $this->numer_kontaktowy = $numer_kontaktowy;
    $this->lista_pacjentow = [];
  }

  public function utworzenie(){
    echo "Gratuluję!<br/>";
    echo "Przychodnia <b>".$this->nazwa."</b> może przyjmować pacjentów z miasta: <b>";
    echo $this->miasto."</b> w okolicy ulica <b>".$this->ulica."</b><br/>";
    echo "Dodatkowo w bazie zapisano numer kontaktowy: ".$this->numer_kontaktowy;
    echo "<br/><br/>";
  }

 public function szczegoly(){
   echo "Przychodnia: <b>".$this->nazwa."</b><br/>";
   echo "Miasto: <b>".$this->miasto."</b><br/>";
   echo "Ulica: <b>".$this->ulica."</b><br/>";
   echo "Nr kontaktowy: <b>".$this->numer_kontaktowy."</b><br/>";

 }

  public function __toString(){
    return "<b>".$this->nazwa."</b> (".$this->miasto.") ";
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
    print "<p>MENU PRZYCHODNI</p>";

    if ($_GET['przychodnia'] == "lista")
     {// tutaj drukuję nazwy z sesji (poźniej bazy danych)
       if (isset($_GET['akcja']))
        {
          if ($_GET['akcja'] == "usun")
           {
            unset ($_SESSION["PRZYCHODNIE"][$_GET['placowka']]);
            unset ($_GET['placowka']);
           }
        }       
       $liczba_przychodni = 0;
       print "<span class='lista'>";
       foreach ($_SESSION["PRZYCHODNIE"] as $klucze => $wartosci) {
         print "- ".$wartosci." <a href='?przychodnia=lista&placowka=".$klucze."'>[szczegóły]</a><br/>";
         $liczba_przychodni++;
       }
       print "</span>";
      //sprawdza ilość wydrukowanych przychodni
      print "<span class='info'>";
      if ($liczba_przychodni == 0)
       {
        print "najpierw dodaj przychodnię";
       }
      else
       {
        print "liczba przychodni w bazie: ".$liczba_przychodni;
       }
      print "</span>";

     }
    else if ($_GET['przychodnia'] == "dodaj")
     {//sekcja informacyjna dodawania przychodni
       print "<span class='info'>użytkowniku,<br/>w aktualnej wersji programu nie będzie można wyedytować danych, trzeba je będzie usunąć i dodać ponownie.<br/><br/>";

       if (isset($_POST['nazwa']))
        {
          $przychodnia = new Przychodnia($_POST['nazwa'], $_POST['miasto'], $_POST['ulica'], $_POST['telefon']);
          $przychodnia->utworzenie();
          $_SESSION["PRZYCHODNIE"][] = $przychodnia;
        }
       print "</span>";

     }
    else if ($_GET['przychodnia'] == "przypisz")
     {//drukuje pacjentów
       print "<p>odczytuje pacjentów nieprzypisanych</p>";
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
else
 {
  print "<p>wybierz akcję z menu</p>";
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

     print "<span class='info'>";
     if (isset($_GET['placowka']))
      {
        $_SESSION["PRZYCHODNIE"][$_GET['placowka']]->szczegoly();
        print "<a href='?przychodnia=lista&placowka=".$_GET['placowka']."&akcja=usun'>[usuń z bazy]</a>";
      }
     else
      {
       print "wybierz ze środkowej kolumny jednostkę, której szczegóły chcesz zobaczyć";
       print "<br/><br/>";
       print "po wyświetleniu szczegółów będzie można zobaczyć jakie opcje są dostępne.";
      }
     print "</span>";
    }
   else if ($_GET['przychodnia'] == "dodaj")
    {// formularz dodawania nowej przychodni
?>
<form method="post" action="?przychodnia=dodaj">
 <table>
  <tr>
    <td colspan="2" class="top">Formularz nowej przychodni</td>
  </tr>
  <tr>
    <td class="nazwy">nazwa:</td>
    <td><input type="text" name="nazwa" /></td>
  </tr>
  <tr>
    <td class="nazwy">miasto:</td>
    <td><input type="text" name="miasto" /></td>
  </tr>
  <tr>
    <td class="nazwy">ulica:</td>
    <td><input type="text" name="ulica" /></td>
  </tr>
  <tr>
    <td class="nazwy">nr telefonu:</td>
    <td><input type="text" name="telefon" /></td>
  </tr>
  <tr>
    <td colspan="2" class="button">
      <input type="reset" value="wyczyść">
      <input type="submit" value="dodaj przychodnię">
    </td>
  </tr>
 </table>
</form>

<?php

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
else {
  print "<p>wybierz akcję z menu</p>";
}
?>
</div>

</html>
