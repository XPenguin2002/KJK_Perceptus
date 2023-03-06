<?php
session_start();
if(!isset($_SESSION['zalogowany'])||($_SESSION['typ_uzytkownika']=="admin")){
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

        $polaczenie->close();
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Przechowywanie plików | Użytkownik</title>
    <link rel="stylesheet" type="text/css" media="screen" href="css/css.php">
</head>
<body>

    <div class="element1">
    
        <h2>Witaj na stronie uzytkownika</h2>
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
                $zap_plikii_moje = "SELECT rozmiar FROM pliki where (kosz=2 or kosz=0) and email='$email'";
                if ($resultt_moje = $polaczenie->query($zap_plikii_moje)) {
                    while ($row=$resultt_moje->fetch_assoc()){
                        $rozmiar=$row["rozmiar"];
                        $a+=$rozmiar;
                    }
                }
                $zap_mb_moje = "SELECT mb FROM uzytkownicy where email='$email'";
                $result_mb_moje = $polaczenie->query($zap_mb_moje);
                $row=$result_mb_moje->fetch_assoc();
                $b=$row["mb"];
                $c=$a/1048576;
                $d=$b/1048576;
                $e=round($c, 2);
                $f=round($d, 2);
                echo $e." mb/ ".$f." mb";
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
                    <li><div class="ustawienia2" onclick="openPopup3()">
                        <a>usuwanie plików</a>
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
            if(strpos($fullurl,"ulubione")==true){
                echo "<h1>ULUBIONE</h1>";
                require_once "skrypty/baza.php";
                $polaczenie = @new mysqli($host, $uzytkownik_bd, $haslo_bd, $bd);
                $polaczenie->set_charset('utf8mb4');
                if ($polaczenie->connect_errno != 0) {
                    echo "Error: " . $polaczenie->connect_errno;
                } 
                else {
                    $zap_pliki_moje = "SELECT * FROM pliki where (email='$email' or siec=1) and kosz!=1";
                    if ($result_moje = $polaczenie->query($zap_pliki_moje)) {
                        while ($row=$result_moje->fetch_assoc()){
                            $plik=$row["plik"];
                            $id_plik=$row["id"];
                            $rozmi=$row["rozmiar"];
                            $rozmia=$rozmi/1024;
                            $rozmiar=round($rozmia, 2);
                            $wlasciciel=$row["email"];
                            $data=$row["data"];
                            $zap_pliki_mojee="SELECT id from ulubione where email='$email' and id=$id_plik";
                            $result_mojee = $polaczenie->query($zap_pliki_mojee);
                            $row2=$result_mojee->fetch_assoc();
                            if($row2['id']!=NULL){
                            echo '
                                <!-- Tu możesz wyświetlić listę plików -->
                                <div class="file-item">
                                    <div class="file-name">'.$plik.'&nbsp;&nbsp;&nbsp;'.$wlasciciel.'&nbsp;&nbsp;&nbsp;'.$rozmiar.'&nbsp;kb&nbsp;&nbsp;&nbsp;'.$data.'&nbsp;&nbsp;&nbsp;</div>
                                    <div class="file-actions">
                                        <a href=skrypty/pobierz.php?id='.$id_plik.'><img src="css/pobierz.png" title="pobierz"></a><span>&nbsp;</span>
                                        <a href=skrypty/nie_ulubione.php?id='.$id_plik.'><img src="css/ulubione.png" title="usuń z ulubionych"></a><span>&nbsp;</span>
                                    </div>
                                </div>';
                            }
                        }
                        $result_moje->free();
                    }
                    $polaczenie->close();
                }
            }elseif(strpos($fullurl,"wszystkie")==true){
                echo "<h1>UDOSTĘPNIONE</h1>";
                require_once "skrypty/baza.php";
                $polaczenie = @new mysqli($host, $uzytkownik_bd, $haslo_bd, $bd);
                $polaczenie->set_charset('utf8mb4');
                if ($polaczenie->connect_errno != 0) {
                    echo "Error: " . $polaczenie->connect_errno;
                } 
                else {
                    $zap_pliki_moje = "SELECT * FROM pliki where (kosz=0 or kosz=2) and email!='$email' and siec=1";
                    if ($result_moje = $polaczenie->query($zap_pliki_moje)) {
                        while ($row=$result_moje->fetch_assoc()){
                            $plik=$row["plik"];
                            $id_plik=$row["id"];
                            $rozmi=$row["rozmiar"];
                            $rozmia=$rozmi/1024;
                            $rozmiar=round($rozmia, 2);
                            $wlasciciel=$row["email"];
                            $data=$row["data"];
                            echo '
                                <!-- Tu możesz wyświetlić listę plików -->
                                <div class="file-item">
                                    <div class="file-name">'.$plik.'&nbsp;&nbsp;&nbsp;'.$wlasciciel.'&nbsp;&nbsp;&nbsp;'.$rozmiar.'&nbsp;kb&nbsp;&nbsp;&nbsp;'.$data.'&nbsp;&nbsp;&nbsp;</div>
                                    <div class="file-actions">
                                        <a href=skrypty/pobierz.php?id='.$id_plik.'><img src="css/pobierz.png" title="pobierz"></a><span>&nbsp;</span>';
                                        $zap_pliki_mojee="SELECT id from ulubione where email='$email' and id=$id_plik";
                                        $result_mojee = $polaczenie->query($zap_pliki_mojee);
                                        $row2=$result_mojee->fetch_assoc();
                                        if($row2['id']!=NULL){
                                        echo '<a href=skrypty/nie_ulubione.php?id='.$id_plik.'><img src="css/ulubione.png" title="usuń z ulubionych"></a><span>&nbsp;</span>';
                                        }
                                        else{
                                        echo '<a href=skrypty/ulubione.php?id='.$id_plik.'><img src="css/nie_ulubione.png" title="dodaj do ulubionych"></a><span>&nbsp;</span>';
                                        }
                                        $result_mojee->free();
                                    echo '</div>
                                </div>';
                        }
                        $result_moje->free();
                    }
                    $polaczenie->close();
                }
            }elseif(strpos($fullurl,"kosz")==true){
                echo "<h1>KOSZ</h1>";
                require_once "skrypty/baza.php";
                $polaczenie = @new mysqli($host, $uzytkownik_bd, $haslo_bd, $bd);
                $polaczenie->set_charset('utf8mb4');
                if ($polaczenie->connect_errno != 0) {
                    echo "Error: " . $polaczenie->connect_errno;
                } 
                else {
                    $zap_pliki_moje = "SELECT * FROM pliki where email='$email' and kosz=1";
                    if ($result_moje = $polaczenie->query($zap_pliki_moje)) {
                        while ($row=$result_moje->fetch_assoc()){
                            $plik=$row["plik"];
                            $id_plik=$row["id"];
                            $rozmi=$row["rozmiar"];
                            $rozmia=$rozmi/1024;
                            $rozmiar=round($rozmia, 2);
                            $wlasciciel=$row["email"];
                            $data=$row["data"];
                            echo '
                                <!-- Tu możesz wyświetlić listę plików -->
                                <div class="file-item">
                                    <div class="file-name">'.$plik.'&nbsp;&nbsp;&nbsp;'.$wlasciciel.'&nbsp;&nbsp;&nbsp;'.$rozmiar.'&nbsp;kb&nbsp;&nbsp;&nbsp;'.$data.'&nbsp;&nbsp;&nbsp;</div>
                                    <div class="file-actions">
                                        <a href=skrypty/usunn.php?id='.$id_plik.'><img src="css/usun.png" title="usuń"></a><span>&nbsp;</span>
                                        <a href=skrypty/nie_usun.php?id='.$id_plik.'><img src="css/nie_usun.png" title="przywróć"></a><span>&nbsp;</span>
                                    </div>
                                </div>';
                        }
                        $result_moje->free();
                    }
                    $polaczenie->close();
                }
            }else{
                echo "<h1>MOJE</h1>";
                require_once "skrypty/baza.php";
                $polaczenie = @new mysqli($host, $uzytkownik_bd, $haslo_bd, $bd);
                $polaczenie->set_charset('utf8mb4');
                if ($polaczenie->connect_errno != 0) {
                    echo "Error: " . $polaczenie->connect_errno;
                } 
                else {
                    $zap_pliki_moje = "SELECT * FROM pliki where email='$email' and (kosz=0 or kosz=2)";
                    if ($result_moje = $polaczenie->query($zap_pliki_moje)) {
                        while ($row=$result_moje->fetch_assoc()){
                            $plik=$row["plik"];
                            $id_plik=$row["id"];
                            $rozmi=$row["rozmiar"];
                            $rozmia=$rozmi/1024;
                            $rozmiar=round($rozmia, 2);
                            $wlasciciel=$row["email"];
                            $data=$row["data"];
                            echo '
                                <!-- Tu możesz wyświetlić listę plików -->
                                <div class="file-item">
                                    <div class="file-name">'.$plik.'&nbsp;&nbsp;&nbsp;'.$wlasciciel.'&nbsp;&nbsp;&nbsp;'.$rozmiar.'&nbsp;kb&nbsp;&nbsp;&nbsp;'.$data.'&nbsp;&nbsp;&nbsp;</div>
                                    <div class="file-actions">
                                        <a href=skrypty/pobierz.php?id='.$id_plik.'><img src="css/pobierz.png" title="pobierz"></a><span>&nbsp;</span>
                                        <a href=skrypty/usun.php?id='.$id_plik.'><img src="css/usun.png" title="usuń"></a><span>&nbsp;</span>
                                        <a href=skrypty/zlinkuj.php?id='.$id_plik.'><img src="css/zlinkuj.png" title="zlinkuj"></a><span>&nbsp;</span>';
                                        $zap_pliki_mojeee="SELECT siec from pliki where email='$email' and id=$id_plik";
                                        $result_mojeee = $polaczenie->query($zap_pliki_mojeee);
                                        $row3=$result_mojeee->fetch_assoc();
                                        if($row3["siec"]!=1){
                                            echo '<a href=skrypty/opublikuj.php?id='.$id_plik.'><img src="css/nie_opublikuj.png" title="upublicznij"></a><span>&nbsp;</span>';
                                        }
                                        else{
                                            echo '<a href=skrypty/nie_opublikuj.php?id='.$id_plik.'><img src="css/opublikuj.png" title="nie_upubliczniaj"></a><span>&nbsp;</span>';
                                        }
                                        $result_mojeee->free();
                                        $zap_pliki_mojee="SELECT id from ulubione where email='$email' and id=$id_plik";
                                        $result_mojee = $polaczenie->query($zap_pliki_mojee);
                                        $row2=$result_mojee->fetch_assoc();
                                        if($row2['id']!=NULL){
                                        echo '<a href=skrypty/nie_ulubione.php?id='.$id_plik.'><img src="css/ulubione.png" title="usuń z ulubionych"></a><span>&nbsp;</span>';
                                        }
                                        else{
                                        echo '<a href=skrypty/ulubione.php?id='.$id_plik.'><img src="css/nie_ulubione.png" title="dodaj do ulubionych"></a><span>&nbsp;</span>';
                                        }
                                        $result_mojee->free();
                                    echo '</div>
                                </div>';
                        }
                        $result_moje->free();
                    }
                }
                $polaczenie->close();
            }
            ?>
            <!-- Możesz dodać formularz do dodawania nowych plików -->
            <div class="dol">
                <form action="skrypty/dodaj_plik.php" method="post" name="dodaj_form" enctype="multipart/form-data">
                    <input type="file" name="file" id="file" required>
                    <input type="submit" name="dodaj_submit" value="Dodaj plik">
                    <div class="error">
                        <?php 
                        $fullurl = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                        if(strpos($fullurl,"blad1")==true){
                            echo "Coś poszło nie tak.<br>";
                        }
                        elseif(strpos($fullurl,"blad2")==true){
                            echo "Za mało miejsca na dysku.<br>";
                        }
                        elseif(strpos($fullurl,"git1")==true){
                            echo "<span style='color:green'>Przesłano plik.</span><br>";
                        } 
                        ?>
                    </div>
                </form>
            </div>
        </div>
        <div class="przyciski">
            <br><br>
            <a href="strona_user.php?ulubione" class="ulubione">Ulubione</a>
            <a href="strona_user.php?moje" class="moje">Moje</a>
            <a href="strona_user.php?wszystkie" class="wszystkie">Udostępnione</a>
            <a href="strona_user.php?kosz" class="kosz">Kosz</a>
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
		        <h1>Ustawienia kosza</h1>
		        <form method="post">
                    <p>Co ile dni chcesz usuwać pliki z kosza:</p>
                    <input type="number" value="<?=$kosz;?>" min="1" max="365" name="kosz_ile"/><br>
				    <br><input type="submit" name="kosz_submit" value="Zapisz wybór">
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
    function openPopup1(){
        popup1.style.display="block";
    }
    function openPopup2(){
        popup2.style.display="block";
    }
    function openPopup3(){
        popup3.style.display="block";
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