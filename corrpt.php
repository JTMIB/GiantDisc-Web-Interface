<?php

require "control_web.inc";
#include "gdwebdef.php";

$host = getenv('HTTP_HOST');
$requ = getenv('REQUEST_URI');

  $link =  mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
  if ( ! $link )
      die( "Keine Verbindung zu MySQL" );

## Variablen aus Browser-Zeile
$file=$_GET['file']; 
$com=$_GET['com'];
$length=$_GET['length'];  
      
#$playtp = set_get_cookie($setplaytp, "playtp", "dl", $HTTP_COOKIE_VARS);

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

#######################################################################################

if (($com == "playtime") OR ($com == "auto"))
	{
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
	$ergebnis = mysqli_query( $link , "UPDATE tracks SET length = $length WHERE mp3file = '$file'");
	}

	track_temp();
	# Tracks mit fehlender Laenge kopieren
	# nur mp3 und ogg und flac und opus
	$ergebnis = mysqli_query( $link , "INSERT INTO tracks_temp SELECT * FROM tracks WHERE length = 0 and mp3file LIKE '%mp3%'" );
	$ergebnis = mysqli_query( $link , "INSERT INTO tracks_temp SELECT * FROM tracks WHERE length = 0 and mp3file LIKE '%ogg%'" );
	$ergebnis = mysqli_query( $link , "INSERT INTO tracks_temp SELECT * FROM tracks WHERE length = 0 and mp3file LIKE '%flac%'" );
	if ( $read_CD == "yes")
	   {
     $ergebnis = mysqli_query( $link , "INSERT INTO tracks_temp SELECT * FROM tracks WHERE length = 0 and mp3file LIKE '%opus%'" );
     $ergebnis = mysqli_query( $link , "INSERT INTO tracks_temp SELECT * FROM tracks WHERE length = 0 and mp3file LIKE '%m4a%'" );
     }
  


	$ergebnis = mysqli_query( $link , "SELECT length, mp3file FROM tracks_temp WHERE length = 0" );
	$anz_track = mysqli_num_rows( $ergebnis );

while ( $datensatz = mysqli_fetch_row( $ergebnis ) )
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
<meta http-equiv="Content-Type" content="text/html; charset=$char_set">
<META NAME="language" CONTENT="de">
<META NAME="author" CONTENT="Juergen Thoens">
<META NAME="publisher" CONTENT="Juergen Thoens">
<META NAME="copyright" CONTENT="Juergen Thoens">
<META NAME="description" CONTENT="Juergen Thoens">
<meta name="keywords" content="GiantDisc">
<link REL="SHORTCUT ICON" HREF="img/gd16.ico" >
<link rel="stylesheet" href="gdweb.css">
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

  if (str_contains($feld, 'flac'))
      {
      print "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=playtime.php?dir=$mp3dir&file=$feld&com=playtime&uri=http://$host$requ\">";
      }
  else
      {
      if ( $read_CD == "yes" )
        {
        print "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=$cgi_dir/music.pl?file=$mp3dir/$feld&com=playtime&uri=http://$host$requ\">";
        }
      else
        {
        print "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=playtime.php?dir=$mp3dir&file=$feld&com=playtime&uri=http://$host$requ\">";
        }
     
      }

	}
else
	{
	print "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"2; URL=index.php\">";
	}


print "<title>GiantDisc&nbsp;Web&nbsp;Interface: $current</title></head>";
echo <<<HEAD2
<table border="0" cellpadding="0" cellspacing="0" width="100%" height="75">

<tr height="46">
<td height="46"><div class='maintitle'>GiantDisc&nbsp;Web&nbsp;Interface</div>
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
		if (file_exists("$tempdir/$feld") == true)
			{
      if (str_contains($feld, 'flac'))
#			if ($phpver == true)
			  {
 			  print "<a class=\"menuinactive\" href=playtime.php?file=$tempdir/$feld&com=playtime&uri=http://$host$requ>$output007</a>";
        }
      else
        {
        if ( $read_CD == "yes" )
          {
          print "<a class=\"menuinactive\" href=$cgi_dir/music.pl?file=$tempdir/$feld&com=playtime&uri=http://$host$requ>$output007</a>";
          }
        else
          {
          print "<a class=\"menuinactive\" href=playtime.php?file=$tempdir/$feld&com=playtime&uri=http://$host$requ>$output007</a>";
          }
        
        
# 			  print "<a class=\"menuinactive\" href=$cgi_dir/music.pl?file=$tempdir/$feld&com=playtime&uri=http://$host$requ>$output007</a>";
        }
			}
		if (file_exists("$mp3dir/$feld") == true)
			{
      if (str_contains($feld, 'flac'))
#			if ($phpver == true)
			 {
			 print "<a class=\"menuinactive\" href=playtime.php?file=$mp3dir/$feld&com=playtime&uri=http://$host$requ>$output007</a>";
       }
       else
       {
        if ( $read_CD == "yes" )
          {
          print "<a class=\"menuinactive\" href=$cgi_dir/music.pl?file=/home/music/02/$feld&com=playtime&uri=http://$host$requ>$output007</a>";
          }
        else
          {
          print "<a class=\"menuinactive\" href=playtime.php?file=/home/music/02/$feld&com=playtime&uri=http://$host$requ>$output007</a>";
          }        
   
#			 print "<a class=\"menuinactive\" href=$cgi_dir/music.pl?file=$mp3dir/$feld&com=playtime&uri=http://$host$requ>$output007</a>";
       }
			}

# kann man löschen			
		if (file_exists("/home/music/02/$feld") == true)
			{
      if (str_contains($feld, 'flac'))
#			if ($phpver == true)
			  {
			 print "<a class=\"menuinactive\" href=playtime.php?file=/home/music/02/$feld&com=playtime&uri=http://$host$requ>$output007</a>";
        }
      else
        {
        if ( $read_CD == "yes" )
          {
          print "<a class=\"menuinactive\" href=$cgi_dir/music.pl?file=/home/music/02/$feld&com=playtime&uri=http://$host$requ>$output007</a>";
          }
        else
          {
          print "<a class=\"menuinactive\" href=playtime.php?file=/home/music/02/$feld&com=playtime&uri=http://$host$requ>$output007</a>";
          }        
#			 print "<a class=\"menuinactive\" href=$cgi_dir/music.pl?file=/home/music/02/$feld&com=playtime&uri=http://$host$requ>$output007</a>";
        } 
			}
		print "</td></tr></table></td></tr></table>";
		}


	}
else
	{
#	print "<p>Hier!</p>";	
	print_header ("settings", "", "$cgi_dir", "$char_set", $output135, $output136, $output137, $output138);
#	print_head ("info", "$cgi_dir", "$host", "$requ");

	track_temp();
	# Tracks mit fehlender L�nge kopieren
	# nur mp3 und ogg	und flac und opus
	$ergebnis = mysqli_query( $link, "INSERT INTO tracks_temp SELECT * FROM tracks WHERE length = 0 and mp3file LIKE '%mp3%'" );
	$ergebnis = mysqli_query( $link , "INSERT INTO tracks_temp SELECT * FROM tracks WHERE length = 0 and mp3file LIKE '%ogg%'" );
	
if ($phpver == true)
  {
  $ergebnis = mysqli_query( $link , "INSERT INTO tracks_temp SELECT * FROM tracks WHERE length = 0 and mp3file LIKE '%flac%'" );
  if ($read_CD == "yes" )
      {
      $ergebnis = mysqli_query( $link , "INSERT INTO tracks_temp SELECT * FROM tracks WHERE length = 0 and mp3file LIKE '%opus%'" );
      $ergebnis = mysqli_query( $link , "INSERT INTO tracks_temp SELECT * FROM tracks WHERE length = 0 and mp3file LIKE '%m4a%'" );
      }
  }
#	$ergebnis = mysql_db_query( "GiantDisc", "SELECT * FROM tracks WHERE length = 0" );


$ergebnis = mysqli_query( $link , "SELECT length, mp3file FROM tracks_temp WHERE length = 0" );
$anz_track = mysqli_num_rows( $ergebnis );

print "$output002 ";
print "$anz_track";
print " $output003";

print "<TABLE border=1><TR><td><TABLE border=1>";
print "<TR><TD><B>$output004</B></TD><TD><B>$output005</B></TD><TD><B>$output006</B></TD></TR>";
$a = 0;
$x = 0;
while ( $datensatz = mysqli_fetch_row( $ergebnis ) )
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

#  print "<td valign=top><P>&nbsp;&nbsp;&nbsp;&nbsp;</P><table><tr><td class=\"menuactive\"><a class=\"menuinactive\" href=$cgi_dir/music.pl?file=$mp3dir/$feld&com=playtime&uri=http://$host$requ>$output007</a></td></tr></table></td>";

#if ($phpver == true)
if (str_contains($feld, 'flac'))
  {
  print "<td valign=top><P>&nbsp;&nbsp;&nbsp;&nbsp;</P><table><tr><td class=\"menuactive\"><a class=\"menuinactive\" href=playtime.php?dir=$mp3dir&file=$feld&com=playtime&uri=http://$host$requ>$output007</a></td></tr></table></td>";
  }
else
  {
        if ( $read_CD == "yes" )
          {
          print "<td valign=top><P>&nbsp;&nbsp;&nbsp;&nbsp;</P><table><tr><td class=\"menuactive\"><a class=\"menuinactive\" href=$cgi_dir/music.pl?file=$mp3dir/$feld&com=playtime&uri=http://$host$requ>$output007</a></td></tr></table></td>";
          }
        else
          {
          print "<td valign=top><P>&nbsp;&nbsp;&nbsp;&nbsp;</P><table><tr><td class=\"menuactive\"><a class=\"menuinactive\" href=playtime.php?dir=$mp3dir&file=$feld&com=playtime&uri=http://$host$requ>$output007</a></td></tr></table></td>";
          }
#  print "<td valign=top><P>&nbsp;&nbsp;&nbsp;&nbsp;</P><table><tr><td class=\"menuactive\"><a class=\"menuinactive\" href=$cgi_dir/music.pl?file=$mp3dir/$feld&com=playtime&uri=http://$host$requ>$output007</a></td></tr></table></td>";
  }  

print "</tr></table>";

	}

$droptable = mysqli_query( $link , "DROP TABLE tracks_temp");


mysqli_close( $link );

?>

<hr noshade>

<p>

<?php

print_foot ("search");

?>
