<?php
/***************************************/
// PROGRAMME..... moteur 3D
// MODULE........ importation de fichier nearstars.
// AUTEUR........ N.Dupont-Bloch
// DATE.......... 7/12/2003
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
<title>3D-Importation de catalogue HYG (1.0)</title>
</head>

<body bgcolor="#FFFFFF">
<?php
$parametres = new Parametres(); // initialisation commune.

//******
if ((isset($HTTP_POST_VARS['image_etoile'])) &&
    ($HTTP_POST_VARS['image_etoile']))
   {$image_etoile = $HTTP_POST_VARS['image_etoile'];
   }
else
   {$image_etoile = 'sphere08.3d';
   }
session_register('image_etoile');

//******
if ((isset($HTTP_POST_VARS['repere'])) &&
    ($HTTP_POST_VARS['repere']))
   {$repere = $HTTP_POST_VARS['repere'];
   }
else
   {$repere = 'grille_hyg.3d';
   }
session_register('repere');

//******
if ((isset($HTTP_POST_VARS['nom_fichier_in'])) &&
    ($HTTP_POST_VARS['nom_fichier_in']))
   {$nom_fichier_in = $HTTP_POST_VARS['nom_fichier_in'];
   }
else
   {$nom_fichier_in = '.csv';
   }
session_register('nom_fichier_in');

//******
if ((isset($HTTP_POST_VARS['nom_fichier_out'])) &&
    ($HTTP_POST_VARS['nom_fichier_out']))
   {$nom_fichier_out = $HTTP_POST_VARS['nom_fichier_out'];
   }
else
   {$nom_fichier_out = '.3da';
   }
session_register('nom_fichier_out');

//****** formulaire: paramètres de l'importation.
echo '<form method="POST" action="' .$PHP_SELF .'?act=wri">';
echo '<table border="0" cellspacing="0" cellpadding="0" bgcolor="#7EABFE">';
echo '   <tr>';
echo '      <td colspan="2">';
echo '      <table border="1" cellpadding="0" cellspacing="0">';
echo '         <tr>';
echo '            <td>';
echo '            <table border="0" cellspacing="0" cellpadding="0">';
echo '               <tr><td width="80"><font face="Arial">catalogue HYG</font></td>';
echo '                  <td><font face="Arial"><input type="file" size="20" name="nom_fichier_in"></font></td>';
echo '               </tr>';
echo '               <tr><td width="80"><font face="Arial">exporter vers</font></td>';
echo '                  <td><font face="Arial"><input type="text" size="20" name="nom_fichier_out" value="' .$nom_fichier_out .'"></font></td>';
echo '               </tr>';
echo '            </table>';
echo '            </td>';
echo '         </tr>';
echo '         <tr>';
echo '            <td>';

echo '            <table border="0" cellspacing="0" cellpadding="0">';
echo '               <tr><td width="80"><font face="Arial">images des étoiles</font></td>';
//echo '                  <td><font face="Arial"><input type="file" size="20" name="nom_fichier_in""></font></td>';
echo '                   <td><font face="Arial"><SELECT name="image_etoile"></font>';
//*** cherche tous les objets.
$repertoire = '../' .$parametres->dir_objets;
$handle = opendir($repertoire);
while ($file = strtolower(readdir($handle))) {
   if (substr($file, -3) == '.3d')
      {
      $image_etoile = strtolower(substr($file, 0, strlen($file)-3));
      $selected = ($image_etoile == $file) ? 'selected' : '';
      echo '<option value="' .$file .'">' .$image_etoile .'</option>';
      }
   }
closedir($handle);
echo '                  </SELECT></font></td>';

echo '               </tr>';
echo '               <tr><td width="80"><font face="Arial">repère</font></td>';

echo '                   <td><font face="Arial"><SELECT name="repere"></font>';
//*** cherche tous les objets.
$repertoire = '../' .$parametres->dir_objets;
$handle = opendir($repertoire);
while ($file = strtolower(readdir($handle))) {
   if (substr($file, -3) == '.3d')
      {
      $repere = strtolower(substr($file, 0, strlen($file)-3));
      $selected = ($repere == $file) ? 'selected' : '';
      echo '<option value="' .$file .'">' .$repere .'</option>';
      }
   }
closedir($handle);
echo '                  </SELECT></font></td>';

echo '               </tr>';
echo '            </table>';
echo '            </td>';
echo '         </tr>';
echo '      </table>';
echo '      </td>';
echo '   </tr>';
echo '   <tr><td width="80">&nbsp;</td><td align="left"><font face="Arial"><input type="submit" name="bt_ok" value="Générer"></font></td>';
echo '   </tr>';
echo '</table>';
echo '</form>';
//******

if (isset($act) && ($act == 'wri'))
   {
   //*** fout
   $fout = fopen('../' .$parametres->dir_anims .$nom_fichier_out, "w");
   fwrite($fout, 'lumiere;creer;;' .c_fichier_separateur_ligne);
   fwrite($fout, 'lumiere;translation;;0;20;0;' .c_fichier_separateur_ligne);
   fwrite($fout, 'lumiere;allumer;;5;255;255;255;' .c_fichier_separateur_ligne);
   fwrite($fout, ';' .c_fichier_separateur_ligne);
   fwrite($fout, 'objet;charger;;' .$repere .';' .c_fichier_separateur_ligne);
   fwrite($fout, ';' .c_fichier_separateur_ligne);
   fwrite($fout, 'objet;charger;;reticule.3d;' .c_fichier_separateur_ligne);
   fwrite($fout, ';' .c_fichier_separateur_ligne);
   
   //*** fin
   $rayon    = 10;
   $fin = fopen ($nom_fichier_in, "r");
   while (!feof ($fin))
      {
      $ligne = fgets($fin, 4096);
      $rayon = 20;
      if (substr($ligne, 0, 1) != "#")
         {
         $c = explode(";", $ligne);
         $nom = ''; // $c[5];
         $ad  = $c[7];
         $dec = $c[8];
         $dis = $c[9];
         $mg  = $c[10];
         $sp  = $c[12];
         $ic  = $c[13];
   
         $rho   = 20; //$dis / $rayon;
   
         $theta = $ad / 24 * 6.2832;
         $xet = 10 -$rho * 2 * sin($theta);
         
         $phi = deg2rad($dec);
         $yet = 50 - $rho * 4 * cos($phi);
   
         $zet = ($dis - 20) * 2;
   
         //$xet = - 10 * $ad;
         //$xet = 30-($ad * 30);
         //$yet = ($dec - 60) * 2;
         //$zet = 0;
         
         /*
         $cr = 255;
         $cv = 255;
         $cb = 255;
         */
         $precision = 0;
         $xet = round($xet, $precision);
         $yet = round($yet, $precision);
         $zet = round($zet, $precision);
         
         //*** ligne depuis le repère au sol.
         fwrite($fout, 'objet;charger;;ligne01.3d;' .c_fichier_separateur_ligne);
         fwrite($fout, 'objet;deplacement_point;;1;1;' .$xet .';0;' .$zet .';' .c_fichier_separateur_ligne);
         fwrite($fout, 'objet;deplacement_point;;1;2;' .$xet .';' .(0.95 * $yet) .';' .$zet .';' .c_fichier_separateur_ligne);
   
         //***
         fwrite($fout, 'objet;charger;' .$nom .';' .$image_etoile .';' .c_fichier_separateur_ligne);
         fwrite($fout, 'objet;translation;' .$nom .';' .$xet .';' .$yet .';' .$zet .';' .c_fichier_separateur_ligne);
         fwrite($fout, ';' .c_fichier_separateur_ligne);
         }
      }
   fclose ($fin);
   
   fwrite($fout, ';' .c_fichier_separateur_ligne);
   fwrite($fout, 'ecran;surlignage;false;' .c_fichier_separateur_ligne);
   fwrite($fout, 'ecran;eclairer;30;30;30;' .c_fichier_separateur_ligne);
   fwrite($fout, 'ecran;zoom;200;' .c_fichier_separateur_ligne);
   fwrite($fout, 'ecran;distance;120;' .c_fichier_separateur_ligne);
   fwrite($fout, 'ecran;eclairage;off;' .c_fichier_separateur_ligne);
   fwrite($fout, 'ecran;format_sortie;png;' .c_fichier_separateur_ligne);
   fwrite($fout, 'ecran;dimension;640;360;// format 16/9e' .c_fichier_separateur_ligne);
   fwrite($fout, 'ecran;translation;0;0;0;' .c_fichier_separateur_ligne);
   fwrite($fout, 'ecran;rotation;0;0;-10;' .c_fichier_separateur_ligne);
   fclose ($fout);
   }
?>
</body>
</html>
