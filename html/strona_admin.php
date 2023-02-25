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

    <div class="element1">
    
        <h2>Witaj na stronie admina</h2>
        <div class="zajete">
            <p>Zajęte miejsce na dysku:<br>69420MB/2137666MB</p>
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
            <h1>Przechowywanie plików</h1>
            <!-- Tu możesz wyświetlić listę plików -->
            <div class="file-item">
                <img class="file-icon" src="/file-icon.png" alt="Ikona pliku">
                <div class="file-name">Nazwa pliku </div>
                <div class="file-actions">
                    <a href="/download/plik.pdf"> Pobierz </a>
                    <a href="/delete/plik.pdf"> Usuń </a>
                </div>
            </div>
            <!-- Możesz dodać formularz do dodawania nowych plików -->
            <div class="dol">
                <form action="/upload" method="post" enctype="multipart/form-data">
                    <input type="file" name="file" id="file" required>
                    <input type="submit" value="Dodaj plik">
                    <div class="error"> <!-- Tu możesz wyświetlić ewentualne błędy --> </div>
                </form>
            </div>
        </div>
        <div class="przyciski">
            <br><br>
            <a class="ulubione">Ulubione</a>
            <a class="moje">Moje</a>
            <a class="wszystkie">Wszystkie</a>
            <a class="kosz">Kosz</a>
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