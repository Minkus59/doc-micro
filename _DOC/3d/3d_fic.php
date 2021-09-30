<?php
/***************************************/
// PROGRAMME..... moteur 3D
// MODULE........ chargement des objets depuis les fichiers ".3D".
// AUTEUR........ N.Dupont-Bloch, 2003
// VERSION....... 1.10
/***************************************/

define(c_fichier_separateur_colonne, ";");
define(c_fichier_separateur_ligne,   "\n");

function charger_objet(&$o, $nf)
/************************/
   {
   $no_face  = 0;

   $fd = fopen ($nf, "r");
   while (!feof ($fd))
      {
      $ligne = fgets($fd, 4096);
      switch (substr($ligne, 0, 1))
         {
         case "f":
            $no_face ++;
            $no_point = 0;
            $c = explode(c_fichier_separateur_colonne, $ligne);
            $r          = $c[1];
            $v          = $c[2];
            $b          = $c[3];
            $filaire    = $c[4];
            $surlignage = $c[5];
            $o[$no_face]["face"] = new Face($r, $v, $b);
            $o[$no_face]["face"]->filaire    = ($filaire    == "true");
            $o[$no_face]["face"]->surlignage = ($surlignage == "true");
            break;
         case "p":
            $no_point ++;
            $c = explode(c_fichier_separateur_colonne, $ligne);
            $x = $c[1];
            $y = $c[2];
            $z = $c[3];
            $o[$no_face][$no_point]["point"] = new Point($x, $y, $z);
            break;
         }
      }
   if ($no_point < 3)
      {// simple point ou simple ligne.
      $o[$no_face]["face"]->filaire    = true;	
      $o[$no_face]["face"]->surlignage = false;
      }	
   fclose ($fd);
   }
   
/*
// version "manuelle:
$o[1]["face"]     = new Face("#FF0000");
$o[1][1]["point"] = new Point(-5, 5, 5);
$o[1][2]["point"] = new Point(5, 5, 5);
$o[2]["face"]     = new Face("#00FF00");
$o[2][1]["point"] = new Point(-5, -5, 5);
$o[2][2]["point"] = new Point(5, -5, 5);
*/
?>
