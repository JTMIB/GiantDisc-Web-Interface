<?php

include "control_web.inc";
#include "playlist.inc";
#include "gdwebdef.php";

$playtp = set_get_cookie($setplaytp, "playtp", "dl", $HTTP_COOKIE_VARS);

print_header ("settings", "", "$cgi_dir", "$char_set", $output135, $output136, $output137, $output138);

#######################################################################################
### Info

$link =  mysqli_connect( MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB );

 if ( ! $link )
     die( "Keine Verbindung zu MySQL" );
      
$ergebnis = mysqli_query( $link, "SELECT * FROM album" );
$anz_album = mysqli_num_rows( $ergebnis );

$ergebnis = mysqli_query( $link, "SELECT mp3file FROM tracks" );
$anz_track = mysqli_num_rows( $ergebnis );

$db_00 = diskfreespace("$tempdir/");
$db_01 = diskfreespace("$mp3dir/");
$db_02 = diskfreespace("$imagedir/");


$db_use = 0;
$db_use00 = 0;
$db_use01 = 0;
$db_use02 = 0;
while ( $datensatz = mysqli_fetch_row( $ergebnis ) )
    {
    foreach ( $datensatz as $feld )
		  {
#			$db_use = filesize("/home/music/00/$feld");
#			$db_use00 = $db_use00 + $db_use;
#			$db_use = filesize("/home/music/01/$feld");
#			$db_use01 = $db_use01 + $db_use;
#			$db_use = filesize("/home/music/02/$feld");
#			$db_use02 = $db_use02 + $db_use

			if (file_exists("$tempdir/$feld") == true)
				{
				$db_use = filesize("$tempdir/$feld");
				$db_use00 = $db_use00 + $db_use;
				}
			if (file_exists("$mp3dir/$feld") == true)
				{
				$db_use = filesize("$mp3dir/$feld");
				$db_use01 = $db_use01 + $db_use;
				}
			if (file_exists("$imagedir/$feld") == true)
				{
				$db_use = filesize("$imagedir/$feld");
				$db_use02 = $db_use02 + $db_use;
				}

		  }
    }

#cover	
$ergebnis = mysqli_query( $link, "SELECT coverimg FROM album" );
$anz_album = mysqli_num_rows( $ergebnis );
while ( $datensatz = mysqli_fetch_row( $ergebnis ) )
    {
    foreach ( $datensatz as $feld )
		  {
		  if ($feld != NULL)
		  {
			if (file_exists("$tempdir/$feld") == true)
				{
				$db_use = filesize("$tempdir/$feld");
				$db_use00 = $db_use00 + $db_use;
				}
			if (file_exists("$mp3dir/$feld") == true)
				{
				$db_use = filesize("$mp3dir/$feld");
				$db_use01 = $db_use01 + $db_use;
				}
			if (file_exists("$imagedir/$feld") == true)
				{
				$db_use = filesize("$imagedir/$feld");
				$db_use02 = $db_use02 + $db_use;
				}
		  }	
		  }
    }
mysqli_free_result($ergebnis);


$db_00un = "KByte";
$db_use00 = $db_use00 / 1024;
if ($db_use00 > 10000)
	{
	$db_use00 = $db_use00 / 1024;
	$db_00un = "MByte";
	}

$db_01un = "KByte";
$db_use01 = $db_use01 / 1024;
if ($db_use01 > 10000)
	{
	$db_use01 = $db_use01 / 1024;
	$db_01un = "MByte";
	}

$db_02un = "KByte";
$db_use02 = $db_use02 / 1024;
if ($db_use02 > 10000)
	{
	$db_use02 = $db_use02 / 1024;
	$db_02un = "MByte";
	}

$db_use00 = (int) $db_use00;
$db_use01 = (int) $db_use01;
$db_use02 = (int) $db_use02;

$db_00 = $db_00 / 1024 / 1024;
$db_01 = $db_01 / 1024 / 1024;
$db_02 = $db_02 / 1024 / 1024;
$db_00 = (int) $db_00;
$db_01 = (int) $db_01;
$db_02 = (int) $db_02;



#sumary time
$stime = 0;
#hour
$sth = 0;
#minute
$stm = 0;

$ergebnis = mysqli_query( $link, "SELECT length FROM tracks" );
$anz_zeil = mysqli_num_rows( $ergebnis );
while ( $datensatz = mysqli_fetch_row( $ergebnis ) )
    {
    foreach ( $datensatz as $feld )
		  {
		  $stime = $stime + $feld;
		  }
	}
mysqli_free_result($ergebnis);
while ( $stime > 3600)
	{
	$sth++;
	$stime = $stime - 3600;
	}
while ( $stime > 60)
	{
	$stm++;
	$stime = $stime - 60;
	}



print "<p> </p>
<TABLE>
<TR><TD>&nbsp;</TD><TD>&nbsp;</TD><TD>&nbsp;</TD></TR>
<TR><TD><small><B>$output013</B></small></TD><TD>&nbsp;</TD><TD>&nbsp;</TD></TR>
<TR><TD><small><B>Album</B></small></TD><TD>&nbsp;</TD><TD><small>$anz_album</small></TD></TR>
<TR><TD><small><B>Track</B></small></TD><TD>&nbsp;</TD><TD><small>$anz_track</small></TD></TR>
<TR><TD>&nbsp;</TD><TD>&nbsp;</TD><TD>&nbsp;</TD></TR>

<tr><TD><small><B>$output055</B></small></TD><TD>&nbsp;</TD><TD><small>$sth:$stm:$stime $output054</small></TD></tr>
<tr><TD>&nbsp;</TD><TD>&nbsp;</TD><TD>&nbsp;</TD></tr>

<TR><TD><small><B>$output009</B></small></TD><TD>&nbsp;&nbsp;</TD><TD><small><B>$output010</B></small></TD><TD>&nbsp;&nbsp;</TD><TD><small><B>$output011</B></small></TD></TR>

<TR><TD><small><B>00</B></small></TD><TD>&nbsp;</TD><TD><small>$db_use00 $db_00un</small></TD><TD>&nbsp;</TD><TD><small>$db_00 MByte</small></TD></TR>

<TR><TD><small><B>01</B></small></TD><TD>&nbsp;</TD><TD><small>$db_use01 $db_01un</small></TD><TD>&nbsp;</TD><TD><small>$db_01 MByte</small></TD></TR>

<TR><TD><small><B>02</B></small></TD><TD>&nbsp;</TD><TD><small>$db_use02 $db_02un</small></TD><TD>&nbsp;</TD><TD><small>$db_02 MByte</small></TD></TR>


</TABLE>";





mysqli_close( $link );
?>

<hr noshade>

<p>

<?php
print_foot ("search");
?>
