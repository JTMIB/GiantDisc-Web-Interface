<?php

include "control_web.inc";
include "gdwebdef.php";

$playtp = set_get_cookie($setplaytp, "playtp", "dl", $HTTP_COOKIE_VARS);

print_header ("browse", "", "$cgi_dir", "$char_set", $output135, $output136, $output137, $output138);


#  $link =  mysql_connect( MYSQL_HOST, MYSQL_USER, MYSQL_PASS );
  $link =  mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
  if ( ! $link )
      die( "Keine Verbindung zu MySQL" );

## Variablen aus Browser-Zeile
$l0=$_GET['l0']; 
$l1=$_GET['l1'];
$ar=$_GET['ar']; 
$albid=$_GET['albid'];
$shgn=$_GET['shgn'];
$pg=$_GET['pg'];
$pl_id=$_GET['pl_id'];
$title=$_GET['title'];
$tool=$_GET['tool'];
$id=$_GET['id'];
$source=$_GET['source'];
$evaluation=$_GET['evaluation'];


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

  #$qrystr = "SELECT * FROM genre WHERE $whereclause ORDER BY id";
  #$recset = mysql_db_query ("GiantDisc", $qrystr);
  $con = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
  $recset = mysqli_query( $con, "SELECT * FROM genre WHERE $whereclause ORDER BY id");
  print "\n<div class='genrebox'>";
  while($row = mysqli_fetch_array($recset)) {
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


#######################################################################################

?>

<p>

<?php

### Show first level:
print "<table class='browsemenu'>";
print "<tr>";
print "<td><small><b><a href=\"browse.php?l0=tr-ar\">$output126</a></b></small></td>";
print "<td><small><b><a href=\"browse.php?l0=tr-ti\">$output125</a></b></small></td>";
print "<td><small><b><a href=\"browse.php?l0=tr-gn\">$output124</a></b></small></td>";
print "<td><small><b><a href=\"browse.php?l0=s_tr\">$output123</a></b></small></td>";
print "</tr>";
print "<tr>";
print "<td><small><b><a href=\"browse.php?l0=al-ar\">$output122</a></b></small></td>";
print "<td><small><b><a href=\"browse.php?l0=al-ti\">$output121</a></b></small></td>";
print "<td><small><b><a href=\"browse.php?l0=al-gn\">$output120</a></b></small></td>";
print "<td><small><b><a href=\"browse.php?l0=allalb&pg=1\">$output109</a></b></small></td>";
print "<td><small><b><a href=\"browse.php?l0=playlist\">$output119</a></b></small></td>";
print "</tr>";
print "</table>";


$qrystr = ""; # clear query string

if (isset($l0)){

  ### Tracks by Artist
  if (strcmp($l0, "tr-ar")==0){
    print "<h4>$output126</h4>";
    write_alphabet("browse.php?l0=tr-ar&l1=", $l1);
    if (isset($l1)){ # level 1 specified?
      $tempquery = "SELECT DISTINCT artist FROM tracks WHERE artist LIKE '$l1%' ORDER BY artist";
      #$recset = mysql_db_query ("GiantDisc", $tempquery);
      $con = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
      $recset = mysqli_query( $con, $tempquery);
      if (mysqli_affected_rows($con)>0){print("<p><small>".mysqli_affected_rows($con)." artists</small><br>");}
      while($row = mysqli_fetch_array($recset)) {
	    write_artistlink("browse.php?l0=tr-ar&l1=$l1&ar=", $row["artist"]);
      }

	  if (isset($ar)){ # artist / level 2 specified?
        $qrystr = "SELECT * FROM tracks WHERE artist LIKE '$ar%' ORDER BY title ";
      }
	}
  }


  ### Tracks by Title
  if (strcmp($l0, "tr-ti")==0){
    print "<h4>$output125</h4>";
    write_alphabet("browse.php?l0=tr-ti&l1=", "");
	if (isset($l1)){ # level 1 specified?
      $qrystr = "SELECT * FROM tracks WHERE title LIKE '$l1%' ORDER BY title ";
	}
  }


  ### Tracks by Genre
  if (strcmp($l0, "tr-gn")==0){
    print "<h4>$output124</h4>";
    write_genres("browse.php?l0=tr-gn&l1=", $l1, $shgn);
	if (isset($l1) and strlen($l1)>0 ){ # level 1 specified?
      $qrystr = "SELECT * FROM tracks WHERE genre1 LIKE '$l1%' or genre2 LIKE '$l1%' ORDER BY artist ";
	}
  }


  ### Albums by Artist
  if (strcmp($l0, "al-ar")==0){
    print "<h4>$output122</h4>";
    write_alphabet("browse.php?l0=al-ar&l1=", $l1);
    if (isset($l1)){ # level 1 specified?
      #$tempquery = "SELECT DISTINCT artist FROM album WHERE artist LIKE '$l1%' ORDER BY artist";
      #$recset = mysql_db_query ("GiantDisc", $tempquery);
      
      $con = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
      $recset = mysqli_query( $con, "SELECT DISTINCT artist FROM album WHERE artist LIKE '$l1%' ORDER BY artist");
      
      if (mysqli_affected_rows($con)>0){print("<p><small>".mysqli_affected_rows($con)." artists</small><br>");}
      while($row = mysqli_fetch_array($recset)) {
	    write_artistlink("browse.php?l0=al-ar&l1=$l1&ar=", $row["artist"]);
      }

	  if (isset($ar)){ # artist / level 2 specified?
        $tempquery = "SELECT * FROM album WHERE artist LIKE '$ar%' ORDER BY title";
        $recset = mysqli_query ( $con , $tempquery);
        if (mysqli_affected_rows( $con )>0){print("<p><small>".mysqli_affected_rows( $con )." albums</small><br>");}
        print "<table borders=\"0\">";
        while($row = mysqli_fetch_array($recset)) {

	      schreibe_albumlink("browse.php?l0=al-ar&l1=$l1&ar=$ar&albid=", $htdocs, $imagedir, $row, $showedit, $output132, $output133, $b_player, $bashplayer, $xspf, $output134);
        }
        print "</table>";
      }

	  if (isset($albid)){ # albumid / level 3 specified?
        $qrystr = "SELECT * FROM tracks WHERE sourceid = '$albid' ORDER BY tracknb ";
      }
	}
  }


  ### Albums by Title
  if (strcmp($l0, "al-ti")==0){
    print "<h4>$output121</h4>";
    write_alphabet("browse.php?l0=al-ti&l1=", $l1);
    if (isset($l1)){ # level 1 (initial char) specified?
      #$tempquery = "SELECT * FROM album WHERE title LIKE '$l1%' ORDER BY title";
      #$recset = mysql_db_query ("GiantDisc", $tempquery);
      $con = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
      $recset = mysqli_query( $con, "SELECT * FROM album WHERE title LIKE '$l1%' ORDER BY title");
      if (mysqli_affected_rows($con)>0){print("<p><small>".mysqli_affected_rows($con)." albums</small><br>");}
      print "<table borders=\"0\">";
      while($row = mysqli_fetch_array($recset)) {
      schreibe_albumlink("browse.php?l0=al-ti&l1=$l1&albid=", $htdocs, $imagedir, $row, $showedit, $output132, $output133, $b_player, $bashplayer, $xspf, $output134);

      }
      print "</table>";
    }

    if (isset($albid)){ # albumid / level 2 specified?
      $qrystr = "SELECT * FROM tracks WHERE sourceid = '$albid' ORDER BY tracknb ";
    }
  }


  ### Albums by Genre
  if (strcmp($l0, "al-gn")==0){
    print "<h4>$output120</h4>";
    write_genres("browse.php?l0=al-gn&l1=", $l1, $shgn);
	if (isset($l1) and strlen($l1)>0 ){ # level 1 specified?
      $tempquery = "SELECT * FROM album WHERE genre LIKE '$l1%' ORDER BY artist";
      $recset = mysqli_query ( $link, $tempquery);
      print("<p><small>".mysqli_affected_rows( $link)." albums</small><br>");
      print "<table borders=\"0\">";
      while($row = mysqli_fetch_array($recset)) {
	    schreibe_albumlink("browse.php?l0=al-gn&l1=$l1&shgn=$shgn&albid=", $htdocs, $imagedir, $row, $showedit, $output132, $output133, $b_player, $bashplayer, $xspf, $output134);
      }
      print "</table>";
    }

    if (isset($albid)){ # albumid / level 2 specified?
      $qrystr = "SELECT * FROM tracks WHERE sourceid = '$albid' ORDER BY tracknb ";
    }
  }

  ### all Albums
  if (strcmp($l0, "allalb")==0){
    if ($source == "0")
        $ausgabe=" - $output110";
    if ($source == "1")
        $ausgabe=" - $output111";
    if ($source == "2")
        $ausgabe=" - $output112";
    if ($source == "3")
        $ausgabe=" - $output113";
    if ($source == "4")
        $ausgabe=" - $output114";
    if ($source == "5")
        $ausgabe=" - $output115";
            
    print "<h4>$output109 $ausgabe</h4>";

  print "<table><tr><td>";
	if ($albid == 0){
	 if ($source == "")
	   {
    # alles -> ohne Filter
    if ($evaluation >= 1 )
      {
      $tempquery = "SELECT album.artist,
                          album.title,
                          album.composer,
                          album.cddbid,
                          album.coverimg,
                          album.covertxt,
                          album.modified,
                          album.genre
                          FROM album
                          JOIN album_fav WHERE evaluation >= $evaluation AND album.cddbid = album_fav.cddbid
                          ORDER BY album.artist";
      }
      else
      {
      $tempquery = "SELECT * FROM album ORDER BY artist";
      }
     }
     else 
     {
    # Filter 0=CD 1=radio 2=vinyl 3=tape 4=tv 5=video 
     if ($evaluation >= 1)
     {
     $ergebnis = mysqli_query( $link , "DROP TABLE fav_temp" );
     # fav_temp erzeugen
     $createtable = mysqli_query( $link , "CREATE TABLE fav_temp (
                          artist		varchar(255),
                          title		varchar(255),
                          composer	varchar(255),
                          cddbid		varchar(20) not null,
                          coverimg	varchar(255),
                          covertxt	mediumtext,
                          modified	date,
                          genre		varchar(10)
                          )");

     $filter_step1 = "INSERT INTO fav_temp (artist, title, composer, cddbid, coverimg, covertxt, modified, genre)
                          SELECT album.artist,
                          album.title,
                          album.composer,
                          album.cddbid,
                          album.coverimg,
                          album.covertxt,
                          album.modified,
                          album.genre
                          FROM album
                          JOIN tracks WHERE album.cddbid = tracks.sourceid AND tracks.tracknb = 1 AND tracks.source LIKE '%$source%'
                          ORDER BY album.artist";     
     $ergebnis = mysqli_query( $link, $filter_step1);

     $tempquery = "SELECT fav_temp.artist,
                          fav_temp.title,
                          fav_temp.composer,
                          fav_temp.cddbid,
                          fav_temp.coverimg,
                          fav_temp.covertxt,
                          fav_temp.modified,
                          fav_temp.genre
                          FROM fav_temp
                          JOIN album_fav WHERE evaluation >= $evaluation AND fav_temp.cddbid = album_fav.cddbid
                          ORDER BY fav_temp.artist";     
     
     }
     else
     {
     $tempquery = "SELECT album.artist,
                          album.title,
                          album.composer,
                          album.cddbid,
                          album.coverimg,
                          album.covertxt,
                          album.modified,
                          album.genre
                          FROM album
                          JOIN tracks WHERE cddbid = sourceid AND tracknb = 1 AND source = $source
                          ORDER BY album.artist";     
     }
	    }
	    
      #$recset = mysql_db_query ("GiantDisc", $tempquery);
      $con = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
      $recset = mysqli_query( $con, $tempquery);
      if (mysqli_affected_rows($con)>0){print("<p><small>".mysqli_affected_rows($con)." $output117</small><br>");}
      print "<small>$output116";

	  $anz_reihen = mysqli_affected_rows($con);
	  $a = 0;
	  $b = 1; # erste Seite
	  $showmax = 50;

# erste Seite	  
	  	if ($source=="")
		    print "<a href=\"browse.php?l0=allalb&pg=$b&evaluation=$evaluation\"><img src=\"img/doppelpfeil_links.png\" width=12 ></a> ";
		  else
		    print "<a href=\"browse.php?l0=allalb&pg=$b&source=$source&evaluation=$evaluation\"><img src=\"img/doppelpfeil_links.png\" width=12 ></a> ";
# vorige Seite
      if ( $pg > 1 )
        $nextpg = $pg - 1;
      else
        $nextpg = 1;  
	  	if ($source=="")
		    print "<a href=\"browse.php?l0=allalb&pg=$nextpg&evaluation=$evaluation\"><img src=\"img/einzelpfeil_links.png\" width=12 ></a>&nbsp;&nbsp;";
		  else
		    print "<a href=\"browse.php?l0=allalb&pg=$nextpg&source=$source&evaluation=$evaluation\"><img src=\"img/einzelpfeil_links.png\" width=12 ></a>&nbsp;&nbsp;";

        	  
	  for ($b=1; $b*$showmax <= $anz_reihen; $b++){
	  if ($pg == $b)
	  	{
	  	#aktuelle Seite
		  print "<b>$b</b> ";
		  }
	  else
	  	{
	  	if ($source=="")
		    print "<a href=\"browse.php?l0=allalb&pg=$b&evaluation=$evaluation\"><b>$b</b></a> ";
		  else
		    print "<a href=\"browse.php?l0=allalb&pg=$b&source=$source&evaluation=$evaluation\"><b>$b</b></a> ";
		  }
	  }
	  $b--;

	  if ($anz_reihen > $b*$showmax){
	  $b++;
	  if ($pg == $b)
	  	{
	  	# letzte Seite
		  print "<b>$b</b> ";
		  }
	  else
	  	{
	  	if ($source=="")
		    print "<a href=\"browse.php?l0=allalb&pg=$b&evaluation=$evaluation\"><b>$b</b></a> ";
		  else
		    print "<a href=\"browse.php?l0=allalb&pg=$b&source=$source&evaluation=$evaluation\"><b>$b</b></a> ";
		  }

	  }

# naechste Seite
      $nextpg = $pg + 1;
      if ($nextpg > $b)
        $nextpg = $b;
	  	if ($source=="")
		    print "&nbsp;<a href=\"browse.php?l0=allalb&pg=$nextpg&evaluation=$evaluation\"><img src=\"img/einzelpfeil_rechts.png\" width=12 ></a> ";
		  else
		    print "&nbsp;<a href=\"browse.php?l0=allalb&pg=$nextpg&source=$source&evaluation=$evaluation\"><img src=\"img/einzelpfeil_rechts.png\" width=12 ></a> ";

# letzte Seite
	  	if ($source=="")
		    print "<a href=\"browse.php?l0=allalb&pg=$b&evaluation=$evaluation\"><img src=\"img/doppelpfeil_rechts.png\" width=12 ></a> ";
		  else
		    print "<a href=\"browse.php?l0=allalb&pg=$b&source=$source&evaluation=$evaluation\"><img src=\"img/doppelpfeil_rechts.png\" width=12 ></a> ";

	  $pg--;
	  print "</small></p>";
	  
  print "</td>";
  print "<td> &nbsp; &nbsp; </td>";
#  print "<td>Filter $anz_reihen</td>";
#  print "<td> &nbsp; &nbsp; </td>";
  print "<td><small><a href=\"browse.php?l0=allalb&pg=1&evaluation=$evaluation\"> $output118 </a></small></td>";
  print "<td><small><a href=\"browse.php?l0=allalb&pg=1&source=0&evaluation=$evaluation\"> $output110 </a></small></td>";
  print "<td><small><a href=\"browse.php?l0=allalb&pg=1&source=1&evaluation=$evaluation\"> $output111 </a></small></td>";
  print "<td><small><a href=\"browse.php?l0=allalb&pg=1&source=2&evaluation=$evaluation\"> $output112 </a></small></td>";
  print "<td><small><a href=\"browse.php?l0=allalb&pg=1&source=3&evaluation=$evaluation\"> $output113 </a></small></td>";
  print "<td><small><a href=\"browse.php?l0=allalb&pg=1&source=4&evaluation=$evaluation\"> $output114 </a></small></td>";
  print "<td><small><a href=\"browse.php?l0=allalb&pg=1&source=5&evaluation=$evaluation\"> $output115 </a></small></td>";
  print "<td> &nbsp; &nbsp; </td>";
#  print "<td>Filter $evaluation</td>";
  print "<td>";
?>
<!-- 1. Stern -->
<?php
if ($evaluation >= 1)
{
print "<span class=\"star active\" style=\"width: 250px; margin: 0px;\" onmouseover=\"removeActive(this);\" onmouseout=\"resetActive();\">";
}
else
{
print "<span class=\"star\" style=\"width: 250px; margin: 0px;\" onmouseover=\"removeActive(this);\" onmouseout=\"resetActive();\">";
}
print"<a href=\"browse.php?l0=allalb&pg=1&source=$source&evaluation=1\"><span class=\"spacer\">&nbsp;</span></a>";?>
 <!-- 2. Stern -->
<?php
 if ($evaluation >= 2)
 {
 print "<span class=\"star active\" style=\"width: 200px;\">";
 }
 else
 {
 print "<span class=\"star\" style=\"width: 200px;\">";
 }
 print"<a href=\"browse.php?l0=allalb&pg=1&source=$source&evaluation=2\"><span class=\"spacer\">&nbsp;</span></a>";?>
  <!-- 3. Stern -->
<?php
  if ($evaluation >= 3)
  {
  print "<span class=\"star active\" style=\"width: 150px;\">";
  }
  else
  {
  print "<span class=\"star\" style=\"width: 150px;\">";
  }
  print"<a href=\"browse.php?l0=allalb&pg=1&source=$source&evaluation=3\"><span class=\"spacer\">&nbsp;</span></a>";?>
   <!-- 4. Stern -->
<?php
   if ($evaluation >= 4)
   {
   print "<span class=\"star active\" style=\"width: 100px;\">";
   }
   else
   {
   print "<span class=\"star\" style=\"width: 100px;\">";
   }
   print"<a href=\"browse.php?l0=allalb&pg=1&source=$source&evaluation=4\"><span class=\"spacer\">&nbsp;</span></a>";?>
    <!-- 5. Stern -->
<?php
    if ($evaluation >= 5)
    {
    print "<span class=\"star active\" style=\"width: 50px;\">";    
    }
    else
    {
    print "<span class=\"star\" style=\"width: 50px;\">";
    }
    print"<a href=\"browse.php?l0=allalb&pg=1&source=$source&evaluation=5\"><span class=\"spacer\">&nbsp;</span></a>";?>
    <!-- 5. Stern Ende-->
    </span>
   <!-- 4. Stern Ende-->
  </span>
  <!-- 3. Stern Ende-->
 </span>
 <!-- 2. Stern Ende-->
 </span>
<!-- 1. Stern Ende-->
</span>
<?php
  print "</td>";  
  print "</tr></table>";
  
      print "<hr>";

      print "<table borders=\"0\">";
      while($row = mysqli_fetch_array($recset)) {
		$albumrecord = $row;
#		print "$albumrecord[1]<br>";
#		print "$showedit<br>";
# write_albumlink("browse.php?l0=al-ti&l1=$l1&albid=", $row, $showedit);
##################
		$a++;

		#if ($a <= 1+$showmax AND $a >= 1)
		if ($a >= 1+$pg*$showmax AND $a <= $showmax+$pg*$showmax)
		{
		print "<tr>";  #<tr class="lnodd"   ...even>
### Favoriten Beginn
		  $abfrage_stern = mysqli_query( $link,"SELECT evaluation FROM album_fav WHERE cddbid LIKE '%$albumrecord[cddbid]%'" );
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
### Favoriten Ende      		
		
    print "<td class=\"trklst\">";

			# Cover ausgeben
			if (strlen($albumrecord["coverimg"])>1){
			  $pfad = $htdocs;
				$imgpath = str_replace( $pfad, "", $imagedir );
				$image = $albumrecord["coverimg"];
         
# 80*80 vom Cover anzeigen
        $teil_cover = explode(".", $image);
        $cover_a = array( $imgpath, '/', $teil_cover[0], '-t', '.', $teil_cover[1] );
        $cover_t = implode("", $cover_a);
        $cover_l = array( $pfad, $imgpath, '/', $teil_cover[0], '-t', '.', $teil_cover[1] );
        $link_ct = implode("", $cover_l);
        $imgpath_pic = $imgpath."/".$image;
        
				#print "Hallo<br>";
				#print "$pfad<br>";
				#print "$imgpath<br>";
				#print "$image<br>";
        #print "$cover_t<br>";
        #print "$link_ct<br>";
        #print "$imgpath_pic<br>";         
        

        if (file_exists($link_ct)){
          #print "Ja!<br>";
  	      print "<a href=\"$imgpath_pic\" data-lightbox=\"bild-1\">";
          print "<img width=\"80\" src=\"".$cover_t."\" height=\"80\" border=\"0\">";
  	      print "</a>";    
        }
        else{
          #print "Nein!<br>";
          # Tumbnail berechnen
          $pichoehe = 80; // 80 Pixel soll Bild hoch sein
          
          // Bilddaten feststellen
          $size=getimagesize("$pfad"."$imgpath"."/"."$image");
          
          $breite=$size[0];
          $hoehe=$size[1];

          $neueHoehe=$pichoehe; 
          $neueBreite=intval($breite*$neueHoehe/$hoehe);

          #print "Hallo<br>";
          #print "$pfad<br>";
          #print "$imgpath<br>";
          #print "$image<br>";
          #print "$imgpfad<br>";
          #print "$size[0]<br>";
          #print "$size[1]<br>";
          #print "$neueBreite<br>";
          #print "$neueHoehe<br>";
          

          if($size[2]==1) {
          // Es ist ein GIF
          $altesBild=ImageCreateFromGIF("$pfad"."$imgpath"."/"."$image");
          $neuesBild=ImageCreateTrueColor($neueBreite,$neueHoehe);
          ImageCopyResized($neuesBild,$altesBild,0,0,0,0,$neueBreite,$neueHoehe,$breite,$hoehe);
          ImageJPEG($neuesBild,"$pfad"."$cover_t");
          }
          
          if($size[2]==2) {
          // Es ist ein JPG
          $altesBild=ImageCreateFromJPEG("$pfad"."$imgpath"."/"."$image");
          $neuesBild=ImageCreateTrueColor($neueBreite,$neueHoehe);
          ImageCopyResized($neuesBild,$altesBild,0,0,0,0,$neueBreite,$neueHoehe,$breite,$hoehe);
          ImageJPEG($neuesBild,"$pfad"."$cover_t");
          }
          
          if($size[2]==3) {
          // Es ist ein PNG
          $altesBild=ImageCreateFromPNG("$pfad"."$imgpath"."/"."$image");
          $neuesBild=ImageCreateTrueColor($neueBreite,$neueHoehe);
          ImageCopyResized($neuesBild,$altesBild,0,0,0,0,$neueBreite,$neueHoehe,$breite,$hoehe);
          ImagePNG($neuesBild,"$pfad"."$cover_t");
          }

          # Ende Tumbnail berechnen
          $imgpathpic = $imgpath."/".$image; 
  	      print "<a href=\"$imgpathpic\" data-lightbox=\"bild-1\">";
          print "<img width=\"80\" src=\"".$cover_t."\" height=\"80\" border=\"0\">";
  	      print "</a>";
        }

        #print "$pfad<br>";    
        #print "$imgpath<br>";
        #print "$teil_str[3]<br>";
        #print "$cover_t<br>";
        #print "$link_ct<br>";

			}
			else
			{
				$imgpath = "img/no-img-sm.gif";
				print "<img width=\"80\" src=\"".$imgpath."\" height=\"80\" border=\"0\">";
			}
			
			print "</td>";
			
			# Artist - Album Name
			print "<td class=\"trklst\">".$albumrecord["artist"]."</td>";
			print "<td class=\"trklst\"> - </td>";
			# Link zum Album
			$prefix = "browse.php?l0=thisalb&albid=";
			print "<td><a class=\"brslst\" href=\"".$prefix.$albumrecord["cddbid"]."\">"
			.$albumrecord["title"]."</a></td> ";

#			if (strlen($prefix)>0){
#				print "<td><a class=\"brslst\" href=\"".$prefix.$albumrecord["cddbid"]."\">"
#				.$albumrecord["title"]."</a></td> ";
#			}
#			else{
#				print "<td class=\"trklst\">".$albumrecord["title"]."link</td> ";
#			}
#  if ($showedit){
#    print "<td class=\"trklst\">&nbsp;<a href=\"gdeditalbum.php?cddbid="
#		   		.$albumrecord["cddbid"]."\" target=\"_blank\">"
#				."<img border=\"0\" src=\"img/edit-c.gif\" alt=\"Edit\"></a></td>";
#  }

  #Album-Playlist-Link (by Merlot)
  #Alle Alben
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
        ."&prAlbum=1" #don't use a new value for $playtp, it would interfere with cookie-settings
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
##################
      }
      print "</table>";

	  print "<hr>";
	    #$recset = mysql_db_query ("GiantDisc", $tempquery);
	    #$con = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
	    $recset = mysqli_query( $con, $tempquery);
      if (mysqli_affected_rows($con)>0){print("<p><small>".mysqli_affected_rows($con)." $output117</small><br>");}
      print "<small>$output116";
      
	  $pg++;
	  $a = 0;
	  $b = 1; # erste Seite
	  $showmax = 50;
	  
# erste Seite	  
	  	if ($source=="")
		    print "<a href=\"browse.php?l0=allalb&pg=$b&evaluation=$evaluation\"><img src=\"img/doppelpfeil_links.png\" width=12 ></a> ";
		  else
		    print "<a href=\"browse.php?l0=allalb&pg=$b&source=$source&evaluation=$evaluation\"><img src=\"img/doppelpfeil_links.png\" width=12 ></a> ";
# vorige Seite
      if ( $pg > 1 )
        $nextpg = $pg - 1;
      else
        $nextpg = 1;  
	  	if ($source=="")
		    print "<a href=\"browse.php?l0=allalb&pg=$nextpg&evaluation=$evaluation\"><img src=\"img/einzelpfeil_links.png\" width=12 ></a>&nbsp;&nbsp;";
		  else
		    print "<a href=\"browse.php?l0=allalb&pg=$nextpg&source=$source&evaluation=$evaluation\"><img src=\"img/einzelpfeil_links.png\" width=12 ></a>&nbsp;&nbsp;";
	  
	  
	  for ($b=1; $b*$showmax <= $anz_reihen; $b++){
	  if ($pg == $b)
	  	{
	  	#aktuelle Seite
		  print "<b>$b</b> ";
		  }
	  else
	  	{
	  	if ($source=="")
		    print "<a href=\"browse.php?l0=allalb&pg=$b&evaluation=$evaluation\"><b>$b</b></a> ";
		  else
		    print "<a href=\"browse.php?l0=allalb&pg=$b&source=$source&evaluation=$evaluation\"><b>$b</b></a> ";		
		  }
	  }
	  $b--;

	  if ($anz_reihen > $b*$showmax){
	  $b++;
	  if ($pg == $b)
	  	{
	  	# letzte Seite
		  print "<b>$b</b> ";
		  }
	  else
	  	{
	  	if ($source=="")
		    print "<a href=\"browse.php?l0=allalb&pg=$b&evaluation=$evaluation\"><b>$b</b></a> ";
		  else
		    print "<a href=\"browse.php?l0=allalb&pg=$b&source=$source&evaluation=$evaluation\"><b>$b</b></a> ";		
		  }

	  }

 # naechste Seite
      $nextpg = $pg + 1;
      if ($nextpg > $b)
        $nextpg = $b;
	  	if ($source=="")
		    print "&nbsp;<a href=\"browse.php?l0=allalb&pg=$nextpg&evaluation=$evaluation\"><img src=\"img/einzelpfeil_rechts.png\" width=12 ></a> ";
		  else
		    print "&nbsp;<a href=\"browse.php?l0=allalb&pg=$nextpg&source=$source&evaluation=$evaluation\"><img src=\"img/einzelpfeil_rechts.png\" width=12 ></a> ";

# letzte Seite
	  	if ($source=="")
		    print "<a href=\"browse.php?l0=allalb&pg=$b&evaluation=$evaluation\"><img src=\"img/doppelpfeil_rechts.png\" width=12 ></a> ";
		  else
		    print "<a href=\"browse.php?l0=allalb&pg=$b&source=$source&evaluation=$evaluation\"><img src=\"img/doppelpfeil_rechts.png\" width=12 ></a> ";


	  $pg--;
	  print "</small></p>";

	  $pg--;
	}
  }

    ### This Album ###
  if (strcmp($l0, "thisalb")==0){
#    print "<h4>This Album</h4>";
	$tempquery = "SELECT * FROM album WHERE cddbid LIKE '%$albid'";
    $recset = mysqli_query ( $link, $tempquery);
	$a = 0;
	$anz_reihen = mysqli_affected_rows( $link );
	print "<hr>";
      print "<table borders=\"0\">";
      while($row = mysqli_fetch_array($recset)) {
		$albumrecord = $row;
		$a++;
		print "<tr>";
		print "<td class=\"trklst\">";
			# Cover ausgeben
			if (strlen($albumrecord["coverimg"])>1){
# 80*80 vom Cover anzeigen
			  $pfad = $htdocs;
				$image = $albumrecord["coverimg"];
				$imgpath = str_replace( $pfad, "", $imagedir );
        $teil_cover = explode(".", $image);
        $cover_a = array( $imgpath, '/', $teil_cover[0], '-t', '.', $teil_cover[1] );
        $cover_t = implode("", $cover_a);
        $cover_l = array( $pfad, $imgpath, '/',  $teil_cover[0], '-t', '.', $teil_cover[1] );
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

#  print "Hallo<br>";
#  print "$pfad<br>";
#  print "$image<br>";
#  print "$imgpath<br>";
#  print "$cover_t<br>";
#  print "$link_ct<br>";
				
			}
			else
			{
				$imgpath = "img/no-img-sm.gif";
				print "<a href=\"$imgpath\">";
				print "<img width=\"80\" src=\"".$imgpath."\" height=\"80\" border=\"0\">";
				print "</a>";
			}
			print "</td>";
			# Artist - Album Name
			print "<td class=\"trklst\">".$albumrecord["artist"]."</td>";
			print "<td class=\"trklst\"> - </td>";
			# Link zum Album
			$prefix = "browse.php?l0=thisalb&albid=";
			print "<td><a class=\"brslst\" href=\"".$prefix.$albumrecord["cddbid"]."\">"
			.$albumrecord["title"]."</a></td> ";

  #Album-Playlist-Link (by Merlot)
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
        ."&prAlbum=1" #don't use a new value for $playtp, it would interfere with cookie-settings
        ."&albid=".$albumrecord["cddbid"]
        ."&id=1"
        ."\">";
        print "<img border=\"0\" src=\"img/xspf.png\" width=\"40\" alt=\"Download\" title=\"$output134\"></a>";
        }
                        
			print "</td></tr>";
      }
      print "</table>";
      
  # hier die Titel
	  if (isset($albid)){ # albumid / level 3 specified?
        $qrystr = "SELECT * FROM tracks WHERE sourceid = '$albid' ORDER BY tracknb ";
		}


  }

  ### Playlist
  if (strcmp($l0, "playlist")==0){
    print "<h4>$output119</h4>";

print "<hr noshade>";
	# zurück Knopf
  if ($id <= 1)
    {
    # nichts machen
    }
  else
    {
    print "<br><table align='right' border='0' cellspacing='0' cellpadding='1'><tr><td class=\"menu".$sel."active\"><a class=\"menu\" href=\"javascript:history.go(-2)\">$output081</a></td></tr></table>";
    }

	if ($pl_id != 0)
		{
		if ($id != 0)
			{
			p_appendex ($pl_id, $title, $id, $link);
			#### insert a title into playlist
			}
		p_playlist ($output080, $output132, $bashplayer, $output133, $b_player, $xspf, $output134);
		print "<!-- --><a NAME='liste'></a><!-- -->";
		print "<hr noshade>";
		print "<table><tr>";
		
    print "<td><small>Playlist No. $pl_id - $title</small></td>";
		print "<td class=\"trklst\">&nbsp; &nbsp;<a href=\"gdtrid2mp3.php?playtp=pr"
				."&prPlaylist=$pl_id"
				."\">"."<img border=\"0\" src=\"img/download.png\" width=\"40\" alt=\"Download\" title=\"$output080\"></a></td>";

      if($bashplayer == "yes")
        {
        print "<td>&nbsp; &nbsp; &nbsp;<a href=\"p_play.php?l0=prPlaylist";
        print "&albid=$pl_id";
        print "&step=start";
        print "\">";
        print "<img border=\"0\" src=\"img/button_blue_play.png\" width=\"40\" alt=\"Download\" title=\"$output133 $b_player\"></a></td>";
        }

      if($xspf == "yes")
        {
        print "<td>&nbsp; &nbsp; &nbsp;<a href=\"gdtrid2xspf.php?playtp=pr"
        ."&prPlaylist=$pl_id" 
        ."\">";
        print "<img border=\"0\" src=\"img/xspf.png\" width=\"40\" alt=\"Download\" title=\"$output134\"></a></td>";
        }
				
		print "<td>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td>";

    if (strcmp($tool, "edit")==0)		
      print "<td><a href=\"browse.php?l0=$l0&pl_id=$pl_id&title=$title&tool=no#liste\"><img border=\"0\" src=\"img/edit.png\" alt=\"Edit\" title=\"bearbeiten\"></a></td>";
    else
      print "<td><a href=\"browse.php?l0=$l0&pl_id=$pl_id&title=$title&tool=edit#liste\"><img border=\"0\" src=\"img/edit.png\" alt=\"Edit\" title=\"bearbeiten\"></a></td>";
    
    print "</tr></table>";
		p_listen ($pl_id, $title, $PATH_ABSOLUTE_mp3, $output080, $output082, $tool, $output091, $output092, $xspf, $output134, $link, $mp3dir);
    }
	else
		{
		p_playlist ($output080, $output132, $bashplayer, $output133, $b_player, $xspf, $output134);
		}
	# zurück Knopf
  if ($id <= 1)
    {
    # nichts machen
    }
  else
    {
    print "<table align='right' border='0' cellspacing='0' cellpadding='1'><tr><td class=\"menu".$sel."active\"><a class=\"menu\" href=\"javascript:history.go(-2)\">$output081</a></td></tr></table><br>";
    }
  }

  ### single Tracks
  if (strcmp($l0, "s_tr")==0){
    print "<h4>$output123</h4>";

#$con = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
#$check = mysqli_query ( $con, "SELECT * FROM `strack_temp` LIMIT 0,1"); 
#if ($check){
#// query war oke und konnte ausgeführt werden
#$sql_befehl = mysqli_query ( $con, "DROP TABLE strack_temp");
#} 

	strack_temp();
#	$ergebnis = mysql_query( "INSERT INTO strack_temp SELECT * FROM tracks" );
  $con = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
  $ergebnis = mysqli_query( $con, "INSERT INTO strack_temp SELECT * FROM tracks WHERE sourceid='' OR tracknb=1 " );
  
  $ergebnis = mysqli_query( $con, "DELETE FROM strack_temp
                                        WHERE sourceid IN
                                        (SELECT cddbid
                                        FROM album)" );


    $tempquery = "SELECT * FROM strack_temp";
	$recset = mysqli_query ( $con, $tempquery);
    if (mysqli_affected_rows($con)>0){print("<p><small>".mysqli_affected_rows($con)." single tracks</small><br>");}

####################
	$ergebnis = mysqli_query( $con, "select artist, title, id from strack_temp" );

echo("<table><tr><td><small><b>Artist</b></small></td><td><small><b>Title</b></small></td><td>&nbsp;</td></tr>");
$a = 0;
$track = 1;
$evenln = 1;
while ( $datensatz = mysqli_fetch_row( $ergebnis ) )
    {
    foreach ( $datensatz as $feld )
		  {
		  switch ($a)
		  	{
			case 0:
				$artist = $feld;
				$a++;
			break;
			case 1:
				$title = $feld;
				$a++;
			break;
			case 2:
			    if ($evenln)
					{
					print "<tr class=\"lneven\">";
					}
				else
					{
      				print "<tr class=\"lnodd\">";
					}

				#print "<td class=\"trklst\">$track</td>";
				print "<td class=\"trklst\">$artist</td><td class=\"trklst\">$title</td>";
				$recset = mysqli_query( $con, "select mp3file from tracks where $feld = id");
				while ( $datastep = mysqli_fetch_row( $recset ) )
					{
					foreach ( $datastep as $field )
						{
						$httphost = $_SERVER["HTTP_HOST"];
						$l = strlen($PATH_ABSOLUTE_mp3) - 3;
						$mp3path = substr($PATH_ABSOLUTE_mp3,$l,3);
						print "<td class=\"trklst\"><a href=\"http://$httphost/music";
						print "$mp3path";
						print "/";
						print "$field\">";
						print "<img border=\"0\" src=\"img/dl.gif\" alt=\"Download\" title=\"$output080\"></a>";

						if($xspf == "yes")
			       {
			       print "&nbsp;&nbsp;&nbsp;";
			       print "<a href=\"gd2xspf.php?mp3path=$mp3path/$field&title=$title&artist=$artist&laenge=$laenge"
              ."\">";
             print "<img border=\"0\" src=\"img/xspf.png\" width=\"16\" alt=\"Download\" title=\"$output134\"></a>";
             }
						
						print "&nbsp;&nbsp;&nbsp;<a href=\"appendpl.php?id=".$feld."\">"
		     			."<img border=\"0\" src=\"img/onetopl.gif\" alt=\"Append to Playlist\" title=\"$output079\"></a>
						</td>";

      $erg_lyric = mysqli_query( $con ,"select lyrics from tracks where id = $feld && lyrics IS NOT NULL");
      $anz_lyric = mysqli_num_rows( $erg_lyric );
      if ($anz_lyric > 0)
        {
        print "<td>&nbsp; &nbsp; &nbsp;<a href=\"javascript:popUp1('lyric.php?id=$feld')\">";       
        print "<img border=\"0\" src=\"img/lyric_button.png\" alt=\"Lyric\" title=\"Lyric\"></a></td>";
        }    


            print "</tr>";

						}
					}

				#print "<img border=\"0\" src=\"img/dl.gif\" alt=\"Download\"></a></td></tr>";
				mysqli_free_result($recset);
				$a = 0;
				$track++;
				$evenln = 1-$evenln;
			break;
			}
		  }
    }
echo("</table>");
mysqli_free_result($ergebnis);
$ergebnis = mysqli_query( $con, "DROP TABLE strack_temp" );


  }

}
##########################################################

  print "<!-- --><a NAME='liste'></a><!-- -->";
if (strcmp($l0, "thisalb")!=0){
if(strlen($albid)>0){
  	$tempquery = "SELECT * FROM album WHERE cddbid LIKE '%$albid'";
    $recset = mysqli_query ( $link, $tempquery);
	$a = 0;
	$anz_reihen = mysqli_affected_rows( $link );
if (mysqli_affected_rows( $link )>0){print "<hr noshade>";}else{print "<p>&nbsp;</p>";}
#	print "<hr>";
      print "<table borders=\"0\">";
      while($row = mysqli_fetch_array($recset)) {
		$albumrecord = $row;
		$a++;
		print "<tr>";
		print "<td class=\"trklst\">";
			# Cover ausgeben
			if (strlen($albumrecord["coverimg"])>1){
			  $pfad = $htdocs; 
			  $image = $albumrecord["coverimg"];
        $imgpath = str_replace( $pfad, "", $imagedir );

# 80*80 vom Cover anzeigen
  $teil_cover = explode(".", $image);
  $cover_a = array( $imgpath, '/', $teil_cover[0], '-t', '.', $teil_cover[1] );
  $cover_t = implode("", $cover_a);
  $cover_l = array( $pfad, '/', $imgpath, '/', $teil_cover[0], '-t', '.', $teil_cover[1] );
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
    
    #print "$image<br>";
    #print "$pfad<br>";
    #print "$imagedir<br>";
    #print "$imgpath<br>";
    #print "$cover_t<br>";
    #print "$link_ct<br>";
    #print "$imgpath_pic<br>";				
				
			}
			else
			{
				$imgpath = "img/no-img-sm.gif";
				print "<a href=\"$imgpath\">";
				print "<img width=\"40\" src=\"".$imgpath."\" height=\"40\" border=\"0\">";
				print "</a>";
			}
			print "</td>";
			# Artist - Album Name
			print "<td class=\"trklst\">".$albumrecord["artist"]."</td>";
			print "<td class=\"trklst\"> - </td>";
			# Link zum Album
			$prefix = "browse.php?l0=thisalb&albid=";
			print "<td class=\"trklst\">"
			.$albumrecord["title"]."</td> ";

  #Album-Playlist-Link (by Merlot)
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
        ."&prAlbum=1" #don't use a new value for $playtp, it would interfere with cookie-settings
        ."&albid=".$albumrecord["cddbid"]
        ."&id=1"
        ."\">";
        print "<img border=\"0\" src=\"img/xspf.png\" width=\"40\" alt=\"Download\" title=\"$output134\"></a>";
        }
      
			print "</td></tr>";
      }
      print "</table>";
}}
  #####################################################################

if(strlen($qrystr)>0){ # do query and show tracks
  if (strlen($rating)==0){$rating=0;}
  ### query tracks
  $recset = mysqli_query ( $link , $qrystr);

  ### display the tracks
if(strlen($albid)<=0){
  if (mysqli_affected_rows( $link )>0){print "<hr noshade>";}else{print "<p>&nbsp;</p>";}
}
  print("<p><small><b>".mysqli_affected_rows( $link )." matching tracks</b></small></p>");
  print("<table border=\"0\" cellspacing=\"0\">\n");
  $evenln = 1;
  while($row = mysqli_fetch_array($recset)) {
    if ($evenln){
      print "<tr class=\"lneven\">";
	}
	else{
      print "<tr class=\"lnodd\">";
	}
    show_trackrow($row,
	          $showartist, $showtitle, $showgenre, $showlang, $showyear, $showrating,
			  $showbpm, $showsource, $showcreated, $showmodified, $showlength,
			  $showedit, $showdownload, $playtp, $showtoplaylist, $output079, $output080, $xspf, $output134, $mp3dir);
    $evenln = 1-$evenln; #toggle $evenln

	print "</tr>\n";
  }
  print("</table>\n");
  
  mysqli_free_result($recset);
  mysqli_close( $link );

    if (strcmp($l0, "thisalb")==0 OR strcmp($l0, "al-gn")==0 OR strcmp($l0, "al-ti")==0 OR strcmp($l0, "al-ar")==0){
      print "<table><tr><td>&nbsp;</td></tr><tr><td class=\"menu".$sel."active\"><a class=\"menu\" href=\"javascript:history.go(-1)\">$output081</a></td></tr></table>";

    }

}

?>

<hr noshade>

<p>

<?php
print_footer ("browse");
?>

<?php
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
	print "<img border=\"0\" src=\"img/edit.png\" alt=\"Edit\" title=\"$output149\"></td>";

  print "</tr>";
}

#######################################################################################

?>