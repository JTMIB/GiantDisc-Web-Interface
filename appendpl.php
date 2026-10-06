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
