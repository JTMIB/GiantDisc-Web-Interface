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

# verwendete Variablen
$anz_reihen_stern = ''; // Initialisierung



print_header ("settings", "", "$cgi_dir", "$char_set", $output135, $output136, $output137, $output138);


  $link =  mysqli_connect( MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB );
  if ( ! $link )
      die( "Keine Verbindung zu MySQL" );

## Variablen aus Browser-Zeile
$cddbid=$_GET['cddbid'];
      
$recset = mysqli_query ( $link, "SELECT artist, title, composer, cddbid, coverimg, covertxt, modified, genre FROM album WHERE cddbid = '$cddbid' ");


while($row = mysqli_fetch_object($recset))
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
print "<p> <br> </p>";

print "<table border=0>";
print "<tr><td>";
print "<table border=0>";
print "<tr><td align=\"right\">$output032:</td><td> </td><td>$artist</td></tr>";
print "<tr><td align=\"right\">$output095:</td><td> </td><td>$title</td></tr>";
print "<tr><td align=\"right\">$output096:</td><td> </td><td>$composer</td></tr>";
print "<tr><td align=\"right\">$output097:</td><td> </td><td>$cddbid</td></tr>";
print "<tr><td align=\"right\">$output098:</td><td> </td><td>$covertxt</td></tr>";
print "<tr><td align=\"right\">$output099:</td><td> </td><td>$modified</td></tr>";
print "<tr><td align=\"right\">$output100:</td><td> </td><td>";

if ($genre == "")
    {
    print "$genre";    
    }
else
    {
    $recset = mysqli_query ( $link, "SELECT genre FROM genre WHERE id = '$genre' ");
    #$anz_row = mysql_num_rows( $recset );
    #print "$anz_row<br>";
    while($row = mysqli_fetch_object($recset))
        {
        $show_genre = $row->genre;
        }    
    print "$show_genre";
    }
print "</td></tr>";
print "</table>";
print "</td><td> </td><td>";

if ($coverimg == "")
    {
    print "<td valign=\"center\" align=\"right\">";
    print "<form enctype=\"multipart/form-data\" action=\"cover_upload.php\" method=\"post\">";

    print "<input name=\"datei\" size=\"60\" type=\"file\" accept=\".jpg, .jpeg, .gif\">";
    print "<input type=hidden name=cddbid value='$cddbid' size=8 maxlength=8>";
    print "<br>";
    print "<input type=\"submit\" value=\"&nbsp;&nbsp;$output101&nbsp;&nbsp;\">";

    print "</form>";    
    }
else
    {
    print "<td valign=\"top\">";
    print "<img src=\"02/$coverimg\" width=\"200\" border=\"0\" align=\"right\"></td>";
    
    print "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    print "<small>$output107</small><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"javascript:
    if (confirm('$output106')){location.href='cover_delete.php?cddbid=$cddbid&artist=$artist&title=$title'}\"><img border=\"0\" src=\"img/drop.png\" width=\"45\" title=\"$output107\"></a>";

print "</td>";
print "<td> &nbsp; &nbsp; &nbsp;</td>";
print "<td><small>$output108</small><br><a href=\"cont_gdsel.php?showartist=on&artist=&showtitle=on&title=&showgenre=on&genre=&showrating=on&rating=&playtp=df&CDid=$cddbid\"><img border=\"0\" src=\"img/edit.png\" width=\"45\" title=\"$output108\"></a>";
print "</td>";

print "<td> &nbsp; &nbsp; &nbsp;</td>";

print "<td>";
$abfrage_stern = mysqli_query( $link,"SELECT evaluation FROM album_fav WHERE cddbid LIKE '%$cddbid%'" );

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
#    print "$feld";
    print "<small>$output127</small><br>";   
?>
<!-- 1. Stern -->
<?php
if ($feld >= 1)
print "<span class=\"star active\" style=\"width: 250px; margin: 0px;\"";
else
print "<span class=\"star\" style=\"width: 250px; margin: 0px;\"";
?>
onmouseover="removeActive(this);" onmouseout="resetActive();">
<?php print"<a href=\"fav_rat.php?cddbid=$cddbid&evaluation=1&action=update\"><span class=\"spacer\">&nbsp;</span></a>";?>
 <!-- 2. Stern -->
<?php
if ($feld >= 2)
 print "<span class=\"star active\" style=\"width: 200px;\">";
else
 print "<span class=\"star\" style=\"width: 200px;\">";
?>
 <?php print"<a href=\"fav_rat.php?cddbid=$cddbid&evaluation=2&action=update\"><span class=\"spacer\">&nbsp;</span></a>";?>
  <!-- 3. Stern -->
<?php
if ($feld >= 3)
  print "<span class=\"star active\" style=\"width: 150px;\">";
else
  print "<span class=\"star\" style=\"width: 150px;\">";
?>
  <?php print"<a href=\"fav_rat.php?cddbid=$cddbid&evaluation=3&action=update\"><span class=\"spacer\">&nbsp;</span></a>";?>
   <!-- 4. Stern -->
<?php
if ($feld >= 4)
   print "<span class=\"star active\" style=\"width: 100px;\">";
else
   print "<span class=\"star\" style=\"width: 100px;\">";
?>
   <?php print"<a href=\"fav_rat.php?cddbid=$cddbid&evaluation=4&action=update\"><span class=\"spacer\">&nbsp;</span></a>";?>
    <!-- 5. Stern -->
<?php
if ($feld >= 5)
    print "<span class=\"star active\" style=\"width: 50px;\">";
else
    print "<span class=\"star\" style=\"width: 50px;\">";
?>
    <?php print"<a href=\"fav_rat.php?cddbid=$cddbid&evaluation=5&action=update\"><span class=\"spacer\">&nbsp;</span></a>";?>
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


    }
else
    {

    print "<small>$output127</small><br>";
?>
<!-- 1. Stern -->
<span class="star" style="width: 250px; margin: 0px;"
onmouseover="removeActive(this);" onmouseout="resetActive();">
<?php print"<a href=\"fav_rat.php?cddbid=$cddbid&evaluation=1&action=set\"><span class=\"spacer\">&nbsp;</span></a>";?>
 <!-- 2. Stern -->
 <span class="star" style="width: 200px;">
 <?php print"<a href=\"fav_rat.php?cddbid=$cddbid&evaluation=2&action=set\"><span class=\"spacer\">&nbsp;</span></a>";?>
  <!-- 3. Stern -->
  <span class="star" style="width: 150px;">
  <?php print"<a href=\"fav_rat.php?cddbid=$cddbid&evaluation=3&action=set\"><span class=\"spacer\">&nbsp;</span></a>";?>
   <!-- 4. Stern -->
   <span class="star" style="width: 100px;">
   <?php print"<a href=\"fav_rat.php?cddbid=$cddbid&evaluation=4&action=set\"><span class=\"spacer\">&nbsp;</span></a>";?>
    <!-- 5. Stern -->
    <span class="star" style="width: 50px;">
    <?php print"<a href=\"fav_rat.php?cddbid=$cddbid&evaluation=5&action=set\"><span class=\"spacer\">&nbsp;</span></a>";?>
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

    }

print "</td>";
    }
if ( $anz_reihen_stern >= 1 )
    {
    print "<td>";
    print "<a href=\"fav_rat.php?cddbid=$cddbid&evaluation=0&action=delete\"><img border=\"0\" src=\"img/drop.png\" width=\"45\" title=\"$output082\"></a>";
    print "</td>";
    }


print "</tr>";   
print "</table>";

  mysqli_free_result($recset);
  mysqli_close( $link );

?>


<hr noshade>



</body>
</html>