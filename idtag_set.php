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


print_header ("settings", "", "$cgi_dir", "$host", "$requ");

print "<h2>Set ID3-Tag from CD-Album</h2>";


touch ("/home/music/00/idtag_set.sh");
$dateiname = "/home/music/00/idtag_set.sh";
$fp = fopen( $dateiname, 'w');
fwrite( $fp, "#\n");


print "<p> </p>";
print "<table width=80%>";
#######################################################################
# einzelne Tracks
$ergebnis = mysql_db_query( "GiantDisc",
		"SELECT mp3file,
				artist,
				title,
				year,
				sourceid,
				mp3file
				FROM tracks
				WHERE mp3file LIKE 'trxx%.mp3' AND tracknb = '1'" );
$anz_reihen = mysql_num_rows( $ergebnis );

$a = 0;	# Spalte
$b = 0;	
$c = 1;	# Datensatz
$evenln = 1;	# grau oder weiss



while ( $datensatz = mysql_fetch_row( $ergebnis ) )
    {
    foreach ( $datensatz as $feld )
		{
		$a++;
		$b++;
		switch ($a)
			{
			case $a == 1:
    		if ($evenln){
    		  print "<tr class=\"lneven\">";
			}
			else{
    		  print "<tr class=\"lnodd\">";
			}			
			print "<td class=\"trklst\">$c</td>";
			break;

			case $a == 2:
			# Artist
			print "<td class=\"trklst\">$feld</td>";
			$artist = $feld;
			break;
			
			case $a == 3:
			# Titel
			print "<td class=\"trklst\">$feld</td>";
			$titel = $feld;
			break;

			case $a == 4:
			# Jahr
			print "<td class=\"trklst\">$feld</td>";
			$year = $feld;
			break;
			
			case $a == 5:
			# Disk-ID
			print "<td class=\"trklst\">$feld</td>";
			# Album
			$m = $n + 1;
			print "<td class=\"trklst\"> </td>";
			break;
						
			case $a == 6;
			# Dateiname
			print "<td class=\"trklst\">$feld</td><tr>";

			# Album
			fwrite( $fp, "mp3info ");				
			# Artist
			fwrite( $fp, "-a \"$artist\" ");			
			# Titel
			fwrite( $fp, "-t \"$titel\" ");			
			# Jahr
			fwrite( $fp, "-y \"$year\" ");			
			# Track
#			fwrite( $fp, "-n \"$c\" ");			
			# Dateiname
			fwrite( $fp, "/home/music/01/$feld\n");
			$a = 0;
			$c++;
		    $evenln = 1-$evenln; #toggle $evenln
			break;
						
			}
		}
	}
mysql_free_result($ergebnis);
# Ende - einzelne Tracks
#######################################################################	

$ergebnis = mysql_db_query( "GiantDisc", "SELECT * FROM album" );
#$anz_felder = mysql_num_fields( $ergebnis );
$anz_reihen = mysql_num_rows( $ergebnis );
mysql_free_result($ergebnis);
#print "<p>$anz_reihen</p>";

# CDID in ein Array kopieren
$i = 0;
$alben = mysql_db_query( "GiantDisc", "SELECT cddbid, title FROM album" );
while ( $datasatz = mysql_fetch_row( $alben ) )
    {
    foreach ( $datasatz as $field )
		{
		$id[$i] = $field;
		$i++;
#		print "<p>$field</p>";
		}
	}
mysql_free_result($alben);
# Ende - CDID in ein Array kopieren


$n = 0;
while ( $n/2 != $anz_reihen)
	{
	#print "<p>$n</p>";
# Abfrage Tracks
$ergebnis = mysql_db_query( "GiantDisc",
		"SELECT mp3file,
				artist,
				title,
				year,
				sourceid,
				mp3file
				FROM tracks
				WHERE sourceid = '$id[$n]' AND mp3file LIKE '%.mp3'" );
#				WHERE mp3file LIKE '%.mp3'" );								

#$anz_felder = mysql_num_fields( $ergebnis );
$a = 0;	# Spalte
$b = 0;	
$c = 1;	# Datensatz
$evenln = 1;	# grau oder weiss



while ( $datensatz = mysql_fetch_row( $ergebnis ) )
    {
    foreach ( $datensatz as $feld )
		{
		$a++;
		$b++;
		switch ($a)
			{
			case $a == 1:
    		if ($evenln){
    		  print "<tr class=\"lneven\">";
			}
			else{
    		  print "<tr class=\"lnodd\">";
			}			
			print "<td class=\"trklst\">$c</td>";
			break;

			case $a == 2:
			# Artist
			print "<td class=\"trklst\">$feld</td>";
			$artist = $feld;
			break;
			
			case $a == 3:
			# Titel
			print "<td class=\"trklst\">$feld</td>";
			$titel = $feld;
			break;

			case $a == 4:
			# Jahr
			print "<td class=\"trklst\">$feld</td>";
			$year = $feld;
			break;
			
			case $a == 5:
			# Disk-ID
			print "<td class=\"trklst\">$feld</td>";
			# Album
			$m = $n + 1;
			print "<td class=\"trklst\">$id[$m]</td>";
			break;
						
			case $a == 6;
			# Dateiname
			print "<td class=\"trklst\">$feld</td><tr>";

			# Album
			fwrite( $fp, "mp3info -l \"$id[$m]\" ");				
			# Artist
			fwrite( $fp, "-a \"$artist\" ");			
			# Titel
			fwrite( $fp, "-t \"$titel\" ");			
			# Jahr
			fwrite( $fp, "-y \"$year\" ");			
			# Track
			fwrite( $fp, "-n \"$c\" ");			
			# Dateiname
			fwrite( $fp, "/home/music/01/$feld\n");
			$a = 0;
			$c++;
		    $evenln = 1-$evenln; #toggle $evenln
			break;
						
			}
		}
	}
mysql_free_result($ergebnis);
# Abfrage Tracks Ende
	$n++;
	$n++;
	}

fclose( $fp );	
print "</table>";
print "<p> </p>";

#######################################################################


?>


<hr noshade>




</body>
</html>
