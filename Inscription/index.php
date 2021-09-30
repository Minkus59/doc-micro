<?php
require('../lib/script/cnx.inc.php');

//---------------------------------------
//Comteur
session_start(); 
if(file_exists('compteur.txt')) 
{ 
 $compteur_f = fopen('compteur.txt', 'r+'); 
 $compte = fgets($compteur_f); 
} 
else 
{ 
 $compteur_f = fopen('compteur.txt', 'a+'); 
 $compte = 0; 
} 

if(!isset($_SESSION['compteur'])) { 
 $_SESSION['compteur'] = 'visite'; 
 $compte++; 
 fseek($compteur_f, 0); 
 fputs($compteur_f, $compte); 
} 

fclose($compteur_f); 

//---------------------------------------
//compatibilite

if (!isset($_SESSION['compa'])) {
  $_SESSION[compa] = 'nouveau';
}

if ((isset($_GET['compa']))&&($_GET['compa']=="ancien")) {
  $_SESSION[compa] = 'ancien';
}

//---------------------------------------
//theme

$hiver_debut=mktime(0,0,0,12,21,13);
$hiver_fin=mktime(0,0,0,3,20,14);
$primtemp_debut=mktime(0,0,0,3,20,14);
$primtemp_fin=mktime(0,0,0,6,21,14);
$ete_debut=mktime(0,0,0,6,21,14);
$ete_fin=mktime(0,0,0,9,21,14);
$automne1_debut=mktime(0,0,0,9,21,14);
$automne1_fin=mktime(0,0,0,10,31,14);

$halloween_debut=mktime(0,0,0,10,31,14);
$halloween_fin=mktime(0,0,0,10,7,14);

$automne2_debut=mktime(0,0,0,10,7,13);
$automne2_fin=mktime(0,0,0,12,21,13);

$actuel=time();

//---------------------------------------
//theme

if (!isset($_SESSION['theme'])) {
  
  if (($actuel>$automne1_debut)&&($actuel<$automne1_fin)) {
    $_SESSION[theme] = 'automne';
  }

  if (($actuel>$automne2_debut)&&($actuel<$automne2_fin)) {
    $_SESSION[theme] = 'automne';
  }

  if (($actuel>$halloween_debut)&&($actuel<$halloween_fin)) {
    $_SESSION[theme] = 'halloween';
  }

  if (($actuel>$hiver_debut)&&($actuel<$hiver_fin)) {
    $_SESSION[theme] = 'hiver';
  }

  if (($actuel>$primtemp_debut)&&($actuel<$primtemp_fin)) {
    $_SESSION[theme] = 'normal';
  }

  if (($actuel>$ete_debut)&&($actuel<$ete_fin)) {
    $_SESSION[theme] = 'normal';
  }

}

if ((isset($_GET['theme']))&&($_GET['theme']=="normal")) {
  $_SESSION[theme] = 'normal';
}

if ((isset($_GET['theme']))&&($_GET['theme']=="hiver")) {
  $_SESSION[theme] = hiver;
}

if ((isset($_GET['theme']))&&($_GET['theme']=="halloween")) {
  $_SESSION[theme] = 'halloween';
}

if ((isset($_GET['theme']))&&($_GET['theme']=="automne")) {
  $_SESSION[theme] = 'automne';
}

$Theme=$_SESSION['theme'];

//----------------------------------------------------------------------
// Compteur MySQL

if(!isset($_SESSION['visite'])) { 
     $_SESSION['visite'] = 'visite';

     $Ip=$_SERVER['REMOTE_ADDR'];

     cnx();

     $ajout=@MySQL_query('INSERT INTO docmicro_visite (hote, created)
                         VALUES("'.$Ip.'", NOW())');
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Doc-Micro - Contact</title>
<META http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<META name="description" content="N'hésitez pas à nous contacter par téléphone ou par E-mail " >
<META name="keywords" content="doc, micro, docmicro, doc-micro, 3d, on, web, 3donweb, flash, 3ds max, 3ds, max, architechture, image, image de synthese, synthese, modelisation, maillage, texture, casa, presto, epernay, Helinckx, Michael, Helinckx Michael, 3donweb@free.fr, www.3donweb.fr, http://3donweb.free.fr, galerie 3d, galerie 2d, rapido-pizza, rapido, pizza, Minkus, 51200, 51, only-men, onlymen">
<META name="robots" content="index, follow, no">
<META name="msvalidate.01" content="70B01C3E3B7ABDD862DCCE23D28D7257" />

<META name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<link rel="shortcut icon" href="../lib/img/doc-micro.ico" />

<?php

if ((isset($_SESSION['compa']))&&($_SESSION[compa]=='ancien')) {
        ?><link href="../lib/css/docmicro_ancien.css" rel="stylesheet" type="text/css"/><?php
}
else {
$nav=explode("(",$_SERVER["HTTP_USER_AGENT"]);

if (($nav[0] == "Mozilla/4.0 ")||($compa=="true")) {
	?><link href="../lib/css/docmicro_ancien.css" rel="stylesheet" type="text/css"/><?php
}
if ($nav[0] == "Mozilla/5.0 ") {
	?><link href="../lib/css/docmicro.css" rel="stylesheet" type="text/css"/><?php
}
}
?>
</head>

<body>
<CENTER>
<div id="all"  class='<?php echo $Theme; ?>'>
<div id="head">
<?php

if (isset($erreur)) {
echo $erreur;
}
?>
</div>

<div id="left">
<div id="int">
<?php
require('../lib/script/menu_gauche.inc.php');
?>
</div>
</div>

<div id="middle">
<div id="middle_int">







</div>
</div>

<div id="vide"><div id="mention">
<li><a Href="../Mentions-legales/">Mentions-légales</a></li>
</div>

<div id="themesite">
Thème :
<li><a Href="../?theme=normal">Normal</a> - <a Href="../?theme=automne">Automne</a> - <a Href="../?theme=halloween">Halloween</a> - <a Href="../?theme=hiver">Hiver</a></li>
</div></div>

<div id="footer">
<div id="bouton">
<ul>
<li><a Href="../">Accueil</a></li>
<li><a Href="../Assistance/">Assistance</a></li>
<li><a Href="../Formation/">Formation</a></li>
<li><a Href="../Web-creation/">Web création</a></li>
<li><a Href="../Devis/">Devis</a></li>
<li><a Href="../Contact/">Contact</a></li>
</ul>
</div>
</div>
</div>
<div id="compa" class="petit">
Si le site Internet ne s'affiche pas correctement <a Href="/?compa=ancien">cliquez ici</a>
</div>
</CENTER>
</body>
</html>         			