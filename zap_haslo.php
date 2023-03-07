<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Hasło</title>
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
    <h1>Zapomniałeś hasła?</h1>
    <form action="./skrypty/mail_haslo.php" method="post">
            <label>Email:</label><input type="text" name="email" required><br>
			<span>
                <?php
                $fullurl = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                if(strpos($fullurl,"blad=nl")==true){
                    echo "<span style='color: red;'>Coś poszło nie tak. Spróbuj ponownie później.</span><br>";
                    goto bob;
                }
				elseif(strpos($fullurl,"pow=n1")==true){
                    echo "<span style='color: green;'>Link z instrukcją do zresetowania hasła został wysłany na podaną wiadomośc email.</span><br>";
                    goto bob;
                }
                bob:
                ?>
            </span>
            <br><input type="submit" value="Wyslij prośbę o zresetowanie hasła">
        </form>
    </div>
</body>
</html>