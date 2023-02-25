<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Problem</title>
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
		textarea {
  			width: 100%;
  			height: 150px;
  			padding: 12px 20px;
			margin-bottom: 20px;
			box-sizing: border-box;
			border: 1px solid #ccc;
			border-radius: 4px;
			outline: none;
  			resize: none;
		}
	</style>
</head>
<body>
<div class="login-container">
    <h1>Formularz zgłoszeniowy</h1>
    <form action="./skrypty/mail_zgloszenie_problemu.php" method="post">
            <label>Imie:</label><input type="text" name="imie"><br>
            <label>Nazwisko:</label><input type="text" name="nazwisko"><br>
            <label>Email:</label><input type="text" name="email" required><br>
            <label>Wiadomość zgłoszenia:</label><textarea type="wiadomosc" name="wiadomosc" required></textarea><br>
			<span>
                <?php
                $fullurl = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                if(strpos($fullurl,"blad=pil")==true){
                    echo "<span style='color: red;'>Za dużo zgłoszeń na raz. Spróbuj ponownie za 10 minut.</span><br>";
                    goto bob;
                }
                elseif(strpos($fullurl,"blad=nl")==true){
                    echo "<span style='color: red;'>Coś poszło nie tak. Spróbuj ponownie później.</span><br>";
                    goto bob;
                }
				elseif(strpos($fullurl,"pow=n1")==true){
                    echo "<span style='color: green;'>Email został wysłany.</span><br>";
                    goto bob;
                }
                bob:
                ?>
            </span>
            <br><input type="submit" value="Wyslij zgloszenie">
        </form>
    </div>
</body>
</html>