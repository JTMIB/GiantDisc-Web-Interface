<?php

include "control_web.inc";
include "gdwebdef.php";

## Variablen aus Browser-Zeile
$l0=$_GET['l0']; 
$albid=$_GET['albid'];
$step=$_GET['step'];

$playtp = set_get_cookie($setplaytp, "playtp", "dl", $HTTP_COOKIE_VARS);

#######################################################################################
#print_header ("browse", "", "$cgi_dir", "$char_set");
#print_header ("play", "play", "play", "play");
###############################################################
# nach player suchen
exec("ps -ef | grep $b_player > $tempdir/play.txt");

if ($step != "stop")
  {

$a = 0;

$file = "$tempdir/play.txt";
$file_handle = fopen($file, 'r');
 
while (!feof($file_handle)) {
 
  $line = fgets($file_handle);
#  print "$line<br>";
  $pos = strpos($line, "/usr/bin/$b_player $mp3dir");
#  print "$pos<br>";
  if ($pos === FALSE)
    {
    #print "nicht gefunden!<br>";
    }
  else
    {
    $ausgabe = substr($line, $pos);
#    print "$ausgabe<br>";
    $a++;
    $titel_nr = explode("/", $ausgabe);
#    print "$titel_nr[9]<br>";
    $titel_nummer = $titel_nr[9]; 
    }
}
#print "$a<br>";
fclose($file_handle);
  }
?>
<!DOCTYPE html
	PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
  <html>
  <head>
  <title>GiantDisc Web Interface: play</title><meta http-equiv="Content-Type" content="text/html; charset=play"><meta name="author" content="Juergen Thoens">
  <meta name="pragma" content="no-cache">
  <meta http-equiv="cache-control" content="no-cache">
  <meta http-equiv="expires" content="100">
  <meta name="revisit-after" content="1">
  <link href="gdweb.css" rel="stylesheet" type="text/css">
  <link rel="SHORTCUT ICON" href="img/gd16.ico">
  <link rel="stylesheet" type="text/css" href="style.css">
  <link href="lightbox.css" rel="stylesheet">
<?php


  
  if ($step == "refresh")
    {
    if ($a > 0)
      {
      print "  <meta http-equiv=\"refresh\" content=\"8; URL=play.php?l0=$l0&albid=$albid&step=refresh\">\n";
      }
    else
      {
      print "  <meta http-equiv=\"refresh\" content=\"2; URL=play.php?l0=$l0&albid=$albid&step=stop\">\n";
      }
    }
    
  if ($step == "next")
    {
    print "  <meta http-equiv=\"refresh\" content=\"1; URL=play.php?l0=$l0&albid=$albid&step=refresh\">\n";
    }
  if ($step == "start")
    {
    print "  <meta http-equiv=\"refresh\" content=\"2; URL=play.php?l0=$l0&albid=$albid&step=refresh\">\n";
    }


?>  
<script type="text/javascript">
// code generated with http://www.free-solutions.de/js/browser_tool_neuwin.htm
function popUp1(wintype)
{
  var nwl = (screen.width-620)/2;
  var nwh = (screen.height-450)/2;
  popUp=window.open(wintype, 'NewWindows', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=620,height=450,left=22,top=22'); 

  popUp.window.focus(); 
}
</script>

<script src="jquery-2.1.3.min.js"></script>

</head>
<body>

  <table>
  <tr><td><div class='maintitle'>GiantDisc&nbsp;Web&nbsp;Interface</div><div class='play'></td><td width="100%" bgcolor="#ffffff" height="44" align="right">
	<img src="img/flac.gif" height="75" border="0" align="right">
	<img src="img/oggenc.gif" height="75" border="0" align="right">
	<img src="img/mp3.jpg" width="94" height="75" border="0" align="right"></td></tr>
  </table>

  <table border='0' cellspacing='0' cellpadding='1'><tr><td class="menuinactive"><a class="menu" href="index.php">Search</a></td>
  <td>&nbsp;</td>
  <td class="menuinactive"><a class="menu" href="browse.php">Browse</a></td>
  <td>&nbsp;</td>
  <td class="menuinactive"><a class="menu" href="record.php">Record</a></td>
  <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
  <td class="menuinactive"><a class="light" href="settings.php">Options</a></td></tr>
  </table>

<p>


<?php
#######################################################################################


#######################################################################################
  $link =  mysql_connect( MYSQL_HOST, MYSQL_USER, MYSQL_PASS );
  if ( ! $link )
      die( "Keine Verbindung zu MySQL" );

#######################################################################################
### Constants
$trunclen = 30; # characters

function write_genres($prefix, $selgenre, $shgenre)
# displays a part of the genre tree.
# '$selgenre' specifies, which genre is selected (will be highlighted).
# '$shgenre' specifies the deepest genre node in the hierarchy that should be opened/printed
{
  $whereclause = " id LIKE '_' "; # always show the first level
  $i = strlen($shgenre);
  while ($i >= 1){
    $whereclause .= " OR id LIKE '".substr($shgenre, 0, $i)."_' ";
    $i--;
  }

  $qrystr = "SELECT * FROM genre WHERE $whereclause ORDER BY id";
  $recset = mysql_db_query ("GiantDisc", $qrystr);
  print "\n<div class='genrebox'>";
  while($row = mysql_fetch_array($recset)) {
    print str_repeat("&nbsp;", (strlen($row["id"])-1)*4);  # indents
    $pos = strpos($shgenre, $row["id"]);  # is current id a prefix of $shgenre?
    if ($pos!==false and $pos==0){# are we in the open branch?
      $imgdir = "tri-d.png";
      $opengen = substr ($row["id"], 0, strlen($row["id"])-1); # chop off last char
    }else{
      $imgdir = "tri-r.png";
      $opengen = $row["id"];
    }
    # print triangle
    print "<a href='$prefix&shgn=$opengen'><img src='img/$imgdir'></a>&nbsp;"; # ignore $l1: show no results when tree is changed
    # print genre text
    print "<a ".($row["id"]==$selgenre ? "class='selected'":"")
               ." href='".$prefix.$row["id"]."&shgn=$shgenre'>".$row["genre"]."</a><br>";
  }
  print "</div>";
}



function write_artistlink($prefix, $artist)
{
  global $trunclen;
  if (strlen($artist)>$trunclen){
    $art = substr($artist, 0, $trunclen);
    $appendix="..";
  }
  else{
    $art = $artist;
    $appendix="";
  }
#  print "<a class=\"brslst\" href=\"".$prefix.urlencode($art)."\">"
#        .htmlentities($art).$appendix."</a>&nbsp; ";
  print "<a class=\"brslst\" href=\"".$prefix.urlencode($art)."\">"
        .$artist."</a>&nbsp; ";

}
  mysql_free_result($recset);
  mysql_close( $link );

#######################################################################################
if ($step == "next")
    {
    exec("sudo -u root /usr/bin/killall mplayer > /dev/null");
    }

#######################################################################################
if ($step == "start" or $step == "stop")
{
# alten Job stoppen
$anz_kill = 0;
$dateiname = "$tempdir/b_player.sh";
$file_handle = fopen($dateiname, 'r');
while (!feof($file_handle)) {
 
  $line = fgets($file_handle);
#  print "$line<br>";
  $anz_kill++;
}
fclose($file_handle);
#print "$anz_kill<br>";

touch ("$tempdir/kill.sh");
$dateiname = "$tempdir/kill.sh";
$fp = fopen( $dateiname, 'w');
fwrite( $fp, "#\n");
$i = 0;
while($i < $anz_kill) {
#   print "$i<br>";
   $i++;
   fwrite( $fp, "sudo -u root killall $b_player\n");
   fwrite( $fp, "sudo -u root killall $b_player\n");
}
fclose( $fp );
exec("$tempdir/kill.sh");
}
  
###############################################################
if ($step == "start")
{
# Datei anlegen
touch ("$tempdir/b_player.sh");
$dateiname = "$tempdir/b_player.sh";
$fp = fopen( $dateiname, 'w');
fwrite( $fp, "#\n");

$prAlbum=1;

if(isset($prAlbum))
{
$link =  mysql_connect( MYSQL_HOST, MYSQL_USER, MYSQL_PASS );
  if ( ! $link )
      die( "Keine Verbindung zu MySQL" );
      
  $sql="SELECT * FROM tracks WHERE sourceid='".$albid."' ORDER BY tracknb ";
  $recset = mysql_db_query ("GiantDisc", $sql);
  $anz_row = mysql_num_rows( $recset );
#  print "$anz_row<br>";

  while($row = mysql_fetch_array($recset)) {

   $mp3path = full_mp3_fname($row[mp3file]);
   fwrite( $fp, "sudo -u root /usr/bin/$b_player $htdocs");
   fwrite( $fp, "$mp3path\n"); 
#   print "/usr/bin/mpg123 $htdocs";
#   print $mp3path."\n";
#   print "\n";
#   print "<br>";
  }
  fclose( $fp );
  
  exec("/srv/www/htdocs/music/00/b_player.sh > /dev/null &");
  
}
  mysql_free_result($recset);
  mysql_close( $link );

}
###############################################################
# nach player suchen
exec("ps -ef | grep $b_player > $tempdir/play.txt");


$file = "$tempdir/play.txt";
$file_handle = fopen($file, 'r');
 
while (!feof($file_handle)) {
 
  $line = fgets($file_handle);
#  print "$line<br>";
  $pos = strpos($line, "/usr/bin/$b_player $mp3dir");
#  print "$pos<br>";
  if ($pos === FALSE)
    {
    #print "nicht gefunden!<br>";
    }
  else
    {
    $ausgabe = substr($line, $pos);
#    print "$ausgabe<br>";
    $titel_nr = explode("/", $ausgabe);
#    print "$titel_nr[9]<br>";
    $titel_nummer = $titel_nr[9]; 
    }
}

fclose($file_handle);

###############################################################
# Ausgabe von Album mit Titelliste
$link =  mysql_connect( MYSQL_HOST, MYSQL_USER, MYSQL_PASS );
  if ( ! $link )
      die( "Keine Verbindung zu MySQL" );

print "<table border=1>";
print "<tr><td>";
print "<hr>";

$recset = mysql_db_query ("GiantDisc", "SELECT artist, title, composer, cddbid, coverimg, covertxt, modified, genre FROM album WHERE cddbid = '$albid' ");
#$anz_row = mysql_num_rows( $recset );
#print "$anz_row<br>";

while($row = mysql_fetch_object($recset))
   {
   $artist = $row->artist;
   $title = $row->title;
   $composer = $row->composer;
   $cddbid = $row->cddbid;
   $coverimg = $row->coverimg;
   $covertxt = $row->covertxt;
   $modified = $row->modified;
   $genre = $row->genre;
   }

    $lang_cover = strlen($coverimg);
    if ($lang_cover == "0")
      {
      $coverimg_t = "../img/no-img-sm.gif";
      }
    else
      {
      $c_img = explode(".", $coverimg);
      $coverimg_t = "$c_img[0]-t.$c_img[1]";
      }
      
$genre_recset = mysql_db_query ("GiantDisc", "SELECT genre FROM genre WHERE id = '$genre' ");
#$anz_row = mysql_num_rows( $genre_recset );
#print "$anz_row<br>";
while($row = mysql_fetch_object($genre_recset))
    {
    $genre = $row->genre;
    }
 
#print "<p> <br> </p>";

print "<table border=0>";
print "<tr><td>";
print "<table border=0>";
print "<tr><td align=\"right\">$output032:</td><td> </td><td>$artist</td></tr>";
print "<tr><td align=\"right\">$output095:</td><td> </td><td>$title</td></tr>";
print "<tr><td align=\"right\">$output096:</td><td> </td><td>$composer</td></tr>";
print "<tr><td align=\"right\">$output097:</td><td> </td><td>$cddbid</td></tr>";
print "<tr><td align=\"right\">$output098:</td><td> </td><td>$covertxt</td></tr>";
print "<tr><td align=\"right\">$output099:</td><td> </td><td>$modified</td></tr>";
print "<tr><td align=\"right\">$output100:</td><td> </td><td>$genre</td></tr>";
print "</table>";
print "</td><td> </td>";
print "<td valign=\"top\">";
#print "<img src=\"02/$coverimg_t\" width=\"200\" border=\"0\" align=\"right\"></td>";
print "<a href=\"02/$coverimg\" data-lightbox=\"bild-1\" ><img src=\"02/$coverimg_t\" width=\"200\" border=\"0\" align=\"right\"></a></td>";

if ($step == "stop")
  {
  #start playing
  print "<td>&nbsp; &nbsp; &nbsp;</td>";
  print "<td valign=\"center\">";
  print "<a href=\"play.php?l0=thisalb";
  print "&albid=$albid";
  print "&step=start";
  print "\">";
  print "<img border=\"0\" src=\"img/button_blue_play.png\" width=\"40\" title=\"$output131\"></a>";
  print "</td>"; 
  }
else
  {
  #stop playing
  print "<td>&nbsp; &nbsp; &nbsp;</td>";
  print "<td valign=\"center\">";
  print "<a href=\"play.php?l0=thisalb";
  print "&albid=$albid";
  print "&step=stop";
  print "\">";
  print "<img border=\"0\" src=\"img/button_blue_stop.png\" width=\"40\" title=\"$output130\"></a>";
  print "</td>";  
  }

if ($step == "stop")
  {
  #nichts anzeigen
  print "<td>&nbsp; &nbsp; &nbsp;</td>";
  print "<td valign=\"center\">";
  print "<img border=\"0\" src=\"img/0.gif\" width=\"40\">";
  print "</td>";    
  }
else
  {
  #next track
  print "<td>&nbsp; &nbsp; &nbsp;</td>";
  print "<td valign=\"center\">";
  print "<a href=\"play.php?l0=thisalb";
  print "&albid=$albid";
  print "&step=next";
  print "\">";
  print "<img border=\"0\" src=\"img/forward.png\" width=\"40\" title=\"$output129\"></a>";
  print "</td>";  
  }

  #Lautstärke
#  print "<td>&nbsp; &nbsp; &nbsp;</td>";
#  print "<td valign=\"center\">";
#  print "<input type=\"range\" min=\"0\" max=\"100\" step=\"5.0\">";
#  print "</td>";

print "</tr>";   
print "</table>";
  mysql_free_result($recset);
  mysql_close( $link );

#################################################################################################


?>

<p>

<?php
$link =  mysql_connect( MYSQL_HOST, MYSQL_USER, MYSQL_PASS );
  if ( ! $link )
      die( "Keine Verbindung zu MySQL" );

print "</td></tr>";
print "<tr><td>";

### Show first level:

$qrystr = ""; # clear query string


if (isset($l0)){


    ### This Album ###
  if (strcmp($l0, "thisalb")==0){
#    print "<h4>This Album</h4>";
	$tempquery = "SELECT * FROM album WHERE cddbid LIKE '%$albid'";
    $recset = mysql_db_query ("GiantDisc", $tempquery);
	$a = 0;
	$anz_reihen = mysql_affected_rows();
#	print "<hr>";
      print "<table borders=\"0\">";
      while($row = mysql_fetch_array($recset)) {
		$albumrecord = $row;
		$a++;
		print "<tr>";
		print "<td class=\"trklst\">";

			print "</tr>";
      }
      print "</table>";
  # hier die Titel
	  if (isset($albid)){ # albumid / level 3 specified?
        $qrystr = "SELECT * FROM tracks WHERE sourceid = '$albid' ORDER BY tracknb ";
		}


  }
}
##########################################################

#  print "<!-- --><a NAME='liste'></a><!-- -->";


#####################################################################

if(strlen($qrystr)>0){ # do query and show tracks
  if (strlen($rating)==0){$rating=0;}
  ### query tracks
  $recset = mysql_db_query ("GiantDisc", $qrystr);

  ### display the tracks
if(strlen($albid)<=0){
  if (mysql_affected_rows()>0){print "<hr noshade>";}else{print "<p>&nbsp;</p>";}
}
  print("<p>&nbsp; &nbsp; <small><b>".mysql_affected_rows()." $output128</b></small></p>");
  print("<table border=\"0\" cellspacing=\"0\">\n");
  $evenln = 1;

  while($row = mysql_fetch_array($recset)) {

    if ($evenln){
      print "<tr class=\"lneven\"><td>&nbsp; &nbsp;</td>";
	}
	else{
      print "<tr class=\"lnodd\"><td>&nbsp; &nbsp;</td>";
	}
    show_onlytrackrow($row,
	          $showartist, $showtitle, $showgenre, $showlang, $showyear, $showrating,
			  $showbpm, $showsource, $showcreated, $showmodified, $showlength,
			  $showedit, $showdownload, $playtp, $showtoplaylist, $output079, $output080, $titel_nummer);
    $evenln = 1-$evenln; #toggle $evenln

	print "</tr>\n";
  }
  print("</table>\n");
  
  mysql_free_result($recset);
  mysql_close( $link );
}
print "</td>";
print "</tr>";   
print "</table>";
?>

<hr noshade>

<p>
 <br>
 <br>
 <br>
 <br>
</p>

<script src="lightbox.js"></script>
<script>
    lightbox.option({
      'resizeDuration': 200,
      'wrapAround': true
    })
 </script>
</body>
</html>