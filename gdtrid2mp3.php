<?php
### Set MIME-Type

# Parameters:
# $mp3f:    mp3 file like "tr0xab10f90c-4.mp3"
# $playtp:  play method. 
#			  "rd" Redirect URL
#			  "df" Dynamically generate mp3 file
#			  "pr" Winamp m3u Playlist remote file (URL)
# $locpath: local path to mp3 directories like "E:\"

include "control_web.inc";

#$link =  mysql_connect( MYSQL_HOST, MYSQL_USER );
#  if ( ! $link )
#      die( "Keine Verbindung zu MySQL" );

## Variablen aus Browser-Zeile
$playtp=$_GET['playtp'];
$prAlbum=$_GET['prAlbum'];
$albid=$_GET['albid'];
$id=$_GET['id'];
if (isset($_GET['prPlaylist'])) {
    $prPlaylist = $_GET['prPlaylist'];
}
				
if(isset($mp3f)){
  $mp3path = full_mp3_fname($mp3f);
}

$httphost = $_SERVER["HTTP_HOST"];

### Dynamically send file
if (strcmp($playtp, "df")==0){
  header("Content-Type: audio/mp3");
  header("Content-Location: ".$mp3path);
  #header("Content-Disposition: filename=".$mp3path);
  if(!readfile($mp3path));
  exit; # if file doesn't exist, id3 tag is not correctly displayed in winamp!
}


##########################################################################
### Winamp Playlist remote file (URL / http)

if (strcmp($playtp, "pr")==0 && !isset($prAlbum)){

 if ($prPlaylist >= 0){
 $link =  mysqli_connect( MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB );
  if ( ! $link )
      die( "Keine Verbindung zu MySQL" );
 $abfrage = "SELECT tracks.length, tracks.artist, tracks.title, tracks.mp3file
  FROM tracks, playlistitem
  WHERE playlistitem.playlist = $prPlaylist AND playlistitem.trackid = tracks.id";
 $recset = mysqli_query ( $link, $abfrage);

    header("Content-disposition: inline; filename=playlist.m3u");
    header("Content-Type: audio/x-mpegurl");
    header("Content-Location: playlist.m3u");
    print "#EXTM3U\n";


$a = 0;
while ( $datensatz = mysqli_fetch_row( $recset ) )
    {
    foreach ( $datensatz as $feld )
		  {
		  if ( $a == 0 )
			{
			print "#EXTINF:"."$feld".",";
			$a++;
			}
		  else
			{
			if ( $a == 1)
				{print "$feld"." - ";}
			if ( $a == 2)
				{print "$feld"."\n";}
			if ( $a == 3)
				{
				$l = strlen($PATH_ABSOLUTE_mp3) - 3;
				$mp3path = substr($PATH_ABSOLUTE_mp3,$l,3);
				print "http://$httphost/music"."$mp3path"."/";
				print "$feld"."\n";
				}
			$a++;
			}
		  if ( $a == 4)
		  		{$a = 0;}
		  }
	}
 mysqli_free_result($recset);
 mysqli_close( $link );  
 }
 else
 {
 $link =  mysqli_connect( MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB );
  if ( ! $link )
      die( "Keine Verbindung zu MySQL" );
  $abfrage = "SELECT * FROM tracks WHERE id=".$id." ";    	
  $recset = mysqli_query ( $link , $abfrage);
  if($row = mysqli_fetch_array($recset)) {
    header("Content-disposition: inline; filename=foo.m3u");
    header("Content-Type: audio/x-mpegurl");
    header("Content-Location: foo.m3u");
    print "#EXTM3U\n";
    print "#EXTINF:".$row[length].",".$row[artist]." - ".$row[title]."\n";
    print "http://$httphost/~music/webint/".$mp3path."\n";
    mysqli_free_result($recset);
    mysqli_close( $link );  
 }
  }

exit;

?>

<?php
exit;
}


##########################################################################
### Winamp Playlist for album (by Merlot)
if(isset($prAlbum))
{
$link =  mysqli_connect( MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB );
  if ( ! $link )
      die( "Keine Verbindung zu MySQL" );
  $sql="SELECT * FROM tracks WHERE sourceid='".$albid."' ORDER BY tracknb ";
  $recset = mysqli_query ( $link, $sql);

   header("Content-disposition: inline; filename=playlist.m3u");
   header("Content-Type: audio/x-mpegurl");
   header("Content-Location: playlist.m3u");

  print "#EXTM3U\n";
  while($row = mysqli_fetch_array($recset)) {
   ###  Sonderzeichen zurück tauschen
   if (isset($row['artist'])) {
    $row['artist'] = str_replace("&#39;", "'", $row['artist']);
   }
   if (isset($row['title'])) {
    $row['title'] = str_replace("&#39;", "'", $row['title']);
   }    
  
   print "#EXTINF:";
   print "$row[length]";
   print ",";
   print "$row[artist]";
   print " - ";
   print "$row[title]";
   print "\n";
   

    $l = strlen($PATH_ABSOLUTE_mp3) - 3;
    $mp3path = substr($PATH_ABSOLUTE_mp3,$l,3);
    print "http://$httphost/music"."$mp3path"."/";
    print "$row[mp3file]";
    print "\n";

}
   mysqli_free_result($recset);
   mysqli_close( $link );   
#   exit;
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

