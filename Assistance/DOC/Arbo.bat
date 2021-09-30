@echo off
set /p nomPc=Veuillez indiquez le nom de l'ordinateur ?
set /p lecteur=Quelle disque dur souhaitez vous scanner ? (exemple= C) 
set /p lettre=Lettre du lecteur de sauvegarde ? (exemple= C)
tree %lecteur%: /F /A > %lettre%:\Arbo_%nomPC%_%lecteur%.txt
echo Merci, operation termine
pause

