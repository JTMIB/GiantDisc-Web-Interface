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
?>
<!doctype html public "-//W3C//DTD HTML 3.2 Final//EN">
<html>
<head>
<title>GiantDisc Web Interface</title>
<link href="gdweb.css" rel="stylesheet" type="text/css">
<?php
print "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=$char_set\">";
?>
</head>
<body>

<h5>GiantDisc Web Interface</h5>
<h2>Edit Track Details</h2>

<?php

  $link =  mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
  if ( ! $link )
      die( "Keine Verbindung zu MySQL" );

## Variablen aus Browser-Zeile
$id=$_GET['id'];
      
#phpinfo();

function show_selform($artist, $title, $genre1, $genre2,
					$year, $lang, $type, $rating, $length,
					$source, $sourceid, $tracknb, $mp3file, $bitrate,
					$quality, $voladjust, $lengthfrm, $startfrm,
					$bpm, $lyrics, $created, $modified, $backup,
					$id
					)
{
?>
<form action="gdtrupd.php" method="post">
<table border="0">
<tr>
<td class="plformtit">Artist</td>
<td colspan="3" class="plform">
<input type="text" name="artist" value="<?php echo $artist ?>" size="40" maxlength="200">
</td></tr>

<tr>
<td class="plformtit">Title</td>
<td colspan="3" class="plform">
<input type="text" name="title"  value="<?php echo $title  ?>" size="40" maxlength="200">
</td></tr>

<tr>
<td class="plformtit">Genre</td>
<td colspan="3" class="plform">
<small><select name="genre1"><?php print_genre_options($genre1); ?></select></small>
<small><select name="genre2"><?php print_genre_options($genre2); ?></select></small>
</td></tr>

<tr>
<td class="plformtit">Year</td>
<td class="plform">
<input type="text" name="year" value="<?php echo $year  ?>" size="4" maxlength="4">
</td>
<td class="plformtit">Beats/min</td>
<td class="plform">
<input type="text" name="bpm" value="<?php echo $bpm  ?>" size="4" maxlength="4">
</td>
</tr>

<tr>
<td class="plformtit">Language</td>
<td class="plform">
<small><select name="lang"><?php print_lang_options($lang); ?></select></small>
</td>
<td class="plformtit">Type</td>
<td class="plform">
<small><select name="type"><?php print_type_options($type+1); ?></select></small>
</td>
</tr>

<tr>
<td class="plformtit">Rating</td>
<td class="plform">
<small><select name="rating"><?php print_rating_options($rating); ?></select></small>
</td>
<td class="plformtit">Source</td>
<td class="plform">
<small><select name="source"><?php print_source_options($source+1); ?></select></small>
</td>
</tr>

<tr>
<td class="plformtit">Created</td>
<td class="plform"> <?php echo $created ?>
</td>
<td class="plformtit">Quality</td>
<td class="plform">
<small><select name="quality"><?php print_condition_options($quality); ?></select></small>
</td>
</tr>

<tr>
<td class="plformtit">mp3file</td>
<td class="plform"> <?php echo $mp3file ?>
</td>
<td class="plformtit">voladjust</td>
<td class="plform"> <?php echo $voladjust ?>
</td>
</tr>

<tr>
<td class="plformtit">format</td>
<td class="plform"> <?php echo $bitrate ?>
</td>
<td class="plformtit">&nbsp;</td>
<td class="plform"></td>
</tr>

<tr>
<td class="plformtit">Modified</td>
<td class="plform"> <?php echo $modified ?>
</td>
<td class="plformtit">Length</td>
<td class="plform"> <?php echo $length ?>
</td>
</tr>

<tr>
<td class="plformtit">lengthfrm</td>
<td class="plform"> <?php echo $lengthfrm ?>
</td>
<td class="plformtit">startfrm</td>
<td class="plform"> <?php echo $startfrm ?>
</td>
</tr>

<tr>
<td class="plformtit">Album</td>
<td class="plform"> <?php echo $sourceid ?>
</td>
<td class="plformtit">Tracknum</td>
<td class="plform">
<input type="text" name="tracknb" value="<?php echo $tracknb?>" size="2" maxlength="2">
</td>
</tr>

<tr><td colspan="4" class="plform" style="text-align: center;">
<br><input type="submit" value="Update" style="text-align: center;"><br>
</td></tr>

<tr>
<td class="plformtit" style="vertical-align: top;">Lyrics</td>
<td colspan="3" class="plform">
<small><textarea cols="35" rows="30" name="lyrics"><?php echo $lyrics?></textarea></small>
</td></tr>

</table>

<!-- hidden fields -->
<input type="hidden" name="id"        value="<?php echo $id ?>">
<input type="hidden" name="sourceid"  value="<?php echo $sourceid ?>">
<input type="hidden" name="length"    value="<?php echo $length ?>">
<input type="hidden" name="mp3file"   value="<?php echo $mp3file ?>">
<input type="hidden" name="voladjust" value="<?php echo $voladjust ?>">
<input type="hidden" name="lengthfrm" value="<?php echo $lengthfrm ?>">
<input type="hidden" name="startfrm"  value="<?php echo $startfrm ?>">
<input type="hidden" name="created"   value="<?php echo $created ?>">

</form>
<?php
}
?>



<?php


if(isset($id)){
  # open data source
#  $benutzer = "music";
#  $passwort = "music";
#  $db = "GiantDisc";
#  $dbh = mysql_connect( "localhost", $benutzer, $passwort );

#  print("<table border=\"0\" cellspacing=\"0\">\n");
  $recset = mysqli_query ( $link, "SELECT * FROM tracks WHERE id=".$id);

  if($row = mysqli_fetch_array($recset)) {
    show_selform($row["artist"], $row["title"], $row["genre1"], $row["genre2"],
				$row["year"], $row["lang"], $row["type"], $row["rating"], $row["length"],
				$row["source"], $row["sourceid"], $row["tracknb"], $row["mp3file"], $row["bitrate"],
				$row["quality"], $row["voladjust"], $row["lengthfrm"], $row["startfrm"],
				$row["bpm"], $row["lyrics"], $row["created"], $row["modified"], $row["backup"],
				$row["id"]);
  }
  mysqli_free_result($recset);
  mysqli_close( $link );
}
?>



<?php
?>
</body>
</html>
