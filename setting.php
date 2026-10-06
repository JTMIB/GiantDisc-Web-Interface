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

## Variablen aus Browser-Zeile
$backgroundcolor=$_GET['backgroundcolor'];

$playtp = set_get_cookie($setplaytp, "playtp", "dl", $HTTP_COOKIE_VARS);

# BODY aufspueren und background-color neu setzen
    if ( isset($backgroundcolor) ) {
#      print "$backgroundcolor ist gesetzt<br>";

      copy("gdweb.css","gdweb.tmp");      
      $zaehler=0;
      $myfile = fopen("gdweb.css", "w") or die("Kann Datei nicht öffnen!");
      $datei_lesen="gdweb.tmp";
      $dh = fopen($datei_lesen, "r");
      while (!feof($dh)) {
        $zeile = fgets($dh);

        if ($zaehler == 1) {
          $zeile = "	background-color: $backgroundcolor ;\n";
#          print "$zeile\n";          
          $zaehler++;    
        }
        if (str_contains($zeile, 'BODY {')) {
#            print "Die Zeichenkette 'BODY {' wurde in der Zeichenkette gefunden<br>";
            fwrite($myfile, "$zeile");
            $zaehler++;
        }
        if ($zaehler == 0 || $zaehler == 2){
          fwrite($myfile, "$zeile");
        }
        
      }
      fclose($dh);
      fclose($myfile);  
        
    } 
# Ende - BODY aufpueren und background-color neu setzen

print "<!DOCTYPE html
	PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">
  <html>
  <head>
  <title>GiantDisc Web Interface: $current</title>";
print "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=$char_set\">";    
print "<meta name=\"author\" content=\"Juergen Thoens\">
  <meta name=\"pragma\" content=\"no-cache\">
  <meta http-equiv=\"cache-control\" content=\"no-cache\">
  <meta http-equiv=\"expires\" content=\"100\">
  <meta name=\"revisit-after\" content=\"1\">
  <link href=\"gdweb.css\" rel=\"stylesheet\" type=\"text/css\">
  <link rel=\"SHORTCUT ICON\" href=\"img/gd16.ico\">
  <link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\">
  <link href=\"lightbox.css\" rel=\"stylesheet\">";
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

<script src="jquery-1.9.1.min.js"></script>
<script src="scroll-top.js"></script>
<!-- Initialisieren -->
<script type="text/javascript">
   $(document).ready(function(){
      $('.top').UItoTop();
   });
</script>

<?php
# bevorzugtes Format
  $myfile = fopen("setting.inc", "r") or die("Kann Datei nicht öffnen!");
  while(!feof($myfile)) {
    $daten = fgets($myfile);
      if (strncmp($daten, '$prefc', 6) === 0) {
#          print "$daten<br>";

#          if(strstr($daten, "mp3") != false) { 
#                                             echo 'MP3<br>';
#                                             $prefc = "mp3";
#                                             }
                                             
      }
  }
  fclose($myfile);

# Sprache - language
  $myfile = fopen("setting.inc", "r") or die("Kann Datei nicht öffnen!");
  while(!feof($myfile)) {
    $daten = fgets($myfile);
      if (strncmp($daten, '$language', 9) === 0) {
#          print "$daten<br>";
                                           
      }
  }
  fclose($myfile);

# MP3-Bitrate-Qualität
  $myfile = fopen("setting.inc", "r") or die("Kann Datei nicht öffnen!");
  while(!feof($myfile)) {
    $daten = fgets($myfile);
      if (strncmp($daten, '$mp3_Q', 6) === 0) {
#          print "$daten<br>";
                                           
      }
  }
  fclose($myfile);

# OGG-Bitrate-Qualität
  $myfile = fopen("setting.inc", "r") or die("Kann Datei nicht öffnen!");
  while(!feof($myfile)) {
    $daten = fgets($myfile);
      if (strncmp($daten, '$ogg_Q', 6) === 0) {
#          print "$daten<br>";
                                           
      }
  }
  fclose($myfile);

# FLAC-Compression
  $myfile = fopen("setting.inc", "r") or die("Kann Datei nicht öffnen!");
  while(!feof($myfile)) {
    $daten = fgets($myfile);
      if (strncmp($daten, '$flac_Q', 7) === 0) {
#          print "$daten<br>";
                                           
      }
  }
  fclose($myfile);

# OPUS-Bitrate
  $myfile = fopen("setting.inc", "r") or die("Kann Datei nicht öffnen!");
  while(!feof($myfile)) {
    $daten = fgets($myfile);
      if (strncmp($daten, '$opus_Q', 7) === 0) {
#          print "$daten<br>";
                                           
      }
  }
  fclose($myfile);
  

?>

<?php
  print "<table><tr>";
  print "<td>";
  print "<div class='maintitle'>GiantDisc&nbsp;Web&nbsp;Interface</div>";
  print "<div class='$current'>";
  print "</td>";

	print "<td width=\"100%\" height=\"44\" align=\"right\">
	<img src=\"img/M4A.png\" height=\"75\" border=\"0\" align=\"right\">
	<img src=\"img/flac.gif\" height=\"75\" border=\"0\" align=\"right\">
	<img src=\"img/opus.png\" width=\"75\" border=\"0\" align=\"right\">
	<img src=\"img/ogg_vorbis.png\" height=\"75\" border=\"0\" align=\"right\">
	<img src=\"img/mp3.png\" height=\"75\" border=\"0\" align=\"right\">";
	print "</td></tr></table>";

  print "<table border='0' cellspacing='0' cellpadding='1'><tr>";
  print "<td class=\"menu".$sel."active\"><b>$output139</b></td>";
  print "</tr></table>";    
?>

<p> </p>
<form action="setting_write.php">
  <fieldset>
<?php
print "<table><tr>";
  
# Sprache
  print "<td valign=top>";
  print "<p><b>$output143</b></p>";
  
  print "<input type=\"radio\" id=\"de\" name=\"language\" value=\"de\" ";
  if ($language == "de")  print "checked=\"checked\"";
  print ">";
  print "<label for=\"de\"> <img src=\"img/language_de.png\" height=\"16\" width=\"24\" alt=\"deutsch\"> $output144</label><br>";
  
  print "<input type=\"radio\" id=\"en\" name=\"language\" value=\"en\" ";
  if ($language == "en")  print "checked=\"checked\"";
  print ">";
  print "<label for=\"en\"> <img src=\"img/language_en.png\" height=\"16\" width=\"24\" alt=\"english\"> $output145</label><br>";  
  print "</td>";

  print "<td>&nbsp; &nbsp; &nbsp;</td>";

# Browser Zeichensatz  
  print "<td valign=top>";
  print "<p><b>$output146</b></p>";

  print "<input type=\"radio\" id=\"CharSet\" name=\"CharSet\" value=\"1\" ";
  if ($char_set == "iso-8859-1")  print "checked=\"checked\"";
  print ">";
  print "<label for=\"CharSet\"> iso-8859-1</label><br>";
  
  print "<input type=\"radio\" id=\"CharSet\" name=\"CharSet\" value=\"2\" ";
  if ($char_set == "UTF-8")  print "checked=\"checked\"";
  print ">";
  print "<label for=\"CharSet\"> UTF-8</label><br>";
  
  print "</td>";  
print "</tr></table>";
?>  
  </fieldset>
  
  <fieldset>
<?php  
# beforzugtes Format
  print "<p><b>$output140</b></p>";
  
  print "<table width=\"800\">";
  print "<tr><td><input type=\"radio\" id=\"mp3\" name=\"prefc\" value=\"mp3\" ";
  if ($prefc == "mp3")  print "checked=\"checked\"";
  print ">";
  print "<label for=\"mp3\"> MP3</label><br></td>";
   
  print "<td><input type=\"radio\" id=\"ogg\" name=\"prefc\" value=\"ogg\" ";
  if ($prefc == "ogg")  print "checked=\"checked\"";
  print ">";
  print "<label for=\"ogg\"> OGG</label><br></td>";
  
  print "<td><input type=\"radio\" id=\"flac\" name=\"prefc\" value=\"flac\" ";
  if ($prefc == "flac")  print "checked=\"checked\"";
  print ">";
  print "<label for=\"flac\"> FLAC</label><br></td>";
    
  print "<td><input type=\"radio\" id=\"opus\" name=\"prefc\" value=\"opus\" ";
  if ($prefc == "opus")  print "checked=\"checked\"";
  print ">";
  print "<label for=\"opus\"> OPUS</label><br></td>";

  print "<td><input type=\"radio\" id=\"m4a\" name=\"prefc\" value=\"m4a\" ";
  if ($prefc == "m4a")  print "checked=\"checked\"";
  print ">";
  print "<label for=\"m4a\"> M4A</label><br></td></tr>";
    
  print "<tr><td> </td><td></td><td></td><td></td></tr>";

# Qualität
  print "<tr>";
# Bitrate MP3  
  print "<td valign=top><input type=\"radio\" id=\"mp3_Q\" name=\"mp3_Q\" value=\"1\" ";
  if ($mp3_Q == "1")  print "checked=\"checked\"";
  print ">";
  print "<label for=\"mp3_Q\"> 64 Kbit/s</label><br>";

  print "<input type=\"radio\" id=\"mp3_Q\" name=\"mp3_Q\" value=\"2\" ";
  if ($mp3_Q == "2")  print "checked=\"checked\"";
  print ">";
  print "<label for=\"mp3_Q\"> 96 Kbit/s</label><br>";

  print "<input type=\"radio\" id=\"mp3_Q\" name=\"mp3_Q\" value=\"3\" ";
  if ($mp3_Q == "3")  print "checked=\"checked\"";
  print ">";
  print "<label for=\"mp3_Q\"> 112 Kbit/s</label><br>";

  print "<input type=\"radio\" id=\"mp3_Q\" name=\"mp3_Q\" value=\"4\" ";
  if ($mp3_Q == "4")  print "checked=\"checked\"";
  print ">";
  print "<label for=\"mp3_Q\"> 128 Kbit/s</label><br>";

  print "<input type=\"radio\" id=\"mp3_Q\" name=\"mp3_Q\" value=\"5\" ";
  if ($mp3_Q == "5")  print "checked=\"checked\"";
  print ">";
  print "<label for=\"mp3_Q\"> 160 Kbit/s</label><br>";

  print "<input type=\"radio\" id=\"mp3_Q\" name=\"mp3_Q\" value=\"6\" ";
  if ($mp3_Q == "6")  print "checked=\"checked\"";
  print ">";
  print "<label for=\"mp3_Q\"> 192 Kbit/s</label><br>";

  print "<input type=\"radio\" id=\"mp3_Q\" name=\"mp3_Q\" value=\"7\" ";
  if ($mp3_Q == "7")  print "checked=\"checked\"";
  print ">";
  print "<label for=\"mp3_Q\"> 256 Kbit/s</label><br>";

  print "<input type=\"radio\" id=\"mp3_Q\" name=\"mp3_Q\" value=\"8\" ";
  if ($mp3_Q == "8")  print "checked=\"checked\"";
  print ">";
  print "<label for=\"mp3_Q\"> 320 Kbit/s</label><br></td>";

# Bitrate/Quality OGG 
  print "<td valign=top><input type=\"radio\" id=\"ogg_Q\" name=\"ogg_Q\" value=\"1\" ";
  if ($ogg_Q == "1")  print "checked=\"checked\"";
  print ">";
  print "<label for=\"ogg_Q\"> quality 1</label><br>";  

  print "<input type=\"radio\" id=\ogg_Q\" name=\"ogg_Q\" value=\"2\" ";
  if ($ogg_Q == "2")  print "checked=\"checked\"";
  print ">";
  print "<label for=\"ogg_Q\"> quality 2</label><br>";

  print "<input type=\"radio\" id=\ogg_Q\" name=\"ogg_Q\" value=\"3\" ";
  if ($ogg_Q == "3")  print "checked=\"checked\"";
  print ">";
  print "<label for=\"ogg_Q\"> quality 3</label><br>";

  print "<input type=\"radio\" id=\ogg_Q\" name=\"ogg_Q\" value=\"4\" ";
  if ($ogg_Q == "4")  print "checked=\"checked\"";
  print ">";
  print "<label for=\"ogg_Q\"> quality 4</label><br>";

  print "<input type=\"radio\" id=\ogg_Q\" name=\"ogg_Q\" value=\"5\" ";
  if ($ogg_Q == "5")  print "checked=\"checked\"";
  print ">";
  print "<label for=\"ogg_Q\"> quality 5</label><br>";

  print "<input type=\"radio\" id=\ogg_Q\" name=\"ogg_Q\" value=\"6\" ";
  if ($ogg_Q == "6")  print "checked=\"checked\"";
  print ">";
  print "<label for=\"ogg_Q\"> quality 6</label><br>";

  print "<input type=\"radio\" id=\ogg_Q\" name=\"ogg_Q\" value=\"7\" ";
  if ($ogg_Q == "7")  print "checked=\"checked\"";
  print ">";
  print "<label for=\"ogg_Q\"> quality 7</label><br>";

  print "<input type=\"radio\" id=\ogg_Q\" name=\"ogg_Q\" value=\"8\" ";
  if ($ogg_Q == "8")  print "checked=\"checked\"";
  print ">";
  print "<label for=\"ogg_Q\"> quality 8</label><br>";

  print "<input type=\"radio\" id=\ogg_Q\" name=\"ogg_Q\" value=\"9\" ";
  if ($ogg_Q == "9")  print "checked=\"checked\"";
  print ">";
  print "<label for=\"ogg_Q\"> quality 9</label><br>";

  print "<input type=\"radio\" id=\ogg_Q\" name=\"ogg_Q\" value=\"10\" ";
  if ($ogg_Q == "10")  print "checked=\"checked\"";
  print ">";
  print "<label for=\"ogg_Q\"> quality 10</label><br></td>";

# Compression FLAC 
  print "<td valign=top><input type=\"radio\" id=\"flac_Q\" name=\"flac_Q\" value=\"1\" ";
  if ($flac_Q == "1")  print "checked=\"checked\"";
  print ">";
  print "<label for=\"flac_Q\"> compression 1</label><br>";  

  print "<input type=\"radio\" id=\"flac_Q\" name=\"flac_Q\" value=\"2\" ";
  if ($flac_Q == "2")  print "checked=\"checked\"";
  print ">";
  print "<label for=\"flac_Q\"> compression 2</label><br>";  

  print "<input type=\"radio\" id=\"flac_Q\" name=\"flac_Q\" value=\"3\" ";
  if ($flac_Q == "3")  print "checked=\"checked\"";
  print ">";
  print "<label for=\"flac_Q\"> compression 3</label><br>";  

  print "<input type=\"radio\" id=\"flac_Q\" name=\"flac_Q\" value=\"4\" ";
  if ($flac_Q == "4")  print "checked=\"checked\"";
  print ">";
  print "<label for=\"flac_Q\"> compression 4</label><br>";

  print "<input type=\"radio\" id=\"flac_Q\" name=\"flac_Q\" value=\"5\" ";
  if ($flac_Q == "5")  print "checked=\"checked\"";
  print ">";
  print "<label for=\"flac_Q\"> compression 5</label><br>";

  print "<input type=\"radio\" id=\"flac_Q\" name=\"flac_Q\" value=\"6\" ";
  if ($flac_Q == "6")  print "checked=\"checked\"";
  print ">";
  print "<label for=\"flac_Q\"> compression 6</label><br>";

  print "<input type=\"radio\" id=\"flac_Q\" name=\"flac_Q\" value=\"7\" ";
  if ($flac_Q == "7")  print "checked=\"checked\"";
  print ">";
  print "<label for=\"flac_Q\"> compression 7</label><br>";

  print "<input type=\"radio\" id=\"flac_Q\" name=\"flac_Q\" value=\"8\" ";
  if ($flac_Q == "8")  print "checked=\"checked\"";
  print ">";
  print "<label for=\"flac_Q\"> compression 8</label><br></td>";

# Bitrate OPUS 
  print "<td valign=top><input type=\"radio\" id=\"opus_Q\" name=\"opus_Q\" value=\"1\" ";
  if ($opus_Q == "1")  print "checked=\"checked\"";
  print ">";
  print "<label for=\"flac_Q\"> 96 Kbit/s</label><br>"; 

  print "<input type=\"radio\" id=\"opus_Q\" name=\"opus_Q\" value=\"2\" ";
  if ($opus_Q == "2")  print "checked=\"checked\"";
  print ">";
  print "<label for=\"flac_Q\"> 128 Kbit/s</label><br>";

  print "<input type=\"radio\" id=\"opus_Q\" name=\"opus_Q\" value=\"3\" ";
  if ($opus_Q == "3")  print "checked=\"checked\"";
  print ">";
  print "<label for=\"flac_Q\"> 256 Kbit/s</label><br>";

  print "<input type=\"radio\" id=\"opus_Q\" name=\"opus_Q\" value=\"4\" ";
  if ($opus_Q == "4")  print "checked=\"checked\"";
  print ">";
  print "<label for=\"flac_Q\"> 320 Kbit/s</label><br>";
  
  print "<input type=\"radio\" id=\"opus_Q\" name=\"opus_Q\" value=\"5\" ";
  if ($opus_Q == "5")  print "checked=\"checked\"";
  print ">";
  print "<label for=\"flac_Q\"> 512 Kbit/s</label><br></td>";  

# Bitrate M4A
  print "<td valign=top><input type=\"radio\" id=\"m4a_Q\" name=\"m4a_Q\" value=\"1\" ";
  if ($m4a_Q == "1")  print "checked=\"checked\"";
  print ">";
  print "<label for=\"m4a_Q\"> 96 Kbit/s</label><br>";

  print "<input type=\"radio\" id=\"m4a_Q\" name=\"m4a_Q\" value=\"2\" ";
  if ($m4a_Q == "2")  print "checked=\"checked\"";
  print ">";
  print "<label for=\"m4a_Q\"> 128 Kbit/s</label><br>";

  print "<input type=\"radio\" id=\"m4a_Q\" name=\"m4a_Q\" value=\"3\" ";
  if ($m4a_Q == "3")  print "checked=\"checked\"";
  print ">";
  print "<label for=\"m4a_Q\"> 192 Kbit/s</label><br>";
  
  print "<input type=\"radio\" id=\"m4a_Q\" name=\"m4a_Q\" value=\"4\" ";
  if ($m4a_Q == "4")  print "checked=\"checked\"";
  print ">";
  print "<label for=\"m4a_Q\"> 256 Kbit/s</label><br>";

  print "<input type=\"radio\" id=\"m4a_Q\" name=\"m4a_Q\" value=\"5\" ";
  if ($m4a_Q == "5")  print "checked=\"checked\"";
  print ">";
  print "<label for=\"m4a_Q\"> 320 Kbit/s</label><br>";

  print "<input type=\"radio\" id=\"m4a_Q\" name=\"m4a_Q\" value=\"6\" ";
  if ($m4a_Q == "6")  print "checked=\"checked\"";
  print ">";
  print "<label for=\"m4a_Q\"> alac</label><br>";
  
print "</td>";  
  print "</tr>";
  print "</table>"; 
?>      
  </fieldset>
  
  <fieldset>
<?php
# CD-Laufwerk vorhanden
  print "<table><tr>";
  
  print "<td valign=top>";
  print "<p><b>$output147</b></p>";
  
  print "<input type=\"radio\" id=\"yes\" name=\"read_CD\" value=\"yes\" ";
  if ($read_CD == "yes")  print "checked=\"checked\"";
  print ">";
  print "<label for=\"yes\"> $output021</label>&nbsp; &nbsp; &nbsp;";
  
  print "<input type=\"radio\" id=\"no\" name=\"read_CD\" value=\"no\" ";
  if ($read_CD == "no")  print "checked=\"checked\"";
  print ">";
  print "<label for=\"no\"> $output022</label><br>";  
  print "</td>";
  print "</tr>";

if ($read_CD == "yes")
  {
  print "<tr>";
  print "<td valign=top>";
  print "<p><br><b>$output148</b></p>";
  
  print "<input type=\"radio\" id=\"cdrom\" name=\"dev_cd\" value=\"/dev/cdrom\" ";
  if ($dev_cd == "/dev/cdrom")  print "checked=\"checked\"";
  print ">";
  print "<label for=\"cdrom\"> /dev/cdrom</label><br>";  

  print "<input type=\"radio\" id=\"cdrom\" name=\"dev_cd\" value=\"/dev/sr0\" ";
  if ($dev_cd == "/dev/sr0")  print "checked=\"checked\"";
  print ">";
  print "<label for=\"cdrom\"> /dev/sr0</label><br>";  

  print "<input type=\"radio\" id=\"cdrom\" name=\"dev_cd\" value=\"/dev/sr1\" ";
  if ($dev_cd == "/dev/sr1")  print "checked=\"checked\"";
  print ">";
  print "<label for=\"cdrom\"> /dev/sr1</label><br>";  

#  print "<input type=\"radio\" id=\"cdrom\" name=\"dev_cd\" value=\"/dev/sr2\" ";
#  if ($dev_cd == "/dev/sr2")  print "checked=\"checked\"";
#  print ">";
#  print "<label for=\"cdrom\"> /dev/sr2</label><br>";  

  print "</td>";  
  print "</tr>";
  }  
  
  print "</table>"; 
?>  
  </fieldset>
    
  <p> </p>
<?php
    print "&nbsp;&nbsp;&nbsp;<input type=\"submit\" name=\"submitted\" value=\"$output142\">&nbsp; &nbsp;";
    print "<input type=\"submit\" name=\"submitted\" value=\"$output141\">";
?>   
</form>

<hr noshade>
<a NAME='farben'></a>
  <fieldset>
  <table>
    <tr>
    <p><b>Hintergrundfarbe</b></p>
    </tr>
    <tr>
     <td style="background-color:aliceblue"><a href="setting.php?backgroundcolor=aliceblue#farben" style="color:#000000; text-decoration:none;">aliceblue</a></td> 
     <td style="background-color:antiquewhite"><a href="setting.php?backgroundcolor=antiquewhite#farben" style="color:#000000; text-decoration:none;">antiquewhite</a></td>
     <td style="background-color:aqua"><a href="setting.php?backgroundcolor=aqua#farben" style="color:#000000; text-decoration:none;">aqua</a></td>
     <td style="background-color:aquamarine"><a href="setting.php?backgroundcolor=aquamarine#farben" style="color:#000000; text-decoration:none;">aquamarine</a></td>
     <td style="background-color:azure"><a href="setting.php?backgroundcolor=azure#farben" style="color:#000000; text-decoration:none;">azure</a></td>
     <td style="background-color:beige"><a href="setting.php?backgroundcolor=beige#farben" style="color:#000000; text-decoration:none;">beige</a></td>
     <td style="background-color:bisque"><a href="setting.php?backgroundcolor=bisque#farben" style="color:#000000; text-decoration:none;">bisque</a></td>
     <td style="background-color:black"><a href="setting.php?backgroundcolor=black#farben" style="color:#FFFFFF; text-decoration:none;">black</a></td>
    </tr><tr> 
     <td style="background-color:blanchedalmond"><a href="setting.php?backgroundcolor=blanchedalmond#farben" style="color:#000000; text-decoration:none;">blanchedalmond</a></td>
     <td style="background-color:blue"><a href="setting.php?backgroundcolor=blue#farben" style="color:#FFFFFF; text-decoration:none;">blue</a></td>
     <td style="background-color:blueviolet"><a href="setting.php?backgroundcolor=blueviolet#farben" style="color:#FFFFFF; text-decoration:none;">blueviolet</a></td>
     <td style="background-color:brown"><a href="setting.php?backgroundcolor=brown#farben" style="color:#FFFFFF; text-decoration:none;">brown</a></td>
     <td style="background-color:burlywood"><a href="setting.php?backgroundcolor=burlywood#farben" style="color:#FFFFFF; text-decoration:none;">burlywood</a></td>
     <td style="background-color:cadetblue"><a href="setting.php?backgroundcolor=cadetblue#farben" style="color:#FFFFFF; text-decoration:none;">cadetblue</a></td>
     <td style="background-color:chartreuse"><a href="setting.php?backgroundcolor=chartreuse#farben" style="color:#000000; text-decoration:none;">chartreuse</a></td>
     <td style="background-color:chocolate"><a href="setting.php?backgroundcolor=chocolate#farben" style="color:#FFFFFF; text-decoration:none;">chocolate</a></td>
    </tr><tr>
     <td style="background-color:coral"><a href="setting.php?backgroundcolor=coral#farben" style="color:#FFFFFF; text-decoration:none;">coral</a></td>
     <td style="background-color:cornflowerblue"><a href="setting.php?backgroundcolor=cornflowerblue#farben" style="color:#FFFFFF; text-decoration:none;">cornflowerblue</a></td>
     <td style="background-color:cornsilk"><a href="setting.php?backgroundcolor=cornsilk#farben" style="color:#000000; text-decoration:none;">cornsilk</a></td>
     <td style="background-color:crimson"><a href="setting.php?backgroundcolor=crimson#farben" style="color:#FFFFFF; text-decoration:none;">crimson</a></td>
     <td style="background-color:cyan"><a href="setting.php?backgroundcolor=cyan#farben" style="color:#000000; text-decoration:none;">cyan</a></td>     
     <td style="background-color:darkblue"><a href="setting.php?backgroundcolor=darkblue#farben" style="color:#FFFFFF; text-decoration:none;">darkblue</a></td>
     <td style="background-color:darkcyan"><a href="setting.php?backgroundcolor=darkcyan#farben" style="color:#FFFFFF; text-decoration:none;">darkcyan</a></td>
     <td style="background-color:darkgoldenrod"><a href="setting.php?backgroundcolor=darkgoldenrod#farben" style="color:#FFFFFF; text-decoration:none;">darkgoldenrod</a></td>
    </tr><tr>
     <td style="background-color:darkgray"><a href="setting.php?backgroundcolor=darkgray#farben" style="color:#FFFFFF; text-decoration:none;">darkgray</a></td>
     <td style="background-color:darkgreen"><a href="setting.php?backgroundcolor=darkgreen#farben" style="color:#FFFFFF; text-decoration:none;">darkgreen</a></td>
     <td style="background-color:darkred"><a href="setting.php?backgroundcolor=darkred#farben" style="color:#FFFFFF; text-decoration:none;">darkred</a></td>
     <td style="background-color:green"><a href="setting.php?backgroundcolor=green#farben" style="color:#FFFFFF; text-decoration:none;">green</a></td>
     <td style="background-color:darkkhaki"><a href="setting.php?backgroundcolor=darkkhaki#farben" style="color:#FFFFFF; text-decoration:none;">darkkhaki</a></td>
     <td style="background-color:darkmagenta"><a href="setting.php?backgroundcolor=darkmagenta#farben" style="color:#FFFFFF; text-decoration:none;">darkmagenta</a></td>
     <td style="background-color:darkolivegreen"><a href="setting.php?backgroundcolor=darkolivegreen#farben" style="color:#FFFFFF; text-decoration:none;">darkolivegreen</a></td>
     <td style="background-color:darkorange"><a href="setting.php?backgroundcolor=darkorange#farben" style="color:#FFFFFF; text-decoration:none;">darkorange</a></td>
    </tr><tr> 
     <td style="background-color:darkorchid"><a href="setting.php?backgroundcolor=Darkorchid#farben" style="color:#FFFFFF; text-decoration:none;">darkorchid</a></td>
     <td style="background-color:darksalmon"><a href="setting.php?backgroundcolor=darksalmon#farben" style="color:#FFFFFF; text-decoration:none;">darksalmon</a></td>
     <td style="background-color:darkseagreen"><a href="setting.php?backgroundcolor=darkseagreen#farben" style="color:#000000; text-decoration:none;">darkseagreen</a></td>
     <td style="background-color:deepSkyBlue"><a href="setting.php?backgroundcolor=deepSkyBlue#farben" style="color:#000000; text-decoration:none;">deepSkyBlue</a></td>
     <td style="background-color:darkslateblue"><a href="setting.php?backgroundcolor=darkslateblue#farben" style="color:#FFFFFF; text-decoration:none;">darkslateblue</a></td>
     <td style="background-color:darkslategray"><a href="setting.php?backgroundcolor=darkslategray#farben" style="color:#FFFFFF; text-decoration:none;">darkslategray</a></td>
     <td style="background-color:darkturquoise"><a href="setting.php?backgroundcolor=darkturquoise#farben" style="color:#000000; text-decoration:none;">darkturquoise</a></td>
     <td style="background-color:darkviolet"><a href="setting.php?backgroundcolor=darkviolet#farben" style="color:#FFFFFF; text-decoration:none;">darkviolet</a></td>
    </tr><tr> 
     <td style="background-color:deeppink"><a href="setting.php?backgroundcolor=deeppink#farben" style="color:#FFFFFF; text-decoration:none;">deeppink</a></td>
     <td style="background-color:deepskyblue"><a href="setting.php?backgroundcolor=deepskyblue#farben" style="color:#FFFFFF; text-decoration:none;">deepskyblue</a></td>
     <td style="background-color:dimgray"><a href="setting.php?backgroundcolor=dimgray#farben" style="color:#FFFFFF; text-decoration:none;">dimgray</a></td>
     <td style="background-color:dodgerblue"><a href="setting.php?backgroundcolor=dodgerblue#farben" style="color:#FFFFFF; text-decoration:none;">dodgerblue</a></td>
     <td style="background-color:firebrick"><a href="setting.php?backgroundcolor=firebrick#farben" style="color:#FFFFFF; text-decoration:none;">firebrick</a></td>
     <td style="background-color:floralwhite"><a href="setting.php?backgroundcolor=floralwhite#farben" style="color:#000000; text-decoration:none;">floralwhite</a></td>
     <td style="background-color:forestgreen"><a href="setting.php?backgroundcolor=forestgreen#farben" style="color:#FFFFFF; text-decoration:none;">forestgreen</a></td>
     <td style="background-color:fuchsia"><a href="setting.php?backgroundcolor=fuchsia#farben" style="color:#000000; text-decoration:none;">fuchsia</a></td>
    </tr><tr> 
     <td style="background-color:gainsboro"><a href="setting.php?backgroundcolor=gainsboro#farben" style="color:#000000; text-decoration:none;">gainsboro</a></td>
     <td style="background-color:ghostwhite"><a href="setting.php?backgroundcolor=ghostwhite#farben" style="color:#000000; text-decoration:none;">ghostwhite</a></td>
     <td style="background-color:gold"><a href="setting.php?backgroundcolor=gold#farben" style="color:#000000; text-decoration:none;">gold</a></td>
     <td style="background-color:goldenrod"><a href="setting.php?backgroundcolor=goldenrod#farben" style="color:#FFFFFF; text-decoration:none;">goldenrod</a></td>
     <td style="background-color:gray"><a href="setting.php?backgroundcolor=gray#farben" style="color:#FFFFFF; text-decoration:none;">gray</a></td>
     <td style="background-color:green"><a href="setting.php?backgroundcolor=green#farben" style="color:#FFFFFF; text-decoration:none;">green</a></td>
     <td style="background-color:greenyellow"><a href="setting.php?backgroundcolor=greenyellow#farben" style="color:#000000; text-decoration:none;">greenyellow</a></td>
     <td style="background-color:grey"><a href="setting.php?backgroundcolor=grey#farben" style="color:#FFFFFF; text-decoration:none;">grey</a></td>
    </tr><tr>
     <td style="background-color:honeydew"><a href="setting.php?backgroundcolor=honeydew#farben" style="color:#000000; text-decoration:none;">honeydew</a></td>
     <td style="background-color:hotpink"><a href="setting.php?backgroundcolor=hotpink#farben" style="color:#FFFFFF; text-decoration:none;">hotpink</a></td>
     <td style="background-color:indianred"><a href="setting.php?backgroundcolor=indianred#farben" style="color:#FFFFFF; text-decoration:none;">indianred</a></td>
     <td style="background-color:indigo"><a href="setting.php?backgroundcolor=indigo#farben" style="color:#FFFFFF; text-decoration:none;">indigo</a></td>
     <td style="background-color:ivory"><a href="setting.php?backgroundcolor=ivory#farben" style="color:#000000; text-decoration:none;">ivory</a></td>
     <td style="background-color:khaki"><a href="setting.php?backgroundcolor=khaki#farben" style="color:#000000; text-decoration:none;">khaki</a></td>
     <td style="background-color:lavender"><a href="setting.php?backgroundcolor=lavender#farben" style="color:#000000; text-decoration:none;">lavender</a></td>
     <td style="background-color:lavenderblush"><a href="setting.php?backgroundcolor=lavenderblush#farben" style="color:#000000; text-decoration:none;">lavenderblush</a></td>
    </tr><tr> 
     <td style="background-color:lawngreen"><a href="setting.php?backgroundcolor=lawngreen#farben" style="color:#000000; text-decoration:none;">lawngreen</a></td>
     <td style="background-color:lemonchiffon"><a href="setting.php?backgroundcolor=lemonchiffon#farben" style="color:#000000; text-decoration:none;">lemonchiffon</a></td>
     <td style="background-color:lightblue"><a href="setting.php?backgroundcolor=lightblue#farben" style="color:#000000; text-decoration:none;">lightblue</a></td>
     <td style="background-color:lightcoral"><a href="setting.php?backgroundcolor=lightcoral#farben" style="color:#FFFFFF; text-decoration:none;">lightcoral</a></td>
     <td style="background-color:lightcyan"><a href="setting.php?backgroundcolor=lightcyan#farben" style="color:#000000; text-decoration:none;">lightcyan</a></td>
     <td style="background-color:lightgoldenrodyellow"><a href="setting.php?backgroundcolor=lightgoldenrodyellow#farben" style="color:#000000; text-decoration:none;">lightgoldenrodyellow</a></td>
     <td style="background-color:lightgray"><a href="setting.php?backgroundcolor=lightgray#farben" style="color:#000000; text-decoration:none;">lightgray</a></td>
     <td style="background-color:lightgreen"><a href="setting.php?backgroundcolor=lightgreen#farben" style="color:#000000; text-decoration:none;">lightgreen</a></td>
    </tr><tr> 
     <td style="background-color:lightpink"><a href="setting.php?backgroundcolor=lightpink#farben" style="color:#000000; text-decoration:none;">lightpink</a></td>
     <td style="background-color:lightsalmon"><a href="setting.php?backgroundcolor=lightsalmon#farben" style="color:#FFFFFF; text-decoration:none;">lightsalmon</a></td>
     <td style="background-color:lightseagreen"><a href="setting.php?backgroundcolor=lightseagreen#farben" style="color:#FFFFFF; text-decoration:none;">lightseagreen</a></td>
     <td style="background-color:lightskyblue"><a href="setting.php?backgroundcolor=lightskyblue#farben" style="color:#000000; text-decoration:none;">lightskyblue</a></td>
     <td style="background-color:lightslategray"><a href="setting.php?backgroundcolor=lightslategray#farben" style="color:#000000; text-decoration:none;">lightslategray</a></td>
     <td style="background-color:lightsteelblue"><a href="setting.php?backgroundcolor=lightsteelblue#farben" style="color:#000000; text-decoration:none;">lightsteelblue</a></td>
     <td style="background-color:lightyellow"><a href="setting.php?backgroundcolor=lightyellow#farben" style="color:#000000; text-decoration:none;">lightyellow</a></td>
     <td style="background-color:lime"><a href="setting.php?backgroundcolor=lime#farben" style="color:#000000; text-decoration:none;">lime</a></td>
    </tr><tr>
     <td style="background-color:limegreen"><a href="setting.php?backgroundcolor=limegreen#farben" style="color:#FFFFFF; text-decoration:none;">limegreen</a></td>
     <td style="background-color:linen"><a href="setting.php?backgroundcolor=linen#farben" style="color:#000000; text-decoration:none;">linen</a></td>
     <td style="background-color:magenta"><a href="setting.php?backgroundcolor=magenta#farben" style="color:#FFFFFF; text-decoration:none;">magenta</a></td>
     <td style="background-color:maroon"><a href="setting.php?backgroundcolor=maroon#farben" style="color:#FFFFFF; text-decoration:none;">maroon</a></td>
     <td style="background-color:mediumaquamarine"><a href="setting.php?backgroundcolor=mediumaquamarine#farben" style="color:#000000; text-decoration:none;">mediumaquamarine</a></td>
     <td style="background-color:mediumblue"><a href="setting.php?backgroundcolor=mediumblue#farben" style="color:#FFFFFF; text-decoration:none;">mediumblue</a></td>
     <td style="background-color:mediumorchid"><a href="setting.php?backgroundcolor=mediumorchid#farben" style="color:#FFFFFF; text-decoration:none;">mediumorchid</a></td>
     <td style="background-color:mediumpurple"><a href="setting.php?backgroundcolor=mediumpurple#farben" style="color:#FFFFFF; text-decoration:none;">mediumpurple</a></td>
    </tr><tr> 
     <td style="background-color:mediumseagreen"><a href="setting.php?backgroundcolor=mediumseagreen#farben" style="color:#FFFFFF; text-decoration:none;">mediumseagreen</a></td>
     <td style="background-color:mediumslateblue"><a href="setting.php?backgroundcolor=mediumslateblue#farben" style="color:#FFFFFF; text-decoration:none;">mediumslateblue</a></td>
     <td style="background-color:mediumspringgreen"><a href="setting.php?backgroundcolor=mediumspringgreen#farben" style="color:#000000; text-decoration:none;">mediumspringgreen</a></td>
     <td style="background-color:mediumturquoise"><a href="setting.php?backgroundcolor=mediumturquoise#farben" style="color:#000000; text-decoration:none;">mediumturquoise</a></td>
     <td style="background-color:mediumvioletred"><a href="setting.php?backgroundcolor=mediumvioletred#farben" style="color:#FFFFFF; text-decoration:none;">mediumvioletred</a></td>
     <td style="background-color:midnightblue"><a href="setting.php?backgroundcolor=midnightblue#farben" style="color:#FFFFFF; text-decoration:none;">midnightblue</a></td>
     <td style="background-color:mintcream"><a href="setting.php?backgroundcolor=mintcream#farben" style="color:#000000; text-decoration:none;">mintcream</a></td>
     <td style="background-color:mistyrose"><a href="setting.php?backgroundcolor=mistyrose#farben" style="color:#000000; text-decoration:none;">mistyrose</a></td>
    </tr><tr>
     <td style="background-color:moccasin"><a href="setting.php?backgroundcolor=moccasin#farben" style="color:#000000; text-decoration:none;">moccasin</a></td>
     <td style="background-color:navajowhite"><a href="setting.php?backgroundcolor=navajowhite#farben" style="color:#000000; text-decoration:none;">navajowhite</a></td>
     <td style="background-color:navy"><a href="setting.php?backgroundcolor=navy#farben" style="color:#FFFFFF; text-decoration:none;">navy</a></td>
     <td style="background-color:oldlace"><a href="setting.php?backgroundcolor=oldlace#farben" style="color:#000000; text-decoration:none;">oldlace</a></td>
     <td style="background-color:olive"><a href="setting.php?backgroundcolor=olive#farben" style="color:#FFFFFF; text-decoration:none;">olive</a></td>
     <td style="background-color:olivedrab"><a href="setting.php?backgroundcolor=olivedrab#farben" style="color:#FFFFFF; text-decoration:none;">olivedrab</a></td>
     <td style="background-color:orange"><a href="setting.php?backgroundcolor=orange#farben" style="color:#000000; text-decoration:none;">orange</a></td>
     <td style="background-color:orangered"><a href="setting.php?backgroundcolor=orangered#farben" style="color:#FFFFFF; text-decoration:none;">orangered</a></td>
    </tr><tr> 
     <td style="background-color:orchid"><a href="setting.php?backgroundcolor=orchid#farben" style="color:#FFFFFF; text-decoration:none;">orchid</a></td>
     <td style="background-color:palegoldenrod"><a href="setting.php?backgroundcolor=palegoldenrod#farben" style="color:#000000; text-decoration:none;">palegoldenrod</a></td>
     <td style="background-color:palegreen"><a href="setting.php?backgroundcolor=palegreen#farben" style="color:#000000; text-decoration:none;">palegreen</a></td>
     <td style="background-color:paleturquoise"><a href="setting.php?backgroundcolor=paleturquoise#farben" style="color:#000000; text-decoration:none;">paleturquoise</a></td>
     <td style="background-color:palevioletred"><a href="setting.php?backgroundcolor=palevioletred#farben" style="color:#000000; text-decoration:none;">palevioletred</a></td>
     <td style="background-color:papayawhip"><a href="setting.php?backgroundcolor=papayawhip#farben" style="color:#000000; text-decoration:none;">papayawhip</a></td>
     <td style="background-color:peachpuff"><a href="setting.php?backgroundcolor=peachpuff#farben" style="color:#000000; text-decoration:none;">peachpuff</a></td>
     <td style="background-color:peru"><a href="setting.php?backgroundcolor=peru#farben" style="color:#FFFFFF; text-decoration:none;">peru</a></td>
    </tr><tr> 
     <td style="background-color:pink"><a href="setting.php?backgroundcolor=pink#farben" style="color:#000000; text-decoration:none;">pink</a></td>
     <td style="background-color:plum"><a href="setting.php?backgroundcolor=plum#farben" style="color:#FFFFFF; text-decoration:none;">plum</a></td>
     <td style="background-color:powderblue"><a href="setting.php?backgroundcolor=powderblue#farben" style="color:#000000; text-decoration:none;">powderblue</a></td>
     <td style="background-color:purple"><a href="setting.php?backgroundcolor=purple#farben" style="color:#FFFFFF; text-decoration:none;">purple</a></td>
     <td style="background-color:rebeccapurple"><a href="setting.php?backgroundcolor=rebeccapurple#farben" style="color:#FFFFFF; text-decoration:none;">rebeccapurple</a></td>
     <td style="background-color:red"><a href="setting.php?backgroundcolor=red#farben" style="color:#FFFFFF; text-decoration:none;">red</a></td>
     <td style="background-color:rosybrown"><a href="setting.php?backgroundcolor=rosybrown#farben" style="color:#FFFFFF; text-decoration:none;">rosybrown</a></td>
     <td style="background-color:royalblue"><a href="setting.php?backgroundcolor=royalblue#farben" style="color:#FFFFFF; text-decoration:none;">royalblue</a></td>
    </tr><tr> 
     <td style="background-color:saddlebrown"><a href="setting.php?backgroundcolor=saddlebrown#farben" style="color:#FFFFFF; text-decoration:none;">saddlebrown</a></td>
     <td style="background-color:salmon"><a href="setting.php?backgroundcolor=salmon#farben" style="color:#FFFFFF; text-decoration:none;">salmon</a></td>
     <td style="background-color:sandybrown"><a href="setting.php?backgroundcolor=sandybrown#farben" style="color:#000000; text-decoration:none;">sandybrown</a></td>
     <td style="background-color:seagreen"><a href="setting.php?backgroundcolor=seagreen#farben" style="color:#FFFFFF; text-decoration:none;">seagreen</a></td>
     <td style="background-color:seashell"><a href="setting.php?backgroundcolor=seashell#farben" style="color:#000000; text-decoration:none;">seashell</a></td>
     <td style="background-color:sienna"><a href="setting.php?backgroundcolor=sienna#farben" style="color:#FFFFFF; text-decoration:none;">sienna</a></td>
     <td style="background-color:silver"><a href="setting.php?backgroundcolor=silver#farben" style="color:#000000; text-decoration:none;">silver</a></td>
     <td style="background-color:skyblue"><a href="setting.php?backgroundcolor=skyblue#farben" style="color:#000000; text-decoration:none;">skyblue</a></td>
    </tr><tr> 
     <td style="background-color:slateblue"><a href="setting.php?backgroundcolor=slateblue#farben" style="color:#FFFFFF; text-decoration:none;">slateblue</a></td>
     <td style="background-color:slategray"><a href="setting.php?backgroundcolor=slategray#farben" style="color:#FFFFFF; text-decoration:none;">slategray</a></td>
     <td style="background-color:snow"><a href="setting.php?backgroundcolor=snow#farben" style="color:#000000; text-decoration:none;">snow</a></td>
     <td style="background-color:springgreen"><a href="setting.php?backgroundcolor=springgreen#farben" style="color:#000000; text-decoration:none;">springgreen</a></td>
     <td style="background-color:steelblue"><a href="setting.php?backgroundcolor=steelblue#farben" style="color:#FFFFFF; text-decoration:none;">steelblue</a></td>
     <td style="background-color:tan"><a href="setting.php?backgroundcolor=tan#farben" style="color:#FFFFFF; text-decoration:none;">tan</a></td>
     <td style="background-color:teal"><a href="setting.php?backgroundcolor=teal#farben" style="color:#FFFFFF; text-decoration:none;">teal</a></td>
     <td style="background-color:thistle"><a href="setting.php?backgroundcolor=thistle#farben" style="color:#000000; text-decoration:none;">thistle</a></td>
    </tr><tr>
     <td style="background-color:tomato"><a href="setting.php?backgroundcolor=tomato#farben" style="color:#000000; text-decoration:none;">tomato</a></td>
     <td style="background-color:turquoise"><a href="setting.php?backgroundcolor=turquoise#farben" style="color:#000000; text-decoration:none;">turquoise</a></td>
     <td style="background-color:violet"><a href="setting.php?backgroundcolor=violet#farben" style="color:#000000; text-decoration:none;">violet</a></td>
     <td style="background-color:wheat"><a href="setting.php?backgroundcolor=wheat#farben" style="color:#000000; text-decoration:none;">wheat</a></td>
     <td style="background-color:white"><a href="setting.php?backgroundcolor=white#farben" style="color:#000000; text-decoration:none;">white</a></td>
     <td style="background-color:whitesmoke"><a href="setting.php?backgroundcolor=whitesmoke#farben" style="color:#000000; text-decoration:none;">whitesmoke</a></td>
     <td style="background-color:yellow"><a href="setting.php?backgroundcolor=yellow#farben" style="color:#000000; text-decoration:none;">yellow</a></td>
     <td style="background-color:yellowgreen"><a href="setting.php?backgroundcolor=yellowgreen#farben" style="color:#000000; text-decoration:none;">yellowgreen</a></td>
    </tr>
  </table>  
  </fieldset>

<?php

?>    
<hr noshade>

<p> </p>

<?php
print_footer ("browse");
?>