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
<title>Doc-Micro - Mentions-l�gales</title>
<META http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<META name="description" content="Mentions-l�gales." >
<META name="keywords" content="graphisme, cr�ation, refonte, e-commerce, site vitrine, vitrine, catalogue, site catalogue, carte de visite, carte, visite, webmaster, site internet, site, internet, facturation, logiciel de facturation, formation informatique, formation � domicile, assistance � domicile, assistance, web-designer, developpeur php, creation de site internet, assistance � distance, depannage, lille, roubaix, tourcoing, croix, metropole lilloise, edition facture,boutique en ligne, devis, facture, devis, devis batiment, facture batiment, facture artisant, devis artisant, devis / facture, doc, micro, docmicro, doc-micro, Helinckx, Helinckx Michael, Minkus">
<META name="robots" content="index, follow">
<META name="category" content="Mentions-L�gales">
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

<p><div id="titre"><STRONG>Doc-Micro : </STRONG></div></p>
<li>HELINCKX Michael</li>
<li>SIRET: 797 723 145 00018</li>
<li>Si�ge social : 7 rue du Commandant POTHIER, 51200 Epernay</li>
<li>Code APE : 6201Z</li>
<li> Directeur de la publication : HELINCKX Michael</li>
<li>Depuis le 01/11/2013</li>
<hr></hr>
<div id="titre"><STRONG>Hebergement : </STRONG></div></p>
<li>OVH</li>
<li>SAS au capital de 10 000 000 � </li>
<li>RCS Roubaix � Tourcoing 424 761 419 00045</li>
<li> Code APE 6202A</li>
<li> N� TVA : FR 22 424 761 419</li>
<li> Si�ge social : 2 rue Kellermann - 59100 Roubaix - France.</li>
<li> Directeur de la publication : Octave KLABA</li>

<hr></hr><hr></hr>
<p><STRONG>CONDITIONS GENERALES DE VENTE</STRONG>

<p><div id="titre">� OBJET</div></p>

<li> Les pr�sentes Conditions G�n�rales de Vente ont pour objet de d�finir les droits et les obligations 
des parties dans le cadre de la cr�ation / gestion de sites internet r�alis�e par Doc-Micro.</li>
<li> Le CLIENT reconna�t avoir pris connaissance, au moment de la passation de la commande, des conditions
 g�n�rales et particuli�res et d�clare express�ment les accepter sans r�serve.</li>
<hr></hr>
<p><div id="titre"> � OBLIGATION DE Doc-Micro</div> </p>

<li> Doc-Micro s'engage � concevoir et mettre en ligne le site Internet.</li>
<li> Doc-Micro s'engage � mettre tout en �uvre pour assurer la permanence, la continuit� et la qualit� des services fournis au client.</li>
<li> Il est express�ment sp�cifi� que Doc-Micro n�a qu�une obligation de moyens (respecter le cahier des charges) 
et en aucun cas ne saurait �tre tenu d�une obligation de r�sultats. Doc-Micro ne saurait donc �tre tenu responsable
 d�une quelconque d�gradation du site ayant un impact direct ou indirect sur les r�sultats du site ou l�image de la soci�t�,
 �tant donn� le caract�re incontr�lable d�Internet.</li>
<li> En aucun cas, la responsabilit� de Doc-Micro ne pourra �tre recherch�e en cas de : </li>
<ul><li>Faute, n�gligence, omission, non-respect des conseils donn�s ou d�faillance du CLIENT.
<li>Faute, n�gligence ou omission d�un tiers sur lequel Doc-Micro n�a aucun pouvoir de contr�le de surveillance.</li>
<li>Force majeure, �v�nement ou incident ind�pendant de la volont� de Doc-Micro.</li>
<li>Doc-Micro ne pourra �tre tenu responsable envers le CLIENT des cons�quences de l'introduction d'un virus informatique 
dans le serveur web ou dans le site ayant un effet sur son bon fonctionnement ou dans le r�seau interne de Doc-Micro,
 de la migration du site dans un environnement mat�riel ou logiciel diff�rent, des modifications apport�es aux composants logiciels
 par Doc-Micro ou par une personne autre que Doc-Micro , d'une baisse du chiffre d'affaires cons�cutive au fonctionnement ou � l'absence de fonctionnement,
 ou � l'utilisation ou � l'absence d'utilisation du site ou des informations s'y trouvant ou devant s'y trouver,
 d'intrusion ill�gale ou non autoris�e de tout tiers dans le serveur web ou dans le site, d'un encombrement temporaire de la bande passante,
 d'une interruption du service de connexion � Internet pour une cause hors de contr�le de Doc-Micro.</li>
</ul>
<hr></hr>
<p><div id="titre"> � OBLIGATION DU CLIENT</div></p>

<li> Le CLIENT reconna�t avoir re�u de Doc-Micro toutes les informations et conseils qui lui �taient n�cessaires
 pour souscrire au pr�sent engagement en connaissance de cause. Ainsi, les choix effectu�s par le CLIENT lors de la
 commande ainsi qu'�ventuellement par la suite, demeurent sous son enti�re responsabilit�. Le CLIENT s'engage � fournir
 � Doc-Micro tous les documents, renseignements et informations afin de lui permettre de r�aliser le site conform�ment
 aux besoins et souhaits du client.</li>
<li> En application des dispositions l�gales et notamment de la loi du 30 septembre 1986 modifi�e, le CLIENT
 est civilement et p�nalement responsable du contenu de son site, des informations transmises, diffus�es et/ou collect�es,
 de leur exploitation, des liens hypertextes, des revendications de tiers et actions p�nales qu'elles suscitent, notamment
 en mati�re de propri�t� intellectuelle, de droits de la personnalit� et de protection des mineurs. Le CLIENT s'engage � 
respecter les lois et r�glements en vigueur dont notamment les r�gles ayant trait au fonctionnement des services en ligne,
 au commerce �lectronique, aux droits d'auteur, aux bonnes m�urs et � l'ordre public ainsi que les principes universels d'usage 
de l'Internet, commun�ment appel�s "Netiquette". </li>
<li> Le CLIENT s'engage � respecter les dispositions relatives aux mentions l�gales obligatoires � ins�rer sur son site web
 en vertu de la loi du 30 septembre 1986 modifi�e et celles relatives � l'informatique, aux fichiers et aux libert�s, en particulier
 celles relatives aux d�clarations des traitements automatis�s d'informations nominatives aupr�s de la Commission Nationale de l'Informatique
 et des Libert�s (C.N.I.L.).</li>
<li> Payer le prix convenu au pr�sent contrat � la signature du devis, selon les modalit�s de paiement d�finies aux articles 10 et 11.</li>

<hr></hr>
<p><div id="titre"> � SIGNATURE DU SITE</div></p>

<li> Les coordonn�es de Doc-Micro figureront en � signature sur le site � mais celles-ci peuvent �tre retir�es � tout moment
 � la demande de Doc-Micro . Notamment, le CLIENT qui souhaite faire modifier le site par un tiers devra en informer Doc-Micro
 afin que celui-ci puisse d�cider s�il souhaite ou non laisser ses coordonn�es sur le site modifi�. Exemple de signature en pied
 de page � Site r�alis� par la soci�t� Doc-Micro �, avec les URL de redirections. </li>
<hr></hr>
<p><div id="titre"> � MODIFICATION PAR UN TIERS OU LE CLIENT</div></p>

<li> Le CLIENT sera libre d�exploiter son site comme il l�entend, ou de le faire modifier soit par Doc-Micro soit par un tiers
 ou lui-m�me. Il a express�ment �t� convenu qu�en cas de modification par un tiers ou par le CLIENT, Doc-Micro signataire des pr�sentes
 devra donner son accord pour que ses coordonn�es demeurent sur le site, et ne sera plus responsable de son bon fonctionnement. </li>
<hr></hr>
<p><div id="titre"> - COMMANDE</div></p>

<li>  Toute commande pass�e par le client � Doc-Micro est formalis�e par la production d�un devis reprenant les caract�ristiques 
de la prestation demand�e, remis en main propre ou envoy� par courrier ou e-mail. La commande devient effective d�s lors que
 Doc-Micro a re�u le devis dat�, sign� par le client et accompagn� du montant des ahres pr�vu.</li>
<li>  Le d�lai de validit� du devis ainsi que le montant des ahres demand� sont stipul�s dans le devis.</li>
<li>  Les commandes ne sont plus susceptibles de modification ou m�me d�annulation apr�s acceptation par Doc-Micro du devis sign�
 par le client.</li>
<li>  Les suppl�ments �ventuellement command�s � une p�riode ult�rieure doivent faire l'objet d'un nouveau devis.</li>

<hr></hr>
<p><div id="titre"> � PRIX</div></p>

<li>  Le � Devis Doc-Micro � est valable pour une dur�e d'un mois, � compter de sa date de r�alisation. Le montant total 
de la prestation figure sur le � Devis Doc-Micro �. Tous les prix s'entendent en euros toutes taxes comprise.</li>
<li>  Les renseignements pr�sents sur le site Internet � Doc-Micro �, ne sont donn�s qu'� titre indicatif et peuvent, � ce titre,
 �tre modifi�s par Doc-Micro sans pr�avis.
 Doc-Micro se r�serve le droit de r�viser ses tarifs � tout moment. Les parties conviennent que Doc-Micro peut, de plein droit, modifier 
ses tarifs sans autre formalit� que d'en informer le client. Toute modification des conditions g�n�rales ou particuli�res ainsi que 
l'introduction de nouvelles formules, options ou prix fera l'objet d'une information en ligne sur le site www.Doc-Micro.fr, ou � toute
 autre adresse que Doc-Micro viendrait � lui substituer.</li>
<li>Le client est et reste enti�rement responsable du paiement de l'ensemble des sommes factur�es au titre du contrat pass� avec Doc-Micro,
 y compris dans le cas o� un tiers payeur intervient au nom et pour le compte du client, lequel devra dans tous les cas �tre pr�alablement agr��
 express�ment par Doc-Micro . Le d�faut total ou partiel de paiement 30 jours apr�s l'�ch�ance du terme de toute somme due au titre du contrat
 entra�nera de plein droit et sans mise en demeure pr�alable : </li>
<ul><li>L'exigibilit� imm�diate de toutes les sommes restant dues par le client au titre du contrat, quel que soit le mode de r�glement pr�vu.
<li>La suspension de toutes les prestations en cours, objet du contrat, sans pr�judice pour Doc-Micro d'user de la facult� de r�siliation du
 contrat stipul�e � l'article "R�siliation".</li>
</ul>
<hr></hr>
<p><div id="titre"> � MOYENS DE PAIEMENT</div></p>

<li> Le r�glement des prestations et services sera effectu� par esp�ces, ch�que � l�ordre de HELINCKX Michael ou pr�l�vement bancaire. </li>
<hr></hr>
<p><div id="titre"> � FACTURATION ET REGLEMENT</div></p>

<li> Le r�glement s�effectuera � signature du � Devis Doc-Micro � par les moyens de paiement convenus � l�article 9.</li>
<li>  Toute modification du cahier des charges intervenant apr�s le d�but d�ex�cution fera l�objet d�une facturation distincte. </li>
<hr></hr>
<p><div id="titre">� CONDITIONS FINANCIERES</div></p>

<li> Lors de l'acceptation des services, le CLIENT compl�tera et signera le devis, faisant office de bon de commande, �manant de Doc-Micro.
 Les modalit�s de paiement seront les suivantes :</li>
<ul><li>A la signature du devis : 30% du prix sp�cifi� sur le devis.
<li>A la mise en line d�finitive du site : le solde de la facture.</li>
</ul>
<li> En cas d'annulation des services du Webmaster par le client apr�s la signature et la r�ception du bon de commande et avant le d�marrage
 des travaux, un pourcentage d'un montant de 10% du montant total de la facture sera demand� � titre de dommages et int�r�ts et de compensation
 pour services rendus. En cas d'annulation des services du Webmaster par le client pendant la r�alisation des travaux, Doc-Micro se r�serve le 
droit de retenir une somme au prorata des travaux effectu�s.</li>
<hr></hr>
<p><div id="titre"> � DUREE DU CONTRAT ET ENTREE EN VIGEUR</div></p>

<li> Les pr�sentes conditions sp�cifiques de vente entrent en vigueur d�s l�acceptation du devis par Doc-Micro.</li>
<hr></hr>
<p><div id="titre"> � HEBERGEMENT DU SITE</div></p>

<li> Lorsque l�h�bergement du site est pr�vu au moment du devis, Doc-Micro fait appel � une soci�t� sp�cialis�e dans l'h�bergement de sites
 Internet pour placer le site du CLIENT. Doc-Micro ne peut en aucun cas fournir d'autres garanties que celles fournies par la soci�t� d'h�bergement.
 Par l'acceptation de ce contrat, le CLIENT accepte �galement le contrat de la soci�t� d'h�bergement choisie conjointement avec Doc-Micro.</li>
<hr></hr>
<p><div id="titre"> � NOMS DE DOMAINE ET ANTERIORITE</div></p>

<li> Doc-Micro enregistrera au nom du CLIENT tout nom de domaine dans l�extension choisie par le client dans les conditions d�finies entre les parties.
 Le CLIENT est donc propri�taire du nom de domaine. Il appartient toutefois au CLIENT d�effectuer toute recherche d�ant�riorit� utile afin 
d��viter tout conflit avec tout autre titulaire d�une marque ou d�un quelconque droit de propri�t� intellectuelle.</li>
<hr></hr>
<p><div id="titre"> � PROPRIETE DU SITE INTERNET</div></p>

<li> Le client est propri�taire de son site Internet � partir du moment o� le solde est r�gl�.
 Dans le cas contraire, Doc-Micro reste l'unique propri�taire du site d�velopp� jusqu'au paiement du solde. </li>
<hr></hr>
<p><div id="titre"> � PROPRIETE DU CONTENU DU SITE INTERNET</div></p>

<li> Le CLIENT est propri�taire des informations se trouvant sur son site (logo, pages HTML, fichiers images, sons ...),
 des bases de donn�es, fichier clients ou autres.</li>
<li> Le CLIENT s'engage express�ment � ne pas proposer sur son site des marchandises
 illicites ou interdites par la loi, � respecter la propri�t� intellectuelle des autres sites et plus g�n�ralement des �uvres de l'esprit,
 litt�raires, artistiques et autres ainsi que les droits d'auteurs et les propri�taires des marques. Il s'engage en outre � respecter les droits
 de la personnalit� et le respect de la personne humaine. </li>
<li> Le CLIENT d�clare �tre titulaire d'un droit de propri�t� ou d'un droit d'utilisation ou de licence des marques, brevets,
 logiciels utilis�s ou cit�s sur le site. </li>
<hr></hr>
<p><div id="titre">� CLAUSE DE DIVISIBILITE CONTRACTUELLE</div></p>

<li> Dans le cas o� l�une des clauses du pr�sent contrat �tait contraire � une loi d�ordre public nationale ou internationale,
 seule la clause en question sera annul�e, le contrat demeurant valable pour le surplus. Les parties n�gocieront de bonne foi la
 r�daction d�une nouvelle clause destin�e � remplacer celle qui �tait nulle.</li>
<hr></hr>
<p><div id="titre"> � FORCE MAJEURE</div></p>

<li> En cas de force majeure telle que d�finie par la jurisprudence des tribunaux fran�ais, rendant impossible l'ex�cution par
 l'une ou l'autre partie de ses obligations, les obligations respectives de Doc-Micro et du client seront dans un premier temps suspendues.
 Au cas o� la suspension exc�de un d�lai de deux mois, le contrat pourra �tre r�sili� de plein droit � l'initiative de l'une ou l'autre des parties.
 Doc-Micro et le client seront alors d�li�s de leurs engagements, sans qu'une quelconque indemnit� soit due de part et d'autre de ce fait.
 Doc-Micro ne sera pas tenu pour responsable pour tout retard ou inex�cution, lorsque la cause du retard ou de l'inex�cution serait due
 � la survenance d'un cas de force majeure habituellement reconnue par la jurisprudence. </li>
<hr></hr>
<p><div id="titre"> � RESILIATION</div></p>

<li> En dehors du cas pr�vu aux articles 8 et 18, la possibilit� de r�silier le contrat s�op�re :
En cas de non-respect par le CLIENT de ses obligations contractuelles souscrites au terme des pr�sentes, la r�siliation sera acquise 
un mois apr�s la date d�envoi d�une lettre recommand�e avec accus� de r�ception (sans attendre le terme �chu), sans qu�aucune demande 
de dommages et int�r�ts de la part de l�une ou de l�autre des parties ne puisse �tre form�e de ce chef. Les parties signataires renoncent
 � cet �gard express�ment � se r�clamer de quelconque dommages et int�r�ts suite � une telle r�siliation. </li>
<hr></hr>
<p><div id="titre">� RESPONSABILITES DE Doc-Micro</div> </p>

<li>Doc-Micro s'engage � mettre tous ses moyens en �uvre pour d�livrer dans des conditions optimales ses services de webmaster, formateur et assistant informatque au CLIENT.
 La responsabilit� de Doc-Micro envers le CLIENT ne pourrait �tre engag�e que pour des faits �tablis qui lui seraient exclusivement imputables.
 Du fait des caract�ristiques et limites de l'Internet, Doc-Micro ne saurait voir sa responsabilit� engag�e pour notamment : </li>
<ul><li> le contenu des informations transmises, diffus�es ou collect�es ainsi que tous fichiers, notamment les fichiers d'adresses,
 mais aussi le son, le texte, les images, les donn�es accessibles sur le site et ce � quelque titre que ce soit ;</li>
<li> les difficult�s d'acc�s au site h�berg� du fait du non-respect total ou partiel d'une obligation du client, d'une d�faillance
 et/ou d'une saturation � certaines p�riodes des op�rateurs des r�seaux de transport vers le monde Internet et en particulier de son ou ses fournisseurs d'acc�s ;</li>
<li> le non acheminement de courriers �lectroniques ou articles de forum de discussion ;</li>
<li> la contamination par virus des donn�es et/ou logiciels du client, dont la protection incombe � ce dernier ;</li>
<li> les intrusions malveillantes de tiers sur le site du client et/ou dans les bo�tes aux lettres �lectroniques du client ;</li>
<li> les dommages que pourraient subir les �quipements connect�s � la plate-forme d'h�bergement (terminaux du client)
 ou leur mauvaise utilisation, ceux-ci �tant sous l'enti�re responsabilit� du client ;</li>
<li> les d�tournements �ventuels de mots de passe, codes confidentiels, et plus g�n�ralement de toute information � caract�re sensible pour le client ;</li>
<li> les pr�judices indirects, c'est � dire tous ceux qui ne r�sultent pas directement et exclusivement de la d�faillance 
partielle ou totale des services fournis par Doc-Micro , tels que pr�judice commercial, perte de commandes, atteinte � l'image de marque,
 trouble commercial quelconque, perte de b�n�fices ou de clients.</li>
</ul>
<li>Doc-Micro se r�serve le droit d'interrompre temporairement l'accessibilit� � ses services pour des raisons de maintenance sans droit � indemnit�s.
 Cependant, Doc-Micro s'engage � mettre en �uvre tous les moyens dont il dispose pour minimiser ce type d'interruption.
Doc-Micro est non responsable de la perte de revenus due � une interruption ou une d�faillance de service.</li>
<hr></hr>
<p><div id="titre"> � CONFIDENTIALITE</div></p>

<li> Les parties conviennent de garder confidentiel les �l�ments du pr�sent contrat ainsi que les op�rations r�alis�es en application de ce dernier.</li>
<hr></hr>
<p><div id="titre"> � DROIT APPLICABLE ET JURIDICTION COMPETENTE</div></p>

<li> Les relations commerciales entre le CLIENT et Doc-Micro sont r�gies par la loi fran�aise.
 Les litiges �ventuels entre les parties seront r�gl�es � l�amiable ou � d�faut au Tribunal de Commerce du si�ge social de Doc-Micro.</li>
<hr></hr>
<p><div id="titre"> � ELECTION DE DOMICILE</div></p>

<li> Doc-Micro , �lit domicile � son si�ge social EI au 7 rue du Commandant POTHIER, 51200 Epernay.
 Toute correspondance concernant les prestations r�alis�es par Doc-Micro devra �tre envoy�e � l'adresse ci-dessus pour lui �tre opposable. </li>


</div>

</div>

<div id="vide"><div id="mention">
<li><a Href="../Mentions-legales/">Mentions-l�gales</a></li>
</div>


<div id="themesite">
Th�me :
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