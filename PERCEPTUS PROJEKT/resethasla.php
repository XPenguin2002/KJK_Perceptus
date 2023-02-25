<?php
require_once "skrypty/baza.php";
$polaczenie = @new mysqli($host, $uzytkownik_bd, $haslo_bd, $bd);
$polaczenie->set_charset('utf8mb4');
if ($polaczenie->connect_errno != 0) {
    echo "Error: " . $polaczenie->connect_errno;
} 
else {

    if(!isset($_GET["kod"])){
		exit("Nie znaleziono strony");
	}
	
	$kod = $_GET["kod"];
	$ile = "SELECT email FROM resethasla WHERE kod='$kod'";
	$wile = @$polaczenie->query($ile);
	
	if(mysqli_num_rows($wile) == 0){
		exit("Nie znaleziono strony");
	}

	if(isset($_POST["password"])){
		$pw = $_POST["password"];
		$row = mysqli_fetch_array($wile);
		$email = $row["email"];
		$kon = "UPDATE uzytkownicy set haslo=SHA2('$pw',512) where email='$email'";
		$konn = @$polaczenie->query($kon);
		if($konn){
			exit("Hasło zostało zmienione");
		}
		else{
			exit("Coś poszło nie tak");
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
		<title>Reset hasła</title>
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
		<h1>Reset Hasła</h1>
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
				<br><input type="submit" id="submit" value="Resetuj hasło">
			</form>
		</div>
	</body>
	</html>
	

    

