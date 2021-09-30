<?php
//***************************************
// PROGRAMME..... moteur de visualisation 3D
// MODULE........ modules / contenu d'une image SQL (MySQL).
// AUTEUR........ N.Dupont-Bloch
// DATE.......... 12/10/2003
// VERSION....... 1.6
// PARAMETRES.... act: action à effectuer
//                id_rec: id du record à traiter (selon valeur de act).
//***************************************
require('../3d_config.inc.php');
require('../3d_database.inc.php');
require('../3d_fic.php');
$parametres = new Parametres();
session_start();
?>

<html>
<head>
<meta http-equiv="Content-Type"
content="text/html; charset=iso-8859-1">
<title>3D-StoryBoard (1.6)</title>
</head>

<body>
<?php
if (!(isset($act)) ||
    (!$act))
   {
   $act = '';
   }

if ((isset($HTTP_POST_VARS['depart_image'])) &&
    ($HTTP_POST_VARS['depart_image']))
   {
   $depart_image = $HTTP_POST_VARS['depart_image'];
   }
//******
session_register('depart_image');

if ((isset($HTTP_POST_VARS['arrivee_image'])) &&
    ($HTTP_POST_VARS['arrivee_image']))
   {
   $arrivee_image = $HTTP_POST_VARS['arrivee_image'];
   }
session_register('arrivee_image');

//******
if ((isset($HTTP_POST_VARS['scene'])) &&
    ($HTTP_POST_VARS['scene']))
   {
   $scene = $HTTP_POST_VARS['scene'];
   }
session_register('scene');

//******
if ((isset($HTTP_POST_VARS['action_image'])) &&
    ($HTTP_POST_VARS['action_image']))
   {
   $action_image = $HTTP_POST_VARS['action_image'];
   }
session_register('action_image');

//******
if ((isset($HTTP_POST_VARS['intervalle_image'])) &&
    ($HTTP_POST_VARS['intervalle_image']))
   {
   $intervalle_image = $HTTP_POST_VARS['intervalle_image'];
   }
else
   {
   $intervalle_image = 5;
   }
session_register('intervalle_image');

//******
if ((isset($HTTP_POST_VARS['zoom_image'])) &&
    ($HTTP_POST_VARS['zoom_image']))
   {
   $zoom_image = $HTTP_POST_VARS['zoom_image'];
   }
else
   {
   // cadre au centre de 70% de l'image originelle.
   $zoom_image = 0.7;
   }
session_register('zoom_image');


//****** formulaire de recherche.
echo '<form method="POST" action="' .$PHP_SELF .'?act=read">';
echo '   <table border="0" cellspacing="0" bgcolor="' .$parametres->coul_fond3 .'">';
echo '   <tr><td colspan="2"><table border="1" cellpadding="0" cellspacing="0"><tr><td colspan="2">';
echo '   <div align="left">';
echo '      <table border="0" cellspacing="0">';
echo '      <tr><td width="140"><font face="Arial">scène               </font></td><td><font face="Arial"><input type="text"  size="3" name="scene" value="' .$scene .'"></font></td></tr>';
echo '      <tr><td width="140"><font face="Arial">afficher 1 image sur</font></td><td><font face="Arial"><input type="text"  size="3" name="intervalle_image" value="' .$intervalle_image .'"></font></td></tr>';
$checked = ($action_image == "jamais") ? 'checked' : '';
echo '      <tr><td width="140"><font face="Arial">afficher les actions</font></td><td><font face="Arial"><input type="radio" name="action_image" value="jamais"   ' .$checked .'>jamais</font></td></tr>';
$checked = ($action_image == "toujours") ? 'checked' : '';
echo '      <tr><td width="140">&nbsp                                         </td><td><font face="Arial"><input type="radio" name="action_image" value="toujours" ' .$checked .'>toujours</font></td></tr>';
echo '      <tr><td width="140"><font face="Arial">zoom image</font></td>';
echo '         <td><font face="Arial"><SELECT name="zoom_image">';
$selected = ($zoom_image == 1)? 'selected' : '';
echo '         <option ' .$selected .' value="1">100%</option>';
$selected = ($zoom_image == 0.7)? 'selected' : '';
echo '         <option ' .$selected .' value="0.7">70%</option>';
$selected = ($zoom_image == 0.5)? 'selected' : '';
echo '         <option ' .$selected .' value="0.5">50%</option>';
$selected = ($zoom_image == 0.3)? 'selected' : '';
echo '         <option ' .$selected .' value="0.3">30%</option>';
$selected = ($zoom_image == 0.1)? 'selected' : '';
echo '         <option ' .$selected .' value="0.1">10%</option>';
echo '         </SELECT></td></tr>';
echo '      </table>';
echo '   </div>';
echo '   </td></tr><tr><td>';
echo '   <div align="left">';
echo '       <table border="0" cellspacing="0">';
echo '       <tr>';
echo '       <td width="80" colspan="2">';
echo '       <p align="center"><font face="Arial">départ</font></p>';
echo '       </td>';
echo '       </tr>';
echo '       <tr>';
echo '       <td width="80"><font face="Arial">image</font></td>';
echo '       <td><font face="Arial"><input type="text" size="3" name="depart_image" value="' .$depart_image .'"></font></td>';
echo '       </tr>';
echo '       </table>';
echo '    </div>';
echo '    </td><td>';
echo '    <div align="left">';
echo '       <table border="0" cellspacing="0">';
echo '       <tr>';
echo '       <td width="80" colspan="2">';
echo '       <p align="center"><font face="Arial">arrivée</font></p>';
echo '       </td>';
echo '       </tr>';
echo '       <tr>';
echo '       <td width="80"><font face="Arial">image</font></td>';
echo '       <td><font face="Arial"><input type="text" size="3" name="arrivee_image" value="' .$arrivee_image .'"></font></td>';
echo '       </tr>';
echo '    </table>';
echo '    </div>';
echo '    </td></tr>';
echo '       </table></td></tr><tr><td width="80">&nbsp;</td><td align="left">';
echo '       <font face="Arial"><input type="submit" name="bt_read" value="Lire"></font></td>';
echo '       </tr>';
echo '    </table>';
echo '    </form>';
//******

switch ($act)
   {
   case 'read':
      //******************************************
      @$dbhandle  = mysql_connect($parametres->serveur_database, $parametres->user_database, $parametres->password_database)
      or die ('</br>La connexion au serveur a échoué: ' .mysql_error() .'</br>');
      @mysql_select_db($parametres->database ,$dbhandle)
      or die ('</br>La sélection de la base a échoué: ' .mysql_error() .'</br>');

      //*** lit la constitution des images.
      $req  = 'SELECT tab_act.id_rec, tab_act.id_scn, tab_act.id_img, tab_act.id_act, tab_act.act';
      $req .= ' FROM tab_act';
      $req .= ' WHERE tab_act.id_scn = '  .$scene;
      $req .= ' AND   tab_act.id_img >= ' .$depart_image;
      $req .= ' AND   tab_act.id_img <= ' .$arrivee_image;
      $req .= ' ORDER by tab_act.id_scn, tab_act.id_img, tab_act.id_act';
      //echo $req;
      $resultat = mysql_query($req)
      or die ('</br>erreur query (read): ' .mysql_error());
      unset($id_img_prec);
      unset($id_obj_prec);
      $modulo_image = 0; // pour afficher une miniature toutes les n images.
      echo '<TABLE border="0" cellpadding="0" cellspacing="1">';
      while ($row = mysql_fetch_array($resultat))
         {
         //****** pour calculer la miniature, récupérer son format d'écran originel.
         $req_imgxy  = 'SELECT tab_act.id_scn, tab_act.id_img, tab_act.act';
         $req_imgxy .= ' FROM tab_act';
         $req_imgxy .= ' WHERE tab_act.id_scn=' .$row['id_scn'];
         $req_imgxy .= ' AND tab_act.id_img=' .$row['id_img'];
         $req_imgxy .= ' AND   tab_act.act like "%ecran%dimension%"';
         $resultat_cmd = mysql_query($req_imgxy) or die ('calcul miniature:' .mysql_error());
         while ($row_cmd = mysql_fetch_array($resultat_cmd))
            {
            $commande = explode(';', $row_cmd['act']); // utiliser c_fichier_separateur_colonne
            $imgx = $commande[3];
            $imgy = $commande[4];
            }
         
         //****** si changement d'image, affiche n° d'image et miniature.
         if ($id_img_prec != $row['id_img'])
            {
            $id_img_prec = $row['id_img'];
            echo '<tr><td> </td></tr>';
            echo '<tr bgcolor="' .$parametres->coul_fond3 .'"><td><font face="Arial"><strong>' .$row['id_img'] .'</strong></font>';
            $modulo_image--;
            if ($modulo_image <= 0)
               {//****** lien pour afficherl'image SQL en taille originelle.
               $url  = 'http://' .$parametres->serveur_moteur_3d .'/3d_v5/3d_aff.php?dx=0&dy=0&dz=0&da=0&db=0&dg=0';
               $url .= '&fil=true&imge=scn&imgs=true&imgf=png';
               $url .= '&scn=' .$row['id_scn'] .'&dbsrv=' .$parametres->serveur_database .'&p1=' .$row['id_img'];
               echo '<a href="' .$url .'" target="_blank">';
               //****** demande au moteur d'afficher une miniature de l'image SQL.
               $url  = 'http://' .$parametres->serveur_moteur_3d .'/3d_v5/3d_aff.php?dx=0&dy=0&dz=0&da=0&db=0&dg=0';
               $url .= '&fil=true&imgz=200&imge=scn&imgs=true&imgf=png';
               if ($imgx && $imgy)
                  {$url .= '&imgx=' .($zoom_image * $imgx) .'&imgy=' .($zoom_image * $imgy); 
                  }
               else
                  {
                  $imgx = 640; // format par défaut s'il n'est pas précisé dans l'image Sql.
                  $imgy = 480;
                  }
               $url .= '&scn=' .$row['id_scn'] .'&dbsrv=' .$parametres->serveur_database .'&fin=&fout=&p1=' .$row['id_img'];
               echo '<img width="100" height="' .$imgy * 100 / $imgx .'" src="' .$url .'">';
               echo '</a>';
               $modulo_image = $intervalle_image;
               }
            echo '</td>';
            //****** titre des colonnes.
            echo '<td><font face="Arial">scène</font></td><td><font face="Arial">action</font></td><td><font face="Arial">objet</font></td><td><font face="Arial">paramètres</font></td></tr>';
            }
         if ($action_image == "toujours")
            {//****** affiche le détail des actions de chaque image.
            // si changement d'objet: changement de couleur ligne.
            $c = explode(';', $row['act']);
            $id_obj = $c[2];
            $couleur_fond = ($id_obj_prec != $id_obj) ? $parametres->coul_fond3 : $parametres->coul_fond4; //'#EFEFEF' : '#FCFCFC'; 
            $id_obj_prec = $id_obj;

            echo '<tr bgcolor="' .$couleur_fond .'">';
            echo '   <td>';
            //****** supprimer.
            echo '   <a href="' .$PHP_SELF .'?act=del&id_rec=' .$row['id_rec'] .'">supp</a>';
            //****** modifier.
            //echo '   <a href="' .$PHP_SELF .'?act=upd&id_rec=' .$row['id_rec'] .'">modif</a>';
            echo '   </td>';
            echo '   <td align="center">' .$row['id_scn'] .'</td>';
            echo '   <td align="center">' .$row['id_act'] .'</td>';
            echo '   <td><font face="Arial">' .$id_obj .'</font></td>';
            echo '   <td><font face="Arial">' .$row['act'] .'</font></td>';
            echo '</tr>';
            }
         }
      echo '</TABLE>';
      mysql_close();
      break;
      
   case 'upd':
      //******************************************
      echo 'modification non implémentée.</br>';
      break;
      
   case 'del':
      //******************************************
      @$dbhandle  = mysql_connect($parametres->serveur_database, $parametres->user_database, $parametres->password_database)
      or die ('</br>La connexion au serveur a échoué: ' .mysql_error() .'</br>');
      @mysql_select_db($parametres->database ,$dbhandle)
      or die ('</br>La sélection de la base a échoué: ' .mysql_error() .'</br>');
      $req = 'DELETE from tab_act WHERE tab_act.id_rec=' .$id_rec;
      //echo $req;
      $resultat = mysql_query($req) or die(mysql_error());
      mysql_close();
      break;
   echo '<font face="Arial"><a href="' .$PHP_SELF .'">retour</a></font>';
   }
?>
</body>
</html>
