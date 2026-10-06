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
require_once('getid3/getid3.php');
require_once('getid3/write.php');

## Variablen aus Browser-Zeile
$schritt=$_GET['schritt'];
if ($schritt == 3)
  {
  $cddbid=$_GET['cddbid'];
  $anz_track=$_GET['anz_track'];
  $lfn_track=$_GET['lfn_track'];
  }


#$host = getenv(HTTP_HOST);
$host = getenv('HTTP_HOST');
#$requ = getenv(REQUEST_URI);
$requ = getenv('REQUEST_URI');
$wort = strtok($requ, "?");
$a = 0;
while (is_string( $wort ) )
	{
	if ( $wort )
		{
		if ( $a == 0)
			{
			$a++;
			$requ = $wort;
			}
		}
	$wort = strtok( "?" );
	}


#  $link =  mysql_connect( MYSQL_HOST, MYSQL_USER, MYSQL_PASS );
  $link =  mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
  if ( ! $link )
      die( "Keine Verbindung zu MySQL" );
      
#temporäre Tabellen löschen
#geschieht bei CD-Schacht öffnen/schliessen	
if ($schritt < 1)
	{

#	$ergebnis = mysql_db_query( "GiantDisc", "DROP TABLE album_temp, tracks_temp" );
#	$ergebnis = mysqli_query( $link, "DROP TABLE album_temp, tracks_temp" );
   $result = mysqli_query( $link, "SHOW TABLES LIKE 'tracks_temp'");
      if (mysqli_num_rows($result) > 0) {
#        echo "Tabelle ist da.<br>";
        $ergebnis = mysqli_query( $link, "DROP TABLE tracks_temp" );
      } else {
#        echo "Tabelle ist nicht da.<br>";
      }
   $result = mysqli_query( $link, "SHOW TABLES LIKE 'album_temp'");
      if (mysqli_num_rows($result) > 0) {
#        echo "Tabelle ist da.<br>";
        $ergebnis = mysqli_query($link, "DROP TABLE album_temp" );
      } else {
#        echo "Tabelle ist nicht da.<br>";
      }	
	
#	mysql_close( $link );
	}
if ($schritt == 4)
#	print_head ("aufnahme", "$cgi_dir", "$host", "$requ");
	print_header ("startrec", "", "$cgi_dir", "$char_set", $output135, $output136, $output137, $output138, "$host", "$requ");

if ($schritt == 5)
#	print_head ("aufnahme", "$cgi_dir", "$host", "$requ");
	print_header ("aufnahme", "", "$cgi_dir", "$char_set", $output135, $output136, $output137, $output138, "$host", "$requ");

if ($schritt == 6)
#	print_head ("fin", "$cgi_dir", "$host", "$requ");
	print_header ("fin", "", "$cgi_dir", "$char_set", $output135, $output136, $output137, $output138, "$host", "$requ");	
	
if ($schritt < 4)
#	print_head ("record", "$cgi_dir", "$host", "$requ");
	print_header ("record", "", "$cgi_dir", "$char_set", $output135, $output136, $output137, $output138, "$host", "$requ");
	
print "<h2>$output053</h2>";
#######################################################################################
### read from CD
#Menue
print "<table>";
print "<tr>";
print "<td valign=top><table>";
#print "<tr><td class=\"menuactive\"><a class=\"menuinactive\" href=\"$cgi_dir/music.pl?file=$dev_cd&com=opentray&uri=http://$host$requ\">$output016</a></td></tr>";
#print "<tr><td class=\"menuactive\"><a class=\"menuinactive\" href=\"$cgi_dir/music.pl?file=$dev_cd&com=closetray&uri=http://$host$requ\">$output017</a></td></tr>";
#print "<tr><td class=\"menuactive\"><a class=\"menuinactive\" href=\"$cgi_dir/music.pl?file=$dev_cd&com=readcd&uri=http://$host$requ\">$output018</a></td></tr>";

if ($schritt == 4 || $schritt == 5 ||  $schritt == 6)
{
print "<tr><td class=\"menuinactive\"><p class=\"menuinactive\">$output016</a></td></tr>";
print "<tr><td class=\"menuinactive\"><p class=\"menuinactive\">$output017</p></td></tr>";
print "<tr><td class=\"menuinactive\"><p class=\"menuinactive\">$output018</p></td></tr>";
print "<tr><td class=\"menuinactive\"><p class=\"menuinactive\">$output020</p></td></tr>";
}
else
{
print "<tr><td class=\"menuactive\"><a class=\"menuinactive\" href=\"$cgi_dir/music.pl?file=$dev_cd&com=opentray&uri=http://$host$requ\">$output016</a></td></tr>";
if ($schritt == 3 & $sourceid != "noaudiodiskfound" & $lfn_track == $anz_track)
	{
	print "<tr><td class=\"menuinactive\"><p class=\"menuinactive\">$output017</p></td></tr>";
	print "<tr><td class=\"menuinactive\"><p class=\"menuinactive\">$output018</p></td></tr>";

	print "<tr><td class=\"menuactive\"><a class=\"menuinactive\" href=\"read_from_cd.php?schritt=4\">$output020</a></td></tr>";
#	print "<tr><td class=\"menuactive\"><a class=\"menuinactive\" href=\"$cgi_dir/music.pl?file=start&com=ripit&uri=http://$host$requ\">$output020</a></td></tr>";
	print "<tr><td class=\"menuactive\"><a class=\"menuinactive\" href=\"http://$host$requ?schritt=1&sourceid=$cddbid&anz_track=$anz_track\">$output036</a></td></tr>";
	}
else
	{
	print "<tr><td class=\"menuactive\"><a class=\"menuinactive\" href=\"$cgi_dir/music.pl?file=$dev_cd&com=closetray&uri=http://$host$requ\">$output017</a></td></tr>";
	print "<tr><td class=\"menuactive\"><a class=\"menuinactive\" href=\"$cgi_dir/music.pl?file=$dev_cd&com=readcd&uri=http://$host$requ\">$output018</a></td></tr>";
	print "<tr><td class=\"menuinactive\"><p class=\"menuinactive\">$output020</p></td></tr>";
	}
}

print "</table></td>";
#End Menue

print "<td>&nbsp; &nbsp; &nbsp;</td>";

print "<td>";


switch ($schritt)
{
case 1:

## Variablen aus Browser-Zeile
$sourceid=$_GET['sourceid'];
$anz_track=$_GET['anz_track'];

if ($sourceid == "noaudiodiskfound" or $anz_track == "0")
	{
	print "$output019";
	}
else
	{
	# Kompatibel machen
	$a = strlen($sourceid);
	$a = $a - 8;
	$sourceid = substr($sourceid,$a);

	# Wurde diese CD bereits eingelesen?
  
  
	#$ergebnis = mysql_db_query( "GiantDisc", "SELECT cddbid FROM album WHERE cddbid LIKE '$sourceid'" );
	$ergebnis = mysqli_query( $link, "SELECT artist, title, coverimg, cddbid FROM album WHERE cddbid LIKE '$sourceid'" );
	$anz_album = mysqli_num_rows($ergebnis);

#	mysql_close( $link );
	

if ($anz_album != 0)
	{
	print "<p>$output023 <b>$sourceid</b> $output024</p>";
	print "<p>$output025</p>";
	print "<table align=right>";
	print "<tr><td class=\"menuactive\"><a class=\"menuinactive\" href=\"index.php\">$output022</a></td>";
	print "<td>&nbsp; &nbsp; &nbsp;</td>";
	print "<td class=\"menuactive\"><a class=\"menuinactive\" href=\"read_from_cd.php?schritt=2&sourceid=$sourceid&anz_track=$anz_track\">$output021</a></td></tr>";

	print "</table>";

	#print $anz_album;
	}
else
	{
	#temporär eine Album-Tabelle und eine Track-Tabelle erzeugen

#$ergebnis = mysql_db_query( "GiantDisc", "SELECT cddbid FROM album_temp" );
$ergebnis = mysqli_query( $link, "SHOW TABLES LIKE 'album_temp'" );
#if ( ! $ergebnis )
if ( $ergebnis )
	{
	#prüfen ob track_temp existiert
	# hier
   $result = mysqli_query( $link, "SHOW TABLES LIKE 'tracks_temp'");
      if (mysqli_num_rows($result) > 0) {
#        echo "Tabelle ist da.<br>";
      } else {
#        echo "Tabelle ist nicht da.<br>";
        track_temp();
      }
   $result = mysqli_query( $link, "SHOW TABLES LIKE 'album_temp'");
      if (mysqli_num_rows($result) > 0) {
#        echo "Tabelle ist da.<br>";
          $var_album_temp = 1;
      } else {
#        echo "Tabelle ist nicht da.<br>";
          $var_album_temp = 0;
        album_temp();
      }	
#	print "Hallo";
#	track_temp();
#	album_temp();
	$modified = date("Y-m-d");

###  Sonderzeichen tauschen
  $artist = str_replace("'","&#39;",$artist);
  $title = str_replace("'","&#39;",$title);

if ($var_album_temp == 0)
  {	
  $ergebnis = mysqli_query( $link, "INSERT INTO album_temp (artist, title, composer, cddbid, coverimg, covertxt, modified, genre) values('', '', '', '$sourceid', '', '', '$modified', '')" );
  }

if ($var_album_temp == 0)
  {	
	$x = 1;
	while ($x <= $anz_track)
		{
#		$ergebnis = mysql_db_query( "GiantDisc", "INSERT INTO tracks_temp (artist, title, composer, genre1, genre2, year, lang, type, rating, length, source, sourceid, tracknb, mp3file, quality, voladjust, lengthfrm, startfrm, bpm, lyrics, bitrate, created, modified, backup)
#							values('aritst', 'track$x', '', '', '', 'NULL', '', '1', '1', '0', '0', '$sourceid', '$x', 'tr0x$sourceid-$x.$prefc', '', '', '', '', '', 'NULL', '', '$modified', '$modified', 'NULL')" );
#		$ergebnis = mysql_db_query( "GiantDisc", "INSERT INTO tracks_temp (artist, title, type, rating, length, source, sourceid, tracknb, mp3file, lyrics, created, modified)
#							values('aritst', 'track$x', '1', '1', '0', '0', '$sourceid', '$x', 'tr0x$sourceid-$x.$prefc', 'NULL', '$modified', '$modified')" );
		$ergebnis = mysqli_query( $link, "INSERT INTO tracks_temp (artist, title, type, rating, length, source, sourceid, tracknb, mp3file, lyrics, created, modified)
							values('aritst', 'track$x', '1', '1', '0', '0', '$sourceid', '$x', 'tr0x$sourceid-$x.$prefc', 'NULL', '$modified', '$modified')" );

		$x++;
		}
}
		
  $ergebnis = mysqli_query( $link, "UPDATE tracks_temp SET lyrics = NULL WHERE lyrics IS NOT NULL");
  $ergebnis = mysqli_query( $link, "UPDATE tracks_temp SET composer = NULL WHERE composer IS NOT NULL");
  $ergebnis = mysqli_query( $link, "UPDATE tracks_temp SET moreinfo = NULL WHERE moreinfo IS NOT NULL");
  $ergebnis = mysqli_query( $link, "UPDATE tracks_temp SET backup = NULL WHERE backup IS NOT NULL");

#mysql_close( $link );
	}
	
	read_cd_album("create");

#	print "$sourceid $anz_track";
print "<hr noshade>";
print "<h2>$output026</h2>";
print "<p>$output027</p>";
print "<table width=100%>";
print "<tr><td><input type=checkbox name=def_artist checked value='true'> artist name</td>";
print "<td><input type=checkbox name=def_years checked value='true'> years</td>";


if ($prefc == "mp3")
{
print "<td class=plformtit>MP3 Bitrate Kbit/s</td><td class=plform><small><select name=bitrate><option value=\"\">&nbsp;</option>";
print "<option value=\"1\"";
        if ($mp3_Q == "1") print " selected";
print ">64</option>";
print "<option value=\"2\"";
        if ($mp3_Q == "2") print " selected";
print ">96</option>";
print "<option value=\"3\"";
        if ($mp3_Q == "3") print " selected";
print ">112</option>";
print "<option value=\"4\"";
        if ($mp3_Q == "4") print " selected";
print ">128</option>";
print "<option value=\"5\"";
        if ($mp3_Q == "5") print " selected";
print ">160</option>";
print "<option value=\"6\"";
        if ($mp3_Q == "6") print " selected";
print ">192</option>";
print "<option value=\"7\"";
        if ($mp3_Q == "7") print " selected";
print ">256</option>";
print "<option value=\"8\"";
        if ($mp3_Q == "8") print " selected";
print ">320</option>";
print "</select></small></td></tr>";
}

if ($prefc == "ogg")
{
print "<td class=plformtit>OGG Quality (Kbit/s)</td><td class=plform><small><select name=bitrate><option value=\"\">&nbsp;</option>";
print "<option value=\"1\"";
        if ($ogg_Q == "1") print " selected";
print ">1</option>";
print "<option value=\"2\"";
        if ($ogg_Q == "2") print " selected";
print ">2</option>";
print "<option value=\"3\"";
        if ($ogg_Q == "3") print " selected";
print ">3</option>";
print "<option value=\"4\"";
        if ($ogg_Q == "4") print " selected";
print ">4</option>";
print "<option value=\"5\"";
        if ($ogg_Q == "5") print " selected";
print ">5</option>";
print "<option value=\"6\"";
        if ($ogg_Q == "6") print " selected";
print ">6</option>";
print "<option value=\"7\"";
        if ($ogg_Q == "7") print " selected";
print ">7</option>";
print "<option value=\"8\"";
        if ($ogg_Q == "8") print " selected";
print ">8</option>";
print "<option value=\"9\"";
        if ($ogg_Q == "9") print " selected";
print ">9</option>";
print "<option value=\"10\"";
        if ($ogg_Q == "10") print " selected";
print ">10</option>";
print "</select></small></td></tr>";
}

if ($prefc == "flac")
{
print "<td class=plformtit>FLAC Compression</td><td class=plform><small><select name=bitrate><option value=\"\">&nbsp;</option>";
print "<option value=\"1\"";
       if ($flac_Q == "1") print " selected";
print ">1</option>";
print "<option value=\"2\"";
       if ($flac_Q == "2") print " selected";
print ">2</option>";
print "<option value=\"3\"";
       if ($flac_Q == "3") print " selected";
print ">3</option>";
print "<option value=\"4\"";
       if ($flac_Q == "4") print " selected";
print ">4</option>";
print "<option value=\"5\"";
       if ($flac_Q == "5") print " selected";
print ">5</option>";
print "<option value=\"6\"";
       if ($flac_Q == "6") print " selected";
print ">6</option>";
print "<option value=\"7\"";
       if ($flac_Q == "7") print " selected";
print ">7</option>";
print "<option value=\"8\"";
       if ($flac_Q == "8") print " selected";
print ">8</option>";
print "</select></small></td></tr>";
}

if ($prefc == "opus")
{
print "<td class=plformtit>OPUS Quality (Kbit/s)</td><td class=plform><small><select name=bitrate><option value=\"\">&nbsp;</option>";
print "<option value=\"1\"";
       if ($opus_Q == "1") print " selected";
print ">96</option>";
print "<option value=\"2\"";
       if ($opus_Q == "2") print " selected";
print ">128</option>";
print "<option value=\"3\"";
       if ($opus_Q == "3") print " selected";
print ">256</option>";
print "<option value=\"4\"";
       if ($opus_Q == "4") print " selected";
print ">320</option>";
print "<option value=\"5\"";
       if ($opus_Q == "5") print " selected";
print ">512</option>";
print "</select></small></td></tr>";
}

if ($prefc == "m4a")
{
print "<td class=plformtit>M4A Quality (Kbit/s)</td><td class=plform><small><select name=bitrate><option value=\"\">&nbsp;</option>";
print "<option value=\"1\"";
       if ($m4a_Q == "1") print " selected";
print ">96</option>";
print "<option value=\"2\"";
       if ($m4a_Q == "2") print " selected";
print ">128</option>";
print "<option value=\"3\"";
       if ($m4a_Q == "3") print " selected";
print ">196</option>";
print "<option value=\"4\"";
       if ($m4a_Q == "4") print " selected";
print ">256</option>";
print "<option value=\"5\"";
       if ($m4a_Q == "5") print " selected";
print ">320</option>";
print "<option value=\"6\"";
       if ($m4a_Q == "6") print " selected";
print ">alac</option>";
print "</select></small></td></tr>";
}

print "<tr><td><input type=checkbox name=def_composer checked value='true'> composer</td></tr>";
print "<tr><td><input type=checkbox name=def_genres checked value='true'> genres</td>";
print "<td><input type=checkbox name=def_type checked value='true'> type</td>";

if ($gnudb == "yes")
	{
	print "<td><a href=http://gnudb.org/search/";
	print " target=\"gnudb\" >
	<img src=\"img/gnudb.gif\" height=\"72\" border=\"0\" align=\"right\">
	</a></td>";
	}

if ($freedb == "yes")
	{
	print "<td><a href=\"http://www.freedb.org/freedb_discid_check.php?discid=";
	print $sourceid;
	print "&search=search\" target=\"freedb\" >
	<img src=\"img/freedb.gif\" height=\"25\" border=\"0\" align=\"right\">
	</a></td>";
	}

if ($tracktype == "yes")
	{
	print "<td><a href=\"tracktype.php\"";
	print " target=\"tracktype\" >
	<img src=\"img/tracktype.gif\" height=\"25\" border=\"0\" align=\"right\">
	</a></td>";
	}

	print "<td><b>&nbsp; &nbsp; &nbsp; &nbsp; $sourceid</b></td>";

print "</tr>";
print "<tr><td><input type=checkbox name=def_lang checked value='true'> language</td>";
print "<td><input type=checkbox name=def_rating checked value='true'> rating</td></tr>";
print "<tr><td></td>";
print "<td><input type=checkbox name=def_source checked value='true'> source</td></tr>";
print "</table>";

print "</td><td><p>&nbsp; &nbsp; &nbsp; &nbsp;<p></td><td>";
print "<input type='submit' value='Update' style='text-align: center;'>";
print "</td></tr></table>";

print "<input type=hidden name=cddbid value='$sourceid' size=8 maxlength=8>";
print "<input type=hidden name=anz_track value='$anz_track' size=8 maxlength=2>";
print "<input type=hidden name=schritt value='3'>";
#print "<input type=hidden name=Folder value=$strSelFolder>";

print "</form>";

	}
		
	}	

break;


case 2:

## Variablen aus Browser-Zeile
$sourceid=$_GET['sourceid'];
$anz_track=$_GET['anz_track'];

track_temp();
album_temp();
$modified = date("Y-m-d");
$ergebnis = mysqli_query( $link , "INSERT INTO album_temp SELECT * FROM album WHERE cddbid = '$sourceid'" );
$ergebnis = mysqli_query( $link , "UPDATE album_temp SET modified = '$modified'" );

$ergebnis = mysqli_query( $link , "INSERT INTO tracks_temp SELECT * FROM tracks WHERE sourceid = '$sourceid'" );

for ( $a = 1; $a <= $anz_track; $a++)
	{
#	print "<p>$a</p>";
	$ergebnis = mysqli_query( $link , "SELECT  mp3file FROM tracks_temp WHERE  tracknb = '$a'" );
	while ( $datensatz = mysqli_fetch_row( $ergebnis ) )
     {
    foreach ( $datensatz as $feld )
		{
		$b = strlen($feld);
		if (strstr( $feld, ".flac"))
			$b--;				# bei flac einen mehr abziehen.
		if (strstr( $feld, ".opus"))
			$b--;				# bei opus einen mehr abziehen.
		$b = $b - 3;
		$feld = substr($feld,0,$b);
#		print "<p>$feld$prefc</p>";
		$ausgabe = mysqli_query( $link , "UPDATE tracks_temp SET mp3file = '$feld$prefc' where tracknb = '$a'" );
		}
	 }
	}

read_cd_album("overwrite");

#print "$sourceid $anz_track";
print "<hr noshade>";
print "<h2>$output026</h2>";
print "<p>$output027</p>";
print "<table width=100%>";
print "<tr><td><input type=checkbox name=def_artist value='true'> artist name</td>";
print "<td><input type=checkbox name=def_years value='true'> years</td>";

if ($prefc == "mp3")
{
print "<td class=plformtit>MP3 Bitrate Kbit/s</td><td class=plform><small><select name=bitrate><option value=\"\">&nbsp;</option>";
print "<option value=\"1\"";
        if ($mp3_Q == "1") print " selected";
print ">64</option>";
print "<option value=\"2\"";
        if ($mp3_Q == "2") print " selected";
print ">96</option>";
print "<option value=\"3\"";
        if ($mp3_Q == "3") print " selected";
print ">112</option>";
print "<option value=\"4\"";
        if ($mp3_Q == "4") print " selected";
print ">128</option>";
print "<option value=\"5\"";
        if ($mp3_Q == "5") print " selected";
print ">160</option>";
print "<option value=\"6\"";
        if ($mp3_Q == "6") print " selected";
print ">192</option>";
print "<option value=\"7\"";
        if ($mp3_Q == "7") print " selected";
print ">256</option>";
print "<option value=\"8\"";
        if ($mp3_Q == "8") print " selected";
print ">320</option>";
print "</select></small></td></tr>";
}

if ($prefc == "ogg")
{
print "<td class=plformtit>OGG Quality (Kbit/s)</td><td class=plform><small><select name=bitrate><option value=\"\">&nbsp;</option>";
print "<option value=\"1\"";
        if ($ogg_Q == "1") print " selected";
print ">1</option>";
print "<option value=\"2\"";
        if ($ogg_Q == "2") print " selected";
print ">2</option>";
print "<option value=\"3\"";
        if ($ogg_Q == "3") print " selected";
print ">3</option>";
print "<option value=\"4\"";
        if ($ogg_Q == "4") print " selected";
print ">4</option>";
print "<option value=\"5\"";
        if ($ogg_Q == "5") print " selected";
print ">5</option>";
print "<option value=\"6\"";
        if ($ogg_Q == "6") print " selected";
print ">6</option>";
print "<option value=\"7\"";
        if ($ogg_Q == "7") print " selected";
print ">7</option>";
print "<option value=\"8\"";
        if ($ogg_Q == "8") print " selected";
print ">8</option>";
print "<option value=\"9\"";
        if ($ogg_Q == "9") print " selected";
print ">9</option>";
print "<option value=\"10\"";
        if ($ogg_Q == "10") print " selected";
print ">10</option>";
print "</select></small></td></tr>";
}

if ($prefc == "flac")
{
print "<td class=plformtit>FLAC Compression</td><td class=plform><small><select name=bitrate><option value=\"\">&nbsp;</option>";
print "<option value=\"1\"";
       if ($flac_Q == "1") print " selected";
print ">1</option>";
print "<option value=\"2\"";
       if ($flac_Q == "2") print " selected";
print ">2</option>";
print "<option value=\"3\"";
       if ($flac_Q == "3") print " selected";
print ">3</option>";
print "<option value=\"4\"";
       if ($flac_Q == "4") print " selected";
print ">4</option>";
print "<option value=\"5\"";
       if ($flac_Q == "5") print " selected";
print ">5</option>";
print "<option value=\"6\"";
       if ($flac_Q == "6") print " selected";
print ">6</option>";
print "<option value=\"7\"";
       if ($flac_Q == "7") print " selected";
print ">7</option>";
print "<option value=\"8\"";
       if ($flac_Q == "8") print " selected";
print ">8</option>";
print "</select></small></td></tr>";
}

if ($prefc == "opus")
{
print "<td class=plformtit>OPUS Quality (Kbit/s)</td><td class=plform><small><select name=bitrate><option value=\"\">&nbsp;</option>";
print "<option value=\"1\"";
       if ($opus_Q == "1") print " selected";
print ">96</option>";
print "<option value=\"2\"";
       if ($opus_Q == "2") print " selected";
print ">128</option>";
print "<option value=\"3\"";
       if ($opus_Q == "3") print " selected";
print ">256</option>";
print "<option value=\"4\"";
       if ($opus_Q == "4") print " selected";
print ">320</option>";
print "<option value=\"5\"";
       if ($opus_Q == "5") print " selected";
print ">512</option>";
print "</select></small></td></tr>";
}

if ($prefc == "m4a")
{
print "<td class=plformtit>M4A Quality (Kbit/s)</td><td class=plform><small><select name=bitrate><option value=\"\">&nbsp;</option>";
print "<option value=\"1\"";
       if ($m4a_Q == "1") print " selected";
print ">96</option>";
print "<option value=\"2\"";
       if ($m4a_Q == "2") print " selected";
print ">128</option>";
print "<option value=\"3\"";
       if ($m4a_Q == "3") print " selected";
print ">196</option>";
print "<option value=\"4\"";
       if ($m4a_Q == "4") print " selected";
print ">256</option>";
print "<option value=\"5\"";
       if ($m4a_Q == "5") print " selected";
print ">320</option>";
print "<option value=\"6\"";
       if ($m4a_Q == "6") print " selected";
print ">alac</option>";
print "</select></small></td></tr>";
}

print "<tr><td><input type=checkbox name=def_composer value='true'> composer</td></tr>";
print "<tr><td><input type=checkbox name=def_genres value='true'> genres</td>";
print "<td><input type=checkbox name=def_type value='true'> type</td>";

if ($gnudb == "yes")
	{
	print "<td><a href=http://gnudb.org/search/";
	print " target=\"gnudb\" >
	<img src=\"img/gnudb.gif\" height=\"72\" border=\"0\" align=\"right\">
	</a></td>";
	}

if ($freedb == "yes")
	{
	print "<td><a href=\"http://www.freedb.org/freedb_discid_check.php?discid=";
	print $sourceid;
	print "&search=search\" target=\"freedb\" >
	<img src=\"img/freedb.gif\" height=\"25\" border=\"0\" align=\"right\">
	</a></td>";
	}

if ($tracktype == "yes")
	{
	print "<td><a href=\"tracktype.php\"";
	print " target=\"tracktype\" >
	<img src=\"img/tracktype.gif\" height=\"25\" border=\"0\" align=\"right\">
	</a></td>";
	}

	print "<td>&nbsp; &nbsp; &nbsp; &nbsp; <b>$sourceid</b></td>";

print "</tr>";

print "<tr><td><input type=checkbox name=def_lang value='true'> language</td>";
print "<td><input type=checkbox name=def_rating value='true'> rating</td></tr>";
print "<tr><td></td>";
print "<td><input type=checkbox name=def_source value='true'> source</td></tr>";
print "</table>";

print "</td><td><p>&nbsp; &nbsp; &nbsp; &nbsp;<p></td><td>";
print "<input type='submit' value='Update' style='text-align: center;'>";
print "</td></tr></table>";

print "<input type=hidden name=cddbid value='$sourceid' size=8 maxlength=8>";
print "<input type=hidden name=anz_track value='$anz_track' size=8 maxlength=2>";
print "<input type=hidden name=schritt value='3'>";

#print "<input type=hidden name=Folder value=$strSelFolder>";

print "</form>";

break;

case 3:

## Variablen aus Browser-Zeile
$artist=$_GET['artist'];
$album=$_GET['album'];
$title=$_GET['title'];
$composer=$_GET['composer'];
$genre1=$_GET['genre1'];
$genre2=$_GET['genre2'];
$year=$_GET['year'];
$bpm=$_GET['bpm'];
$lang=$_GET['lang'];
$type=$_GET['type'];
$rating=$_GET['rating'];
$source=$_GET['source'];
$def_artist=$_GET['def_artist'];
$def_years=$_GET['def_years'];
$bitrate=$_GET['bitrate'];
$def_composer=$_GET['def_composer'];
$def_genres=$_GET['def_genres'];
$def_type=$_GET['def_type'];
$def_lang=$_GET['def_lang'];
$def_rating=$_GET['def_rating'];
$def_source=$_GET['def_source'];
$cddbid=$_GET['cddbid'];
$anz_track=$_GET['anz_track'];
$lfn_track=$_GET['lfn_track'];

#print "<p>$artist";
#print "</p>";

# Sind wir das erste Mal hier?
if ($lfn_track <= 0)
	{
# ja!!!!	
	$lfn_track = 1;
	$source--;

###  Sonderzeichen tauschen
$artist = str_replace("'","&#39;",$artist);
$album = str_replace("'","&#39;",$album);

$ergebnis = mysqli_query( $link , "UPDATE album_temp SET artist = '$artist', title = '$album', genre ='$genre1'" );
	
if ($def_artist == true)
	{
	$ergebnis = mysqli_query( $link , "UPDATE tracks_temp SET artist = '$artist'" );
	}
if ($def_composer == true)
	{
	$ergebnis = mysqli_query( $link , "UPDATE tracks_temp SET composer = '$composer'" );
	}
if ($def_composer == true)
	{
	$ergebnis = mysqli_query( $link , "UPDATE album_temp SET composer = '$composer'" );
	}
if ($def_years == true)
	{
	if ($year == "")
	   $year=0;
	$ergebnis = mysqli_query( $link , "UPDATE tracks_temp SET year = '$year'" );
	}
if ($def_genres == true)
	{
	$ergebnis = mysqli_query( $link , "UPDATE tracks_temp SET genre1 = '$genre1', genre2 = '$genre2'" );
	}
if ($def_type == true)
	{
	$ergebnis = mysqli_query( $link , "UPDATE tracks_temp SET type = '$type'" );
	}
if ($def_lang == true)
	{
	$ergebnis = mysqli_query( $link , "UPDATE tracks_temp SET lang = '$lang'" );
	}
if ($def_rating == true)
	{
	$ergebnis = mysqli_query( $link , "UPDATE tracks_temp SET rating = '$rating'" );
	}
if ($def_source == true)
	{
	$ergebnis = mysqli_query( $link , "UPDATE tracks_temp SET source = '$source'" );
	}
	switch ($bitrate)
	{
	case 1:
	if ($prefc == "mp3")
		$feld = "mp3 64";
	if ($prefc == "ogg")
		$feld = "ogg 1";
	if ($prefc == "flac")
		$feld = "flac 1";
	if ($prefc == "opus")
		$feld = "opus 96";
	if ($prefc == "m4a")
		$feld = "m4a 96";		
	break;
	
	case 2:
	if ($prefc == "mp3")
		$feld = "mp3 96";
	if ($prefc == "ogg")
		$feld = "ogg 2";
	if ($prefc == "flac")
		$feld = "flac 2";
	if ($prefc == "opus")
		$feld = "opus 128";
	if ($prefc == "m4a")
		$feld = "m4a 128";	
	break;

	case 3:
	if ($prefc == "mp3")
		$feld = "mp3 112";
	if ($prefc == "ogg")
		$feld = "ogg 3";
	if ($prefc == "flac")
		$feld = "flac 3";
	if ($prefc == "opus")
		$feld = "opus 256";
	if ($prefc == "m4a")
		$feld = "m4a 196";	
	break;

	case 4:
	if ($prefc == "mp3")
		$feld = "mp3 128";
	if ($prefc == "ogg")
		$feld = "ogg 4";
	if ($prefc == "flac")
		$feld = "flac 4";
	if ($prefc == "opus")
		$feld = "opus 320";
	if ($prefc == "m4a")
		$feld = "m4a 256";	
	break;

	case 5:
	if ($prefc == "mp3")
		$feld = "mp3 160";
	if ($prefc == "ogg")
		$feld = "ogg 5";
	if ($prefc == "flac")
		$feld = "flac 5";
	if ($prefc == "opus")
		$feld = "opus 512";
	if ($prefc == "m4a")
		$feld = "m4a 320";	
	break;

	case 6:
	if ($prefc == "mp3")
		$feld = "mp3 192";
	if ($prefc == "ogg")
		$feld = "ogg 6";
	if ($prefc == "flac")
		$feld = "flac 6";
	if ($prefc == "m4a")
		$feld = "m4a alac";	
	break;

	case 7:
	if ($prefc == "mp3")
		$feld = "mp3 256";
	if ($prefc == "ogg")
		$feld = "ogg 7";
	if ($prefc == "flac")
		$feld = "flac 7";
	break;
	
	case 8:
	if ($prefc == "mp3")
		$feld = "mp3 320";
	if ($prefc == "ogg")
		$feld = "ogg 8";
	if ($prefc == "flac")
		$feld = "flac 8";
	break;

	case 9:
	if ($prefc == "ogg")
		$feld = "ogg 9";
	break;

	case 10:
	if ($prefc == "ogg")
		$feld = "ogg 10";
	break;

	}
	$ergebnis = mysqli_query( $link , "UPDATE tracks_temp SET bitrate = '$feld'" );	
	}
else
	{
# nein, wir waren schon mal da!

###  Sonderzeichen tauschen
$artist = str_replace("'","&#39;",$artist);
$title = str_replace("'","&#39;",$title);


	$source--;
	$ergebnis = mysqli_query( $link , "UPDATE tracks_temp SET artist = '$artist' where tracknb = '$lfn_track'" );
	$ergebnis = mysqli_query( $link , "UPDATE tracks_temp SET title = '$title' where tracknb = '$lfn_track'" );
	$ergebnis = mysqli_query( $link , "UPDATE tracks_temp SET composer = '$composer' where tracknb = '$lfn_track'" );
	$ergebnis = mysqli_query( $link , "UPDATE tracks_temp SET genre1 = '$genre1', genre2 = '$genre2' where tracknb = '$lfn_track'" );
	$ergebnis = mysqli_query( $link , "UPDATE tracks_temp SET year = '$year' where tracknb = '$lfn_track'" );
	$ergebnis = mysqli_query( $link , "UPDATE tracks_temp SET bpm = '$bpm' where tracknb = '$lfn_track'" );
	$ergebnis = mysqli_query( $link , "UPDATE tracks_temp SET lang = '$lang' where tracknb = '$lfn_track'" );
	$ergebnis = mysqli_query( $link , "UPDATE tracks_temp SET type = '$type' where tracknb = '$lfn_track'" );
	$ergebnis = mysqli_query( $link , "UPDATE tracks_temp SET rating = '$rating' where tracknb = '$lfn_track'" );
	$ergebnis = mysqli_query( $link , "UPDATE tracks_temp SET source = '$source' where tracknb = '$lfn_track'" );
	
	$lfn_track++;
	}


# Sind wir mit der Eingabe fertig?
if ($lfn_track > $anz_track)
	{

	$ergebnis = mysqli_query( $link , "SELECT  artist,  title FROM tracks_temp" );
	$anz_felder = mysqli_num_fields( $ergebnis );
	print "<p class=plformtit>$output030</p><hr>";
	$a = 0;
	$b = 1;
	
#touch ("/srv/www/htdocs/music/00/ripit.sh");
touch ("$tempdir/ripit.sh");
#$dateiname = "/srv/www/htdocs/music/00/ripit.sh";
$dateiname = "$tempdir/ripit.sh";
$fp = fopen( $dateiname, 'w');
fwrite( $fp, "#\n");

while ( $datensatz = mysqli_fetch_row( $ergebnis ) )
    {
    foreach ( $datensatz as $feld )
		  {
		  if ( $a == 0 )
				{
				print "<p class=plformtit>$output028 $b - $feld - ";
				if ($b < 10)
					{
					fwrite( $fp, "$ripp_path");
					if ($ripper == "cdparanoia")
						fwrite( $fp, "cdparanoia -d $dev_cd -w $b $tempdir/track0$b.wav\n");
					if ($ripper == "cdda2wav")
						fwrite( $fp, "cdda2wav -D $dev_cd -t $b $tempdir/track0$b.wav\n");
					}
				else
					{
					fwrite( $fp, "$ripp_path");
					if ($ripper == "cdparanoia")
						fwrite( $fp, "cdparanoia -d $dev_cd -w $b $tempdir/track$b.wav\n");
					if ($ripper == "cdda2wav")
						fwrite( $fp, "cdda2wav -D $dev_cd -t $b $tempdir/track$b.wav\n");
					}
				$a = $a + 1;
				}
		  else
				{
				print "$feld</p>";
				$a = 0;
				$b++;
				}
		  }
    }

$ergebnis = mysqli_query( $link , "SELECT bitrate FROM tracks_temp where tracknb = 1" );
$anz_felder = mysqli_num_fields( $ergebnis );
while ( $datensatz = mysqli_fetch_row( $ergebnis ) )
    {
    foreach ( $datensatz as $feld )
		{
		if ($prefc == "ogg")
			$bitrate = substr($feld,4);
		if ($prefc == "flac")	
			$bitrate = substr($feld,5);
		if ($prefc == "mp3")
			$bitrate = substr($feld,4);
		if ($prefc == "opus")	
			$bitrate = substr($feld,5);
    if ($prefc == "m4a")
			$bitrate = substr($feld,4);        	
		}
	}
mysqli_free_result($ergebnis);
	
$ergebnis = mysqli_query( $link , "SELECT title FROM album_temp" );
$anz_felder = mysqli_num_fields( $ergebnis );
while ( $datensatz = mysqli_fetch_row( $ergebnis ) )
    {
    foreach ( $datensatz as $feld )
		{
		$album = $feld;
		}
	}
mysqli_free_result($ergebnis);

$ergebnis = mysqli_query( $link , "SELECT artist, year, title FROM tracks_temp" );
$anz_felder = mysqli_num_fields( $ergebnis );
$a = 0;
$b = 1;
while ( $datensatz = mysqli_fetch_row( $ergebnis ) )
    {
    foreach ( $datensatz as $feld )
		  {
		  $a = $a + 1;
		  
  	 	switch ($a)
			{
			case $a == 1:
			$artist = $feld;
			break;

			case $a == 2:
			$year = $feld;
			break;
			
			case $a == 3:
			$title = $feld;
			if ($b < 10)
				{
				fwrite( $fp, "rm $mp3dir/tr0x$cddbid-$b.*\n");
				###  Sonderzeichen zurück tauschen
				$artist = str_replace("&#39;","'",$artist);
				$title = str_replace("&#39;","'",$title);

				# for ffmpeg (M4A)
				if ($prefc == "m4a")
					{
					if ($bitrate == "320")
					     {
               $bitrate= "aac -b:a 320k";
               }
					if ($bitrate == "256")
					     {
               $bitrate= "aac -b:a 256k";
               }
					if ($bitrate == "192")
					     {
               $bitrate= "aac -b:a 192k";
               }
					if ($bitrate == "128")
					     {
               $bitrate= "aac -b:a 128k";
               }
					if ($bitrate == "96")
					     {
               $bitrate= "aac -b:a 96k";
               }                                                            
#					fwrite( $fp, "ffmpeg -i $tempdir/track0$b.wav -c:a $bitrate  -metadata album=\"$album\" -metadata artist=\"$artist\" -metadata title=\"$title\" metadata date=\"$year\" --metadata track=\"$b\" $mp3dir/tr0x$cddbid-$b.m4a\n");
					fwrite( $fp, "ffmpeg -i $tempdir/track0$b.wav -c:a $bitrate -metadata album=\"$album\" -metadata artist=\"$artist\" -metadata title=\"$title\" -metadata date=\"$year\" -metadata track=\"$b\" $mp3dir/tr0x$cddbid-$b.m4a\n");
					}
# ffmpeg -i /var/www/html/music/00/track01.wav -c:a alac -metadata title="roughboys" -metadata artist="PETE TOWNSHEND" -metadata album="thebestofpetetownshend" -metadata track="1" -metadata date="1996" /var/www/html/music/00/track01_alac.m4a					
				
        # for opusenc (opus)
				if ($prefc == "opus")
					{
#					fwrite( $fp, "opusenc $tempdir/track0$b.wav -q $bitrate  -l \"$album\" -a \"$artist\" -t \"$title\" -d \"$year\" -N \"$b\" -o $mp3dir/tr0x$cddbid-$b.ogg\n");
					fwrite( $fp, "opusenc --bitrate $bitrate --album \"$album\" --artist \"$artist\" --title \"$title\" --date \"$year\" $tempdir/track0$b.wav $mp3dir/tr0x$cddbid-$b.opus\n");
					}
# opusenc --bitrate 256 --album "Goldeneye" --artist "Tina Turner" --title "Goldeneye" track01.wav track01.opus

				# for oggenc (ogg)
				if ($prefc == "ogg")
					{
					if ($id3tag == "yes")
						{
						fwrite( $fp, "oggenc $tempdir/track0$b.wav -q $bitrate  -l \"$album\" -a \"$artist\" -t \"$title\" -d \"$year\" -N \"$b\" -o $mp3dir/tr0x$cddbid-$b.ogg\n");
						}
					else
						{
						fwrite( $fp, "oggenc $tempdir/track0$b.wav -q $bitrate -o $mp3dir/tr0x$cddbid-$b.ogg\n");
						}
					}
				# for lame and notlame (mp3))
				if ($prefc == "mp3")
					{
					fwrite( $fp, "$encoder -S -h -b $bitrate $tempdir/track0$b.wav $mp3dir/tr0x$cddbid-$b.mp3\n");
					if ($id3tag == "yes")
						fwrite( $fp, "mp3info -l \"$album\" -a \"$artist\" -t \"$title\" -y \"$year\" -n \"$b\" $mp3dir/tr0x$cddbid-$b.mp3\n");
					}
				# for flac
				if ($prefc == "flac")
					{
					fwrite( $fp, "flac -$bitrate $tempdir/track0$b.wav -o $mp3dir/tr0x$cddbid-$b.flac\n");
					fwrite( $fp, "metaflac $mp3dir/tr0x$cddbid-$b.flac --set-tag=ALBUM=\"$album\"\n");
					fwrite( $fp, "metaflac $mp3dir/tr0x$cddbid-$b.flac --set-tag=ARTIST=\"$artist\"\n");
					fwrite( $fp, "metaflac $mp3dir/tr0x$cddbid-$b.flac --set-tag=TITLE=\"$title\"\n");
					fwrite( $fp, "metaflac $mp3dir/tr0x$cddbid-$b.flac --set-tag=YEAR=\"$year\"\n");
					fwrite( $fp, "metaflac $mp3dir/tr0x$cddbid-$b.flac --set-tag=TRACKNUMBER=\"$b\"\n");
					}
				fwrite( $fp, "chmod 666 $mp3dir/tr0x$cddbid-$b.*\n");
        fwrite( $fp, "chown nobody:nogroup $mp3dir/tr0x$cddbid-$b.*\n");	
				fwrite( $fp, "rm $tempdir/track0$b.wav\n");
#				fwrite( $fp, "rm /home/music/00/track0$b.inf\n");
#				fwrite( $fp, "rm /home/music/00/track0$b.cddb\n");
				}
			else
				{
				fwrite( $fp, "rm $mp3dir/tr0x$cddbid-$b.*\n");

				# for ffmpeg (M4A)
				if ($prefc == "m4a")
					{
#					fwrite( $fp, "ffmpeg -i $tempdir/track$b.wav -c:a $bitrate  -metadata album=\"$album\" -metadata artist=\"$artist\" -metadata title=\"$title\" metadata date=\"$year\" --metadata track=\"$b\" $mp3dir/tr0x$cddbid-$b.m4a\n");
					fwrite( $fp, "ffmpeg -i $tempdir/track$b.wav -c:a $bitrate -metadata album=\"$album\" -metadata artist=\"$artist\" -metadata title=\"$title\" -metadata date=\"$year\" -metadata track=\"$b\" $mp3dir/tr0x$cddbid-$b.m4a\n");
					}
        # for opusenc (opus)
				if ($prefc == "opus")
					{
#					fwrite( $fp, "opusenc $tempdir/track$b.wav -q $bitrate  -l \"$album\" -a \"$artist\" -t \"$title\" -d \"$year\" -N \"$b\" -o $mp3dir/tr0x$cddbid-$b.ogg\n");
					fwrite( $fp, "opusenc --bitrate $bitrate --album \"$album\" --artist \"$artist\" --title \"$title\" --date \"$year\" $tempdir/track$b.wav $mp3dir/tr0x$cddbid-$b.opus\n");
					}
                    				
				# for oggenc (ogg)
				if ($prefc == "ogg")
					{
					if ($id3tag == "yes")
						{
						fwrite( $fp, "oggenc $tempdir/track$b.wav -q $bitrate  -l \"$album\" -a \"$artist\" -t \"$title\" -d \"$year\" -N \"$b\" -o $mp3dir/tr0x$cddbid-$b.ogg\n");
						}
					else
						{
						fwrite( $fp, "oggenc $tempdir/track$b.wav -q $bitrate -o $mp3dir/tr0x$cddbid-$b.ogg\n");
						}
					}
				# for lame and notlame (mp3))	
				if ($prefc == "mp3")
					{
					fwrite( $fp, "$encoder -S -h -b $bitrate $tempdir/track$b.wav $mp3dir/tr0x$cddbid-$b.mp3\n");
					if ($id3tag == "yes")
						fwrite( $fp, "mp3info -l \"$album\" -a \"$artist\" -t \"$title\" -y \"$year\" -n \"$b\" $mp3dir/tr0x$cddbid-$b.mp3\n");
					}
				# for flac
				if ($prefc == "flac")
					{
					fwrite( $fp, "flac -$bitrate $tempdir/track$b.wav -o $mp3dir/tr0x$cddbid-$b.flac\n");
					fwrite( $fp, "metaflac $mp3dir/tr0x$cddbid-$b.flac --set-tag=ALBUM=\"$album\"\n");
					fwrite( $fp, "metaflac $mp3dir/tr0x$cddbid-$b.flac --set-tag=ARTIST=\"$artist\"\n");
					fwrite( $fp, "metaflac $mp3dir/tr0x$cddbid-$b.flac --set-tag=TITLE=\"$title\"\n");
					fwrite( $fp, "metaflac $mp3dir/tr0x$cddbid-$b.flac --set-tag=YEAR=\"$year\"\n");
					fwrite( $fp, "metaflac $mp3dir/tr0x$cddbid-$b.flac --set-tag=TRACKNUMBER=\"$b\"\n");
					}
				fwrite( $fp, "chmod 666 $mp3dir/tr0x$cddbid-$b.*\n");
        fwrite( $fp, "chown nobody:nogroup $mp3dir/tr0x$cddbid-$b.*\n");
				fwrite( $fp, "rm $tempdir/track$b.wav\n");
#				fwrite( $fp, "rm /home/music/00/track$b.inf\n");
#				fwrite( $fp, "rm /home/music/00/track$b.cddb\n");
				}
				$a = 0;
				$b++;
			break;
			}
		}
    }
mysqli_free_result($ergebnis);
fclose( $fp );	
	}
else
	{
	print "<p class=plformtit>$output028 $lfn_track $output029 $cddbid</p>";
	read_cd_track($lfn_track);

	print "</td><td><p>&nbsp; &nbsp; &nbsp; &nbsp;<p></td><td>";
	print "<input type='submit' value='Update' style='text-align: center;'>";
	print "<input type=hidden name=cddbid value='$cddbid' size=8 maxlength=8>";
	print "<input type=hidden name=anz_track value='$anz_track' size=8 maxlength=2>";
	print "<input type=hidden name=lfn_track value='$lfn_track' size=8 maxlength=2>";
	print "<input type=hidden name=schritt value='3'>";
	#print "<input type=hidden name=Folder value=$strSelFolder>";
	print "</form>";
	print "</td></tr></table>";
	}
	
#	print "<p>Artist = $artist<br>
#	Album = $album<br>
#	Titel = $title<br>
#	Composer = $composer<br>
#	Genre1 = $genre1<br>
#	Genre2 = $genre2<br>
#	Year = $year<br>
#	Beats/Min = $bpm<br>
#	Language = $lang<br>
#	Type = $type<br>
#	Rating = $rating<br>
#	Source = $source<br>
#	Schritt = $schritt<br>
#	Bitrate = $bitrate<br>
#	BoxArtist = $def_artist<br>
#	BoxYear = $def_years<br>
#	BoxGenre = $def_genres<br>
#	BoxType = $def_type<br>
#	BoxLanguage = $def_lang<br>
#	BoxRating = $def_rating<br>
#	BoxSource = $def_source<br>
#	cddbid = $cddbid<br>
#	anz_track = $anz_track</p>";

#	print "<p>lfn_track = $lfn_track</p>";


break;


case 4:
#temporaere Album-Tabelle und Track-Tabelle kopieren/updaten
$ergebnis = mysqli_query( $link , "SELECT cddbid FROM album_temp" );
$anz_felder = mysqli_num_fields( $ergebnis );
while ( $datensatz = mysqli_fetch_row( $ergebnis ) )
	{
	foreach ( $datensatz as $feld )
		{
		print "$feld";
		$sourceid = $feld;
		}
	}
	
$ergebnis = mysqli_query( $link , "SELECT cddbid FROM album WHERE cddbid LIKE '$sourceid'" );
$anz_reihen = mysqli_num_rows( $ergebnis );

#print "<br>";
#print $anz_reihen;
#print "<br>";


if ($anz_reihen == 0)
	{
	$ergebnis = mysqli_query( $link , "INSERT INTO album SELECT * FROM album_temp" );

	$ergebnis = mysqli_query( $link , "INSERT INTO tracks (artist, title, genre1, genre2,
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
	}
else
	{
# update tracks
	$ergebnis = mysqli_query( $link , "SELECT * FROM tracks_temp" );
	$anz_track = mysqli_num_rows( $ergebnis );
	
	$a = 1;
	while ( $anz_track >= $a)
	{
	$ergebnis = mysqli_query( $link , "SELECT  artist, title, genre1, genre2, year, lang,
	 type, rating, length, source, bpm, mp3file, bitrate, created, modified 
	 FROM tracks_temp WHERE tracknb = $a" );
	 
	$b = 0; 
	while ( $datensatz = mysqli_fetch_row( $ergebnis ) )
		{
		foreach ( $datensatz as $feld )
			{
			$b++;
			switch ($b)
				{
				case 1:
				$artist = $feld;
				break;
				
				case 2;
				$title = $feld;
				break;

				case 3;
				$genre1 = $feld;
				break;

				case 4;
				$genre2 = $feld;
				break;

				case 5;
				$year = $feld;
				break;

				case 6;
				$lang = $feld;
				break;

				case 7;
				$type = $feld;
				break;

				case 8;
				$rating = $feld;
				break;

				case 9;
				$length = $feld;
				$length = 0;
				break;

				case 10;
				$source = $feld;
				break;

				case 11;
				$bpm = $feld;
				break;

				case 12;
				$mp3file = $feld;
				break;
 
				case 13;
				$bitrate = $feld;
				break;

				case 14;
				$created = $feld;
				break;

				case 15;
				$modified = $feld;
				break;
				
				default:
				break;
				}
			}
		} 

#print "<br>";
#print "$artist<br>";	
#print "$title<br>";
#print "$genre1<br>";
#print "$genre2<br>";
#print "$year<br>";
#print "$lang<br>";
#print "$type<br>";
#print "$rating<br>";
#print "$length<br>";
#print "$source<br>";
#print "$bpm<br>";
#print "$bitrate<br>";
#print "$created<br>";
#print "$modified<br>";
#print "$anz_track<br>";
#print "$a<br>";

$ergebnis = mysqli_query ( $link , "UPDATE tracks SET "
							."artist = \"$artist\", "
							."title = \"$title\", "
							."genre1 = \"$genre1\", "
							."genre2 = \"$genre2\", "
							."year = \"$year\", "
							."lang = \"$lang\", "
							."type = \"$type\", "
							."rating = \"$rating\", "
							."length = \"$length\", "
							."source = \"$source\", "
							."bpm = \"$bpm\", "
							."mp3file = \"$mp3file\", "
							."bitrate = \"$bitrate\", "
							."created = \"$created\", "
							."modified = CURDATE() "
							."WHERE sourceid = '$sourceid' AND  tracknb = '$a'");
							
	$a++;
	}
	}
	
# update album
	$b = 0; 
	$ergebnis = mysqli_query( $link , "SELECT artist, title, genre 
		FROM album_temp" );
	while ( $datensatz = mysqli_fetch_row( $ergebnis ) )
		{
		foreach ( $datensatz as $feld )
			{
			$b++;
			switch ($b)
				{
				case 1:
				$artist = $feld;
				break;
				
				case 2;
				$title = $feld;
				break;

				case 3;
				$genre = $feld;
				break;

				default:
				break;
				}
			}
		}

#print "<br>";
#print "$artist<br>";	
#print "$title<br>";
#print "$genre<br>";

$ergebnis = mysqli_query ( $link , "UPDATE album SET "
							."artist = \"$artist\", "
							."title = \"$title\", "
							."modified = CURDATE(), "
							."genre = \"$genre\" "
							."WHERE cddbid = '$sourceid'");


#exec("/var/www/html/music/00/ripit.sh > /dev/null &");
exec("$htdocs/music/00/ripit.sh > /dev/null &");

break;


case 5:

$ergebnis = mysqli_query( $link , "SELECT  artist,  title FROM tracks_temp" );
$anz_felder = mysqli_num_fields( $ergebnis );
print "<p class=plformtit>$output030</p><hr>";
print "<table border=1>";
print "<tr><td>$output031</td><td>$output032</td><td>$output033</td><td>$output034</td><td>$output035</td></tr>";

# welche Datei wird eingelesen?
#$dateiname = "/var/www/html/music/00/rip.txt";
$dateiname = "$htdocs/music/00/rip.txt";
$fp = fopen( $dateiname, "r" ) or die ("Konnte $dateiname nicht oeffnen");
while ( ! feof( $fp))
	{
	$zeile = fgets($fp, 1024);
#	print "$zeile<br>";
#	if ( strstr( $zeile, "track") & strstr( $zeile, "cdparanoia") )
#	if ( strpos( $zeile, "track") !== false AND strpos( $zeile, "cdparanoia") !== false )
#	if ( strstr( $zeile, "track") & strstr( $zeile, "cdda2wav") )
#	if ( (strstr( $zeile, "track") & strstr( $zeile, "cdda2wav")) or (strstr( $zeile, "track") & strstr( $zeile, "cdparanoia")) )
	if ( (strpos( $zeile, "track") !== false AND strpos( $zeile, "cdda2wav") !== false) or (strpos( $zeile, "track") !== false AND strpos( $zeile, "cdparanoia") !== false) )
		{
#		print "gefunden";
		$pos = strpos( $zeile, "track")+5;
		$c = substr ($zeile, $pos, 2);
		$c = $c * 1;
#		print "$c";
		}
	}
fclose( $fp );

# welche Datei wird codiert?
$f = 0;
#$dateiname = "/var/www/html/music/00/rip.txt";
$dateiname = "$htdocs/music/00/rip.txt";
$fp = fopen( $dateiname, "r" ) or die ("Konnte $dateiname nicht ï¿½ffnen");
while ( ! feof( $fp))
	{
	$zeile = fgets($fp, 1024);
#	print "$zeile<br>";
#	if ( strstr( $zeile, "track") & strstr( $zeile, "lame") or strstr( $zeile, "track") & strstr( $zeile, "oggenc")  or strstr( $zeile, "track") & strstr( $zeile, "flac"))
	if ( strpos( $zeile, "track") !== false AND strpos( $zeile, "lame") !== false OR strpos( $zeile, "track") !== false AND strpos( $zeile, "oggenc") !== false OR strpos( $zeile, "track") !== false AND strpos( $zeile, "opusenc") !== false OR strpos( $zeile, "track") !== false AND strpos( $zeile, "flac") !== false OR strpos( $zeile, "track") !== false AND strpos( $zeile, "ffmpeg") !== false)
		{
		$f = 1;
#		print "gefunden";
		$pos = strpos( $zeile, "track")+5;
		$d = substr ($zeile, $pos, 2);
		$d = $d * 1;
#		print "$d";
		}
	}
fclose( $fp );
	
$a = 0;
$b = 1;
while ( $datensatz = mysqli_fetch_row( $ergebnis ) )
   {
   foreach ( $datensatz as $feld )
	  {
	  if ( $a == 0 )
			{
			print "<tr><td align=center><p><small>$b</small></p></td><td><p><small>$feld</small></p></td>";
			$a = $a + 1;
			}
	  else
			{
			print "<td><p><small>$feld</small></p></td>";
			if ($c > $b or $c <= 0)
				{
				print "<td align=center><img src=\"img/icon_activate.gif\"></td>";
				if ( ($d > $b or $d <= 0) && $f == 1)
					{
					print "<td align=center><img src=\"img/icon_activate.gif\"></td></tr>";
					}
				else	
					{
					if ($b == $d)
						print "<td align=center><img src=\"img/zahnrad.gif\" height=\"21\"></td></tr>";
					else
						print "<td>&nbsp;</td></tr>";
					}
				}
			else
				{	
				if ($b == $c)
					{
					print "<td align=center><img src=\"img/disc.gif\"></td>";
					print "<td>&nbsp;</td></tr>";
					}
				else
					{
					print "<td>&nbsp;</td>";
					print "<td>&nbsp;</td></tr>";
					}
				}
			$a = 0;
			$b++;
			}
	  }
   }
#mysql_close( $link );
print "</table>";

break;

case 6:
# Hier id3tag

#########################################
# PHP-Version
$PHPVersion = phpversion();
$a = 0;
$teil = strtok ( $PHPVersion, "." );
while ($teil) {
	$ver[$a] = $teil;
	#print "$ver[$a]<br>";
	$a++;
	$teil = strtok (".");
}

if ( (int)$ver[0] > 5 )
	#print "richtige Hauptversion";
	$phpver = true;
else
{
if ( (int)$ver[0] < 5 )
	#print "falsche Version";
	$phpver = false;	
else
{
if ( (int)$ver[1] > 0 )
	#print "richtige Hauptversion";
	$phpver = true;	
else
{
if ( (int)$ver[2] >= 5 )
	#print "richtige Hauptversion";
	$phpver = true;	
}}}
####################

if ($phpver == true AND $id3tag == "no")
{
$ergebnis = mysqli_query( $link , "SELECT title FROM album_temp" );
$anz_felder = mysqli_num_fields( $ergebnis );
while ( $datensatz = mysqli_fetch_row( $ergebnis ) )
  {
  foreach ( $datensatz as $feld )
    {
    $album = $feld;
    }
  }


$ergebnis = mysqli_query( $link , "SELECT artist, title, year, tracknb, mp3file from tracks_temp ORDER BY tracknb" );
$anz_reihen = mysqli_num_rows ( $ergebnis );
#print "Es sind $anz_reihen Zeilen in der Tabelle.<br>";
$a = 1;
while ( $datensatz = mysqli_fetch_row( $ergebnis ) )
  {
  foreach ( $datensatz as $feld )
  #$a = $a + 1;
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

		case $a == 3:
		$year = $feld;
		$a++;
		break;
          
		case $a == 4:
		$tracknb = $feld;
		$a++;
		break;

		case $a == 5:
		$mp3file = $feld;
		print " <br>";
		print "$album<br>";
		print "$artist<br>";
		print "$title<br>";
		print "$year<br>";
    print "$tracknb<br>";
    print "$mp3file<br>";
    print " <br>";    		
		$a = 1;

# MP3 Tag schreiben
if ( strstr($mp3file, ".mp3"))
  {
  $TaggingFormat = 'UTF-8';
  // Initialize getID3 engine
  $getID3 = new getID3;
  $getID3->setOption(array('encoding'=>$TaggingFormat));

  // Initialize getID3 tag-writing module
  $tagwriter = new getid3_writetags;
  //$tagwriter->filename = '/path/to/file.mp3';
  $tagwriter->filename       = "$PATH_ABSOLUTE_mp3/$mp3file";

  $tagwriter->tagformats = array('id3v1', 'id3v2.3');
  //$tagwriter->tagformats = array('id3v2.3');
  //$tagwriter->tagformats = array('id3v1');
  // set various options (optional)
  $tagwriter->overwrite_tags = true;
  $tagwriter->tag_encoding   = $TaggingFormat;
  $tagwriter->remove_other_tags = true;

# Sonderzeichen zurücktauschen
$title = str_replace("&#39;","'",$title);
$artist = str_replace("&#39;","'",$artist);
$album = str_replace("&#39;","'",$album);

###  Sonderzeichen tauschen
#$artist = str_replace("ä","ae",$artist);
#$title = str_replace("ä","ae",$title);

  // populate data array
  $TagData = array(
	'title'   => array($title),
	'artist'  => array($artist),
	'album'   => array($album),
	'year'    => array($year),
	'genre'   => array(''),
	'comment' => array(''),
	'track'   => array($tracknb),
    );
  
  $tagwriter->tag_data = $TagData;
  // write tags
  if ($tagwriter->WriteTags()) {
	echo 'Successfully wrote tags<br>';
	 if (!empty($tagwriter->warnings)) {
	   echo 'There were some warnings:<br>'.implode('<br><br>', $tagwriter->warnings);
	   }
  } else {
	   echo 'Failed to write tags!<br>'.implode('<br><br>', $tagwriter->errors);
  }
    }
# OGG Tag/comment schreiben
if ( strstr($mp3file, ".ogg"))
  {
  $TaggingFormat = 'UTF-8';

  // Initialize getID3 engine
  $getID3 = new getID3;
  $getID3->setOption(array('encoding'=>$TaggingFormat));

  // Initialize getID3 tag-writing module
  $tagwriter = new getid3_writetags;
  //$tagwriter->filename = '/path/to/file.mp3';
  $tagwriter->filename       = "$PATH_ABSOLUTE_mp3/$mp3file";

  //$tagwriter->tagformats = array('id3v1', 'id3v2.3');
  //$tagwriter->tagformats = array('id3v2.3');
  //$tagwriter->tagformats = array('id3v1');
  $tagwriter->tagformats = array('vorbiscomment');

  // set various options (optional)
  $tagwriter->overwrite_tags = true;
  $tagwriter->tag_encoding   = $TaggingFormat;
  $tagwriter->remove_other_tags = true;

  // populate data array
  $TagData = array(
	'title'   => array($title),
	'artist'  => array($artist),
	'album'   => array($album),
	'date'    => array($year),
	'genre'   => array(''),
	'comment' => array(''),
	'track'   => array($tracknb),
    );
  $tagwriter->tag_data = $TagData;
  // write tags
  if ($tagwriter->WriteTags()) {
	echo 'Successfully wrote tags<br>';
	 if (!empty($tagwriter->warnings)) {
		echo 'There were some warnings:<br>'.implode('<br><br>', $tagwriter->warnings);
	 }
  } else {
	echo 'Failed to write tags!<br>'.implode('<br><br>', $tagwriter->errors);
  }
# FLAC Tag/comment schreiben
if ( strstr($mp3file, ".flac"))
  {
  $TaggingFormat = 'UTF-8';

  // Initialize getID3 engine
  $getID3 = new getID3;
  $getID3->setOption(array('encoding'=>$TaggingFormat));

  // Initialize getID3 tag-writing module
  $tagwriter = new getid3_writetags;
  //$tagwriter->filename = '/path/to/file.mp3';
  $tagwriter->filename       = "$PATH_ABSOLUTE_mp3/$mp3file";

  //$tagwriter->tagformats = array('id3v1', 'id3v2.3');
  //$tagwriter->tagformats = array('id3v2.3');
  //$tagwriter->tagformats = array('id3v1');
  $tagwriter->tagformats = array('metaflac');

  // set various options (optional)
  $tagwriter->overwrite_tags = true;
  $tagwriter->tag_encoding   = $TaggingFormat;
  $tagwriter->remove_other_tags = true;  

  // populate data array
  $TagData = array(
	'title'   => array($title),
	'artist'  => array($artist),
	'album'   => array($album),
	'year'    => array($year),
	'genre'   => array(''),
	'comment' => array(''),
	'track'   => array($tracknb),
    );
  $tagwriter->tag_data = $TagData;
  
  // write tags
  if ($tagwriter->WriteTags()) {
	 echo 'Successfully wrote tags<br>';
	 if (!empty($tagwriter->warnings)) {
		  echo 'There were some warnings:<br>'.implode('<br><br>', $tagwriter->warnings);
	 }
  } else {
	   echo 'Failed to write tags!<br>'.implode('<br><br>', $tagwriter->errors);
    }

  }
# End Flac schreiben

  }
		break;

		default:
		break;


  }



  }
} #Ende pfpver == true

$ergebnis = mysqli_query( $link , "DROP TABLE album_temp, tracks_temp" );


break;

}

print "</td>";

print "</tr></table>";

  mysqli_close( $link );  
?>








<?php
print_footer ("record");
?>
