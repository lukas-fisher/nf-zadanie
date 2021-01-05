<?php

//załączam klasy i funkcje - w odrębnych plikach dla klarowności kodu i łatwiejszej modyfikacji.
include "klasy.php";
include "funkcje.php";

session_start();

// #NFZ - oddział
if (!isset($_SESSION["NFZ"]))
 {
  $_SESSION["NFZ"] = "15";
 }
// #baza dla przychodni.
 if (!isset($_SESSION["PRZYCHODNIE"]))
  {
   $_SESSION["PRZYCHODNIE"] = [];
  }
// #baza dla pacjentów.
 if (!isset($_SESSION["PACJENCI"]))
  {
   $_SESSION["PACJENCI"] = [];
  }
// #baza dla chorób.
 if (!isset($_SESSION["CHOROBY"]))
  {
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
<?php
// #kolumna1 #menu
?>
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
// #kolumna2 #przychodnia
if (isset($_GET['przychodnia']))
 {
  print "<p>MENU PRZYCHODNI</p>";
  // #kolumna2 #przychodnia #lista
  if ($_GET['przychodnia'] == "lista")
   {
    // #kolumna2 #przychodnia #lista #usuwam
    if (isset($_GET['akcja']))
     {
      if ($_GET['akcja'] == "usun")
       {
        unset ($_SESSION["PRZYCHODNIE"][$_GET['placowka']]);
        unset ($_GET['placowka']);
        // $_GET kasuję ze względu na warunkowanie działania w #kolumna3
       }
     }


    $liczba_przychodni = 0;
    print "<span class='lista'>";
    // #kolumna2 #przychodnia #lista #drukuję tutaj drukuję nazwy z sesji (poźniej bazy danych)
    foreach ($_SESSION["PRZYCHODNIE"] as $klucze => $wartosci)
     {
      print "- ".$wartosci." <a href='?przychodnia=lista&placowka=".$klucze."'>[szczegóły]</a><br/>";
      $liczba_przychodni++;
     }
    print "<br/></span>";

    // #kolumna2 #przychodnia #lista #sumakontrolna sprawdza ilość wydrukowanych przychodni
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
  // #kolumna2 #przychodnia #dodaj
  else if ($_GET['przychodnia'] == "dodaj")
   {
    print "<span class='info'><b>Użytkowniku</b>,<br/>w aktualnej wersji programu nie będzie można wyedytować danych przychodni, trzeba je będzie usunąć i dodać ponownie.<br/><br/>W tym celu udaj się do sekcji <a href='?przychodnia=lista'>[Przychodnie] -> [Pokaż listę]</a>. Dalesze instrucje znajdziesz we wskazanej zakładce.<br/><br/>";

    // #kolumna2 #przychodnia #obiekt
    if (isset($_POST['nazwa']))
     {
      $przychodnia = new Przychodnia($_POST['nazwa'], $_POST['miasto'], $_POST['ulica'], $_POST['telefon']);
      $przychodnia->utworzenie();
      $_SESSION["PRZYCHODNIE"][] = $przychodnia;
     }
    print "</span>";
   }
  // #kolumna2 #przychodnia #przypisanie
  else if ($_GET['przychodnia'] == "przypisz")
   {
    //sprawdza czy jest przychodnia do zmiany dla pacjenta
    if (isset($_POST['id']) AND isset($_GET['dopisz']))
     {
       // #kolumna2 #przychodnia #zmiana
       $poprzednia_przychodnia = $_SESSION["PACJENCI"][$_POST['id']]->zobaczPrzychodnie();
       if ($poprzednia_przychodnia != "BRAK")
        {
         $_SESSION["PRZYCHODNIE"][$poprzednia_przychodnia]->usunPacjenta($_GET['dopisz']);
        }
       else
        {
         print "<h1>poprzednia: ".$poprzednia_przychodnia." | podano ".$_GET['dopisz']."</h1>";
        }
      //korzystam z funkcji (chociaż robię to tylko raz) - dla czytelności kodu
      $sprawdz = PoszukajIdPoNazwiePrzychodni($_POST['przychodnia']);
      print "<span class='info'>";
      $_SESSION["PACJENCI"][$_POST['id']]->ustawPrzychodnie($sprawdz, $_POST['przychodnia']);
      $_SESSION["PRZYCHODNIE"][$sprawdz]->dodajPacjenta($_GET['dopisz']);
      print "</span><br/>";
     }
   // drukuję listę pacjentów z sesji - tylko bez przychodni
   print "<span class='lista'>";
   foreach ($_SESSION["PACJENCI"] as $klucze => $wartosci)
    {
     //drukuje tylko jeśli przychodnia nie została przypisana
     $testowanie = $_SESSION["PACJENCI"][$klucze]->bezPrzychodni($klucze);
    }
   print "<br/></span>";
   print "<span class='info'>brak kartotek do przypisania</span>";
  }
 }
// #kolumna2 #pacjent
else if (isset($_GET['pacjent']))
  {
   print "<p>MENU PACJENTA</p>";
   // #kolumna2 #pacjent #lista
   if ($_GET['pacjent'] == "lista")
    {
     // #kolumna2 #pacjent #lista #usun
     if (isset($_GET['akcja']))
      {
       if ($_GET['akcja'] == "usun")
        {
         unset ($_SESSION["PACJENCI"][$_GET['kartoteka']]);
         unset ($_GET['kartoteka']);
        }
      }

     // #kolumna2 #pacjent #lista #drukuj listę pacjentów z sesji
     $liczba_pacjentow = 0;
     print "<span class='lista'>";
     foreach ($_SESSION["PACJENCI"] as $klucze => $wartosci)
      {
       print "- ".$wartosci." <a href='?pacjent=lista&kartoteka=".$klucze."'>[info]</a><br/>";
       $liczba_pacjentow++;
      }
     print "<br/></span>";

     // #kolumna2 #pacjent #lista #suma - sprawdzam ilość wydrukowanych pacjentów
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
   // #kolumna2 #pacjent #dodaj
   else if ($_GET['pacjent'] == "dodaj")
    {
     // #kolumna2 #pacjent #dodaj - instrukcja postępowania
     print "<span class='info'><b>Dodaj Karotekę</b><br/><br/>Samo stworzenie pacjenta nie przypisuje go do żadnej przychodni jaka jest utworzona.<br/>Aby to zrobić skorzystaj z opcji <a href='?przychodnia=przypisz'>[Przychodnie] -> [przypisz pacjenta]</a> po jego utworzeniu (tam zobaczysz tylko tych pacjentów, którzy nie są zadeklarowani).<br/>";

     // #kolumna2 #pacjent #dodaj #obiekt
     if (isset($_POST['pesel']))
      {
       $pacjent = new Pacjent($_POST['nazwisko'], $_POST['imie'], $_POST['pesel']);
       $pacjent->utworzenie();//potwierdzenie
       $_SESSION["PACJENCI"][] = $pacjent;
      }
    }
  }
// #kolumna2 - komunikat powitalny
else
 {
  print "<p>Witaj</p>";
  print "<span class='info'>W tym miejscu będą się pojawiały dane oraz instrucje. Aby zacząć korzystać z systemu wybierz opcję z MENU po lewej.<br/><br/>Enjoy.</span>";
 }
?>
</div>
<div>
<?php
// #kolumna3 #przychodnia
if (isset($_GET['przychodnia']))
 {// #kolumna3 dla przychodni
   if ($_GET['przychodnia'] == "lista")
    {// warianty dla listy przychodni
     $zerowa = 0;

     print "<span class='info'>";
     if (isset($_GET['placowka']))
      {// wyświetlamy konkretną placówkę jaką wybierzemy
        print "<a href='?przychodnia=lista&placowka=".$_GET['placowka']."&akcja=usun'>[usuń placówkę z bazy]</a><br/>";
        $_SESSION["PRZYCHODNIE"][$_GET['placowka']]->szczegoly();
        $zerowa++;
      }

     if (isset($_GET['dopisz']))
      {//formularz obsługujący dopisanie pacjenta do przychodni
      //jest w tym miejscu dla wygody, aby można było zobaczyć jakie przychodnie są w bazie
       include "form-dopisz-pacjenta.html";
       $zerowa++;
      }

     if ($zerowa == 0)
      {//jeśli nie ma zdefiniowanej czynności dla listy w tej kolumnie wyświetlam instrukcję
       print "Wybierz ze środkowej kolumny przychodnię, na temat której chcesz zobaczyć wszystkie informacje poprzez wybranie <b>[szczegóły]</b>.";
       print "<br/><br/>";
       print "Po wyświetleniu panelu będzie można zobaczyć jakie opcje są dostępne oraz przeglądać kartoteki pacjentów.";
      }
     print "</span>";
    }

   if ($_GET['przychodnia'] == "dodaj")
    {// formularz dodawania nowej przychodni
      include "form-nowa-przychodnia.html";
    }
   else if ($_GET['przychodnia'] == "przypisz")
    {//instrukcja dla przypisywania pacjentów do przychodni
      print "<span class='info'>wybierz z listy obok kartotekę dla której chcesz przypisać przychodnię poprzez <b>[dopisz]</b>. Wyświetlają się tylko te kartoteki, które nie mają wybranej przychodni.<br/><br/>Jeśli chesz zmienić przypisaną przychodnię do istniejącej kartoteki pacjenta (której nie widać na tej liście), należy przejść do <a href='?pacjent=lista'>[Pacjenci] -> [pokaż listę]</a> i wyświetlenie zakładki <b>[info]</b> dla konkretnej kartoteki</span>";
    }
 }
// #kolumna3 #pacjent
else if (isset($_GET['pacjent']))
 {// #kolumna3 dla pacjenta
  if ($_GET['pacjent'] == "lista")
   {
     print "<span class='info'>";
     if (isset($_GET['kartoteka']))
      { //pokazuję konkretną kartotekę pacjenta
        $_SESSION["PACJENCI"][$_GET['kartoteka']]->szczegoly();
        print "<a href='?pacjent=lista&kartoteka=".$_GET['kartoteka']."&akcja=usun'>[usuń kartotekę z bazy]</a><br/>";
        print "<a href='?przychodnia=lista&dopisz=".$_GET['kartoteka']."'>[zadeklaruj przychodnię]</a>";
      }
     else
      { // proszę o wybranie kartoteki, aby zobaczyć szczegóły
       print "wybierz ze środkowej kolumny kartotekę pacjenta, której szczegóły chcesz zobaczyć poprzez przycisk <b>[info]</b>";
       print "<br/><br/>";
       print "po wyświetleniu szczegółów będzie można zobaczyć jakie opcje są dla niej dostępne z tego poziomu aplikacji.";
      }
     print "</span>";
   }
  else if ($_GET['pacjent'] == "dodaj")
   {// formularz dodawania nowego pacjenta
     include "form-nowy-pacjent.html";
   }
  else
   {
    print "<p>wybierz akcję z menu</p>";
   }
 }
// #kolumna3 - komunikat powitalny
else
 {//komunikat powitalny
  print "<p>Panel boczny</p>";
  print "<span class='info'>W tym miejscu będą się pojawiały dodatkowe instrucje oraz formularze.<br/><br/></span>";
  }
?>
</div>
</html>
