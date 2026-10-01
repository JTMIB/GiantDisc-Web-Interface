<?php

include "gdweb-settings.php";
include "setting.inc";

##############################################################################

# the cgi-bin or perl directroy / das cgi-bin oder Perl Verzeichnis
# /usr/local/httpd/cgi-bin/
#
$cgi_dir = "/cgi-bin";
#$cgi_dir = "/cgi-perl";
#$cgi_dir = "/perl";

#phpmyadmin
$phpmyadmin="phpMyAdmin";

# mp3 - encoder
#$encoder = "notlame";
$encoder = "lame";

# ogg - encoder (yes or no)
$oggenc = "yes";

# flac - encoder (yes or no)
$flacenc =  "yes";

# opus - encoder (yes or no)
$opusenc =  "yes";

# ffmpeg - encoder (yes or no)
$ffmpeg =  "yes";

# cdinfo is available (yes or no)
# if no, then cdda2wav must be available
$cdinfo = "yes";

$ripper = "cdparanoia";
#$ripper = "cdda2wav";
# the path for the ripper / der Pfad für den Ripper
$ripp_path = "/usr/bin/";
#$ripp_path = "/usr/local/bin/";

#rip.txt
$riptxt = "rip.txt";

# open www.gnudb.org (yes or no)
$gnudb = "yes";

# open www.freedb.org (yes or no)
$freedb = "yes";

# open www.TrackType.org (yes or no)
$tracktype = "no";

# prefered format
#$prefc = "mp3";
#$prefc = "ogg";
#$prefc = "flac";
#$prefc = "opus";

# write ID3tag when encoding / schreibe ID3tag wenn codiert wird
# if your PHP-Version is higher then 5.0.5 please set it to no
# wenn deine PHP-Version hoeher ist als 5.0.5 dann setzte bitte auf no (nein)
# es wird dann das Modul getID3 verwendet
# (yes or no)
$id3tag = "no";

# XSPF-Playlist / XSPF-Playliste fuer VLC-Player
$xspf = "yes";

# GiantDisc - bash-player
# Only makes sense if the player and the database/web server are the same device.
# Macht nur dann Sinn, wenn der Player und die Datenbank/Webserver das gleiche Gerät sind.
$bashplayer = "no";

#which bashplayer? / welcher bashplayer?
#$b_player = "mpg123";
$b_player = "mplayer";

#shutdown_enable per Browser herunterfahren
$shutdown_enable = "yes";

# mysql-login-data
define('MYSQL_HOST','localhost');
define('MYSQL_USER','music');
define('MYSQL_PASS','music');
define('MYSQL_DB','GiantDisc');

#temporary directory / temporaeres Verzeichnis
# OpenSuSE
#define('PATH_ABSOLUTE_TEMP','/srv/www/htdocs/music/00');
#$tempdir = "/srv/www/htdocs/music/00";
# debian/Ubuntu
define('PATH_ABSOLUTE_TEMP','/var/www/html/music/00');
$tempdir = "/var/www/html/music/00";
# Synology
#define('PATH_ABSOLUTE_TEMP','/volume1/web/music/00');
#$tempdir = "/volume1/web/music/00";

#MP3 directory / Verzeichnis fuer MP3
# OpenSuSE
#$PATH_ABSOLUTE_mp3 = '/srv/www/htdocs/music/01';
#$mp3dir = "/srv/www/htdocs/music/01";
# debian/Ubuntu
$PATH_ABSOLUTE_mp3 = '/var/www/html/music/01';
$mp3dir = "/var/www/html/music/01";
# Synology
#$PATH_ABSOLUTE_mp3 = '/volume1/web/music/01';
#$mp3dir = "/volume1/web/music/01";

#cover directory / Verzeichnis fuer CD-cover
# OpenSuSE
#define('PATH_ABSOLUTE_IMAGE','/srv/www/htdocs/music/02');
#$imagedir = "/srv/www/htdocs/music/02";
# debian/Ubuntu
define('PATH_ABSOLUTE_IMAGE','/var/www/html/music/02');
$imagedir = "/var/www/html/music/02";
# Synology
#define('PATH_ABSOLUTE_IMAGE','/volume1/web/music/02');
#$imagedir = "/volume1/web/music/02";

# OpenSuSE
#$htdocs = "/srv/www/htdocs";
# debian/Ubuntu
$htdocs = "/var/www/html";
# Synology
#$htdocs = "/volume1/web";


###########################################################################
### headers / footers

# Berechtigung pruefen
# $ipadr = getenv(REMOTE_ADDR);
#  $filename = "hosts.gdd";
#  $fp = fopen( $filename, "r" ) or die("Konnte $filename nicht ï¿½ffnen");
#  $i = 0;
#  $allow = "no";
#  while ( ! feof( $fp ) )
#		{
#		$zeile = fgets( $fp, 1024 );
#		$ipnr[$i] = $zeile;
#		$i++;
#		}
#
#  for ($a = 0; $a <= $i; $a++) {
#		if (strstr ($ipnr[$a], $ipadr) )
#			{
#			$allow = "yes";
#			}
#		}



###########################################################################
function print_foot($current)
{
  print "</body></html>";
}
###########################################################################
function print_footer($current)
{
  print "</div>";
  print "<script src=\"lightbox.js\"></script>";
  print " <script>
    lightbox.option({
      'resizeDuration': 200,
      'wrapAround': true,
      'maxWidth': 600,
      'albumLabel': \"Bild %1 of %2\"
    })
 </script>";
  print "</body></html>";
}

#######################################################################################
#######################################################################################
### read inbox albums

function read_inbox_albums($strSelFolder, $schritt){

@chdir($strSelFolder);
$cdir = @dir($strSelFolder);

$i = 0;
while ($entry = $cdir->read()) {
   if (!preg_match("/^\.$/", $entry)) {
      if (is_dir($entry)) {
         if (preg_match("/^\.\.$/", $entry)) {
			$folder = urlencode(substr($strSelFolder,0,strrpos($strSelFolder,"/")));
		 } else {
		    $folder = urlencode($strSelFolder);
		    strlen($strSelFolder)==1 ? $sep= "" : $sep= "/";
		    $folder .= $sep;
		    $folder .= urlencode($entry);
		 }
		 $type = strtoupper(filetype($entry));
# .. filtern
if ($entry != "..")
		{
		$pfad[] = $entry;
		$i++;
		}

      }
   }
}

print "<p>&nbsp;<br></p><center><table border=1 >";
$y = 0;
while ($y < $i)
	{
	$reihe[0] = $strSelFolder;
	$reihe[1] = $pfad[$y];
	$ausgabe = implode("",$reihe);

// pruefen ob das Verzeichnis leer ist!
	$cdir = @dir($ausgabe);
	$cdir->rewind();
	$entry = $cdir->read();	# .
	$entry = $cdir->read();	# ..
	$entry = $cdir->read();	# erster Dateiname
	if ($entry != "")
		{
		print "<tr><td><A HREF=read_inbox_album.php?schritt=2&Folder=$ausgabe>$reihe[1]</A></td></tr>";
		}

	$y++;
	}
echo "</table></center>";


if ($schritt == 2)
{
echo <<<HEAD3
	<table border=0 width=60%><tr><td>
	<table border=1>
HEAD3;

$chkopen = 0;

$cdir->rewind();
clearstatcache();
$i = 0;
while ($entry = $cdir->read()) {
  if (strstr($entry, ".mp3") || strstr($entry, ".ogg") || strstr($entry, ".flac"))
   {
   if (!is_dir($entry)) {
   	  if ($i++ % 2 == 0) {
		  echo "<tr bgcolor=LightGrey>";
	  } else {
	  	 echo "<tr bgcolor=white>";
      }
	  echo "<td valign=top class=treetext>$entry</td>";
      echo "<td nowrap class=treetext align=right>".GetRealVolume(filesize($entry))."</td>";
# Sind die Dateien lesbar?
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
	  echo '</td></tr>';
	}
   }

}
echo '</table>';
$cdir->close;

if ($chkopen == 0 && $i > 0)
{
print "</td><td><small><b>&nbsp;&nbsp;$i Tracks in Directory<br>&nbsp;<br>&nbsp;&nbsp;$strSelFolder</b>
</small></td>";
print "<td><table border='0' cellspacing='0' cellpadding='1'><tr><td class=\"menuactive\"><a class=\"menuactive\" href=\"read_inbox_album.php?schritt=3&Folder=$strSelFolder\">OK</a></td><tr></table></td>";
}
print "</tr></table>";
}

if ($schritt == 3)
	{

$wort = strtok($strSelFolder, "/");
while (is_string( $wort ) )
	{
	if ( $wort )
		{
#		print "$wort<br>";
		$artist = $wort;
		$title = $wort;
		}
	$wort = strtok( "/" );
	}

print "<form action=read_inbox_album.php method=get>";
print "<table border=0><tr><td>";
print "<table border=0>
<tr>
<td class=plformtit>Artist</td>
<td colspan=3 class=plform>
<input type=text name=artist value='$artist' size=40 maxlength=200>
</td></tr>

<tr>
<td class=plformtit>Album</td>
<td colspan=3 class=plform>
<input type=text name=album  value='$title' size=40 maxlength=200>
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
#  mysql_close( $link );

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
#  mysql_close( $link );

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
$ergebnis = mysql_db_query ("GiantDisc", "SELECT mp3file FROM tracks WHERE mp3file LIKE 'trxx%'" );
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

print "<h2>$output026</h2>";
print "<p>$output027</p>";
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
print "<input type=hidden name=schritt value='4'>";
print "<input type=hidden name=Folder value=$strSelFolder>";

print "</form>";

	}
}

###########################################################################
function GetRealVolume($v = 0) {
   if ($v > pow(2,10)) {
	  if ($v > pow(2,20)) {
    	 $r = (integer)($v / pow(2,20));
    	 $r .= " MB";
      } else {
         $r = (integer)($v / pow(2,10));
    	 $r .= " KB";
   	  } // end if
   } else {
      $r = (string) $v . " Byte";
   } // end if
   return $r;
}

###########################################################################
# strack_temp erstellen
function strack_temp() {
#$createtable = mysql_db_query( "GiantDisc", "CREATE TABLE strack_temp (
$con = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
$createtable = mysqli_query( $con, "CREATE TABLE strack_temp (
artist        varchar(255),
title         varchar(255),
composer	  varchar(255),
genre1        char(10),
genre2		  char(10),
year          smallint unsigned,
lang          char(4),
type          tinyint unsigned,
rating        tinyint unsigned,
length        smallint unsigned,
source        tinyint unsigned,
sourceid      char(20),
tracknb       tinyint unsigned,
mp3file       varchar(255),
quality		  tinyint unsigned,
voladjust     smallint DEFAULT 0,
lengthfrm     mediumint DEFAULT 0,
startfrm      mediumint DEFAULT 0,
bpm           smallint DEFAULT 0,
lyrics        mediumtext,
moreinfo	  mediumtext,
bitrate       char(10),
created       date,
modified      date,
backup        tinyint(3) unsigned,
id            int not null auto_increment,
PRIMARY KEY (ID)
)");

}

###########################################################################
# tracks_temp erstellen
function track_temp() {
$con = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
$createtable = mysqli_query( $con, "CREATE TABLE tracks_temp (
artist        varchar(255),
title         varchar(255),
composer	  varchar(255),
genre1        char(10),
genre2		  char(10),
year          smallint unsigned,
lang          char(4),
type          tinyint unsigned,
rating        tinyint unsigned,
length        smallint unsigned,
source        tinyint unsigned,
sourceid      char(20),
tracknb       tinyint unsigned,
mp3file       varchar(255),
quality		  tinyint unsigned,
voladjust     smallint DEFAULT 0,
lengthfrm     mediumint DEFAULT 0,
startfrm      mediumint DEFAULT 0,
bpm           smallint DEFAULT 0,
lyrics        mediumtext,
moreinfo	  mediumtext,
bitrate       char(10),
created       date,
modified      date,
backup        tinyint(3) unsigned,
id            int not null auto_increment,
PRIMARY KEY (ID)
)");

}

###########################################################################
# album_temp erstellen
function album_temp() {
#$createtable = mysql_db_query( "GiantDisc", "CREATE TABLE album_temp (
$con = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
$createtable = mysqli_query( $con, "CREATE TABLE album_temp (
artist		varchar(255),
title		varchar(255),
composer	varchar(255),
cddbid		varchar(20) not null,
coverimg	varchar(255),
covertxt	mediumtext,
modified	date,
genre		varchar(10)
)");

}

#######################################################################################

function full_mp3_fnames($fname)
# returns the full path+filename for the mp3 file 'fname'
{
  if (strlen($fname)>=8){
    #exec("/bin/ls /home/music/??/".$fname, $fullname);
    exec("/bin/ls /srv/www/htdocs/music/??/".$fname, $fullname);
    $fullname[0] = substr( $fullname[0], strlen( "/srv/www/htdocs" ));
    return $fullname[0];
  }
  else{
    return "";
  }
}
###########################################################################

function write_alphabet($prefix)
{
  # Zahlen
  $cnt = ord("0");
  while ($cnt <= ord("9")){

  	# Album by Artist
	if ($prefix == "browse.php?l0=al-ar&l1="){
	$fig = chr($cnt);
#    $tempquery = mysql_db_query( "GiantDisc", "SELECT * FROM album WHERE artist LIKE '$fig%'");
    $con = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
    $tempquery = mysqli_query( $con, "SELECT * FROM album WHERE artist LIKE '$fig%'");
	$anz_row = mysqli_num_rows( $tempquery );
	}

  	# Album by Title
	if ($prefix == "browse.php?l0=al-ti&l1="){
	$fig = chr($cnt);
    #$tempquery = mysql_db_query( "GiantDisc", "SELECT * FROM album WHERE title LIKE '$fig%'");
    $con = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
    $tempquery = mysqli_query( $con, "SELECT * FROM album WHERE title LIKE '$fig%'");
	$anz_row = mysqli_num_rows( $tempquery );
	}

  	# Tracks by Artist
	if ($prefix == "browse.php?l0=tr-ar&l1="){
	$fig = chr($cnt);
    #$tempquery = mysql_db_query( "GiantDisc", "SELECT * FROM tracks WHERE artist LIKE '$fig%'");
    $con = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
    $tempquery = mysqli_query( $con, "SELECT * FROM tracks WHERE artist LIKE '$fig%'");
	$anz_row = mysqli_num_rows( $tempquery );
	}

  	# Tracks by Title
	if ($prefix == "browse.php?l0=tr-ti&l1="){
	$fig = chr($cnt);
    #$tempquery = mysql_db_query( "GiantDisc", "SELECT * FROM tracks WHERE artist LIKE '$fig%'");
    $con = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
    $tempquery = mysqli_query( $con, "SELECT * FROM tracks WHERE artist LIKE '$fig%'");
	$anz_row = mysqli_num_rows( $tempquery );
	}

	if ($anz_row > 0)
		{
    	print "<a class=\"brslst\" href=\"".$prefix.chr($cnt)."\">".chr($cnt)."..</a> ";
		}
    $cnt++;
  }


  # Buchstaben
  $cnt = ord("a");
  while ($cnt <= ord("z")){
    print "<a class=\"brslst\" href=\"".$prefix.chr($cnt)."\">".chr($cnt)."..</a> ";
    $cnt++;
  }
}
###########################################################################

function write_aristlink($prefix, $artist)
{
  global $trunclen;
  if (strlen($artist)>$trunclen){
    $art = substr($artist, 0, $trunclen);
    $appendix="..";
  }
  else{
    $art = $artist;
    $appendix="";
  }
  print "<a class=\"brslst\" href=\"".$prefix.urlencode($art)."\">"
        .htmlentities($art).$appendix."</a>&nbsp; ";
}
###########################################################################
###########################################################################
###########################################################################
### read from cd album
function read_cd_album($act)
{

#$ergebnis = mysql_db_query( "GiantDisc", "SELECT * FROM album_temp" );
$link =  mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
$ergebnis = mysqli_query( $link, "SELECT * FROM album_temp" );
#$anz_felder = mysql_num_fields( $ergebnis );
$anz_felder = mysqli_num_rows($ergebnis);

$a = 0;
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
			$title = $feld;
			break;

			case $a == 3:
			$composer = $feld;
			break;

			default:
			break;

			}
		}
	}
#mysql_close( $link );

#$ergebnis = mysql_db_query( "GiantDisc", "SELECT * FROM tracks_temp" );
$ergebnis = mysqli_query( $link, "SELECT * FROM tracks_temp" );
$anz_felder = mysqli_num_fields( $ergebnis );

$a = 0;
while ( $datensatz = mysqli_fetch_row( $ergebnis ) )
	{
    foreach ( $datensatz as $feld )
		{
		 $a = $a + 1;

	 	switch ($a)
			{
			case $a == 1:
			break;

			case $a == 2:
			break;

			case $a == 3:
			break;

			case $a == 4:
			if ($overwrite == "create")
				$genre1 = $feld;
			break;

			case $a == 5:
			if ($overwrite == "create")
			 $genre2 = $feld;
			break;

			case $a == 6:
			$year = $feld;
			break;

			case $a == 7:
			$lang = $feld;
			break;

			case $a == 8:
			if ($overwrite == "create")
				$type = $feld;
			break;

			case $a == 9:
			$rating = $feld;
			break;

			case $a == 10:
			break;

			case $a == 11:
			$source = $feld;
			break;

			default:
			break;

			}
		}
	}


print "<form action=read_from_cd.php method=get>";
print "<table border=0><tr><td>";
print "<table border=0>
<tr>
<td class=plformtit>Artist</td>
<td colspan=3 class=plform>
<input type=text name=artist value='$artist' size=40 maxlength=200>
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

#  $recset = mysql_db_query ("GiantDisc", "SELECT * FROM musictype ORDER BY id");
  $recset = mysqli_query ( $link , "SELECT * FROM musictype ORDER BY id");
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
$selected = $rating;
  $recset = array("-", "0", "+", "++");
  print "<option value=\"\">&nbsp;</option>\n";
#  while(list($key, $value) = each($recset)) {
  foreach ($recset as $key => $value) {
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

  $recset = mysqli_query ( $link, "SELECT * FROM source ORDER BY id");
  print "<option value=\"\">&nbsp;</option>\n";
  while($row = mysqli_fetch_array($recset)) {
    print "<option value=\"".$row["id"]."\"";
    if ($row["id"] == $selected) {print(" selected");};
    print ">";
	print $row["source"]."</option>\n";
  }
#  mysql_close( $link );

#print_source_options($source+1);
print "</select></small>
</td>
</tr>

</table>";

}
###########################################################################
###########################################################################
### read from cd track
function read_cd_track($lfn_track)
{
  $link =  mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
  if ( ! $link )
      die( "Keine Verbindung zu MySQL" );
$ergebnis = mysqli_query( $link , "SELECT artist, title, composer, genre1, genre2, year, bpm, lang, type, rating, source FROM tracks_temp where tracknb = '$lfn_track'" );
$anz_felder = mysqli_num_fields( $ergebnis );
$z = 0;
while ( $datensatz = mysqli_fetch_row( $ergebnis ) )
    {
    foreach ( $datensatz as $feld )
		{
		$z++;
		switch ($z)
			{
			case $z == 1:
			$artist = $feld;
			break;

			case $z == 2:
			$title = $feld;
			break;

			case $z == 3:
			$composer = $feld;
			break;

			case $z == 4:
			$genre1 = $feld;
			break;

			case $z == 5:
			$genre2 = $feld;
			break;

			case $z == 6:
			$year = $feld;
			break;

			case $z == 7:
			$bpm = $feld;
			break;

			case $z == 8:
			$lang = $feld;
			break;

			case $z == 9:
			$type = $feld;
			break;

			case $z == 10:
			$rating = $feld;
			break;

			case $z == 11:
			$source = $feld;
			break;

			default:
			break;

			}

		}
	}


#mysql_close( $link );

print "<form action=read_from_cd.php method=get>";
print "<table border=0><tr><td>";
print "<table border=0>
<tr>
<td class=plformtit>Artist</td>
<td colspan=3 class=plform>
<input type=text name=artist value=\"$artist\" size=40 maxlength=200>
</td></tr>

<tr>
<td class=plformtit>Title</td>
<td colspan=3 class=plform>
<input type=text name=title  value=\"$title\" size=40 maxlength=200>
</td></tr>
<tr>
<td class=plformtit>Composer</td>
<td colspan=3 class=plform>
<input type=text name=composer  value=\"$composer\" size=40 maxlength=200>
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
<input type=text name=year value=\"$year\" size=4 maxlength=4>
</td>
<td class=plformtit>Beats/min</td>
<td class=plform>
<input type=text name=bpm value=\"$bpm\" size=4 maxlength=4>
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
#$selected = $type + 1;
$selected = $type;
#  $benutzer = "music";
#  $passwort = "music";
#  $db = "GiantDisc";
#  $link =  mysql_connect( "localhost", $benutzer, $passwort  );

  $recset = mysqli_query ( $link , "SELECT * FROM musictype ORDER BY id");
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
$selected = $rating;
  $recset = array("-", "0", "+", "++");
  print "<option value=\"\">&nbsp;</option>\n";
#  while(list($key, $value) = each($recset)) {
  foreach($recset as $key => $value) {
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
#$selected = 1;
$selected = $source + 1;
#  $benutzer = "music";
#  $passwort = "music";
#  $db = "GiantDisc";
#  $link =  mysql_connect( "localhost", $benutzer, $passwort  );

  $recset = mysqli_query ( $link , "SELECT * FROM source ORDER BY id");
  print "<option value=\"\">&nbsp;</option>\n";
  while($row = mysqli_fetch_array($recset)) {
    print "<option value=\"".$row["id"]."\"";
    if ($row["id"] == $selected) {print(" selected");};
    print ">";
	print $row["source"]."</option>\n";
  }
#  mysql_close( $link );

#print_source_options($source+1);
print "</select></small>
</td>
</tr>

</table>";

}

###########################################################################
### headers / footers
#function print_header($current, $redirect, $cgi_dir, $host, $requ)
function print_header($current, $redirect, $cgi_dir, $char_set, $output135, $output136, $output137, $output138)

// prints the header (<head> and <title> tags, and opening <body> and <div> tags)
//
// if $current is not empty, the main top menu is printed
{
  $redir="";
  if (strlen($redirect)>1){
    $redir = "<meta http-equiv=\"REFRESH\" content=\"0;url=$redirect\">";
  }
  print "<!DOCTYPE html
	PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">
  <html>
  <head>
  <title>GiantDisc Web Interface: $current</title>";
    print "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=$char_set\">";    
print "<meta name=\"author\" content=\"Juergen Thoens\">
  <meta name=\"pragma\" content=\"no-cache\">
  <meta http-equiv=\"cache-control\" content=\"no-cache\">
  <meta http-equiv=\"expires\" content=\"100\">
  <meta name=\"revisit-after\" content=\"1\">
  <link href=\"gdweb.css\" rel=\"stylesheet\" type=\"text/css\">
  <link rel=\"SHORTCUT ICON\" href=\"img/gd16.ico\">
  <link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\">
  <link href=\"lightbox.css\" rel=\"stylesheet\">";
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

<script src="jquery-1.9.1.min.js"></script>
<script src="scroll-top.js"></script>
<!-- Initialisieren -->
<script type="text/javascript">
   $(document).ready(function(){
      $('.top').UItoTop();
   });
</script>

<?php

$host = getenv('HTTP_HOST');
#$host = "localhost";
$requ = getenv('REQUEST_URI');
#$requ = $redirect;
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


if (strcmp($current, "startrec")==0)
	print "<META HTTP-EQUIV='Refresh' CONTENT='2; URL=$cgi_dir/music.pl?file=start&com=ripit&uri=http://$host$requ'>";

if (strcmp($current, "aufnahme")==0)
	print "<META HTTP-EQUIV='Refresh' CONTENT='10; URL=$cgi_dir/music.pl?file=watch&com=ripit&uri=http://$host$requ'>";

if (strcmp($current, "fin")==0)
  {
	print "<META HTTP-EQUIV='Refresh' CONTENT='5; URL=corrpt.php?com=auto'>";
  }

  print "</head>
  <body>";

if (strcmp($current, "fin")==0)
  {
  print "<audio autoplay reload controls>";
  print "<source src=\"img/tada.mp3\">";
  print "<source src=\"img/tada.ogg\">";
  print "</audio>";
  }

  print "<table><tr>";
  print "<td>";
  print "<div class='maintitle'>GiantDisc&nbsp;Web&nbsp;Interface</div>";
  print "<div class='$current'>";
  print "</td>";

	print "<td width=\"100%\" height=\"44\" align=\"right\">
	<img src=\"img/M4A.png\" height=\"75\" border=\"0\" align=\"right\">
	<img src=\"img/flac.gif\" height=\"75\" border=\"0\" align=\"right\">
	<img src=\"img/opus.png\" width=\"75\" border=\"0\" align=\"right\">
	<img src=\"img/ogg_vorbis.png\" height=\"75\" border=\"0\" align=\"right\">
	<img src=\"img/mp3.png\" height=\"75\" border=\"0\" align=\"right\">";
	print "</td></tr></table>";



  if (strlen($current)>1){
    // print the main top menu
    print "<table border='0' cellspacing='0' cellpadding='1'><tr>";

    $sel = (strcmp($current, "search")==0) ? "" : "in";
    #print "<td class=\"menu".$sel."active\"><a class=\"menu\" href=\"index.php\">Search</a></td>";
    print "<td class=\"menu".$sel."active\"><a class=\"menu\" href=\"index.php\">$output135</a></td>";
    print "<td>&nbsp;</td>";
    $sel = (strcmp($current, "browse")==0) ? "" : "in";
    #print "<td class=\"menu".$sel."active\"><a class=\"menu\" href=\"browse.php\">Browse</a></td>";
    print "<td class=\"menu".$sel."active\"><a class=\"menu\" href=\"browse.php\">$output136</a></td>";
    print "<td>&nbsp;</td>";
    $sel = (strcmp($current, "record")==0) ? "" : "in";
    #print "<td class=\"menu".$sel."active\"><a class=\"menu\" href=\"record.php\">Record</a></td>";
    print "<td class=\"menu".$sel."active\"><a class=\"menu\" href=\"record.php\">$output137</a></td>";

#        print "<td>&nbsp;</td>";
#        $sel = (strcmp($current, "play")==0) ? "" : "in";
#        print "<td class=\"menu".$sel."active\">";
#        print "<a class=\"menu\" href=\"play.php?l0=thisalb";
#        print "&albid=".$albumrecord["cddbid"];
#        print "&step=refresh";
#        print "\">Play</a></td>";

    print "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
    $sel = (strcmp($current, "settings")==0) ? "" : "in";
    #print "<td class=\"menu".$sel."active\"><a class=\"light\" href=\"settings.php\">Options</a></td>";
    print "<td class=\"menu".$sel."active\"><a class=\"light\" href=\"settings.php\">$output138</a></td>";
     
    print "</tr></table>";
  }
  else{
    ;
  }
}

###########################################################################
### functions
function print_genre_options($selected)
{
  #$recset = mysql_db_query ("GiantDisc", "SELECT * FROM genre ORDER BY id");
  $link =  mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
  if ( ! $link )
      die( "Keine Verbindung zu MySQL" );  
  $recset = mysqli_query ( $link, "SELECT * FROM genre ORDER BY id");
  print "<option value=\"\">&nbsp;</option>\n";
  while($row = mysqli_fetch_array($recset)) {
    print "<option value=\"".$row["id"]."\"";
    if ($row["id"] == $selected) {print(" selected");};
    print ">";
	for($i=1; $i<strlen($row["id"]); $i++){print ("&nbsp;&nbsp;&nbsp;");};
	print $row["genre"]."</option>\n";
  }
}


function print_genre_string($genre)
{
  $link =  mysqli_connect( MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB );
  if ( ! $link )
      die( "Keine Verbindung zu MySQL" );
      
  $recset = mysqli_query ( $link, "SELECT * FROM genre WHERE id='$genre'");
  if($row = mysqli_fetch_array($recset)) {
	print $row["genre"];
  }
}

function print_lang_options($selected)
{
#  $recset = mysql_db_query ("GiantDisc", "SELECT * FROM language ORDER BY id");
  $link =  mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
  if ( ! $link )
      die( "Keine Verbindung zu MySQL" );
  $recset = mysqli_query ( $link, "SELECT * FROM language ORDER BY id");

#           "SELECT language.id, language.language, COUNT(*) AS freq "
#          ."FROM language,tracks WHERE tracks.lang=language.id "
#          ."GROUP BY language.id ORDER BY freq DESC ");
  print "<option value=\"\">&nbsp;</option>\n";
  while($row = mysqli_fetch_array($recset)) {
    print "<option value=\"".$row["id"]."\"";
    if ($row["id"] == $selected) {print(" selected");};
    print ">";
	print $row["language"]."</option>\n";
  }
}

function print_type_options($selected)
{
  $link =  mysqli_connect( MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB );
  if ( ! $link )
      die( "Keine Verbindung zu MySQL" );
      
  $recset = mysqli_query ( $link, "SELECT * FROM musictype ORDER BY id");
  print "<option value=\"\">&nbsp;</option>\n";
  while($row = mysqli_fetch_array($recset)) {
    print "<option value=\"".$row["id"]."\"";
    if ($row["id"] == $selected) {print(" selected");};
    print ">";
	print $row["musictype"]."</option>\n";
  }
}

function print_source_options($selected)
{
  $link =  mysqli_connect( MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB );
  if ( ! $link )
      die( "Keine Verbindung zu MySQL" );
      
  $recset = mysqli_query ( $link, "SELECT * FROM source ORDER BY id");
  print "<option value=\"\">&nbsp;</option>\n";
  while($row = mysqli_fetch_array($recset)) {
    print "<option value=\"".$row["id"]."\"";
    if ($row["id"] == $selected) {print(" selected");};
    print ">";
	print $row["source"]."</option>\n";
  }
}

function print_rating_options($selected)
{
  $recset = array("-", "0", "+", "++");
  print "<option value=\"\">&nbsp;</option>\n";
#  while(list($key, $value) = each($recset)) {
  foreach ($recset as $key => $value) {
    print "<option value=\"".$key."\"";
    if (strlen($selected)>0 && $key == $selected) {print(" selected");};
    print ">";
	print $value."</option>\n";
  }
}

function print_rating_string($rating)
{
  $recset = array("-", "0", "+", "++");
#  while(list($key, $value) = each($recset)) {
  foreach ($recset as $key => $value) {
    if ($key == $rating) {print($value);};
  }
}

function language_string($language)
{
  $recset = mysql_db_query ("GiantDisc", "SELECT * FROM language WHERE id='$language'");
  if($row = mysql_fetch_array($recset)) {
    return $row["language"];
  }
}

function print_condition_options($selected)
{
  $recset = array("ok", "noisy", "damaged");
  print "<option value=\"\">&nbsp;</option>\n";
#  while(list($key, $value) = each($recset)) {
  foreach ($recset as $key => $value) {
    print "<option value=\"".$key."\"";
    if ($key == $selected) {print(" selected");};
    print ">";
	print $value."</option>\n";
  }
}

function seconds2time($seconds)
{
  return floor($seconds/60).":".sprintf("%02d",($seconds%60));
}

function full_mp3_fname($fname)
# returns the full path+filename for the mp3 file 'fname'
{
  if (strlen($fname)>=8){
    #exec("/bin/ls /home/music/??/".$fname, $fullname);
    #exec("/bin/ls /srv/www/htdocs/music/??/".$fname, $fullname);
    #exec("/bin/ls /volume1/web/music/??/".$fname, $fullname);
    exec("/bin/ls /var/www/html/music/??/".$fname, $fullname);
    #$fullname[0] = substr( $fullname[0], strlen( "/srv/www/htdocs" ));
    #$fullname[0] = substr( $fullname[0], strlen( "/volume1/web" ));
    $fullname[0] = substr( $fullname[0], strlen( "/var/www/html" ));
    return $fullname[0];
  }
  else{
    return "";
  }
}



#######################################################################################

function show_trackrow($row,
                       $showartist, $showtitle, $showgenre, $showlang, $showyear, $showrating,
			           $showbpm, $showsource, $showcreated, $showmodified, $showlength,
					   $showedit, $showdownload, $playtp, $showtoplaylist, $output079, $output080, $xspf, $output134, $mp3dir)
{


$yearstr = $row["year"];
# Jahreszahl vierstellig anzeigen

#    if (strcmp($playtp, "dl")==0){$mp3path = full_mp3_fname($row["mp3file"]);}
    if (strcmp($playtp, "dl")==0){
        $anz_slash = substr_count($mp3dir, "/");
        $teil_mp3 = explode("/", $mp3dir);
        $verz_l = array($teil_mp3[$anz_slash]);
        $mp3verz = implode("", $verz_l);
        $mp3path = $mp3verz."/".$row["mp3file"];
        
     #$yearstr = $mp3path;
    }

    if ($showartist){
      print "<td class=\"trklst\">&nbsp;".$row["artist"]." </td>";
	}
    if ($showtitle){
	  print "<td class=\"trklst\">&nbsp;".$row["title"]." </td>";
	}
    if ($showgenre){
	  print "<td class=\"trklst\">&nbsp;";
      if (strlen($row["genre1"])>0){
	    print_genre_string($row["genre1"]);
	  }
      if (strlen($row["genre1"])>0 && strlen($row["genre2"])>0){print "<br>";}
      if (strlen($row["genre2"])>0){
	    print "&nbsp;";
	    print_genre_string($row["genre2"]);
	  }
	  print "</td>";
	}
    if ($showrating){
	  print "<td class=\"trklst\">&nbsp;";
      print_rating_string($row["rating"]);
	  print "</td>";
	}
    if ($showlang){
	  print "<td class=\"trklst\">&nbsp;".language_string($row["lang"])." </td>";
	}
    if ($showyear){
	  print "<td class=\"trklst\">&nbsp;".$yearstr." </td>";
	}
    if ($showlength){
	  print "<td class=\"trklst\">&nbsp;".seconds2time($row["length"])." </td>";
	}
    if ($showedit){
	  print "<td class=\"trklst\">&nbsp;<a href=\"gdedittrack.php?id="
		   		.$row["id"]."\" target=\"_blank\">"
				."<img border=\"0\" src=\"img/edit-c.gif\" alt=\"Edit\"></a></td>";
	}
    if ($showdownload){
      # Download mp3
	  # types: playtp = dl, rd, df, dl or dr
      if (strcmp($playtp, "dl")==0){
	    print "<td class=\"trklst\">&nbsp;<a href=\"".$mp3path."\">"
		     ."<img border=\"0\" src=\"img/dl.gif\" alt=\"Download\"  title=\"$output080\"></a>
			 &nbsp;&nbsp;&nbsp;";
			 
			 if($xspf == "yes")
			   {
			   $title=$row["title"];
			   $artist=$row["artist"];
			   $laenge=$row["length"];
			   print "<a href=\"gd2xspf.php?mp3path=$mp3path&title=$title&artist=$artist&laenge=$laenge"
        ."\">";
         print "<img border=\"0\" src=\"img/xspf.png\" width=\"16\" alt=\"Download\" title=\"$output134\"></a>";
         print "&nbsp;&nbsp;&nbsp;";
         }
			 
       print "<a href=\"appendpl.php?id=".$row["id"]."\">"
		     ."<img border=\"0\" src=\"img/onetopl.gif\" alt=\"Append to Playlist\" title=\"$output079\"></a>";

  $link =  mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
  if ( ! $link )
      die( "Keine Verbindung zu MySQL" );
      $erg_lyric = mysqli_query( $link ,"select lyrics from tracks where id = $row[id] && lyrics IS NOT NULL");
      $anz_lyric = mysqli_num_rows( $erg_lyric );
      if ($anz_lyric > 0)
        {
        #print "&nbsp; &nbsp; &nbsp;<a href=\"lyric.php?id=$row[id]\" target=\"$row[id]\">";
        print "&nbsp; &nbsp; &nbsp;<a href=\"javascript:popUp1('lyric.php?id=$row[id]')\">";       
        print "<img border=\"0\" src=\"img/lyric_button.png\" alt=\"Lyric\" title=\"Lyric\"></a>";


        }      
#      print $row["id"];
      print "</td>";

	  }
      else{ # all other playtypes handled by "gdtrid2mp3.php"
	    print "<td class=\"trklst\">&nbsp;<a href=\"gdtrid2mp3.php?playtp=$playtp"
				."&id=".$row["id"]
				."&mp3f=".$row["mp3file"]
				."\">"."<img border=\"0\" src=\"img/dl.gif\" alt=\"Download\" title=\"$output080\"></a></td>";
	  }
	}
}

#######################################################################################

function show_onlytrackrow($row,
                       $showartist, $showtitle, $showgenre, $showlang, $showyear, $showrating,
			           $showbpm, $showsource, $showcreated, $showmodified, $showlength,
					   $showedit, $showdownload, $playtp, $showtoplaylist, $output079, $output080, $titel_nummer)
{
#	if (strlen($row["year"])>2){ $yearstr = "'".substr($row["year"], 2, 2);}
#	else                       { $yearstr = $row["year"];}
$yearstr = $row["year"];
# Jahreszahl vierstellig anzeigen

    if (strcmp($playtp, "dl")==0){$mp3path = full_mp3_fname($row["mp3file"]);}

    if ($showartist){
      print "<td class=\"trklst\">&nbsp;".$row["artist"]." </td>";
	}
    if ($showtitle){
	  print "<td class=\"trklst\">&nbsp;".$row["title"]." </td>";
	}
    if ($showgenre){
	  print "<td class=\"trklst\">&nbsp;";
      if (strlen($row["genre1"])>0){
	    print_genre_string($row["genre1"]);
	  }
      if (strlen($row["genre1"])>0 && strlen($row["genre2"])>0){print "<br>";}
      if (strlen($row["genre2"])>0){
	    print "&nbsp;";
	    print_genre_string($row["genre2"]);
	  }
	  print "</td>";
	}
    if ($showrating){
	  print "<td class=\"trklst\">&nbsp;";
      print_rating_string($row["rating"]);
	  print "</td>";
	}
    if ($showlang){
	  print "<td class=\"trklst\">&nbsp;".language_string($row["lang"])." </td>";
	}
    if ($showyear){
	  print "<td class=\"trklst\">&nbsp;".$yearstr." </td>";
	}
    if ($showlength){
	  print "<td class=\"trklst\">&nbsp;".seconds2time($row["length"])." </td>";
	}
    if ($showedit){
	  print "<td class=\"trklst\">&nbsp;<a href=\"gdedittrack.php?id="
		   		.$row["id"]."\" target=\"_blank\">"
				."<img border=\"0\" src=\"img/edit-c.gif\" alt=\"Edit\"></a></td>";
	}
    if ($showdownload){
      # Download mp3
	  # types: playtp = dl, rd, df, dl or dr
      if (strcmp($playtp, "dl")==0){

        print "<td class=\"trklst\">&nbsp;";

      $link =  mysql_connect( MYSQL_HOST, MYSQL_USER );
      if ( ! $link )
          die( "Keine Verbindung zu MySQL" );
      $abfrage = "SELECT mp3file FROM tracks WHERE id = $rowid ";
      $recset = mysql_db_query ("GiantDisc", $abfrage);

      $sql = "select lyrics from tracks where id = $row[id] && lyrics IS NOT NULL";
      $erg_lyric = mysql_db_query( "GiantDisc", $sql);
      $anz_lyric = mysql_num_rows( $erg_lyric );
      if ($anz_lyric > 0)
        {
        #print "&nbsp; &nbsp; &nbsp;<a href=\"lyric.php?id=$row[id]\" target=\"$row[id]\">";
        print "&nbsp; &nbsp; &nbsp;<a href=\"javascript:popUp1('lyric.php?id=$row[id]')\">";       
        print "<img border=\"0\" src=\"img/lyric_button.png\" alt=\"Lyric\" title=\"Lyric\"></a>";
        }      
      mysql_free_result($recset);
      mysql_close( $link );
      
#      print $erg_lyric;
#      print $row["id"];
      $rowid = $row["id"];
            
      $link =  mysql_connect( MYSQL_HOST, MYSQL_USER );
      if ( ! $link )
          die( "Keine Verbindung zu MySQL" );
      
      $abfrage = "SELECT mp3file FROM tracks WHERE id = $rowid ";
      $recset = mysql_db_query ("GiantDisc", $abfrage);
#      $anz_row = mysql_num_rows( $recset );
#      print "$anz_row<br>";

      while($row = mysql_fetch_object($recset))
        {
        $mp3file = $row->mp3file;
        $mp3file = explode(".", $mp3file);
        $mp3file = $mp3file[0];
        $titel_nummer = explode(".", $titel_nummer);
        $titel_nummer = $titel_nummer[0];
#        print " $mp3file";
#        print " $titel_nummer";        
        }

      mysql_free_result($recset);
      mysql_close( $link );
      
      if ($mp3file == $titel_nummer)
          print "</td><td> &nbsp; <img border=\"0\" src=\"img/aktiv_track.gif\" height=\"16\" alt=\"aktiv track\" title=\"aktiv track\"></td>";
      else
          print "</td><td></td>";

	  }
      else{ # all other playtypes handled by "gdtrid2mp3.php"
	    print "<td class=\"trklst\">&nbsp;<a href=\"gdtrid2mp3.php?playtp=$playtp"
				."&id=".$row["id"]
				."&mp3f=".$row["mp3file"]
				."\">"."<img border=\"0\" src=\"img/dl.gif\" alt=\"Download\" title=\"$output080\"></a></td>";
	  }
	}
}


###########################################################################
### cookie functions

$expire_sec = 1000000;  # Cookie expires in ... seconds


function set_get_cookie($updatecookie, $cookiename, $defaultval, $cookievars)
{
  global $expire_sec;

  if (isset($updatecookie) and strlen($updatecookie)>0){
    setcookie ($cookiename, $updatecookie,time()+$expire_sec, "/~music/");
    return $updatecookie;
  }
  else{
    if (strlen ($cookievars[$cookiename]) >0 ){
      return $cookievars[$cookiename];
    }
    else{
      setcookie ($cookiename, $defaultval,time()+$expire_sec, "/~music/");
      return $defaultval;
    }
  }
}


###########################################################################
function set_get_cbx_cookie($newsettings, $cookiename, $cookieval, $cookievars)
# set get checkbox cookie
{
  global $expire_sec;

  if (isset($newsettings)){
    setcookie ($cookiename, $cookieval, time()+$expire_sec, "/~music/");
  }
  else{
    if (isset($cookievars[$cookiename]) and $cookievars[$cookiename]){
      $cookieval = 1;
	}
	else{
      $cookieval = 0;
	}
  }
  return $cookieval;
}


###########################################################################
### "direct linking" functions

function dl_make_filelist()
{
#  exec("/bin/ls /home/music/??/*0.mp3 > tmp/filelist.txt");

#  $fp = fopen ("tmp/file2.txt", "w");
#  fwrite ($fp, "This is my string\n");
#  fclose($fp);
}

#######################################################################################
### "display the playlists"
### Abspiellisten

function p_playlist($output080, $output132, $bashplayer, $output133, $b_player, $xspf, $output134){

$abfrage =  "SELECT id, title FROM playlist ORDER BY id";
#$ergebnis = mysql_db_query( "GiantDisc", $abfrage );
#$anz_felder = mysql_num_fields( $ergebnis );
$con = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
$ergebnis = mysqli_query( $con, $abfrage);
#  if (mysql_affected_rows()>0){print "<hr noshade>";}else{print "<p>&nbsp;</p>";}
  print("<p><small><b>".mysqli_affected_rows($con)." machting playlists</b></small></p>");


echo("<table><tr><td>&nbsp; &nbsp;</td><td><small><B>No.</B></small></td><td>&nbsp;</td><td><small><B>Playlistname</B></small></td></tr>");

$a = 0;
while ( $datensatz = mysqli_fetch_row( $ergebnis ) )
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
				print "<tr><td>&nbsp;</td><td><small>$pl_id</small></td><td>&nbsp;-&nbsp;</td>";
				print "<td><small><a  href=browse.php?l0=playlist&pl_id=$pl_id&title=$feld#liste>$feld</a></small></td>";
				print "<td class=\"trklst\">&nbsp;<a href=\"gdtrid2mp3.php?playtp=pr"
				."&prPlaylist=$pl_id"
				."\">"."<img border=\"0\" src=\"img/download.png\" width=\"40\" alt=\"Download\" title=\"$output132\"></a>";

      if($bashplayer == "yes")
        {
        print "&nbsp; &nbsp; &nbsp; <a href=\"p_play.php?l0=prPlaylist";
        print "&albid=$pl_id";
        print "&step=start";
        print "\">";
        print "<img border=\"0\" src=\"img/button_blue_play.png\" width=\"40\" alt=\"Download\" title=\"$output133 $b_player\"></a>";
        }
        

        if($xspf == "yes")
        {
        print "&nbsp; &nbsp; &nbsp;<a href=\"gdtrid2xspf.php?playtp=pr"
				."&prPlaylist=$pl_id"
				."\">";
        print "<img border=\"0\" src=\"img/xspf.png\" width=\"40\" alt=\"Download\" title=\"$output134\"></a>";
        }

				print "</td></tr>";
				$a = 0;
				}
		  }
    }
echo("</table>");

}

#######################################################################################
#######################################################################################
### "display the selected playlist"

function p_listen($pl_id, $title, $PATH_ABSOLUTE_mp3, $output080, $output082, $tool, $output091, $output092, $xspf, $output134, $link, $mp3dir){
$abfrage = "select artist, title, tracknumber, id from tracks, playlistitem
where trackid = id
and playlist = $pl_id
order by tracknumber ASC";
$ergebnis = mysqli_query( $link , $abfrage );
$anz_felder = mysqli_num_fields( $ergebnis );

  #if (mysql_affected_rows()>0){print "<hr noshade>";}else{print "<p>&nbsp;</p>";}
  print("<p><small><b>".mysqli_affected_rows( $link )." tracks in $title</b></small></p>");

echo("<table><tr><td><small><b>Track-No.</b></small></td><td><small><b>Artist</b></small></td><td><small><b>Title</b></small></td><td>&nbsp;</td></tr>");

$a = 0;
#$track = 1;
$evenln = 1;
while ( $datensatz = mysqli_fetch_row( $ergebnis ) )
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
				$abfrage = "select trackid from playlistitem where $pl_id = playlist"; 
        $test_reihe = mysqli_query( $link , $abfrage);
				$anz_row = mysqli_num_rows( $test_reihe );

        $sql = "select mp3file from tracks where $feld = id";
				$recset = mysqli_query( $link , $sql );
				while ( $datastep = mysqli_fetch_row( $recset ) )
					{
					foreach ( $datastep as $field )
						{
						$httphost = $_SERVER["HTTP_HOST"];
						$l = strlen($PATH_ABSOLUTE_mp3) - 3;
						$mp3path = substr($PATH_ABSOLUTE_mp3,$l,3);
						print "<td class=\"trklst\"><a href=\"http://$httphost/music";
						print "$mp3path";
						print "/";
						print "$field\">";
						print "<img border=\"0\" src=\"img/dl.gif\" alt=\"Download\" title=\"$output080\"></a>";
						
					  if($xspf == "yes")
			       {
			       print "&nbsp;&nbsp;&nbsp;";
			       #$title=$row["title"];
			       #$artist=$row["artist"];
			       $laenge=0;

        $anz_slash = substr_count($mp3dir, "/");
        $teil_mp3 = explode("/", $mp3dir);
        $verz_l = array( $teil_mp3[$anz_slash], "/", $field);
        $mp3verz = implode("", $verz_l);
			       print "<a href=\"gd2xspf.php?mp3path=$mp3verz&title=$title&artist=$artist&laenge=$laenge"."\">";
			       
             print "<img border=\"0\" src=\"img/xspf.png\" width=\"16\" alt=\"Download\" title=\"$output134\"></a>";
             }

            #print "$feld";
            $erg_lyric = mysqli_query( $link ,"select lyrics from tracks where id = $feld && lyrics IS NOT NULL");
            $anz_lyric = mysqli_num_rows( $erg_lyric );
            if ($anz_lyric > 0)
              {
              print "&nbsp; &nbsp; &nbsp;<a href=\"javascript:popUp1('lyric.php?id=$feld')\">";       
              print "<img border=\"0\" src=\"img/lyric_button.png\" alt=\"Lyric\" title=\"Lyric\"></a>";
              }                      

            print "</td>";
        if (strcmp($tool, "edit")==0)
            {
            print "<td class=\"trklst\">&nbsp; &nbsp;";
            if ($track > 1)
              {
              print "<a href=\"http://$httphost/music/delete.php?pl_id=$pl_id&trackid=$feld&trackno=$track&com=up\"><img border=\"0\" src=\"img/up.gif\" width=\"10\" height=\"13\" title=\"$output091\"></a>&nbsp; &nbsp;";						
              }
            else
              {
              print "<img border=\"0\" src=\"img/0.gif\" width=\"10\">&nbsp; &nbsp;";						
              }  
            if ($track < $anz_row)
              {
              print "<a href=\"http://$httphost/music/delete.php?pl_id=$pl_id&trackid=$feld&trackno=$track&com=down\"><img border=\"0\" src=\"img/down.gif\" width=\"10\" height=\"13\" title=\"$output092\"></a>&nbsp; &nbsp;";						
              }
            else              
              {
              print "<img border=\"0\" src=\"img/0.gif\" width=\"10\">&nbsp; &nbsp;";						
              }  
            print "<a href=\"http://$httphost/music/delete.php?pl_id=$pl_id&trackid=$feld&trackno=$track&com=ask_del\"><img border=\"0\" src=\"img/drop.png\" width=\"15\" height=\"13\" title=\"$output082\"></a></td>";						
            }
            print "</tr>";

						#print "<td class=\"trklst\">$field<img border=\"0\" src=\"img/dl.gif\" alt=\"Download\" title=\"$output080\"></td></tr>";

						}
					}

				#print "<img border=\"0\" src=\"img/dl.gif\" alt=\"Download\" title=\"$output080\"></a></td></tr>";
				mysqli_free_result($recset);
				$a = 0;
#				$track++;
				$evenln = 1-$evenln;
			break;
			}
		  }
    }
echo("</table>");
mysqli_free_result($ergebnis);

return 0;
}

#######################################################################################
### "append a title to a playlist"

function p_append($id, $output048, $output049){

$link =  $link =  mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
  if ( ! $link )
      die( "Keine Verbindung zu MySQL" );
echo("<h1>Append Title to Playlist</h1><hr noshade>");

$abfrage =  "SELECT artist, title FROM tracks where id=$id";
$ergebnis = mysqli_query( $link , $abfrage );
$anz_felder = mysqli_num_fields( $ergebnis );

print "<FORM METHOD='GET' ACTION='newpl.php'>
<table border='0' cellspacing='0'><tr><td class='trklst'>&nbsp;<b>$output048</b>";

$a = 0;
while ( $datensatz = mysqli_fetch_row( $ergebnis ) )
    {
    foreach ( $datensatz as $feld )
		  {
			if ($a == 0)
				{
				print "&nbsp;&nbsp;$feld";
				$a = $a + 1;
				}
			else
				{
				print "&nbsp;&nbsp;-&nbsp;&nbsp;$feld";
				$a = 0;
				}
		  }
    }
print "&nbsp;&nbsp;<b>$output049</b></td>
<td>&nbsp;</td>
<td>
<input name='playlistname' type='text' VALUE=''>
<input name='id' type='hidden' VALUE='$id'>
&nbsp;<INPUT TYPE='submit' VALUE='create'>
&nbsp;&nbsp;&nbsp;</td>
</tr></table></FORM><hr noshade>";
mysqli_free_result($ergebnis);




$ergebnis = mysqli_query( $link , "SELECT id, title FROM playlist" );
$anz_felder = mysqli_num_fields( $ergebnis );

echo("<table><tr><td>&nbsp;</td><td>&nbsp; &nbsp;</td><td><small><B>No.</B></small></td><td>&nbsp;</td><td><small><B>Playlistname</B></small></td></tr>");

$a = 0;
while ( $datensatz = mysqli_fetch_row( $ergebnis ) )
    {
    foreach ( $datensatz as $feld )
		  {
			if ($a == 0)
				{
				#print "<small>$feld $id</small><br>";
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
mysqli_free_result($ergebnis);
mysqli_close( $link );
}

#######################################################################################
#######################################################################################
### insert a title into playlist

function p_appendex($pl_id, $title, $id, $link){

$ergebnis = mysqli_query( $link , "select tracknumber from playlistitem where playlist=$pl_id" );

$a=0;
$b=0;
while ( $datensatz = mysqli_fetch_row( $ergebnis ) )
    {
    foreach ( $datensatz as $feld )
		  {
			$a = $feld;
			if ($a > $b)
				$b = $a;
		  }
    }
$b++;

mysqli_free_result($ergebnis);

$ergebnis = mysqli_query( $link , "insert into playlistitem (playlist, tracknumber, trackid)
values ($pl_id, $b, $id)" );

}

#######################################################################################
#######################################################################################



###########################################################################

###########################################################################
?>
