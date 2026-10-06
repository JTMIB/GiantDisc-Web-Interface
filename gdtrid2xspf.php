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

## Variablen aus Browser-Zeile
$playtp=$_GET['playtp'];
$prAlbum=$_GET['prAlbum'];
$albid=$_GET['albid'];
$id=$_GET['id'];
if (isset($_GET['prPlaylist'])) {
    $prPlaylist = $_GET['prPlaylist'];
}

#$prPlaylist=$_GET['prPlaylist']; 
#if(isset($mp3f)){
#  $mp3path = full_mp3_fname($mp3f);
#}


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
if (strcmp($playtp, "pr")==0 && !isset($prAlbum)){

 if ($prPlaylist >= 0){
 $link =  mysqli_connect( MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB );
  if ( ! $link )
      die( "Keine Verbindung zu MySQL" );
# $recset = mysql_db_query ("GiantDisc",
# "SELECT tracks.length, tracks.artist, tracks.title, tracks.mp3file
#  FROM tracks, playlistitem
#  WHERE playlistitem.playlist = $prPlaylist AND playlistitem.trackid = tracks.id");

 $abfrage = "SELECT tracks.length, tracks.artist, tracks.title, tracks.mp3file
  FROM tracks, playlistitem
  WHERE playlistitem.playlist = $prPlaylist AND playlistitem.trackid = tracks.id";
 $recset = mysqli_query ( $link, $abfrage);
  
   header("Content-disposition: inline; filename=playlist-$prPlaylist.xspf");
   header("Content-Type: audio/application/xml");
   header("Content-Location: playlist-$prPlaylist.xspf");
   $id_titel = 0;
  print "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";   
  print "<playlist xmlns=\"http://xspf.org/ns/0/\" xmlns:vlc=\"http://www.videolan.org/vlc/playlist/ns/0/\" version=\"1\">\n";
  print "	<title>Wiedergabeliste</title>\n";
  print "	<trackList>\n";   

  $a = 0;
while ( $datensatz = mysqli_fetch_row( $recset ) )
    {
    foreach ( $datensatz as $feld )
		  {
		  if ( $a == 0 )
			{
			$laenge = $feld;
			$a++;
			}
		  else
			{
			if ( $a == 1)
				{
				$artist = $feld;
				$artist = str_replace("&#39;", "'", $artist);
				$artist = str_replace("&", " ", $artist);
        }
			if ( $a == 2)
				{
				$title = $feld;
				$title = str_replace("&#39;", "'", $title);
				$title = str_replace("&", " ", $title);
        }
			if ( $a == 3)
				{
				$l = strlen($PATH_ABSOLUTE_mp3) - 3;
				$mp3path = substr($PATH_ABSOLUTE_mp3,$l,3);
				print "		<track>\n";
				print "			<location>";
				print "http://$httphost/music"."$mp3path"."/";
				print "$feld";
				print "</location>"."\n";
				print "			<title>$title</title>\n";
				print "			<creator>$artist</creator>\n";
        print "			<duration>$laenge"."000</duration>\n";
        print "			<extension application=\"http://www.videolan.org/vlc/playlist/0\">\n";
        print "				<vlc:id>$id_titel</vlc:id>\n";
        $id_titel++;				
				print "			</extension>\n";
				print "		</track>\n";
				}
			$a++;
			}
		  if ( $a == 4)
		  		{$a = 0;}
		  }
	}


  print "	</trackList>\n";   
  print "	<extension application=\"http://www.videolan.org/vlc/playlist/0\">\n";
  for($tid=0; $tid < $id_titel; $tid++) {
   echo "		<vlc:item tid=\"$tid\"/>\n";
  }  
  print "	</extension>\n";
  print "</playlist>\n";
    
# mysqli_free_result($recset);
 mysqli_close( $link );  
 }
exit;
}

##########################################################################
### VLC Playlist for album - XSPF
#if(isset($prAlbum))
if($prAlbum = 1)
{
#Album und Titel für Dateiname
$link =  mysqli_connect( MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB );
  if ( ! $link )
      die( "Keine Verbindung zu MySQL" );
  $abfrage="SELECT artist, title FROM album WHERE cddbid ='".$albid."'";
  $ergebnis = mysqli_query ( $link, $abfrage);
  $anz_reihen = mysqli_affected_rows( $link );
  while($reihe = mysqli_fetch_assoc($ergebnis)) {
    $artist = str_replace("&#39;","-",$reihe["artist"]);
    $artist = str_replace("&","-",$reihe["artist"]);
    $title = str_replace("&#39;","-",$reihe["title"]);
    $title = str_replace("&","-",$title);
    $title = str_replace("#39","-",$title);
    $artist = str_replace(" ","-",$artist);
    $title = str_replace(" ","-",$title);
    $title = str_replace("'","-",$title);
    $title = str_replace("´","-",$title);
    }
#mysqli_free_result($ergebnis);
mysqli_close( $link );

###      
$link =  mysqli_connect( MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB );
  if ( ! $link )
      die( "Keine Verbindung zu MySQL" );
  $sql="SELECT * FROM tracks WHERE sourceid='".$albid."' ORDER BY tracknb ";
  $recset = mysqli_query ( $link, $sql);

   header("Content-disposition: inline; filename=playlist-$artist-$title.xspf");
   header("Content-Type: audio/application/xml");
   header("Content-Location: playlist-$artist-$title.xspf");
   $id_titel = 0;
  print "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";   
  print "<playlist xmlns=\"http://xspf.org/ns/0/\" xmlns:vlc=\"http://www.videolan.org/vlc/playlist/ns/0/\" version=\"1\">\n";
  print "	<title>Wiedergabeliste</title>\n";
  print "	<trackList>\n";


  while($row = mysqli_fetch_array($recset)) {  
   ###  Sonderzeichen zurück tauschen
   if (isset($row['artist'])) {
    $row['artist'] = str_replace("&#39;", "'", $row['artist']);
   }
   if (isset($row['title'])) {
    $row['title'] = str_replace("&#39;", "'", $row['title']);
   }   

   if (isset($row['artist'])) {
    $row['artist'] = str_replace("&", " ", $row['artist']);
   }
   if (isset($row['title'])) {
    $row['title'] = str_replace("&", " ", $row['title']);
   }   

   $mp3path = substr($PATH_ABSOLUTE_mp3,-2);
   print "		<track>\n";
   print "			<location>http://$httphost";
        print "/music/";
        print "$mp3path";
        print "/$row[mp3file]</location>\n";
   print "			<title>$row[title]</title>\n";
   print "			<creator>$row[artist]</creator>\n";
   print "			<duration>$row[length]000</duration>\n";
   print "			<extension application=\"http://www.videolan.org/vlc/playlist/0\">\n";
   print "				<vlc:id>$id_titel</vlc:id>\n";
   $id_titel++;
   print "			</extension>\n";
   print "		</track>\n";
}
  print "	</trackList>\n";
  print "	<extension application=\"http://www.videolan.org/vlc/playlist/0\">\n";
  for($tid=0; $tid < $id_titel; $tid++) {
   echo "		<vlc:item tid=\"$tid\"/>\n";
  }
  print "	</extension>\n";
  print "</playlist>\n";


mysqli_free_result($recset);
mysqli_close( $link );
}




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