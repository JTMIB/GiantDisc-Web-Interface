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


print_header ("record", "", "$cgi_dir", "$host", "$requ");
#print_head ("record", "$cgi_dir", "$host", "$requ");

#######################################################################################
### read inbox album

switch ($schritt)
{
case 1:
#Infobox ausgeben und Track's anzeigen
print "<script language=JavaScript>
<!--
 alert('$output015');
//-->
</script>";

$strSelFolder = "$htdocs/music/inbox/"; 	//  -> Rootvolume


if (strcmp($Folder,$strSelFolder) > 0)
	{
	$strSelFolder = $Folder;
	}


echo <<<HEAD3
	<table border=0 width=60%><tr><td>
	<table border=1>
HEAD3;
@chdir($strSelFolder);
$cdir = @dir($strSelFolder);
$cdir->rewind();
#clearstatcache();
$i = 0;
while ($entry = $cdir->read()) {
   if (!is_dir($entry)) {
#		$tescht = strspn( ".mp3", $entry );
		if (strspn( ".mp3", $entry ) == 4 || strspn( ".ogg", $entry ) == 4 || strspn( ".flac", $entry ) == 5)
		{
   	  if ($i++ % 2 == 0)
			{
		  echo "<tr bgcolor=LightGrey>";
			}
		  else
			{
	  	 echo "<tr bgcolor=white>";
			}
# Ist die Datei lesbar?
	  $chkopen = 0;
	  $fp = fopen ( $entry, "r" );
	  if ($fp == false)
	  	{
		print "can't open $entry";
		$chkopen++;
		}
	  else
	  	{
		fclose($fp);
		}	
	   echo "<td valign=top class=treetext>";
	   if ($chkopen == 0)
	   		{
	   		echo "<a href='read_inbox_track.php?schritt=2&file=$entry&Folder=$strSelFolder'>$entry</a>";
	   		}
	   else
			{
	   		echo "$entry";
	   		}
	  echo "</td>";
      echo "<td nowrap class=treetext align=right>".GetRealVolume(filesize($entry))."</td>";

	   echo '</td></tr>';
		}
	}
}
echo '</table>';
$cdir->close;
break;


case 2:

$artist = $file;
$title = $file;

print "<form action=read_inbox_track.php method=get>";
print "<table border=0><tr><td>";
print "<table border=0>
<tr>
<td class=plformtit>Artist</td>
<td colspan=3 class=plform>
<input type=text name=artist value='$artist' size=40 maxlength=200>
</td></tr>

<tr>
<td class=plformtit>Title</td>
<td colspan=3 class=plform>
<input type=text name=title  value='$title' size=40 maxlength=200>
</td></tr>

<tr>
<td class=plformtit>Composer</td>
<td colspan=3 class=plform>
<input type=text name=composer  value='$composer' size=40 maxlength=200>
</td></tr>

<tr>
<td class=plformtit>Genre</td>
<td colspan=3 class=plform>
<small><select name=genre1>";
print_genre_options($genre1);
print "</select></small>
<small><select name=genre2>";
print_genre_options($genre2);
print "</select></small>
</td></tr>

<tr>
<td class=plformtit>Year</td>
<td class=plform>
<input type=text name=year value='$year' size=4 maxlength=4>
</td>
<td class=plformtit>Beats/min</td>
<td class=plform>
<input type=text name=bpm value='$bpm' size=4 maxlength=4>
</td>
</tr>

<tr>
<td class=plformtit>Language</td>
<td class=plform>
<small><select name=lang>";
print_lang_options($lang);
print "</select></small>
</td>
<td class=plformtit>Type</td>
<td class=plform>
<small><select name=type>";
$selected = 2;


  $recset = mysql_db_query ("GiantDisc", "SELECT * FROM musictype ORDER BY id");
  print "<option value=\"\">&nbsp;</option>\n";
  while($row = mysql_fetch_array($recset)) {
    print "<option value=\"".$row["id"]."\"";
    if ($row["id"] == $selected) {print(" selected");};
    print ">";
	print $row["musictype"]."</option>\n";
  }


print "</select></small>
</td>
</tr>

<tr>
<td class=plformtit>Rating</td>
<td class=plform>
<small><select name=rating>";
$selected = 1;
  $recset = array("-", "0", "+", "++");
  print "<option value=\"\">&nbsp;</option>\n";
  while(list($key, $value) = each($recset)) {
    print "<option value=\"".$key."\"";
    if (strlen($selected)>0 && $key == $selected) {print(" selected");};
    print ">";
	print $value."</option>\n";
  }

#print_rating_options($rating);
print "</select></small>
</td>
<td class=plformtit>Source</td>
<td class=plform>
<small><select name=source>";
$selected = 1;


  $recset = mysql_db_query ("GiantDisc", "SELECT * FROM source ORDER BY id");
  print "<option value=\"\">&nbsp;</option>\n";
  while($row = mysql_fetch_array($recset)) {
    print "<option value=\"".$row["id"]."\"";
    if ($row["id"] == $selected) {print(" selected");};
    print ">";
	print $row["source"]."</option>\n";
  }


#print_source_options($source+1);
print "</select></small>
</td>
</tr>

</table>";
print "</td><td><p>&nbsp; &nbsp; &nbsp; &nbsp;<p></td><td>";
print "<input type='submit' value='Update' style='text-align: center;'>";
#print "<p><br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br></p>";
#print "<input type='submit' value='Update' style='text-align: center;'>";
print "</td></tr></table>";


# letzte cddbid auslesen
#$benutzer = "music";
#$passwort = "music";
#$db = "GiantDisc";
#$link =  mysql_connect( "localhost", $benutzer, $passwort  );
#if ( ! $link )
#    die( "Keine Verbindung zu MySQL" );
#mysql_select_db( $db, $link )
#    or die ( "Konnte Datenbank \"$db\" nicht öffnen: ".mysql_error() );

$ergebnis = mysql_db_query( "GiantDisc", "SELECT mp3file FROM tracks WHERE mp3file LIKE 'trxx%'" );
$anz_felder = mysql_num_fields( $ergebnis );
$anz_reihen = mysql_num_rows( $ergebnis );

while ( $datensatz = mysql_fetch_row( $ergebnis ) )
    {
    foreach ( $datensatz as $feld )
		  {
		  }
    }
#mysql_close( $link );

# errechnen von naechster cddbid
if ($anz_reihen == 0)
	$feld = "trxx00000000.mp3";
$feld = substr($feld, 4, 8);
$feld++;
$lang = strlen($feld);
$soll = 8 - $lang;
$y = 0;
while ($y < $soll)
	{
	$reihe[$y] = "0";
	$y++;
	}
$reihe[$y] = $feld;
$cddbid = implode("",$reihe);
#print "<br>$cddbid";

print "<hr noshade>";

print "<h2>Recording defaults</h2>";
print "<p>Take album parameters as<br>default values for tracks</p>";
print "<table>";
print "<tr><td><input type=checkbox name=def_artist checked value='true'> artist name</td>";
print "<td><input type=checkbox name=def_years checked value='true'> years</td></tr>";
print "<tr><td><input type=checkbox name=def_genres checked value='true'> genres</td>";
print "<td><input type=checkbox name=def_type checked value='true'> type</td></tr>";
print "<tr><td><input type=checkbox name=def_lang checked value='true'> language</td>";
print "<td><input type=checkbox name=def_rating checked value='true'> rating</td></tr>";
print "<tr><td></td>";
print "<td><input type=checkbox name=def_source checked value='true'> source</td></tr>";
print "</table>";

print "<input type=hidden name=cddbid value='$cddbid' size=8 maxlength=8>";
print "<input type=hidden name=schritt value='3'>";
print "<input type=hidden name=Folder value=$Folder>";
print "<input type=hidden name=file value=$file>";

print "</form>";
break;


case 3:

		$reihe[0] = $Folder;
		$reihe[1] = $file;
		$ausgabe = implode("",$reihe);
		$reihe[0] = "$htdocs/music/01/trxx";
		$reihe[1] = $cddbid;
		if (strstr($ausgabe, "mp3"))
		$reihe[2] = ".mp3";
		if (strstr($ausgabe, "ogg"))
		$reihe[2] = ".ogg";
		if (strstr($ausgabe, "flac"))
		$reihe[2] = ".flac";
		
		$sourceid = implode("",$reihe);
		copy ($ausgabe, $sourceid);
		
		if (strstr($ausgabe, "mp3"))
			$filetype = "mp3";
		if (strstr($ausgabe, "ogg"))
			$filetype = "ogg";
		if (strstr($ausgabe, "flac"))
			$filetype = "flac";


$modified = date("Y-m-d");

# Track in Datenbank einfügen
$type = $type - 1;
$source = $source - 1;
$tracknb = 1;

$CDid = $cddbid;

		$anfrage = "INSERT INTO tracks
											( artist, title, composer, genre1, genre2, year, lang, type,
											rating, length, source, sourceid, tracknb, mp3file, quality, voladjust,
											lengthfrm, startfrm, bpm, lyrics, bitrate, created, modified, backup, id )
									VALUES ( '$artist', '$title', '$composer', '$genre1', '$genre2', '$year', '$lang', '$type',
											'$rating', '0', '$source', '$CDid', '$tracknb', 'trxx$cddbid.$filetype', '0', '0',
											 '0', '0', '$bpm', NULL, '$filetype', '$modified', '$modified', NULL, NULL)";
		$ergebnis = mysql_db_query( "GiantDisc", $anfrage );

		if ( ! $ergebnis )
			die ("Insert ist fehlgeschlagen: ".mysql_error());


$artist = str_replace(" ", "+", $artist);

print "<p>Now the Track $title from the artist $artist is successful imported in the GinatDisc-database.</p>";
print "<p>To edit the Track-Title, please klick on the OK-button.</p><table><tr><td class=\"menuactive\"><a class=\"menuinactive\" href=cont_gdsel.php?showartist=on&artist=$artist&showtitle=on&title=&showgenre=on&genre=$genre1&showrating=on&rating=$rating&playtp=df&CDid=$CDid>OK</a></td></tr></table>";


break;


case 4:
break;


default:
break;
}


?>









<hr noshade>

<p>

<?php
print_footer ("record");
?>
