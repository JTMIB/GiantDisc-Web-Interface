<?php

include "control_web.inc";
#include "gdwebdef.php";


#
# First part:
#				Check whether every required parts of the webinterface
#				are installed.
#


# mysql
#$chk['mysqlaccess'] = @mysql_connect(MYSQL_HOST);
#$chk['databaseaccess'] = @mysql_select_db(MYSQL_DB) && $chk['mysqlaccess'];
#$chk['mysqlaccess'] = mysql_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS);
$chk['mysqlaccess'] = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
#$dblink = mysql_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS);
#$chk['databaseaccess'] = mysql_select_db(MYSQL_DB,$dblink) && $chk['mysqlaccess'];

#########################################

# check if TEMP-Directory is readable and writeable
$chk['tmprw'] = is_dir(PATH_ABSOLUTE_TEMP) && is_readable(PATH_ABSOLUTE_TEMP) && is_writable(PATH_ABSOLUTE_TEMP);

# check if Cover-Directory is readable and writeable
$chk['coverrw'] = is_dir(PATH_ABSOLUTE_IMAGE) && is_readable(PATH_ABSOLUTE_IMAGE) && is_writable(PATH_ABSOLUTE_IMAGE);

# check if MP3/OGG-Directory is readable and writeable
$chk['mp3rw'] = is_dir($PATH_ABSOLUTE_mp3) && is_readable($PATH_ABSOLUTE_mp3) && is_writable($PATH_ABSOLUTE_mp3);

#########################################
# PHP-Version

$PHPVersion = phpversion();
$a = 0;
$teil = strtok ( $PHPVersion, "." );
while ($teil) {
	$ver[$a] = $teil;
	#print "$ver[$a]<br>";
	$a++;
	$teil = strtok (".");
}

if ( (int)$ver[0] > 5 )
	#print "richtige Hauptversion";
	$phpver = true;
else
{
if ( (int)$ver[0] < 5 )
	#print "falsche Version";
	$phpver = false;	
else
{
if ( (int)$ver[1] > 0 )
	#print "richtige Hauptversion";
	$phpver = true;	
else
{
if ( (int)$ver[2] >= 5 )
	#print "richtige Hauptversion";
	$phpver = true;	
}}}

#########################################
# php.ini

if(ini_get("register_globals") == "1")
#if (unregister_globals() == true)
  $registerglobals = "img/icon_activate.gif";
  else
  $registerglobals = "img/icon_cancel.gif";

if(ini_get("register_long_arrays") == "1")
  $registerlongarrays = "img/icon_activate.gif";
  else
  $registerlongarrays = "img/icon_cancel.gif";


$uploadmaxfilesize = ini_get('upload_max_filesize');
$uploadmaxfilesize = explode("=", $uploadmaxfilesize);
$uploadmaxfilesize = $uploadmaxfilesize[0];
$lang = strlen($uploadmaxfilesize);
$lang = $lang - 1;
$uploadmaxfilesize = substr($uploadmaxfilesize,0,3);
$uploadmaxfilesize = (int)$uploadmaxfilesize;
if($uploadmaxfilesize >= 100)
  $uploadmaxfilesize_ico= "img/icon_activate.gif";
else
  $uploadmaxfilesize_ico= "img/icon_cancel.gif";


$postmaxsize = ini_get('post_max_size');
$postmaxsize = explode("=", $postmaxsize);
$postmaxsize = $postmaxsize[0];
$lang = strlen($postmaxsize);
$lang = $lang - 1;
$postmaxsize = substr($postmaxsize,0,$lang);
$postmaxsize = (int)$postmaxsize;
if($postmaxsize >= 300)
  {
  $postmaxsize_ico= "img/icon_activate.gif";
  }
else
  {
  $postmaxsize_ico= "img/icon_cancel.gif";
  }

$maxexecutiontime = ini_get('max_execution_time');
$maxexecutiontime = explode("=", $maxexecutiontime);
$maxexecutiontime = $maxexecutiontime[0];
$lang = strlen($maxexecutiontime);
#$lang = $lang - 1;
$maxexecutiontime = substr($maxexecutiontime,0,$lang);
$maxexecutiontime = (int)$maxexecutiontime;
if($maxexecutiontime >= 60)
  {
  $maxexecutiontime_ico= "img/icon_activate.gif";
  }
else
  {
  $maxexecutiontime_ico= "img/icon_cancel.gif";
  }


#########################################
# Required Third Party Software

$chk['ripper'] = file_exists("$ripp_path$ripper");


$chk['encoder'] = file_exists("/usr/bin/$encoder");
if ($chk['encoder'] == false)
	{
	$chk['encoder'] = file_exists("/usr/local/bin/$encoder");
	}


if ($oggenc == "yes")
	$chk['oggenc'] = file_exists("/usr/bin/oggenc");

if ($opusenc == "yes")
	$chk['opusenc'] = file_exists("/usr/bin/opusenc");

if ($oggenc == "yes")
	$chk['ogginfo'] = file_exists("/usr/bin/ogginfo");

if ($flacenc == "yes")
	$chk['flacenc'] = file_exists("/usr/bin/flac");

if ($flacenc == "yes")
	$chk['metaflac'] = file_exists("/usr/bin/metaflac");

if ($ffmpeg == "yes")
	$chk['ffmpeg'] = file_exists("/usr/bin/ffmpeg");
	
	
$chk['mp3info'] = file_exists("/usr/bin/mp3info");
if ($chk['mp3info'] == false)
	{
	$chk['mp3info'] = file_exists("/usr/local/bin/mp3info");
	}



if ($cdinfo == "yes")
{
$chk['cdinfo'] = file_exists("/usr/bin/cdinfo");
if ($chk['cdinfo'] == false)
	{
	$chk['cdinfo'] = file_exists("/usr/local/bin/cdinfo");
	}
$chk['cd-discid'] = file_exists("/usr/bin/cd-discid");
if ($chk['cd-discid'] == false)
	{
	$chk['cd-discid'] = file_exists("/usr/local/bin/cd-discid");
	}
}

if ($oggenc == "no" && $prefc == "ogg")
	{
	$chk['prefc'] = false;
	}


if ($bashplayer == "yes")
{
    if ($b_player == "mpg123")
    {
    $chk['mpg123'] = file_exists("/usr/bin/mpg123");
    }    
    if ($b_player == "mplayer")
    {
    $chk['mplayer'] = file_exists("/usr/bin/mplayer");
    }
}

#########################################

	# check if there were errors
	$a=false; foreach($chk as $e=>$value) {
		if (!$value) { $a = true; break; }
	}

	# some errors where found
#	if ($a) {

print_header ("settings", "", "$cgi_dir", "$char_set", $output135, $output136, $output137, $output138);

?>





<body>



<table cellspacing="10">
<tr><td colspan="3"><br><b>System</b></td></tr>


<tr>
<td valign="top">MP3-Directory read- & writable</td>
<td valign="top"><?php echo (($chk['mp3rw'])?"<img src=\"img/icon_activate.gif\" alt=\"\">":"<img src=\"img/icon_cancel.gif\" alt=\"\">"); ?></td>
<td valign="top"><?php if (!$chk['mp3rw']): print "$output042" ?><?php endif; ?></td>
</tr>

<tr>
<td valign="top">Cover-Directory read- & writable</td>
<td valign="top"><?php echo (($chk['coverrw'])?"<img src=\"img/icon_activate.gif\" alt=\"\">":"<img src=\"img/icon_cancel.gif\" alt=\"\">"); ?></td>
<td valign="top"><?php if (!$chk['coverrw']): print "$output043" ?><?php endif; ?></td>
</tr>

<tr>
<td valign="top">Temp-Directory read- & writable</td>
<td valign="top"><?php echo (($chk['tmprw'])?"<img src=\"img/icon_activate.gif\" alt=\"\">":"<img src=\"img/icon_cancel.gif\" alt=\"\">"); ?></td>
<td valign="top"><?php if (!$chk['tmprw']): print "$output044" ?><?php endif; ?></td>
</tr>

<tr>
<td valign="top">PHP-Version:<?php  echo" ".$PHPVersion; ?></td>
<td valign="top"><?php echo (($phpver)?"<img src=\"img/icon_activate.gif\" alt=\"\">":"<img src=\"img/icon_cancel.gif\" alt=\"\">"); ?></td>
<td valign="top"><?php if (!$phpver): print "$output093" ?><?php endif; ?></td>
</tr>

<tr><td colspan="3"><br><b>php.ini</b></td></tr>
<tr>
<td valign="top">register_globals=<?php if(ini_get("register_globals") == "1")
	print "ON";
	else
	print "OFF"; ?></td>
<td valign="top">
<?php
#echo "<img src=$registerglobals alt=\"\">"; 
?>
</td>
<td valign="top"></td>
</tr>

<tr>
<td valign="top">register_long_arrays=<?php if(ini_get("register_long_arrays") == "1")
	print "ON";
	else
	print "OFF"; ?></td>
<td valign="top">
<?php
#echo "<img src=$registerlongarrays alt=\"\">"; 
?>
</td>
<td valign="top"></td>
</tr>

<tr>
<td valign="top">upload_max_filesize=<?php 
echo "$uploadmaxfilesize";
echo "M";
 ?></td>
<td valign="top"><?php echo "<img src=$uploadmaxfilesize_ico alt=\"\">"; ?></td>
<td valign="top"></td>
</tr>

<tr>
<td valign="top">post_max_size=<?php 
echo "$postmaxsize";
echo "M";
 ?></td>
<td valign="top"><?php echo "<img src=$postmaxsize_ico alt=\"\">"; ?></td>
<td valign="top"></td>
</tr>

<tr>
<td valign="top">max_execution_time=<?php 
echo "$maxexecutiontime";
 ?></td>
<td valign="top"><?php echo "<img src=$maxexecutiontime_ico alt=\"\">"; ?></td>
<td valign="top"></td>
</tr>


<tr><td colspan="3"><br><b>MySQL</b></td></tr>
<tr>
<td valign="top">MySQL Database access</td>
<td valign="top"><?php echo (($chk['mysqlaccess'])?"<img src=\"img/icon_activate.gif\" alt=\"\">":"<img src=\"img/icon_cancel.gif\" alt=\"\">"); ?></td>
<td valign="top"><?php if (!$chk['mysqlaccess']): print "$output040<br>$output041" ?><?php endif; ?></td>
</tr>



<?php
if ($read_CD == "yes")
{
print "<tr><td colspan=\"3\"><br><b>";
print "$output078";
print "</b></td></tr>";

print "<tr><td valign=\"top\">ripper</td><td valign=\"top\">";
echo (($chk['ripper'])?"<img src=\"img/icon_activate.gif\" alt=\"\">":"<img src=\"img/icon_cancel.gif\" alt=\"\">"); 
print "</td><td valign=\"top\">";
?>
<?php if (!$chk['ripper']): print "<b>$ripper</b>$output037" ?>
<?php endif; 
print "</td></tr>";
?>

<?php
print "<tr><td valign=\"top\">encoder</td><td valign=\"top\">";
echo (($chk['encoder'])?"<img src=\"img/icon_activate.gif\" alt=\"\">":"<img src=\"img/icon_cancel.gif\" alt=\"\">"); 
print "</td><td valign=\"top\">";
?>
<?php if (!$chk['encoder']): print "<b>$encoder</b>$output037" ?>
<?php endif; 
print "</td></tr>";
}
?>


<?php
if ($oggenc == "yes" && $read_CD == "yes")
	{
	print "<tr><td valign=\"top\">oggenc</td><td valign=\"top\">";
	echo (($chk['oggenc'])?"<img src=\"img/icon_activate.gif\" alt=\"\">":"<img src=\"img/icon_cancel.gif\" >");
	print "</td><td valign=\"top\">";
	if (!$chk['oggenc'])
		{
		print "<b>oggenc</b>";
		print "$output037";
		}
	print "</td></tr>";
	}
?>

<?php
if ($opusenc == "yes" && $read_CD == "yes")
	{
	print "<tr><td valign=\"top\">opusenc</td><td valign=\"top\">";
	echo (($chk['opusenc'])?"<img src=\"img/icon_activate.gif\" alt=\"\">":"<img src=\"img/icon_cancel.gif\" >");
	print "</td><td valign=\"top\">";
	if (!$chk['opusenc'])
		{
		print "<b>opusenc</b>";
		print "$output037";
		}
	print "</td></tr>";
	}
?>

<?php
if ($flacenc == "yes" && $read_CD == "yes")
	{
	print "<tr><td valign=\"top\">flac</td><td valign=\"top\">";
	echo (($chk['flacenc'])?"<img src=\"img/icon_activate.gif\" alt=\"\">":"<img src=\"img/icon_cancel.gif\" >");
	print "</td><td valign=\"top\">";
	if (!$chk['flacenc'])
		{
		print "<b>flac</b>";
		print "$output037";
		}
	print "</td></tr>";
	}
?>

<?php
if ($ffmpeg == "yes" && $read_CD == "yes")
	{
	print "<tr><td valign=\"top\">ffmpeg</td><td valign=\"top\">";
	echo (($chk['ffmpeg'])?"<img src=\"img/icon_activate.gif\" alt=\"\">":"<img src=\"img/icon_cancel.gif\" >");
	print "</td><td valign=\"top\">";
	if (!$chk['ffmpeg'])
		{
		print "<b>ffmpeg</b>";
		print "$output037";
		}
	print "</td></tr>";
	}
?>

<?php
if ($flacenc == "yes" && $read_CD == "yes")
	{
	print "<tr><td valign=\"top\">metaflac</td><td valign=\"top\">";
	echo (($chk['metaflac'])?"<img src=\"img/icon_activate.gif\" alt=\"\">":"<img src=\"img/icon_cancel.gif\" >");
	print "</td><td valign=\"top\">";
	if (!$chk['metaflac'])
		{
		print "<b>metaflac</b>";
		print "$output037";
		}
	print "</td></tr>";
	}
?>

<?php
if ($oggenc == "yes" AND $id3tag == "yes")
	{
	print "<tr><td valign=\"top\">ogginfo</td><td valign=\"top\">";
	echo (($chk['ogginfo'])?"<img src=\"img/icon_activate.gif\" alt=\"\">":"<img src=\"img/icon_cancel.gif\" >");
	print "</td><td valign=\"top\">";
	if (!$chk['ogginfo'])
		{
		print "<b>ogginfo</b>";
		print "$output037";
		}
	print "</td></tr>";
	}
?>


<?php
if ($id3tag == "yes")
	{
	print "<tr><td valign=\"top\">mp3info</td><td valign=\"top\">";
	echo (($chk['mp3info'])?"<img src=\"img/icon_activate.gif\" alt=\"\">":"<img src=\"img/icon_cancel.gif\" >");
	print "</td><td valign=\"top\">";
	if (!$chk['mp3info'])
		{
		print "<b>mp3info</b>";
		print "$output037";
		}
	print "</td></tr>";
	}
?>


<?php
if ($cdinfo == "yes" && $read_CD == "yes")
	{
	print "<tr><td valign=\"top\">cdinfo</td><td valign=\"top\">";
	echo (($chk['cdinfo'])?"<img src=\"img/icon_activate.gif\" alt=\"\">":"<img src=\"img/icon_cancel.gif\" >");
	print "</td><td valign=\"top\">";
	if (!$chk['cdinfo'])
		{
		print "<b>cdinfo</b>";
		print "$output037";
		}
	print "</td></tr>";
	}
?>

<?php
if ($cdinfo == "yes" && $read_CD == "yes")
	{
	print "<tr><td valign=\"top\">cd-discid</td><td valign=\"top\">";
	echo (($chk['cd-discid'])?"<img src=\"img/icon_activate.gif\" alt=\"\">":"<img src=\"img/icon_cancel.gif\" >");
	print "</td><td valign=\"top\">";
	if (!$chk['cd-discid'])
		{
		print "<p><b>cd-discid</b>";
		print "$output037<br>";
		print "<font size=-1>abcde-tools</font></p>";
		}
	print "</td></tr>";
	}
?>

<?php
if ($oggenc == "no" && $prefc == "ogg")
	{
	print "<tr><td valign=\"top\">prefered audio format</td><td valign=\"top\">";
	echo (($chk['prefc'])?"<img src=\"img/icon_activate.gif\" alt=\"\">":"<img src=\"img/icon_cancel.gif\" >");
	print "</td><td valign=\"top\">";
	if (!$chk['prefc'])
		{
		print "$output045";
		}
	print "</td></tr>";
	}

?>

<?php
if ($bashplayer == "yes" && $b_player == "mpg123")
	{
	print "<tr><td valign=\"top\">mpg123</td><td valign=\"top\">";
	echo (($chk['mpg123'])?"<img src=\"img/icon_activate.gif\" alt=\"\">":"<img src=\"img/icon_cancel.gif\" >");
	print "</td><td valign=\"top\">";
	if (!$chk['mpg123'])
		{
		print "<b>$b_player</b>$output037";
		}
	print "</td></tr>";
	}

?>
<?php
if ($bashplayer == "yes" && $b_player == "mplayer")
	{
	print "<tr><td valign=\"top\">mplayer</td><td valign=\"top\">";
	echo (($chk['mplayer'])?"<img src=\"img/icon_activate.gif\" alt=\"\">":"<img src=\"img/icon_cancel.gif\" >");
	print "</td><td valign=\"top\">";
	if (!$chk['mplayer'])
		{
		print "<b>$b_player</b>$output037";
		}
	print "</td></tr>";
	}

?>



</table>


</body>
</html>
<?php

		exit;

?>