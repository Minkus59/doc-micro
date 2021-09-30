<?php
/***************************************/
// PROGRAMME..... moteur 3D
// MODULE........ librairie: définition objets et méthodes.
// AUTEUR........ N.Dupont-Bloch, 2003
// VERSION....... 10
/***************************************/

define('infini', 1E20);
define('pi', 3.14);

function calculer_angle($px, $py)
   {// détermine angle (par arctangente et quadrant).
   if ($px != 0)
      $phi = abs(atan($py / $px));
   else
      $phi = pi_sur_2; // soit atan(infini)

   if (($px < 0) && ($py >= 0))
      $phi = pi - $phi;
   elseif (($px < 0) && ($py < 0))
      $phi += pi;
   elseif (($px >= 0) && ($py < 0))
      $phi = deux_pi - $phi;
   return $phi;
   }

//************************************************
class Ecran {
   var $largeur,$hauteur;
   var $marge;             // décalage du bord de l'image pendant le clipping.
   var $zoom;              // focale (rudimentaire).
   var $distance_ecran;    // distance entre scène et écran.
   var $x, $y, $z;         // coordonnées par rapport à l'observateur
   var $a, $b, $g;         // angles (degrés) par rapport à l'observateur alpha, beta, gamma
   var $filaire;           // si affichage forçé en filaire.
   var $surlignage;        // si arêtes redessinées.
   var $format_sortie;     // 'png' ou 'jpg'.
   var $eclairage;         // pour calculer couleur résultante de chaque Face.
   var $r_ambiance,        // éclairage d'ambiance uniforme (0,0,0 pour aucun).
       $v_ambiance,
       $b_ambiance;
   
   function Ecran() {
      $this->zoom           = 200; // défaut 200;
      $this->distance_ecran = 100; // défaut 100:
      $this->format_sortie  = 'png';
      $this->largeur    = 640;
      $this->hauteur    = 350;
      $this->marge      = 5;
      $this->filaire    = false;
      $this->surlignage = false;
      $this->eclairage  = 'std';
      $this->x = 0;
      $this->y = 0;
      $this->z = 0;
      $this->a = 0;
      $this->b = 0;
      $this->g = 0;
      }

   function eclairer($r, $v, $b) {
      $this->r_ambiance = $r;
      $this->v_ambiance = $v;
      $this->b_ambiance = $b;
      }

   function inclure($x, $y) {
      return (($x >= $this->marge) &&
              ($x <= $this->largeur - $this->marge) &&
              ($y >= $this->marge) &&
              ($y <= $this->hauteur - $this->marge)); 
      }

   function trouver_secteur($x, $y) {
      /* secteur écran de (x,y):
          A | B | C
          --+---+--
          D | E | F     E = partie affichable de l'écran (moins la marge).
          --+---+--
          G | H | I
       */
      if (($x < $this->marge) &&
          ($y < $this->marge))
         return "A";
      elseif (($x >= $this->marge) && 
              ($x <= $this->largeur - $this->marge) &&
              ($y <  $this->marge))
         return "B";
      elseif (($x > $this->largeur - $this->marge) &&
              ($y <  $this->marge))
         return "C";
      elseif (($x <  $this->marge) && 
              ($y >= $this->marge) &&
              ($y <= $this->hauteur - $this->marge))
         return "D";
      elseif (($x >= $this->marge) &&
              ($x <= $this->largeur - $this->marge) && 
              ($y >= $this->marge) &&
              ($y <= $this->hauteur - $this->marge))
         return "E";
      elseif (($x >  $this->largeur - $this->marge) && 
              ($y >= $this->marge) &&
              ($y <= $this->hauteur - $this->marge))
         return "F";
      elseif (($x <  $this->marge) && 
              ($y >  $this->hauteur - $this->marge))
         return "G";
      elseif (($x >= $this->marge) && 
              ($x <= $this->largeur - $this->marge) &&
              ($y >  $this->hauteur - $this->marge))
         return "H";
      elseif (($x >  $this->largeur - $this->marge) && 
              ($y >  $this->hauteur - $this->marge))
         return "I";
      }
   } // fin Ecran.

//************************************************
class Point {
   var $x,  $y,  $z;  // absolu.
   var $xo, $yo, $zo; // après orientation.
   var $xp, $yp;      // après projection 2D.
   var $delta;        // angle vers le prochain point (pour test de visibilité de la face).
   var $intensite;    // lumen ANSI (si le point est source de lumière).
   var $r, $v, $b;    // RVB (si le point est source de lumière).
   // var $alpha, $beta; // non encore utilisés: angles (rad) d'orientation du flux lumineux (si le point est source de lumière).
   
   function Point($x, $y, $z) {
      $this->x  = $x;
      $this->y  = $y;
      $this->z  = $z;
      $this->xo = $x;
      $this->yo = $y;
      $this->zo = $z;
      $this->intensite = 0;
      }

   function translation($dx, $dy, $dz) {
      $this->xo += $dx;
      $this->yo += $dy;
      $this->zo += $dz;
      }
   
   function rotation($o, $a, $b, $g) {
      // *** tourne autour des coordonnées de l'objet pivot $o.
      $x = $this->xo - $o->xo;
      $y = $this->yo - $o->yo;
      $z = $this->zo - $o->zo;
      
      // *** rotation.
      $long_max = max(50, $x, $y, $z); // distance maxi entre point et centre de son objet parent, pour calcul module.

      if ($a) {//*** orientation alpha => plan (x, y)
         $rho = sqrt(pow($x / $long_max, 2) + pow($y / $long_max, 2));
         $phi = calculer_angle($x, $y);
         $phi += deg2rad($a);
         $x = $long_max * ($rho * cos($phi));
         $y = $long_max * ($rho * sin($phi));
         }

      if ($b) {//*** orientation beta => x, z
         $rho = sqrt(pow($x / $long_max, 2) + pow($z / $long_max, 2));
         $phi = calculer_angle($x, $z);
         $phi += deg2rad($b);
         $x = $long_max * ($rho * cos($phi));
         $z = $long_max * ($rho * sin($phi));
         }

      if ($g) {//*** orientation beta => y, z
         $rho = sqrt(pow($y / $long_max, 2) + pow($z / $long_max, 2));
         $phi = calculer_angle($y, $z);
         $phi += deg2rad($g);
         $y = $long_max * ($rho * cos($phi));
         $z = $long_max * ($rho * sin($phi));
         }
      
      $this->xo = $x;
      $this->yo = $y;
      $this->zo = $z;
      }

   function projeter($e) {
      $x = $this->xo;// + $e->x;
      $y = $this->yo;// + $e->y;
      $z = $this->zo;// + $e->z;

      //*** projection 3D -> 2D
      if ($e->distance_ecran + $z > 0)
         {// si z résultant est devant observateur.
         $xp = $e->largeur / 2 + $e->zoom * ($x / (0.001 + ($e->distance_ecran + $z)));
         $yp = $e->hauteur / 2 - $e->zoom * ($y / (0.001 + ($e->distance_ecran + $z)));
         }
      else
         {// si z résultant est derrière observateur, éviter l'inversion des coordonnées projetées.
         $xp = $e->largeur / 2 + $e->zoom * ($x * (0.001 + ($e->distance_ecran - $z)));
         $yp = $e->hauteur / 2 - $e->zoom * ($y * (0.001 + ($e->distance_ecran - $z)));
         }

      $this->xp = round($xp);
      $this->yp = round($yp);
      }

   function allumer($pi, $pr, $pv, $pb) {
       $this->intensite = $pi;
       $this->r = $pr;
       $this->v = $pv;  
       $this->b = $pb;  
       }
   }// fin Point.

//************************************************
class Face {
   var $r,  $v,  $b;        // RVB absolu.
   var $ro, $vo, $bo;       // RVB après application des sources de lumière avant écrêtage RVB.
   var $delta;              // angles entre les points successifs, pour test visibilité.
   var $xp1, $yp1,          // boite limite à l'écran.
       $xp2, $yp2;
   var $toujours_visible;   // pour les objets plans.   
   var $filaire;            // pour représentation fil de fer.
   var $surlignage;         // dessin des lignes par-dessus les faces (sauf si face filaire).

   function Face($r, $v, $b) {
      $this->r = $r;
      $this->v = $v;
      $this->b = $b;
      $this->ro = 0;
      $this->vo = 0;
      $this->bo = 0;
      $this->delta = array();
      $this->toujours_visible = false;
      $this->filaire          = false;
      $this->surlignage       = true;
      }

   function modifier_apparence($tv, $fil, $surl) {
      $this->toujours_visible = $tv;
      $this->filaire          = $fil;
      $this->surlignage       = $surl;
      }

   function calculer_eclairement($e, $source, $pf, $type_eclairage)
      {// $pf est généralement un point de la face (le 1er, au centre...); c'est sur $pf qu'est calculée l'influence de $source.
      switch($type_eclairage) {
         case "off":
            // Directement les couleurs des faces, sans altération.
            $this->ro = $this->r;
            $this->vo = $this->v;
            $this->bo = $this->b;
            break;
         
         case "std":
            // Eclairage par défaut: lumière blanche à la position de l'écran.
            $seuil = 30;
            $coef  = 0.03;
            $d = 0.1 + $coef * round($pf->zo + $e->distance_ecran);
            $this->ro = max(255, $seuil, $this->r / $d);
            $this->vo = max(255, $seuil, $this->v / $d);
            $this->bo = max(255, $seuil, $this->b / $d);
            break;
      
         case "scn":
            // Distance entre le point $source (de lumière) et le point $pf.
            // Marrant si $pf distant de la face.
            $d = round(sqrt(pow($source->xo - $pf->xo, 2) + pow($source->yo - $pf->yo, 2) + pow($source->zo - $pf->zo, 2)));
            $d_carre = 0.1 + pow($d, 2);
            // Effet de la source de lumière sur la couleur et l'intensité de la Face au point $pf.
            $this->ro += round($this->r * ($source->intensite * $source->r) / $d_carre);
            $this->vo += round($this->v * ($source->intensite * $source->v) / $d_carre);
            $this->bo += round($this->b * ($source->intensite * $source->b) / $d_carre);
            
            // influence de l'éclairage d'ambiance:
            $this->ro += $e->r_ambiance;
            $this->vo += $e->v_ambiance;
            $this->bo += $e->b_ambiance;
            
            // compression.
            $max = max($this->ro, $this->vo, $this->bo);
            if ($max > 255) {
               $m = 255 / $max;
               $this->ro = round($this->ro * $m);
               $this->vo = round($this->vo * $m);
               $this->bo = round($this->bo * $m);
               }
            break;
         } // end switch.
      }

   function calculer_boite_limite($f)
      {// trouve les coordonnées extrêmes après projection.
      $this->xp1 =  infini; $this->yp1 =  infini;
      $this->xp2 = -infini; $this->yp2 = -infini;
      $i = 1;
      while(isset($f[$i]["point"])) {
         $this->xp1 = min($this->xp1, $f[$i]["point"]->xp);
         $this->yp1 = min($this->yp1, $f[$i]["point"]->yp);
         $this->xp2 = max($this->xp2, $f[$i]["point"]->xp);
         $this->yp2 = max($this->yp2, $f[$i]["point"]->yp);
         $i++;
         }  
      }

   function tournee_vers_observateur()
      {// si par plus d'une fois l'angle que fait un point avec le point précédent diminue,
       // c'est que le sens de la face est inversé pr rapport à l'observateur.
      if ($this->toujours_visible)
         return true; // face visible de tous les angles, par ex. objet plan, dérive d'avion...
      
      $nb_diminution_angle = 0;
      $no_angle = 2;
      while(isset($this->delta[$no_angle])) {
         if ($this->delta[$no_angle] < $this->delta[$no_angle - 1])
            $nb_diminution_angle++;
         $no_angle++;
         }
      return ($nb_diminution_angle < 2);
      }
   } // fin Face.

//************************************************
class Objet {
   var $nom;
   var $parent;            // pour futur héritage, non encore utilisé.
   var $x, $y, $z;         // placement (x,y,z) par rapport au centre absolu.
   //var $a, $b, $g;         // rotation (alpha, beta, gamma) par rapport au centre absolu.
   var $barycentre_xo,     // pour calcul: centre géométrique 3D de l'objet. 
       $barycentre_yo, 
       $barycentre_zo;
   var $distance;          // pour calcul: distance entre barycentre et observateur.
   var $filaire;           // si true, tjrs affiché en fil de fer.
   
   function Objet($n, $p, $x, $y, $z) {
      $this->nom    = $n;
      $this->parent = $p;
      $this->x = $x;
      $this->y = $y;
      $this->z = $z;
      $this->a = 0;
      $this->b = 0;
      $this->g = 0;
      $this->filaire = false;
      }
   
   function translation(&$o, $x, $y, $z) {
      $no_face = 1;
      while ($o[$no_face]) {
         $no_point = 1;
         while ($o[$no_face][$no_point]) {
            $o[$no_face][$no_point]["point"]->translation($x, $y, $z);
            $no_point++;
            }
         $no_face++;
         }
      }
   
   function rotation(&$o, $op, $a, $b, $g) {
      for ($no_face=1; isset($o[$no_face]["face"]); $no_face++)
         for ($no_point=1; isset($o[$no_face][$no_point]["point"]); $no_point++)
            $o[$no_face][$no_point]["point"]->rotation($op["objet"], $a, $b, $g);
      }
   
   function rvbi(&$o, $r, $v, $b, $i) {
      for ($no_face=1; isset($o[$no_face]["face"]); $no_face++) {
         $o[$no_face]["face"]->r = $r;
         $o[$no_face]["face"]->v = $v;
         $o[$no_face]["face"]->b = $b;
         $o[$no_face]["face"]->i = $i;
         }
      }
   
   function calculer_barycentre($o)
      {// trouve le centre spatial de l'objet après orientation.
      $xmin =  infini; $ymin =  infini; $zmin =  infini;
      $xmax = -infini; $ymax = -infini; $zmax = -infini;
      $i = 1;
      while(isset($o[$i])) {
         $ii = 1;
         while(isset($o[$i][$ii])) {
            $xmin = min($xmin, $o[$i][$ii]["point"]->xo);
            $xmax = max($xmax, $o[$i][$ii]["point"]->xo);
            $ymin = min($ymin, $o[$i][$ii]["point"]->yo);
            $ymax = max($ymax, $o[$i][$ii]["point"]->yo);
            $zmin = min($zmin, $o[$i][$ii]["point"]->zo);
            $zmax = max($zmax, $o[$i][$ii]["point"]->zo);
            $ii++;
            }     
         $i++;
         }  
      $this->barycentre_xo = $xmax - (($xmax - $xmin) / 2);
      $this->barycentre_yo = $ymax - (($ymax - $ymin) / 2);
      $this->barycentre_zo = $zmax - (($zmax - $zmin) / 2);
      }
   
   function calculer_distance($e) {
      $this->distance = ($e->distance_ecran + $this->barycentre_zo) + abs($this->barycentre_yo) + abs($this->barycentre_xo);
      $this->distance = round($this->distance, 2);
      return 1/(0.0001 + $this->distance); // fraction pour éviter division par zéro.
      }  
   }

?>
