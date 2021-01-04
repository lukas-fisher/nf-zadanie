<?php


include "klasy.php";
include "funkcje.php";

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
          $sprawdz = PoszukajIdPoNazwiePrzychodni($_POST['przychodnia']);

          print "<span class='info'>";
          $_SESSION["PACJENCI"][$_POST['id']]->ustawPrzychodnie($sprawdz, $_POST['przychodnia']);
          $_SESSION["PRZYCHODNIE"][$sprawdz]->dodajPacjenta($_GET['dopisz']);

          print "</span><br/>";

        }

       // drukuję listę pacjentów z sesji - tylko bez przychodni

     print "<span class='lista'>";
     foreach ($_SESSION["PACJENCI"] as $klucze => $wartosci) {
       $testowanie = $_SESSION["PACJENCI"][$klucze]->bezPrzychodni($klucze);
     }
     print "<br/></span>";

        print "<span class='info'>brak kartotek do przypisania</span>";

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
        print "<a href='?przychodnia=lista&placowka=".$_GET['placowka']."&akcja=usun'>[usuń placówkę z bazy]</a><br/>";
        $_SESSION["PRZYCHODNIE"][$_GET['placowka']]->szczegoly();
        $zerowa++;
      }

     if (isset($_GET['dopisz']))
      {//formularz obsługujący dopisanie pacjenta do przychodni
       include "form-dopisz-pacjenta.html";
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
      include "form-nowa-przychodnia.html";
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
