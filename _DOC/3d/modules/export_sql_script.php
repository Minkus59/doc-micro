<?php
//***************************************
// PROGRAMME..... moteur de visualisation 3D
// MODULE........ modules / exportation scripts Sql vers scripts CSV.
// AUTEUR........ N.Dupont-Bloch
// DATE.......... 14/10/2003
// VERSION....... 1.0
// PARAMETRES.... 
//***************************************
require('../3d_config.inc.php');
require('../3d_database.inc.php');
require('../3d_fic.php');
$params = new Parametres();
session_start();
?>

<html>
<head>
<meta http-equiv="Content-Type"
content="text/html; charset=iso-8859-1">
<title>3D-Export scripts Sql vers Csv (1.0)</title>
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

//****** formulaire de sélection.
echo '<form method="POST" action="' .$PHP_SELF .'?act=export">';
echo '   <table border="0" cellspacing="0" bgcolor="' .$params->coul_fond3 .'">';
echo '   <tr><td colspan="2"><table border="1" cellpadding="0" cellspacing="0"><tr><td colspan="2">';
echo '   <div align="left">';
echo '      <table border="0" cellspacing="0">';
echo '      <tr><td width="80"><font face="Arial">scène               </font></td>';
echo '          <td><font face="Arial"><input type="text"  size="3" name="scene" value="' .$scene .'"></font></td></tr>';
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
echo '       <font face="Arial"><input type="submit" name="bt_ok" value="Exporter"></font></td>';
echo '       </tr>';
echo '    </table>';
echo '    </form>';
//******

switch ($act)
   {
   case 'export':
      //******************************************
      @$dbhandle  = mysql_connect($params->serveur_database, $params->user_database, $params->password_database)
      or die ('</br>La connexion au serveur a échoué: ' .mysql_error() .'</br>');
      @mysql_select_db($params->database ,$dbhandle)
      or die ('</br>La sélection de la base a échoué: ' .mysql_error() .'</br>');

      //*** lit la constitution des images.
      $req  = 'SELECT tab_act.id_rec, tab_act.id_scn, tab_act.id_img, tab_act.id_act, tab_act.id_obj, tab_act.act';
      $req .= ' FROM tab_act';
      $req .= ' WHERE tab_act.id_scn = '  .$scene;
      $req .= ' AND   tab_act.id_img >= ' .$depart_image;
      $req .= ' AND   tab_act.id_img <= ' .$arrivee_image;
      $req .= ' ORDER by tab_act.id_scn, tab_act.id_img, tab_act.id_act';
      //echo $req;
      $resultat = mysql_query($req)
      or die ('</br>erreur query (read): ' .mysql_error());
      unset ($fout);
      while ($row = mysql_fetch_array($resultat))
         {
         if ($id_img != $row['id_img'])
            {//****** rupture image: nouveau fichier incrémental.
            $id_img = $row['id_img'];
            if (isset($fout))
               {
               fclose($fout);
               }
            $numero = $row['id_img'];
            while (strlen($numero) < 4)
               {
               $numero = '0' .$numero;
               }
            $nom_fout = '../' .$params->dir_anims .$row['id_scn'] .'-' .$numero .'.3da';
            $fout = fopen($nom_fout, 'w');
            fputs($fout, c_fichier_separateur_colonne .'scene ' .$row['id_scn'] .'; image ' .$row['id_img'] .c_fichier_separateur_ligne);
            }
         //****** exporte action.
         $c = $row['act'];
         $commande = explode(c_fichier_separateur_colonne, $c);
         $objet = $commande[2];
         if ($objet != $objet_prec)
            {
            fputs($fout, ';-----------------------------' .c_fichier_separateur_ligne);
            $objet_prec = $objet;
            }
         fputs($fout, $c .c_fichier_separateur_ligne);
         }
      fclose($fout);
      echo '</TABLE>';
      mysql_close();
      break;
   }
?>
</body>
</html>
