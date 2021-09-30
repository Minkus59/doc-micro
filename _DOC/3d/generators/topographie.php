<?php
/***************************************/
// PROGRAMME..... moteur 3D
// MODULE........ Transformation carte topographique jpg => objet 3D.
// AUTEUR........ N.Dupont-Bloch
// DATE.......... 7/10/8/2003
// VERSION....... 1.0
// PARAMETRES.... $action=wri quand le script se rappelle par le formulaire.
/***************************************/
/*
Analyse une carte topographique JPG noir & blanc:
les niveaux de gris sont considérés proportionnels à l'altitude,
du noir = zéro au blanc = altitude maxi. le résultat est un objet 3D.
*/
require("../3d_fic.php");
require('../3d_config.inc.php');
?>

<HTML>
<HEAD>
<TITLE>3D-Topographie (1.0)</TITLE>
</HEAD>


<?php
$parametres = new Parametres();
$dir_racine = '../'; // par rapport à 3d_config.inc.php.

define(c_script_version, "3D-Topographie 1.0");

//*** récupérer paramètres de génération de la grille.
if (isset($HTTP_POST_VARS["intervalle"]))
   {$intervalle = $HTTP_POST_VARS["intervalle"];   }
else
   {$intervalle = 1;   }

if (isset($HTTP_POST_VARS["zoom_altitude"]))
   {$zoom_altitude = $HTTP_POST_VARS["zoom_altitude"];   }
else
   {$zoom_altitude = 0.02;   }

// représentation:
if (isset($HTTP_POST_VARS["filaire"]))
   {$filaire = $HTTP_POST_VARS["filaire"];   }
else
   {$filaire = "false";   }

if (isset($HTTP_POST_VARS["surlignage"]))
   {$surlignage = $HTTP_POST_VARS["surlignage"];   }
else
   {$surlignage = "true";   }

if (isset($HTTP_POST_VARS["constituants"]))
   {$constituants = $HTTP_POST_VARS["constituants"];   }
else
   {$constituants = "points";   }

// couleur:
if (isset($HTTP_POST_VARS["r"]))
   {$r = $HTTP_POST_VARS["r"];   }
else
   {$r = 255;   }

if (isset($HTTP_POST_VARS["v"]))
   {$v = $HTTP_POST_VARS["v"];   }
else
   {$v = 255;   }

if (isset($HTTP_POST_VARS["b"]))
   {$b = $HTTP_POST_VARS["b"];   }
else
   {$b = 255;   }

if (isset($HTTP_POST_VARS["nom_fichier"]))
   {$nom_fichier = $HTTP_POST_VARS["nom_fichier"];   }
else
   {$nom_fichier = ".3d";   }

if (isset($HTTP_POST_VARS["commentaire"]))
   {$commentaire = $HTTP_POST_VARS["commentaire"];   }
else
   {
   $commentaire  = "# carte générée par " .c_script_version .c_fichier_separateur_ligne;
   }


if (isset($action) && ($action == 'wri')) {
   //*** vérifie paramètres.
   if ($intervalle < 1) { $intervalle = 1;}
   
   //*** charge la carte.
   $img_size    = getimagesize($carte);
   $img_largeur = $img_size[0];
   $img_hauteur = $img_size[1];
   $img         = imagecreatefromjpeg($carte);

   //*** générer et enregistrer la surface avec les paramètres récupérés.
   $f_out = fopen($dir_racine .$parametres->dir_objets .$nom_fichier, "w");
   $ligne = $commentaire;
   fputs($f_out, $ligne .c_fichier_separateur_ligne);

   for ($x = 0; $x < $img_largeur; $x+=  $intervalle) {
      for ($y = 0; $y < $img_hauteur; $y+=  $intervalle) {
         // nouvelle face.
         $ligne  = "f" .c_fichier_separateur_colonne;
         $ligne .= $r  .c_fichier_separateur_colonne;
         $ligne .= $v  .c_fichier_separateur_colonne;
         $ligne .= $b  .c_fichier_separateur_colonne;
         $ligne .= $filaire .c_fichier_separateur_colonne; // filaire
         $ligne .= $surlignage .c_fichier_separateur_colonne; // surlignage.
         fputs($f_out, $ligne .c_fichier_separateur_ligne);

         switch ($constituants) {
            case 'points':
            //************
            //nouveau point.
            $rvb_index = ImageColorAt($img, $x, $y); // récupère couleur.
            $rvb = imagecolorsforindex($img, $rvb_index);
            $p_r = $rvb['red'];
            $p_v = $rvb['green'];
            $p_b = $rvb['blue'];
            $p_x = ( $x / $intervalle) - ($img_largeur / 2); // longitude.
            $p_z = (-$y / $intervalle) + ($img_hauteur / 2); // latitude.
            $p_y = ($p_r + $p_v + $p_b) * $zoom_altitude; // conversion couleur -> altitude.
            
            // enregistre le point.
            $ligne  = "p" .c_fichier_separateur_colonne;
            $ligne .= $p_x .c_fichier_separateur_colonne;
            $ligne .= $p_y .c_fichier_separateur_colonne;
            $ligne .= $p_z .c_fichier_separateur_colonne;
            fputs($f_out, $ligne .c_fichier_separateur_ligne);
            break;
            
            case 'pics':
            //**********
            //nouveau point altitude zéro.
            $p_x = ( $x / $intervalle) - ($img_largeur / 2); // longitude.
            $p_z = (-$y / $intervalle) + ($img_hauteur / 2); // latitude.
            $p_y = 0;
            
            // enregistre le point.
            $ligne  = "p" .c_fichier_separateur_colonne;
            $ligne .= $p_x .c_fichier_separateur_colonne;
            $ligne .= $p_y .c_fichier_separateur_colonne;
            $ligne .= $p_z .c_fichier_separateur_colonne;
            fputs($f_out, $ligne .c_fichier_separateur_ligne);

            //nouveau point en altitude.
            $rvb = ImageColorAt($img, $x, $y); // récupère couleur.
            $rvb_index = ImageColorAt($img, $x, $y); // récupère couleur.
            $rvb = imagecolorsforindex($img, $rvb_index);
            $p_r = $rvb['red'];
            $p_v = $rvb['green'];
            $p_b = $rvb['blue'];
            $p_y = ($p_r + $p_v + $p_b) * $zoom_altitude; // conversion couleur -> altitude.
            
            // enregistre le point.
            $ligne  = "p" .c_fichier_separateur_colonne;
            $ligne .= $p_x .c_fichier_separateur_colonne;
            $ligne .= $p_y .c_fichier_separateur_colonne;
            $ligne .= $p_z .c_fichier_separateur_colonne;
            fputs($f_out, $ligne .c_fichier_separateur_ligne);
            break;
            
            case 'faces':
            //***********
            //nouveau point.
            $rvb_index = ImageColorAt($img, $x, $y); // récupère couleur.
            $rvb = imagecolorsforindex($img, $rvb_index);
            $p_r = $rvb['red'];
            $p_v = $rvb['green'];
            $p_b = $rvb['blue'];
            $p_x = ( $x / $intervalle) - ($img_largeur / 2); // longitude.
            $p_z = (-$y / $intervalle) + ($img_hauteur / 2); // latitude.
            $p_y = ($p_r + $p_v + $p_b) * $zoom_altitude; // conversion couleur -> altitude.
            
            // enregistre le point.
            $ligne  = "p" .c_fichier_separateur_colonne;
            $ligne .= $p_x .c_fichier_separateur_colonne;
            $ligne .= $p_y .c_fichier_separateur_colonne;
            $ligne .= $p_z .c_fichier_separateur_colonne;
            fputs($f_out, $ligne .c_fichier_separateur_ligne);

            // autres points, toujours dans le sens des aiguilles.
            
            // point à droite.
            if ($x + $intervalle <= $img_largeur) {
               $rvb_index = ImageColorAt($img, $x + $intervalle, $y); // récupère couleur.
               $rvb = imagecolorsforindex($img, $rvb_index);
               $p_r = $rvb['red'];
               $p_v = $rvb['green'];
               $p_b = $rvb['blue'];
               $p_x = (($x + $intervalle) / $intervalle) - ($img_largeur / 2); // longitude.
               $p_z = (-$y / $intervalle) + ($img_hauteur / 2);                // latitude.
               $p_y = ($p_r + $p_v + $p_b) * $zoom_altitude; // conversion couleur -> altitude.

               // enregistre le point.
               $ligne  = "p" .c_fichier_separateur_colonne;
               $ligne .= $p_x .c_fichier_separateur_colonne;
               $ligne .= $p_y .c_fichier_separateur_colonne;
               $ligne .= $p_z .c_fichier_separateur_colonne;
               fputs($f_out, $ligne .c_fichier_separateur_ligne);
               }

            // point dessous à droite.
            if (($x + $intervalle <= $img_largeur) && ($y + $intervalle <= $img_hauteur)) {
               $rvb_index = ImageColorAt($img, $x + $intervalle, $y+ $intervalle); // récupère couleur.
               $rvb = imagecolorsforindex($img, $rvb_index);
               $p_r = $rvb['red'];
               $p_v = $rvb['green'];
               $p_b = $rvb['blue'];
               $p_x = ( ($x + $intervalle) / $intervalle) - ($img_largeur / 2);  // longitude.
               $p_z = ((-$y + $intervalle) / $intervalle) + ($img_hauteur / 2);  // latitude.
               $p_y = ($p_r + $p_v + $p_b) * $zoom_altitude; // conversion couleur -> altitude.

               // enregistre le point.
               $ligne  = "p" .c_fichier_separateur_colonne;
               $ligne .= $p_x .c_fichier_separateur_colonne;
               $ligne .= $p_y .c_fichier_separateur_colonne;
               $ligne .= $p_z .c_fichier_separateur_colonne;
               fputs($f_out, $ligne .c_fichier_separateur_ligne);
               }

            // point dessous.
            if ($y + $intervalle <= $img_hauteur) {
               $rvb_index = ImageColorAt($img, $x, $y+ $intervalle); // récupère couleur.
               $rvb = imagecolorsforindex($img, $rvb_index);
               $p_r = $rvb['red'];
               $p_v = $rvb['green'];
               $p_b = $rvb['blue'];
               $p_x = (  $x / $intervalle) - ($img_largeur / 2);  // longitude.
               $p_z = ((-$y + $intervalle)  / $intervalle) + ($img_hauteur / 2); // latitude.
               $p_y = ($p_r + $p_v + $p_b) * $zoom_altitude; // conversion couleur -> altitude.

               // enregistre le point.
               $ligne  = "p" .c_fichier_separateur_colonne;
               $ligne .= $p_x .c_fichier_separateur_colonne;
               $ligne .= $p_y .c_fichier_separateur_colonne;
               $ligne .= $p_z .c_fichier_separateur_colonne;
               fputs($f_out, $ligne .c_fichier_separateur_ligne);
               }

            // on termine toujours une face par dernier point = premier point.
            $rvb_index = ImageColorAt($img, $x, $y); // récupère couleur.
            $rvb = imagecolorsforindex($img, $rvb_index);
            $p_r = $rvb['red'];
            $p_v = $rvb['green'];
            $p_b = $rvb['blue'];
            $p_x = ( $x / $intervalle) - ($img_largeur / 2); // longitude.
            $p_z = (-$y / $intervalle) + ($img_hauteur / 2); // latitude.
            $p_y = ($p_r + $p_v + $p_b) * $zoom_altitude; // conversion couleur -> altitude.

            // enregistre le point.
            $ligne  = "p" .c_fichier_separateur_colonne;
            $ligne .= $p_x .c_fichier_separateur_colonne;
            $ligne .= $p_y .c_fichier_separateur_colonne;
            $ligne .= $p_z .c_fichier_separateur_colonne;
            fputs($f_out, $ligne .c_fichier_separateur_ligne);
            break;
            }
         }
      }

   fclose($f_out);

   //*** génération de la scène.
   if (true) {
      $nom_anim = $dir_racine .$parametres->dir_anims .$nom_fichier .'a';
      $fout = fopen($nom_anim, "w");
      fwrite($fout, 'lumiere;creer;;' .c_fichier_separateur_ligne);
      fwrite($fout, 'lumiere;translation;;0;20;0;' .c_fichier_separateur_ligne);
      fwrite($fout, 'lumiere;allumer;;5;255;255;255;' .c_fichier_separateur_ligne);
      fwrite($fout, ';' .c_fichier_separateur_ligne);
      fwrite($fout, 'objet;charger;;' .$nom_fichier .';' .c_fichier_separateur_ligne);
      fwrite($fout, ';' .c_fichier_separateur_ligne);
      fwrite($fout, 'ecran;surlignage;false;' .c_fichier_separateur_ligne);
      fwrite($fout, 'ecran;eclairer;30;30;30;' .c_fichier_separateur_ligne);
      fwrite($fout, 'ecran;zoom;800;' .c_fichier_separateur_ligne);
      fwrite($fout, 'ecran;distance;100;' .c_fichier_separateur_ligne);
      fwrite($fout, 'ecran;eclairage;scn;' .c_fichier_separateur_ligne);
      fwrite($fout, 'ecran;format_sortie;png;' .c_fichier_separateur_ligne);
      fwrite($fout, 'ecran;dimension;640;360;// format 16/9e' .c_fichier_separateur_ligne);
      fwrite($fout, 'ecran;translation;0;0;0;' .c_fichier_separateur_ligne);
      fwrite($fout, 'ecran;rotation;0;0;-30;' .c_fichier_separateur_ligne);
      }
   }
?>

<BODY>
<FORM name="frm_sphere" method="POST" action="<?php echo $self; ?>?action=wri">
<TABLE border="1" cellspacing="1" bgcolor="#7EABFE">
<tr>
   <td><font face="Arial">carte topographique</font></td>
   <td colspan="2"><font face="Arial"><input type="file" size="20" name="carte"></font></td>
   </tr>
<tr>
   <td><font face="Arial">intervalle</font></td>
   <td colspan="2"><font face="Arial"><input type="text" size="3" name="intervalle" value="<?php echo $intervalle; ?>"> point(s)</font></td>
   </tr>
<tr>
   <td><font face="Arial">zoom altitude</font></td>
   <td colspan="2"><font face="Arial"><input type="text" size="3" name="zoom_altitude" value="<?php echo $zoom_altitude; ?>"></font></td>
   </tr>
<tr>
   <td align="right">constituants <select name="constituants" size="1">
      <option <?php if ($constituants == "points")  echo "selected"; ?> value="points">points</option>
      <option <?php if ($constituants == "faces")   echo "selected"; ?> value="faces">faces</option>
      <option <?php if ($constituants == "pics")    echo "selected"; ?> value="pics">pics</option>
      </select></td>
   <td align="right">filaire <select name="filaire" size="1">
      <option <?php if ($filaire == "true")  echo "selected"; ?> value="true">oui (arêtes)</option>
      <option <?php if ($filaire == "false") echo "selected"; ?> value="false">non (faces)</option>
      </select></td>
   <td align="right">surlignage <select name="surlignage" size="1">
      <option <?php if ($surlignage == "true")  echo "selected"; ?> value="true">oui</option>
      <option <?php if ($surlignage == "false") echo "selected"; ?> value="false">non</option>
      </select></td>
   </tr>
<tr>
   <td align="right">rouge (0...255)<input type="text" size="3" name="r" value="<?php echo $r; ?>"></td>
   <td align="right">vert (0...255) <input type="text" size="3" name="v" value="<?php echo $v; ?>"></td>
   <td align="right">bleu (0...255) <input type="text" size="3" name="b" value="<?php echo $b; ?>"></td>
   </tr>
<tr>
   <td align="left" colspan="3">commentaire</br><textarea name="commentaire" cols="50" rows="5"><?php echo $commentaire; ?></textarea></td>
   </tr>
<tr>
   <td align="right" colspan="3">exporter vers<input type="text" size="40" name="nom_fichier" value="<?php echo $nom_fichier; ?>"></td>
   </tr>
<tr>
   <td align="left" colspan="3"><input type="submit" name="bt_generer" value="Générer"></td>
   </tr>
</TABLE>
</FORM>
</BODY>
</HTML>