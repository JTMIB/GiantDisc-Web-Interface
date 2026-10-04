The GiantDisc Web Interface is the successor to the GiantDisc jukebox created by Rolf Brugger in 2003.
I have continued the project on the web server side and am currently developing it further for Ubuntu Server 26.04.

What can you do with the GiantDisc web interface?

- Import almost any audio CD; cdparanoia can even rip damaged CDs
- Encode to MP3, OGG, FLAC, OPUS, or M4A
- Archive in a MariaDB (formerly MySQL) database
- Store and display lyrics
- Store album cover art
- Assign a 0- to 5-star rating to albums for filtering within the "All Albums" view
- One-click database backup and recovery

The original instructions can be found here:
https://thoens.de/?Bastelkiste___GiantDisc-Web-Interface

-----------------------------------------------------------------------------------

Beim GiantDisc Web Interface handelt es sich um die Weiterführung
der GiantDisc Jukebox von Rolf Brugger aus dem Jahre 2003.
Ich habe dieses Projekt auf Webserver-Seite weitergeführt
und aktuell für Ubuntu-Server 26.04 weiter entwickelt.

Was kann man GiantDisc Web Interface tun:

- Einlesen von fast allen Audio-CD / cdparanoia kann auch beschädigte CD's rippen
- Codieren in MP3, OGG, FLAC, OPUS, M4A
- Archivieren in einer Maria-DB (früher MySQL)
- Musiktexte hinterlegen und anzeigen
- CD-Cover für ein Album hinterlegen
- 0 bis 5 Sternchen für ein Album vergeben um in "Alle Alben" danach zu selektieren
- Datenbank Backup und Recover per Mausklick

Die orginale Anleitung findet man hier:
https://thoens.de/?Bastelkiste___GiantDisc-Web-Interface

-----------------------------------------------------------------------------------

Instructions

After the initial installation, enabling SSH, and setting a root password (sudo passwd root), the server's IP address is required—unless a static address was already assigned during installation. This address is necessary to connect via PuTTY.

ip addr show

Next, the installation is brought up to date:

apt update
apt upgrade

Next, the web server is installed:

apt install -y apache2 apache2-utils
systemctl status apache2
apache2 -v

ufw app list
ufw allow 'Apache'
ufw status

hostname -I

For the initial test, you can now enter the web server's IP address into your browser and should see the default page displayed.

Next up is MariaDB:

apt install mariadb-server mariadb-client
systemctl status mariadb
mariadb --version
mysql_secure_installation

Once this is done, you can attend to PHP:

sudo apt install php php-cli
sudo apt install php-mysql php-xml php-curl php-zip php-mbstring
sudo apt install libapache2-mod-php
sudo systemctl restart apache2
php --version

If you have multiple PHP versions installed, you can switch between the different versions using
update-alternatives --config php

To be able to view the database via a browser—and modify it if necessary—I also installed phpMyAdmin:

For this, unzip is required beforehand:

apt install unzip

wget https://www.phpmyadmin.net/downloads/phpMyAdmin-latest-all-languages.zip -O phpmyadmin.zip
unzip phpmyadmin.zip
rm phpmyadmin.zip
mv phpMyAdmin-*-all-languages phpmyadmin
chmod -R 0755 phpmyadmin

Create file:
vi /etc/apache2/conf-available/phpmyadmin.conf

Insert this content:
# phpMyAdmin Apache configuration

Alias /phpMyAdmin /usr/share/phpmyadmin

<Directory /usr/share/phpmyadmin>
Options SymLinksIfOwnerMatch
DirectoryIndex index.php
</Directory>

# Disallow web access to directories that don't need it
<Directory /usr/share/phpmyadmin/templates>
Require all denied
</Directory>
<Directory /usr/share/phpmyadmin/libraries>
Require all denied
</Directory>
<Directory /usr/share/phpmyadmin/setup/lib>
Require all denied
</Directory>

Then enter a few more commands:

a2enconf phpmyadmin
mkdir /usr/share/phpmyadmin
cp -R phpmyadmin /usr/share/
mkdir /usr/share/phpmyadmin/tmp/
chown -R www-data:www-data /usr/share/phpmyadmin/tmp/

Now restart the web server:
systemctl restart apache2

If you now enter the IP address followed by `/phpMyAdmin` in your browser, you should see the phpMyAdmin login page.

The next commands deal with setting up database access.

mysql -u root
UPDATE mysql.user SET plugin = 'mysql_native_password' WHERE user = 'root' AND plugin = 'unix_socket';
FLUSH PRIVILEGES;
exit

Set the root password for MySQL:
mysql -u root -p

User admin für phpmyadmin anlegen:
create user admin@localhost identified by 'secret';
grant all privileges on *.* to admin@localhost with grant option;
flush privileges;
exit;

You can now log in to the phpMyAdmin page using this user.

The next step is to transfer the scripts to the web server.
To do this, I switch to the web server's document root directory using `cd /var/www/html/`.
A new folder is created with `mkdir music`, followed by `chmod 777 music`.
The contents of the ZIP archive are copied into this folder using WinSCP.
Finally, the necessary permissions are set using the commands `chmod 777 -R music` and `chown nobody:nogroup -R music`.

Now for the database; to do this, I switch to the
directory: /var/www/html/music/database
Then, enter the following commands line by line:

mysql -u root -p
insert Password
DROP DATABASE IF EXISTS GiantDisc;
CREATE DATABASE GiantDisc;
use GiantDisc;
CREATE USER 'music'@'localhost' IDENTIFIED BY 'music';
select * from mysql.user;
GRANT ALL PRIVILEGES ON GiantDisc . * TO 'music'@'localhost';
FLUSH PRIVILEGES;
Exit

And the database tables are created with the following command:

mysql -u root -p < create-tables.mysql

From now on, it should be possible to view the first page of the GiantDisc web interface by visiting http://ipaddress/music.

A large number of errors are still listed here, but they will be eliminated step by step in the coming stages.

To do this, simply install the "required third-party software" using the following commands:

apt install cdparanoia
apt install lame
apt install vorbis-tools
apt install flac
apt install cdtool
apt install mp3info
apt install cd-discid
apt install opus-tool
apt install ffmpeg

Next, switch to the /usr/bin directory. Once there, execute the following commands to allow the system user www-data to launch the programs:

chmod +s cdparanoia
chmod +s lame
chmod +s oggenc
chmod +s cd-discid
chmod +s metaflac
chmod +s eject
chmod +s opusenc
chmod +s mp3info
chmod +s chmod
chmod +s chown
chmod +s ffmpeg

As of Ubuntu Server 24.04, additional adjustments are required to allow Apache 2 to execute the `eject` and `ps` commands.

To do this, create or edit the file `/etc/systemd/system/apache2.service.d/override.conf` and add the following lines:

[Service]

PrivateDevices=false
ProtectSystem=false

DeviceAllow=/dev/sr0 rwm
DeviceAllow=/dev/sr1 rwm

ProtectProc=default
ProcSubset=all

Once the permissions have been set, the next step is to install Perl:

apt-get install perl
a2enmod cgid
systemctl restart apache2

After that, the file music.pl simply needs to be copied from /var/www/html/music to /usr/lib/cgi-bin:

cd /var/www/html/music
cp music.pl /usr/lib/cgi-bin/music.pl
chmod 755 /usr/lib/cgi-bin/music.pl

Since only the red crosses for the php.ini are visible now, a few adjustments need to be made to that file. To do this, I switch to the `/etc/php/8.5/apache2` directory and edit the `php.ini` file using `vi`. The directory path will differ if you are using a different PHP version, and you are, of course, welcome to use a different editor if you prefer.

I am changing the following lines in the php.ini file:

upload_max_filesize = 150M
max_file_uploads = 50
post_max_size = 600M
max_execution_time = 90

The upload parameters are intended for uploading audio files. If you expect larger files, you can of course enter higher values ​​here. Then, finish by restarting Apache:

systemctl restart apache2

The GiantDisc web interface should now be fully functional. The only potential sticking point is accessing the CD drive. You may need to go to the "Options" – "Settings" menu to select the desired CD/DVD drive. The default setting, `/dev/cdrom`, should suffice. I personally use a retired Esprimo Q900 with a built-in CD/DVD drive; however, these drives tend to age quickly and often become unsuitable for ripping audio CDs. For this reason, I use an external USB CD/DVD drive. In my case, I use `/dev/sr1`.

-----------------------------------------------------------------------------------

I have included two buttons to allow the albums or playlists to be played.
The first button generates M3U files. The second button generates XSPF files. To ensure the Firefox browser knows how to handle them, two additional entries must be added under Settings > Applications.
On my system, M3U files launch the foobar2000 player, while XSPF files launch VLC. In principle, it is also possible to use other players.
In other browsers and on other systems, the settings are tucked away in different places. It would be boring if everything were uniform, after all.

-----------------------------------------------------------------------------------

