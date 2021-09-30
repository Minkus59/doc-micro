<?php
/***************************************/
// PROGRAMME..... moteur 3D
// MODULE........ création de sphère.
// AUTEUR........ N.Dupont-Bloch
// DATE.......... 15/10/2003
// VERSION....... 1.8
// paramètres.... $action=wri si le script s'appelle lui-même pour générer un objet.
/***************************************/
require('../3d_config.inc.php');
require("../3d_fic.php");
$params = new Parametres();
?>

<HTML>
<HEAD>
<TITLE>3D-Sphère (1.6)</TITLE>
</HEAD>

<?php
$parametres = new Parametres();
$dir_racine = '../'; // par rapport à 3d_config.inc.php.

define(c_script_version, "3D-sphere 1.8");

//*** récupérer paramètres de génération de la sphère.
if (isset($HTTP_POST_VARS["diametre"]))
   {$diametre = $HTTP_POST_VARS["diametre"];   }
else
   {$diametre = 15;   }
   
// latitude:
if (isset($HTTP_POST_VARS["deb_v"]))
   {$deb_v = $HTTP_POST_VARS["deb_v"];   }
else
   {$deb_v = 0;   }

if (isset($HTTP_POST_VARS["fin_v"]))
   {$fin_v = $HTTP_POST_VARS["fin_v"];   }
else
   {$fin_v = 180;   }

if (isset($HTTP_POST_VARS["div_v"]))
   {$div_v = $HTTP_POST_VARS["div_v"];   }
else
   {$div_v = 15;   }

// longitude:
if (isset($HTTP_POST_VARS["deb_h"]))
   {$deb_h = $HTTP_POST_VARS["deb_h"];   }
else
   {$deb_h = 0;   }

if (isset($HTTP_POST_VARS["fin_h"]))
   {$fin_h = $HTTP_POST_VARS["fin_h"];   }
else
   {$fin_h = 360;   }

if (isset($HTTP_POST_VARS["div_h"]))
   {$div_h = $HTTP_POST_VARS["div_h"];   }
else
   {$div_h = 15;   }

if (isset($HTTP_POST_VARS["nb_decimales"]))
   {$nb_decimales = $HTTP_POST_VARS["nb_decimales"];   }
else
   {$nb_decimales = 2;   }

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
   {$constituants = "faces";   }

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
   $commentaire  = "# sphère générée par " .c_script_version .c_fichier_separateur_ligne;
   }

if (isset($HTTP_POST_VARS["nom_fichier"]))
   {$nom_ficher = $HTTP_POST_VARS["nom_fichier"];   }
else
   {$nom_ficher = ".3d";   }

if ($action == 'wri')
   {
   //*** générer et enregistrer la sphère avec les paramètres récupérés.
   $fd = fopen($dir_racine .$parametres->dir_objets .$nom_fichier, "w");

   $ligne = $commentaire;
   fputs($fd, $ligne .c_fichier_separateur_ligne);

   $intervalle_v = (deg2rad($fin_v) - deg2rad($deb_v)) / $div_v;
   $intervalle_h = (deg2rad($fin_h) - deg2rad($deb_h)) / $div_h;
   for ($i = deg2rad($deb_v); $i <= deg2rad($fin_v) - $intervalle_v; $i += $intervalle_v)
      {
      for ($ii = deg2rad($deb_h); $ii <= deg2rad($fin_h) - $intervalle_h; $ii += $intervalle_h)
         {
         // *** enregistrer face.
         $ligne  = "f" .c_fichier_separateur_colonne;
         $ligne .= $r  .c_fichier_separateur_colonne;
         $ligne .= $v  .c_fichier_separateur_colonne;
         $ligne .= $b  .c_fichier_separateur_colonne;
         $ligne .= $filaire    .c_fichier_separateur_colonne;
         if ($filaire == "true")
            {
            $surlignage = "false";
            }
         $ligne .= $surlignage .c_fichier_separateur_colonne;
         fputs($fd, $ligne .c_fichier_separateur_ligne);

         //*** enregistrer point(s) de la face.
         if ($constituants == "points")
            {// *** enregistrer le seul point de la face.
            $x = round($diametre/2 * cos($ii) * sin($i), $nb_decimales);
            $y = round($diametre/2 * cos($i), $nb_decimales);
            $z = round($diametre/2 * sin($ii) * sin($i), $nb_decimales);

            $ligne  = "p" .c_fichier_separateur_colonne;
            $ligne .= $x .c_fichier_separateur_colonne;
            $ligne .= $y .c_fichier_separateur_colonne;
            $ligne .= $z .c_fichier_separateur_colonne;

            fputs($fd, $ligne .c_fichier_separateur_ligne);
            fputs($fd, c_fichier_separateur_ligne);
            } // fin face constituée de points.

         elseif ($constituants == "faces")
            {// *** enregistrer face 5 points (le dernier = copie du 1er).
            $x1 = round($diametre/2 * cos($ii) * sin($i), $nb_decimales);
            $y1 = round($diametre/2 * cos($i), $nb_decimales);
            $z1 = round($diametre/2 * sin($ii) * sin($i), $nb_decimales);

            $x2 = round($diametre/2 * cos($ii + $intervalle_h) * sin($i), $nb_decimales);
            $y2 = round($diametre/2 * cos($i), $nb_decimales);
            $z2 = round($diametre/2 * sin($ii + $intervalle_h) * sin($i), $nb_decimales);

            $x3 = round($diametre/2 * cos($ii + $intervalle_h) * sin($i + $intervalle_v), $nb_decimales);
            $y3 = round($diametre/2 * cos($i  + $intervalle_v), $nb_decimales);
            $z3 = round($diametre/2 * sin($ii + $intervalle_h) * sin($i + $intervalle_v), $nb_decimales);

            $x4 = round($diametre/2 * cos($ii) * sin($i + $intervalle_v), $nb_decimales);
            $y4 = round($diametre/2 * cos($i + $intervalle_v), $nb_decimales);
            $z4 = round($diametre/2 * sin($ii) * sin($i + $intervalle_v), $nb_decimales);

            $ligne  = "p" .c_fichier_separateur_colonne;
            $ligne .= $x1 .c_fichier_separateur_colonne;
            $ligne .= $y1 .c_fichier_separateur_colonne;
            $ligne .= $z1 .c_fichier_separateur_colonne;
            fputs($fd, $ligne .c_fichier_separateur_ligne);

            $ligne  = "p" .c_fichier_separateur_colonne;
            $ligne .= $x2 .c_fichier_separateur_colonne;
            $ligne .= $y2 .c_fichier_separateur_colonne;
            $ligne .= $z2 .c_fichier_separateur_colonne;
            fputs($fd, $ligne .c_fichier_separateur_ligne);

            $ligne  = "p" .c_fichier_separateur_colonne;
            $ligne .= $x3 .c_fichier_separateur_colonne;
            $ligne .= $y3 .c_fichier_separateur_colonne;
            $ligne .= $z3 .c_fichier_separateur_colonne;
            fputs($fd, $ligne .c_fichier_separateur_ligne);

            $ligne  = "p" .c_fichier_separateur_colonne;
            $ligne .= $x4 .c_fichier_separateur_colonne;
            $ligne .= $y4 .c_fichier_separateur_colonne;
            $ligne .= $z4 .c_fichier_separateur_colonne;
            fputs($fd, $ligne .c_fichier_separateur_ligne);

            $ligne  = "p" .c_fichier_separateur_colonne;
            $ligne .= $x1 .c_fichier_separateur_colonne;
            $ligne .= $y1 .c_fichier_separateur_colonne;
            $ligne .= $z1 .c_fichier_separateur_colonne;
            fputs($fd, $ligne .c_fichier_separateur_ligne);

            fputs($fd, c_fichier_separateur_ligne);
            } // fin face constituée de polygones.
         }
      }
   fclose($fd);
   }
?>

<BODY>
<FORM name="frm_sphere" method="POST" action="<?php echo $self; ?>?action=wri">
<TABLE border="1" cellspacing="0" bgcolor="<?php echo $params->coul_fond3; ?>">
<tr>
   <td><table><tr><td width="80">diamètre</td><td><input type="text" size="3" name="diametre" value="<?php echo $diametre; ?>"></td></tr></table></td>
   <td colspan="2"><table><tr><td>calcul précis à</td><td><input type="text" size="3" name="nb_decimales" value="<?php echo $nb_decimales;?>">décimale(s)</td></tr></table></td>
   </tr>
<tr>
   <td><table><tr><td width="80">latitude: de</td><td><input type="text" size="3" name="deb_v"    value="<?php echo $deb_v; ?>"> °</td></tr></table></td>
   <td><table><tr><td width="80">à           </td><td><input type="text" size="3" name="fin_v"    value="<?php echo $fin_v; ?>"> °</td></tr></table></td>
   <td><table><tr><td width="80">en          </td><td><input type="text" size="3" name="div_v"    value="<?php echo $div_v; ?>"> pas</td></tr></table></td>
   </tr>
<tr>
   <td><table><tr><td width="80">longitude: de</td><td><input type="text" size="3" name="deb_h"    value="<?php echo $deb_h; ?>"> °</td></tr></table></td>
   <td><table><tr><td width="80">à            </td><td><input type="text" size="3" name="fin_h"    value="<?php echo $fin_h; ?>"> °</td></tr></table></td>
   <td><table><tr><td width="80">en           </td><td><input type="text" size="3" name="div_h"    value="<?php echo $div_h; ?>"> pas</td></tr></table></td>
   </tr>
<tr>
   <td align="right">constituants <select name="constituants" size="1">
      <option <?php if ($constituants == "points")  echo "selected"; ?> value="points">points</option>
      <option <?php if ($constituants == "faces")  echo "selected"; ?> value="faces">faces</option>
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
   <td align="right" colspan="3">commentaire</br><textarea name="commentaire" cols="50" rows="5"><?php echo $commentaire; ?></textarea></td>
   </tr>
<tr>
   <td align="right" colspan="3">enregistrer sous <input type="text" size="20" name="nom_fichier" value="<?php echo $nom_fichier; ?>"></td>
   </tr>
<tr>
   <td align="left" colspan="3"><input type="submit" name="bt_generer" value="Générer"></td>
   </tr>
</TABLE>
</FORM>
</BODY>
</HTML>