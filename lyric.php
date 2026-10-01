<?php
include "control_web.inc";
include "gdwebdef.php";

## Variablen aus Browser-Zeile
$id=$_GET['id']; 

?>

<html>
  <head>
  <title>GiantDisc Web Interface: browse</title>
  <?php
  print "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=$char_set\">";
  ?>
  <meta name="pragma" content="no-cache">
  <meta http-equiv="cache-control" content="no-cache">
  <meta http-equiv="expires" content="100">
  <meta name="revisit-after" content="1">
  <link href="gdweb.css" rel="stylesheet" type="text/css">
  <link rel="SHORTCUT ICON" href="img/gd16.ico">
  </head>

<body>
<table width="100%">
<tr>
<td class="trklst">
<?php

  $link =  mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
  if ( ! $link )
      die( "Keine Verbindung zu MySQL" );

$ergebnis = mysqli_query( $link , "SELECT artist, title, lyrics, year  FROM tracks where id = $id" );
$anz_felder = mysqli_num_fields( $ergebnis );
$anz_row = mysqli_num_rows( $ergebnis );

$a = 0;
while ( $datensatz = mysqli_fetch_row( $ergebnis ) )
	{
    foreach ( $datensatz as $feld )
		{
		 $a = $a + 1;

	 	switch ($a)
			{
			case $a == 1:
			$artist = $feld;
			break;

			case $a == 2:
			$title = $feld;
			break;

			case $a == 3:
			$lyrics = $feld;
			break;

			case $a == 4:
			$year = $feld;
			break;

			default:
			break;
      }		 
    }	
	}


print "$output032:<br>$output033:<br>Jahr:</td><td class=\"trklst\">$artist<br>$title<br>$year";

?>
</td>
<td><a href="#" onclick="self.close();"><img src="img/lyric.png" height="80" border="0" align="right" title="
<?php
print "$output090";
?>
"></a></td>

</tr>
<table>


<table>
<tr>
<td>
<?php
print "<pre>$lyrics</pre>";
?>
</td>
</tr>
<table>


<?php
print "<table align='right' border='0' cellspacing='0' cellpadding='1'><tr><td class=\"menu".$sel."active\"><a class=\"menu\" href=\"#\" onclick=\"self.close();\">$output090</a></td></tr></table>";

#  mysql_free_result($recset);
  mysqli_close( $link );

?>
<p>&nbsp; &nbsp;</p>

</body>
</html>