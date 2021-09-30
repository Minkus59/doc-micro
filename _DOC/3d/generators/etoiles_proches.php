<?php
/***************************************/
// PROGRAMME..... moteur 3D
// MODULE........ importation de catalogue d'étoiles proches.
// AUTEUR........ N.Dupont-Bloch
// DATE.......... 7/12/2003
// VERSION....... 1.0
/***************************************/

/* format de catalogue attendu:
#"nom";ascension droite;déclinaison;type;magnitude visuelle;magnitude absolue;distance;x;y;z;"autres noms"
"Sol";-;-;G2;-26.8;4.83;0;0;0;0;"Soleil Sun Sole Sonne"
"Proxima Centauri C";14:29.7;-62:41;M5.5;11.01;15.45;4.22;-153;-117;-374;"Hip70890;V645 Cen;Gl 551"
"Alpha Centauri A";14:39.7;-60:50;G2;-0.01;4.34;4.40;-163;-137;-383;"Hip71681;HD128620;CP-60 5483;Gl 559 A"
"Alpha Centauri B";14:39.7;-60:50;K0;1.35;5.70;4.40;-163;-137;-383;"Hip71683;HD128621;Gl 559 B"
*/

require('../3d_config.inc.php');
require('../3d_database.inc.php');
require('../3d_fic.php');
session_start();
?>


<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>3D-Importation de catalogue NearStars (1.0)</title>
</head>

<body bgcolor="#FFFFFF">
<?php
$parametres = new Parametres(); // initialisation commune.

//******
if ((isset($HTTP_POST_VARS['reduction'])) &&
    ($HTTP_POST_VARS['reduction']))
   {$reduction = $HTTP_POST_VARS['reduction'];
   }
else
   {$reduction = '20';
   }
session_register('reduction');

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
echo '               <tr><td width="80"><font face="Arial">catalogue NearStars</font></td>';
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
//echo '                  <td><font face="Arial"><input type="file" size="20" name="image_etoile"></font></td>';
echo '                   <td><font face="Arial"><SELECT name="image_etoile"></font>';
//*** cherche tous les objets.
$repertoire = '../' .$parametres->dir_objets;
$handle = opendir($repertoire);
while ($file = strtolower(readdir($handle))) {
   if (substr($file, -3) == '.3d')
      {
      $image_etoile = strtolower(substr($file, 0, strlen($file)-3));
      //$selected = ($image_etoile == $file) ? 'selected' : '';
      echo '<option value="' .$file .'">' .$image_etoile .'</option>';
      }
   }
closedir($handle);
echo '                  </SELECT></font></td>';

echo '               </tr>';
echo '               <tr><td width="80"><font face="Arial">repère</font></td>';
//echo '                  <td><font face="Arial"><input type="file" size="20" name="repere"></font></td>';
echo '                   <td><font face="Arial"><SELECT name="repere"></font>';
//*** cherche tous les objets.
$repertoire = '../' .$parametres->dir_objets;
$handle = opendir($repertoire);
while ($file = strtolower(readdir($handle))) {
   if (substr($file, -3) == '.3d')
      {
      $repere = strtolower(substr($file, 0, strlen($file)-3));
      //$selected = ($repere == $file) ? 'selected' : '';
      echo '<option value="' .$file .'">' .$repere .'</option>';
      }
   }
closedir($handle);
echo '                  </SELECT></font></td>';
echo '               </tr>';

echo '               <tr><td width="80"><font face="Arial">réduction d\'échelle</font></td>';
echo '                  <td><font face="Arial"><input type="text" size="5" name="reduction" value="' .$reduction .'"></font></td>';
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
   fwrite($fout, 'objet;charger;;' .$HTTP_POST_VARS['repere'] .';' .c_fichier_separateur_ligne);
   fwrite($fout, ';' .c_fichier_separateur_ligne);
   fwrite($fout, 'objet;charger;;reticule.3d;' .c_fichier_separateur_ligne);
   fwrite($fout, ';' .c_fichier_separateur_ligne);
   
   //*** fin
   $fin = fopen ($nom_fichier_in, "r");
   while (!feof ($fin))
      {
      $ligne = fgets($fin, 4096);
      if (substr($ligne, 0, 1) != "#")
         {
         $c = explode(";", $ligne);
         $nom = $c[0];
         $ad  = $c[1];
         $dec = $c[2];
         $sp  = $c[3]; // type spectral.
         $mv  = $c[4]; // magnitude relative visuelle.
         $mg  = $c[5]; // magnitude absolue.
         $dis = $c[6]; // distance AL.
         $xet = $c[7];
         $yet = $c[8];
         $zet = $c[9];
         $cat = $c[10]; // autres catalogues.

         if (substr($sp, 0, 1) == 'D')
            {
            $i = 128;   
            $sp = substr($sp, 1, strlen($sp) - 1);
            $homotetie = 0.25;
            }
         else
            {
            $i = 255;
            $homotetie = 1;
            }
         
         switch(substr($sp, 0, 1))
            {
            case 'O':
               $r = 195; $v = 225; $b = 255;
               break;
            case 'B':
               $r = 185; $v = 255; $b = 255;
               break;
            case 'A':
               $r = 235; $v = 245; $b = 245;
               break;
            case 'F':
               $r = 235; $v = 245; $b = 230;
               break;
            case 'G':
               $r = 225; $v = 250; $b = 150;
               break;
            case 'K':
               $r = 250; $v = 215; $b = 130;
               break;
            case 'M':
               $r = 255; $v = 170; $b = 100;
               break;
            }
         
         $precision = 0;
         $xet = round($xet / $reduction, $precision);
         $yet = round($yet / $reduction, $precision);
         $zet = round($zet / $reduction, $precision);
         
         //*** ligne depuis le repère au sol.
         fwrite($fout, 'objet;charger;;ligne01.3d;' .c_fichier_separateur_ligne);
         fwrite($fout, 'objet;rvbi;;128; 128; 128; 128;' .c_fichier_separateur_ligne);
         fwrite($fout, 'objet;deplacement_point;;1;1;' .$xet .';0;' .$zet .';' .c_fichier_separateur_ligne);
         fwrite($fout, 'objet;deplacement_point;;1;2;' .$xet .';' .$yet .';' .$zet .';' .c_fichier_separateur_ligne);
   
         //***
         fwrite($fout, 'objet;charger;' .$nom .';' .$HTTP_POST_VARS['image_etoile'] .';' .c_fichier_separateur_ligne);
         fwrite($fout, 'objet;rvbi;'    .$nom .';' .$r .';' .$v .';' .$b .';' .$i .';' .c_fichier_separateur_ligne);
         if ($homotetie != 1) {
            fwrite($fout, 'objet;homotetie;' .$nom .';' .$homotetie .';' .c_fichier_separateur_ligne);
            }
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
