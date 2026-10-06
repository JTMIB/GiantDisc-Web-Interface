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
#include "playlist.inc";
#include "gdwebdef.php";



#print_header ("browse", "", "$cgi_dir", "$host", "$requ");
print_header ("browse", "", "$cgi_dir", "", $output135, $output136, $output137, $output138);

$link =  $link =  mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
  if ( ! $link )
      die( "Keine Verbindung zu MySQL" );

## Variablen aus Browser-Zeile
$playlistname=$_GET['playlistname'];
$id=$_GET['id'];

### Show first level:
print "<p></p>";
print "<table class='browsemenu'>";
print "<tr>";
print "<td><small><b><a href=\"browse.php?l0=tr-ar\">$output126</a></b></small></td>";
print "<td><small><b><a href=\"browse.php?l0=tr-ti\">$output125</a></b></small></td>";
print "<td><small><b><a href=\"browse.php?l0=tr-gn\">$output124</a></b></small></td>";
print "<td><small><b><a href=\"browse.php?l0=s_tr\">$output123</a></b></small></td>";
print "</tr>";
print "<tr>";
print "<td><small><b><a href=\"browse.php?l0=al-ar\">$output122</a></b></small></td>";
print "<td><small><b><a href=\"browse.php?l0=al-ti\">$output121</a></b></small></td>";
print "<td><small><b><a href=\"browse.php?l0=al-gn\">$output120</a></b></small></td>";
print "<td><small><b><a href=\"browse.php?l0=allalb&pg=1\">$output109</a></b></small></td>";
print "<td><small><b><a href=\"browse.php?l0=playlist\">$output119</a></b></small></td>";
print "</tr>";
print "</table>";

$ergebnis = mysqli_query( $link , "SELECT id FROM playlist WHERE title = '$playlistname'" );
$anz_felder = mysqli_num_fields( $ergebnis );

$a = 0;
while ( $datensatz = mysqli_fetch_row( $ergebnis ) )
    {
    foreach ( $datensatz as $feld )
		  {
   	  $pl_id = $feld;
		  }
    }


$lang = strlen($playlistname);
if ($lang == 0)
{
print "<table><tr><td><P>&nbsp;<BR>&nbsp;<BR>&nbsp;<BR></P>";
print "<P>$output047</P>";
print "<div ALIGN=right><a href='javascript:history.back()'><img src='img/back.gif' BORDER=1></a></div></td></tr><table>";
}
else
{
if ($pl_id <= 0)
	{
	$neues_playlistname = str_replace(' ', '_', $playlistname);
	$playlistname = $neues_playlistname;

#	print "$playlistname<br>";
#	print $lang;
	
	mysqli_query( $link , "INSERT INTO playlist (title, author, note) VALUES ('$playlistname', '', '')" );

	$ergebnis = mysqli_query( $link , "SELECT id FROM playlist WHERE title = '$playlistname'" );
	$anz_felder = mysqli_num_fields( $ergebnis );

	$a = 0;
	while ( $datensatz = mysqli_fetch_row( $ergebnis ) )
	    {
	    foreach ( $datensatz as $feld )
			  {
	   	  $pl_id = $feld;
			  }
	    }

	mysqli_query( $link , "INSERT INTO playlistitem (playlist, tracknumber, trackid) VALUES ('$pl_id', 1, '$id')" );

	mysqli_free_result($ergebnis);

	}
else
	{
	#######################################################################################
	### insert a title into playlist
	mysqli_free_result($ergebnis);
#	mysql_close( $link );
	$title = $playlistname;
	p_appendex ($pl_id, $title, $id, $link);
	}

#######################################################################################
### display the selected playlist
$title = $playlistname;
p_listen ($pl_id, $title, $PATH_ABSOLUTE_mp3, "", "", "", "", "", $xspf, "", $link, $mp3dir);
}

mysqli_close( $link );
?>

<hr noshade>

<p>

<?php
print_footer ("browse");
?>
