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

//----------------------------------------------------------------------

// Récupération des paramètres POST
$Nom2= htmlspecialchars($_POST['nom2']);
$Prenom2= htmlspecialchars($_POST['prenom2']);
$Societe2= htmlspecialchars($_POST['societe2']);
$Email2= htmlspecialchars($_POST['email2']);
$Tel2= htmlspecialchars($_POST['tel2']);
$Commentaire2= htmlspecialchars($_POST['commentaire2']);
$Budjet= htmlspecialchars($_POST['budjet']);
$Parainage2= htmlspecialchars($_POST['parrain2']);
$Valid2=$_POST['valid'];

// Une fois le formulaire envoyé

if(isset($_POST["Valider"])) {

	//VERIFFFF -----------------------------------------------------------------------------------------
	
		
	if (!preg_match("#^[A-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $Email2)){ 
		$erreur_email= "<br/>L'adresse email n'est pas valide !<br />";
		}
		
	elseif ($Valid2!="on"){
		$erreur_valid2= "Veuillez certifier l'exactitude des données saisie !<br />";
		}

		else {

			// declaration du mail
								
						$headers1 ='From: "http://www.Doc-Micro.fr"<contact@doc-micro.fr>'."\n"; 						
						$headers1 .='Content-Type: text/html; charset="iso-8859-1"'."\n"; 						
						$headers1 .='Content-Transfer-Encoding: 8bit'; 																		
						$message1 ="<html><head><title>Nouveau Devis</title></head>						
							<body><p>Nouveau Devis</p>					
							<p>$Nom2<p>$Prenom2<p>$Societe2<p>$Email2<p>$Tel2<p>$Commentaire2<p>$Budjet<p>$Parainage2<p>$Valid2						
							</body></html>" ; 	

                                                               
								//cnx et ajout bdd

				                                cnx();
                    
                                                                @MySQL_query('INSERT INTO docmicro_devis (nom, prenom, societe, email, telephone, description, budjet, parainage, certifier, created)
								VALUES("'.$Nom2.'", "'.$Prenom2.'", "'.$Societe2.'", "'.$Email2.'", "'.$Tel2.'", "'.$Commentaire2.'", "'.$Budjet.'", "'.$Parainage2.'", "1", NOW())');					

								$num_devis= @MySQL_num_rows(@MySQL_query('SELECT * FROM docmicro_devis'));

                                                                // Envoi mail confirmation --------------------------------------------------------------------------
							
								if (!mail("contact@doc-micro.fr", "Devis : ".$num_devis, $message1, $headers1)) { 							
									$erreur_mail= "L'email  n'a pu etre envoyé, vérifiez que vous l'avez entré correctement !";															
									mysql_close(); }
							
								// Insertion des donnees ----------------------------------------------------------------------------

										else {
                                                      
											unset($Nom2, $Prenom2, $Societe2, $Email2, $Tel2, $Parrain2, $Commentaire2, $Budjet, $Valid2);			
											$erreur_yes= "Merci de votre intérêt, votre devis sera envoyé dans les plus bref delai par E-mail.<br />";																																							
											
					}
				}
			}
//--------------------------------------------------------------------------------------------------------------------------

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Doc-Micro - Devis</title>
<META http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<META name="description" content="Devis gratuit." >
<META name="keywords" content="doc, micro, docmicro, doc-micro, 3d, on, web, 3donweb, flash, 3ds max, 3ds, max, architechture, image, image de synthese, synthese, modelisation, maillage, texture, casa, presto, epernay, Helinckx, Michael, Helinckx Michael, 3donweb@free.fr, www.3donweb.fr, http://3donweb.free.fr, galerie 3d, galerie 2d, rapido-pizza, rapido, pizza, Minkus, 51200, 51, only-men, onlymen">
<META name="robots" content="index, follow">
<META name="category" content="Devis">
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
<div id="titre_assis">Devis personnalisé<hr></hr></div>

Nos devis sont GRATUITS et ne vous engagent à rien, toutes les informations resteront confidentielles.</p>
 
<div id="erreur"><?php 
if (isset($erreur_mail)) { echo $erreur_mail; } 
if (isset($erreur_yes)) { echo $erreur_yes; }
if (isset($erreur_email)) { echo $erreur_email; }
if (isset($erreur_valid2)) { echo $erreur_valid2; }
?></div>

<form id="form_devis" action="" method="POST">

<label class="col_1" for="nom2">Nom :</label>
<input type="text" name="nom2" id="nom2"/>
<span class="erreur">Le nom doit être saisie !</span>
</br>
<label class="col_1" for="prenom2">Prénom :</label>
<input type="text" name="prenom2" id="prenom2"/>
<span class="erreur">Le prénom doit être saisie !</span>
</br>
<label class="col_1" for="email2">E-mail :</label>
<input type="text" name="email2" id="email2"/>
<span class="erreur">L'E-mail doit être saisie et conforme !</span>
</br>
<label class="col_1" for="societe2">Société :</label>
<input type="text" name="societe2" id="societe2"/>
</br>
<label class="col_1" for="tel2">Numéro de téléphone :</label>
<input type="text" name="tel2" id="tel2"/>
<span class="erreur">Le n° de téléphone doit être saisie !</span>
</br>
<label class="col_1" for="commentaire2">Décrivez avec un maximum de détails votre projet (site internet, assistance ou formation) :</label>
<textarea name="commentaire2" id="commentaire2" cols="40" rows="8"></textarea>
<span class="col_1"></span>
<span class="erreur">La description doit être saisie !</span>
</br>
<label class="col_1" for="budjet">Votre budget :</label>
<input type="text" name="budjet" id="budjet"/>
</br>
<label class="col_1" for="parrain2">Comment ou Qui vous a fait connaître Doc-Micro ? :</label>
<input type="text" name="parrain2" id="parrain2"/>
</br>
<label class="col_1" for="valid">Je certifie sur l'honneur de l'exactitude des informations saisies :</label>
<input type="checkbox" name="valid" id="valid"/>
<span class="erreur">Vous devez certifier sur l'honneur !</span>
</br>
<span class="col_1"></span>
<input type="submit" name="Valider" value="Envoyer"/>
</form>

<script type="text/javascript" src="../lib/script/Devis.js"></script>

</div></div>

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