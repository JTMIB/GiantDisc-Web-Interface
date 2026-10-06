<?php
/**
 * GiantDisc Web-Interface (Modernisierte Version)
 * 
 * @author Jürgen Thöns <juergen.thoens@gmx.de>
 * @copyright 2026 Jürgen Thöns
 * 
 * Basiert strukturell auf dem originalen GiantDisc-Interface von Rolf Brugger.
 * Dieses Programm ist Freie Software: Sie können es unter den Bedingungen
 * der GNU General Public License, wie von der Free Software Foundation
 * veröffentlicht, weitergeben und/oder modifizieren (Version 3 der Lizenz).
 */
 
include "control_web.inc";
include "gdwebdef.php";

## Variablen aus Browser-Zeile
$l0=$_GET['l0']; 
$albid=$_GET['albid'];
$step=$_GET['step'];

$playtp = set_get_cookie($setplaytp, "playtp", "dl", $HTTP_COOKIE_VARS);

#######################################################################################
#print_header ("browse", "", "$cgi_dir", "$char_set");
#print_header ("play", "play", "play", "play");
###############################################################
# nach player suchen
exec("ps -ef | grep $b_player > $tempdir/play.txt");
# stop
if ($step != "stop")
  {

$a = 0;

$file = "$tempdir/play.txt";
$file_handle = fopen($file, 'r');
 
while (!feof($file_handle)) {
 
  $line = fgets($file_handle);
#  print "$line<br>";
  $pos = strpos($line, "/usr/bin/$b_player $mp3dir");
#  print "$pos<br>";
  if ($pos === FALSE)
    {
    #print "nicht gefunden!<br>";
    }
  else
    {
    $ausgabe = substr($line, $pos);
#    print "$ausgabe<br>";
    $a++;
    $titel_nr = explode("/", $ausgabe);
#    print "$titel_nr[9]<br>";
    $titel_nummer = $titel_nr[9]; 
    }
}
#print "$a<br>";
fclose($file_handle);
  }
  

?>
<!DOCTYPE html
	PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
  <html>
  <head>
  <title>GiantDisc Web Interface: play</title><meta http-equiv="Content-Type" content="text/html; charset=play"><meta name="author" content="Juergen Thoens">
  <meta name="pragma" content="no-cache">
  <meta http-equiv="cache-control" content="no-cache">
  <meta http-equiv="expires" content="100">
  <meta name="revisit-after" content="1">
  <link href="gdweb.css" rel="stylesheet" type="text/css">
  <link rel="SHORTCUT ICON" href="img/gd16.ico">
  <link rel="stylesheet" type="text/css" href="style.css">
  <link href="lightbox.css" rel="stylesheet">
<?php

# refresch
  if ($step == "refresh")
    {
    if ($a > 0)
      {
      print "  <meta http-equiv=\"refresh\" content=\"8; URL=p_play.php?l0=prPlaylist&albid=$albid&step=refresh\">\n";
      }
    else
      {
      print "  <meta http-equiv=\"refresh\" content=\"2; URL=p_play.php?l0=prPlaylist&albid=$albid&step=stop\">\n";
      }
    }

  if ($step == "next")
    {
    print "  <meta http-equiv=\"refresh\" content=\"1; URL=p_play.php?l0=prPlaylist&albid=$albid&step=refresh\">\n";
    }
  if ($step == "start")
    {
    print "  <meta http-equiv=\"refresh\" content=\"2; URL=p_play.php?l0=prPlaylist&albid=$albid&step=refresh\">\n";
    }

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

<script src="jquery-2.1.3.min.js"></script>

</head>
<body>

  <table>
  <tr><td><div class='maintitle'>GiantDisc&nbsp;Web&nbsp;Interface</div><div class='play'></td><td width="100%" bgcolor="#ffffff" height="44" align="right">
	<img src="img/flac.gif" height="75" border="0" align="right">
	<img src="img/oggenc.gif" height="75" border="0" align="right">
	<img src="img/mp3.jpg" width="94" height="75" border="0" align="right"></td></tr>
  </table>

  <table border='0' cellspacing='0' cellpadding='1'><tr><td class="menuinactive"><a class="menu" href="index.php">Search</a></td>
  <td>&nbsp;</td>
  <td class="menuinactive"><a class="menu" href="browse.php">Browse</a></td>
  <td>&nbsp;</td>
  <td class="menuinactive"><a class="menu" href="record.php">Record</a></td>
  <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
  <td class="menuinactive"><a class="light" href="settings.php">Options</a></td></tr>
  </table>

<p>


<?php
#######################################################################################


#######################################################################################
  $link =  mysql_connect( MYSQL_HOST, MYSQL_USER, MYSQL_PASS );
  if ( ! $link )
      die( "Keine Verbindung zu MySQL" );

#######################################################################################
### Constants
$link =  mysql_connect( MYSQL_HOST, MYSQL_USER, MYSQL_PASS );
  if ( ! $link )
      die( "Keine Verbindung zu MySQL" );

$recset = mysql_db_query ("GiantDisc", "SELECT title from playlist WHERE id = $albid");      

while($row = mysql_fetch_object($recset))
   {
   $title = $row->title;
   }
  #print "<b>$albid</b><br>";
  #print "<b>$title</b><br>";


  mysql_free_result($recset);
  mysql_close( $link );

#######################################################################################
if ($step == "next")
    {
    exec("sudo -u root /usr/bin/killall mplayer > /dev/null");
    }

#######################################################################################
# start & stop
if ($step == "start" or $step == "stop")
{
# alten Job stoppen
$anz_kill = 0;
$dateiname = "$tempdir/b_player.sh";
$file_handle = fopen($dateiname, 'r');
while (!feof($file_handle)) {
 
  $line = fgets($file_handle);
#  print "$line<br>";
  $anz_kill++;
}
fclose($file_handle);
#print "$anz_kill<br>";

touch ("$tempdir/kill.sh");
$dateiname = "$tempdir/kill.sh";
$fp = fopen( $dateiname, 'w');
fwrite( $fp, "#\n");
$i = 0;
while($i < $anz_kill) {
#   print "$i<br>";
   $i++;
   fwrite( $fp, "sudo -u root killall $b_player\n");
   fwrite( $fp, "sudo -u root killall $b_player\n");
}
fclose( $fp );
exec("$tempdir/kill.sh");
}

###############################################################
# start  
if ($step == "start" && strcmp($l0, "prPlaylist")==0)
{
# Datei anlegen
touch ("$tempdir/b_player.sh");
$dateiname = "$tempdir/b_player.sh";
$fp = fopen( $dateiname, 'w');
fwrite( $fp, "#\n");

$link =  mysql_connect( MYSQL_HOST, MYSQL_USER, MYSQL_PASS );
  if ( ! $link )
      die( "Keine Verbindung zu MySQL" );

$recset = mysql_db_query ("GiantDisc",
 "SELECT tracks.mp3file
  FROM tracks, playlistitem
  WHERE playlistitem.playlist = $albid AND playlistitem.trackid = tracks.id");      

  $anz_row = mysql_num_rows( $recset );
#  print "$anz_row<br>";


while ( $datensatz = mysql_fetch_row( $recset ) )
    {
    foreach ( $datensatz as $feld )
		  {
#			print "sudo -u root /usr/bin/$b_player $PATH_ABSOLUTE_mp3/$feld<br>";
			fwrite( $fp, "sudo -u root /usr/bin/$b_player $PATH_ABSOLUTE_mp3/$feld\n");
		  }
	}
fclose( $fp );

  exec("/srv/www/htdocs/music/00/b_player.sh > /dev/null &");

mysql_free_result($recset);
mysql_close( $link );
}
  
#print "$l0<br>";
#print "$albid<br>";
#print "$step<br>";
#print "$b_player<br>";
#print "$titel_nummer<br>";

###############################################################
# nach player suchen
#exec("ps -ef | grep $b_player > $tempdir/play.txt");


$file = "$tempdir/play.txt";
$file_handle = fopen($file, 'r');
 
while (!feof($file_handle)) {
 
  $line = fgets($file_handle);
#  print "$line<br>";
  $pos = strpos($line, "/usr/bin/$b_player $mp3dir");
#  print "$pos<br>";
  if ($pos === FALSE)
    {
    #print "nicht gefunden!<br>";
    }
  else
    {
    $ausgabe = substr($line, $pos);
#    print "$ausgabe<br><br>";
    $titel_nr = explode("/", $ausgabe);
#    print "$titel_nr[9]<br>";
    $titel_nummer = $titel_nr[9]; 
    }
}

fclose($file_handle);

###############################################################
# Ausgabe von Playlist No und Playlist Name mit Titelliste
$link =  mysql_connect( MYSQL_HOST, MYSQL_USER, MYSQL_PASS );
  if ( ! $link )
      die( "Keine Verbindung zu MySQL" );

print "<table border=0>";
print "<tr><td>";
print "<hr>";
print "Playlist No. $albid - $title";
print "</td></tr>";

print "<tr><td>";
# Liste
$pl_id = $albid;
#p_listen ($pl_id, $title, $PATH_ABSOLUTE_mp3, $output080, $output082, $tool, $output091, $output092);
$ergebnis = mysql_db_query( "GiantDisc","select artist, title, tracknumber, id from tracks, playlistitem
where trackid = id
and playlist = $pl_id" );
$anz_felder = mysql_num_fields( $ergebnis );

  #if (mysql_affected_rows()>0){print "<hr noshade>";}else{print "<p>&nbsp;</p>";}
  print("<p><small><b>".mysql_affected_rows()." tracks in $title</b></small></p>");

echo("<tr><td><table><tr><td><small><b>Track-No.</b></small></td><td><small><b>Artist</b></small></td><td><small><b>Title</b></small></td><td>&nbsp;</td></tr>");

$a = 0;
#$track = 1;
$evenln = 1;
while ( $datensatz = mysql_fetch_row( $ergebnis ) )
    {
    foreach ( $datensatz as $feld )
		  {
		  switch ($a)
		  	{
			case 0:
				$artist = $feld;
				$a++;
			break;
			case 1:
				$title = $feld;
				$a++;
			break;
			case 2:
				$track = $feld;
				$a++;
			break;
			case 3:
			    if ($evenln)
					{
					print "<tr class=\"lneven\">";
					}
				else
					{
      				print "<tr class=\"lnodd\">";
					}

				print "<td class=\"trklst\">$track</td><td class=\"trklst\">$artist</td><td class=\"trklst\">$title</td>";
        $test_reihe = mysql_db_query( "GiantDisc","select trackid from playlistitem where $pl_id = playlist");
				$anz_row = mysql_num_rows( $test_reihe );

				$recset = mysql_db_query( "GiantDisc","select mp3file from tracks where $feld = id");
				while ( $datastep = mysql_fetch_row( $recset ) )
					{
					foreach ( $datastep as $field )
						{
						#$httphost = $_SERVER["HTTP_HOST"];
						#$l = strlen($PATH_ABSOLUTE_mp3) - 3;
						#$mp3path = substr($PATH_ABSOLUTE_mp3,$l,3);
            						
						print "<td class=\"trklst\">";
											
            #hier
				    $lyric = mysql_db_query( "GiantDisc","select lyrics from tracks where id = $feld and lyrics IS NOT NULL" );
        		$anz_lyric = mysql_num_rows( $lyric );
        		#print "$anz_lyric";
            if ($anz_lyric > 0)
                {
                print "&nbsp; &nbsp; &nbsp;<a href=\"javascript:popUp1('lyric.php?id=$feld')\">";       
                print "<img border=\"0\" src=\"img/lyric_button.png\" alt=\"Lyric\" title=\"Lyric\"></a></td>";
                }            
            else
                {
                print "&nbsp; </td>";
                }				
						
            # aktives Stück
            $vergleich = levenshtein ($field, $titel_nummer);
#            print "<br>$vergleich<br>";
#            print "$titel_nummer<br>";
#            print strlen ($titel_nummer);
#            print "<br>$field<br>";
#            print strlen ($field);
            

						if ($vergleich == 1 && $step != "stop")
                print "<td class=\"trklst\"> &nbsp; <img border=\"0\" src=\"img/aktiv_track.gif\" height=\"16\" alt=\"aktiv track\" title=\"aktiv track\">";
            else
                {
                print "<td class=\"trklst\">";
                #echo strcasecmp ($field, $titel_nummer);
						    }
						    
            print "</td>";
            print "</tr>";
            
						}
					}

				mysql_free_result($recset);
				$a = 0;
#				$track++;
				$evenln = 1-$evenln;
			break;
			}
		  }
    }
echo("</table></td>");

  print "<td><table><tr>";

if ($step == "stop")
  {
  #start playing
  print "<td>&nbsp; &nbsp; &nbsp;</td>";
  print "<td valign=\"center\">";
  print "<a href=\"p_play.php?l0=prPlaylist";
  print "&albid=$albid";
  print "&step=start";
  print "\">";
  print "<img border=\"0\" src=\"img/button_blue_play.png\" width=\"40\" title=\"$output131\"></a>";
  print "</td>"; 
  }
else
  {  
  #stop playing
  print "<td>&nbsp; &nbsp; &nbsp;</td>";
  print "<td valign=\"center\">";
  print "<a href=\"p_play.php?l0=prPlaylist";
  print "&albid=$albid";
  print "&step=stop";
  print "\">";
  print "<img border=\"0\" src=\"img/button_blue_stop.png\" width=\"40\" title=\"$output130\"></a>";
  print "</td>"; 
  }

  if ($step == "stop")
  {
  #nichts anzeigen
  print "<td>&nbsp; &nbsp; &nbsp;</td>";
  print "<td valign=\"center\">";
  print "<img border=\"0\" src=\"img/0.gif\" width=\"40\">";
  print "</td>";    
  }
else
  {
  #next track
  print "<td>&nbsp; &nbsp; &nbsp;</td>";
  print "<td valign=\"center\">";
  print "<a href=\"p_play.php?l0=prPlaylist";
  print "&albid=$albid";
  print "&step=next";
  print "\">";
  print "<img border=\"0\" src=\"img/forward.png\" width=\"40\" title=\"$output129\"></a>";
  print "</td>";  
  }

    
  print "</tr></table>";
  print "</td></tr></table>";

print "</td></tr>";   
print "</table>";  
  mysql_free_result($recset);
  mysql_close( $link );

#################################################################################################  
?>

<hr noshade>

<p>
 <br>
 <br>
 <br>
 <br>
</p>

<script src="lightbox.js"></script>
<script>
    lightbox.option({
      'resizeDuration': 200,
      'wrapAround': true
    })
 </script>
</body>
</html>