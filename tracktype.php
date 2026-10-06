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

print "<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">
<html>
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">
<title>TrackType.org (beta)</title>
<style type=\"text/css\">
body,table,th,tr,td,div,span,form,h1,h2,h3 {border-width:0em;margin:0em;padding:0em;vertical-align:top}
body,table {background-color:ivory;color:black;font-family:sans-serif;width:100%}
table.page {border-collapse:collapse;height:100%}
td.menu {background-color:lightgrey;padding:1em;height:100%;width:12em}
td.body {height:100%;padding:1em}
a:hover {color:silver}
input[type=\"submit\"]:hover {background-color:silver}
input[type=\"submit\"] {background-color:beige;color:black;font-weight:bold;width:6em}
input[type=\"submit\"] {margin-right:3px}
input[type=\"submit\"][disabled] {background-color:ivory;color:gray}
input.n[type=\"submit\"],input.n[type=\"submit\"][disabled] {width:3em}
input[type=\"text\"] {background-color:linen;color:black;font-weight:bold}
label {color:gray}
b {color:black}
.head,.info,.fail {background-color:lightgrey;width:100%}
.info,.fail {font-weight:bold;text-align:right;width:100%}
.info {color:gray}
.fail {color:red}
.even {background-color:aliceblue}
.total {background-color:beige;color:gray;font-weight:bold}
.list th {padding-bottom:2px;text-align:left;vertical-align:bottom;color:gray}
.list td {padding-bottom:4px}
</style>
</head>
<body>
<div class=\"head\"><h1 style=\"color:gray;padding-left:3px\">TrackType.org (beta)</h1></div>
<div style=\"background-color:orange;margin-top:3px;padding:3px\">";

  $link =  mysql_connect( MYSQL_HOST, MYSQL_USER, MYSQL_PASS );
  if ( ! $link )
      die( "Keine Verbindung zu MySQL" );

$ergebnis = mysql_db_query( "GiantDisc", "SELECT cddbid FROM album_temp" );
while ( $datensatz = mysql_fetch_row( $ergebnis ) )
    {
    foreach ( $datensatz as $feld )
		{
		#print "$feld";
		}
	}

	
print"
<form method=\"POST\" action=\"http://www.tracktype.org/index.html?lDisc\">
<input type=\"text\" name=\"ifSearch\" tabindex=\"1\" value=\"$feld\">
<input type=\"radio\" name=\"ifFields\" tabindex=\"1\" value=\"3\" checked>DiscID
<input style=\"margin-left:2em\" type=\"submit\" accesskey=\"s\" name=\"iButton\" tabindex=\"1\" value=\"Search\">
</form>";

print "
</div>
</body>
</html>";

  mysql_free_result($recset);
  mysql_close( $link );

?>
