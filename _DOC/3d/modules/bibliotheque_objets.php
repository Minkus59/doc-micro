<?php
/***************************************/
// PROGRAMME..... moteur 3D
// MODULE........ Affiche la bibliothèque d'objets en appelant le moteur 3D via un script.
// AUTEUR........ N.Dupont-Bloch
// DATE.......... 14/10/8/2003
// VERSION....... 1.0
/***************************************/
require('../3d_config.inc.php');
require('../3d_database.inc.php');
require('../3d_fic.php');
session_start();
?>

<html>
<head>
<meta http-equiv="Content-Type"
content="text/html; charset=iso-8859-1">
<title>3D-Objets: bibliothèque (1.0)</title>
</head>

<body>
<?php
$params = new Parametres(); // initialisation commune.

//******
if ((isset($HTTP_POST_VARS['objet'])) &&
    ($HTTP_POST_VARS['objet']))
   {$objet = $HTTP_POST_VARS['objet'];
   }
else
   {$objet = '*.3d';
   }
session_register('objet');

//******
if ((isset($HTTP_POST_VARS['surlignage'])) &&
    ($HTTP_POST_VARS['surlignage']))
   {$surlignage = $HTTP_POST_VARS['surlignage'];
   }
else
   {$surlignage = 'true';
   }
session_register('surlignage');

//******
if ((isset($HTTP_POST_VARS['filaire'])) &&
    ($HTTP_POST_VARS['filaire']))
   {$filaire = $HTTP_POST_VARS['filaire'];
   }
else
   {$filaire = 'true';
   }
session_register('filaire');

//******
if ((isset($HTTP_POST_VARS['eclairage'])) &&
    ($HTTP_POST_VARS['eclairage']))
   {$eclairage = $HTTP_POST_VARS['eclairage'];
   }
else
   {$eclairage = 'std';
   }
session_register('eclairage');


//****** formulaire: paramètres de l'importation.
echo '<form method="POST" action="' .$PHP_SELF .'?act=view">';
echo '<table border="0" cellspacing="0" cellpadding="0" bgcolor="' .$params->coul_fond3 .'">';
echo '   <tr>';
echo '      <td colspan="2">';
echo '      <TABLE border="1" cellpadding="0" cellspacing="0">';
echo '        <tr>';
echo '            <td><TABLE border="0" cellspacing="0" cellpadding="0" cellspacing="1">';
echo '               <tr><td width="140"><font face="Arial">objet</font></td>';
echo '                   <td><font face="Arial"><SELECT name="objet"></font>';
//*** cherche tous les objets.
$repertoire = '../' .$params->dir_objets;
$handle = opendir($repertoire);
while ($file = strtolower(readdir($handle))) {
   if (substr($file, -3) == '.3d')
      {
      $nom_objet = strtolower(substr($file, 0, strlen($file)-3));
      $selected = ($objet == $file) ? 'selected' : '';
      echo '<option ' .$selected .' value="' .$file .'">' .$nom_objet .'</option>';
      }
   }
closedir($handle);
echo '                  </SELECT></font></td>';
echo '        </tr></TABLE></td>';
echo '        <tr>';
echo '            <td><TABLE border="0" cellpadding="0" cellspacing="1"><tr>';
echo '            <td width="140"><font face="Arial">représentation</td>';
echo '            <td><font face="Arial">';
echo '               <select name="filaire" size="1">';
$selected = ($filaire == "false") ? 'selected' : '';
echo '                <option ' .$selected .' value="false">faces cachées</option>';
$selected = ($filaire == "true") ? 'selected' : '';
echo '                <option ' .$selected .' value="true">filaire</option>';
echo '            </select> </td>';
echo '            </tr></TABLE></td>';
echo '        </tr>';
echo '        <tr>';
echo '            <td><TABLE border="0" cellpadding="0" cellspacing="1"><tr>';
echo '            <td width="140"><font face="Arial">surlignage</td>';
echo '            <td><font face="Arial">';
echo '                <select name="surlignage" size="1">';
$selected = ($surlignage == "false") ? 'selected' : '';
echo '                <option ' .$selected .' value="false">selon les faces</option>';
$selected = ($surlignage == "true") ? 'selected' : '';
echo '                <option ' .$selected .' value="true">toujours</option>';
echo '            </select></td>';
echo '            </tr></TABLE></td>';
echo '        </tr>';
echo '        <tr>';
echo '            <td><TABLE border="0" cellpadding="0" cellspacing="1"><tr>';
echo '            <td width="140"><font face="Arial">éclairage</td>';
echo '            <td><font face="Arial">';
echo '                <select name="eclairage" size="1">';
$selected = ($eclairage == "scn") ? 'selected' : '';
echo '                <option ' .$selected .' value="scn">sources de lumière</option>';
$selected = ($eclairage == "std") ? 'selected' : '';
echo '                <option ' .$selected .' value="std">éclairage standard</option>';
$selected = ($eclairage == "off") ? 'selected' : '';
echo '                <option ' .$selected .' value="off">sans éclairage</option>';
echo '            </select></td>';
echo '            </tr></TABLE></td>';
echo '         </tr>';
echo '      </TABLE>';
echo '      </td>';
echo '   </tr>';
echo '   <tr><td width="80">&nbsp;</td><td align="left"><font face="Arial"><input type="submit" name="bt_ok" value="Voir"></font></td>';
echo '   </tr>';
echo '</table>';
echo '</form>';
//******

if (isset($act) && ($act == 'view'))
   {
   //*** fout
   $nom_fichier_out = 'objet.3da';
   $fout = fopen('../' .$params->dir_anims .$nom_fichier_out, "w");
   fwrite($fout, 'lumiere;creer;;' .c_fichier_separateur_ligne);
   fwrite($fout, 'lumiere;translation;;10;10;0;' .c_fichier_separateur_ligne);
   fwrite($fout, 'lumiere;irvb;;2;255;255;255;' .c_fichier_separateur_ligne);
   fwrite($fout, ';' .c_fichier_separateur_ligne);
   fwrite($fout, 'objet;charger;;reticule.3d;' .c_fichier_separateur_ligne);
   fwrite($fout, ';' .c_fichier_separateur_ligne);
   fwrite($fout, 'objet;charger;'          .$objet .';' .$objet .';' .c_fichier_separateur_ligne);
   fwrite($fout, 'objet;translation;'      .$objet .';0;0;0;' .c_fichier_separateur_ligne);
   fwrite($fout, 'objet;rotation;'         .$objet .';0;20;-20;' .c_fichier_separateur_ligne);
   fwrite($fout, ';' .c_fichier_separateur_ligne);
   fwrite($fout, 'ecran;surlignage;ecran;' .$surlignage .';' .c_fichier_separateur_ligne);
   fwrite($fout, 'ecran;filaire;ecran;'    .$filaire .';' .c_fichier_separateur_ligne);
   fwrite($fout, 'ecran;rvb;ecran;30;30;30;' .c_fichier_separateur_ligne);
   fwrite($fout, 'ecran;zoom;ecran;200;'     .c_fichier_separateur_ligne);
   fwrite($fout, 'ecran;distance;ecran;120;' .c_fichier_separateur_ligne);
   fwrite($fout, 'ecran;eclairage;ecran;'  .$eclairage .';' .c_fichier_separateur_ligne);
   fwrite($fout, 'ecran;format_sortie;ecran;png;' .c_fichier_separateur_ligne);
   fwrite($fout, 'ecran;dimension;ecran;160;120;' .c_fichier_separateur_ligne);
   fwrite($fout, 'ecran;translation;ecran;0;0;0;' .c_fichier_separateur_ligne);
   fwrite($fout, 'ecran;rotation;ecran;0;20;-20;' .c_fichier_separateur_ligne);
   fclose ($fout);
   $url  = 'http://' .$params->serveur_moteur_3d .'/3d_v5/3d_aff.php';
   $url .= '?fin=' .$nom_fichier_out;
   $url .= '&imgz=200';
   $url .= '&fil=' .$filaire;
   $url .= '&imgs=' .$surlignage;
   $url .= '&imge=' .$eclairage;
   $url .= '&imgf=png';
   echo '<img src="' .$url .'">';
   }
?>
</body>
</html>
