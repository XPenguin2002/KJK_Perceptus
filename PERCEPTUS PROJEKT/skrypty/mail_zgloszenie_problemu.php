<?php

require_once "baza.php";
$polaczenie = @new mysqli($host, $uzytkownik_bd, $haslo_bd, $bd);
$polaczenie->set_charset('utf8mb4');
if ($polaczenie->connect_errno != 0) {
    echo "Error: " . $polaczenie->connect_errno;
} 
else {
    $ip2 = $_SERVER["REMOTE_ADDR"];
    $nieudane = "SELECT COUNT(*) FROM `ip2` WHERE `adres_ip` LIKE '$ip2' AND `timestamp` > (now() - interval 10 minute)";
    $wynik_nl = @$polaczenie->query($nieudane);
    $ilosc_nl = mysqli_fetch_array($wynik_nl, MYSQLI_NUM);
    if ($ilosc_nl[0] > 0) {
        header("Location:../formularz_problemy.php?blad=pil");
        $polaczenie->close();
        exit();
    }
    else{
        $subject = $_POST["imie"]." ".$_POST["nazwisko"]." ".$_POST["email"];
        $message = $_POST["wiadomosc"];
        $header = "Pomoc techniczna";
        $do = "pomoc@kjkenterprise.pl";
        $od = "-fno-reply";

        if(mail($do,$subject,$message,$header,$od)){
            $zapis = "INSERT INTO `ip2` (`adres_ip` ,`timestamp`)VALUES ('$ip2',CURRENT_TIMESTAMP)";
            $polaczenie->query($zapis);
            header("location:../formularz_problemy.php?pow=n1");
            $polaczenie->close();
            exit();
        }
        else {
            header("location:../formularz_problemy.php?blad=nl");
            $polaczenie->close();
            exit();
        }
    }
}

?>