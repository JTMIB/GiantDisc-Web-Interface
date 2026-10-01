<?php

require "control_web.inc";
#include "gdweb.inc";
include "gdwebdef.php";

$host = getenv(HTTP_HOST);
$requ = getenv(REQUEST_URI);


#$playtp = set_get_cookie($setplaytp, "playtp", "dl", $HTTP_COOKIE_VARS);


#######################################################################################
#$benutzer = "music";
#$passwort = "music";
$db = "GiantDisc";
$link =  mysql_connect( MYSQL_HOST, MYSQL_USER, MYSQL_PASS );
if ( ! $link )
    die( "Keine Verbindung zu MySQL" );
mysql_select_db( $db, $link )
    or die ( "Konnte Datenbank \"$db\" nicht öffnen: ".mysql_error() );


if ($com == "playtime")
	{
	# mp3file ermitteln
	$wort = strtok($file, "/");
	while (is_string( $wort ) )
		{
		if ( $wort )
			{
			$file = $wort;
			}
		$wort = strtok( "/" );
		}
	# length in Sekunden ermitteln
	$wort = strtok($length, ":");
	$length = 0;
	while (is_string( $wort ) )
		{
		if ( $wort )
			{
			if ($length == 0)
				{
				$length = $wort;
				}
			else
				{
				$length = $length * 60;
				$length = $length + $wort;
				}
			}
		$wort = strtok( ":" );
		}

#	print "<P>$file<BR>$length</P>";

	# Zeile aktualisieren
	$ergebnis = mysql_query( "UPDATE tracks SET length = $length WHERE mp3file = '$file'" );

	track_temp();
	# Tracks mit fehlender Länge kopieren
	$ergebnis = mysql_query( "INSERT INTO tracks_temp SELECT * FROM tracks WHERE length = 0" );

	
	$ergebnis = mysql_query( "SELECT length, mp3file FROM tracks_temp WHERE length = 0" );
	$anz_track = mysql_num_rows( $ergebnis );

while ( $datensatz = mysql_fetch_row( $ergebnis ) )
    {
    foreach ( $datensatz as $feld )
		  {
			$x++;
			if ($x == 1)
				{
				if ($feld == 0)
					$length = 0;
				else
					$length =1;
				}
			if ($x == 2)
				{
				$a++;
				$x = 0;
#				if ($length == 0)
#					print "<TR><TD>$a</TD><TD>$feld</TD><TD align=center>$length</TD></TR>";
				}
		  }
    }


# alternativer Header
echo <<<HEAD1
<html>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<META NAME="language" CONTENT="de">
<META NAME="author" CONTENT="Jürgen Thöns">
<META NAME="publisher" CONTENT="Jürgen Thöns">
<META NAME="copyright" CONTENT="Jürgen Thöns">
<META NAME="description" CONTENT="Jürgen Thöns">
<meta name="keywords" content="GiantDisc">
<link REL="SHORTCUT ICON" HREF="img/gd16.ico" >
<link rel="stylesheet" href="gdweb.css">
<script type="text/javascript" language="javascript" src="navi.js"></script>
HEAD1;


if ($anz_track > 0)
	{
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
#				print "<p>$requ</p>";
				}
			}
		$wort = strtok( "?" );
		}

	print "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=$cgi_dir/music.pl?file=/home/music/01/$feld&com=playtime&uri=http://$host$requ\">";
	print "<SCRIPT LANGUAGE=\"JavaScript\"><!-- //
setTimeout('window.location.href=\"$cgi_dir/music.pl?file=/home/music/01/$feld&com=playtime&uri=http://$host$requ\"', 100);
// --></SCRIPT>";
	}
else
	{
	print "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"2; URL=index.php\">";
	}


print "<title>GiantDisc Control Interface: $current</title></head>";
echo <<<HEAD2
<table border="0" cellpadding="0" cellspacing="0" width="100%" height="75">

<tr height="46" bgcolor="#ffffff">
<td height="46"><h2>GiantDisc Control Interface</h2>
</td>

<td height="46"><br>
</td>

<td height="46"></td>
</tr>
</table>
HEAD2;

	print "<p>$output002 $anz_track $output003</p>";

	if ($anz_track > 0)
		{
		print "</TABLE></td>";
		print "<td valign=top><P>&nbsp;&nbsp;&nbsp;&nbsp;</P><table><tr><td class=\"menuactive\">";
		print "<a class=\"menuinactive\" href=$cgi_dir/music.pl?file=/home/music/01/$feld&com=playtime&uri=http://$host$requ>$output007</a>";
		print "</td></tr></table></td></tr></table>";
		}


	}
else
	{
	#print_head ("info");
	print_header("info",  "", "$cgi_dir", "$char_set");
	track_temp();
	# Tracks mit fehlender Länge kopieren
	$ergebnis = mysql_query( "INSERT INTO tracks_temp SELECT * FROM tracks WHERE length = 0" );


#$ergebnis = mysql_query( "SELECT * FROM album" );
#$anz_album = mysql_num_rows( $ergebnis );

$ergebnis = mysql_query( "SELECT length, mp3file FROM tracks_temp WHERE length = 0" );
$anz_track = mysql_num_rows( $ergebnis );

print "$output002 ";
print "$anz_track";
print " $output003";

print "<TABLE border=1><TR><td><TABLE border=1>";
print "<TR><TD><B>$output004</B></TD><TD><B>$output005</B></TD><TD><B>$output006</B></TD></TR>";
$a = 0;
$x = 0;
while ( $datensatz = mysql_fetch_row( $ergebnis ) )
    {
    foreach ( $datensatz as $feld )
		  {
			$x++;
			if ($x == 1)
				{
				if ($feld == 0)
					$length = 0;
				else
					$length =1;
				}
			if ($x == 2)
				{
				$a++;
				$x = 0;
				if ($length == 0)
					print "<TR><TD>$a</TD><TD>$feld</TD><TD align=center>$length</TD></TR>";
				}
		  }
    }


print "</TABLE></td>";

print "<td valign=top><P>&nbsp;&nbsp;&nbsp;&nbsp;</P><table><tr><td class=\"menuactive\"><a class=\"menuinactive\" href=$cgi_dir/music.pl?file=/home/music/01/$feld&com=playtime&uri=http://$host$requ>$output007</a></td></tr></table></td>";

print "</tr></table>";

	}

$droptable =mysql_query("DROP TABLE tracks_temp");
mysql_close( $link );



?>

<hr noshade>

<p>

<?php

print_foot ("search");

?>
