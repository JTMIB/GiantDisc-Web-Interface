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

## Variablen aus Browser-Zeile
$cddbid=$_GET['cddbid'];
$artist=$_GET['artist'];
$title=$_GET['title'];


print "<meta http-equiv=\"refresh\" content=\"3;URL=cover.php?cddbid=$cddbid\">";
?>

</head>
<body>
<?php


print "$output103 $cddbid / $artist / $title $output104";

  $link =  mysqli_connect( MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB );
  if ( ! $link )
      die( "Keine Verbindung zu MySQL" );


$qrystr = mysqli_query( $link, "SELECT * FROM album WHERE cddbid LIKE '$cddbid%' and artist LIKE '$artist%' and title LIKE '$title%'");
$anz_row = mysqli_num_rows( $qrystr );
#print "$anz_row<br>";      

	$a = 0;
	while ( $datensatz = mysqli_fetch_row( $qrystr ) )
    	{
    	foreach ( $datensatz as $feld )
		  	{
			   $a++;
			   if ($a == 5)
			      {
            #print "$feld<br>";
            $coverimg = $feld;
            }
			  }
		  }

$coverimg_t = explode(".", $coverimg);
#print "$coverimg_t[0]<br>" ;
#print "$coverimg_t[1]<br>" ;
$coverimg_t = $coverimg_t[0] . "-t." . $coverimg_t[1];

$qrystr = mysqli_query( $link, "UPDATE album SET coverimg=NULL WHERE cddbid LIKE '$cddbid%' and artist LIKE '$artist%' and title LIKE '$title%'");
unlink("$imagedir/$coverimg_t");
unlink("$imagedir/$coverimg");

      

#  mysqli_free_result( $qrystr );
  mysqli_close( $link );
?>
<hr noshade>

</body>
</html> 