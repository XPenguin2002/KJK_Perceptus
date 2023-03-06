<?php
session_start();

require_once "baza.php";
$polaczenie = @new mysqli($host, $uzytkownik_bd, $haslo_bd, $bd);
$polaczenie->set_charset('utf8mb4');
if ($polaczenie->connect_errno != 0) {
    echo "Error: " . $polaczenie->connect_errno;
} 
else {

$id=$_GET["id"];
$kod = rand(100000,999999);
$fullurl = "https://www.kjkenterprise.pl/skrypty/zlinkujj.php?kod=$kod";
?>
<input type="text" value="<?=$fullurl;?>" id="myInput">
<?php
$zapytanie = "UPDATE pliki set kod='$kod' where id='$id'";
$wynik = $polaczenie->query($zapytanie);
?>
<script>
    var copyText=document.getElementById("myInput");
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(copyText.value);
    copyText.style.display="none";
</script>
<?php

}
echo "Skopiowano unikatowy link do schowka. Za chwilę nastąpi przekierowanie do poprzedniej strony.";
$url=$_SERVER['HTTP_REFERER'];
header("refresh:5;URL=$url");
?>