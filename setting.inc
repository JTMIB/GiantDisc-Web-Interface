<?php

###########################################################################
# prefered format
$prefc = "m4a";
#$prefc = "mp3";
#$prefc = "ogg";
#$prefc = "flac";
#$prefc = "opus";
#$prefc = "m4a";

###########################################################################
# Sprache - language
$language = "de";
require "german_utf8.inc";
#$language = "de";
#$language = "en";

###########################################################################
# Browser Zeichensatz
$char_set = "UTF-8";

###########################################################################
# MP3-Bitrate
$mp3_Q = "8";

###########################################################################
# OGG-Bitrate/Qualität
$ogg_Q = "9";

###########################################################################
# FLAC-Comprression
$flac_Q = "8";

###########################################################################
# OPUS-Bitrate
$opus_Q = "4";

###########################################################################
# M4A-Bitrate
$m4a_Q = "6";

###########################################################################
# CD-Laufwerk - CD drive
# Auf no stellen, wenn kein CD-Laufwerk vorhanden ist.
# Wenn dieser Parameter auf no steht, dann wird die Prüfung der entsprechenden Softwaren ausgeschaltet.
$read_CD = "yes";

###########################################################################
# verwendetes Geraet - device used
$dev_cd = "/dev/sr1";

?>
