<?php
session_start();

require_once "baza.php";
$polaczenie = @new mysqli($host, $uzytkownik_bd, $haslo_bd, $bd);
$polaczenie->set_charset('utf8mb4');
if ($polaczenie->connect_errno != 0) {
    echo "Error: " . $polaczenie->connect_errno;
} 
else {

$id=$_GET["id"];
$zapytanie = "UPDATE pliki set siec='0' where id='$id'";
$wynik = $polaczenie->query($zapytanie);
$url=$_SERVER['HTTP_REFERER'];
header("Location: $url");

}

?>