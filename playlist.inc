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
 
#######################################################################################

#######################################################################################
#######################################################################################
### "append a title to a playlist"

function p_append($id){

echo("<h1>Append Title to Playlist</h1><hr noshade>");

$ergebnis = mysql_db_query( "GiantDisc", "SELECT artist, title FROM tracks where id=$id" );
$anz_felder = mysql_num_fields( $ergebnis );

print "<FORM METHOD='GET' ACTION='newpl.php'>
<table border='0' cellspacing='0'><tr class='lneven'><td class='trklst'>&nbsp;<small><B>Append</B></small>";

$a = 0;
while ( $datensatz = mysql_fetch_row( $ergebnis ) )
    {
    foreach ( $datensatz as $feld )
		  {
			if ($a == 0)
				{
				print "<small>&nbsp;&nbsp;$feld</small>";
				$a = $a + 1;
				}
			else
				{
				print "<small>&nbsp;&nbsp;-&nbsp;&nbsp;$feld</small>";
				$a = 0;
				}
		  }
    }
print "&nbsp;&nbsp;<small><B>to one listed playlist or create a new one:</B></small></td>
<td>&nbsp;</td>
<td>
<input name='playlistname' type='text' VALUE=''>
<input name='id' type='hidden' VALUE='$id'>
&nbsp;<INPUT TYPE='submit' VALUE='create'>
&nbsp;&nbsp;&nbsp;</td>
</tr></table></FORM><hr noshade>";
mysql_free_result($ergebnis);




$ergebnis = mysql_db_query( "GiantDisc", "SELECT id, title FROM playlist" );
$anz_felder = mysql_num_fields( $ergebnis );

echo("<table><tr><td>&nbsp;</td><td>&nbsp; &nbsp;</td><td><small><B>No.</B></small></td><td>&nbsp;</td><td><small><B>Playlistname</B></small></td></tr>");

$a = 0;
while ( $datensatz = mysql_fetch_row( $ergebnis ) )
    {
    foreach ( $datensatz as $feld )
		  {
			if ($a == 0)
				{
				#print "<small>$feld</small></td>";
				$pl_id = $feld;
				$a = $a + 1;
				}
			else
				{
				print "<tr><td><a  href=browse.php?l0=playlist&pl_id=$pl_id&title=$feld&id=$id><img border=\"0\" src=\"img/onetopl.gif\" alt=\"Append to Playlist\"></a></td><td>&nbsp;</td><td><small>$pl_id</small></td><td>&nbsp;-&nbsp;</td><td><small>
				<a  href=browse.php?l0=playlist&pl_id=$pl_id&title=$feld&id=$id>$feld</a></small></td></tr>";
				$a = 0;
				}
		  }
    }
echo("</table>");
mysql_free_result($ergebnis);
#mysql_close( $link );
}

#######################################################################################
#######################################################################################
### insert a title into playlist

function p_appendex($pl_id, $title, $id){

#$benutzer = "music";
#$passwort = "music";
#$db = "GiantDisc";
#$link =  mysql_connect( "localhost", $benutzer, $passwort );
#if ( ! $link )
#    die( "Keine Verbindung zu MySQL" );
#mysql_select_db( $db, $link )
#    or die ( "Konnte Datenbank \"$db\" nicht öffnen: ".mysql_error() );

$ergebnis = mysql_db_query( "GiantDisc", "select tracknumber from playlistitem where playlist=$pl_id" );

$a=0;
$b=0;
while ( $datensatz = mysql_fetch_row( $ergebnis ) )
    {
    foreach ( $datensatz as $feld )
		  {
			$a = $feld;
			if ($a > $b)
				$b = $a;
		  }
    }
$b++;

mysql_free_result($ergebnis);

$ergebnis = mysql_db_query( "GiantDisc", "insert into playlistitem (playlist, tracknumber, trackid)
values ($pl_id, $b, $id)" );


#mysql_close( $link );
}

?>
