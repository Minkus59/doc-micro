<?php
//***************************************
// PROGRAMME..... moteur de visualisation 3D
// MODULE........ animation / translation d'un objet.
// AUTEUR........ N.Dupont-Bloch
// DATE.......... 11/10/2003
// VERSION....... 1.1
// PARAMETRES....
//***************************************
require('../3d_config.inc.php');
require('../3d_database.inc.php');
require('../3d_algos.inc.php');
$params = new Parametres();
?>
<html>

<head>
<meta http-equiv="Content-Type"
content="text/html; charset=iso-8859-1">
<title>3D-Objet translation (1.0)</title>
</head>

<body bgcolor="#FFFFFF">

<?php
if (!isset($act) || !$act) {
   echo '<form method="POST" action="' .$PHP_SELF .'?act=rec">';
   echo ' <table border="0" bgcolor="' .$params->coul_fond3 .'" cellspacing="0">';
   echo '     <tr>';
   echo '         <td colspan="2"><table border="1" cellspacing="0" width="100%">';
   echo '             <tr>';
   echo '                 <td colspan="2">';
   echo '                   <div align="left">';
   echo '                     <table border="0" cellspacing="0">';
   echo '                       <tr>';
   echo '                         <td width="80"><font face="Arial">scène</font></td>';
   echo '                         <td><font face="Arial"><input type="text" name="scene" size="3" value="1"></font></td>';
   echo '                       </tr>';
   echo '                       <tr>';
   echo '                         <td width="80"><font face="Arial">objet</font></td>';
   echo '                         <td><font face="Arial"><input type="text" name="objet" size="10" value=""></font></td>';
   echo '                       </tr>';
   echo '                       <tr>';
   echo '                         <td width="80"><font face="Arial">action</font></td>';
   echo '                         <td><font face="Arial"><input type="text" name="action" size="3" value="1"></font></td>';
   echo '                       </tr>';
   echo '                     </table>';
   echo '                   </div>';
   echo '                 </td>';
   echo '             </tr>';
   echo '             <tr>';
   echo '                 <td>';
   echo '                   <div align="left">';
   echo '                     <table border="0" cellspacing="0">';
   echo '                       <tr>';
   echo '                         <td colspan="2">';
   echo '                           <p align="center"><font face="Arial">départ</font></p>';
   echo '                         </td>';
   echo '                       </tr>';
   echo '                       <tr>';
   echo '                         <td width="80"><font face="Arial">image</font></td>';
   echo '                         <td><font face="Arial"><input type="text" size="3" name="depart_image" value="1"></font></td>';
   echo '                       </tr>';
   echo '                       <tr>';
   echo '                         <td width="80"><font face="Arial">X</font></td>';
   echo '                         <td><font face="Arial"><input type="text" size="3" name="depart_x" value="0"></font></td>';
   echo '                       </tr>';
   echo '                       <tr>';
   echo '                         <td width="80"><font face="Arial">Y</font></td>';
   echo '                         <td><font face="Arial"><input type="text" size="3" name="depart_y" value="0"></font></td>';
   echo '                       </tr>';
   echo '                       <tr>';
   echo '                         <td width="80"><font face="Arial">Z</font></td>';
   echo '                         <td><font face="Arial"><input type="text" size="3" name="depart_z" value="0"></font></td>';
   echo '                       </tr>';
   echo '                     </table>';
   echo '                   </div>';
   echo '                 </td>';
   echo '                 <td>';
   echo '                   <div align="left">';
   echo '                     <table border="0" cellspacing="0">';
   echo '                       <tr>';
   echo '                         <td colspan="2" align="center"><font face="Arial">arrivée</font></td>';
   echo '                       </tr>';
   echo '                       <tr>';
   echo '                         <td width="80"><font face="Arial">image</font></td>';
   echo '                         <td><font face="Arial"><input type="text" size="3" name="arrivee_image" value="1"></font></td>';
   echo '                       </tr>';
   echo '                       <tr>';
   echo '                         <td width="80"><font face="Arial">X</font></td>';
   echo '                         <td><font face="Arial"><input type="text" size="3" name="arrivee_x" value="0"></font></td>';
   echo '                       </tr>';
   echo '                       <tr>';
   echo '                         <td width="80"><font face="Arial">Y</font></td>';
   echo '                         <td><font face="Arial"><input type="text" size="3" name="arrivee_y" value="0"></font></td>';
   echo '                       </tr>';
   echo '                       <tr>';
   echo '                         <td width="80"><font face="Arial">Z</font></td>';
   echo '                         <td><font face="Arial"><input type="text" size="3" name="arrivee_z" value="0"></font></td>';
   echo '                       </tr>';
   echo '                     </table>';
   echo '                   </div>';
   echo '                 </td>';
   echo '             </tr>';
   echo '             <tr>';
   echo '                 <td colspan="2">';
   echo '                   <div align="left">';
   echo '                     <table border="0" cellspacing="0">';
   echo '                       <tr>';
   echo '                         <td width="80"><font face="Arial">variation</font></td>';
   echo '                         <td><font face="Arial"><select size="1" name="variation">';
   echo '                             <option selected value="lineaire">linéaire</option>';
   echo '                           </select></font></td>';
   echo '                       </tr>';
   echo '                     </table>';
   echo '                   </div>';
   echo '                 </td>';
   echo '             </tr>';
   echo '         </table>';
   echo '         </td>';
   echo '     </tr>';
   echo '     <tr>';
   echo '         <td width="80"><p align="center">&nbsp;</p>';
   echo '         </td>';
   echo '         <td><font face="Arial"><input type="submit" name="bt_enregistrer" value="Enregistrer"></font>';
   echo '         </td>';
   echo '     </tr>';
   echo ' </table>';
   echo '</form>';
   }
else {
   switch ($act) {
      case 'rec':
         //******************************************
         // enregistre la séquence.
         $id_scn          = $HTTP_POST_VARS['scene'];
         $id_act          = $HTTP_POST_VARS['action'];

         $depart_image    = $HTTP_POST_VARS['depart_image'];
         $depart_x        = $HTTP_POST_VARS['depart_x'];
         $depart_y        = $HTTP_POST_VARS['depart_y'];
         $depart_z        = $HTTP_POST_VARS['depart_z'];

         $arrivee_image   = $HTTP_POST_VARS['arrivee_image'];
         $arrivee_x       = $HTTP_POST_VARS['arrivee_x'];
         $arrivee_y       = $HTTP_POST_VARS['arrivee_y'];
         $arrivee_z       = $HTTP_POST_VARS['arrivee_z'];

         $id_obj          = $HTTP_POST_VARS['objet'];
         $variation       = $HTTP_POST_VARS['variation'];
        
         $nb_img = 1 + $arrivee_image - $depart_image;
         
         //****** Connexion DB
         $parametres = new Parametres();
         @$dbhandle  = mysql_connect($parametres->serveur_database, $parametres->user_database, $parametres->password_database)
         or die ('</br>La connexion au serveur a échoué: ' .mysql_error() .'</br>');
         @mysql_select_db($parametres->database ,$dbhandle)
         or die ('</br>La sélection de la base a échoué: ' .mysql_error() .'</br>');
         
         for ($id_img = 1; $id_img <= $nb_img; $id_img++)
            {
            $script = new Mysql();
            switch ($variation)
               {
               case 'lineaire':
                  $x = round(calculer_variation_lineaire($depart_x, $arrivee_x, $id_img, $nb_img));
                  $y = round(calculer_variation_lineaire($depart_y, $arrivee_y, $id_img, $nb_img));
                  $z = round(calculer_variation_lineaire($depart_z, $arrivee_z, $id_img, $nb_img));
                  break;
               }
            $act  = 'objet;translation;' .$id_obj .';' .$x .';' .$y .';' .$z .';';
            $req  = 'INSERT into tab_act (id_scn, id_img, id_obj, id_act, act)';
            $req .= ' VALUES (' .$id_scn .',' .($id_img + $depart_image - 1) .',"' .$id_obj .'",' .$id_act .',"' .$act .'")';
            //echo $req .'</br>';
            $resultat = mysql_query($req) or die(mysql_error());
            }
         mysql_close();
         echo '</br>Ok.';
         break;
      }
   echo '<font face="Arial"><a href="' .$PHP_SELF .'">retour</a></font>';
   }
?>
</body>
</html>
