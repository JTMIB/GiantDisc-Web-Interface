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
include "gdwebdef.php";

$file=$_GET['file'];
$comand=$_GET['comand'];

$playtp = set_get_cookie($setplaytp, "playtp", "dl", $HTTP_COOKIE_VARS);

print_header ("settings", "", "$cgi_dir", "$char_set", $output135, $output136, $output137, $output138);

print "<br><hr><br>";
#######################################################################################

#print "$file<br>";
#print "$comand<br>";
#print " <br>";

if ($comand != "fullrestore")
{
print "<table><tr class=\"lneven\"><td class=\"trklst\">";
print "$output162 <b>$file !</b>";
print "</td>";
print "<td><a href=\"restore.php?file=$file&comand=fullrestore\"><img src=\"img/restore.png\" border=\"0\" height=\"25\" alt=\"restore\" title=\"$output158\"></a></td>";
print "</tr></table>";
print "<br><hr><br>";
}

#######################################################################################

if ($comand == "fullrestore")
{
#print "Restore<br>";

//Tragen Sie hier Ihre Datenbankinformationen ein und den Namen der Backup-Datei
$mysqlImportFilename ="backup/$file";
$mysqlHostName = MYSQL_HOST;
#print "$mysqlHostName<br>";
$teile = explode(":", $mysqlHostName);
#print "$teile[0]<br>";
$mysqlHostName = $teile[0];
#print "$mysqlHostName<br>";

//Bei den folgenden Punkten bitte keine Änderung durchführen
//Import der Datenbank und Ausgabe des Status
$command='mysql -h' .$mysqlHostName .' -u' .MYSQL_USER .' -p' .MYSQL_PASS .' ' .MYSQL_DB .' < ' .$mysqlImportFilename;
exec($command,$output,$worked);
switch($worked){
  case 0:
#    echo 'Die Daten aus der Datei <b>' .$file .'</b> wurden erfolgreich in der Datenbank <b>' .MYSQL_DB .'</b> eingespielt';
    print "$output160 <b>" .$file ."</b> $output161 <b>" .MYSQL_DB ."</b> $output150";
  break;
  case 1:
    print "$output159<br/><br/><table><tr><td>MySQL Database Name:</td><td><b>" .MYSQL_DB ."</b></td></tr><tr><td>MySQL User Name:</td><td><b>" .MYSQL_USER ."</b></td></tr><tr><td>MySQL Password:</td><td><b>NOTSHOWN</b></td></tr><tr><td>MySQL Host Name:</td><td><b>" .$mysqlHostName ."</b></td></tr><tr><td>MySQL Import Dateiname:</td><td><b>" .$mysqlImportFilename ."</b></td></tr></table>";
  break;
}
print "<br><hr><br>";
print "<br> <br>";
print "<table align='left' border='0' cellspacing='0' cellpadding='1'><tr><td class=\"menu".$sel."active\"><a class=\"menu\" href=\"database.php\">$output081</a></td></tr></table></p>";

}


#######################################################################################
?>