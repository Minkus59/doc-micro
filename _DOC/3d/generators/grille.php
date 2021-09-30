<?php
/***************************************/
// PROGRAMME..... moteur 3D
// MODULE........ création de grille (pour quadrillage au sol, etc).
// AUTEUR........ N.Dupont-Bloch
// DATE.......... 7/10/8/2003
// VERSION....... 1.0
/***************************************/
require("../3d_fic.php");
require('../3d_config.inc.php');
?>

<HTML>
<HEAD>
<TITLE>3D-Grille (1.0)</TITLE>
</HEAD>


<?php
/*
// grille.
for ($i = 0; $i < 8; $i++)
   {
   for ($ii = 0; $ii < 5; $ii++)
      {
      $no_objet++;
      $t_o[$no_objet]["objet"] = new Objet('', '', 0,0,0);
      charger_objet($t_o[$no_objet], $parametres->dir_objets .'carre.3d');
      $t_o[$no_objet]["objet"]->translation($t_o[$no_objet], -40 + ($i * 10), 0, -5 + ($ii * 10));
      $t_o[$no_objet]["objet"]->filaire = true;
      }
   }
*/

$parametres = new Parametres();
$dir_racine = '../'; // par rapport à 3d_config.inc.php.

define(c_script_version, "3D-Grille 1.0");

//*** récupérer paramètres de génération de la grille.
// dimensions:
if (isset($HTTP_POST_VARS["largeur"]))
   {$largeur = $HTTP_POST_VARS["largeur"];   }
else
   {$largeur = 5;   }

if (isset($HTTP_POST_VARS["div_largeur"]))
   {$div_largeur = $HTTP_POST_VARS["div_largeur"];   }
else
   {$div_largeur = 2;   }

if (isset($HTTP_POST_VARS["profondeur"]))
   {$profondeur = $HTTP_POST_VARS["profondeur"];   }
else
   {$profondeur = 5;   }

if (isset($HTTP_POST_VARS["div_profondeur"]))
   {$div_profondeur = $HTTP_POST_VARS["div_profondeur"];   }
else
   {$div_profondeur = 2;   }

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
   $commentaire  = "# grille générée par " .c_script_version .c_fichier_separateur_ligne;
   }

//*** générer et enregistrer le cube avec les paramètres récupérés.
$fd = fopen($dir_racine .$parametres->dir_objets .$nom_fichier, "w");

$ligne = $commentaire;
fputs($fd, $ligne .c_fichier_separateur_ligne);

$intervalle_largeur    = $largeur    / $div_largeur;
$intervalle_profondeur = $profondeur / $div_profondeur;


//******
for ($i = - $profondeur / 2; $i < $profondeur / 2; $i += $intervalle_profondeur)
   {
   for ($ii = - $largeur / 2; $ii < $largeur / 2; $ii += $intervalle_largeur)
      {
      // enregistrer face.
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
      
      if ($constituants == "faces")
         {
         //*** calcul des points.
         $x1 = $ii;
         $y1 = $hauteur/2;
         $z1 = $i + $intervalle_profondeur;

         $x2 = $ii + $intervalle_largeur;
         $y2 = $hauteur/2;
         $z2 = $i + $intervalle_profondeur;

         $x3 = $ii + $intervalle_largeur;
         $y3 = $hauteur/2;
         $z3 = $i;

         $x4 = $ii;
         $y4 = $hauteur/2;
         $z4 = $i;

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
         }

      elseif ($constituants == "points")
         {
         //*** calcul des points.
         $x = $ii + ($intervalle_profondeur / 2);
         $y = 0;
         $z = $i + ($intervalle_profondeur / 2);

         $ligne  = "p" .c_fichier_separateur_colonne;
         $ligne .= $x .c_fichier_separateur_colonne;
         $ligne .= $y .c_fichier_separateur_colonne;
         $ligne .= $z .c_fichier_separateur_colonne;
         fputs($fd, $ligne .c_fichier_separateur_ligne);
         }
      
      fputs($fd, c_fichier_separateur_ligne);
      }
   }

fclose($fd);
?>

<BODY>
<FORM name="frm_sphere" method="POST" action="<?php echo $self; ?>">
<TABLE border="1" cellspacing="1" bgcolor="#7EABFE">
<tr>
   <td align="right">largeur        <input type="text" size="3" name="largeur" value="<?php echo $largeur; ?>"></td>
   <td align="left" colspan="2">en <input type="text" size="3" name="div_largeur" value="<?php echo $div_largeur; ?>"> pas</td>
   </tr>
<tr>
   <td align="right">profondeur     <input type="text" size="3" name="profondeur" value="<?php echo $profondeur; ?>"></td>
   <td align="left" colspan="2">en <input type="text" size="3" name="div_profondeur" value="<?php echo $div_profondeur; ?>"> pas</td>
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
   <td align="left" colspan="3">commentaire</br><textarea name="commentaire" cols="50" rows="5"><?php echo $commentaire; ?></textarea></td>
   </tr>
<tr>
   <td align="right" colspan="3">enregistrer sous <input type="text" size="40" name="nom_fichier" value="<?php echo $nom_fichier; ?>"></td>
   </tr>
<tr>
   <td align="left" colspan="3"><input type="submit" name="bt_generer" value="Générer"></td>
   </tr>
</TABLE>
</FORM>
</BODY>
</HTML>