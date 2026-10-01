<?php

include "control_web.inc";
include "gdwebdef.php";

$playtp = set_get_cookie($setplaytp, "playtp", "dl", $HTTP_COOKIE_VARS);

## Variablen aus Browser-Zeile
$pl_id=$_GET['pl_id'];
$trackid=$_GET['trackid'];
$trackno=$_GET['trackno'];
$com=$_GET['com'];

#print_header ("browse", "", "$cgi_dir", "$char_set");
$current = "browse";
print "<!DOCTYPE html
	PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">
  <html>
  <head>
  <title>GiantDisc Web Interface: $current</title>";
print "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=$char_set\">";    
print "<meta name=\"author\" content=\"Jürgen Thöns\">
  <meta name=\"pragma\" content=\"no-cache\">
  <meta http-equiv=\"cache-control\" content=\"no-cache\">
  <meta http-equiv=\"expires\" content=\"100\">
  <meta name=\"revisit-after\" content=\"1\">
  <link href=\"gdweb.css\" rel=\"stylesheet\" type=\"text/css\">
  <link rel=\"SHORTCUT ICON\" href=\"img/gd16.ico\">";

$link =  mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
 if ( ! $link )
     die( "Keine Verbindung zu MySQL" );

if (strcmp($com, "delete")==0 || strcmp($com, "up")==0 || strcmp($com, "down")==0)
  {
  $ergebnis = mysqli_query( $link ,"select title from playlist where id = $pl_id");
  while ( $datensatz = mysqli_fetch_row( $ergebnis ) )
    {
    foreach ( $datensatz as $feld )
    $p_title = $feld;
    }
	print "<META HTTP-EQUIV='Refresh' CONTENT='1; URL=browse.php?l0=playlist&pl_id=$pl_id&title=$p_title&tool=edit#liste'>";  
  }


  print "</head>
  <body>";
  print "<table><tr>";
  print "<td>";
  print "<div class='maintitle'>GiantDisc&nbsp;Web&nbsp;Interface</div>";
  print "<div class='$current'>";
  print "</td>";

	print "<td width=\"100%\" height=\"44\" align=\"right\">
	<img src=\"img/flac.gif\" height=\"75\" border=\"0\" align=\"right\">
	<img src=\"img/oggenc.gif\" height=\"75\" border=\"0\" align=\"right\">
	<img src=\"img/mp3.jpg\" width=\"94\" height=\"75\" border=\"0\" align=\"right\">";
	print "</td></tr></table>";
	
    // print the main top menu
    print "<table border='0' cellspacing='0' cellpadding='1'><tr>";

    $sel = (strcmp($current, "search")==0) ? "" : "in";
    print "<td class=\"menu".$sel."active\"><a class=\"menu\" href=\"index.php\">Search</a></td>";
    print "<td>&nbsp;</td>";
    $sel = (strcmp($current, "browse")==0) ? "" : "in";
    print "<td class=\"menu".$sel."active\"><a class=\"menu\" href=\"browse.php\">Browse</a></td>";
    print "<td>&nbsp;</td>";
    $sel = (strcmp($current, "record")==0) ? "" : "in";
    print "<td class=\"menu".$sel."active\"><a class=\"menu\" href=\"record.php\">Record</a></td>";

    print "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
    $sel = (strcmp($current, "settings")==0) ? "" : "in";
    print "<td class=\"menu".$sel."active\"><a class=\"light\" href=\"settings.php\">Options</a></td>";

    print "</tr></table>";
  
    


#######################################################################################
?>

<p>

<?php
### Show first level:
print "<table class='browsemenu'>";
print "<tr>";
print "<td><small><b><a href=\"browse.php?l0=tr-ar\">Tracks by Artist</a></b></small></td>";
print "<td><small><b><a href=\"browse.php?l0=tr-ti\">Tracks by Title</a></b></small></td>";
print "<td><small><b><a href=\"browse.php?l0=tr-gn\">Tracks by Genre</a></b></small></td>";
print "<td><small><b><a href=\"browse.php?l0=s_tr\">Single Tracks</a></b></small></td>";
print "</tr>";
print "<tr>";
print "<td><small><b><a href=\"browse.php?l0=al-ar\">Albums by Artist</a></b></small></td>";
print "<td><small><b><a href=\"browse.php?l0=al-ti\">Albums by Title</a></b></small></td>";
print "<td><small><b><a href=\"browse.php?l0=al-gn\">Albums by Genre</a></b></small></td>";
print "<td><small><b><a href=\"browse.php?l0=allalb&pg=1\">all Albums</a></b></small></td>";
print "<td><small><b><a href=\"browse.php?l0=playlist\">Playlist</a></b></small></td>";
print "</tr>";
print "</table>";

print "<h4>Playlist</h4><hr noshade>";


if (strcmp($com, "ask_del")==0)
  {
  print("<p><small><b>$output083</b></small></p>");
  $ergebnis = mysqli_query( $link ,"select title from playlist where id = $pl_id");
  while ( $datensatz = mysqli_fetch_row( $ergebnis ) )
    {
    foreach ( $datensatz as $feld )
    $p_title = $feld;
    }
  $ergebnis = mysqli_query( $link ,"select artist, title from tracks where id = '$trackid'");
  $a = 1;
  while ( $datensatz = mysqli_fetch_row( $ergebnis ) )
    {
    foreach ( $datensatz as $feld )
	 	 switch ($a)
			{
			case $a == 1:
			$artist = $feld;
			$a++;
			break;

			case $a == 2:
			$title = $feld;
			$a++;
			break;

			default:
			$a++;
			break;
			}    
    }
  
  print "<p align='center'>$output084<br><small><b>$artist - $title</b></small><br>$output085 <small><b>No. $pl_id - $p_title</b></small> $output086</p>";
  
  
  print "<br><table align='center' border='0' cellspacing='0' cellpadding='1'><tr><td class=\"menuactive\"><a class=\"menu\" href=\"delete.php?pl_id=$pl_id&trackid=$trackid&trackno=$trackno&com=delete\">$output021</a></td>";
  print "<td> </td>";
  print "<td class=\"menuactive\"><a class=\"menu\" href=\"javascript:history.go(-1)\">$output022</a></td></tr></table><br>";

  }

if (strcmp($com, "delete")==0)
  {
  print("<p><small><b>$output083</b></small></p>");
  # löschen / delete
  print "<p align='center'>$output087</p>";
  $recset = mysqli_query( $link ,"DELETE FROM playlistitem where playlist = $pl_id && trackid = $trackid && tracknumber = $trackno");
  
  $ergebnis = mysqli_query( $link ,"select trackid from playlistitem where playlist = $pl_id order by tracknumber");
  $a = 1;
  $trackno = array();
  while ( $datensatz = mysqli_fetch_row( $ergebnis ) )
    {
    foreach ( $datensatz as $feld )
      {
			$trackno[$a] = $feld;
			#print "$trackno[$a]<br>";
			$a++;
			}
    }
  $recset = mysqli_query( $link ,"DELETE FROM playlistitem where playlist = $pl_id");
  $b = 1;
  while ($a != $b)
    {
    $ergebnis = mysqli_query( $link ,"INSERT INTO playlistitem (playlist, tracknumber, trackid)
                        VALUES ($pl_id, $b, $trackno[$b])");
    $b++;    
    }
  }
  # Ende löschen / delete


if (strcmp($com, "up")==0 || strcmp($com, "down")==0)
  {
  if (strcmp($com, "up")==0)
      print("<p><small><b>$output088</b></small></p>");
  if (strcmp($com, "down")==0)
      print("<p><small><b>$output089</b></small></p>");
  
  
  #print "$pl_id<br>";
  #print "$trackid<br>";
  #print "$trackno<br>";  

  $ergebnis = mysqli_query( $link ,"select trackid from playlistitem where playlist = $pl_id order by tracknumber");
  $a = 1;
  $tracknr = array();
  while ( $datensatz = mysqli_fetch_row( $ergebnis ) )
    {
    foreach ( $datensatz as $feld )
      {
			$tracknr[$a] = $feld;
			#print "$tracknr[$a]<br>";
			$a++;
			}
    }
    # tauschen
    $aktuell = $tracknr[$trackno];
    if (strcmp($com, "up")==0)
      {
      $davor = $tracknr[$trackno - 1];
      $tracknr[$trackno - 1] = $aktuell;
      $tracknr[$trackno] = $davor;     
      }
    if (strcmp($com, "down")==0)
      {
      $danach = $tracknr[$trackno + 1];
      $tracknr[$trackno + 1] = $aktuell;
      $tracknr[$trackno] = $danach;
      }


    $recset = mysqli_query( $link ,"DELETE FROM playlistitem where playlist = $pl_id");
    $b = 1;
    while ($a != $b)
      {
      $ergebnis = mysqli_query( $link ,"INSERT INTO playlistitem (playlist, tracknumber, trackid)
                        VALUES ($pl_id, $b, $tracknr[$b])");
      $b++;    
      }
  
  }

mysqli_close( $link ); 
?>

<hr noshade>

<p>

<?php
print_footer ("browse");
?>