<?php

include "control_web.inc";
include "gdwebdef.php";

$host = getenv('HTTP_HOST');

$playtp = set_get_cookie($setplaytp, "playtp", "dl", $HTTP_COOKIE_VARS);

print_header ("settings", "", "$cgi_dir", "$char_set", $output135, $output136, $output137, $output138);

?>

<?php
print "<h3>General Options</h3>";
print "<ul>

  <li> <a href=\"init.php\">$output074</a></li>
  <li> <a href=\"setting.php\">$output139</a></li>
</ul>";


print "<h3>$output064</h3>
<ul>
  <li> $output056 <a href=\"opt-consistency-album.php?emptyalb=1\">$output057</a> $output058
  <li> $output056 <a href=\"opt-consistency-album.php?nocovers=1\">$output059</a> $output060
  <li> $output056 $output061 <a href=\"corrpt.php\">$output063</a> $output062</li>
</ul>";
?>

<?php
#print "<h3>$output068</h3><ul>  <li> $output065 <a target=\"_blank\" href=\"gdgenretrans.php\">genre $output066</a> $output067</ul>";
?>

<?php
# DB Statistics
print "<h3>$output069</h3>
<ul>
  <li> <a href=\"info.php\">$output070</a>
</ul>
<h3>$output150</h3>
<ul>
  <li> <a href=\"http://$host/$phpmyadmin/\" target=\"_blank\">phpMyAdmin</a>
  <li> <a href=\"database.php\" >Backup & Restore</a>
</ul>

<hr>

<ul>
  <li> <a href=\"cont_gdsel.php\">$output073</a>
  <li> <a href=\"cont_gdalb.php\">$output094</a>
</ul>";
?>


<?php
    if ($shutdown_enable == "yes")
    {
    print "<hr>";
    print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    $meineIP = $_SERVER['SERVER_ADDR'];
    print "<a href=\"http://$meineIP:8080/shutdown\" target=\"neu\"><img src=\"img/power_off_button_quadrat.png\" height=\"75\" border=\"0\" title=\"$output163\"></a>";
    }

print_footer ("settings");
?>
