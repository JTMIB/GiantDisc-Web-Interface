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
print "<!doctype html public \"-//W3C//DTD HTML 3.2 Final//EN\">
<html>
<head>
<title>GiantDisc Web Interface: Update Track Details</title>
<link href=\"gdweb.css\" rel=\"stylesheet\" type=\"text/css\">
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=$char_set\">
</head>
<body>";
?>

<?php 

$artist = $_POST["artist"];
$title = $_POST["title"];
$genre1 = $_POST["genre1"];
$genre2 = $_POST["genre2"];
$year = $_POST["year"];
$bpm = $_POST["bpm"];
$lang = $_POST["lang"];
$type = $_POST["type"];
$rating = $_POST["rating"];
$source = $_POST["source"];
$sourceid = $_POST["sourceid"];
$quality = $_POST["quality"];
$tracknb = $_POST["tracknb"];
$length = $_POST["length"];
$mp3file = $_POST["mp3file"];
$voladjust = $_POST["voladjust"];
$lengthfrm = $_POST["lengthfrm"];
$startfrm = $_POST["startfrm"];
$created = $_POST["created"];
$id = $_POST["id"];
$lyrics = $_POST["lyrics"];


      
?>
<h5>GiantDisc Web Interface</h5>
<h2>Update Track Details</h2>

<table border="0">
<tr>
<td class="plformtit">Artist</td>
<td class="plform"> <?php echo $artist ?>
</td></tr>

<tr>
<td class="plformtit">Title</td>
<td class="plform"> <?php echo $title  ?>
</td></tr>

<tr>
<td class="plformtit">Genre</td>
<td class="plform"> <?php echo $genre1 ?>, <?php echo $genre2 ?>
</td></tr>

<tr>
<td class="plformtit">Year</td>
<td class="plform"><?php echo $year ?>
</tr>

<tr>
<td class="plformtit">Beats</td>
<td class="plform"><?php echo $bpm  ?>
</td>
</tr>

<tr>
<td class="plformtit">Language</td>
<td class="plform"> <?php echo $lang ?>
</td>
</tr>

<tr>
<td class="plformtit">Type</td>
<td class="plform"> <?php echo ($type-1) ?>
</td>
</tr>

<tr>
<td class="plformtit">Rating</td>
<td class="plform"><?php echo $rating ?>
</td>
</tr>

<tr>
<td class="plformtit">Source</td>
<td class="plform"><?php echo ($source-1) ?>
</td>
</tr>

<tr>
<td class="plformtit">Sourceid</td>
<td class="plform"><?php echo $sourceid ?>
</td>
</tr>

<tr>
<td class="plformtit">Quality</td>
<td class="plform"> <?php echo ($quality) ?>
</td>
</tr>

<tr>
<td class="plformtit">Tracknum</td>
<td class="plform"><?php echo $tracknb?>
</td>
</tr>

<tr>
<td class="plformtit">Length</td>
<td class="plform"><?php echo $length?>
</td>
</tr>

<tr>
<td class="plformtit">mp3file</td>
<td class="plform"><?php echo $mp3file?>
</td>
</tr>

<tr>
<td class="plformtit">voladjust</td>
<td class="plform"><?php echo $voladjust?>
</td>
</tr>

<tr>
<td class="plformtit">lengthfrm</td>
<td class="plform"><?php echo $lengthfrm?>
</td>
</tr>

<tr>
<td class="plformtit">startfrm</td>
<td class="plform"><?php echo $startfrm?>
</td>
</tr>

<tr>
<td class="plformtit">created</td>
<td class="plform"><?php echo $created?>
</td>
</tr>

<tr>
<td class="plformtit">ID</td>
<td class="plform"><?php echo $id?>
</td>
</tr>

<tr>
<td class="plformtit" style="vertical-align: top;">Lyrics</td>
<td colspan="3" class="plform"><pre><?php echo $lyrics?></pre>
</td></tr>

</table>


<?php


###  Sonderzeichen tauschen
$artist = str_replace("'","&#39;",$artist);
$title = str_replace("'","&#39;",$title);
$lyrics = str_replace("'","&#39;",$lyrics);

if(isset($id)){
  # open data source
  $link =  mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
  if ( ! $link )
      die( "Keine Verbindung zu MySQL" );	

  $sqlcmd= "UPDATE tracks SET "
          ."artist='$artist', title='$title', genre1='$genre1', genre2='$genre2', "
		  ."year=$year, lang='$lang', type=".($type-1).", rating=$rating, "
          ."length=$length, source=".($source-1).", sourceid='$sourceid', tracknb=$tracknb, "
		  ."mp3file='$mp3file', quality=$quality, voladjust=$voladjust, "
		  ."lengthfrm=$lengthfrm, startfrm=$startfrm, bpm=$bpm, lyrics='$lyrics', "
		  ."created='$created', modified=CURDATE() "
		  ."WHERE id=$id";



  print ("SQL-COMMAND: ". $sqlcmd);

  if(! mysqli_query ( $link, $sqlcmd)){
    echo "<p><b>Error: </b>".mysql_errno()." - ".mysql_error()."<br>";
  }
  else{
    print "<p><b>Record sucessfully updated</b>";

### Felder die NULL sein können wieder auf NULL setzen
if (strlen($lyrics) < 1)
    $sqlcmd = mysqli_query( $link, "UPDATE tracks SET lyrics = NULL WHERE id=".$id);

    print "<table align='right' border='0' cellspacing='0' cellpadding='1'><tr><td class=\"menu".$sel."active\"><a class=\"menu\" href=\"javascript:history.go(-2)\">$output081</a></td></tr></table></p>";

  }

}

  mysqli_close( $link );
?>



<?php 
?>
</body>
</html>