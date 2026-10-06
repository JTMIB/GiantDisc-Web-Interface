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
 
require_once('getid3/getid3.php');

print "get FLAC<br>";
$getID3 = new getID3;
$filename = "01/tr0x0e015702-1.flac";

echo "Working on file ".$filename."<br>";

    $ThisFileInfo = $getID3->analyze($filename);
		getid3_lib::CopyTagsToComments($ThisFileInfo);
    
    echo $ThisFileInfo['filenamepath'] . "<br>";
    echo (!empty($ThisFileInfo['comments_html']['title']) ? implode('<BR>', $ThisFileInfo['comments_html']['title']) : '&nbsp;') . "<br>";    
    echo (!empty($ThisFileInfo['comments_html']['artist']) ? implode('<BR>', $ThisFileInfo['comments_html']['artist']) : '&nbsp;') . "<br>";
    echo (!empty($ThisFileInfo['comments_html']['album']) ? implode('<BR>', $ThisFileInfo['comments_html']['album']) : '&nbsp;') . "<br>";
    echo (!empty($ThisFileInfo['comments_html']['year']) ? implode('<BR>', $ThisFileInfo['comments_html']['year']) : '&nbsp;') . "<br>";
    echo (!empty($ThisFileInfo['comments_html']['tracknumber']) ? implode('<BR>', $ThisFileInfo['comments_html']['tracknumber']) : '&nbsp;') . "<br>";
    
    echo round($ThisFileInfo['audio']['bitrate'] / 1000) . "<br>";
    echo $ThisFileInfo['playtime_string'] . "<br>";
    
print "<br>";
print "<br>";




?>

