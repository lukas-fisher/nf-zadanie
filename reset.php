<?php
session_start();

unset($_SESSION["PRZYCHODNIE"]);

unset($_SESSION["PACJENCI"]);

unset($_SESSION["CHOROBY"]);

unset($_SESSION["NFZ"]);

session_destroy();

print "<a href='sesja.php'>zobacz co kryją sesje</a><br/>";
print "<a href='zadanie.php'>zadanie</a>";
 ?>
