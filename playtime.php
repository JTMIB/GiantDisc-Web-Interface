<?php

require "control_web.inc";
require_once('getid3/getid3.php');

## Variablen aus Browser-Zeile
$dir=$_GET['dir'];
$file=$_GET['file']; 
$com=$_GET['com'];
$uri=$_GET['uri'];

#print "$dir<br>";
print "$file<br>";
#print "$com<br>";
#print "$uri<br>";

if ( strstr($file, ".mp3"))
  {
#  echo "Working on file ".$file."<br>";
  $getID3 = new getID3;
    $ThisFileInfo = $getID3->analyze("$dir/$file");
    
    $title = $ThisFileInfo['tags']['id3v1']['title'][0];
    $artist = $ThisFileInfo['tags']['id3v1']['artist'][0];
    $album = $ThisFileInfo['tags']['id3v1']['album'][0];
    $year = $ThisFileInfo['tags']['id3v1']['year'][0];
    $track = $ThisFileInfo['tags']['id3v1']['track'][0];
    $bitrate =  round($ThisFileInfo['audio']['bitrate'] / 1000);
    $time = $ThisFileInfo['playtime_string'];

    print "$time<br>";
    $anz_time = substr_count($time, ':');
    print "$anz_time<br>";
    if ( $anz_time == 2)
      {
      $einzeln = explode(':', $time);
      $stunden = $einzeln[0];
      $minuten = $einzeln[1];
      $sekunde = $einzeln[2];      
      }
    if ( $anz_time == 1)
      {
      $einzeln = explode(':', $time);
      $stunden = 0;
      $minuten = $einzeln[0];
      $sekunde = $einzeln[1];      
      }    
    if ( $anz_time == 0)
      {
#      $einzeln = explode(':', $time);
      $stunden = 0;
      $minuten = 0;
      $sekunde = $time;      
      }    

    $stunden = $stunden * 60 * 60;
    $length = $minuten * 60 + $sekunde + $stunden;
#    print "$stunden<br>";
#    print "$minuten<br>";
#    print "$sekunde<br>";
#    print "$length<br>";  
  }

if ( strstr($file, ".ogg"))
  {
  $getID3 = new getID3;
  $ThisFileInfo = $getID3->analyze("$dir/$file");
	getid3_lib::CopyTagsToComments($ThisFileInfo);
	
  $title = (!empty($ThisFileInfo['comments_html']['title']) ? implode('<BR>', $ThisFileInfo['comments_html']['title']) : '&nbsp;');
  $artist = (!empty($ThisFileInfo['comments_html']['artist']) ? implode('<BR>', $ThisFileInfo['comments_html']['artist']) : '&nbsp;');
  $album = (!empty($ThisFileInfo['comments_html']['album']) ? implode('<BR>', $ThisFileInfo['comments_html']['album']) : '&nbsp;');
  $year = (!empty($ThisFileInfo['comments_html']['date']) ? implode('<BR>', $ThisFileInfo['comments_html']['date']) : '&nbsp;');
  $track = (!empty($ThisFileInfo['comments_html']['tracknumber']) ? implode('<BR>', $ThisFileInfo['comments_html']['tracknumber']) : '&nbsp;');
  $bitrate =  round($ThisFileInfo['audio']['bitrate'] / 1000);
  $time = $ThisFileInfo['playtime_string'];

    $laengetime=(strlen($time));
    if ( $laengetime >= 6)
      {
      $stunde = strtok( $time, ":");
      $stunde = $stunde * 60 *60;
      }
    else
      {
      $stunde = 0;
      }  
  $minute = strtok( $time, ":");
  $sekunde = strtok ( ":");
  $length = $minute * 60 + $sekunde + $stunde;
#  print "$length<br>"; 
  }

if ( strstr($file, ".flac"))
  {
  $getID3 = new getID3;
  $ThisFileInfo = $getID3->analyze("$dir/$file");
  getid3_lib::CopyTagsToComments($ThisFileInfo);

  $title = (!empty($ThisFileInfo['comments_html']['title']) ? implode('<BR>', $ThisFileInfo['comments_html']['title']) : '&nbsp;');
  $artist = (!empty($ThisFileInfo['comments_html']['artist']) ? implode('<BR>', $ThisFileInfo['comments_html']['artist']) : '&nbsp;');
  $album = (!empty($ThisFileInfo['comments_html']['album']) ? implode('<BR>', $ThisFileInfo['comments_html']['album']) : '&nbsp;');
  $year = (!empty($ThisFileInfo['comments_html']['year']) ? implode('<BR>', $ThisFileInfo['comments_html']['year']) : '&nbsp;');
  $track = (!empty($ThisFileInfo['comments_html']['tracknumber']) ? implode('<BR>', $ThisFileInfo['comments_html']['tracknumber']) : '&nbsp;');
  $bitrate =  round($ThisFileInfo['audio']['bitrate'] / 1000);
  $time = $ThisFileInfo['playtime_string'];

    $laengetime=(strlen($time));
    if ( $laengetime >= 6)
      {
      $stunde = strtok( $time, ":");
      $stunde = $stunde * 60 *60;
      }
    else
      {
      $stunde = 0;
      }  
  $minute = strtok( $time, ":");
  $sekunde = strtok ( ":");
  $length = $minute * 60 + $sekunde + $stunde;
#  print "$length<br>"; 
  }



echo <<<HEAD1
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=$char_set">
<META NAME="language" CONTENT="de">
<META NAME="author" CONTENT="Juergen Thoens">
<META NAME="publisher" CONTENT="Juergen Thoens">
<META NAME="copyright" CONTENT="Juergen Thoens">
<META NAME="description" CONTENT="Juergen Thoens">
<meta name="keywords" content="GiantDisc">
<link REL="SHORTCUT ICON" HREF="img/gd16.ico" >
<link rel="stylesheet" href="gdweb.css">
HEAD1;


print "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=$uri?file=$file&com=$com&length=$length\">";
print "<title>GiantDisc&nbsp;Web&nbsp;Interface: </title></head>";
print "<body>";

    print "$title<br>";
    print "$artist<br>"; 
    print "$album<br>"; 
    print "$year<br>"; 
    print "$track<br>";    
    print "$bitrate<br>";
    print "$time<br>";

print "</body>";
print "</html>";

?>
