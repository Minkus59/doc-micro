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

$hiver_debut=mktime(0,0,0,12,21,14);
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
<title>Doc-Micro - Web-création</title>
<META http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<META name="description" content="Création de site internet (flash, php/mysql et 3D)." >
<META name="keywords" content="graphisme, création, refonte, e-commerce, site vitrine, vitrine, catalogue, site catalogue, carte de visite, carte, visite, webmaster, site internet, site, internet, facturation, logiciel de facturation, formation informatique, formation à domicile, assistance à domicile, assistance, web-designer, developpeur php, creation de site internet, assistance à distance, depannage, lille, roubaix, tourcoing, croix, metropole lilloise, edition facture,boutique en ligne, devis, facture, devis, devis batiment, facture batiment, facture artisant, devis artisant, devis / facture, doc, micro, docmicro, doc-micro, Helinckx, Helinckx Michael, Minkus">
<META name="robots" content="index, follow">
<META name="category" content="Web-création">
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
<div id="maintenance">
06 52 66 06 45 - Apelez nous !
</div></div>

<div id="left">
<div id="int">
<?php
require('../lib/script/menu_gauche.inc.php');
?>
</div>
</div>

<div id="middle">

<div id="middle_int">
<div id="text">Besoin d'un site internet ?<hr></hr></div>
<div id="cadre2">A partir de 250€, Doc-Micro vous offre une prestation selon vos besoins et surtout selon 
votre budget.
<p>Site Carte de visite, site Vitrine, site Catalogue ou site E-commerce.
<p>Que vous soyez professionnel ou amateur dans le domaine d'internet, pas 
de panique, Doc-Micro vous guidera, pas à pas pour amener votre société 
sur la toile ou que vous soyez en France.
<p>De nos jours la majorité des ventes et transactions, passe inévitablement 
par l'internet. Facile d'accès et toujours à porter de main, avoir un site 
internet est devenu banale, mais surtout indispensable.
<p>20 % de votre clientèle se trouve sur internet, à vous de l'attirer.
<p>Doc-Micro vous propose une architecture de sites internet différente et 
vous assure une bonne mise en avant de vos produits, une navigation 
simple et rapide.        
<p>Les sites internet proposés par Doc-Micro sont différents et cela leur donne une 
façon simple et efficace de marquer l'esprit de vos clients et ainsi vous sortir du lot.</p>
Voici quelques sites internet que j'ai réalisés : </p>

<div id="portefolio">
<ul>
<li><a Href="https://www.googgle.cc" target="_blank"><img src="../lib/site/img/googgle.png"/></a></li>
<li><a Href="http://www.only-men.net/" target="_blank"><img src="../lib/site/img/onlymen.png"/></a></li>
<li><a Href="../lib/site/rapidopizza/" target="_blank"><img src="../lib/site/img/rapidopizza.png"/></a></li>
<li><a Href="http://3donweb.free.fr" target="_blank"><img src="../lib/site/img/3donweb.png"/></a></li>
</div>
</div></div>
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
<?php
require('../lib/script/menu_bas.inc.php');
?>
</div>
</div>
</div>
<div id="compa" class="petit">
Si le site Internet ne s'affiche pas correctement <a Href="/?compa=ancien">cliquez ici</a>
</div>
</CENTER>
</body>
</html>         			