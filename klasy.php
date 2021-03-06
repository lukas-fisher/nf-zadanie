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
  echo "Nr kontaktowy: <b>".$this->numer_kontaktowy."</b><br/><br/>";
  echo "Aktualnie zapisani pacjenci:<br/>";
  foreach ($this->lista_pacjentow as $klucze => $wartosci)
   {
     $_SESSION["PACJENCI"][$wartosci]->drukujNazwisko();
     echo " (";
     $_SESSION["PACJENCI"][$wartosci]->drukujPesel();
     echo ") ";
     echo " <a href='?pacjent=lista&kartoteka=".$wartosci."'>[info]</a><br/>";
   }
 }

 public function drukujNazwe(){
   echo $this->nazwa;
 }

 public function podajNazwe($nazwa){
   if ($this->nazwa == $nazwa)
    {
      return $nazwa;
    }
 }

 public function dodajPacjenta($pacjent){
  $this->lista_pacjentow[] = $pacjent;
 }

 public function usunPacjenta($pacjent){
  foreach ($this->lista_pacjentow as $klucze => $wartosci)
   {
    if ($wartosci === $pacjent)
     {
       unset($this->lista_pacjentow[$klucze]);
       echo "<h1>usuwam ".$wartosci." | podano ".$pacjent."</h1>";
     }
   }
 }

 public function __toString(){
   return "<b>".$this->nazwa."</b> (".$this->miasto.") ";
 }
}

class Pacjent {
 public $nazwisko;
 public $imie;
 public $pesel;
 public $choroby = [];
 public $przychodnia;

 public function __construct($nazwisko,$imie,$pesel){
   $this->nazwisko = $nazwisko;
   $this->imie = $imie;
   $this->pesel = $pesel;
   $this->przychodnia = "BRAK";
 }

 public function bezPrzychodni($klucz){
  $zobacz =  $this->przychodnia;
  if ($zobacz === "BRAK")
   {
    echo "<b>".$this->nazwisko."</b> (".$this->pesel.") <a href='?przychodnia=lista&dopisz=".$klucz."'>[dopisz]</a><br/>";
   }
 }

 public function drukujNazwisko(){
  echo $this->nazwisko;
 }

 public function drukujPesel(){
  echo $this->pesel;
 }

 public function zobaczPrzychodnie(){
  return $this->przychodnia;
 }

 public function ustawPrzychodnie($przychodnia, $nazwa){
   if (strlen($przychodnia) == 0)
    {
     echo "błąd, nie mozna było przypisać ".$nazwa."<br/>";
    }
   else
    {
     $this->przychodnia = $przychodnia;
     echo "<b>".$this->nazwisko."</b> (".$this->pesel.") wpisany do <a href='?przychodnia=lista&placowka=".$this->przychodnia."'>".$nazwa."</a><br/>";
    }

 }

 public function utworzenie(){
   echo "<br/>Gratuluję!<br/>";
   echo "Pacjent <b>".$this->nazwisko." ".$this->imie."</b> ";
   echo "(".$this->pesel.") ma stworzoną nową kartotekę.";
   echo "<br/><br/>";
 }

 public function szczegoly() {
   echo "PESEL: <b>".$this->pesel."</b><br/>";
   echo "Nazwisko, Imię:<br/>";
   echo "<b>".$this->nazwisko.", ".$this->imie."</b><br/>";

   if ($this->przychodnia === "BRAK")
    {
      echo "ten pacjent nie ma jeszcze wybranej przychodni";
    }
   else
    {
     echo "przychodnia: <a href='?przychodnia=lista&placowka=".$this->przychodnia."'>[";
      $nazwa = $_SESSION["PRZYCHODNIE"][$this->przychodnia]->drukujNazwe();
     echo "]</a>";
    }
   echo "<br/>";

   if (count($this->choroby) == 0)
    {
      echo "Ten pacjent nie ma jeszcze historii chorobowej";
    }
   else
    {
      echo "Przebyte choroby:<br/>";
     foreach ($this->choroby as $klucze => $wartosci)
      {
       echo $_SESSION["CHOROBY"][$wartosci];
      }
    }
   echo "<br/>";
 }

 public function zapiszChorobe($kod){
   $this->choroby[] = $kod;
 }

 public function __toString(){
   return "<b>".$this->nazwisko."</b> (".$this->pesel.")";
 }
}

class Choroba {
 public $icd10;
 public $nazwa;

 public function __construct($kod, $nazwa){
   $this->icd10 = $kod;
   $this->nazwa = $nazwa;
 }

 public function __toString(){
   return "<b>[".$this->icd10."]</b> - ".$this->nazwa."<br/>";
 }
}


?>
