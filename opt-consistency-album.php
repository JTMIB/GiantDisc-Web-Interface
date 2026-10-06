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
#include "gdwebdef.php";

print_header ("Options", "", "$cgi_dir", "$char_set", $output135, $output136, $output137, $output138);

## Variablen aus Browser-Zeile
#$nocovers=$_GET['nocovers'];
#$emptyalb=$_GET['emptyalb']; 
if (isset($_GET['nocovers'])) {
    $nocovers = $_GET['nocovers'];
}
if (isset($_GET['emptyalb'])) {
    $emptyalb = $_GET['emptyalb'];
}
 
?>

<h2>Matching Albums</h2>


<?php 


  $link =  mysqli_connect( MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB );
  if ( ! $link )
      die( "Keine Verbindung zu MySQL" );	
#mysql_select_db("GiantDisc", $link);



if(isset($emptyalb)){
  $recset = mysqli_query ( $link,
             "SELECT album.* FROM album "
            ."LEFT JOIN tracks ON album.cddbid=tracks.sourceid "
            ."WHERE tracks.sourceid is NULL");

  print "<table>\n";
  $counter=0;
  while($row = mysqli_fetch_array($recset) and $row["cnt"]==0) {
    write_albumlink($prefix, $row, $showedit, $output132, $output133, $b_player, $bashplayer, $xspf, $output134);
    $counter++;
  }
  print "</table>\n";
  print "<p><b>".($counter ? $counter : "$output051")." $output052</b></p>";
  mysqli_free_result($recset);  
}



elseif(isset($nocovers)){
  $recset = mysqli_query ( $link, "SELECT * FROM album "
            ."WHERE (ISNULL(coverimg) OR LENGTH(coverimg) < 8) "
            ."ORDER BY artist, title");

  print "<table>\n";
  $counter=0;
  while($row = mysqli_fetch_array($recset)) {
#    write_albumlink($prefix, $row, $showedit, $output132, $output133, $b_player, $bashplayer, $xspf, $output134);
    schreibe_albumlink($prefix, $htdocs, $imagedir, $row, $showedit, $output132, $output133, $b_player, $bashplayer, $xspf, $output134);
#    schreibe_albumlink("browse.php?l0=al-ar&l1=$l1&ar=$ar&albid=", $htdocs, $imagedir, $row, $showedit, $output132, $output133, $b_player, $bashplayer, $xspf, $output134);
      $counter++;
  }
  print "</table>\n";
  
  print "<p><b>".($counter ? $counter : "No")." $output046</b></p>";
  mysqli_free_result($recset);  
  mysqli_close( $link );
}



else{
  print "<p>No known album consistency check specified!</p>";
}

#######################################################################################


function schreibe_albumlink($prefix, $htdocs, $imagedir, $albumrecord, $showedit, $output132, $output133, $b_player, $bashplayer, $xspf, $output134)
{
  print "<tr>";

### Favoriten Beginn
$link =  mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
 if ( ! $link )
     die( "Keine Verbindung zu MySQL" );
     
		  $abfrage_stern = mysqli_query( $link ,"SELECT evaluation FROM album_fav WHERE cddbid LIKE '%$albumrecord[cddbid]%'" );
		  $anz_reihen_stern = mysqli_num_rows( $abfrage_stern );
		  if ( $anz_reihen_stern >= 1 )
		    {
        while ( $datensatz = mysqli_fetch_row( $abfrage_stern ) )
	     {
        foreach ( $datensatz as $field )
		      {
		      $feld = $field;
		      }
	     }
          
        print "<td class=\"trklst\">";
        if ($feld >= 1)
          print "<img src=\"img/stern_voll.gif\" height=\"14\" border=\"0\" align=\"right\"><br>";
		    else
          print "<img src=\"img/stern_leer.gif\" height=\"14\" border=\"0\" align=\"right\"><br>";
        if ($feld >= 2)
          print "<img src=\"img/stern_voll.gif\" height=\"14\" border=\"0\" align=\"right\"><br>";
		    else
          print "<img src=\"img/stern_leer.gif\" height=\"14\" border=\"0\" align=\"right\"><br>";          		    
        if ($feld >= 3)
          print "<img src=\"img/stern_voll.gif\" height=\"14\" border=\"0\" align=\"right\"><br>";
		    else
          print "<img src=\"img/stern_leer.gif\" height=\"14\" border=\"0\" align=\"right\"><br>";
        if ($feld >= 4)
          print "<img src=\"img/stern_voll.gif\" height=\"14\" border=\"0\" align=\"right\"><br>";
		    else
          print "<img src=\"img/stern_leer.gif\" height=\"14\" border=\"0\" align=\"right\"><br>";
        if ($feld >= 5)
          print "<img src=\"img/stern_voll.gif\" height=\"14\" border=\"0\" align=\"right\"><br>";
		    else
          print "<img src=\"img/stern_leer.gif\" height=\"14\" border=\"0\" align=\"right\"><br>";
        print "</td>";
        }
      else
        {
        print "<td class=\"trklst\">";
        print "<img src=\"img/0.gif\" height=\"14\" border=\"0\" align=\"right\">";
        print "</td>";
        }
      mysqli_free_result( $abfrage_stern );
mysqli_close( $link );      
### Favoriten Ende      		

  
  # Cover ausgeben
  print "<td class=\"trklst\">";
  $image = $albumrecord["coverimg"];
  
  if (strlen($albumrecord["coverimg"])>1){
    $pfad = $htdocs;
#    $imgpath = full_mp3_fname($albumrecord["coverimg"]);
    $imgpath = str_replace( $pfad, "", $imagedir );

# 80*80 vom Cover anzeigen
  $teil_cover = explode(".", $image);
  $cover_a = array( $imgpath, '/', $teil_cover[0], '-t', '.', $teil_cover[1] );
  $cover_t = implode("", $cover_a);  
  $cover_l = array( $pfad, $imgpath, '/', $teil_cover[0], '-t', '.', $teil_cover[1] );
  $link_ct = implode("", $cover_l);
  $imgpath_pic = $imgpath."/".$image;  
  
    if (file_exists($link_ct)){
      #print "Ja!<br>";
  	  print "<a href=\"$imgpath_pic\" data-lightbox=\"bild-1\">";
      print "<img width=\"80\" src=\"".$cover_t."\" height=\"80\" border=\"0\">";
  	  print "</a>";     
    }
    else{
      #print "Nein!<br>";
  	  print "<a href=\"$imgpath_pic\" data-lightbox=\"bild-1\">";
      print "<img width=\"80\" src=\"".$imgpath_pic."\" height=\"80\" border=\"0\">";
  	  print "</a>";    
    }
#	print "$image<br>"; 
#  print "$pfad<br>";
#	print "$imagedir<br>";
#	print "$imgpath<br>";
#	print "$imgpath_pic<br>";
#  print "$cover_t<br>";
#  print "$link_ct<br>";
  
  }
  else
  {
	$imgpath = "img/no-img-sm.gif";
#	print "<a href=\"$imgpath\">";
	print "<a href=\"cover.php?cddbid=".$albumrecord["cddbid"]."\" title=\"bearbeiten\">";
	print "<img width=\"80\" src=\"".$imgpath."\" height=\"80\" border=\"0\">";
	print "</a>";
  }

  print "</td>";
  print "<td class=\"trklst\">".$albumrecord["artist"]."</td>";
  print "<td class=\"trklst\"> - </td>";
  if (strlen($prefix)>0){
    print "<td><a class=\"brslst\" href=\"".$prefix.$albumrecord["cddbid"]."#liste\">"
        .$albumrecord["title"]."</a></td> ";
  }
  else{
    print "<td class=\"trklst\">".$albumrecord["title"]."</td> ";
  }


  if ($showedit){
    print "<td class=\"trklst\">&nbsp;<a href=\"gdeditalbum.php?cddbid="
		   		.$albumrecord["cddbid"]."\" target=\"_blank\">"
				."<img border=\"0\" src=\"img/edit-c.gif\" alt=\"Edit\"></a></td>";
  }

  #Album-Playlist-Link (by Merlot)
  #Alben
  print "<td class=\"trklst\">&nbsp;<a href=\"gdtrid2mp3.php?playtp=pr"
        ."&prAlbum=1" #don't use a new value for $playtp, it would interfere with cookie-settings
        ."&albid=".$albumrecord["cddbid"]
        ."&id=1"
        ."\">"."<img border=\"0\" src=\"img/download.png\" width=\"40\" alt=\"Download\" title=\"$output132\"></a>";

      if($bashplayer == "yes")
        {
        print "&nbsp; &nbsp; &nbsp;<a href=\"play.php?l0=thisalb";
        #print "&prAlbum=1"; 
        print "&albid=".$albumrecord["cddbid"];
        #print "&id=1";
        print "&step=start";
        print "\">";
        print "<img border=\"0\" src=\"img/button_blue_play.png\" width=\"40\" alt=\"Download\" title=\"$output133 $b_player\"></a>";
        }

      if($xspf == "yes")
        {
        print "&nbsp; &nbsp; &nbsp;<a href=\"gdtrid2xspf.php?playtp=pr"
        ."&prAlbum=1" #
        ."&albid=".$albumrecord["cddbid"]
        ."&id=1"
        ."\">";
        print "<img border=\"0\" src=\"img/xspf.png\" width=\"40\" alt=\"Download\" title=\"$output134\"></a>";
        } 

  print "</td>";
	print "<td>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<a href=\"cover.php?cddbid=".$albumrecord["cddbid"]."\">";
	print "<img border=\"0\" src=\"img/edit.png\" alt=\"Edit\" title=\"bearbeiten\"></td>";

  print "</tr>";
}

#######################################################################################

?>



<?php
print_footer ("");
?>
