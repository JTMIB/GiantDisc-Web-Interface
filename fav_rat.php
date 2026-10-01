<?php
include "control_web.inc";

  $link =  mysqli_connect( MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB );
  if ( ! $link )
      die( "Keine Verbindung zu MySQL" );

## Variablen aus Browser-Zeile
$cddbid=$_GET['cddbid'];
$evaluation=$_GET['evaluation'];
$action=$_GET['action'];

if($action == "set")
  {
  print "$action<br>";
  $ergebnis = mysqli_query( $link, "insert into album_fav (cddbid, evaluation) values('$cddbid', '$evaluation')" );
  }      

if($action == "update")
  {
  print "$action<br>";
  $ergebnis = mysqli_query( $link, "UPDATE album_fav SET evaluation = $evaluation WHERE cddbid LIKE '%$cddbid%'");
  } 

if($action == "delete")
  {
  print "$action<br>";
  $ergebnis = mysqli_query( $link, "DELETE FROM album_fav WHERE cddbid LIKE '%$cddbid%'");
  }

  #mysql_free_result($ergebnis);
  mysqli_close( $link );
?>
<html>
  <head>
  <title>GiantDisc Web Interface: settings</title><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><meta name="author" content="Juergen Thoens">
  <meta name="pragma" content="no-cache">
  <meta http-equiv="cache-control" content="no-cache">
  <meta http-equiv="expires" content="100">
  <meta name="revisit-after" content="1">
<?php	print "<META HTTP-EQUIV='Refresh' CONTENT='1; URL=cover.php?cddbid=$cddbid'>";?>  
  <link href="gdweb.css" rel="stylesheet" type="text/css">
  <link rel="SHORTCUT ICON" href="img/gd16.ico">
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
  <body>
<?php

#print "$cddbid<br>";
#print "$evaluation<br>";


?>
<hr noshade>
</body>
</html>  