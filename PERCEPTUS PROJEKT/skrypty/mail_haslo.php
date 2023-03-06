<?php

require_once "baza.php";
$polaczenie = @new mysqli($host, $uzytkownik_bd, $haslo_bd, $bd);
$polaczenie->set_charset('utf8mb4');
if ($polaczenie->connect_errno != 0) {
    echo "Error: " . $polaczenie->connect_errno;
} 
else {

    $subject = "resetowanie hasla ";
    $header = "MIME-Version: 1.0" . "\r\n"; 
    $header .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $do = $_POST["email"];
    $od = "-fno-reply";
    $kod = uniqid(true);
    $fullurl = "https://www.kjkenterprise.pl/resethasla.php?kod=$kod";
    $htmlContent = "<h1>Reset hasła</h1> <h3><a href='$fullurl'>Link ważny przez godzinę</a></h3>";
    $sql = "INSERT INTO `resethasla` (`kod` ,`email`)VALUES ('$kod','$do')";
    $polaczenie->query($sql);

    if(mail($do,$subject,$htmlContent,$header,$od)){
        header("location:../zap_haslo.php?pow=n1");
        $polaczenie->close();
        exit();
    }
    else{
        header("location:../zap_haslo.php?blad=nl");
        $polaczenie->close();
        exit();
    }
}
    
?>