#!/usr/bin/env python3
# -*- coding: utf-8 -*-
# Webserver, welcher auf Port 8080 lauscht
# Mit dem Befehl "/shutdown" wird der Server heruntergefahren
# Damit dies moeglich ist, muss in mit "sudo visudo" folgende Zeile eingefuegt werden
# www-data ALL=(ALL) NOPASSWD: /usr/sbin/shutdown
#
# Script im Hintergrund starten
# nohup python3 /var/www/html/music/shutdown_server.py &
#
# oder automatisch
# sudo nano /etc/systemd/system/shutdown_server.service
# sudo systemctl daemon-reload
# sudo systemctl enable shutdown_server.service
# sudo systemctl start shutdown_server.service
#
# Autor: Juergen Thoens
# eMail: juergen.thoens@gmx.de
# Datum: 26.08.2026

from http.server import BaseHTTPRequestHandler, HTTPServer
import os

class ShutdownHandler(BaseHTTPRequestHandler):
    def do_GET(self):
        if self.path == '/shutdown':
            self.send_response(200)
            self.end_headers()
            self.wfile.write(b"Server faehrt herunter...")
            os.system("sudo shutdown -h now")
        else:
            self.send_response(404)
            self.end_headers()
            self.wfile.write(b"Seite nicht gefunden. Nutze /shutdown")

def run():
    server_address = ('', 8080)
    httpd = HTTPServer(server_address, ShutdownHandler)
    httpd.serve_forever()

if __name__ == '__main__':
    run()
