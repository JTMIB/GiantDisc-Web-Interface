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
include "gdwebdef.php";

$playtp = set_get_cookie($setplaytp, "playtp", "dl", $HTTP_COOKIE_VARS);

print_header ("settings", "", "$cgi_dir", "$char_set", $output135, $output136, $output137, $output138);

$link =  mysqli_connect( MYSQL_HOST, MYSQL_USER, MYSQL_PASS,  MYSQL_DB );
 if ( ! $link )
     die( "Keine Verbindung zu MySQL" );

## Variablen aus Browser-Zeile
$artist=$_GET['artist'];
$title=$_GET['title'];
$genre=$_GET['genre']; 
     
function show_selform($showartist, $artist, $showtitle, $title,
       $showcomposer, $composer,
       $showgenre, $genre,
			 $showmodified, $modified){
?>

<table border="0">
<tr>
<td class="selformtit">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </td>
<td class="selformtit"><b>query album</b> </td>
<td class="selform"> &nbsp;
</td></tr>

<tr>
<td class="selform"> </td>
<td class="selformtit"><b>Artist</b> </td>
<td class="selform"> <input type="text" name="artist" value="<?php echo $artist ?>" size="20" maxlength="200">
</td></tr>

<tr>
<td class="selform"> </td>
<td class="selformtit"><b>Title</b></td>
<td class="selform"> <input type="text" name="title"  value="<?php echo $title  ?>" size="20" maxlength="200">
</td></tr>

<tr>
<td class="selform"> </td>
<td class="selformtit"><b>Genre</b></td>
<td class="selform">
<select name="genre"><?php print_genre_options($genre); ?></select>
</td></tr>


<tr><td colspan="2" class="selform">
<input type="reset" value="Clear">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="submit" value="&nbsp;&nbsp;Query&nbsp;&nbsp;"></td></tr>
</table>
<?php
} # END OF show_selform
?>
<form action="cont_gdalb.php" method="get">

<?php

show_selform($showartist, $artist, $showtitle, $title,
       $showcomposer, $composer,
       $showgenre, $genre, 
			 $showmodified, $modified);

if(isset($artist)){
  # open data source

###  Sonderzeichen tauschen
$artist = str_replace("'","&#39;",$artist);
$title = str_replace("'","&#39;",$title);

  ### VERY preliminary query
print "genre<br>";
if ($genre == "")
    {
  	$recset = mysqli_query ( $link, "SELECT * FROM album WHERE artist LIKE '%$artist%' AND title LIKE '%".$title."%' ");
    }  
else
    {
  	$recset = mysqli_query ( $link, "SELECT * FROM album WHERE artist LIKE '%$artist%' AND title LIKE '%".$title."%' AND genre LIKE '".$genre."%' ");
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
  $evenln = 1-$evenln; #toggle $evenln
  
  print "<td class=\"trklst\">&nbsp;".$row["artist"]." </td>";
	print "<td class=\"trklst\">&nbsp;".$row["title"]." </td>";
	print "<td class=\"trklst\">&nbsp;".$row["composer"]." </td>";
	print "<td class=\"trklst\">&nbsp;";
	   print_genre_string($row["genre"]);
	print "</td>";
	
	print "<td><a href=\"cover.php?cddbid=".$row["cddbid"]."\">";
	print "<img border=\"0\" src=\"img/edit.png\" alt=\"Edit\" title=\"bearbeiten\"></td>";
	

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



</form>

</body>
</html>