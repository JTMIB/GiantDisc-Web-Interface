<?php

include "control_web.inc";
include "gdwebdef.php";

$playtp = set_get_cookie($setplaytp, "playtp", "dl", $HTTP_COOKIE_VARS);

print_header ("settings", "", "$cgi_dir", "$char_set", $output135, $output136, $output137, $output138);

print "<br><hr><br>";

$timestamp = time();
$datum = date("Y_m_d-H_i_s_", $timestamp);
#print "$datum<br>";
$add_name = "GiantDisc_Backup.sql";

$filename = "backup/$datum$add_name";

$mysqlExportPath = $filename;

$mysqlHostName = MYSQL_HOST;
#print "$mysqlHostName<br>";
$teile = explode(":", $mysqlHostName);
#print "$teile[0]<br>";
$mysqlHostName = $teile[0];
#print "$mysqlHostName<br>";

//Export der Datenbank und Ausgabe des Status
#$command='mysqldump --opt -h localhost -u' .MYSQL_USER .' -p' .MYSQL_PASS .' ' .MYSQL_DB .' > ' .$mysqlExportPath;
$command='mysqldump --opt -h '.$mysqlHostName .' -u' .MYSQL_USER .' -p' .MYSQL_PASS .' ' .MYSQL_DB .' > ' .$mysqlExportPath;

exec($command,$output,$worked);
switch($worked){
  case 0:
      print "$output152<b>" .MYSQL_DB ."</b>$output153".getcwd()."/" .$mysqlExportPath ."<br>";
  break;
  case 1:
#      print "Es ist ein Fehler aufgetreten beim Exportieren von <b>" .MYSQL_DB ."</b> zu ".getcwd()."/" .$mysqlExportPath ."<br>";
      echo 'Es ist ein Fehler aufgetreten beim Exportieren von <b>' .MYSQL_DB .'</b> zu '.getcwd().'/' .$mysqlExportPath .'</b>';
  break;
  case 2:
      print "$output154<br/><br/>
            <table><tr><td>MySQL Database Name:</td><td><b>" .MYSQL_DB ."</b></td></tr>
            <tr><td>MySQL User Name:</td><td><b>" .MYSQL_USER ."</b></td></tr>
            <tr><td>MySQL Password:</td><td><b>NOTSHOWN</b></td></tr>
            <tr><td>MySQL Host Name:</td><td><b>" .$mysqlHostName ."</b></td></tr></table>";
  break;
}

print "<br> <br>";
print "<table align='left' border='0' cellspacing='0' cellpadding='1'><tr><td class=\"menu".$sel."active\"><a class=\"menu\" href=\"database.php\">$output081</a></td></tr></table></p>";






#######################################################################################

?>