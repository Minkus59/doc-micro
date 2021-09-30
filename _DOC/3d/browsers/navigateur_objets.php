<?php
/***************************************/
// PROGRAMME..... moteur 3D
// MODULE........ Afficheur d'objets isolés avec appel du moteur 3D par script.
// AUTEUR........ N.Dupont-Bloch
// DATE.......... 13/10/8/2003
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
<title>3D-Objets: navigateur (1.0)</title>
</head>

<body>
<?php
$parametres = new Parametres(); // configuration commune.

//******
if ((isset($HTTP_POST_VARS['objet'])) &&
    ($HTTP_POST_VARS['objet']))
   {$objet = $HTTP_POST_VARS['objet'];
   }
else
   {$objet = '';
   }
session_register('objet');

//****** formulaire.
echo '<form method="POST" action="' .$PHP_SELF .'?act=view">';
echo '<table border="0" cellspacing="0" cellpadding="0" bgcolor="#7EABFE">';
echo '   <tr>';
echo '      <td colspan="2">';
echo '      <table border="1" cellpadding="0" cellspacing="0">';
echo '         <tr>';
echo '            <td>';
echo '            <table border="0" cellspacing="0" cellpadding="0">';
echo '               <tr><td width="80"><font face="Arial">objet</font></td>';
echo '                  <td><font face="Arial"><input type="file" size="20" name="objet"></font></td>';
echo '               </tr>';
echo '            </table>';
echo '            </td>';
echo '         </tr>';
echo '      </table>';
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
   $fout = fopen('../' .$parametres->dir_anims .$nom_fichier_out, "w");

   fwrite($fout, 'lumiere;creer;;' .c_fichier_separateur_ligne);
   fwrite($fout, 'lumiere;translation;;0;20;0;' .c_fichier_separateur_ligne);
   fwrite($fout, 'lumiere;allumer;;2;255;255;255;' .c_fichier_separateur_ligne);
   fwrite($fout, ';' .c_fichier_separateur_ligne);

   fwrite($fout, 'objet;charger;;reticule.3d;' .c_fichier_separateur_ligne);
   fwrite($fout, ';' .c_fichier_separateur_ligne);
   
   fwrite($fout, 'objet;charger;' .$objet .';' .$objet .';' .c_fichier_separateur_ligne);
   fwrite($fout, ';' .c_fichier_separateur_ligne);

   fwrite($fout, 'ecran;surlignage;ecran;false;'.c_fichier_separateur_ligne);
   fwrite($fout, 'ecran;eclairer;ecran;0;0;30;'.c_fichier_separateur_ligne);
   fwrite($fout, 'ecran;zoom;ecran;200;'.c_fichier_separateur_ligne);
   fwrite($fout, 'ecran;distance;ecran;120;'.c_fichier_separateur_ligne);
   fwrite($fout, 'ecran;format_sortie;ecran;png;'.c_fichier_separateur_ligne);
   fwrite($fout, 'ecran;dimension;ecran;320;200;'.c_fichier_separateur_ligne);
   fwrite($fout, 'ecran;translation;ecran;0;0;0;'.c_fichier_separateur_ligne);
   fwrite($fout, 'ecran;rotation;ecran;0;20;-20;'.c_fichier_separateur_ligne);
   fclose ($fout);
   }
?>
<img src="0.jpg">
</body>
</html>
