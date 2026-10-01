<?php

include "control_web.inc";

#print_header ("browse", "", "$cgi_dir", "$host", "$requ");
print_header ("browse", "", "$cgi_dir", $char_set, $output135, $output136, $output137, $output138);

## Variablen aus Browser-Zeile
$id=$_GET['id'];

###########################################################################
### append a title to a playlists
#print "$id<br>";
p_append ($id, $output048, $output049);

?>

<hr noshade>

<p>

<?php
print_footer ("browse");
?>
