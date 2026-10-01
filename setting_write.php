<?php

include "control_web.inc";
include "gdwebdef.php";

$playtp = set_get_cookie($setplaytp, "playtp", "dl", $HTTP_COOKIE_VARS);

## Variablen aus Browser-Zeile
$submitted=$_GET['submitted']; 
$prefc=$_GET['prefc'];
$language=$_GET['language'];
$CharSet=$_GET['CharSet'];
$mp3_Q=$_GET['mp3_Q'];
$ogg_Q=$_GET['ogg_Q'];
$flac_Q=$_GET['flac_Q'];
$opus_Q=$_GET['opus_Q'];
$m4a_Q=$_GET['m4a_Q'];
$read_CD=$_GET['read_CD'];
$dev_cd=$_GET['dev_cd'];


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
  <meta http-equiv=\"refresh\" content=\"2; URL=settings.php\">
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
  print "<table><tr>";
  print "<td>";
  print "<div class='maintitle'>GiantDisc&nbsp;Web&nbsp;Interface</div>";
  print "<div class='$current'>";
  print "</td>";

	print "<td width=\"100%\" height=\"44\" align=\"right\">
	<img src=\"img/flac.gif\" height=\"75\" border=\"0\" align=\"right\">
	<img src=\"img/opus.png\" width=\"75\" border=\"0\" align=\"right\">
	<img src=\"img/ogg_vorbis.png\" height=\"75\" border=\"0\" align=\"right\">
	<img src=\"img/mp3.png\" height=\"75\" border=\"0\" align=\"right\">";
	print "</td></tr></table>";

  print "<table border='0' cellspacing='0' cellpadding='1'><tr>";
  print "<td class=\"menu".$sel."active\"><b>$output139</b></td>";
  print "</tr></table>";    


#print "$submitted<br>";
if ( $submitted == $output141)
        {
        # setting.inc schreiben
        print "$submitted<br>";
        print "$prefc<br>";
        print "$language<br>";
        print "$CharSet<br>";
        print "$mp3_Q<br>";
        print "$ogg_Q<br>";
        print "$flac_Q<br>";
        print "$opus_Q<br>";
        print "$m4a_Q<br>";
        print "$read_CD<br>";
        print "$dev_cd<br>";
        
        $myfile = fopen("setting.inc", "w") or die("Kann Datei nicht öffnen!");
        fwrite($myfile, "<?php\n\n");
        fwrite($myfile, "###########################################################################\n");
        fwrite($myfile, "# prefered format\n");
        fwrite($myfile, '$prefc');
        fwrite($myfile, " = \"$prefc\";\n");
        fwrite($myfile, '#$prefc = "mp3";'."\n");
        fwrite($myfile, '#$prefc = "ogg";'."\n");
        fwrite($myfile, '#$prefc = "flac";'."\n");
        fwrite($myfile, '#$prefc = "opus";'."\n");
        fwrite($myfile, '#$prefc = "m4a";'."\n");
        
        fwrite($myfile, "\n");
        fwrite($myfile, "###########################################################################\n");
        fwrite($myfile, "# Sprache - language\n");        
        fwrite($myfile, '$language');
        fwrite($myfile, " = \"$language\";\n");
if ( $language == "de" )
        fwrite($myfile, "require \"german_utf8.inc\";\n");
if ( $language == "en" )
        fwrite($myfile, "require \"english.inc\";\n");        
        fwrite($myfile, '#$language = "de";'."\n");
        fwrite($myfile, '#$language = "en";'."\n");

        fwrite($myfile, "\n");
        fwrite($myfile, "###########################################################################\n");
        fwrite($myfile, "# Browser Zeichensatz\n");
        fwrite($myfile, '$char_set');
if ( $CharSet == "1" )
        fwrite($myfile, " = \"iso-8859-1\";\n"); 
if ( $CharSet == "2" )
        fwrite($myfile, " = \"UTF-8\";\n");

        fwrite($myfile, "\n");
        fwrite($myfile, "###########################################################################\n");
        fwrite($myfile, "# MP3-Bitrate\n");
        fwrite($myfile, '$mp3_Q');
        fwrite($myfile, " = \"$mp3_Q\";\n");          

        fwrite($myfile, "\n");
        fwrite($myfile, "###########################################################################\n");
        fwrite($myfile, "# OGG-Bitrate/Qualität\n");
        fwrite($myfile, '$ogg_Q');
        fwrite($myfile, " = \"$ogg_Q\";\n"); 

        fwrite($myfile, "\n");
        fwrite($myfile, "###########################################################################\n");
        fwrite($myfile, "# FLAC-Comprression\n");
        fwrite($myfile, '$flac_Q');
        fwrite($myfile, " = \"$flac_Q\";\n");

        fwrite($myfile, "\n");
        fwrite($myfile, "###########################################################################\n");
        fwrite($myfile, "# OPUS-Bitrate\n");
        fwrite($myfile, '$opus_Q');
        fwrite($myfile, " = \"$opus_Q\";\n");

        fwrite($myfile, "\n");
        fwrite($myfile, "###########################################################################\n");
        fwrite($myfile, "# M4A-Bitrate\n");
        fwrite($myfile, '$m4a_Q');
        fwrite($myfile, " = \"$m4a_Q\";\n");
        
        fwrite($myfile, "\n");
        fwrite($myfile, "###########################################################################\n");
        fwrite($myfile, "# CD-Laufwerk - CD drive\n");
        fwrite($myfile, "# Auf no stellen, wenn kein CD-Laufwerk vorhanden ist.\n");
        fwrite($myfile, "# Wenn dieser Parameter auf no steht, dann wird die Prüfung der entsprechenden Softwaren ausgeschaltet.\n");
        fwrite($myfile, '$read_CD');
        fwrite($myfile, " = \"$read_CD\";\n");
        
        fwrite($myfile, "\n");
        fwrite($myfile, "###########################################################################\n");
        fwrite($myfile, "# verwendetes Geraet - device used\n");
        fwrite($myfile, '$dev_cd');
        fwrite($myfile, " = \"$dev_cd\";\n");                
                
        fwrite($myfile, "\n");
        fwrite($myfile, "?>\n");
        fclose($myfile);
        }
        



?>











<hr noshade>

<p> </p>

<?php
print_footer ("browse");
?>