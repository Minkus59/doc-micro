<?php
/***************************************/
// PROGRAMME..... moteur de visualisation 3D
// MODULE........ production d'une scène: les images Sql sont calculées et affichées et enregistrées.
// AUTEUR........ N.Dupont-Bloch
// DATE.......... 13/10/2003
// VERSION....... 1.5
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
<title>3D-Production de scène Sql (1.0)</title>
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
   if (i >= document.form.img_fin.value) {
      //*** Après cette dernière image demandée, arrêter la génération.
      document.form.generer.checked  = false;
      }
   nom_image = i;
   if      (nom_image < 10)    { nom_image = "000" + nom_image;  }
   else if (nom_image < 100)   { nom_image = "00"  + nom_image;  }
   else if (nom_image < 1000)  { nom_image = "0"   + nom_image;  }
   nom_image = document.form.scn.value +"-" +nom_image +"." +document.form.format.value;
   
   url  = "http://" +document.form.serveur_moteur_3d.value;
   url += "/3d_v5/3d_aff.php";
   url += "?imge="  +document.form.eclairage.value;
   url += "&fil="   +document.form.filaire.value;
   url += "&imgf="  +document.form.format.value;
   url += "&imgs="  +document.form.surlignage.value;
   url += "&dbsrv=" +document.form.serveur_database.value;
   url += "&scn="   +document.form.scn.value +"&p1=" +i;
   url += "&fout="  +nom_image;
   
   //*** calcule et, si demandé, affiche l'image.
   if (document.form.afficher.checked == true)
      {
      document.images[0].src = url;
      document.links[0].href = url;
      document.images[0].width  = 200;
      document.images[0].height = 150;
      }
   else
      {
      document.images[0].src    = url;
      document.images[0].width  = 0;
      document.images[0].height = 0;
      }

   // affiche sa destination.
   document.form.nom_image.value = document.form.dir_productions.value +nom_image;
   
   // gaffe au timer.
   if (document.form.intervalle.value < 4000)
      {// Pour éviter l'engorgement. Méthode minimale.
      document.form.intervalle.value = 4000;
      }
   
   // réarme jusqu'à la dernière image ou fin de génération demandée.
   if ((i < document.form.img_fin.value) && (document.form.generer.checked == true))
      {
      timerID = setTimeout("tourner()", document.form.intervalle.value);
      }
   else
      {// arrête la génération.
      i = 0;
      document.form.generer.checked  = false;
      }
   i++;
   }

function changer_generer()
//----------------------
   {
   if (document.form.generer.checked == true)
      {
      tourner();
      }
   }

</script>

<FORM name="form">
    <table border="0" bgcolor="<?php echo $params->coul_fond3; ?>" cellpadding="0" cellspacing="1">
        <tr>
            <td><TABLE border="0" cellpadding="0" cellspacing="1"><tr>
            <td width="140"><font face="Arial">serveur moteur 3D</font></td>
            <td><font face="Arial"><input type="text" size="10" name="serveur_moteur_3d" value="<?php echo $params->serveur_moteur_3d; ?>"></font></td>
            </tr><tr>
            <td><font face="Arial">serveur Sql</td>
            <td><font face="Arial"><input type="text" size="10" name="serveur_database" value="<?php echo $params->serveur_database; ?>"></font></td>
            <td><input type="hidden" readonly disabled name="dir_productions" value="<?php echo $params->dir_productions; ?>"></td>
            </tr></TABLE></td>
        </tr>
        <tr>
            <td><TABLE border="0" cellpadding="0" cellspacing="1"><tr>
            <td width="140"><font face="Arial">images</br>enregistrées</br>dans</font></td>
            <td><font face="Arial"><input type="text" size="20" name="nom_image" readonly disabled></font></td>
            </tr></TABLE></td>
        </tr>
        <tr>
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
        <tr>
            <td><TABLE border="0" cellpadding="0" cellspacing="1"><tr>
            <td width="140"><font face="Arial">générer les images</font></td>
            <td><font face="Arial"><input type="checkbox" name="generer" onclick="changer_generer()"></font></td>
            </tr><tr>
            <td width="140"><font face="Arial">afficher les images</font></td>
            <td><font face="Arial"><input type="checkbox" name="afficher"></font></td>
            </tr></TABLE></td>
        </tr>
    </table>
</form>

<p><a href=""><img src="0.jpg"></a></p>

</body>
</html>
