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
$zap_pliki_moje = "SELECT plik FROM pliki where id=$id";
$result_moje = $polaczenie->query($zap_pliki_moje);
$row=$result_moje->fetch_assoc();
$plik=$row["plik"];
$fullPath="../file/$plik";
echo $fullPath;
//Use Mysql Query to find the 'full path' of file using $FileNo.
// I Assume $FilePaths as 'Full File Path'.





  if( headers_sent() )
    die('Headers Sent');


  if(ini_get('zlib.output_compression'))
    ini_set('zlib.output_compression', 'Off');


  if( file_exists($fullPath) )
  {

    $fsize = filesize($fullPath);
    $path_parts = pathinfo($fullPath);
    $ext = strtolower($path_parts["extension"]);

    switch ($ext) 
    {
      case "pdf": $ctype="application/pdf"; break;
      case "exe": $ctype="application/octet-stream"; break;
      case "zip": $ctype="application/x-zip-compressed"; break;
      case "doc": $ctype="application/msword"; break;
      case "xls": $ctype="application/vnd.ms-excel"; break;
      case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
      case "gif": $ctype="image/gif"; break;
      case "png": $ctype="image/png"; break;
      case "jpeg":
      case "jpg": $ctype="image/jpg"; break;
      case "txt": $ctype="text/plain"; break;
      case "log": $ctype="application/octet-stream"; break;
      case "mp4": $ctype="video/mp4"; break;
      default: $ctype="application/force-download";
    }

    header("Pragma: public"); 
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private",false); 
    header("Content-Type: $ctype");
    header("Content-Disposition: attachment; filename=\"".basename($fullPath)."\";" );
    header("Content-Transfer-Encoding: binary");
    header("Content-Length: ".$fsize);
    ob_clean();
    flush();
    readfile( $fullPath );

  } 
  else
    die('File Not Found');



$url=$_SERVER['HTTP_REFERER'];
header("Location: $url");
$polaczenie->close();
}

?>