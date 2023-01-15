<?php
session_start();
if(!isset($_SESSION['zalogowany'])||($_SESSION['typ_uzytkownika']=="uzytkownik")){
    header("Location:index.php");
    session_unset();
    exit();
}
echo "<h4>Twoj email:".$_SESSION['email']."</h4>";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Strona admina</title>
</head>
<body>
    <h1>Witaj na stronie admina</h1>
    <a href="./skrypty/wyloguj.php">Wyloguj</a>
</body>
</html>