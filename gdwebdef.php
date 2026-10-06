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
 

if (!$HTTP_COOKIE_VARS["gdwebintset"]){
  # if no cookie set, set default values
  $showartist     = set_get_cbx_cookie(/*set it*/1, "showartist", 1, $HTTP_COOKIE_VARS);
  $showtitle      = set_get_cbx_cookie(/*set it*/1, "showtitle",  1, $HTTP_COOKIE_VARS);
  $showcomposer   = set_get_cbx_cookie(/*set it*/1, "showcomposer",  1, $HTTP_COOKIE_VARS);  
  $showgenre      = set_get_cbx_cookie(/*set it*/1, "showgenre", 0,  $HTTP_COOKIE_VARS);
  $showlang       = set_get_cbx_cookie(/*set it*/1, "showlang", 0,   $HTTP_COOKIE_VARS);
  $showyear       = set_get_cbx_cookie(/*set it*/1, "showyear", 1,   $HTTP_COOKIE_VARS);
  $showrating     = set_get_cbx_cookie(/*set it*/1, "showrating", 1, $HTTP_COOKIE_VARS);
  $showlength     = set_get_cbx_cookie(/*set it*/1, "showlength", 1, $HTTP_COOKIE_VARS);
  $showedit       = set_get_cbx_cookie(/*set it*/1, "showedit", 0,   $HTTP_COOKIE_VARS);
  $showdownload   = set_get_cbx_cookie(/*set it*/1, "showdownload", 1,   $HTTP_COOKIE_VARS);
  $showtoplaylist = set_get_cbx_cookie(/*set it*/1, "showtoplaylist", 0, $HTTP_COOKIE_VARS);
  $playtp = set_get_cookie($setplaytp, "playtp", "dl", $HTTP_COOKIE_VARS);
}
else{
  $playtp = set_get_cookie(false, "gdwebplaytp", "dl", $HTTP_COOKIE_VARS);

  $showartist = $HTTP_COOKIE_VARS["showartist"];  
  $showtitle  = $HTTP_COOKIE_VARS["showtitle"];  
}

?>
