<?php
session_start();
require_once "baza.php";
$polaczenie = @new mysqli($host, $uzytkownik_bd, $haslo_bd, $bd);
$polaczenie->set_charset('utf8mb4');
if ($polaczenie->connect_errno != 0) {
    echo "Error: " . $polaczenie->connect_errno;
} 
else {
    $email=$_SESSION['email'];
    $zapytanie1 = "SELECT autoryzacja from uzytkownicy where email = '$email'";
    $wynik1 = $polaczenie->query($zapytanie1);
    if($wynik1->num_rows > 0){
        while($row = $wynik1->fetch_assoc()){
            if($row["autoryzacja"]==2){
                $kod=$_POST['kod'];
                require '../vendor/autoload.php';
                $g = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();
                $zapytanie = "SELECT secret from uzytkownicy where email = '$email'";
                $wynik = $polaczenie->query($zapytanie);
                if($wynik->num_rows > 0){
                    while($row = $wynik->fetch_assoc()){
                        $sekret1=$row["secret"];
                        $sekret=$g->getCode($sekret1);
                        if($sekret==$kod){
                            $_SESSION['zalogowany'] = true;
                            require_once "sprawdzanie_typu.php";
                            $wynik->free_result();
                        }
                        else{
                            header('Location: ../index.php?blad=n3');
                        }
                    }
                }
                else{
                    header('Location: ../index.php?blad=n2');
                }
            }
            elseif($row["autoryzacja"]==1){
                $kod2=$_POST['kod'];
                $zapytanie = "SELECT kod from uzytkownicy where email = '$email'";
                $wynik = $polaczenie->query($zapytanie);
                if($wynik->num_rows > 0){
                    while($row = $wynik->fetch_assoc()){
                        $kod1=$row["kod"];
                        if($kod1==$kod2){
                            $emailkod = "UPDATE uzytkownicy set kod=NULL where email='$email'";
                            $polaczenie->query($emailkod);
                            $_SESSION['zalogowany'] = true;
                            require_once "sprawdzanie_typu.php";
                            $wynik->free_result();
                        }
                        else{
                            header('Location: ../index.php?blad=n3');
                        }
                    }
                }
            }
            else{
                header('Location: ../index.php?blad=n2');
            }
        }
    }
}
?>