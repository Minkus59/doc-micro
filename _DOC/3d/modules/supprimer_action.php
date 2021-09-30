<?php
//***************************************
// PROGRAMME..... moteur de visualisation 3D
// MODULE........ modules / supprimer des n° d'actions dans une image SQL (MySQL).
// AUTEUR........ N.Dupont-Bloch
// DATE.......... 11/10/2003
// VERSION....... 1.1
// PARAMETRES.... id_imgd    image de départ.
//                id_imga    image d'arrivée.
//                id_scn     scène.
//                act     n° action à supprimer.
//***************************************
require('../3d_config.inc.php');
require('../3d_database.inc.php');
session_start();
?>


<html>
<head>
<meta http-equiv="Content-Type"
content="text/html; charset=iso-8859-1">
<title>3D-Supprimer une action / image SQL (1.0)</title>
</head>

<body>
<?php
$parametres = new Parametres();

if ((isset($HTTP_POST_VARS['id_imgd'])) &&
    ($HTTP_POST_VARS['id_imgd']))
   {
   $id_imgd = $HTTP_POST_VARS['id_imgd'];
   }
session_register('id_imgd');

if ((isset($HTTP_POST_VARS['id_imga'])) &&
    ($HTTP_POST_VARS['id_imga']))
   {
   $id_imga = $HTTP_POST_VARS['id_imga'];
   }
session_register('id_imga');

if ((isset($HTTP_POST_VARS['id_scn'])) &&
    ($HTTP_POST_VARS['id_scn']))
   {
   $id_scn = $HTTP_POST_VARS['id_scn'];
   }
session_register('id_scn');

if ((isset($HTTP_POST_VARS['id_act'])) &&
    ($HTTP_POST_VARS['id_act']))
   {
   $id_act = $HTTP_POST_VARS['id_act'];
   }
session_register('id_act');

if (!isset($act) || (!$act))
{
//****** formulaire de suppression.
echo '<form method="POST" action="' .$PHP_SELF .'?act=mod">';
echo '         <table border="0" cellspacing="0" bgcolor="' .$parametres->coul_fond3 .'">';
echo '         <tr><td colspan="2"><table border="1" cellpadding="0" cellspacing="0"><tr><td colspan="2">';
echo '              <div align="left">';
echo '                <table border="0" cellspacing="0">';
echo '                  <tr>';
echo '                    <td width="80"><font face="Arial">serveur SQL</font></td>';
echo '                    <td><font face="Arial"><input type="text" size="20" name="serveur_sql" value="' .$parametres->serveur_database .'"></font></td>';
echo '                  </tr>';
echo '                </table>';
echo '              </div>';
echo '            </td></tr><tr><td colspan="2">';
echo '              <div align="left">';
echo '                <table border="0" cellspacing="0">';
echo '                  <tr>';
echo '                    <td width="80"><font face="Arial">scène</font></td>';
echo '                    <td><font face="Arial"><input type="text" size="3" name="id_scn" value="' .$scene .'"></font></td>';
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
echo '                    <td><font face="Arial"><input type="text" size="3" name="id_imgd" value="' .$id_imgd .'"></font></td>';
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
echo '                    <td><font face="Arial"><input type="text" size="3" name="id_imga" value="' .$id_imga .'"></font></td>';
echo '                  </tr>';
echo '                </table>';
echo '              </div>';
echo '            </td></tr>';
echo '                  <tr>';
echo '                    <td colspan="2"><TABLE border="0" cellpadding="0" cellspacing="1"><tr><td><font face="Arial">supprimer les actions n°</td><td><input type="text" size="3" name="id_act"></td></tr></TABLE>';
echo '                  </tr>';
echo '          </table></td></tr>';
echo '          <tr><td width="80">&nbsp;</td>';
echo '             <td><font face="Arial"><input type="submit" name="bt_mod" value="Supprimer"></font></td>';
echo '          </tr>';
echo '          </table>';
echo '          </form>';
//******
}
if ($act == 'mod')
   {
      //******************************************
      @$dbhandle  = mysql_connect($parametres->serveur_database, $parametres->user_database, $parametres->password_database)
      or die ('</br>La connexion au serveur a échoué: ' .mysql_error() .'</br>');
      @mysql_select_db($parametres->database ,$dbhandle)
      or die ('</br>La sélection de la base a échoué: ' .mysql_error() .'</br>');
      $req = 'DELETE from tab_act';
      $req .= ' WHERE tab_act.id_act=' .$id_act;
      $req .= ' AND tab_act.id_img >=' .$id_imgd . ' AND tab_act.id_img <= ' .$id_imga;
      //echo 'SQL:' .$req;
      $resultat = mysql_query($req);
      mysql_close();
   }
?>
</body>
</html>
