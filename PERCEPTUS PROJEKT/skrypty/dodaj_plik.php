<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once "baza.php";
$polaczenie = @new mysqli($host, $uzytkownik_bd, $haslo_bd, $bd);
$polaczenie->set_charset('utf8mb4');
if ($polaczenie->connect_errno != 0) {
    echo "Error: " . $polaczenie->connect_errno;
} 
else {
    if (isset($_POST['dodaj_submit'])) {
        //
        $email = $_SESSION['email'];
        $zap_pliki_moje = "SELECT rozmiar FROM pliki where (kosz=2 or kosz=0) and email='$email'";
        if ($result_moje = $polaczenie->query($zap_pliki_moje)) {
            while ($row=$result_moje->fetch_assoc()){
                $rozmiar=$row["rozmiar"];
                $a+=$rozmiar;
            }
        }
        $zap_mb_moje = "SELECT mb FROM uzytkownicy where email='$email'";
        $result_mb_moje = $polaczenie->query($zap_mb_moje);
        $row=$result_mb_moje->fetch_assoc();
        $b=$row["mb"];
        $file = $_FILES['file']['name'];
        $tmpnm = $_FILES['file']['tmp_name'];
        $type = $_FILES['file']['type'];
        $size = $_FILES['file']['size'];
        $dir = "../file/".$file;
        $a+=$size;
        if($b>$a){
            move_uploaded_file($tmpnm, $dir);            
            $query = ("INSERT into pliki (email,plik,typ,rozmiar,siec,kosz,data) VALUES('$email','$file','$type','$size','0','0',now())");
            $result = $polaczenie->query($query);
    
            if($result){
                $url=$_SERVER['HTTP_REFERER'];
                echo "Dodawanie pliku... Za chwilę wrócisz na poprzednią stronę.";
                header("Location: $url?git1");
    
            }else{
                $url=$_SERVER['HTTP_REFERER'];
                echo "BŁĄD. Za chwilę wrócisz na poprzednią stronę.";
                header("Location: $url?blad1");
    
            }
        }
        else{
            $url=$_SERVER['HTTP_REFERER'];
            echo "BŁĄD. Za mało miejsca na dysku. Powrót do poprzedniej strony.";
            header("Location: $url?blad2");
        }
        //
    }
    $polaczenie->close();
}
?>