<?php
session_start();
if(!isset($_SESSION['typ_uzytkownika'])){
    header("Location: ../index.php");
}
if($_SESSION['typ_uzytkownika']=="admin"){
    header('Location: ../strona_admin.php');
}
else if($_SESSION['typ_uzytkownika']=="uzytkownik"){
    header('Location: ../strona_user.php');
}
?>