<?php


include "control_web.inc";


$c = 0;
$d = 0;

$dateiname = "00/rip.txt";
$fp = fopen( $dateiname, "r" ) or die ("Konnte $dateiname nicht öffnen");
while ( ! feof( $fp))
	{
	$zeile = fgets($fp, 1024);
#	print "$zeile<br>";
#	if ( strstr( $zeile, "track") & strstr( $zeile, "cdparanoia") )
#	if ( strstr( $zeile, "track") & strstr( $zeile, "cdda2wav") )
#	if ( (strstr( $zeile, "track") & strstr( $zeile, "cdda2wav")) or (strstr( $zeile, "track") & strstr( $zeile, "cdparanoia")) )
	if ( (str_contains( $zeile, "track") & str_contains( $zeile, "cdda2wav")) or (str_contains( $zeile, "track") & str_contains( $zeile, "cdparanoia")) )
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
$dateiname = "00/rip.txt";
$fp = fopen( $dateiname, "r" ) or die ("Konnte $dateiname nicht öffnen");
while ( ! feof( $fp))
	{
	$zeile = fgets($fp, 1024);
#	print "$zeile<br>";
#	if ( strstr( $zeile, "track") & strstr( $zeile, "lame") or strstr( $zeile, "track") & strstr( $zeile, "oggenc")  or strstr( $zeile, "track") & strstr( $zeile, "flac"))
	if ( str_contains( $zeile, "track") & str_contains( $zeile, "lame") or str_contains( $zeile, "track") & str_contains( $zeile, "oggenc")  or str_contains( $zeile, "track") & str_contains( $zeile, "flac"))
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

  print "<!DOCTYPE html
	PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">
  <html>
  <head>
  <title>GiantDisc Web Interface: record</title>
  <meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
  <meta name=\"author\" content=\"Jürgen Thöns\">
  <meta name=\"pragma\" content=\"no-cache\">
  <meta http-equiv=\"cache-control\" content=\"no-cache\">
  <meta http-equiv=\"expires\" content=\"100\">
  <meta name=\"revisit-after\" content=\"1\">
  <link href=\"gdweb.css\" rel=\"stylesheet\" type=\"text/css\">
  <link rel=\"SHORTCUT ICON\" href=\"img/gd16.ico\">";

if ($c == 0 && $d == 0)
	print "<META HTTP-EQUIV='Refresh' CONTENT='1; URL=rec_men.php'>";
else
	print "<META HTTP-EQUIV='Refresh' CONTENT='1; URL=read_from_cd.php?schritt=5'>";

 
  print "</head>
  <body>";
  print "<table><tr>";
  print "<td>";
  print "<div class='maintitle'>GiantDisc&nbsp;Web&nbsp;Interface</div>";
  print "<div class='record'>";
  print "</td>";

	print "<td width=\"100%\" height=\"44\" align=\"right\">
	<img src=\"img/M4A.png\" height=\"75\" border=\"0\" align=\"right\">
  <img src=\"img/flac.gif\" height=\"75\" border=\"0\" align=\"right\">
	<img src=\"img/opus.png\" width=\"75\" border=\"0\" align=\"right\">
	<img src=\"img/ogg_vorbis.png\" height=\"75\" border=\"0\" align=\"right\">
	<img src=\"img/mp3.png\" height=\"75\" border=\"0\" align=\"right\">";
	print "</td></tr></table>";




?>



<hr noshade>

<p>

<?php 
print_footer ("browse");
?>