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
<title>Doc-Micro - Formation</title>
<META http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<META name="description" content="Formation à domicile dans un rayon de 30km autour d'Epernay." >
<META name="keywords" content="graphisme, création, refonte, e-commerce, site vitrine, vitrine, catalogue, site catalogue, carte de visite, carte, visite, webmaster, site internet, site, internet, facturation, logiciel de facturation, formation informatique, formation à domicile, assistance à domicile, assistance, web-designer, developpeur php, creation de site internet, assistance à distance, depannage, lille, roubaix, tourcoing, croix, metropole lilloise, edition facture,boutique en ligne, devis, facture, devis, devis batiment, facture batiment, facture artisant, devis artisant, devis / facture, doc, micro, docmicro, doc-micro, Helinckx, Helinckx Michael, Minkus">
<META name="robots" content="index, follow">
<META name="category" content="Formation">
<META name="msvalidate.01" content="70B01C3E3B7ABDD862DCCE23D28D7257" />

<META name="viewport" content="width=device-width, initial-scale=0.3" />
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
<div id="text">
Doc Micro vous présente ses formations à domicile, parmi les thèmes suivants :
<hr></hr></div>

<div id="cadre">
<div id="titre">
Courrier électronique (email)
</div>
<div id="theme">
<ul><li>Comment configurer sa boîte mail</li>
<li>Envoyer, consulter ses E-mails</li>
<li>Envoyer, recevoir des pièces jointes</li>
<li><< Spam >> : l'éviter et comment s'en débarrasser.</li></ul>
- Formation de 30 minutes, PRIX: 25€ HT
</div><hr></hr></div>


<div id="cadre">
<div id="titre">
Navigation sur Internet
</div>
<div id="theme">
<ul><li>Internet ? C'est quoi ?</li>
<li>Paramétrer son navigateur</li>
<li>Abréviations ?</li>
<li>Surfer sur le net</li>
<li>La sécurité familiale (contrôle parental)</li>
<li>Les Virus, Trojans, comment se protéger ?</li></ul>
- Formation d'1 heure, PRIX: 50€ HT
</div><hr></hr></div>

<div id="cadre">
<div id="titre">
L'ordinateur, comment l'utiliser ?
</div>
<div id="theme">
<ul><li>Comment ça marche ?</li>
<li>Installation et désinstallation de programmes</li>
<li>Nettoyage du disque</li>
<li>Quelques raccourcies</li>
<li>Protéger vos données</li>
<li>Les Virus, << spywares >>, comment se protéger ?</li></ul>
- Formation de 2 heures, PRIX: 100€ HT
</div><hr></hr></div>

<div id="cadre">
<div id="titre">
Le WIFI, tout savoir
</div>
<div id="theme">
<ul><li>Comment ça marche ?</li>
<li>Paramétrer son réseau</li>
<li>Connexion au réseau</li>
<li>Protéger vos données</li>
<li>Les pirates, comment se protéger ?</li></ul>
- Formation de 2 heures, PRIX: 100€ HT
</div><hr></hr></div>

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