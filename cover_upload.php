<!DOCTYPE html
	PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
  <html>
  <head>
  <title>GiantDisc Web Interface: settings</title><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><meta name="author" content="J?rgen Th?ns">
  <meta name="pragma" content="no-cache">
  <meta http-equiv="cache-control" content="no-cache">
  <meta http-equiv="expires" content="100">
  <meta name="revisit-after" content="1">
  <link href="gdweb.css" rel="stylesheet" type="text/css">
  <link rel="SHORTCUT ICON" href="img/gd16.ico">

<?php

include "control_web.inc";
include "gdwebdef.php";

  $link =  mysqli_connect( MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB );
  if ( ! $link )
      die( "Keine Verbindung zu MySQL" );


## Variablen aus Browser-Zeile
$cddbid=$_POST['cddbid'];


print "<meta http-equiv=\"refresh\" content=\"5;URL=cover.php?cddbid=$cddbid\">";
?>  

  
<script type="text/javascript">
// code generated with http://www.free-solutions.de/js/browser_tool_neuwin.htm
function popUp1(wintype)
{
  var nwl = (screen.width-620)/2;
  var nwh = (screen.height-450)/2;
  popUp=window.open(wintype, 'NewWindows', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=620,height=450,left=22,top=22'); 

  popUp.window.focus(); 
}
</script>

</head>
<body>

<table>
<tr><td><div class='maintitle'>GiantDisc&nbsp;Web&nbsp;Interface</div><div class='settings'></td><td width="100%" height="44" align="right">
	<img src="img/M4A.png" height="75" border="0" align="right">
	<img src="img/flac.gif" height="75" border="0" align="right">
	<img src="img/opus.png" width="75" border="0" align="right">
	<img src="img/ogg_vorbis.png" height="75" border="0" align="right">
	<img src="img/mp3.png" height="75" border="0" align="right">
  </td></tr></table>

<?php

#$upload_verzeichnis = '/tmp';
$upload_verzeichnis = $tempdir;


# Name für Upload-Element im Formular heisst 'datei'
if (isset($_FILES['datei']['name'])) {
    $dateiname = $_FILES['datei']['name'];
# Dateinamen prüfen: Nur Buchstaben, Punkt, Unter- und Bindestrich erlaubt:
#  if (ereg('^[a-zA-Z0-9._-]*$', $dateiname)) {

# Dateinamen prüfen: Nur Buchstaben, Punkt, Unter- und Bindestrich erlaubt:
  $muster = "#^[a-zA-Z0-9._-]+$#";
#if (!preg_match($muster, $dateiname)) {
#   echo 'String enthält auch andere Zeichen.';  
#} else {
#   echo 'String enthält nur Buchstaben und Zahlen.';
#}

  if (preg_match($muster, $dateiname)) {


# Dateityp prüfen
if(($datei_type != 'image/gif' && $datei) && ($datei_type != 'image/jpeg' && $datei)  && ($datei_type != 'image/png' && $datei))
#  if(($datei_type != 'audio/mpeg' && $datei) && ($datei_type != 'audio/ogg' && $datei) && ($datei_type != 'audio/flac' && $datei))
#  if(($datei_type != 'audio/mpeg' && $datei))
	{
	print "<p>Falscher Dateityp!</p>";
	$hochladen = "Fehler";
	}    
# WICHTIG: Prüfen, ob Datei schon existiert, um überschreiben zu verhindern!
	if (file_exists("$upload_verzeichnis/$dateiname"))
	{
    echo "<p>Datei " . htmlspecialchars($dateiname) . " existiert schon!</p>";
	$hochladen = "Fehler";
  }
# Ist ein Fehler aufgetreten?
if ($hochladen == "Fehler")
	{
	print "<p><b>Error</b></p>";	  
	}
	else
	{
    if (move_uploaded_file($_FILES['datei']['tmp_name'],
                             "$upload_verzeichnis/$dateiname")) {

    echo "$output105<br>";

    $recset = mysqli_query ($link, "SELECT mp3file FROM tracks WHERE sourceid = '$cddbid' AND tracknb = 1 ");
    while($row = mysqli_fetch_object($recset))
        {
        $mp3file = $row->mp3file;
        }    
print "$tempdir<br>";
print "$dateiname<br>";
print "$mp3file<br>";

#    $lang = strlen($mp3file);
#    $lang = $lang - 4;
#    $new_img_name = substr($mp3file,0,$lang);

    $teilung = explode(".", $mp3file);
    $new_img_name=$teilung[0];
    
print "$new_img_name<br>";
    $extend = explode(".", $dateiname);
    $was = $extend[1];
    $new_tumb_name = $new_img_name."-t";
    $new_img_name = $new_img_name.".".$was;

print "$new_img_name<br>";
	$sourceid = "$upload_verzeichnis/$dateiname";
  $ausgabe = "$imagedir/$new_img_name";
	copy ($sourceid, $ausgabe);
print "$imagedir<br>";

$pichoehe = 80; // 80 Pixel soll Bild hoch sein
// Bilddaten feststellen
$size=getimagesize("$upload_verzeichnis"."/"."$dateiname");
$breite=$size[0];
$hoehe=$size[1];

$neueHoehe=$pichoehe; 
$neueBreite=intval($breite*$neueHoehe/$hoehe);

    print "$size[0]<br>";
    print "$size[1]<br>";
    print "$neueBreite<br>";
    print "$neueHoehe<br>";

    if($size[2]==2) {
// Es ist ein JPG
$altesBild=ImageCreateFromJPEG("$upload_verzeichnis"."/"."$dateiname");
$neuesBild=imagecreatetruecolor($neueBreite,$neueHoehe);
ImageCopyResized($neuesBild,$altesBild,0,0,0,0,$neueBreite,$neueHoehe,$breite,$hoehe);
ImageJPEG($neuesBild,"$imagedir"."/"."$new_tumb_name".".jpg", 100);
} 

if($size[2]==3) {
// Es ist ein PNG
$altesBild=ImageCreateFromPNG("$upload_verzeichnis"."/"."$dateiname");
$neuesBild=ImageCreate($neueBreite,$neueHoehe);
ImageCopyResized($neuesBild,$altesBild,0,0,0,0,$neueBreite,$neueHoehe,$breite,$hoehe);
ImagePNG($neuesBild,"$imagedir"."/"."$new_tumb_name".".png", 100);
}

unlink("/$upload_verzeichnis/$dateiname");

  $ergebnis = mysqli_query( $link, "UPDATE album SET coverimg = '$new_img_name' WHERE cddbid = '$cddbid'");

#  print "<hr><br>";
#  print "$new_img_name<br>";
#  print "$cddbid<br>";
  
  
	   }
  }  
  }
	else
	{
	print "<p>$output102</p>";
	print "<p><b>Error</b></p>";
	$hochladen = "Fehler";
	}    
}

  mysqli_free_result($recset);
  mysqli_close( $link );





?>








<hr noshade>

</body>
</html>