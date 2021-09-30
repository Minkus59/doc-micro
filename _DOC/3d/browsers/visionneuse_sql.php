<?php
/***************************************/
// PROGRAMME..... moteur de visualisation 3D
// MODULE........ visionneuse de scène: les images Sql sont calculées et affichées.
// AUTEUR........ N.Dupont-Bloch
// DATE.......... 13/10/2003
// VERSION....... 1.0
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
<title>3D-Visionneuse de scène Sql (1.0)</title>
</head>

<body>
<script language="JavaScript">
var i = 0;

function tourner() {
//--------------
   //*** génère image par image.
   if (i < document.form.img_debut.value) {
      i = document.form.img_debut.value;
      }

   url  = "http://" +document.form.serveur_moteur_3d.value;
   url += "/3d_v5/3d_aff.php";
   url += "?imge="  +document.form.eclairage.value;
   url += "&fil="   +document.form.filaire.value;
   url += "&imgf="  +document.form.format.value;
   url += "&imgs="  +document.form.surlignage.value;
   url += "&dbsrv=" +document.form.serveur_database.value;
   url += "&scn="   +document.form.scn.value +"&p1=" +i;

   //*** calcule et affiche l'image.
   document.images[0].src = url;

   // gaffe au timer.
   if (document.form.intervalle.value < 2000)
      {// Pour éviter l'engorgement. Méthode minimale.
      document.form.intervalle.value = 2000;
      }
   
   // réarme jusqu'à la dernière image ou fin de génération demandée.
   if (i < document.form.img_fin.value)
      {
      i++;
      timerID = setTimeout("tourner()", document.form.intervalle.value);
      }
   }

</script>

<p><img src="0.jpg"></p>

<FORM name="form">
    <table border="0" bgcolor="<?php echo $params->coul_fond3; ?>" cellpadding="0" cellspacing="1">
        <tr>
            <input type="hidden" size="10" name="serveur_moteur_3d" value="<?php echo $params->serveur_moteur_3d; ?>">
            <input type="hidden" size="10" name="serveur_database"  value="<?php echo $params->serveur_database; ?>">
            <td><TABLE border="0" cellpadding="0" cellspacing="1"><tr>
            <td width="140"><font face="Arial">scène</font></td>
            <td><font face="Arial"><input type="text" size="3" value="1" name="scn"></font></td>
            </tr><tr>
            <td><font face="Arial">de l'image</font></td>
            <td><font face="Arial"><input type="text" size="3" value="1" name="img_debut"></font></td>
            </tr><tr>
            <td><font face="Arial">à l'image</font></td>
            <td><font face="Arial"><input type="text" size="3" value="1" name="img_fin"></font></td>
            </tr><tr>
            <td><font face="Arial">1 image toutes les</font></td>
            <td><font face="Arial"><input type="text" size="5" name="intervalle" value="6000" /></font></td>
            <td><font face="Arial">ms</font></td>
            </tr></TABLE></td>
        </tr>
        <tr>
            <td><TABLE border="0" cellpadding="0" cellspacing="1"><tr>
            <td width="140"><font face="Arial">représentation</td>
            <td><font face="Arial">
               <select name="filaire" size="1">
                <option selected value="false">faces cachées</option>
                <option value="true">filaire</option>
            </select> </td>
            </tr></TABLE></td>
        </tr>
        <tr>
            <td><TABLE border="0" cellpadding="0" cellspacing="1"><tr>
            <td width="140"><font face="Arial">surlignage</td>
            <td><font face="Arial">
                <select name="surlignage" size="1">
                <option selected value="false">selon les faces</option>
                <option value="true">toujours</option>
            </select></td>
            </tr></TABLE></td>
        </tr>
        <tr>
            <td><TABLE border="0" cellpadding="0" cellspacing="1"><tr>
            <td width="140"><font face="Arial">éclairage</td>
            <td><font face="Arial">
                <select name="eclairage" size="1">
                <option value="scn">sources de lumière</option>
                <option value="std">éclairage standard</option>
                <option value="std">sans éclairage</option>
            </select></td>
            </tr></TABLE></td>
        </tr>
        <tr>
            <td><TABLE border="0" cellpadding="0" cellspacing="1"><tr>
            <td width="140"><font face="Arial">format</td>
            <td><font face="Arial">
                <select name="format" size="1">
                <option value="png">png</option>
                <option value="jpg">jpeg</option>
            </select></td>
            </tr></TABLE><hr></td>
        </tr>
        </tr>
            <td><TABLE border="0" cellpadding="0" cellspacing="1"><tr>
            <td width="140">&nbsp</td>
            <td align="right">visionner</td>
            <td><font face="Arial"><input type="checkbox" name="generer" onclick="tourner()"></font></td>
            </tr></TABLE></td>
        <tr>
    </table>
</form>

</body>
</html>
