<?php
//***************************************
// PROGRAMME..... moteur de visualisation 3D
// MODULE........ animation / rvb écran
// AUTEUR........ N.Dupont-Bloch
// DATE.......... 9/10/2003
// VERSION....... 1.2
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
<title>3D-Ecran rvb (1.0)</title>
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
   echo '                         <td width="80"><font face="Arial">R</font></td>';
   echo '                         <td><font face="Arial"><input type="text" size="3" name="depart_r" value="50"></font></td>';
   echo '                       </tr>';
   echo '                       <tr>';
   echo '                         <td width="80"><font face="Arial">V</font></td>';
   echo '                         <td><font face="Arial"><input type="text" size="3" name="depart_v" value="50"></font></td>';
   echo '                       </tr>';
   echo '                       <tr>';
   echo '                         <td width="80"><font face="Arial">B</font></td>';
   echo '                         <td><font face="Arial"><input type="text" size="3" name="depart_b" value="50"></font></td>';
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
   echo '                         <td width="80"><font face="Arial">R</font></td>';
   echo '                         <td><font face="Arial"><input type="text" size="3" name="arrivee_r" value="50"></font></td>';
   echo '                       </tr>';
   echo '                       <tr>';
   echo '                         <td width="80"><font face="Arial">V</font></td>';
   echo '                         <td><font face="Arial"><input type="text" size="3" name="arrivee_v" value="50"></font></td>';
   echo '                       </tr>';
   echo '                       <tr>';
   echo '                         <td width="80"><font face="Arial">B</font></td>';
   echo '                         <td><font face="Arial"><input type="text" size="3" name="arrivee_b" value="50"></font></td>';
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
         $depart_r        = $HTTP_POST_VARS['depart_r'];
         $depart_v        = $HTTP_POST_VARS['depart_v'];
         $depart_b        = $HTTP_POST_VARS['depart_b'];

         $arrivee_image   = $HTTP_POST_VARS['arrivee_image'];
         $arrivee_r       = $HTTP_POST_VARS['arrivee_r'];
         $arrivee_v       = $HTTP_POST_VARS['arrivee_v'];
         $arrivee_b       = $HTTP_POST_VARS['arrivee_b'];

         $id_obj          = 'ecran';
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
                  $r = round(calculer_variation_lineaire($depart_r, $arrivee_r, $id_img, $nb_img));
                  $v = round(calculer_variation_lineaire($depart_v, $arrivee_v, $id_img, $nb_img));
                  $b = round(calculer_variation_lineaire($depart_b, $arrivee_b, $id_img, $nb_img));
                  break;
               }
            $act  = 'ecran;rvb;ecran;' .$r .';' .$v .';' .$b .';';
            $req  = "INSERT into tab_act (id_scn, id_img, id_obj, id_act, act)";
            $req .= " VALUES ($id_scn, $id_img, \"$id_obj\", $id_act, \"$act\")";
            //echo $req .'</br>';
            $resultat = mysql_query($req);
            }
         mysql_close();
         echo '</br>Ok.';
         break;
      }
   }
?>
</body>
</html>
