<?php
session_start();

require_once "baza.php";
$polaczenie = @new mysqli($host, $uzytkownik_bd, $haslo_bd, $bd);
$polaczenie->set_charset('utf8mb4');
if ($polaczenie->connect_errno != 0) {
    echo "Error: " . $polaczenie->connect_errno;
} 
else {
$email=$_SESSION["email"];
$id=$_GET["id"];
$zapytanie = "DELETE FROM ulubione WHERE email='$email' and id=$id";
$wynik = $polaczenie->query($zapytanie);
$url=$_SERVER['HTTP_REFERER'];
header("Location: $url");

}

?>