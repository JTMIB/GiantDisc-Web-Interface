<?php

include "control_web.inc";

## Variablen aus Browser-Zeile
$mp3path=$_GET['mp3path'];
$title=$_GET['title'];
$artist=$_GET['artist'];
$laenge=$_GET['laenge'];

$httphost = $_SERVER["HTTP_HOST"];

### Dynamically send file
if (strcmp($playtp, "df")==0){
  header("Content-Type: audio/xspf");
  header("Content-Location: ".$mp3path);
  #header("Content-Disposition: filename=".$mp3path);
  if(!readfile($mp3path));
  exit; # if file doesn't exist, id3 tag is not correctly displayed in winamp!
}

##########################################################################
### VLC xspf-Playlist remote file (URL / http)

   header("Content-disposition: inline; filename=playlist-$prPlaylist.xspf");
   header("Content-Type: audio/application/xml");
   header("Content-Location: playlist-$prPlaylist.xspf");
  print "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";   
  print "<playlist xmlns=\"http://xspf.org/ns/0/\" xmlns:vlc=\"http://www.videolan.org/vlc/playlist/ns/0/\" version=\"1\">\n";
  print "	<title>Wiedergabeliste</title>\n";
  print "	<trackList>\n"; 
  print "	  <track>\n"; 
  print "			<location>";
  
        $anz_slash = substr_count($mp3dir, "/");
        $teil_mp3 = explode("/", $mp3dir);
        $verz_l = array( "/", $teil_mp3[$anz_slash - 1], "/", $mp3path);
        $mp3verz = implode("", $verz_l);  
  
  print "http://$httphost"."$mp3verz";
  print "</location>"."\n";
	print "			<title>$title</title>\n";
	print "			<creator>$artist</creator>\n";
  print "			<duration>$laenge"."000</duration>\n";
  print "			<extension application=\"http://www.videolan.org/vlc/playlist/0\">\n";
  print "				<vlc:id>0</vlc:id>\n";
  print "			</extension>\n";

  print "	  </track>\n";
  print "	</trackList>\n";
  print "	<extension application=\"http://www.videolan.org/vlc/playlist/0\">\n";
  print "     <vlc:item tid=\"0\"/>\n";
  print "	</extension>\n";

  print "</playlist>\n";
exit;



##########################################################################
### Redirect to mp3 file
if (strcmp($playtp, "rd")==0){
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Refresh" content="0; URL=<?php print $mp3path;?> "> 
<title>mp3 redirector</title>
</head>
<body>
mp3 redirector
<br>
download file <?php print $mp3f; ?>
</body>
</html>
<?php
}
?>