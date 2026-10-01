<?php

include "control_web.inc";


print_header ("record", "", "$cgi_dir", "$char_set", $output135, $output136, $output137, $output138);

#$dbh = mysql_connect();	


### Show first level:
print "<p>";
print "<table class='browsemenu'>";
print "<tr>";
#print "<td><small><b><a href=\"read_inbox_album.php?schritt=1\">$output071</a></b></small></td>";
#print "<td><small><b><a href=\"read_inbox_track.php?schritt=1\">$output072</a></b></small></td>";
if ($read_CD == "yes")
  {
  print "<td><small><b><a href=\"read_from_cd.php\">$output053</a></b></small></td>";
  }
print "<td><small><b><a href=\"file_upload.php\">$output077</a></b></small></td>";
print "<td><small><b><a href=\"album_upload.php?schritt=0\">$output076</a></b></small></td>";
print "</tr>";
print "</small>";

print "</table>";



?>



<hr noshade>

<p>

<?php 
print_footer ("browse");
?>