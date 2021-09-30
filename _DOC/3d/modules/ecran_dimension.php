<?php
//***************************************
// PROGRAMME..... moteur de visualisation 3D
// MODULE........ animation / dimensions �cran
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
<title>3D-Ecran dimension (1.1)</title>
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
   echo '                         <td width="80"><font face="Arial">sc�ne</font></td>';
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
   echo '                           <p align="center"><font face="Arial">d�part</font></p>';
   echo '                         </td>';
   echo '                       </tr>';
   echo '                       <tr>';
   echo '                         <td width="80"><font face="Arial">image</font></td>';
   echo '                         <td><font face="Arial"><input type="text" size="3" name="depart_image" value="1"></font></td>';
   echo '                       </tr>';
   echo '                       <tr>';
   echo '                         <td width="80"><font face="Arial">largeur</font></td>';
   echo '                         <td><font face="Arial"><input type="text" size="3" name="depart_largeur" value="640"></font></td>';
   echo '                       </tr>';
   echo '                       <tr>';
   echo '                         <td width="80"><font face="Arial">hauteur</font></td>';
   echo '                         <td><font face="Arial"><input type="text" size="3" name="depart_hauteur" value="480"></font></td>';
   echo '                       </tr>';
   echo '                     </table>';
   echo '                   </div>';
   echo '                 </td>';
   echo '                 <td>';
   echo '                   <div align="left">';
   echo '                     <table border="0" cellspacing="0">';
   echo '                       <tr>';
   echo '                         <td colspan="2" align="center"><font face="Arial">arriv�e</font></td>';
   echo '                       </tr>';
   echo '                       <tr>';
   echo '                         <td width="80"><font face="Arial">image</font></td>';
   echo '                         <td><font face="Arial"><input type="text" size="3" name="arrivee_image" value="1"></font></td>';
   echo '                       </tr>';
   echo '                       <tr>';
   echo '                         <td width="80"><font face="Arial">largeur</font></td>';
   echo '                         <td><font face="Arial"><input type="text" size="3" name="arrivee_largeur" value="640"></font></td>';
   echo '                       </tr>';
   echo '                       <tr>';
   echo '                         <td width="80"><font face="Arial">hauteur</font></td>';
   echo '                         <td><font face="Arial"><input type="text" size="3" name="arrivee_hauteur" value="480"></font></td>';
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
   echo '                             <option selected value="lineaire">lin�aire</option>';
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
         // enregistre la s�quence.
         $id_scn          = $HTTP_POST_VARS['scene'];
         $id_act          = $HTTP_POST_VARS['action'];

         $depart_image    = $HTTP_POST_VARS['depart_image'];
         $depart_largeur  = $HTTP_POST_VARS['depart_largeur'];
         $depart_hauteur  = $HTTP_POST_VARS['depart_hauteur'];

         $arrivee_image   = $HTTP_POST_VARS['arrivee_image'];
         $arrivee_largeur = $HTTP_POST_VARS['arrivee_largeur'];
         $arrivee_hauteur = $HTTP_POST_VARS['arrivee_hauteur'];

         $id_obj          = 'ecran';
         $variation       = $HTTP_POST_VARS['variation'];
        
         $nb_img = 1 + $arrivee_image - $depart_image;
         
         //****** Connexion DB
         $parametres = new Parametres();
         @$dbhandle  = mysql_connect($parametres->serveur_database, $parametres->user_database, $parametres->password_database)
         or die ('</br>La connexion au serveur a �chou�: ' .mysql_error() .'</br>');
         @mysql_select_db($parametres->database ,$dbhandle)
         or die ('</br>La s�lection de la base a �chou�: ' .mysql_error() .'</br>');
         
         for ($id_img = 1; $id_img <= $nb_img; $id_img++)
            {
            $script = new Mysql();
            switch ($variation)
               {
               case 'lineaire':
                  $largeur = calculer_variation_lineaire($depart_largeur, $arrivee_largeur, $id_img, $nb_img);
                  $hauteur = calculer_variation_lineaire($depart_hauteur, $arrivee_hauteur, $id_img, $nb_img);
                  break;
               }
            $act  = 'ecran;dimension;ecran;' .$largeur .';' .$hauteur .';';
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
