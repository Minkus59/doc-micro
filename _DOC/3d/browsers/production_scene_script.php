<?php
/***************************************/
// PROGRAMME..... moteur de visualisation 3D
// MODULE........ production d'une scène: les images scripts (*.3da) sont calculées et affichées et enregistrées.
// AUTEUR........ N.Dupont-Bloch
// DATE.......... 14/10/2003
// VERSION....... 1.2
// PARAMETRES.... 
/***************************************/

require('../3d_config.inc.php');
require('../3d_fic.php');
require('../3d_database.inc.php');
$params = new Parametres();
?>

<html>

<head>
<meta http-equiv="Content-Type"
content="text/html; charset=iso-8859-1">
<title>3D-Production de scène par script (1.2)</title>
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
   nom_image = new String("");
   nom_image = i;
   if      (nom_image < 10)    { nom_image = "000" + nom_image;  }
   else if (nom_image < 100)   { nom_image = "00"  + nom_image;  }
   else if (nom_image < 1000)  { nom_image = "0"   + nom_image;  }

   nom_image = document.form.scene.value +'-' +nom_image +".3da";
   url  = "http://" +document.form.serveur_moteur_3d.value;
   url += "/3d_v5/3d_aff.php?dx=0&dy=0&dz=20&da=0&db=0&dg=0";
   url += "&imgz=350";
   url += "&imgx=150&imgy=50";
   url += "&imge=" +document.form.eclairage.value;
   url += "&fil=" +document.form.filaire.value;
   url += "&imgf=png";
   url += "&imgs=" +document.form.surlignage.value;
   url += "&fin="  +nom_image;

   document.images[0].src = url;
   document.form.url.value = url;
   timerID=setTimeout("tourner()", document.form.intervalle.value);
   }
</script>

<p><img src="0.jpg"> </p>

<form name="form">
    <table border="0" bgcolor="<?php echo $params->coul_fond3; ?>">
        <tr>
            <td><font face="Arial">serveur_moteur_3d</td>
            <td><font face="Arial"><input type="text" size="10" name="serveur_moteur_3d" value="<?php echo $params->serveur_moteur_3d; ?>"></td>
        </tr>
        <tr><td width="140"><font face="Arial">scene</font></td>
            <td><font face="Arial"><input type="text" size="3" name="scene"></td>
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
            <td><font face="Arial"><input type="text" size="100" name="url"></td>
        </tr>
    </table>
</form>
</body>
</html>
