<?php
//***************************************
// PROGRAMME..... moteur de visualisation 3D
// MODULE........ modules / création source de lumière.
// AUTEUR........ N.Dupont-Bloch
// DATE.......... 9/10/2003
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
<title>3D-Lumière création de source lumineuse (1.1)</title>
</head>

<body bgcolor="#FFFFFF">
<?php
if (!isset($act) || !$act) {
   echo '<form method="POST" action="' .$PHP_SELF .'?act=rec">';
   echo '         <table border="0" cellspacing="0" bgcolor="' .$params->coul_fond3 .'">';
   echo '         <tr><td colspan="2"><table border="1" cellpadding="0" cellspacing="0"><tr><td colspan="2">';
   echo '              <div align="left">';
   echo '                <table border="0" cellspacing="0">';
   echo '                  <tr>';
   echo '                    <td width="80"><font face="Arial">lumière</font></td>';
   echo '                    <td><font face="Arial"><input type="text" size="20" name="lumiere"></font></td>';
   echo '                  </tr>';
   echo '                </table>';
   echo '              </div>';
   echo '            </td></tr><tr><td colspan="2">';
   echo '              <div align="left">';
   echo '                <table border="0" cellspacing="0">';
   echo '                  <tr>';
   echo '                    <td width="80"><font face="Arial">scène</font></td>';
   echo '                    <td><font face="Arial"><input type="text" size="3" name="scene" value="1"></font></td>';
   echo '                  </tr>';
   echo '                  <tr>';
   echo '                    <td width="80"><font face="Arial">action</font></td>';
   echo '                    <td><font face="Arial"><input type="text" size="3" name="action" value="1"></font></td>';
   echo '                  </tr>';
   echo '                </table>';
   echo '              </div>';
   echo '            </td></tr><tr><td>';
   echo '              <div align="left">';
   echo '                <table border="0" cellspacing="0">';
   echo '                  <tr>';
   echo '                    <td width="80" colspan="2">';
   echo '                      <p align="center"><font face="Arial">départ</font></p>';
   echo '                    </td>';
   echo '                  </tr>';
   echo '                  <tr>';
   echo '                    <td width="80"><font face="Arial">image</font></td>';
   echo '                    <td><font face="Arial"><input type="text" size="3" name="depart_image" value="1"></font></td>';
   echo '                  </tr>';
   echo '                </table>';
   echo '              </div>';
   echo '            </td><td>';
   echo '              <div align="left">';
   echo '                <table border="0" cellspacing="0">';
   echo '                  <tr>';
   echo '                    <td width="80" colspan="2">';
   echo '                      <p align="center"><font face="Arial">arrivée</font></p>';
   echo '                    </td>';
   echo '                  </tr>';
   echo '                  <tr>';
   echo '                    <td width="80"><font face="Arial">image</font></td>';
   echo '                    <td><font face="Arial"><input type="text" size="3" name="arrivee_image" value="1"></font></td>';
   echo '                  </tr>';
   echo '                </table>';
   echo '              </div>';
   echo '            </td></tr>';
   echo '          </table></td></tr><tr><td align="center" width="80">&nbsp;</td><td align="center">';
   echo '          <p align="left"><font face="Arial"><input type="submit" name="bt_enregistrer" value="Enregistrer"></font></td>';
   echo '          </tr></table></form>';
   }
else {
   switch ($act) {
      case 'rec':
         //******************************************
         // enregistre la séquence.
         $id_scn          = $HTTP_POST_VARS['scene'];
         $id_act          = $HTTP_POST_VARS['action'];

         $depart_image    = $HTTP_POST_VARS['depart_image'];

         $arrivee_image   = $HTTP_POST_VARS['arrivee_image'];

         $id_obj          = $HTTP_POST_VARS['lumiere'];
        
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
            $act  = 'lumiere;creer;' .$id_obj .';';
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
