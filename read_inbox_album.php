<?php

include "control_web.inc";


print_header ("record", "", "$cgi_dir", "$host", "$requ");
#print_head ("record", "record", "record", "record");


#######################################################################################
### read inbox album

switch ($schritt)
{
case 1:
#Infobox ausgeben
print "<script language=JavaScript>
<!--
 alert('$output014');
//-->
</script>";

$strSelFolder = "$htdocs/music/inbox/albums/"; 	//  -> Rootvolume


if (strcmp($Folder,$strSelFolder) > 0)
	{
	$strSelFolder = $Folder;
	}

read_inbox_albums ($strSelFolder, $schritt);
break;


case 2:
#nicht leere Album-Verzeichnisse anzeigen
$strSelFolder = $Folder;
read_inbox_albums ($strSelFolder, $schritt);
break;


case 3:
#Albumsformular anzeigen
$strSelFolder = $Folder;
read_inbox_albums ($strSelFolder, $schritt);
break;

case 4:
#

$modified = date("Y-m-d");

# Album in Datenbank einfügen
$anfrage = "INSERT INTO album ( artist, title, cddbid, coverimg, covertxt, modified, genre )
								VALUES ( '$artist', '$album', '$cddbid', NULL, NULL,
										'$modified', '$genre1')";
$ergebnis = mysql_db_query("GiantDisc", $anfrage );

if ( ! $ergebnis )
	die ("Insert ist fehlgeschlagen: ".mysql_error());


# Track's in Datenbank einfügen
$type = $type - 1;
$source = $source - 1;
$tracknb = 1;
$CDid = $cddbid;
$cdir = @dir($Folder);
$cdir->rewind();
while ($entry = $cdir->read()) {
   if (!is_dir($entry)) {
   
		$reihe[0] = $Folder;
		$reihe[1] = "/";
		$reihe[2] = $entry;
		$ausgabe = implode("",$reihe);
		$reihe[0] = "$htdocs/music/01/trxx";
		$reihe[1] = $cddbid;
		if (strstr($ausgabe, "mp3"))
		$reihe[2] = ".mp3";
		if (strstr($ausgabe, "ogg"))
		$reihe[2] = ".ogg";
		if (strstr($ausgabe, "flac"))
		$reihe[2] = ".flac";
   
   
		if (strstr($ausgabe, "mp3"))
			$filetype = "mp3";
		if (strstr($ausgabe, "ogg"))
			$filetype = "ogg";
		if (strstr($ausgabe, "flac"))
			$filetype = "flac";
			
		$anfrage = "INSERT INTO tracks
											( artist, title, genre1, genre2, year, lang, type,
											rating, length, source, sourceid, tracknb, mp3file, quality, voladjust,
 											lengthfrm, startfrm, bpm, lyrics, bitrate, created, modified, backup, id )
									VALUES ( '$artist', '$entry', '$genre1', '$genre2', '$year', '$lang', '$type',
											'$rating', '0', '$source', '$CDid', '$tracknb', 'trxx$cddbid.$filetype', '0', '0',
											 '0', '0', '$bpm', NULL, 'mp3', '$modified', '$modified', NULL, NULL)";
		$ergebnis = mysql_query( $anfrage );

		if ( ! $ergebnis )
			die ("Insert ist fehlgeschlagen: ".mysql_error());

	
		$sourceid = implode("",$reihe);
		copy ($ausgabe, $sourceid);
#		chown ($sourceid, "music:users");

		$tracknb++;
		$cddbid++;

		$laenge = strlen($cddbid);
		$soll = 8 - $laenge;
		$y = 0;
		while ($y < $soll)
			{
			$letter[$y] = "0";
			$y++;
			}
		$x = 0;
		while ($x < $laenge)
			{
			$letter[$y] = substr($cddbid, $x, 1);
			$x++;
			$y++;
			}
		$cddbid = implode("",$letter);
	}
}
$cdir->close;
#mysql_close( $link );

$artist = str_replace(" ", "+", $artist);
print $artist;
print "<p>Now the Album $album from the artist $artist is successful imported in the GinatDisc-database.</p>";
print "<p>To edit the Track-Title, please klick on the OK-button.</p><table><tr><td class=\"menuactive\"><a class=\"menuinactive\" href=cont_gdsel.php?showartist=on&artist=$artist&showtitle=on&title=&showgenre=on&genre=$genre1&showrating=on&rating=$rating&playtp=df&CDid=$CDid>OK</a></td></tr></table>";

#$strSelFolder = $Folder;
#read_inbox_albums ($strSelFolder, $schritt);
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
