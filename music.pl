#!/usr/bin/perl -w
use warnings;
print "Content-type: text/html\r\n\r\n";

# letzte Aenderung am 26.09.2026 durch Juergen Thoens

#erwartete Variablen:
#file=
#com=
#uri=

$zeile = $ENV{"QUERY_STRING"};
@words = split(/&/, $zeile);

#erste Variable filtern
#file
if($words[0] =~ /file=/)
	{
	$temp = $words[0];
	@zuwei = split(/=/, $temp);
	$file = $zuwei[1];
	}

#zweite Variable filtern
#com
if($words[1] =~ /com=/)
        {
        $temp = $words[1];
        @zuwei = split(/=/, $temp);
        $com = $zuwei[1];
        }

#dritte Variable filtern
#uri
if($words[2] =~ /uri=/)
        {
        $temp = $words[2];
        @zuwei = split(/=/, $temp);
        $uri = $zuwei[1];
        }


###################################
# Spielezeit feststellen
if( $com =~ m/playtime/ ) {

#weitere Variablen
$x = 0;
$a = 0;
$z = 0;
$TheFile = "/tmp/mp3info.txt";
#$TheFile = "/var/www/html/music/00/mp3info.txt";
if ($file =~ m/.mp3/)
	{
	system("mp3info -x $file > $TheFile");
	}
if ($file =~ m/.ogg/)
	{
	system("ogginfo $file > $TheFile");
	}
if ($file =~ m/.opus/)
  	{
  	system("opusinfo $file > $TheFile");
  	}
if ($file =~ m/.m4a/)
  	{
  	system("ffmpeg -i $file 2> $TheFile");
  	}

# Datei einlesen
open(INFILE, $TheFile);
@B = <INFILE>;
while($Line = shift(@B))
 {
 $Zeile[$a] = $Line;
#print "$Zeile[$a]";
# bei OGG
 if( $Zeile[$a] =~ m/Playback length:/ && $file =~ m/.ogg/)
 	{
	$lang = $Zeile[$a];
#	print "$lang";
	}
# bei OPUS
 if( $Zeile[$a] =~ m/Playback length:/ && $file =~ m/.opus/)
 	{
	$lang = $Zeile[$a];
#	print "$lang";
	}
# bei M4A
	# if( $Zeile[$a] =~ m/Duration:/ && $file =~ m/.m4a/)
 if( $file =~ m/.m4a/)
 	{
	$lang = $Zeile[$a];
	#	print "$lang";
	}	
 $a++;
 }

if ($file =~ m/.mp3/)
	{
	$b = $a-2;
	$lang = $Zeile[$b];
	@time = split(/ /, $lang);
	$k = length($time[0]);
	$k = $k + index($lang, " ") - 1;
	$length = substr($lang, $k);
	}

if ($file =~ m/.ogg/)
	{
	@time = split(/ /, $lang);
#	print "$time[2]";
  $zeit = $time[2];
	@laenge = split(/:/, $zeit);
	$min = "$laenge[0]";
	$k = length($min);
	$min = substr($min, 0, $k-1);
	$sec = "$laenge[1]";
  $sec = substr($sec, 0, 6);
  $length = $min * 60 + $sec;
#  $length = $min . $sec;
#  print $length;
	}

if ($file =~ m/.opus/)
	{
	@time = split(/ /, $lang);
#	print "$time[2]";
  $zeit = $time[2];
	@laenge = split(/:/, $zeit);
	$min = "$laenge[0]";
	$k = length($min);
	$min = substr($min, 0, $k-1);
	$sec = "$laenge[1]";
  $sec = substr($sec, 0, 6);
  $length = $min * 60 + $sec;
#  $length = $min . $sec;
#	 print $length;
	}
close(INFILE);

if ($file =~ m/.m4a/)
	{
	open(INFILE, $TheFile);
	@B = <INFILE>;

	while($Line = shift(@B))
 		{
 		$Zeile[$z] = $Line;
 		
		if ($Zeile[$z] =~ m/Duration:/)
			{	
#			print "$z $Zeile[$z]<br>";
			$time = $Zeile[$z];
			print "$time <br>";
			@Zeit = split(/ /, $time);
			$Zeit = $Zeit[3];
			# Komma entfernen
			chop($Zeit);
#			print "$Zeit <br>";
			@time = split(/:/, $Zeit);
#			print "$time[1] <br>";
			$sec = $time[0] * 60 * 60 + $time[1] * 60 + $time[2];
#			print "$sec <br>";
			$length = $sec;
			}

		$z++;
 		}		
	close(INFILE);
	}
	
print "<html><head>";
print "<META HTTP-EQUIV='Refresh' CONTENT='1\; URL=$uri?file=$file&com=$com&length=$length'>\r\n";
print "</head>";
print "<body>";

print $uri;
print "<br>";
print $com;
print "<br>";
print $file;
print "<br>";
#print "Hallo<br>";

print "<pre>";
while($x < $a)
 {
 print $Zeile[$x];
 print "<br>";
 $x++;
 }
print "</pre>";

print "</body></html>";
}


###################################
# CD-Fach oeffnen oder schliessen
if( $com =~ m/tray/ ) {

if( $com =~ m/opentray/) {
	system("eject $file")
	}
if( $com =~ m/closetray/) {
	system("eject $file -t")
	}

print "<html><head>";
print "<META HTTP-EQUIV='Refresh' CONTENT='1\; URL=$uri'>\r\n";
print "</head>";
print "<body>";

print "$uri<br>$com<br>$file";

print "</body></html>";
}

###################################
# CDID feststellen
if( $com =~ m/readcd/ ) {

#fuer Ubuntu
system("/usr/bin/cd-discid $file > /var/www/html/music/00/sourceid.inf");
# fuer OpenSuSe
#system("/usr/bin/cd-discid $file > /srv/www/htdocs/music/00/sourceid.inf");
#system("/usr/bin/cd-discid /dev/sr0 > /srv/www/htdocs/music/00/sourceid.inf");
#system("/usr/bin/cd-discid /dev/sr1 > /srv/www/htdocs/music/00/sourceid.inf");
#system("rm /srv/www/htdocs/music/00/sourceid.*");
# Datei einlesen
$TheFile = "/var/www/html/music/00/sourceid.inf";
$a = 0;
$x = 0;
$t = 0;
open(INFILE, $TheFile);
@B = <INFILE>;
while($Line = shift(@B))
 {
 $Zeile[$a] = $Line;
 $a++;
 }
close(INFILE);

if ($a > 0)
{
$reihe = $Zeile[0];
@words = split(/ /, $reihe);
 $temp = $words[0];
 @zuwei = split(/= /, $temp);
 $sourceid = $zuwei[0];
 $temp = $words[1];
 @zuwei = split(/= /, $temp);
 $t = $zuwei[0];

}
else
{

system("/usr/local/bin/cdda2wav -D $file -I cooked_ioctl -t 1 -d 1f /var/www/html/music/00/sourceid.wav");
#system("/usr/bin/cdda2wav -D $file -I cooked_ioctl -t 1 -d 1f /srv/www/htdocs/music/00/sourceid.wav");
#system("/usr/local/bin/cdda2wav -D $file -t 1 /srv/www/htdocs/music/00/sourceid.wav -d 1");

# Datei einlesen
$TheFile = "/var/www/html/music/00/sourceid.inf";
$secFile = "/var/www/html/music/00/sourceid.cddb";
$a = 0;
$x = 0;
$t = 0;
open(INFILE, $TheFile);
@B = <INFILE>;
while($Line = shift(@B))
 {
 $Zeile[$a] = $Line;
 $a++;
 }
close(INFILE);
open(INFILE, $secFile);
@B = <INFILE>;
while($Line = shift(@B))
 {
 $Zeile[$a] = $Line;
 $a++;
 }
close(INFILE);

# discid suchen
while($x < $a)
 {
 if( $Zeile[$x] =~ m/CDDB discid/ ) {
 $sourceid = $Zeile[$x];
 }
 $x++;
 }
$x = 0;

@words = split(/&/, $sourceid);

#if($words[0] =~ /file=/)
# {
 $temp = $words[0];
 @zuwei = split(/= /, $temp);
 $sourceid = $zuwei[1];
#}


# Anzahl der Tracks feststellen
while($x < $a)
 {
 if( $Zeile[$x] =~ m/TTITLE/ ) {
 $t++;
  }
 $x++;
 }
$x = 0;

}
system("rm /var/www/html/music/00/sourceid.*");

print "<html><head>";
print "<META HTTP-EQUIV='Refresh' CONTENT='1\; URL=$uri?schritt=1&sourceid=$sourceid&anz_track=$t'>\r\n";
print "</head>";
print "<body>";

print $sourceid;
print "<br>";
print $t;
print "<br>";

#print "$uri<br>$com<br>$file<br>";

print "<pre>";
while($x < $a)
 {
 print $Zeile[$x];
 print "<br>";
 $x++;
 }
print "</pre>";

print "</body></html>";
}

###################################
# pruefen ob CD gelesen wird
# CD Einlesen starten
if( $com =~ m/ripit/ ) {

system("/usr/bin/ps -ef | /usr/bin/grep cdparanoia > /var/www/html/music/00/rip.txt 2>&1");
system("/usr/bin/ps -ef | /usr/bin/grep cdda2wav >> /var/www/html/music/00/rip.txt 2>&1");
system("/usr/bin/ps -ef | /usr/bin/grep lame >> /var/www/html/music/00/rip.txt 2>&1");
system("/usr/bin/ps -ef | /usr/bin/grep oggenc >> /var/www/html/music/00/rip.txt 2>&1");
system("/usr/bin/ps -ef | /usr/bin/grep flac >> /var/www/html/music/00/rip.txt 2>&1");
system("/usr/bin/ps -ef | /usr/bin/grep opusenc >> /var/www/html/music/00/rip.txt 2>&1");
system("/usr/bin/ps -ef | /usr/bin/grep ffmpeg >> /var/www/html/music/00/rip.txt 2>&1");
system("sleep 2");
system("/usr/bin/ps -ef | /usr/bin/grep cdparanoia >> /var/www/html/music/00/rip.txt 2>&1");
system("/usr/bin/ps -ef | /usr/bin/grep cdda2wav >> /var/www/html/music/00/rip.txt 2>&1");
system("/usr/bin/ps -ef | /usr/bin/grep lame >> /var/www/html/music/00/rip.txt 2>&1");
system("/usr/bin/ps -ef | /usr/bin/grep oggenc >> /var/www/html/music/00/rip.txt 2>&1");
system("/usr/bin/ps -ef | /usr/bin/grep flac >> /var/www/html/music/00/rip.txt 2>&1");
system("/usr/bin/ps -ef | /usr/bin/grep opusenc >> /var/www/html/music/00/rip.txt 2>&1");
system("/usr/bin/ps -ef | /usr/bin/grep ffmpeg >> /var/www/html/music/00/rip.txt 2>&1");

#print "<html><head>";
#print "<META HTTP-EQUIV='Refresh' CONTENT='5\; URL=$uri?schritt=4'>\r\n";
#print "</head>";
#print "<body>";

if( $file =~ m/watch/ ) {

  # Datei einlesen
  $TheFile = "/var/www/html/music/00/rip.txt";
  #$secFile = "/srv/www/htdocs/music/00/sourceid.cddb";
  $a = 0;
  $x = 0;
  #$t = 0;
  open(INFILE, $TheFile);
  @B = <INFILE>;
  while($Line = shift(@B))
   {
   $Zeile[$a] = $Line;
   $a++;
   if( $Line =~ m/track/ ) {
       $x++;
       }

   }
  close(INFILE);

  print "<html><head>";
  if( $x > 0 ) {
    # in arbeit
    print "<META HTTP-EQUIV='Refresh' CONTENT='1\; URL=$uri?schritt=5'>\r\n";
    }
  else
    {
    # alles fertig
    print "<META HTTP-EQUIV='Refresh' CONTENT='1\; URL=$uri?schritt=6'>\r\n";
    }
  print "</head>";
  print "<body>";


#  print "<br>$x";
  # german
  print "<p>Moment bitte</p>";
  # english
  print "<p>One moment please</p>";
  # frensh
  print "<p>Un petit moment</p>";
  # spain
  print "<p>Un momento por favor</p>";
  print "</body></html>";
  }

if( $file =~ m/start/ ) {
#    system("at -f /srv/www/htdocs/music/00/ripit.sh -q v now");
#   wird jetzt in PHP gestartet: exec("/srv/www/htdocs/music/00/ripit.sh > /dev/null &");
    system("sleep 2");
    system("/usr/bin/ps -ef | /usr/bin/grep cdparanoia > /var/www/html/music/00/rip.txt 2>&1");
    system("/usr/bin/ps -ef | /usr/bin/grep cdda2wav >> /var/www/html/music/00/rip.txt 2>&1");
    system("/usr/bin/ps -ef | /usr/bin/grep lame >> /var/www/html/music/00/rip.txt 2>&1");
    system("/usr/bin/ps -ef | /usr/bin/grep oggenc >> /var/www/html/music/00/rip.txt 2>&1");
    system("/usr/bin/ps -ef | /usr/bin/grep flac >> /var/www/html/music/00/rip.txt 2>&1");
    system("/usr/bin/ps -ef | /usr/bin/grep opusenc >> /var/www/html/music/00/rip.txt 2>&1");
    system("/usr/bin/ps -ef | /usr/bin/grep ffmpeg >> /var/www/html/music/00/rip.txt 2>&1");

    print "<html><head>";
    print "<META HTTP-EQUIV='Refresh' CONTENT='1\; URL=$uri?schritt=5'>\r\n";
    print "</head>";
    print "<body>";
    print "<p>Einlesen wird gestartet.</p>";
    print "<p>Reading is started.</p>";
    print "<p>La lecture est lancée.</p>";
    print "<p>Se inicia la lectura.</p>";
    print "</body></html>";

    }

}

###################################

