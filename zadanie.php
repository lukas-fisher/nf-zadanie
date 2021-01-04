<?php


 class Nfz {//klasa powinna być abstrakcyjna?
  public $oddzial_nfz;

 }

class Przychodnia {
  public $nazwa;
  public $miasto;
  public $ulica;
  public $numer_kontaktowy;
  public $lista_pacjentow;

  public function __construct($nazwa, $miasto, $ulica, $numer_kontaktowy) {
    $this->nazwa = $nazwa;
    $this->miasto = $miasto;
    $this->ulica = $ulica;
    $this->numer_kontaktowy = $numer_kontaktowy;
    $this->lista_pacjentow = "BRAK";
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

class Pacjent {
  public $nazwisko;
  public $imie;
  public $pesel;
  public $choroby;
  public $przychodnia;

  public function __construct($nazwisko,$imie,$pesel){
    $this->nazwisko = $nazwisko;
    $this->imie = $imie;
    $this->pesel = $pesel;
    $this->przychodnia = "BRAK";
    $this->choroby = "BRAK";
  }

 public function bezPrzychodni($klucz){
   if ($this->przychodnia == "BRAK")
    {
      echo "<b>".$this->nazwisko."</b> (".$this->pesel.") <a href='?przychodnia=lista&dopisz=".$klucz."'>[dopisz]</a><br/>";
    }
 }

public function podajPrzychodnie(){
  if ($this->przychodnia == "BRAK")
   {
     return FALSE;
   }
  else {
    print $this->przychodnia;
  }
}

 public function drukujNazwisko(){
   echo $this->nazwisko;
 }

 public function drukujPesel(){
   echo $this->pesel;
 }

  public function ustawPrzychodnie($przychodnia){
    $this->przychodnia = $przychodnia;
    echo "<b>".$this->nazwisko."</b> (".$this->pesel.") wpisany do <b>".$this->przychodnia."</b>";
  }

  public function utworzenie(){
    echo "<br/>Gratuluję!<br/>";
    echo "Pacjent <b>".$this->nazwisko." ".$this->imie."</b> ";
    echo "(".$this->pesel.") ma założoną nową kwrtotekę.";
    echo "<br/><br/>";
  }

  public function szczegoly() {
    echo "<b>".$this->pesel."</b><br/>";
    echo $this->nazwisko." ".$this->imie."<br/>";

    if ($this->przychodnia == "BRAK")
     {
       echo "ten pacjent nie ma jeszcze wybranej przychodni";
     }
    else
     {
      echo "przychodnia: ".$this->przychodnia;
     }
    echo "<br/>";

    if ($this->choroby == "BRAK")
     {
       echo "Ten pacjent nie ma jeszcze historii chorobowej";
     }
    else
     {
      echo "tutaj historia chorobowa".$this->choroby;
     }
    echo "<br/>";
  }

  public function __toString(){
    return "<b>".$this->nazwisko."</b> (".$this->pesel.")";
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

session_start();

if (!isset($_SESSION["NFZ"]))
 { // ustawiam ręcznie oddział nfz dla późniejszej klasy abstrakcyjnej, aby móc skalować.
   $_SESSION["NFZ"] = "15";
 }

 if (!isset($_SESSION["PRZYCHODNIE"]))
  {//tworzę bazę dla przychodni.
   $_SESSION["PRZYCHODNIE"] = [];
  }
 if (!isset($_SESSION["PACJENCI"]))
  {//dla pacjentów.
   $_SESSION["PACJENCI"] = [];
  }
 if (!isset($_SESSION["CHOROBY"]))
  {//dla chorób.
   $_SESSION["CHOROBY"] = [];
  }

?>

<html  lang='pl'>
<head>
  <title>system NFZ</title>
  <meta charset='UTF-8' />
  <link rel='stylesheet' href='style.css'>
</head>

<div>
 <p>NFZ
  <ul>Przychodnie
    <li><a href='?przychodnia=lista'>pokaż listę</a></li>
    <li><a href='?przychodnia=dodaj'>dodaj przychodnię</a></li>
    <li><a href='?przychodnia=przypisz'>przypisz pacjenta</a></li>
  </ul>
  <ul>Pacjenci
    <li><a href='?pacjent=lista'>pokaż listę</a></li>
    <li><a href='?pacjent=dodaj'>dodaj pacjenta</a></li>
  </ul>
  <ul>developer:
    <li><a href='sesja.php'>zobacz co kryją sesje</a></li>
    <li><a href='zadanie.php'>widok domyślny</a></li>
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
     {
      // najpierw sprawdzam czy jest jakaś przychodnia do usunięcia
       if (isset($_GET['akcja']))
        {
          if ($_GET['akcja'] == "usun")
           {
            unset ($_SESSION["PRZYCHODNIE"][$_GET['placowka']]);
            unset ($_GET['placowka']);
           }
        }

      // tutaj drukuję nazwy z sesji (poźniej bazy danych)
       $liczba_przychodni = 0;
       print "<span class='lista'>";
       foreach ($_SESSION["PRZYCHODNIE"] as $klucze => $wartosci) {
         print "- ".$wartosci." <a href='?przychodnia=lista&placowka=".$klucze."'>[szczegóły]</a><br/>";
         $liczba_przychodni++;
       }
       print "<br/></span>";

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
     {//sprawdza czy jest przychodnia do zmiany
       if (isset($_POST['id']) AND isset($_GET['dopisz']))
        {
          print "<span class='info'>";
          $_SESSION["PACJENCI"][$_POST['id']]->ustawPrzychodnie($_POST['przychodnia']);
          print "</span><br/>";

        }

       // drukuję listę pacjentów z sesji - tylko bez przychodni

     print "<span class='lista'>";
     $brak_pacjentow = 0;
     foreach ($_SESSION["PACJENCI"] as $klucze => $wartosci) {
       $testowanie = $_SESSION["PACJENCI"][$klucze]->bezPrzychodni($klucze);
       if ($testowanie != FALSE)
        {
          print $testowanie;
          $brak_pacjentow++;
        }
     }
     print "<br/></span>";
     if ($brak_pacjentow == 0)
      {
        print "<span class='info'>wszystkie kartoteki pacjentów z bazy mają przypisaną przychodnię</span>";
      }
     }
  }
else if (isset($_GET['pacjent']))
 {
   print "<p>MENU PACJENTA</p>";

   if ($_GET['pacjent'] == "lista")
    {
     // najpierw sprawdzam czy jest jakaś kartoteka do usunięcia
     if (isset($_GET['akcja']))
      {
        if ($_GET['akcja'] == "usun")
         {
          unset ($_SESSION["PACJENCI"][$_GET['kartoteka']]);
          unset ($_GET['kartoteka']);
         }
      }

      // drukuję listę pacjentów z sesji
      $liczba_pacjentow = 0;
      print "<span class='lista'>";
      foreach ($_SESSION["PACJENCI"] as $klucze => $wartosci) {
        print "- ".$wartosci." <a href='?pacjent=lista&kartoteka=".$klucze."'>[info]</a><br/>";
        $liczba_pacjentow++;
      }
      print "<br/></span>";

      //sprawdzam ilość wydrukowanych pacjentówarning
      print "<span class='info'>";
      if ($liczba_pacjentow == 0)
       {
         print "najpierw dodaj pacjentów do bazy";
       }
      else
       {
        print "liczba kartotek w bazie: ".$liczba_pacjentow;
       }
      print "</span>";

    }
   else if ($_GET['pacjent'] == "dodaj")
    {
      print "<p>dodaj pacjenta</p>";
      print "<span class='info'>samo stworzenie pacjenta nie przypisuje go do żadnej przychodni jaka jest utworzona.<br/>Aby przypisać utworzonego pacjenta do konkretnej przychodni skorzystaj z opcji <a href='?przychodnia=przypisz'>Przychodnie->[przypisz pacjenta]</a> po jego utworzeniu<br/>";

      if (isset($_POST['pesel']))
       {
         $pacjent = new Pacjent($_POST['nazwisko'], $_POST['imie'], $_POST['pesel']);
         $pacjent->utworzenie();
         $_SESSION["PACJENCI"][] = $pacjent;
       }
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
     $zerowa = 0;

     print "<span class='info'>";
     if (isset($_GET['placowka']))
      {
        $_SESSION["PRZYCHODNIE"][$_GET['placowka']]->szczegoly();
        print "<a href='?przychodnia=lista&placowka=".$_GET['placowka']."&akcja=usun'>[usuń z bazy]</a>";
        $zerowa++;
      }

     if (isset($_GET['dopisz']))
      {
?>

<form method="post" action="?przychodnia=przypisz&dopisz=<?php print $_GET['dopisz'];?>">
 <table>
  <tr>
    <td colspan="2" class="top">Formularz dopisania pacenta</td>
  </tr>
  <tr>
    <td class="nazwy">nazwisko:</td>
    <td><input type="text" name="nazwisko" value="<?php $_SESSION["PACJENCI"][$_GET['dopisz']]->drukujNazwisko();?>" /></td>
  </tr>
  <tr>
    <td class="nazwy">pesel:</td>
    <td><input type="text" name="pesel" value="<?php $_SESSION["PACJENCI"][$_GET['dopisz']]->drukujPesel();?>" /></td>
  </tr>
  <tr>
    <td class="nazwy">id kartoteki:</td>
    <td><input type="text" name="id" value=<?php print $_GET['dopisz']; ?> /></td>
  </tr>
  <tr>
    <td class="nazwy">Przychodnia:</td>
    <td><input type="text" name="przychodnia" value="wpisz nazwę" autofocus /></td>
  </tr>
  <tr>
    <td colspan="2" class="button">
      <input type="reset" value="wyczyść">
      <input type="submit" value="przypisz kartotekę">
    </td>
  </tr>
 </table>
</form>

<?php

       $zerowa++;
      }


     if ($zerowa == 0)
      {
       print "wybierz ze środkowej kolumny jednostkę, której szczegóły chcesz zobaczyć";
       print "<br/><br/>";
       print "po wyświetleniu szczegółów będzie można zobaczyć jakie opcje są dostępne.";
      }
     print "</span>";
    }

   if ($_GET['przychodnia'] == "dodaj")
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
      print "<span class='info'>wybierz z listy obok kartotekę dla której chcesz przypisać przychodnię. Wyświetlają się tylko te kartoteki, które nie mają zdefiniowanego wyboru.<br/><br/>Jeśli chesz zmienić przypisaną przychodnię do istniejącej kartoteki (której nie widać na tej liście), należy przejść do <a href='?pacjent=lista'>Pacjenci->pokaż listę</a> i wyświetlenie zakładki <b>[info]</b> dla konkretnej kartoteki</span>";
    }
 }
else if (isset($_GET['pacjent']))
{
  if ($_GET['pacjent'] == "lista")
   {
     print "<span class='info'>";
     if (isset($_GET['kartoteka']))
      {
        $_SESSION["PACJENCI"][$_GET['kartoteka']]->szczegoly();
        print "<a href='?pacjent=lista&kartoteka=".$_GET['kartoteka']."&akcja=usun'>[usuń z bazy]</a> ";
        print "<a href='?przychodnia=lista&dopisz=".$_GET['kartoteka']."'>[wybierz przychodnię]</a>";
      }
     else
      {
       print "wybierz ze środkowej kolumny kartotekę pacjenta, której szczegóły chcesz zobaczyć";
       print "<br/><br/>";
       print "po wyświetleniu szczegółów będzie można zobaczyć jakie opcje są dla niej dostępne z tego poziomu aplikacji.";
      }
     print "</span>";
   }
  else if ($_GET['pacjent'] == "dodaj")
   {
     {// formularz dodawania nowego pacjenta
 ?>
 <form method="post" action="?pacjent=dodaj">
  <table>
   <tr>
     <td colspan="2" class="top">Formularz nowego pacjenta</td>
   </tr>
   <tr>
     <td class="nazwy">nazwisko:</td>
     <td><input type="text" name="nazwisko" /></td>
   </tr>
   <tr>
     <td class="nazwy">imię:</td>
     <td><input type="text" name="imie" /></td>
   </tr>
   <tr>
     <td class="nazwy">pesel:</td>
     <td><input type="text" name="pesel" /></td>
   </tr>
   <tr>
     <td colspan="2" class="button">
       <input type="reset" value="wyczyść">
       <input type="submit" value="utwórz kartotekę pacjenta">
     </td>
   </tr>
  </table>
 </form>

 <?php
   }
}
else {
  print "<p>wybierz akcję z menu</p>";
}
}
?>
</div>

</html>
