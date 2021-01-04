<?php
function PoszukajIdPoNazwiePrzychodni($nazwa){
  foreach ($_SESSION["PRZYCHODNIE"] as $klucze => $wartosci) {
    if ($_SESSION["PRZYCHODNIE"][$klucze]->podajNazwe($nazwa) == TRUE)
     {
       return $klucze;
     }
  }
}
 ?>
