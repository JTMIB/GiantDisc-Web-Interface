<?php

include "control_web.inc";

## Variablen aus Browser-Zeile
$artist=$_POST['artist'];
$title=$_POST['title'];
$composer=$_POST['composer'];
$genre1=$_POST['genre1'];
$genre2=$_POST['genre2'];
$year=$_POST['year'];
$bpm=$_POST['bpm'];
$lang=$_POST['lang'];

$type=$_POST['type'];
$rating=$_POST['rating'];
$source=$_POST['source'];

$schritt=$_POST['schritt'];
$cddbid=$_POST['cddbid'];

  $link =  mysqli_connect( MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB );
  if ( ! $link )
      die( "Keine Verbindung zu MySQL" );

if ($schritt != 2){

print_header ("record", "", "$cgi_dir", "$char_set", $output135, $output136, $output137, $output138);
print "<hr noshade>";
print "<h2>Musikdatei hochladen</h2>";
?>
<form name="uploadformular" 
      enctype="multipart/form-data" action="file_upload.php" method="post">
<?php
print "<table border=0><tr><td>";
print "<table border=0>
<tr>
<td class=plformtit>Artist</td>
<td colspan=3 class=plform>
<input type=text name=artist value='$artist' size=40 maxlength=200>
</td></tr>

<tr>
<td class=plformtit>Title</td>
<td colspan=3 class=plform>
<input type=text name=title  value='$title' size=40 maxlength=200>
</td></tr>

<tr>
<td class=plformtit>Composer</td>
<td colspan=3 class=plform>
<input type=text name=composer  value='$composer' size=40 maxlength=200>
</td></tr>

<tr>
<td class=plformtit>Genre</td>
<td colspan=3 class=plform>
<small><select name=genre1>";
print_genre_options($genre1);
print "</select></small>
<small><select name=genre2>";
print_genre_options($genre2);
print "</select></small>
</td></tr>

<tr>
<td class=plformtit>Year</td>
<td class=plform>
<input type=text name=year value='$year' size=4 maxlength=4>
</td>
<td class=plformtit>Beats/min</td>
<td class=plform>
<input type=text name=bpm value='$bpm' size=4 maxlength=4>
</td>
</tr>

<tr>
<td class=plformtit>Language</td>
<td class=plform>
<small><select name=lang>";
print_lang_options($lang);
print "</select></small>
</td>
<td class=plformtit>Type</td>
<td class=plform>
<small><select name=type>";
$selected = 2;

  $recset = mysqli_query ( $link, "SELECT * FROM musictype ORDER BY id");
  print "<option value=\"\">&nbsp;</option>\n";
  while($row = mysqli_fetch_array($recset)) {
    print "<option value=\"".$row["id"]."\"";
    if ($row["id"] == $selected) {print(" selected");};
    print ">";
	print $row["musictype"]."</option>\n";
  }
#  mysql_close( $link );

print "</select></small>
</td>
</tr>

<tr>
<td class=plformtit>Rating</td>
<td class=plform>
<small><select name=rating>";
$selected = 1;
  $recset = array("-", "0", "+", "++");
  print "<option value=\"\">&nbsp;</option>\n";
  foreach ($recset as $key => $value) {
#  while(list($key, $value) = each($recset)) {
    print "<option value=\"".$key."\"";
    if (strlen($selected)>0 && $key == $selected) {print(" selected");};
    print ">";
	print $value."</option>\n";
  }

#print_rating_options($rating);
print "</select></small>
</td>
<td class=plformtit>Source</td>
<td class=plform>
<small><select name=source>";
$selected = 1;

  $recset = mysqli_query ( $link, "SELECT * FROM source ORDER BY id");
  print "<option value=\"\">&nbsp;</option>\n";
  while($row = mysqli_fetch_array($recset)) {
    print "<option value=\"".$row["id"]."\"";
    if ($row["id"] == $selected) {print(" selected");};
    print ">";
	print $row["source"]."</option>\n";
  }



print "</select></small>
</td>
</tr>

</table>";
print "</td><td><p>&nbsp; &nbsp; &nbsp; &nbsp;<p></td><td>";
print "<input type='submit' value='Datei hochladen' style='text-align: center;'>";
#print "<p><br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br></p>";
#print "<input type='submit' value='Update' style='text-align: center;'>";
print "</td></tr></table>";


# letzte cddbid auslesen
$ergebnis = mysqli_query ( $link, "SELECT mp3file FROM tracks WHERE mp3file LIKE 'trxx%'" );
$anz_felder = mysqli_num_fields( $ergebnis );
$anz_reihen = mysqli_num_rows( $ergebnis );

while ( $datensatz = mysqli_fetch_row( $ergebnis ) )
    {
    foreach ( $datensatz as $feld )
		  {
		  }
    }
#mysql_close( $link );

# errechnen von naechster cddbid
if ($anz_reihen == 0)
	$feld = "trxx00000000.mp3";
$feld = substr($feld, 4, 8);
$feld++;
$lang = strlen($feld);
$soll = 8 - $lang;
$y = 0;
while ($y < $soll)
	{
	$reihe[$y] = "0";
	$y++;
	}
$reihe[$y] = $feld;
$cddbid = implode("",$reihe);
#print "<br>$cddbid";


print "<input type=hidden name=cddbid value='$cddbid' size=8 maxlength=8>";
print "<input type=hidden name=schritt value='2'>";
print "<p> </p>";

print "<input name=\"datei\" accept=\".mp3, .ogg, .flac, .opus, .m4a\" size=40 type=\"file\" />";
print "</form>";
print "<hr noshade>";
}      

?>

<?php

#$upload_verzeichnis = '/tmp';
$upload_verzeichnis = $tempdir;

# bis hier
   
if ($schritt = 2){


    
#if ( $_FILES['uploaddatei']['name']  <> "" )
if ( $_FILES['datei']['name']  <> "" )
{
    // Datei wurde durch HTML-Formular hochgeladen
    // und kann nun weiterverarbeitet werden

    // Kontrolle, ob Dateityp zulässig ist
#    mp4 muss wegen m4a eingetragen werden
    $zugelassenedateitypen = array("audio/mp3", "audio/mpeg", "audio/ogg", "audio/flac", "audio/mp4", "audio/m4a", "audio/opus");

    if ( ! in_array( $_FILES['datei']['type'] , $zugelassenedateitypen ))
    {
        echo "<p>Dateitype ist NICHT zugelassen</p>";
        $hochladen = "Fehler";
    }
    else
    {
        // Test ob Dateiname in Ordnung
        $_FILES['datei']['name'] 
                               = dateiname_bereinigen($_FILES['datei']['name']);

        if ( $_FILES['datei']['name'] <> '' )
        {
            move_uploaded_file (
                 $_FILES['datei']['tmp_name'] ,
                 "$upload_verzeichnis/". $_FILES['datei']['name'] );

	          print_header ("fin", "", "$cgi_dir", "$char_set", $output135, $output136, $output137, $output138);

	          print "<hr noshade>";
	          print "<h2>Musikdatei hochladen</h2>";

            echo "<p>Hochladen war erfolgreich: ";
            $hochladen = "OK";
	          $tracknb = 1;
	          $sourceid = $cddbid;
	          $modified = date("Y-m-d");
#	          # .mp3
#	          $n = strlen($_FILES['datei']['name']);
#	          $extention = substr($_FILES['datei']['name'],$n-4);
            $teile = explode(".", $_FILES['datei']['name']);
            $extention = '.'.$teile[1];     
	          
	          if ($extention == ".mp3")
		          $bitrate = "mp3";
	          if ($extention == ".ogg")
		          $bitrate = "ogg";
	          if ($extention == ".flac")
		          $bitrate = "flac";
	          if ($extention == ".opus")
		          $bitrate = "opus";
	          if ($extention == ".m4a")
		          $bitrate = "m4a";
                                          		          
            $mp3file = "trxx"."$cddbid"."$extention";
	           if ($year == "")
		            $year=0;
	           if ($bpm == "")
	              $bpm=0;
            print "<p><b>$artist - $title</b><br>";
		        print "$composer<br>";
		        print "$genre1<br>";
		        print "$genre2<br>";
		        print "year: $year<br>";
		        print "$lang<br>";
		        print "$type<br>";
		        print "$rating<br>";            
		        #Korrektur
		        $source = $source - 1;
		        print "$source<br>";
		        print "$sourceid<br>";
		        print "TrackNb: $tracknb<br>";
		        print "bpm: $bpm<br>";
		        print "$mp3file<br>";
		        print "$dateiname<br>";
		        print "$bitrate<br>";
		        print "$modified</p>";

            $link =  mysqli_connect( MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB );
            if ( ! $link )
                die( "Keine Verbindung zu MySQL" );

            $abfrage =  "INSERT INTO tracks (artist, title, composer, genre1, genre2, year, lang, type, rating,
                        length, source, sourceid, tracknb, mp3file, quality, voladjust, lengthfrm, startfrm, bpm,
                        lyrics, bitrate, created, modified, backup)
                        values('$artist', '$title', '$composer', '$genre1', '$genre2', '$year', '$lang', '$type', '$rating',
                        '0', '$source', '$sourceid', '$tracknb', '$mp3file', '0', '0', '0', '0', '$bpm',
                        NULL, '$bitrate', '$modified', '$modified', NULL)";
              
            $ergebnis = mysqli_query( $link, $abfrage );
  
            	$sourceid = "$upload_verzeichnis/". $_FILES['datei']['name'];
	          	$ausgabe = "$PATH_ABSOLUTE_mp3/$mp3file";
	          	copy ($sourceid, $ausgabe);
            	unlink("$upload_verzeichnis/". $_FILES['datei']['name']);
            	
print $_FILES['datei']['name'];
print "<br>";

#            echo '<a href=';
#            echo "00/";
#            echo $_FILES['datei']['name'];
#            echo " >";
#            echo "music/00/";
#            echo $_FILES['datei']['name'];
#            echo '</a>';
            
        }
        else
        {
            echo "<p>Dateiname ist nicht zulässig</p>";
            print "<p><b>Error</b></p>";
	          $hochladen = "Fehler";
        }
    }

}
} // schritt = 2
 

#################################################################
if (isset($hochladen)) {
if ($hochladen == "Fehler")
	{
	print "<html>";
	print "<head>";
	print "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"2; URL=file_upload.php?artist=$artist&title=$title&composer=$composer&genre1=$genre1&genre2=$genre2&year=$year&bpm=$bpm&lang=$lang\">";
	print "</head>";
	print "<body>";
	}
}	

#################################################################


mysqli_close( $link );  

print_footer ("record");

function dateiname_bereinigen($dateiname)
{
    // erwünschte Zeichen erhalten bzw. umschreiben
    // aus allen ä wird ae, ü -> ue, ß -> ss (je nach Sprache mehr Aufwand)
    // und sonst noch ein paar Dinge (ist schätzungsweise mein persönlicher Geschmach ;)
    $dateiname = strtolower ( $dateiname );
    $dateiname = str_replace ('"', "-", $dateiname );
    $dateiname = str_replace ("'", "-", $dateiname );
    $dateiname = str_replace ("*", "-", $dateiname );
    $dateiname = str_replace ("ß", "ss", $dateiname );
    $dateiname = str_replace ("ß", "ss", $dateiname );
    $dateiname = str_replace ("ä", "ae", $dateiname );
    $dateiname = str_replace ("ä", "ae", $dateiname );
    $dateiname = str_replace ("ö", "oe", $dateiname );
    $dateiname = str_replace ("ö", "oe", $dateiname );
    $dateiname = str_replace ("ü", "ue", $dateiname );
    $dateiname = str_replace ("ü", "ue", $dateiname );
    $dateiname = str_replace ("Ä", "ae", $dateiname );
    $dateiname = str_replace ("Ö", "oe", $dateiname );
    $dateiname = str_replace ("Ü", "ue", $dateiname );
    $dateiname = htmlentities ( $dateiname );
    $dateiname = str_replace ("&", "und", $dateiname );
    $dateiname = str_replace (" ", "und", $dateiname );
    $dateiname = str_replace ("(", "-", $dateiname );
    $dateiname = str_replace (")", "-", $dateiname );
    $dateiname = str_replace (" ", "-", $dateiname );
    $dateiname = str_replace ("'", "-", $dateiname );
    $dateiname = str_replace ("/", "-", $dateiname );
    $dateiname = str_replace ("?", "-", $dateiname );
    $dateiname = str_replace ("!", "-", $dateiname );
    $dateiname = str_replace (":", "-", $dateiname );
    $dateiname = str_replace (";", "-", $dateiname );
    $dateiname = str_replace (",", "-", $dateiname );
    $dateiname = str_replace ("--", "-", $dateiname );

    // und nun jagen wir noch die Heilfunktion darüber
    $dateiname = filter_var($dateiname, FILTER_SANITIZE_URL);
    return ($dateiname);
}
?>


