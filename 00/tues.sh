#
#/usr/bin/cdparanoia -d /dev/sr1 -w 1 /var/www/html/music/00/track01.wav
#/usr/bin/cdparanoia -d /dev/sr1 -w 2 /var/www/html/music/00/track02.wav
#/usr/bin/cdparanoia -d /dev/sr1 -w 3 /var/www/html/music/00/track03.wav
#/usr/bin/cdparanoia -d /dev/sr1 -w 4 /var/www/html/music/00/track04.wav
#/usr/bin/cdparanoia -d /dev/sr1 -w 5 /var/www/html/music/00/track05.wav
#/usr/bin/cdparanoia -d /dev/sr1 -w 6 /var/www/html/music/00/track06.wav
#/usr/bin/cdparanoia -d /dev/sr1 -w 7 /var/www/html/music/00/track07.wav
#/usr/bin/cdparanoia -d /dev/sr1 -w 8 /var/www/html/music/00/track08.wav
#/usr/bin/cdparanoia -d /dev/sr1 -w 9 /var/www/html/music/00/track09.wav
#/usr/bin/cdparanoia -d /dev/sr1 -w 10 /var/www/html/music/00/track10.wav

ffmpeg -i /var/www/html/music/00/track01.wav -c:a alac -metadata album="Entspannen und Wohlfühlen!" -metadata artist="MERCK" -metadata title="Refresh" -metadata date="2011" -metadata track="1" /var/www/html/music/00/track01.m4a
ffmpeg -i /var/www/html/music/00/track02.wav -c:a alac -metadata album="Entspannen und Wohlfühlen!" -metadata artist="MERCK" -metadata title="So well" -metadata date="2011" -metadata track="2" /var/www/html/music/00/track02.m4a
ffmpeg -i /var/www/html/music/00/track03.wav -c:a alac -metadata album="Entspannen und Wohlfühlen!" -metadata artist="MERCK" -metadata title="Secrets of silence" -metadata date="2011" -metadata track="3" /var/www/html/music/00/track03.m4a
ffmpeg -i /var/www/html/music/00/track04.wav -c:a alac -metadata album="Entspannen und Wohlfühlen!" -metadata artist="MERCK" -metadata title="Dreamland" -metadata date="2011" -metadata track="4" /var/www/html/music/00/track04.m4a
ffmpeg -i /var/www/html/music/00/track05.wav -c:a alac -metadata album="Entspannen und Wohlfühlen!" -metadata artist="MERCK" -metadata title="Touch the flow" -metadata date="2011" -metadata track="5" /var/www/html/music/00/track05.m4a
ffmpeg -i /var/www/html/music/00/track06.wav -c:a alac -metadata album="Entspannen und Wohlfühlen!" -metadata artist="MERCK" -metadata title="Feel the nature" -metadata date="2011" -metadata track="6" /var/www/html/music/00/track06.m4a
ffmpeg -i /var/www/html/music/00/track07.wav -c:a alac -metadata album="Entspannen und Wohlfühlen!" -metadata artist="MERCK" -metadata title="Cloud No. 9" -metadata date="2011" -metadata track="7" /var/www/html/music/00/track07.m4a
ffmpeg -i /var/www/html/music/00/track08.wav -c:a alac -metadata album="Entspannen und Wohlfühlen!" -metadata artist="MERCK" -metadata title="Take care" -metadata date="2011" -metadata track="8" /var/www/html/music/00/track08.m4a
ffmpeg -i /var/www/html/music/00/track09.wav -c:a alac -metadata album="Entspannen und Wohlfühlen!" -metadata artist="MERCK" -metadata title="Silent creek" -metadata date="2011" -metadata track="9" /var/www/html/music/00/track09.m4a
ffmpeg -i /var/www/html/music/00/track10.wav -c:a alac -metadata album="Entspannen und Wohlfühlen!" -metadata artist="MERCK" -metadata title="Take your time" -metadata date="2011" -metadata track="10" /var/www/html/music/00/track10.m4a


