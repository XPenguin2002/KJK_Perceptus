<?php
session_start();
if(!isset($_SESSION['zalogowany'])||($_SESSION['typ_uzytkownika']=="uzytkownik")){
    header("Location:index.php");
    session_unset();
    exit();
}

require_once "skrypty/baza.php";
    $polaczenie = @new mysqli($host, $uzytkownik_bd, $haslo_bd, $bd);
    $polaczenie->set_charset('utf8mb4');
    if ($polaczenie->connect_errno != 0) {
        echo "Error: " . $polaczenie->connect_errno;
    } 
    else {
        require 'vendor/autoload.php';
        $email = $_SESSION['email'];
        //
        $zapytanie = "SELECT secret from uzytkownicy where email = '$email'";
        $wynik = $polaczenie->query($zapytanie);
        if($wynik->num_rows > 0){
            while($row = $wynik->fetch_assoc()) {
                if($row["secret"]==NULL){
                    $g = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();
                    $secret = $g->generateSecret();
                    $link = \Sonata\GoogleAuthenticator\GoogleQrUrl::generate($email,$secret,'KJKEnterprise');
                    $zapytanie2 = "UPDATE uzytkownicy set secret='$secret' where email='$email'";
                    $wynik2 = $polaczenie->query($zapytanie2);
                }
                else{
                    $g = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();
                    $secret = $row["secret"];
                    $link = \Sonata\GoogleAuthenticator\GoogleQrUrl::generate($email,$secret,'KJKEnterprise');
                }
            }
        }
        else{
            echo "<br><span style='color: red; margin-left: 10px;'>Coś poszło nie tak</span>";
        }

        //
        if(isset($_POST["qr"])){
            $code=$_POST["kod"];

            if ($g->checkCode($secret, $code)) {
                $kon = "UPDATE uzytkownicy set autoryzacja=2 where email='$email'";
                $konn = @$polaczenie->query($kon);
                if($konn){
                    echo "<br><span style='color: green; margin-left: 10px;'>Od teraz przy próbie zalogowania będziesz musiał użyć kodu z aplikacji Google Authentication</span>";
                }
                else{
                    echo "<br><span style='color: red; margin-left: 10px;'>Coś poszło nie tak</span>";
                }
            } else {
                echo "Wprowadzono zły kod";
            }
        }
        if(isset($_POST["password"])){
            $pw = $_POST["password"];
            $email = $_SESSION['email'];
            $kon = "UPDATE uzytkownicy set haslo=SHA2('$pw',512) where email='$email'";
            $konn = @$polaczenie->query($kon);
            if($konn){
                header("Location:test.php");
            }
            else{
                echo "<br><span style='color: red; margin-left: 10px;'>Coś poszło nie tak</span>";
            }
        }
        if(isset($_POST["submit1"])){
            $r1 = $_POST["wybor"];
            $email = $_SESSION['email'];
            if($r1==0){
                $kon = "UPDATE uzytkownicy set autoryzacja=0 where email='$email'";
                $konn = @$polaczenie->query($kon);
                if($konn){
                    echo "<br><span style='color: green; margin-left: 10px;'>Usunięto podwójną weryfikację</span>";
                }
                else{
                    echo "<br><span style='color: red; margin-left: 10px;'>Coś poszło nie tak</span>";
                }
            }
            elseif($r1==1){
                $kon = "UPDATE uzytkownicy set autoryzacja=1 where email='$email'";
                $konn = @$polaczenie->query($kon);
                if($konn){
                    echo "<br><span style='color: green; margin-left: 10px;'>Od teraz przy próbie zalogowania będziesz otrzymywać kod na swój email</span>";
                }
                else{
                    echo "<br><span style='color: red; margin-left: 10px;'>Coś poszło nie tak</span>";
                }
            }
            elseif($r1==2){
                
            }
            else{
                echo "<br><span style='color: red; margin-left: 10px;'>Coś poszło nie tak</span>";
            }
        }
        $zapytanie_kosz = "SELECT kosz from uzytkownicy where email = '$email'";
        $wynik_kosz = $polaczenie->query($zapytanie_kosz);
        $row = $wynik_kosz->fetch_assoc();
        $kosz=$row["kosz"];
        if(isset($_POST["kosz_submit"])){
            $kosz2=$_POST["kosz_ile"];
            $zapytanie_kosz2 = "UPDATE uzytkownicy set kosz='$kosz2' where email='$email'";
            $wynik_kosz2 = $polaczenie->query($zapytanie_kosz2);
            if($wynik_kosz2){
                $zapytanie_id = "SELECT id from uzytkownicy where email = '$email'";
                $wynik_id = $polaczenie->query($zapytanie_id);
                $row = $wynik_id->fetch_assoc();
                $id=$row["id"];
                $zapytanie_kosz3 = "DROP EVENT IF EXISTS usuwanie_$id";
                $wynik_kosz3 = $polaczenie->query($zapytanie_kosz3);
                if($wynik_kosz3){
                    $zapytanie_kosz4 = "CREATE DEFINER=`root`@`localhost` EVENT `usuwanie_$id` ON SCHEDULE EVERY $kosz2 DAY STARTS '2022-12-06 21:53:33' ON COMPLETION NOT PRESERVE ENABLE DO DELETE FROM pliki WHERE email='$email' and kosz='1'";
                    $wynik_kosz4 = $polaczenie->query($zapytanie_kosz4);
                    if($wynik_kosz4){
                        echo "<br><span style='color: green; margin-left: 10px;'>Ustawiono czas usuwania plików z kosza na ".$kosz2." dni</span>";
                    }
                    else{
                        echo "<br><span style='color: red; margin-left: 10px;'>Coś poszło nie tak</span>";
                    }
                }
                else{
                    echo "<br><span style='color: red; margin-left: 10px;'>Coś poszło nie tak</span>";
                }
            }
            else{
                echo "<br><span style='color: red; margin-left: 10px;'>Coś poszło nie tak</span>";
            }
        }
        if(isset($_POST["submit_email2"])){
            $email2 = $_POST["email2"];
            $kodzik = rand(100000,999999);
            $kon2 = "INSERT into dodaj (email,kod) VALUES('$email2',$kodzik)";
            $konn2 = @$polaczenie->query($kon2);
            if($konn2){
                $fullurl = "https://www.kjkenterprise.pl/dodaj.php?kod=$kodzik";
                $htmlContent = "<h1>Zaproszenie</h1> <h3><a href='$fullurl'>Link ważny przez godzinę</a></h3>";
                $header = "MIME-Version: 1.0" . "\r\n"; 
                $header .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $od = "-fno-reply";
                $subject = "zaproszenie";
                mail($email2,$subject,$htmlContent,$header,$od);
                $url="https://www.kjkenterprise.pl/strona_admin.php?uzytkownicy?dod1";
                header("Location: $url");
            }
            else{
                echo "<br><span style='color: red; margin-left: 10px;'>Coś poszło nie tak</span>";
            }
        }
        if(isset($_POST["submit_email3"])){
            $email3 = $_POST["emaill"];
            $imie3=$_POST["imie"];
            $nazwisko3=$_POST["nazwisko"];
            $kon3 = "UPDATE uzytkownicy SET imie='$imie3', nazwisko='$nazwisko3' where email='$email3'";
            $konn3 = @$polaczenie->query($kon3);
            if($konn3){
                $url="https://www.kjkenterprise.pl/strona_admin.php?uzytkownicy?dod2";
                header("Location: $url");
            }
            else{
                echo "<br><span style='color: red; margin-left: 10px;'>Coś poszło nie tak</span>";
            }
        }
        if(isset($_POST["submit_email4"])){
            $email4 = $_POST["emailz3"];
            $miej4=$_POST['miejscez3'];
            $miej41=$miej4*1048576;
            $kon3 = "UPDATE uzytkownicy SET mb=$miej41 where email='$email4'";
            $konn3 = @$polaczenie->query($kon3);
            if($konn3){
                $url="https://www.kjkenterprise.pl/strona_admin.php?uzytkownicy?dod3";
                header("Location: $url");
            }
            else{
                echo "<br><span style='color: red; margin-left: 10px;'>Coś poszło nie tak</span>";
            }
        }
        if(isset($_POST["usun_uzytkownika"])){
            $emaild = $_POST["emaill"];
            $kondd = "DELETE from uzytkownicy where email='$emaild'";
            $konndd = @$polaczenie->query($kondd);
            if($konndd){
                $url="https://www.kjkenterprise.pl/strona_admin.php?uzytkownicy?dod4";
                header("Location: $url");
            }
            else{
                echo "<br><span style='color: red; margin-left: 10px;'>Coś poszło nie tak</span>";
            }
        }
        if(isset($_POST["end_ok"])){
            $id_s=$_POST["idn"];
            $notatki_s = $_POST["notatki"];
            $zglo1 = "UPDATE zgloszenie set notatki='$notatki_s' where id=$id_s";
            $zglo2 = @$polaczenie->query($zglo1);
            if($zglo2){
                $url="https://www.kjkenterprise.pl/strona_admin.php?zgloszenia?zgl1";
                header("Location: $url");
            }
            else{
                echo "<br><span style='color: red; margin-left: 10px;'>Coś poszło nie tak</span>";
            }
        }
        if(isset($_POST["end_x"])){
            $id_s=$_POST["idn"];
            $zglo3 = "UPDATE zgloszenie set zamkniecie=1 where id=$id_s";
            $zglo4 = @$polaczenie->query($zglo3);
            if($zglo4){
                $url="https://www.kjkenterprise.pl/strona_admin.php?zgloszenia?zgl2";
                header("Location: $url");
            }
            else{
                echo "<br><span style='color: red; margin-left: 10px;'>Coś poszło nie tak</span>";
            }
        }
        if(isset($_POST["sesja_zmien"])){
            $sesja_czas=$_POST["sesja_czas"];
            $sesja_zap="UPDATE sesja set ile=$sesja_czas";
            $sesja_wyn=$polaczenie->query($sesja_zap);
            if($sesja_wyn){
                $url="https://www.kjkenterprise.pl/strona_admin.php?dysk?sesja";
                header("Location: $url");
            }
            else{
                echo "<br><span style='color: red; margin-left: 10px;'>Coś poszło nie tak</span>";
            }
        }
        $polaczenie->close();
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Przechowywanie plików | Admin</title>
    <link rel="stylesheet" type="text/css" media="screen" href="css/css.php">
</head>
<body>
<?php
    $fullurl = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    if(strpos($fullurl,"dod1")==true){
        echo "<span style='color:green'>Wysłano zaproszenie</span>";
    }
    if(strpos($fullurl,"dod2")==true){
        echo "<span style='color:green'>Zmieniono dane użytkownika</span>";
    }
    if(strpos($fullurl,"dod3")==true){
        echo "<span style='color:green'>Zmieniono miejsce użytkownika</span>";
    }
    if(strpos($fullurl,"dod4")==true){
        echo "<span style='color:green'>Usunięto użytkownika</span>";
    }
    if(strpos($fullurl,"zgl1")==true){
        echo "<span style='color:green'>Dodano notatke do zgłoszenia</span>";
    }
    if(strpos($fullurl,"zgl2")==true){
        echo "<span style='color:green'>Zamknięto zgłoszenie</span>";
    }
    if(strpos($fullurl,"uzyd")==true){
        $iddd=$_GET["uzyd"];
        require_once "skrypty/baza.php";
        $polaczenie = @new mysqli($host, $uzytkownik_bd, $haslo_bd, $bd);
        $polaczenie->set_charset('utf8mb4');
        if ($polaczenie->connect_errno != 0) {
            echo "Error: " . $polaczenie->connect_errno;
        } 
        else {
        $kond = "SELECT * FROM uzytkownicy where id=$iddd";
        $konnd = $polaczenie->query($kond);
        $rowd=$konnd->fetch_assoc();
        $emailo=$rowd["email"];
        $imieo=$rowd["imie"];
        $nazwiskoo=$rowd["nazwisko"];
        echo '<div id="po5">
                                <div id="pop5">
                                    <button type="button" id="but2" onclick="closePopup5()">X</button>
                                        <div class="login-container">
                                        <h1>Edytuj dane:</h1>
                                        <form method="post">
                                            <input type="text" value="'.$emailo.'" readonly name="emaill"/><br>
                                            <input type="text" value="'.$imieo.'" name="imie"/><br>
                                            <input type="text" value="'.$nazwiskoo.'" name="nazwisko"/><br>
				                            <br><input type="submit" name="submit_email3" value="Zmień dane">
			                            </div>
                                    <input type="submit" id="but" name="usun_uzytkownika" title="usuń użytkownika" value="X">
                                    </form>
                                    </div>
                                </div>
                            ';
        }
    }
    if(strpos($fullurl,"mies")==true){
        $iddd=$_GET["mies"];
        require_once "skrypty/baza.php";
        $polaczenie = @new mysqli($host, $uzytkownik_bd, $haslo_bd, $bd);
        $polaczenie->set_charset('utf8mb4');
        if ($polaczenie->connect_errno != 0) {
            echo "Error: " . $polaczenie->connect_errno;
        } 
        else {
        $kond = "SELECT * FROM uzytkownicy where id=$iddd";
        $konnd = $polaczenie->query($kond);
        $rowd=$konnd->fetch_assoc();
        $emailz3=$rowd["email"];
        $miejscez1=$rowd["mb"];
        $miejscez2=$miejscez1/1048576;
        $miejscez3=round($miejscez2, 2);
        echo '<div id="po5">
                                <div id="pop5">
                                    <button type="button" id="but2" onclick="closePopup5()">X</button>
                                        <div class="login-container">
                                        <h1>Edytuj dane:</h1>
                                        <form method="post">
                                            <input type="text" value="'.$emailz3.'" readonly name="emailz3"/><br>
                                            <input type="number" value='.$miejscez3.' name="miejscez3"/>mb<br>
				                            <br><input type="submit" name="submit_email4" value="Zmień ilość miejsca">
			                            </form>
                                        </div>
                                    </div>
                                </div>
                            ';
        }
    }
    if(strpos($fullurl,"sesja")==true){

        echo "<span style='color:green'>Zmieniono długość sesji użytkownika</span>";
    }
?>
    <div class="element1">
        <h2>Witaj na stronie admina</h2>
        <div class="zajete">
            <p>Zajęte miejsce na dysku:<br>
            <?php
            require_once "skrypty/baza.php";
            $polaczenie = @new mysqli($host, $uzytkownik_bd, $haslo_bd, $bd);
            $polaczenie->set_charset('utf8mb4');
            if ($polaczenie->connect_errno != 0) {
                echo "Error: " . $polaczenie->connect_errno;
            } 
            else {
                $zap_plikii_moje = "SELECT rozmiar FROM pliki;";
                if ($resultt_moje = $polaczenie->query($zap_plikii_moje)) {
                    while ($row=$resultt_moje->fetch_assoc()){
                        $rozmiar=$row["rozmiar"];
                        $a+=$rozmiar;
                    }
                }
                $c=$a/1048576;
                $e=round($c, 2);
                echo $e." mb";
                $polaczenie->close();
            }
            ?>
            </p>
        </div>
        <div class="zajete">
            <p>Ilość plików na dysku:<br>
            <?php
            require_once "skrypty/baza.php";
            $polaczenie = @new mysqli($host, $uzytkownik_bd, $haslo_bd, $bd);
            $polaczenie->set_charset('utf8mb4');
            if ($polaczenie->connect_errno != 0) {
                echo "Error: " . $polaczenie->connect_errno;
            } 
            else {
                $zap_plikii_moje = "SELECT id FROM pliki;";
                if ($resultt_moje = $polaczenie->query($zap_plikii_moje)) {
                    $b=1;
                    while ($row=$resultt_moje->fetch_assoc()){
                        $b+=1;
                    }
                }
                echo $b;
                $polaczenie->close();
            }
            ?>
            </p>
        </div>
        <div class="zajete">
            <p>Liczba użytkowników:<br>
            <?php
            require_once "skrypty/baza.php";
            $polaczenie = @new mysqli($host, $uzytkownik_bd, $haslo_bd, $bd);
            $polaczenie->set_charset('utf8mb4');
            if ($polaczenie->connect_errno != 0) {
                echo "Error: " . $polaczenie->connect_errno;
            } 
            else {
                $zap_uzytkownicy = "SELECT id FROM uzytkownicy where typ='uzytkownik';";
                if ($resultt_uzytkownicy = $polaczenie->query($zap_uzytkownicy)) {
                    $c=0;
                    while ($row=$resultt_uzytkownicy->fetch_assoc()){
                        $c+=1;
                    }
                }
                echo $c;
                $polaczenie->close();
            }
            ?>
            </p>
        </div>
        <div class="zajete">
            <p>Liczba nierozwiązanych zgłoszeń:<br>
            <?php
            require_once "skrypty/baza.php";
            $polaczenie = @new mysqli($host, $uzytkownik_bd, $haslo_bd, $bd);
            $polaczenie->set_charset('utf8mb4');
            if ($polaczenie->connect_errno != 0) {
                echo "Error: " . $polaczenie->connect_errno;
            } 
            else {
                $zap_uzytkownicy = "SELECT id FROM zgloszenie where zamkniecie=0;";
                if ($resultt_uzytkownicy = $polaczenie->query($zap_uzytkownicy)) {
                    $d=0;
                    while ($row=$resultt_uzytkownicy->fetch_assoc()){
                        $d+=1;
                    }
                }
                echo $d;
                $polaczenie->close();
            }
            ?>
            </p>
        </div>


        <ol>
            <li><div class="ustawienia">
                    <a>Ustawienia</a>
                </div>
                <ul>
                    <li><div class="ustawienia2" onclick="openPopup1()">
                        <a>2fa</a>
                    </div></li>
                    <li><div class="ustawienia2" onclick="openPopup2()">
                        <a>zmiana hasła</a>
                    </div></li>
                </ul>
            </li>
        </ol>


        <a class="wyloguj" href="./skrypty/wyloguj.php">Wyloguj</a>

    </div>

    <div class="element2">
        <br><br><br><br><div class="files-container">
            <?php
            $fullurl = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            if(strpos($fullurl,"dysk")==true){
                echo "<h1>DYSK</h1>";
                require_once "skrypty/baza.php";
                $polaczenie = @new mysqli($host, $uzytkownik_bd, $haslo_bd, $bd);
                $polaczenie->set_charset('utf8mb4');
                if ($polaczenie->connect_errno != 0) {
                    echo "Error: " . $polaczenie->connect_errno;
                } 
                else {
                    $ses="SELECT * FROM sesja";
                    $wynik_ses = $polaczenie->query($ses);
                    $row_ses = $wynik_ses->fetch_assoc();
                    $ses_tyle1=$row_ses["ile"];
                    echo '
                            <div class="file-item">
                                <div class="file-name">
                                <form method="POST">
                                    Długość sesji użytkownika:<br><br>
                                    <input type="number" name="sesja_czas" value="'.$ses_tyle1.'" min="5" max="60">min.<br><br>
                                    <input type="submit" id="sesja" name="sesja_zmien" value="Ustaw długość sesji">
                                </form>
                                </div>
                            </div>
                            <div class="file-item">
                                <div class="file-name">
                                    Dozwolone rozszerzenia na dysku:<br><br>
                                </div>
                            </div>
                            ';
                }
            }elseif(strpos($fullurl,"zgloszenia")==true){
                echo "<h1>ZGŁOSZENIA</h1>";
                require_once "skrypty/baza.php";
                $polaczenie = @new mysqli($host, $uzytkownik_bd, $haslo_bd, $bd);
                $polaczenie->set_charset('utf8mb4');
                if ($polaczenie->connect_errno != 0) {
                    echo "Error: " . $polaczenie->connect_errno;
                } 
                else {
                    $zap_pliki_moje = "SELECT * FROM zgloszenie where zamkniecie=0";
                    if ($result_moje = $polaczenie->query($zap_pliki_moje)) {
                        while ($rown=$result_moje->fetch_assoc()){
                            $wiadomosc=$rown['wiadomosc'];
                            $notatki=$rown['notatki'];
                            $idn=$rown['id'];
                            $emailz=$rown["email"];
                            echo '
                            <div class="file-item">
                                <div class="file-name">
                                <form method="POST">
                                    <input type="text" name="idn" value="'.$idn.'" style="display:none">
                                    <table style="width:90%">
                                    <tr>
                                    <td>zgłoszenie '.$emailz.'</td>
                                    <td>notatki</td>
                                    </tr>
                                    <tr>
                                    <td><textarea name="wiadomosc" readonly placeholder="'.$wiadomosc.'" value="'.$wiadomosc.'" style="resize:none;height:100%;width:90%;"></textarea></td>
                                    <td><textarea name="notatki" value="'.$notatki.'" style="resize:none;height:100%;width:90%;">'.$notatki.'</textarea></td>
                                    </tr>
                                    </table>
                                </div>
                                <div class="file-actions">
                                    <input type="submit" name="end_ok" value="OK" title="Dodaj notatkę" style="background:lightgreen">&nbsp;
                                    <input type="submit" name="end_x" value="X" title="Zamknij zgłoszenie" style="background:red">
                                </form>
                                </div>
                            </div>
                            ';
                        }
                        $result_moje->free();
                    }
                }
            }else{
                echo "<h1>UŻYTKOWNICY</h1>";
                require_once "skrypty/baza.php";
                $polaczenie = @new mysqli($host, $uzytkownik_bd, $haslo_bd, $bd);
                $polaczenie->set_charset('utf8mb4');
                if ($polaczenie->connect_errno != 0) {
                    echo "Error: " . $polaczenie->connect_errno;
                } 
                else {
                    $zap_pliki_moje = "SELECT * FROM uzytkownicy where typ='uzytkownik'";
                    if ($result_moje = $polaczenie->query($zap_pliki_moje)) {
                        while ($row=$result_moje->fetch_assoc()){
                            $idd=$row['id'];
                            $imie=$row['imie'];
                            $nazwisko=$row['nazwisko'];
                            $emaill=$row['email'];
                            $miejsceee2=$row['mb'];
                            $miejscee2=$miejsceee2/1048576;
                            $miejsce2=round($miejscee2, 2);
                            $miejsceee1 = "SELECT rozmiar FROM pliki where email='$emaill';";
                            if ($miejscee1 = $polaczenie->query($miejsceee1)) {
                                $miejsce1=0;
                                while ($row=$miejscee1->fetch_assoc()){
                                    $rozmiar=$row["rozmiar"];
                                    $miejsce1+=$rozmiar;
                                }
                            }
                            $miejsce11=$miejsce1/1048576;
                            $miejsce111=round($miejsce11, 2);
                            echo '
                            <div class="file-item">
                                <div class="file-name">'.$imie.'&nbsp;'.$nazwisko.'&nbsp;&nbsp;&nbsp;'.$emaill.'&nbsp;&nbsp;&nbsp;'.$miejsce111.' mb /'.$miejsce2.' mb&nbsp;&nbsp;&nbsp;</div>
                                <div class="file-actions">
                                    <a href="https://www.kjkenterprise.pl/strona_admin.php?uzyd='.$idd.'"><img src="css/edytuj.png" title="edytuj"></a><span>&nbsp;</span>
                                    <a href="https://www.kjkenterprise.pl/strona_admin.php?mies='.$idd.'"><img src="css/miejsce.png" title="zmień przydzielone miejsce"></a><span>&nbsp;</span>
                                </div>
                            </div>';
                        }
                        echo '<div class="dol">
                        <input type="button" onclick="openPopup3()" name="dodaj_uzytkownika" value="Dodaj użytkownika">
                        <div class="error">
                        </div>
                        </div>';
                        $result_moje->free();
                    }
                }
                $polaczenie->close();
            }
            
            ?>
            <!-- Możesz dodać formularz do dodawania nowych plików -->
        </div>
        <div class="przyciski">
            <br><br>
            <a href="strona_admin.php?uzytkownicy" class="uzytkownicy">Użytkownicy</a>
            <a href="strona_admin.php?dysk" class="dysk">Dysk</a>
            <a href="strona_admin.php?zgloszenia" class="zgloszenia">Zgłoszenia</a>
        </div>
    </div>
    <div id="po1">
        <div id="pop1">
            <button type="button" onclick="closePopup1()">X</button>
            <div class="login-container">
		        <h1>2fa</h1>
		        <form method="post">
                    <p>Wybierz metodę uwierzytelniania:</p>
                    <input type="radio" name="wybor" value="0">
                    <label for="age1">Brak</label>
                    <input type="radio" name="wybor" value="1">
                    <label for="age2">Email</label> 
                    <input type="radio" name="wybor" value="2">
                    <label for="age3">google auth</label>
				    <br><input type="submit" name="submit1" value="Zapisz wybór">
			    </form>
		    </div>
        </div>
    </div>
    <div id="po2">
        <div id="pop2">
            <button type="button" onclick="closePopup2()">X</button>
            <div class="login-container">
		        <h1>Nowe Hasło</h1>
		        <form method="post">
				    <label>Nowe hasło:</label><input type="password" name="password" id="password" required onkeyup='check();'><br>
				    <label>Powtórz hasło:</label><input type="password" name="confirm_password" id="confirm_password" required onkeyup='check();'><br>
				    <script>
				        var check = function() {
  				            if (document.getElementById('password').value ==
    				            document.getElementById('confirm_password').value) {
    				            document.getElementById('message').style.color = 'green';
    				            document.getElementById('message').innerHTML = 'Hasła się zgadzają';
					            document.getElementById('submit').disabled = false;
  				            } else {
    				            document.getElementById('message').style.color = 'red';
    				            document.getElementById('message').innerHTML = 'Hasła się nie zgadzają';
					            document.getElementById('submit').disabled = true;
  				            }
				        }
				    </script>
				    <span id="message"></span><br>
				    <br><input type="submit" name="password" value="Ustaw nowe hasło">
			    </form>
		    </div>
        </div>
    </div>
    <div id="po3">
        <div id="pop3">
            <button type="button" onclick="closePopup3()">X</button>
            <div class="login-container">
                <h1>Dodaj użytkownika:</h1>
                <form method="post">
				    <label>email:</label><input type="text" name="email2" id="email2" required><br>
                    <span id="message2"></span><br>
				    <br><input type="submit" name="submit_email2" value="Wyślij zaproszenie">
			    </form>
            </div>
        </div>
    </div>
    <div id="po4">
        <div id="pop4">
            <button type="button" onclick="closePopup4()">X</button>
            <div class="login-container">
                <img src="<?=$link;?>"><br>
                <form method="post">
				    <label>Wpisz kod:</label><input type="kod" name="kod" required><br>
                    <br><input type="submit" name="qr" value="Ustaw 2fa">
			    </form>
            </div>
        </div>
    </div>
<script>
    let popup1 = document.getElementById("po1");
    let popup2 = document.getElementById("po2");
    let popup3 = document.getElementById("po3");
    let popup5 = document.getElementById("po5");
    function openPopup1(){
        popup1.style.display="block";
    }
    function openPopup2(){
        popup2.style.display="block";
    }
    function openPopup3(){
        popup3.style.display="block";
    }
    function openPopup5(){
        popup5.style.display="block";
    }
    function closePopup1(){
        popup1.style.display="none";
    }
    function closePopup2(){
        popup2.style.display="none";
    }
    function closePopup3(){
        popup3.style.display="none";
    }
    function closePopup5(){
        popup5.style.display="none";
    }
    <?php
    if(isset($_POST["submit1"])){
        if($r1==2){
            echo 'let popup4 = document.getElementById("po4");
                popup4.style.display="block";
                function closePopup4(){
                popup4.style.display="none";
            }';
        }
    }
    ?>
</script>
</body>
</html>