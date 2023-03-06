<?php
session_start();
if((isset($_SESSION['zalogowany']))&&($_SESSION['zalogowany']==true)){
    require_once "./skrypty/sprawdzanie_typu.php";
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Panel logowania</title>
	<style type="text/css">
		body {
			font-family: Arial, sans-serif;
			margin: 0;
			padding: 0;
			background-color: #f1f1f1;
		}

		.login-container {
			width: 400px;
			margin: 100px auto;
			background-color: #fff;
			border-radius: 5px;
			box-shadow: 0 2px 5px rgba(0,0,0,0.3);
			padding: 20px;
		}

		.login-container h1 {
			text-align: center;
			margin-bottom: 20px;
		}

		.login-container label {
			display: block;
			margin-bottom: 10px;
		}

		.login-container input[type="text"],
		.login-container input[type="password"] {
			width: 100%;
			padding: 12px 20px;
			margin-bottom: 20px;
			box-sizing: border-box;
			border: 1px solid #ccc;
			border-radius: 4px;
			outline: none;
		}

		.login-container input[type="submit"] {
			width: 100%;
			padding: 12px 20px;
			margin-bottom: 20px;
			box-sizing: border-box;
			border: none;
			border-radius: 4px;
			background-color: #4caf50;
			color: #fff;
			cursor: pointer;
		}

		.login-container input[type="submit"]:hover {
			background-color: #45a049;
		}
	</style>
</head>
<body>
    <div class="login-container">
        <h1>Panel logowania</h1>
        <form action="./skrypty/logowanie.php" method="post">
            <label>Email:</label><input type="text" name="email"><br>
            <label>Hasło:</label><input type="password" name="haslo"><br>
            <input type="submit" value="Zaloguj sie">
        </form>
        <span style="color: red;">
                <?php
                $fullurl = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                if(strpos($fullurl,"blad=pil")==true){
                    echo "Przekroczono limit logowan. Sprobuj ponownie za 10 minut.<br>";
                    goto wykryto_blad;
                }
                elseif(strpos($fullurl,"blad=n1")==true){
                    echo "Nieudane logowanie. Prosze sprobowac ponownie.<br>";
                    goto wykryto_blad;
                }
				elseif(strpos($fullurl,"blad=n2")==true){
                    echo "Coś poszło nie tak. Spróbuj ponownie później.<br>";
                    goto wykryto_blad;
                }
				elseif(strpos($fullurl,"blad=n3")==true){
                    echo "Wpisano zły kod.<br>";
                    goto wykryto_blad;
                }
				elseif(strpos($fullurl,"kod1")==true){
                    echo '<style>
					#po4{
						display:block;position:absolute;top:0;bottom:0;left:0;right:0;z-index: 1;
					}
					#pop4{
						display:block;position:absolute;width:50%;height:50%;background:#f1f1f1;top:50%;left:50%;transform:translate(-50%,-50%);text-align:center;padding:0 30px 30px;border-radius: 6px;border-style: solid;
					}
					</style>
					<span style="color: black;">
					<div id="po4">
        				<div id="pop4">
            				<div class="login-container">
                				<form action="skrypty/logowanie2.php" method="post">
				    				<label>Wpisz kod otrzymany na email:</label><input type="kod" name="kod" required><br>
                    				<br><input type="submit" name="qr" value="Zaloguj się">
			    				</form>
            				</div>
        				</div>
    				</div>
					</span>';
                    goto wykryto_blad;
                }
				elseif(strpos($fullurl,"kod2")==true){
                    echo '<style>
					#po4{
						display:block;position:absolute;top:0;bottom:0;left:0;right:0;z-index: 1;
					}
					#pop4{
						display:block;position:absolute;width:50%;height:50%;background:#f1f1f1;top:50%;left:50%;transform:translate(-50%,-50%);text-align:center;padding:0 30px 30px;border-radius: 6px;border-style: solid;
					}
					</style>
					<span style="color: black;">
					<div id="po4">
        				<div id="pop4">
            				<div class="login-container">
                				<form action="skrypty/logowanie2.php" method="post">
				    				<label>Wpisz kod z aplikacji Google Authenticator:</label><input type="kod" name="kod" required><br>
                    				<br><input type="submit" name="qr" value="Zaloguj się">
			    				</form>
            				</div>
        				</div>
    				</div>
					</span>';
                    goto wykryto_blad;
                }
                wykryto_blad:
                ?>
            </span>
        <br><a href="formularz_problemy.php">Zglos nam swoj problem</a><br>
		<br><a href="zap_haslo.php">Zapomniałeś hasła?</a>
    </div>
</body>
</html>