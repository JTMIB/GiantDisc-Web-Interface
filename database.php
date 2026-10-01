<?php

include "control_web.inc";
include "gdwebdef.php";

$playtp = set_get_cookie($setplaytp, "playtp", "dl", $HTTP_COOKIE_VARS);

print_header ("settings", "", "$cgi_dir", "$char_set", $output135, $output136, $output137, $output138);

print "<br><hr><br>";
print "<ul>";
print "<li> <table align='left' border='0' cellspacing='0' cellpadding='1'><tr><td class=\"menu".$sel."active\"><a class=\"menu\" href=\"backup.php\">$output151</a></td></tr></table></p>";

print "</ul>";

print "<br><hr><br>";
#######################################################################################
  $link =  mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
  if ( ! $link )
      die( "Keine Verbindung zu MySQL" );

// sql to create table
$createtable = mysqli_query( $link , "CREATE TABLE backupList (
                          filename VARCHAR(45) NOT NULL,
                          groesse VARCHAR(45) NOT NULL,
                          datum		varchar(10)
                          )");
                          

if ($handle = opendir('backup')) {
#    print "<table>";
    while (false !== ($entry = readdir($handle))) {
          if ($file === ".." or $file === ".") continue;

          $str_lang = strlen($entry);
          if ($str_lang > 2)
            {
#            print "<tr><td>$entry</td>";
            $groesse = filesize("backup/$entry")/1000000;
#            print "<td> $groesse MB</td>";
            $datum = filemtime("backup/$entry");
            $dateidatum = date("d.m.Y", $datum);
#            print "<td> $dateidatum</td>";
#            print "</tr>";
            
            $ergebnis = mysqli_query( $link, "INSERT INTO backupList (filename, groesse, datum) values('$entry', '$groesse', '$dateidatum')" );
            
            }
    }
#    print "</table>";

    closedir($handle);
    mysqli_close( $link );
    
##########################################
$link =  mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
  if ( ! $link )
      die( "Keine Verbindung zu MySQL" );    

#$abfrage = mysqli_query( $link,"SELECT filename, groesse, datum FROM backupList ORDER BY datum DESC" );
$abfrage = mysqli_query( $link,"SELECT filename, groesse, datum FROM backupList ORDER BY filename DESC" );
$anz_reihen = mysqli_num_rows( $abfrage );
#print "$anz_reihen<br>";

$evenln = 1;
echo("<table><tr><td><small><b>$anz_reihen $output156</b></small></td></tr>");
echo("<tr><td><small><b>$output005</b></small></td><td><small><b>MB</b></small></td><td><small><b>$output155</b></small></td><td> </td></tr>");
while($row = mysqli_fetch_object($abfrage))
{
	if ($evenln)
	{
	print "<tr class=\"lneven\">";
	}
else
	{
  print "<tr class=\"lnodd\">";
	}
	print "<td class=\"trklst\">$row->filename</td><td class=\"trklst\">$row->groesse</td><td class=\"trklst\">$row->datum</td><td><a href=\"restore.php?file=$row->filename\"><img src=\"img/restore.png\" border=\"0\" height=\"20\" alt=\"restore\" title=\"$output157\"></a></td></tr>";
	$evenln = 1-$evenln;
}
echo("</table>");
mysqli_free_result( $abfrage );
$abfrage = mysqli_query( $link, "DROP TABLE backupList" );
mysqli_close( $link );
##########################################

        
}
#######################################################################################

?>