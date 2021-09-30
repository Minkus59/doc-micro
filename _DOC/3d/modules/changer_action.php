<?php
//***************************************
// PROGRAMME..... moteur de visualisation 3D
// MODULE........ modules / change des n° d'actions dans une image SQL (MySQL).
// AUTEUR........ N.Dupont-Bloch
// DATE.......... 10/10/2003
// VERSION....... 1.1
// PARAMETRES.... imgd    image de départ.
//                imga    image d'arrivée.
//                scn     scène.
//                actd    n° action originel.
//                acta    n° action après modification.
//***************************************
require('../3d_config.inc.php');
require('../3d_database.inc.php');
session_start();
?>


<html>
<head>
<meta http-equiv="Content-Type"
content="text/html; charset=iso-8859-1">
<title>3D-Modifier no action / image SQL (1.0)</title>
</head>

<body>
<?php
$parametres = new Parametres();

if ((isset($HTTP_POST_VARS['imgd'])) &&
    ($HTTP_POST_VARS['imgd']))
   {
   $imgd = $HTTP_POST_VARS['imgd'];
   }
session_register('imgd');

if ((isset($HTTP_POST_VARS['imga'])) &&
    ($HTTP_POST_VARS['imga']))
   {
   $imga = $HTTP_POST_VARS['imga'];
   }
session_register('imga');

if ((isset($HTTP_POST_VARS['scn'])) &&
    ($HTTP_POST_VARS['scn']))
   {
   $scn = $HTTP_POST_VARS['scn'];
   }
session_register('scn');

if ((isset($HTTP_POST_VARS['actd'])) &&
    ($HTTP_POST_VARS['actd']))
   {
   $actd = $HTTP_POST_VARS['actd'];
   }
session_register('acta');

if ((isset($HTTP_POST_VARS['acta'])) &&
    ($HTTP_POST_VARS['acta']))
   {
   $acta = $HTTP_POST_VARS['acta'];
   }
session_register('acta');


//****** formulaire de modification.
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
echo '                    <td><font face="Arial"><input type="text" size="3" name="scn" value="' .$scene .'"></font></td>';
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
echo '                    <td><font face="Arial"><input type="text" size="3" name="imgd" value="' .$imgd .'"></font></td>';
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
echo '                    <td><font face="Arial"><input type="text" size="3" name="imga" value="' .$imga .'"></font></td>';
echo '                  </tr>';
echo '                </table>';
echo '              </div>';
echo '            </td></tr>';
echo '                  <tr>';
echo '                    <td colspan="2"><TABLE border="0" cellpadding="0" cellspacing="1"><tr><td><font face="Arial">les actions n°</td><td><input type="text" size="3" name="actd"></td><td>prennent le n°</td><td><input type="text" size="3" name="acta"></font></td></tr></TABLE>';
echo '                  </tr>';
echo '          </table></td></tr>';
echo '          <tr><td width="80">&nbsp;</td>';
echo '             <td><font face="Arial"><input type="submit" name="bt_mod" value="Modifier"></font></td>';
echo '          </tr>';
echo '          </table>';
echo '          </form>';
//******

if ($act == 'mod')
   {
      //******************************************
      @$dbhandle  = mysql_connect($parametres->serveur_database, $parametres->user_database, $parametres->password_database)
      or die ('</br>La connexion au serveur a échoué: ' .mysql_error() .'</br>');
      @mysql_select_db($parametres->database ,$dbhandle)
      or die ('</br>La sélection de la base a échoué: ' .mysql_error() .'</br>');
      $req = 'UPDATE tab_act SET tab_act.id_act = ' .$acta;
      $req .= ' WHERE tab_act.id_act=' .$actd;
      $req .= ' AND tab_act.id_img >=' .$imgd . ' AND tab_act.id_img <= ' .$imga;
      //echo $req;
      $resultat = mysql_query($req);
      mysql_close();
   }
?>
</body>
</html>
