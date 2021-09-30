<?php
/***************************************/
// PROGRAMME..... navigateur 3D
// MODULE........ Visualisation d'une scène en appelant le moteur 3D avec paramètres de navigation.
//                Une scène est soit un scipt (*.3da) soit une scène SQL.
// AUTEUR........ N.Dupont-Bloch
// DATE.......... 14/10/2003
// VERSION....... 1.2
// PARAMETRES.... 
/***************************************/
require('../3d_config.inc.php');
$params = new Parametres();

$path = pathinfo($PHP_SELF); 
?>

<HTML>
<HEAD>
<TITLE>3D-Navigateur (1.10)</TITLE>
<SCRIPT language = "JavaScript">
var dx = 0;
var dy = 0;
var dz = 0;
var da = 0;
var db = 0;
var dg = 0;

var imgs = false; // surlignage des faces.
var filaire = false;
var eclairage = "scn";
var imgf = "png";
var img_ratio;     // vaut toujours imgx/imgy.
var fout = "";     // fichier de sauvegarde image.
var fin  = "";     // fichier de script texte (csv).
var scn  = "";     // si script issu de SGBD, n° de scène.

function afficher()
   {
   var p1      = document.frm_navig.p1.value;
   var serveur_moteur_3d = document.frm_navig.serveur_moteur_3d.value;
   var serveur_database  = document.frm_navig.serveur_database.value;

   var fin = document.frm_navig.fin.value;
   var imgs = document.frm_navig.surlignage.value;
   var eclairage = document.frm_navig.eclairage.value;
   var imgx = document.frm_navig.imgx.value;
   var imgy = document.frm_navig.imgy.value;
   var imgz = document.frm_navig.imgz.value;
   var filaire = document.frm_navig.filaire.value;

   top.cible.document.images[0].src = "http://" +serveur_moteur_3d + "<?php echo $params->dir_racine; ?>" +"/3d_aff_fast.php?dx="+dx +"&dy="+dy +"&dz="+dz +"&da="+da +"&db="+db +"&dg="+dg +"&fil=" +filaire +"&imgx=" +imgx +"&imgy=" +imgy +"&imgz=" +imgz +"&imge=" +eclairage +"&imgs=" +imgs +"&imgf=" +imgf +"&scn=" +scn +"&dbsrv=" +serveur_database +"&fin=" +fin +"&fout=" +fout +"&p1=" +p1;
   document.frm_navig.url.value    = top.cible.document.images[0].src;
   document.frm_navig.val_dx.value = dx;
   document.frm_navig.val_dy.value = dy;
   document.frm_navig.val_dz.value = dz;
   document.frm_navig.val_da.value = da;
   document.frm_navig.val_db.value = db;
   document.frm_navig.val_dg.value = dg;
   document.frm_navig.imgx.value = imgx;
   document.frm_navig.imgy.value = imgy;
   document.frm_navig.imgz.value = imgz;
   img_ratio = imgx / imgy;
   document.frm_navig.img_ratio.value = img_ratio;
   }

function executer()
   {
   scn = document.frm_navig.scn.value;
   fin = ""; // le fichier ou le SGBD.
   afficher();
   }

function enregistrer()
   {
   // suppose que nom du fichier + extension corrects.
   fout = document.frm_navig.fout.value;
   if ((fout == "*.jpg") ||
       (fout == "*.png"))
      {
      fout = "";
      }
   afficher();
   fout = "";
   }

function changer_fin()
   {
   scn = ""; // c'est le SGBD ou le fichier.
   fin = document.frm_navig.fin.value;
   afficher();
   }

function changer_imgf()
   {
   imgf = document.frm_navig.imgf.value;
   if (imgf == "png")
      {
      document.frm_navig.fout.value = "*.png";
      }
   else if (imgf == "jpg")
      {
      document.frm_navig.fout.value = "*.jpg";
      }
   afficher();
   }

function inc_x()
   {
   dx += 1;
   afficher();
   }

function inc_xx()
   {
   dx += 10;
   afficher();
   }

function dec_x()
   {
   dx -= 1;
   afficher();
   }

function dec_xx()
   {
   dx -= 10;
   afficher();
   }

function inc_y()
   {
   dy += 1;
   afficher();
   }

function inc_yy()
   {
   dy += 10;
   afficher();
   }

function dec_y()
   {
   dy -= 1;
   afficher();
   }

function dec_yy()
   {
   dy -= 10;
   afficher();
   }

function inc_z()
   {
   dz += 1;
   afficher();
   }

function inc_zz()
   {
   dz += 10;
   afficher();
   }

function dec_z()
   {
   dz -= 1;
   afficher();
   }

function dec_zz()
   {
   dz -= 10;
   afficher();
   }

function inc_a()
   {
   da += 1;
   if (da > 359)
      {da -= 359;
      }
   afficher();
   }

function inc_aa()
   {
   da += 10;
   if (da > 359)
      {da -= 359;
      }
   afficher();
   }

function dec_a()
   {
   da -= 1;
   if (da < 0)
      {da += 360;
      }
   afficher();
   }

function dec_aa()
   {
   da -= 10;
   if (da < 0)
      {da += 360;
      }
   afficher();
   }

function inc_b()
   {
   db += 1;
   if (db > 359)
      {db -= 360;
      }
   afficher();
   }

function inc_bb()
   {
   db += 10;
   if (db > 359)
      {db -= 360;
      }
   afficher();
   }

function dec_b()
   {
   db -= 1;
   if (db < 0)
      {db += 360;
      }
   afficher();
   }

function dec_bb()
   {
   db -= 10;
   if (db < 0)
      {db += 360;
      }
   afficher();
   }

function inc_g()
   {
   dg += 1;
   if (dg > 359)
      {dg -= 360;
      }
   afficher();
   }

function inc_gg()
   {
   dg += 10;
   if (dg > 359)
      {dg -= 360;
      }
   afficher();
   }

function dec_g()
   {
   dg -= 1;
   if (dg < 0)
      {dg += 360;
      }
   afficher();
   }

function dec_gg()
   {
   dg -= 10;
   if (dg < 0)
      {dg += 360;
      }
   afficher();
   }

</SCRIPT>
</HEAD>
<BODY onload="afficher()">
<FORM name="frm_navig">
<TABLE border = "1" cellspacing="0" cellpadding="0" bgcolor="<?php echo $params->coul_fond3; ?>">
<tr>
   <td>
   <TABLE border = "0" cellspacing="1" cellpadding="0">
   <tr>
      <td>X</td>
      <td><input type="button" value="<<" onclick='dec_xx()'></td>
      <td><input type="button" value="<" onclick='dec_x()'></td>
      <td><input type="button" value=">" onclick='inc_x()'></td>
      <td><input type="button" value=">>" onclick='inc_xx()'></td>
      <td><input type="text"   name="val_dx" size="3" readonly></td>
   </tr>
   <tr>
      <td>Y</td>
      <td><input type="button" value="<<" onclick='dec_yy()'></td>
      <td><input type="button" value="<" onclick='dec_y()'></td>
      <td><input type="button" value=">" onclick='inc_y()'></td>
      <td><input type="button" value=">>" onclick='inc_yy()'></td>
      <td><input type="text"   name="val_dy" size="3" readonly></td>
   </tr>
   <tr>
      <td>Z</td>
      <td><input type="button" value="<<" onclick='dec_zz()'></td>
      <td><input type="button" value="<" onclick='dec_z()'></td>
      <td><input type="button" value=">" onclick='inc_z()'></td>
      <td><input type="button" value=">>" onclick='inc_zz()'></td>
      <td><input type="text"   name="val_dz" size="3" readonly></td>
   </tr>
   </TABLE>
   </td>

   <td>
   <TABLE border = "0"  cellspacing="1" cellpadding="0">
   <tr>
      <td><font face="Arial">A</font></td>
      <td><input type="button" value="<<" onclick='dec_aa()'></td>
      <td><input type="button" value="<" onclick='dec_a()'></td>
      <td><input type="button" value=">" onclick='inc_a()'></td>
      <td><input type="button" value=">>" onclick='inc_aa()'></td>
      <td><input type="text"   name="val_da" size="3" readonly></td>
   </tr>
   <tr>
      <td><font face="Arial">B</font></td>
      <td><input type="button" value="<<" onclick='dec_bb()'></td>
      <td><input type="button" value="<" onclick='dec_b()'></td>
      <td><input type="button" value=">" onclick='inc_b()'></td>
      <td><input type="button" value=">>" onclick='inc_bb()'></td>
      <td><input type="text"   name="val_db" size="3" readonly></td>
   </tr>
   <tr>
      <td><font face="Arial">G</font></td>
      <td><input type="button" value="<<" onclick='dec_gg()'></td>
      <td><input type="button" value="<" onclick='dec_g()'></td>
      <td><input type="button" value=">" onclick='inc_g()'></td>
      <td><input type="button" value=">>" onclick='inc_gg()'></td>
      <td><input type="text"   name="val_dg" size="3" readonly></td>
   </tr>
   </TABLE>
   </td>

   <td>
   <TABLE border = "0"  cellspacing="1" cellpadding="0">
   <tr>
      <td><font face="Arial">style</font></td>
      <td><select name="filaire" onchange="afficher()" size="1">
         <option  selected value="false">faces cachées</option>
         <option  value="true">filaire</option>
         </select>
         </td>
   </tr>
   <tr>
      <td><font face="Arial">surlignage</font></td>
      <td><select name="surlignage" onchange="afficher()" size="1">
         <option  selected value="false">selon les faces</option>
         <option  value="true">toujours</option>
         </select></td>
   </tr>
   <tr>
      <td><font face="Arial">éclairage</font></td>
      <td><select name="eclairage" onchange="afficher()" size="1">
         <option  selected value="scn">sources de lumière</option>
         <option  value="std">éclairage standard</option>
         <option  value="off">aucun</option>
         </select></td>
   </tr>
   </TABLE>
   </td>

   <td>
   <TABLE border = "0"  cellspacing="1" cellpadding="0">
   <tr>
      <td><font face="Arial">largeur</font></td>
      <td><input type="text" name="imgx" size="3" onchange="afficher()" value="640"></td>
   </tr>
   <tr>
      <td><font face="Arial">hauteur</font></td>
      <td><input type="text" name="imgy" size="3" onchange="afficher()" value="480"></td>
   </tr>
   <tr>
      <td><font face="Arial">ratio</font></td>
      <td><input type="text" name="img_ratio" size="3" readonly></td>
   </tr>
   <tr>
      <td><font face="Arial">focale</font></td>
      <td><input type="text" name="imgz" size="3" onchange="afficher()" value="200"></td>
   </tr>
   </TABLE>
   </td>
</tr>
<tr>
   <td colspan="2" rowspan="2">
      <TABLE border="0" cellspacing="1" cellpadding="0">
      <tr>
         <td><font face="Arial">enregister sous</font></td>
         <td><input type="text" name="fout" value="*.png" size="19" onchange="afficher()()"></td>
      </tr>
      <tr>
         <td><font face="Arial">format</font></td>
         <td><select name="imgf" onchange="changer_imgf()" size="1">
            <option  value="png">png</option>
            <option  value="jpg">jpg</option>
            </select>
         <input type="button" value="enregistrer" name="bt_enregistrer" onclick="enregistrer()"></td>
      </tr>
      </TABLE>
      </td>
   <td>
      <TABLE border="0" cellspacing="1" cellpadding="0">
      <tr>
         <td><font face="Arial"> scène n°</font></td>
         <td><input type="text" name="scn" size="3"></font>
             <font face="Arial"> image n°</font>
             <input type="text" name="p1" size="3"></font>
             </td>
         <td></td><td><input type="button" value="Exécuter" name="bt_executer" onclick="executer()"></td>
      </tr>
      </TABLE>
      </td>
   <td rowspan="2">
      <TABLE border="0" cellspacing="1" cellpadding="0">
      <tr><td><font face="Arial"><font face="Arial">serveur 3D</td><td><font face="Arial"><input type="text" size="10" name="serveur_moteur_3d" value="<?php echo $params->serveur_moteur_3d; ?>" onchange="afficher()"></td></tr>
      <tr><td><font face="Arial"><font face="Arial">serveur SQL</td><td><font face="Arial"><input type="text" size="10" name="serveur_database" value="<?php echo $params->serveur_database; ?>" onchange="afficher()"></td></tr>
      </TABLE>
      </td>
</tr>
<tr>
   <td>
   <TABLE border="0" cellspacing="1" cellpadding="0">
   <tr><td width="140"><font face="Arial">script</font></td>
       <td><font face="Arial"><SELECT name="fin" onchange="changer_fin()"></font>
      <?php
      //*** cherche tous les scripts.
      $repertoire = '../' .$params->dir_anims;
      $handle = opendir($repertoire);
      while ($file = strtolower(readdir($handle))) {
         if (substr($file, -4) == '.3da')
            {
            $nom_script = strtolower(substr($file, 0, strlen($file)-4));
            $selected = ($fin == $file) ? 'selected' : '';
            echo '<option ' .$selected .' value="' .$file .'">' .$nom_script .'</option>';
            }
         }
      closedir($handle);
      ?>
      </SELECT></font></td>
   </tr>
   </TABLE>
   </td>
</tr>
</TABLE>
<input type="text" name="url" size="100">
</FORM>
</BODY>
</HTML>
