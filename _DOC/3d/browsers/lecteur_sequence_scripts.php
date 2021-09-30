<?php
/***************************************/
// PROGRAMME..... moteur de visualisation 3D
// MODULE........ lecture incrémentale de scripts.
// AUTEUR........ N.Dupont-Bloch
// DATE.......... 7/10/2003
// VERSION....... 1.0
// PARAMETRES.... dx, dy, dz: translation observateur en degrés.
//                da, db, dg: rotation observateur en degrés.
//                fil: booléen:   vue filaire / vue faces cachées.
//                imge: mode d'éclairage de la scène.
//                imgx, imgy: largeur et hauteur de l'image.
//                imgf: générer une image "jpg" / "png".
//                imgs: force le surlignage des faces.
//                imgz: zoom.
//                fin:  fichier texte de commandes de construction d'image.
//                fout: fichier jpg ou png de sortie.
//                scn:  no de la scène. Utilise alors p1 comme no d'image.
//                dbsrv:nom ou @ IP du serveur de SGBD (MySql, ...).
//                p1: paramètre libre, pour application.
/***************************************/

/*
require('3d_config.inc.php');
require('3d_lib.php');
require('3d_fic.php');
require('3d_database.inc.php');
*/
?>

<html>

<head>
<meta http-equiv="Content-Type"
content="text/html; charset=iso-8859-1">
<title>3D-Lecteur incrémental de scripts 1.0</title>
</head>

<body onload="tourner()">
<script language="JavaScript">
var i = 0;

function tourner() {
   if (i < document.form.img_debut.value) {
      i = document.form.img_debut.value;
      }
   i++;
   if (i > document.form.img_fin.value) {
      i = document.form.img_debut.value;
      }
   fin = './anims/' +document.form.script.value + i +".3da";
   port = document.form.port.value;
   url = "http://localhost:" +port +"/3d_v5/3d_aff.php?dx=0&dy=0&dz=20&da=0&db=0&dg=0&imgx=640&imgy=360&imgf=png&imgz=350&imge=scn&fil=false&imgs=false" +document.form.eclairage.value +document.form.filaire.value +document.form.surlignage.value +"&fin="+fin;


   document.images[0].src = url;
   document.form.url.value = url;
   timerID=setTimeout("tourner()", document.form.intervalle.value);
   }
</script>

<p><img src="0.jpg"> </p>

<form name="form">
    <table border="0" bgcolor="#7EABFE">
        <tr>
            <td><font face="Arial">port</td>
            <td><font face="Arial"><input type="text" size="10" name="port" value="81"></td>
        </tr>
        <tr>
            <td><font face="Arial">script</td>
            <td><font face="Arial"><input type="text" size="10" name="script"></td>
        </tr>
        <tr>
            <td><font face="Arial">lecture</td>
            <td><font face="Arial">
              de<input type="text" size="3" value="1" name="img_debut">
              à<input type="text" size="3" value="1" name="img_fin">
              1 image/<input type="text" size="5" name="intervalle"
            value="6000" />ms</td>
        </tr>
        <tr>
            <td><font face="Arial">représentation</td>
            <td><font face="Arial">
               <select name="filaire" size="1">
                <option selected value="&amp;fil=false">faces cachées</option>
                <option value="&amp;fil=true">filaire</option>
            </select> </td>
        </tr>
        <tr>
            <td><font face="Arial">surlignage</td>
            <td><font face="Arial">
                <select name="surlignage" size="1">
                <option selected value="&amp;imgs=false">selon les faces</option>
                <option value="&amp;imgs=true">toujours</option>
            </select></td>
        </tr>
        <tr>
            <td><font face="Arial">éclairage</td>
            <td><font face="Arial">
                <select name="eclairage" size="1">
                <option selected value="&amp;imge=scn">sources de lumière</option>
                <option value="&amp;imge=std">éclairage standard</option>
                <option value="&amp;imge=off">aucun</option>
            </select></td>
        </tr>
        <tr>
            <td><font face="Arial">URL</td>
            <td><font face="Arial"><input type="text" size="30" name="url"></td>
        </tr>
    </table>
</form>
</body>
</html>
