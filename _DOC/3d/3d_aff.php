<?php
/***************************************/
// PROGRAMME..... moteur de visualisation 3D
// MODULE........ affichage
// AUTEUR........ N.Dupont-Bloch, 2003
// VERSION....... 10
// PARAMETRES.... dx, dy, dz: translation observateur en degrés.
//                da, db, dg: rotation observateur en degrés.
//                fil: booléen:   vue filaire / vue faces cachées.
//                imge: mode d'éclairage de la scène.
//                imgx, imgy: largeur et hauteur de l'image.
//                imgf: générer une image "jpg" / "png".
//                imgs: force le surlignage des faces.
//                imgz: zoom.
//                fnts: taille police.
//                bckc: couleur de fond, format "#FFFFFF".
//                bcki: image de fond (attention à la réduction de la palette!)
//                fin:  fichier texte de commandes de construction d'image.
//                fout: fichier jpg ou png de sortie.
//                scn:  no de la scène. Utilise alors p1 comme no d'image.
//                dbsrv:nom ou @ IP du serveur de SGBD (MySql, ...).
//                p1: paramètre libre, pour application, ou n° de l'image en association avec scn.
/***************************************/

require('3d_config.inc.php');
require('3d_lib.php');
require('3d_fic.php');
require('3d_database.inc.php');

$ecran = new Ecran();
$parametres = new Parametres();

//*****************************
// création des objets.
$t_o = array();
$no_obj = 0;

// création des sources de lumière.
$t_l = array();
$no_lumiere = 0;

//*****************************
// interpreteur.
//*****************************
$ecran->a = $da;
$ecran->b = $db;
$ecran->g = $dg;
$ecran->x = $dx;
$ecran->y = $dy;
$ecran->z = $dz;

$script_dispo = false;
if ($fin) {
   $script = new Csv();
   $script_dispo = $script->ouvrir($parametres->dir_anims .$fin);
   }
elseif (($dbsrv) && ($scn)) {
   //*** si serveur et scène sont dans l'url d'appel, on charge une image SQL.
   $script = new Mysql();
   $script_dispo = $script->ouvrir($dbsrv, $parametres->database, $parametres->user_database, '');
   }

if ($script_dispo) {
   if ($script->type_database == 'MYSQL') {
      $req  = 'SELECT tab_act.id_scn, tab_act.id_img, tab_act.id_act, tab_act.act';
      $req .= ' FROM tab_act';
      $req .= ' WHERE tab_act.id_img = ' .$p1;
      $req .= ' AND   tab_act.id_scn = ' .$scn;
      $req .= ' ORDER by tab_act.id_scn, tab_act.id_img, tab_act.id_act';
      $script->requete($req);
      }
   $c = $script->lire();
   while ($c) {
      $c = explode(';', $c);
      switch ($c[0])  {
         case 'objet':
            switch ($c[1]) {
               case 'charger':
                  $no_obj++;
                  $t_o[$no_obj]["objet"] = new Objet($c[2], '', 0, 0, 0);
                  charger_objet($t_o[$no_obj], $parametres->dir_objets .$c[3]);
                  // ignore $c[4] (objet parent, non implémenté).
                  break;
               case 'translation':
                  // ignore $c[2] (nom de l'objet, non implémenté).
                  for ($f=1; $t_o[$no_obj][$f]["face"]; $f++)
                     for ($p=1; $t_o[$no_obj][$f][$p]["point"]; $p++) {
                        $t_o[$no_obj][$f][$p]["point"]->xo += $c[3];
                        $t_o[$no_obj][$f][$p]["point"]->yo += $c[4];
                        $t_o[$no_obj][$f][$p]["point"]->zo += $c[5];}
                  break;
               case 'rotation':
                  // ignore $c[2] (nom de l'objet, non implémenté).
                  for ($f=1; $t_o[$no_obj][$f]["face"]; $f++)
                     for ($p=1; $t_o[$no_obj][$f][$p]["point"]; $p++)
                        $t_o[$no_obj][$f][$p]["point"]->rotation($t_o[$o], $c[3], $c[4], $c[5]);
                  break;
               case 'rvbi':
                  // ignore $c[2] (nom de l'objet, non implémenté).
                  $t_o[$no_obj]["objet"]->rvbi($t_o[$no_obj], $c[3], $c[4], $c[5], $c[6]);
                  break;
               case 'homotetie':
                  // ignore $c[2] (nom de l'objet, non implémenté).
                  for ($f=1; isset($t_o[$no_obj][$f]); $f++) {
                     for ($p=1; isset($t_o[$no_obj][$f][$p]["point"]); $p++) {
                        $t_o[$no_obj][$f][$p]["point"]->xo *= $c[3];
                        $t_o[$no_obj][$f][$p]["point"]->yo *= $c[3];
                        $t_o[$no_obj][$f][$p]["point"]->zo *= $c[3];
                        }
                     }
                  break;
               case 'deplacement_point':
                  // ignore $c[2] (nom de l'objet, non implémenté).
                  // $c3 = no_face, $c4 = no_point; $c5 à $c7 = nouvelles coordonnées absolues.
                  $t_o[$no_obj][$c[3]][$c[4]]["point"]->xo = $c[5];
                  $t_o[$no_obj][$c[3]][$c[4]]["point"]->yo = $c[6];
                  $t_o[$no_obj][$c[3]][$c[4]]["point"]->zo = $c[7];
                  break;
               }
            break;
         case 'lumiere':
            switch ($c[1]) {
               case 'creer':
                  $no_obj++;
                  $t_o[$no_obj]["objet"] = new Objet($c[2],'',0,0,0);
                  charger_objet($t_o[$no_obj], $parametres->dir_objets .'point.3d');
                  $no_lumiere ++;
                  $t_l[$no_lumiere] = $no_obj;
                  break;
               case 'allumer':
               case 'irvb':
                  $t_o[$no_obj][1][1]["point"]->allumer($c[3], $c[4], $c[5], $c[6]); // lumen, r, v, b.
                  break;
               case 'translation':
                  // ignore $c[2] (nom de la source de lumière, non implémenté).
                  $t_o[$no_obj]["objet"]->translation($t_o[$no_obj], $c[3], $c[4], $c[5]);
                  break;
               }
            break;
         case 'ecran':
            switch ($c[1]) {
               case 'filaire':
                  // ignore $c[2] (nom de l'écran, non implémenté).
                  $ecran->filaire = ($c[3] == 'true');
                  break;
               case 'surlignage':
                  // ignore $c[2] (nom de l'écran, non implémenté).
                  $ecran->surlignage = ($c[2] == 'true');
                  break;
               case 'zoom':
                  // ignore $c[2] (nom de l'écran, non implémenté).
                  $ecran->zoom = $c[3];
                  break;
               case 'rvb':
                  // ignore $c[2] (nom de l'écran, non implémenté).
                  $ecran->eclairer($c[3], $c[4], $c[5]);
                  break;
               case 'format_sortie':
                  // ignore $c[2] (nom de l'écran, non implémenté).
                  $ecran->format_sortie = $c[3];
                  break;
               case 'translation':
                  // ignore $c[2] (nom de l'écran, non implémenté).
                  $ecran->x = $c[3];
                  $ecran->y = $c[4];
                  $ecran->z = $c[5];
                  for ($o=1; $t_o[$o]["objet"]; $o++)
                     for ($f=1; $t_o[$o][$f]["face"]; $f++)
                        for ($p=1; $t_o[$o][$f][$p]["point"]; $p++)
                           $t_o[$o][$f][$p]["point"]->translation($c[3] +$dx, $c[4] +$dy, $c[5] +$dz);
                  break;
               case 'rotation':
                  // ignore $c[2] (nom de l'écran, non implémenté).
                  $ecran->a = $c[3];
                  $ecran->b = $c[4];
                  $ecran->g = $c[5];
                  for ($o=1; $t_o[$o]["objet"]; $o++)
                     for ($f=1; $t_o[$o][$f]["face"]; $f++)
                        for ($p=1; $t_o[$o][$f][$p]["point"]; $p++)
                           $t_o[$o][$f][$p]["point"]->rotation($ecran, $c[3] +$da, $c[4] +$db, $c[5] +$dg);
                  break;
               case 'distance':
                  // ignore $c[2] (nom de l'écran, non implémenté).
                  $ecran->distance_ecran = $c[3];
                  break;
               case 'eclairage':
                  // ignore $c[2] (nom de l'écran, non implémenté).
                  $ecran->eclairage = $c[3];
                  break;
               case 'dimension':
                  // ignore $c[2] (nom de l'écran, non implémenté).
                  $ecran->largeur = $c[3];
                  $ecran->hauteur = $c[4];
                  break;
               }
            break;
         }
      $c = $script->lire();
      }
   }
$script->fermer();
//*** fin interpréteur.

//*****************************
// paramètres de l'URL (prioritaires sur un éventuel script en cours).
//*****************************
$font_size = 2;
if (isset($imgx) && $imgx)
   $ecran->largeur = $imgx;
if (isset($imgy) && $imgy)
   $ecran->hauteur = $imgy;
$ecran->filaire = ($fil  == 'true');
$ecran->surlignage  = ($imgs == 'true');
$ecran->format_sortie = $imgf;
$ecran->eclairage = $imge;

if (!isset($imgz) || (!$imgz))
   $imgz = 200;
$ecran->zoom = $imgz;     // courte focale=100; moyenne=250; longue focale=2000.
$ecran->distance_ecran = round($imgz / 2.5);

//*** police
if (!isset($fnts) || (!$fnts))
   $font_size = 2;
else
   $font_size = $fnts;

//*** image de fond
if (isset($bcki) && ($bcki))
   $image1 = imagecreatefromjpeg($bcki);
else
   $image1 = imagecreate($ecran->largeur, $ecran->hauteur);

$noir  = imagecolorallocate($image1, 0, 0, 0);   // surlignage.
$vert  = imagecolorallocate($image1, 0, 255, 0); // noms objets.

if (isset($bckc) && ($bckc)) {
   $r = base_convert(substr($bckc, 1, 2), 16, 10);
   $v = base_convert(substr($bckc, 3, 2), 16, 10);
   $b = base_convert(substr($bckc, 5, 2), 16, 10);
   $parametres->coul_fond = imagecolorallocate($image1, $r, $v, $b);
   }
else {
   $r = base_convert(substr($parametres->coul_fond, 1, 2), 16, 10);
   $v = base_convert(substr($parametres->coul_fond, 3, 2), 16, 10);
   $b = base_convert(substr($parametres->coul_fond, 5, 2), 16, 10);
   $parametres->coul_fond = imagecolorallocate($image1, $r, $v, $b);
   }
imagefill($image1, 1,1, $parametres->coul_fond);
   

//*****************************
/* version "manuelle" d'accès aux faces et points de l'objet:
$t_o[1]["objet"]       = new Objet("nom", "nom_parent", 10, 0.5, -10);
$t_o[1][1]["face"]     = new Face(255, 0, 0);
$t_o[1][1][1]["point"] = new Point(-5, 5, 5);
$t_o[1][1][2]["point"] = new Point(5, 5, 5);
$t_o[1][2]["face"]     = new Face(0, 255, 0);
$t_o[1][2][1]["point"] = new Point(-5, -5, 5);
$t_o[1][2][2]["point"] = new Point(5, -5, 5);
*/

//*****************************
// préliminaires: calcul faces cachées, distance.
$no_obj = 1;
$t_od = array(); // objets à trier par distance à l'observateur.
while ($t_o[$no_obj])
   {// parcourt les objets.
   $no_face = 1;
   while ($t_o[$no_obj][$no_face]["face"])
      {// parcourt et calcule les faces.
      $no_point = 1;
      while ($t_o[$no_obj][$no_face][$no_point]["point"])
         {// parcourt et calcule les points.
         $t_o[$no_obj][$no_face][$no_point]["point"]->projeter($ecran);
         if (($no_point > 1) && 
             (!$t_o[$no_obj][$no_face]["face"]->filaire) &&
             (!$t_o[$no_obj]["objet"]->filaire) &&
             (!$ecran->filaire))
            {// calcule l'angle formé avec le point précédent (pour calculer + tard les faces cachées).
            $x = $t_o[$no_obj][$no_face][$no_point]["point"]->xp - $t_o[$no_obj][$no_face][$no_point - 1]["point"]->xp;
            $y = $t_o[$no_obj][$no_face][$no_point]["point"]->yp - $t_o[$no_obj][$no_face][$no_point - 1]["point"]->yp;
            $t_o[$no_obj][$no_face]["face"]->delta[$no_point] = calculer_angle($x, $y);
            }
         $no_point++;
         // fin points.
         }
      $t_o[$no_obj][$no_face]["face"]->calculer_boite_limite($t_o[$no_obj][$no_face]);
      $no_face++;
      // fin face.
      }
   $t_o[$no_obj]["objet"]->calculer_barycentre($t_o[$no_obj]);
   $t_od[$no_obj]["distance"] = $t_o[$no_obj]["objet"]->calculer_distance($ecran);
   $t_od[$no_obj]["no_objet"] = $no_obj;
   $no_obj++;
   // fin objet.
   }
if (!$ecran->filaire)
   sort($t_od); // trie les objets par distance.

// **********************************************
// affichage.
foreach($t_od as $v) {
   $no_obj = $v["no_objet"];
   if ($t_o[$no_obj])
      {// parcourt les objets triés par distance.
      // prépare coordonnées bas gauche projection objet pour afficher son nom,
      // et boîte limite de la face pour l'afficher seulement si elle est dans l'écran.
      $x_min = infini;
      $y_max = 0;
      $no_face = 1;
      $t_couleur = array();
      $no_couleur = 0;
      while ($t_o[$no_obj][$no_face]["face"])
         {// parcourt et calcule les faces.
         if (($t_o[$no_obj][$no_face]["face"]->tournee_vers_observateur()) || 
             ($t_o[$no_obj][$no_face]["face"]->toujours_visible) || 
             ($t_o[$no_obj][$no_face]["face"]->filaire) ||
             ($t_o[$no_obj]["objet"]->filaire) ||
             ($ecran->filaire)) {
            //*************************************************
            // boîte limite de la face comprise au moins en partie dans l'écran?
            if (($t_o[$no_obj][$no_face]["face"]->xp1 >= $ecran->marge) ||
                ($t_o[$no_obj][$no_face]["face"]->yp1 >= $ecran->marge) ||
                ($t_o[$no_obj][$no_face]["face"]->xp2 <= $ecran->largeur - $ecran->marge) ||
                ($t_o[$no_obj][$no_face]["face"]->yp2 <= $ecran->hauteur - $ecran->marge))
               {//*************************************************
               // liste des points à partir du 1er dans l'écran (nécessaire pour clipping des angles).
               /*
               $f = $t_o[$no_obj][$no_face];
               $ii_debut = 0;
               for ($ii = 1; isset($f[$ii]); $ii++)
                  {
                  if ($ecran->inclure($f[$ii]))
                     {// détecter le 1er point dans l'écran.
                     $ii_debut = $ii;
                     }
                  // puis charger $t_o[$no_obj][$no_face][]["point"] avec les points de $f lus à partir du 1er (xp, yp) dans l'écran.
                  }
               */
               
               $t_pe = array(); // pour dessiner ultérieurement la face (indices impairs=x, pairs=y).
               if ($t_o[$no_obj][$no_face][2]["point"])
                  {//*************************************************
                  //// si la face comporte au moins 2 points.
                  //*** pour chaque point de la face.
                  $i     =  1;
                  $pe    = -1;
                  $nb_pe =  0;
                  $xmin  = $ecran->marge; $xmax = $ecran->largeur - $ecran->marge;
                  $ymin  = $ecran->marge; $ymax = $ecran->hauteur - $ecran->marge;
                  
                  // chercher le dernier point.
                  $imax  = 2;
                  while (isset($t_o[$no_obj][$no_face][$imax+1]["point"]))
                     $imax++;
                  
                  if ($ecran->filaire) {
                     // clipping un peu superflu si tout est filaire.
                     for ($p=1; $t_o[$no_obj][$no_face][$p]["point"]; $p++) {
                        $pe++; $t_pe[$pe] = $t_o[$no_obj][$no_face][$p]["point"]->xp;
                        $pe++; $t_pe[$pe] = $t_o[$no_obj][$no_face][$p]["point"]->yp;
                        $nb_pe++;
                        }
                     }
                  else {
                     //*************************************************
                     // clipping: réduire la face à la partie visible sur l'écran.
                     while ($t_o[$no_obj][$no_face][$i]["point"]) {
                        $x1 = $t_o[$no_obj][$no_face][$i]["point"]->xp;
                        $y1 = $t_o[$no_obj][$no_face][$i]["point"]->yp;

                        // cherche indice du point précédent.
                        if ($i == 1)
                           $point_precedent = $imax - 1;
                        else
                           $point_precedent = $i - 1;

                        // cherche indice du point suivant.
                        if ($i < $imax)
                           $point_suivant = $i+1;
                        else
                           $point_suivant = 2;

                        // limiter les points à l'écran affichable.
                        $secteur_debut = "";
                        switch($ecran->trouver_secteur($x1, $y1)) {
                           case "E":
                              //*** point compris dans l'écran.
                              $secteur_debut = "E";
                              $pe++; $t_pe[$pe] = $x1;
                              $pe++; $t_pe[$pe] = $y1;
                              $nb_pe++;
                              break;

                           case "A": //*** clipping haut gauche
                              if (($t_o[$no_obj][$no_face][$point_precedent]["point"]->xp >= $xmin) &&
                                  ($t_o[$no_obj][$no_face][$point_precedent]["point"]->yp >= $ymin)) {
                                 $x2 = $t_o[$no_obj][$no_face][$point_precedent]["point"]->xp;
                                 $y2 = $t_o[$no_obj][$no_face][$point_precedent]["point"]->yp;
                                 $x0 = $xmin;
                                 $y0 = $y2 - ((($x2 - $x0) * ($y2 - $y1)) / (0.0001 + $x2 - $x1));
                                 if ($ecran->inclure($x0, $y0)) {
                                    $secteur_debut = "D";
                                    $pe++; $t_pe[$pe] = $x0;
                                    $pe++; $t_pe[$pe] = $y0;
                                    $nb_pe++;
                                    }
                                 }

                              if (($t_o[$no_obj][$no_face][$point_suivant]["point"]->xp >= $xmin) &&
                                  ($t_o[$no_obj][$no_face][$point_suivant]["point"]->yp >= $ymin)) {
                                 $x2 = $t_o[$no_obj][$no_face][$point_suivant]["point"]->xp;
                                 $y2 = $t_o[$no_obj][$no_face][$point_suivant]["point"]->yp;
                                 $y0 = $ymin;
                                 $x0 = $x2 - ((($x2 - $x1) * ($y2 - $y0)) / (0.0001 + $y2 - $y1));
                                 if ($ecran->inclure($x0, $y0)) {
                                    if (($secteur_debut == "D") ||
                                        ($secteur_debut == "G") ||
                                        ($secteur_debut == "H") ||
                                        ($secteur_debut == "I") ||
                                        ($secteur_debut == "F"))
                                       {// ajouter point intermédiaire à l'angle A.
                                       $pe++; $t_pe[$pe] = $xmin;
                                       $pe++; $t_pe[$pe] = $ymin;
                                       $nb_pe++;
                                       } 
                                    $secteur_debut = "B";
                                    $pe++; $t_pe[$pe] = $x0;
                                    $pe++; $t_pe[$pe] = $y0;
                                    $nb_pe++;
                                    }
                                 }
                              break;

                           case "B": //*** clipping haut
                              if ($t_o[$no_obj][$no_face][$point_precedent]["point"]->yp >= $ymin) {
                                 $x2 = $t_o[$no_obj][$no_face][$point_precedent]["point"]->xp;
                                 $y2 = $t_o[$no_obj][$no_face][$point_precedent]["point"]->yp;
                                 $y0 = $ymin;
                                 $x0 = $x2 - ((($x2 - $x1) * ($y2 - $y0)) / (0.0001 + $y2 - $y1));
                                 if ($ecran->inclure($x0, $y0)) {
                                    $secteur_debut = "B";
                                    $pe++; $t_pe[$pe] = $x0;
                                    $pe++; $t_pe[$pe] = $y0;
                                    $nb_pe++;
                                    }
                                 }

                              if ($t_o[$no_obj][$no_face][$point_suivant]["point"]->yp >= $ymin) {
                                 $x2 = $t_o[$no_obj][$no_face][$point_suivant]["point"]->xp;
                                 $y2 = $t_o[$no_obj][$no_face][$point_suivant]["point"]->yp;
                                 $y0 = $ymin;
                                 $x0 = $x2 - ((($x2 - $x1) * ($y2 - $y0)) / (0.0001 + $y2 - $y1));
                                 if ($ecran->inclure($x0, $y0)) {
                                    if (($secteur_debut == "C") ||
                                        ($secteur_debut == "D") ||
                                        ($secteur_debut == "G") ||
                                        ($secteur_debut == "H") ||
                                        ($secteur_debut == "I") ||
                                        ($secteur_debut == "F"))
                                       {// ajouter point intermédiaire à l'angle A.
                                       $pe++; $t_pe[$pe] = $xmin;
                                       $pe++; $t_pe[$pe] = $ymin;
                                       $nb_pe++;
                                       } 
                                    $secteur_debut = "B";
                                    $pe++; $t_pe[$pe] = $x0;
                                    $pe++; $t_pe[$pe] = $y0;
                                    $nb_pe++;
                                    }
                                 }
                              break;

                           case "C": //*** clipping haut droit
                              if (($t_o[$no_obj][$no_face][$point_precedent]["point"]->xp <= $xmax) &&
                                  ($t_o[$no_obj][$no_face][$point_precedent]["point"]->yp >= $ymin)) {
                                 $x2 = $t_o[$no_obj][$no_face][$point_precedent]["point"]->xp;
                                 $y2 = $t_o[$no_obj][$no_face][$point_precedent]["point"]->yp;
                                 $y0 = $ymin;
                                 $x0 = $x2 - ((($x2 - $x1) * ($y2 - $y0)) / (0.0001 + $y2 - $y1));
                                 if ($ecran->inclure($x0, $y0)) {
                                    $secteur_debut = "B";
                                    $pe++; $t_pe[$pe] = $x0;
                                    $pe++; $t_pe[$pe] = $y0;
                                    $nb_pe++;
                                    }
                                 }

                              if (($t_o[$no_obj][$no_face][$point_suivant]["point"]->xp <= $xmax) &&
                                  ($t_o[$no_obj][$no_face][$point_suivant]["point"]->yp >= $ymin)) {
                                 $x2 = $t_o[$no_obj][$no_face][$point_suivant]["point"]->xp;
                                 $y2 = $t_o[$no_obj][$no_face][$point_suivant]["point"]->yp;
                                 $x0 = $xmax;
                                 $y0 = $y2 - ((($x2 - $x0) * ($y2 - $y1)) / (0.0001 + $x2 - $x1));
                                 if ($ecran->inclure($x0, $y0)) {
                                    $secteur_debut = "F";
                                    $pe++; $t_pe[$pe] = $x0;
                                    $pe++; $t_pe[$pe] = $y0;
                                    $nb_pe++;
                                    }
                                 }
                              break;

                           case "D": //*** clipping gauche
                              if ($t_o[$no_obj][$no_face][$point_precedent]["point"]->xp >= $xmin) {
                                 $x2 = $t_o[$no_obj][$no_face][$point_precedent]["point"]->xp;
                                 $y2 = $t_o[$no_obj][$no_face][$point_precedent]["point"]->yp;
                                 $x0 = $xmin;
                                 $y0 = $y2 - ((($x2 - $x0) * ($y2 - $y1)) / (0.0001 + $x2 - $x1));
                                 if ($ecran->inclure($x0, $y0)) {
                                    $secteur_debut = "D";
                                    $pe++; $t_pe[$pe] = $x0;
                                    $pe++; $t_pe[$pe] = $y0;
                                    $nb_pe++;
                                    }
                                 }

                              if ($t_o[$no_obj][$no_face][$point_suivant]["point"]->xp >= $xmin) {
                                 $x2 = $t_o[$no_obj][$no_face][$point_suivant]["point"]->xp;
                                 $y2 = $t_o[$no_obj][$no_face][$point_suivant]["point"]->yp;
                                 $x0 = $xmin;
                                 $y0 = $y2 - ((($x2 - $x0) * ($y2 - $y1)) / (0.0001 + $x2 - $x1));
                                 if ($ecran->inclure($x0, $y0)) {
                                    $secteur_debut = "D";
                                    $pe++; $t_pe[$pe] = $x0;
                                    $pe++; $t_pe[$pe] = $y0;
                                    $nb_pe++;
                                    }
                                 }
                              break;

                           case "F": //*** clipping droit
                              if ($t_o[$no_obj][$no_face][$point_precedent]["point"]->xp <= $xmax) {
                                 $x2 = $t_o[$no_obj][$no_face][$point_precedent]["point"]->xp;
                                 $y2 = $t_o[$no_obj][$no_face][$point_precedent]["point"]->yp;
                                 $x0 = $xmax;
                                 $y0 = $y2 - ((($x2 - $x0) * ($y2 - $y1)) / (0.0001 + $x2 - $x1));
                                 if ($ecran->inclure($x0, $y0)) {
                                    $secteur_debut = "F";
                                    $pe++; $t_pe[$pe] = $x0;
                                    $pe++; $t_pe[$pe] = $y0;
                                    $nb_pe++;
                                    }
                                 }

                              if ($t_o[$no_obj][$no_face][$point_suivant]["point"]->xp <= $xmax) {
                                 $x2 = $t_o[$no_obj][$no_face][$point_suivant]["point"]->xp;
                                 $y2 = $t_o[$no_obj][$no_face][$point_suivant]["point"]->yp;
                                 $x0 = $xmax;
                                 $y0 = $y2 - ((($x2 - $x0) * ($y2 - $y1)) / (0.0001 + $x2 - $x1));
                                 if ($ecran->inclure($x0, $y0)) {
                                    $secteur_debut = "F";
                                    $pe++; $t_pe[$pe] = $x0;
                                    $pe++; $t_pe[$pe] = $y0;
                                    $nb_pe++;
                                    }
                                 }
                              break;

                           case "G": //*** clipping bas gauche
                              if (($t_o[$no_obj][$no_face][$point_precedent]["point"]->xp >= $xmin) &&
                                  ($t_o[$no_obj][$no_face][$point_precedent]["point"]->yp <= $ymax)) {
                                 $x2 = $t_o[$no_obj][$no_face][$point_precedent]["point"]->xp;
                                 $y2 = $t_o[$no_obj][$no_face][$point_precedent]["point"]->yp;
                                 $y0 = $ymax;
                                 $x0 = $x2 - ((($x2 - $x1) * ($y2 - $y0)) / (0.0001 + $y2 - $y1));
                                 if ($ecran->inclure($x0, $y0)) {
                                    $secteur_debut = "H";
                                    $pe++; $t_pe[$pe] = $x0;
                                    $pe++; $t_pe[$pe] = $y0;
                                    $nb_pe++;
                                    }
                                 }

                              if (($t_o[$no_obj][$no_face][$point_suivant]["point"]->xp >= $xmin) &&
                                  ($t_o[$no_obj][$no_face][$point_suivant]["point"]->yp <= $ymax)) {
                                 $x2 = $t_o[$no_obj][$no_face][$point_suivant]["point"]->xp;
                                 $y2 = $t_o[$no_obj][$no_face][$point_suivant]["point"]->yp;
                                 $x0 = $xmin;
                                 $y0 = $y2 - ((($x2 - $x0) * ($y2 - $y1)) / (0.0001 + $x2 - $x1));
                                 if ($ecran->inclure($x0, $y0)) {
                                    $secteur_debut = "D";
                                    $pe++; $t_pe[$pe] = $x0;
                                    $pe++; $t_pe[$pe] = $y0;
                                    $nb_pe++;
                                    }
                                 }
                              break;

                           case "H": //*** clipping bas
                              if ($t_o[$no_obj][$no_face][$point_precedent]["point"]->yp <= $ymax) {
                                 $x2 = $t_o[$no_obj][$no_face][$point_precedent]["point"]->xp;
                                 $y2 = $t_o[$no_obj][$no_face][$point_precedent]["point"]->yp;
                                 $y0 = $ymax;
                                 $x0 = $x2 - ((($x2 - $x1) * ($y2 - $y0)) / (0.0001 + $y2 - $y1));
                                 if ($ecran->inclure($x0, $y0)) {
                                    $secteur_debut = "H";
                                    $pe++; $t_pe[$pe] = $x0;
                                    $pe++; $t_pe[$pe] = $y0;
                                    $nb_pe++;
                                    }
                                 }

                              if ($t_o[$no_obj][$no_face][$point_suivant]["point"]->yp <= $ymax) {
                                 $x2 = $t_o[$no_obj][$no_face][$point_suivant]["point"]->xp;
                                 $y2 = $t_o[$no_obj][$no_face][$point_suivant]["point"]->yp;
                                 $y0 = $ymax;
                                 $x0 = $x2 - ((($x2 - $x1) * ($y2 - $y0)) / (0.0001 + $y2 - $y1));
                                 if ($ecran->inclure($x0, $y0)) {
                                    $secteur_debut = "H";
                                    $pe++; $t_pe[$pe] = $x0;
                                    $pe++; $t_pe[$pe] = $y0;
                                    $nb_pe++;
                                    }
                                 }
                              break;

                           case "I": //*** clipping bas droite
                              if (($t_o[$no_obj][$no_face][$point_precedent]["point"]->xp <= $xmax) &&
                                  ($t_o[$no_obj][$no_face][$point_precedent]["point"]->yp <= $ymax)) {
                                 $x2 = $t_o[$no_obj][$no_face][$point_precedent]["point"]->xp;
                                 $y2 = $t_o[$no_obj][$no_face][$point_precedent]["point"]->yp;
                                 $x0 = $xmax;
                                 $y0 = $y2 - ((($x2 - $x0) * ($y2 - $y1)) / (0.0001 + $x2 - $x1));
                                 if ($ecran->inclure($x0, $y0)) {
                                    $secteur_debut = "F";
                                    $pe++; $t_pe[$pe] = $x0;
                                    $pe++; $t_pe[$pe] = $y0;
                                    $nb_pe++;
                                    }
                                 }

                              if (($t_o[$no_obj][$no_face][$point_suivant]["point"]->xp <= $xmax) &&
                                  ($t_o[$no_obj][$no_face][$point_suivant]["point"]->yp <= $ymax)) {
                                 $x2 = $t_o[$no_obj][$no_face][$point_suivant]["point"]->xp;
                                 $y2 = $t_o[$no_obj][$no_face][$point_suivant]["point"]->yp;
                                 $y0 = $ymax;
                                 $x0 = $x2 - ((($x2 - $x1) * ($y2 - $y0)) / (0.0001 + $y2 - $y1));
                                 if ($ecran->inclure($x0, $y0)) {
                                    $secteur_debut = "H";
                                    $pe++; $t_pe[$pe] = $x0;
                                    $pe++; $t_pe[$pe] = $y0;
                                    $nb_pe++;
                                    }
                                 }
                              break;

                           } // end switch.
                        $i++;
                        }
                     }// fin clipping.
                  }
               else
                  {// face composée d'un seul point.
                  $t_pe[0] = $t_o[$no_obj][$no_face][1]["point"]->xp;
                  $t_pe[1] = $t_o[$no_obj][$no_face][1]["point"]->yp;
                  $nb_pe = 1;
                  }  
               
               //*** mettre à jour les xmin et ymin de l'objet pour affichage informations.
               for ($i = 0; isset($t_pe[$i]); $i += 2) {
                  if ($t_pe[$i]   < $x_min) {$x_min = $t_pe[$i];}
                  if ($t_pe[$i+1] > $y_max) {$y_max = $t_pe[$i+1];}
                  }
   
               //*************************************************
               // Les lumières modifient la couleur de la face.
               $no_couleur++;
               $no_lumiere = 1;
               while (isset($t_l[$no_lumiere])) {
                  $t_o[$no_obj][$no_face]["face"]->calculer_eclairement($ecran, $t_o[$t_l[$no_lumiere]][1][1]["point"], $t_o[$no_obj][$no_face][1]["point"], $ecran->eclairage);
                  $no_lumiere++;
                  }
               
               $t_couleur[$no_couleur] = imagecolorexact($image1, $t_o[$no_obj][$no_face]["face"]->ro, $t_o[$no_obj][$no_face]["face"]->vo, $t_o[$no_obj][$no_face]["face"]->bo);
               if ($t_couleur[$no_couleur] == -1)
                  $t_couleur[$no_couleur] = imagecolorallocate($image1, $t_o[$no_obj][$no_face]["face"]->ro, $t_o[$no_obj][$no_face]["face"]->vo, $t_o[$no_obj][$no_face]["face"]->bo);
               if ($t_couleur[$no_couleur] == -1)
                  $t_couleur[$no_couleur] = imagecolorclosest($image1, $t_o[$no_obj][$no_face]["face"]->ro, $t_o[$no_obj][$no_face]["face"]->vo, $t_o[$no_obj][$no_face]["face"]->bo);
               
               //*************************************************
               // dessiner la face.
               if ($nb_pe == 1) // la face est un simple point.
                  imagesetpixel($image1, $t_pe[0], $t_pe[1], $t_couleur[$no_couleur]);
               elseif ($nb_pe == 2) // la face est une simple ligne.
                  imageline($image1, $t_pe[0], $t_pe[1], $t_pe[2], $t_pe[3], $t_couleur[$no_couleur]);
               elseif ($nb_pe > 2) { // la face est un polygone.
                  if (($ecran->filaire) ||
                      ($t_o[$no_obj][$no_face]["face"]->filaire) ||
                      ($t_o[$no_obj]["objet"]->filaire))
                     {// dessiner toutes les lignes.
                     for ($i = 3; isset($t_pe[$i]); $i+=2)
                        imageline($image1, $t_pe[$i-3], $t_pe[$i-2], $t_pe[$i-1], $t_pe[$i], $t_couleur[$no_couleur]);
                     }  
                  else
                     {// dessiner le polygone rempli.
                     imagefilledpolygon($image1, $t_pe, $nb_pe, $t_couleur[$no_couleur]);
                     if (($t_o[$no_obj][$no_face]["face"]->surlignage) ||
                         ($ecran->surlignage))
                        {// on souligne les faces par des lignes noires.
                        $i = 3;
                        while (isset($t_pe[$i])) {
                           imageline($image1, $t_pe[$i - 3], $t_pe[$i - 2], $t_pe[$i - 1], $t_pe[$i], $noir);
                           $i += 2;
                           }
                        }
                     }
                  }
               } // fin face (au moins en partie) comprise dans l'écran.
            } // fin face visible.
         $no_face++;
         } // fin face.
      
      if (($x_min > 0) && ($y_max > 0) && ($x_min < $ecran->largeur - 15) && ($y_max < $ecran->hauteur -15))
         //****** affiche nom objet
         imagestring($image1, $font_size, $x_min, $y_max, $t_o[$no_obj]["objet"]->nom, $vert);
      $no_obj++;
      } // fin objet.
   }

switch($ecran->format_sortie)
   {
   case "png":
      if ($fout)
         imagepng($image1, $parametres->dir_productions .$fout);
      imagepng($image1);
      break;
   case "jpg":
      if ($fout)
         imagejpeg($image1, $parametres->dir_productions .$fout, 100);
      imagejpeg($image1);
      break;
   }
?>
