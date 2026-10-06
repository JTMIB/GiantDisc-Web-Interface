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
 
print "<h1>Version</h1><br>";
$PHPVersion = phpversion();
//Ausgabe
echo"Version:".$PHPVersion;
print "<br>";

$a = 0;
$teil = strtok ( $PHPVersion, "." );
while ($teil) {
	$ver[$a] = $teil;
	print "$ver[$a]<br>";
	$a++;
	$teil = strtok (".");
}

if ( (int)$ver[0] > 5 )
	print "richtige Hauptversion";
else
{
if ( (int)$ver[0] < 5 )
	print "falsche Version";
else
{
if ( (int)$ver[1] > 0 )
	print "richtige Hauptversion";
else
{
if ( (int)$ver[2] >= 5 )
	print "richtige Hauptversion";
}}}


?>

