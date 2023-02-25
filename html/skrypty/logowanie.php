<?php
session_start();
//sprawdza czy wypelnino formularz
if(!isset($_POST['email'])&&!isset($_POST['haslo'])){
    header('Location: ../index.php?blad=n1');
}
else {
    //laczenie z baza
    require_once "baza.php";
    $polaczenie = @new mysqli($host, $uzytkownik_bd, $haslo_bd, $bd);
    $polaczenie->set_charset('utf8mb4');
    if ($polaczenie->connect_errno != 0) {
        echo "Error: " . $polaczenie->connect_errno;
    } 
    else {
        //sprawdz nieudane logowania
        $ip = $_SERVER["REMOTE_ADDR"];
        $nieudane_logowania = "SELECT COUNT(*) FROM `ip` WHERE `adres_ip` LIKE '$ip' AND `timestamp` > (now() - interval 10 minute)";
        $wynik_nl = @$polaczenie->query($nieudane_logowania);
        $ilosc_nl = mysqli_fetch_array($wynik_nl, MYSQLI_NUM);
        if ($ilosc_nl[0] > 3) {
            header("Location:../index.php?blad=pil");
            $polaczenie->close();
            exit();
        }
        //wyszukiwanie czy dla podanych danych istnieje user
        if(empty($_POST['email']) || empty($_POST['haslo'])){
            goto nieudane_logowanie;
        } 
        else {
            $email = $_POST['email'];
            $haslo = $_POST['haslo'];
            $haszowane_haslo = hash("sha512", $haslo);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                goto nieudane_logowanie;
            }
            $zapytanie = $polaczenie->prepare('SELECT * FROM uzytkownicy WHERE email= ? AND haslo= ?');
            @$zapytanie->bind_param('ss', $email, $haszowane_haslo);
            $zapytanie->execute();
            $wynik = $zapytanie->get_result();
            $ilosc_wierszy = $wynik->num_rows;
            if ($ilosc_wierszy == 0) {
                nieudane_logowanie:
                $zapisanie_nieudanego_logowania = "INSERT INTO `ip` (`adres_ip` ,`timestamp`)VALUES ('$ip',CURRENT_TIMESTAMP)";
                $polaczenie->query($zapisanie_nieudanego_logowania);
                header('Location: ../index.php?blad=n1');
            } 
            else {
            //  
                $uzytkownik = $wynik->fetch_object();
                $_SESSION['email'] = $uzytkownik->email;
                $_SESSION['typ_uzytkownika'] = $uzytkownik->typ;
                $zapytanie1 = "SELECT autoryzacja from uzytkownicy where email = '$email'";
                $wynik1 = $polaczenie->query($zapytanie1);
                if($wynik1->num_rows > 0){
                    while($row = $wynik1->fetch_assoc()) {
                        if($row["autoryzacja"]==2){
                            header('Location: ../index.php?kod2');
                        }
                        elseif($row["autoryzacja"]==1){
                            $ekod=rand(100000,999999);
                            $od = "-fno-reply";
                            $subject = "kod do logowania";
                            $header = "MIME-Version: 1.0" . "\r\n"; 
                            $header .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                            mail($email,$subject,$ekod,$header,$od);
                            $emailkod = "UPDATE uzytkownicy set kod='$ekod' where email='$email'";
                            $polaczenie->query($emailkod);
                            header('Location: ../index.php?kod1');
                        }
                        else{
                            $_SESSION['zalogowany'] = true;
                            require_once "sprawdzanie_typu.php";
                            $wynik->free_result();
                        }
                    }
                }
                else{
                    header('Location: ../index.php?blad=n2');
                }
            }
            //
        }
    }
    $polaczenie->close();
}
?>