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
 
setcookie("TestCookie","Test Value");
?>

<?php
include "control_web.inc";


print_header ("settings", "", "$cgi_dir", "$char_set", $output135, $output136, $output137, $output138);

  $link =  mysqli_connect( MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB );
  if ( ! $link )
      die( "Keine Verbindung zu MySQL" );

## Variablen aus Browser-Zeile
$showartist=$_GET['showartist'];
$artist=$_GET['artist'];
$showtitle=$_GET['showtitle'];
$title=$_GET['title'];
$showgenre=$_GET['showgenre'];
$genre=$_GET['genre'];
$showrating=$_GET['showrating'];
$rating=$_GET['rating'];
$playtp=$_GET['playtp'];
#Um Album zu bearbeiten
$CDid=$_GET['CDid'];

function show_selform($showartist, $artist, $showtitle, $title, $showgenre, $genre,
			 $showlang, $lang, $showyear, $yearfrom, $yearto, $showrating, $rating,
			 $showbpm, $bpmfrom, $bpmto, $showsource, $source,
			 $showcreated, $created, $showmodified, $modified){
?>
<table border="0">
<tr>
<td class="selformtit">show</td>
<td class="selformtit"><b>query</b> </td>
<td class="selform"> &nbsp;
</td></tr>

<tr>
<td class="selform"><input type="checkbox" name="showartist"
  <?php if (strcmp($showartist, "on")==0){ ?>checked <?php } ?>></td>
<td class="selformtit"><b>Artist</b> </td>
<td class="selform"> <input type="text" name="artist" value="<?php echo $artist ?>" size="20" maxlength="200">
</td></tr>

<tr>
<td class="selform"><input type="checkbox" name="showtitle"
  <?php if (strcmp($showtitle, "on")==0){ ?>checked <?php }?>></td>
<td class="selformtit"><b>Title</b></td>
<td class="selform"> <input type="text" name="title"  value="<?php echo $title  ?>" size="20" maxlength="200">
</td></tr>

<tr>
<td class="selform"><input type="checkbox" name="showgenre"
  <?php if (strcmp($showgenre, "on")==0){ ?>checked <?php }?>></td>
<td class="selformtit"><b>Genre</b></td>
<td class="selform">
<select name="genre"><?php print_genre_options($genre); ?></select>
</td></tr>

<tr>
<td class="selform"><input type="checkbox" name="showrating"
  <?php if (strcmp($showrating, "on")==0){ ?>checked <?php }?>></td>
<td class="selformtit"><b>Rating</b></td>
<td class="selform">
<select name="rating"><?php print_rating_options($rating); ?></select>
</td></tr>

<tr><td colspan="2" class="selform">
<input type="reset" value="Clear">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="submit" value="&nbsp;&nbsp;Query&nbsp;&nbsp;"></td></tr>
</table>
<?php
} # END OF show_selform
?>


<form action="cont_gdsel.php" method="get">

<?php


show_selform($showartist, $artist, $showtitle, $title, $showgenre, $genre,
			 $showlang, $lang, $showyear, $yearfrom, $yearto, $showrating, $rating,
			 $showbpm, $bpmfrom, $bpmto, $showsource, $source,
			 $showcreated, $created, $showmodified, $modified);



# set default val for mp3 Play-Method
if(!isset($playtp)){ $playtp="df"; }

if(isset($artist)){
  # open data source
#  $benutzer = "music";
#  $passwort = "music";
#  $db = "GiantDisc";
#  $dbh = mysql_connect( "localhost", $benutzer, $passwort );

###  Sonderzeichen tauschen
$artist = str_replace("'","&#39;",$artist);
$title = str_replace("'","&#39;",$title);

  ### VERY preliminary query
  if (isset($_GET['CDid']))
  	{
#  	print "$CDid";
#	if (strlen($rating)==0){$rating=0;}
#  	$recset = mysql_db_query ("GiantDisc", "SELECT * FROM tracks WHERE artist LIKE '%$artist%' AND title LIKE '%".$title."%' AND (genre1 LIKE '".$genre."%' OR genre2 LIKE '".$genre."%') AND rating>=".$rating." AND sourceid =".$CDid." ");
  	$recset = mysqli_query ( $link, "SELECT * FROM tracks WHERE artist LIKE '%$artist%' AND title LIKE '%".$title."%' AND sourceid LIKE '%$CDid%' ");

	  }
  else
  	{
	  if (strlen($rating)==0){$rating=0;}
  	$recset = mysqli_query ( $link, "SELECT * FROM tracks WHERE artist LIKE '%$artist%' AND title LIKE '%".$title."%' AND (genre1 LIKE '".$genre."%' OR genre2 LIKE '".$genre."%') AND rating>=".$rating." ");
	  }	

  print("<p><small><b>".mysqli_affected_rows( $link )." machting tracks</b></small></p>");
  print("<table border=\"0\" cellspacing=\"0\">\n");
  $evenln = 1;
  while($row = mysqli_fetch_array($recset)) {
    if ($evenln){
      print "<tr class=\"lneven\">";
	}
	else{
      print "<tr class=\"lnodd\">";
	}
	if (strlen($row["year"])>2){ $yearstr = "'".substr($row["year"], 2, 2);}
	else                       { $yearstr = $row["year"];}
    $evenln = 1-$evenln; #toggle $evenln

    if (strcmp($playtp, "dl")==0){$mp3path = full_mp3_fname($row["mp3file"]);}

    if (strcmp($showartist, "on")==0){
      print "<td class=\"trklst\">&nbsp;".$row["artist"]." </td>";
	}
    if (strcmp($showtitle, "on")==0){
	  print "<td class=\"trklst\">&nbsp;".$row["title"]." </td>";
	}
    if (strcmp($showgenre, "on")==0){
	  print "<td class=\"trklst\">&nbsp;";
      if (strlen($row["genre1"])>0){
	    print_genre_string($row["genre1"]);
	  }
      if (strlen($row["genre1"])>0 && strlen($row["genre2"])>0){print "<br>";}
      if (strlen($row["genre2"])>0){
	    print "&nbsp;";
	    print_genre_string($row["genre2"]);
	  }
	  print "</td>";
	}
    if (strcmp($showrating, "on")==0){
	  print "<td class=\"trklst\">&nbsp;";
      print_rating_string($row["rating"]);
	  print "</td>";
	}
	  print "<td class=\"trklst\">&nbsp;".$yearstr." </td>";
	  print "<td class=\"trklst\">&nbsp;".seconds2time($row["length"])." </td>";
#	  print "<td class=\"trklst\">&nbsp;<a href=\"gddetails.php?id="
#		   		.$row["id"]."\"><img border=\"0\" src=\"img/view-c.gif\"></a></td>";
	  print "<td class=\"trklst\">&nbsp;<a href=\"gddetails.php?id="
		   		.$row["id"]."\"><img border=\"0\" src=\"img/edit-c.gif\" alt=\"Edit\" title=\"$output149\"></a></td>";
      # Download mp3
	  # types: playtp = dl, rd, df, dl or dr
#      if (strcmp($playtp, "dl")==0){
#	    print "<td class=\"trklst\">&nbsp;<a href=\"".$mp3path."\">"
#		     ."<img border=\"0\" src=\"img/play-c.gif\"></a></td>";
#	  }
#      else{ # all other playtypes handled by "gdtrid2mp3.php"
      $httphost = $_SERVER["HTTP_HOST"];
	    print "<td class=\"trklst\">&nbsp;<a href=\"http://";
      print "$httphost/music/01/";
			print $row["mp3file"]."\">"."<img border=\"0\" src=\"img/play-c.gif\" alt=\"Download\" title=\"$output080\"></a></td>";
#	  }
	print "</tr>\n";
  }
  print("</table>\n");
/*
*/
  mysqli_free_result($recset);
  mysqli_close( $link );
}
?>

<hr noshade>

<p>

</form>

<?php
?>
</body>
</html>
