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

  $link =  mysqli_connect( MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB );
  if ( ! $link )
      die( "Keine Verbindung zu MySQL" );

## Variablen aus Browser-Zeile
$schritt=$_GET['schritt']; 


if ($schritt == 2)
	print_header ("fin", "", "$cgi_dir", "$char_set", $output135, $output136, $output137, $output138, "$host", "$requ");	
else
  print_header ("record", "", "$cgi_dir", "$char_set", $output135, $output136, $output137, $output138);

print "<hr noshade>";

switch ($schritt)
{
case 0:
	print "<h2>Musikalbum hochladen</h2>";
	
	print "<form action=album_upload.php?schritt=1 method=get>";
	print "<table><tr><td>";
	print "<table border=0>
		<tr>
		<td class=plformtit>Artist</td>
		<td colspan=3 class=plform>
		<input type=text name=artist value=\"$artist\" size=40 maxlength=200>
		</td></tr>
		
		<tr>
		<td class=plformtit>Album</td>
		<td colspan=3 class=plform>
		<input type=text name=album  value='$title' size=40 maxlength=200>
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
		
		#$selected = 2;
		$selected = $type + 1;
		$recset = mysqli_query ( $link, "SELECT * FROM musictype ORDER BY id");
		print "<option value=\"\">&nbsp;</option>\n";
		while($row = mysqli_fetch_array($recset)) {
			print "<option value=\"".$row["id"]."\"";
			if ($row["id"] == $selected) {print(" selected");};
			print ">";
			print $row["musictype"]."</option>\n";
		}
		#  mysql_close( $link );

		print "</select></small>
		</td>
		</tr>
		
		<tr>
		<td class=plformtit>Rating</td>

		<td class=plform>
		<small><select name=rating>";
		#$selected = $rating;
		$selected = 1;
		$recset = array("-", "0", "+", "++");
		print "<option value=\"\">&nbsp;</option>\n";
		foreach ( $recset as $key => $value) {
			print "<option value=\"".$key."\"";
			if (strlen($selected)>0 && $key == $selected) {print(" selected");};
			print ">";
			print $value."</option>\n";
		}


		print "</select></small>
		</td>
		<td class=plformtit>Source</td>
		<td class=plform>
		<small><select name=source>";
		$selected = 1;
		$recset = mysqli_query ( $link, "SELECT * FROM source ORDER BY id");
		print "<option value=\"\">&nbsp;</option>\n";
		while($row = mysqli_fetch_array($recset)) {
			print "<option value=\"".$row["id"]."\"";
			if ($row["id"] == $selected) {print(" selected");};
			print ">";
			print $row["source"]."</option>\n";
		}

		print "</select></small>
		</td>
		</tr>";
		print "</table>";
		
		print "<hr noshade>";

		print "<h2>$output026</h2>";
		print "<p>$output027</p>";
		print "<table width=100%>";
		print "<tr><td><input type=checkbox name=def_artist checked value='true'> artist name</td>";
		print "<td><input type=checkbox name=def_years checked value='true'> years</td></tr>";
		
		print "<tr><td><input type=checkbox name=def_composer checked value='true'> composer</td>
		<td><input type=checkbox name=def_type checked value='true'> type</td></tr>";
		print "<tr><td><input type=checkbox name=def_genres checked value='true'> genres</td>";
		print "<td><input type=checkbox name=def_rating checked value='true'> rating</td></tr>";
		
		print "</tr>";
		print "<tr><td><input type=checkbox name=def_lang checked value='true'> language</td>";
		print "<td><input type=checkbox name=def_source checked value='true'> source</td></tr>";
		print "<tr><td></td>";
		print "<td></td></tr>";
		
		print "</table>";
		print "</td><td> </td>";
		print "<td align=right><input type='submit' value='Update' style='text-align: center;'><br><p> </p><br>";
		print "Anzahl Tracks <small><select name=\"anz_track\"><option value=\"1\" selected>1</option>";
		for ( $n = 2; $n <= 99; $n++)
			{
			print "<option value=\"$n\">$n</option>";
			}
		print "</select></small>";
		print "</td></tr>";
	print "</table>";
	print "<input type=hidden name=schritt value='1'>";	
	print "</form>";
	
break;

case 1:
  ## Variablen aus Browser-Zeile
  $artist=$_GET['artist'];
  $album=$_GET['album'];
  $composer=$_GET['composer'];
  $genre1=$_GET['genre1'];
  $genre2=$_GET['genre2'];
  $year=$_GET['year'];
  $bpm=$_GET['bpm'];
  $lang=$_GET['lang'];
  $type=$_GET['type'];
  $rating=$_GET['rating'];
  $source=$_GET['source'];
  $def_artist=$_GET['def_artiste'];
  $def_years=$_GET['def_years'];
  $def_composer=$_GET['def_composer'];
  $def_type=$_GET['def_type'];
  $def_genres=$_GET['def_genres'];
  $def_rating=$_GET['def_rating'];
  $def_lang=$_GET['def_lang'];
  $def_source=$_GET['def_source'];
  $anz_track=$_GET['anz_track'];
  
  $aartist=$artist;
  $acomposer=$composer;
  
	print "<h2>Musikalbum hochladen</h2>";
	print "<table>
			<tr><td> </td><td>";
	print "<table border=0>
		<tr>
		<td class=plformtit>Artist</td>
		<td colspan=3 class=plform>
		$artist
		</td></tr>
		
		<tr>
		<td class=plformtit>Album</td>
		<td colspan=3 class=plform>
		$album
		</td></tr>
		
		<tr>
		<td class=plformtit>Composer</td>
		<td colspan=3 class=plform>
		$composer
		</td></tr>
		
			<table>
		<tr>
		<td class=plformtit>Genre</td>
		<td colspan=3 class=plform>";
		$ergebnis = mysqli_query ( $link, "SELECT genre FROM genre WHERE id LIKE '$genre1'" );
		while ( $datensatz = mysqli_fetch_row( $ergebnis ) )
    		{
    		foreach ( $datensatz as $feld )
		  		{
				print "$feld";
		  		}
    		}
		print "</td>
		<td> </td>
		<td colspan=3 class=plform>";
		$ergebnis = mysqli_query ( $link, "SELECT genre FROM genre WHERE id LIKE '$genre2'" );
		while ( $datensatz = mysqli_fetch_row( $ergebnis ) )
    		{
    		foreach ( $datensatz as $feld )
		  		{
				print "$feld";
		  		}
    		}
		print "</td>
		<td></td>
		</tr>
					
		<tr>
		<td class=plformtit>Year</td>
		<td colspan=3 class=plform>
		$year
		</td>
		<td> </td>
		<td class=plformtit>Beats/min</td>
		<td colspan=3 class=plform>
		$bpm
		</td>
		</tr>

		<tr>
		<td class=plformtit>Language</td>
		<td colspan=3 class=plform>";
		$ergebnis = mysqli_query ( $link, "SELECT language FROM language WHERE id LIKE '$lang'" );
		while ( $datensatz = mysqli_fetch_row( $ergebnis ) )
    		{
    		foreach ( $datensatz as $feld )
		  		{
				print "$feld";
		  		}
    		}
		print "</td>
		<td> </td>
		<td class=plformtit>Type</td>
		<td colspan=3 class=plform>";
		$ergebnis = mysqli_query ($link, "SELECT musictype FROM musictype WHERE id LIKE '$type'" );
		while ( $datensatz = mysqli_fetch_row( $ergebnis ) )
    		{
    		foreach ( $datensatz as $feld )
		  		{
				print "$feld";
		  		}
    		}
		print "</td>
		</tr>
		
		<tr>
		<td class=plformtit>Rating</td>
		<td colspan=3 class=plform>";
		$selected = $rating;
		$recset = array("-", "0", "+", "++");

		foreach ($recset as $key => $value){
			if (strlen($selected)>0 && $key == $selected) {print("$value");};			
		}
		print "</td>
		<td> </td>
		<td class=plformtit>Source</td>
		<td colspan=3 class=plform>";
		$ergebnis = mysqli_query ( $link, "SELECT source FROM source WHERE id LIKE '$source'" );
		while ( $datensatz = mysqli_fetch_row( $ergebnis ) )
    		{
    		foreach ( $datensatz as $feld )
		  		{
				print "$feld";
		  		}
    		}
		print "</td>
		</tr>
		
			</table>";
		
	print "</table>";
	print "</td><td>
		</td></tr>
		</table>";

	print "<table>
		<tr><td> </td><td>
		<table><tr><td class=\"menuactive\"><a class=\"menuinactive\" href=\"album_upload.php?schritt=0\">korrigieren</a></td></tr></table>
		</td></tr>
		</table>";		
	print "<hr>";
############################################################################################
	
	print "<form enctype=\"multipart/form-data\" action=album_upload.php?schritt=2 method=post>";

	#$n = 1;
	if ($def_artist == true)
		{$artist = $artist;}
		else
		{$artist = "";}
	if ($def_composer == true)
		{$composer = $composer;}
		else
		{$composer = "";}
	if ($def_genres == true)
		{$genre1 = $genre1;
		$genre2 = $genre2;}
		else
		{$genre1 = "";
		$genre2 = "";}
	if ($def_years == true)
		{$year = $year;}
		else
		{$year = "";}
	if ($def_lang == true)
		{$lang = $lang;}
		else
		{$lang = "";}
	if ($def_type == true)
		{$type = $type;}
		else
		{$type = "";}
	if ($def_rating == true)
		{$rating = $rating;}
		else
		{$rating = "";}
	if ($def_source == true)
		{$source = $source;}
		else
		{$source = "";}

for ( $n = 1; $n <= $anz_track; $n++)
	{
	print "<table><tr><td> </td><td>";
	print "<table border=0>
	<tr><td><b>Track $n</b></td></tr>
	<tr>
	<td class=plformtit>Artist</td>
	<td colspan=3 class=plform>
	<input type=text name='artist[$n]' value='$artist' size=40 maxlength=200>
	</td></tr>
	
	<tr>
	<td class=plformtit>Title</td>
	<td colspan=3 class=plform>
	<input type=text name=title[$n]  value='$title' size=40 maxlength=200>
	</td></tr>
	
	<tr>
	<td class=plformtit>Composer</td>
	<td colspan=3 class=plform>
	<input type=text name='composer[$n]'  value='$composer' size=40 maxlength=200>
	</td></tr>
	
	<tr>
	<td class=plformtit>Genre</td>
	<td colspan=3 class=plform>
	<small><select name='genre1[$n]'>";
	print_genre_options($genre1);
	print "</select></small>
	<small><select name='genre2[$n]'>";
	print_genre_options($genre2);
	print "</select></small>
	</td></tr>
	
	<tr>
	<td class=plformtit>Year</td>
	<td class=plform>
	<input type=text name='year[$n]' value='$year' size=4 maxlength=4>
	</td>
	<td class=plformtit>Beats/min</td>
	<td class=plform>
	<input type=text name='bpm[$n]' value='$bpm' size=4 maxlength=4>
	</td>
	</tr>
	
	<tr>
	<td class=plformtit>Language</td>
	<td class=plform>
	<small><select name='langu[$n]'>";
	print_lang_options($lang);
	print "</select></small>
	</td>
	<td class=plformtit>Type</td>
	<td class=plform>
	<small><select name='type[$n]'>";
	$selected = $type;

	  $recset = mysqli_query ( $link, "SELECT * FROM musictype ORDER BY id");
	  print "<option value=\"\">&nbsp;</option>\n";
	  while($row = mysqli_fetch_array($recset)) {
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
	<small><select name='rating[$n]'>";
		$selected = $rating;
		$recset = array("-", "0", "+", "++");
		print "<option value=\"\">&nbsp;</option>\n";
#		while(list($key, $value) = each($recset)) {
		foreach ($recset as $key => $value) {
			print "<option value=\"".$key."\"";
			if (strlen($selected)>0 && $key == $selected) {print(" selected");};
			print ">";
			print $value."</option>\n";
		}
	print "</select></small>
	</td>

	<td class=plformtit>Source</td>
	<td class=plform>
	<small><select name='source[$n]'>";
	$selected = $source;

	  $recset = mysqli_query ( $link, "SELECT * FROM source ORDER BY id");
	  print "<option value=\"\">&nbsp;</option>\n";
	  while($row = mysqli_fetch_array($recset)) {
	    print "<option value=\"".$row["id"]."\"";
	    if ($row["id"] == $selected) {print(" selected");};
	    print ">";
		print $row["source"]."</option>\n";
	  }
	print "</select></small>
	</td>

			
	</tr>	
	</table>
	
	<table>
	<tr>
	<td class=plformtit>Choose a file to upload:</td>
	<td class=plform>
	<input name='datei[]' type=\"file\" accept=\".mp3, .ogg, .flac, .opus, .m4a, audio/mp4\" size=40 />
	</td>
	</tr>	
	</table>";
	print "</td></tr>
	<tr><td> </td><td><hr></td></tr>
	</table>";
	}
########################################		

	
	print "<input type=hidden name=aartist value='$aartist'>";
	print "<input type=hidden name=aalbum value='$album'>";
	print "<input type=hidden name=acomposer value='$acomposer'>";
	print "<input type=hidden name=genre value='$genre1'>";
	print "<input type=hidden name=anz_track value='$anz_track'>";
	print "<table><tr><td> </td><td>";
	print "<input type='submit' value='Update' style='text-align: center;'><br><p> </p><br>";
	print "</td></tr></table>";

	print "</form>";	
############################################################################################		

break;

case 2:
## Variablen aus Browser-Zeile
$aartist=$_POST['aartist']; 
$aalbum=$_POST['aalbum']; 
$acomposer=$_POST['acomposer']; 
$genre=$_POST['genre']; 
$anz_track=$_POST['anz_track'];

$artist=$_POST['artist']; 
$title=$_POST['title']; 
$composer=$_POST['composer']; 
$genre1=$_POST['genre1']; 
$genre2=$_POST['genre2']; 
$year=$_POST['year']; 
$bmp=$_POST['bmp']; 
$langu=$_POST['langu']; 
$type=$_POST['type'];
$rating=$_POST['rating'];
$source=$_POST['source'];


#temporär eine Album-Tabelle und eine Track-Tabelle erzeugen
	album_temp();
	track_temp();	
	$modified = date("Y-m-d");
# letzte cddbid auslesen
$ergebnis = mysqli_query( $link, "SELECT mp3file FROM tracks WHERE mp3file LIKE 'trxx%'" );
$anz_felder = mysqli_num_fields( $ergebnis );
$anz_reihen = mysqli_num_rows( $ergebnis );

while ( $datensatz = mysqli_fetch_row( $ergebnis ) )
    {
    foreach ( $datensatz as $feld )
		  {
		  }
    }
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

# in Tabelle album_temp schreiben
print "<p><b>Album</b></p>";
print "<p>$aartist - $aalbum<br>$acomposer<br>$cddbid<br>$genre<br>   $anz_track<br>$artist[1]<br></p>";	
	$ergebnis = mysqli_query( $link, "INSERT INTO album_temp (artist, title, composer, cddbid, coverimg, covertxt, modified, genre) values('$aartist', '$aalbum', '$acomposer', '$cddbid', '', '', '$modified', '$genre')" );


	$n = 1;
	
#	$prefc = "opus";
#	$bitrate="opus";
	$sourceid = $cddbid;
	$target_path = $PATH_ABSOLUTE_mp3;
	
while ($n <= $anz_track)
	{
	print "<hr>";

	# Extension extrahieren
	$i = $n - 1;
	$Datei_Name = $_FILES['datei']['name'][$i];
	$Datei_Name = explode(".", $Datei_Name);
	$prefc = "$Datei_Name[1]";
	$bitrate="$Datei_Name[1]";	
  # Extension extrahieren Ende

  if ($year[$n] == "")
    $year[$n]="0";
  
  $quelle = intval($source[$n]);
  $quelle = $quelle - 1;  
  $ergebnis = mysqli_query( $link, "INSERT INTO tracks_temp (artist, title, composer, genre1, genre2, year,
                lang, type, rating, length, source, sourceid, tracknb, mp3file, quality, voladjust, lengthfrm, startfrm, bpm, lyrics, bitrate, created, modified)
				values('$artist[$n]', '$title[$n]', '$composer[$n]', '$genre1[$n]', '$genre2[$n]', '$year[$n]',
                '$langu[$n]', '$type[$n]', '$rating[$n]', '0', '$quelle', '$sourceid', '$n', 'trxx$cddbid.$prefc', '0', '0', '0', '0', '0', 'NULL', '$bitrate', '$modified', '$modified')" );

  $ergebnis = mysqli_query( $link, "UPDATE tracks_temp SET lyrics = NULL WHERE lyrics IS NOT NULL");
  $ergebnis = mysqli_query( $link, "UPDATE tracks_temp SET composer = NULL WHERE composer IS NOT NULL");
  $ergebnis = mysqli_query( $link, "UPDATE tracks_temp SET moreinfo = NULL WHERE moreinfo IS NOT NULL");
  $ergebnis = mysqli_query( $link, "UPDATE tracks_temp SET backup = NULL WHERE backup IS NOT NULL");

  
	print "<p><b>Track $n</b><br>$artist[$n] - $title[$n]</p>";							 	

#	move_uploaded_file($_FILES['datei']['tmp_name'], "$target_path/datei.mp3"); 
	$i = $n - 1;
	move_uploaded_file($_FILES['datei']['tmp_name'][$i], "$target_path/trxx$cddbid.$prefc");
# $cddbid erhoehen	
	$feld = $cddbid;
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
		$n++;
		}
# Ende $cddbid erhöhen

# Tabellen kopieren und temporaere loeschen
	$ergebnis = mysqli_query( $link, "INSERT INTO album SELECT * FROM album_temp" );
	$ergebnis = mysqli_query( $link, "INSERT INTO tracks (artist, title, genre1, genre2,
										year,lang, type, rating, length, source,
										sourceid, tracknb, mp3file, quality,
										voladjust,lengthfrm, startfrm, bpm, lyrics,
										bitrate, created, modified, backup)
								SELECT artist, title, genre1, genre2, year,
									lang, type, rating, length, source, sourceid,
									tracknb, mp3file, quality, voladjust,
									lengthfrm, startfrm, bpm, lyrics,
									bitrate, created, modified, backup
								FROM tracks_temp" );
$ergebnis = mysqli_query( $link, "DROP TABLE tracks_temp" );
$ergebnis = mysqli_query( $link, "DROP TABLE album_temp" );
	

break;



}
#Ende switch $schritt

mysqli_close( $link );  
?>

<hr noshade>



<?php 
print_footer ("record");
?>