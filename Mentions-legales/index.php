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
<title>Doc-Micro - Mentions-légales</title>
<META http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<META name="description" content="Mentions-légales." >
<META name="keywords" content="graphisme, création, refonte, e-commerce, site vitrine, vitrine, catalogue, site catalogue, carte de visite, carte, visite, webmaster, site internet, site, internet, facturation, logiciel de facturation, formation informatique, formation à domicile, assistance à domicile, assistance, web-designer, developpeur php, creation de site internet, assistance à distance, depannage, lille, roubaix, tourcoing, croix, metropole lilloise, edition facture,boutique en ligne, devis, facture, devis, devis batiment, facture batiment, facture artisant, devis artisant, devis / facture, doc, micro, docmicro, doc-micro, Helinckx, Helinckx Michael, Minkus">
<META name="robots" content="index, follow">
<META name="category" content="Mentions-Légales">
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
<li>Siège social : 7 rue du Commandant POTHIER, 51200 Epernay</li>
<li>Code APE : 6201Z</li>
<li> Directeur de la publication : HELINCKX Michael</li>
<li>Depuis le 01/11/2013</li>
<hr></hr>
<div id="titre"><STRONG>Hebergement : </STRONG></div></p>
<li>OVH</li>
<li>SAS au capital de 10 000 000 € </li>
<li>RCS Roubaix – Tourcoing 424 761 419 00045</li>
<li> Code APE 6202A</li>
<li> N° TVA : FR 22 424 761 419</li>
<li> Siège social : 2 rue Kellermann - 59100 Roubaix - France.</li>
<li> Directeur de la publication : Octave KLABA</li>

<hr></hr><hr></hr>
<p><STRONG>CONDITIONS GENERALES DE VENTE</STRONG>

<p><div id="titre">– OBJET</div></p>

<li> Les présentes Conditions Générales de Vente ont pour objet de définir les droits et les obligations 
des parties dans le cadre de la création / gestion de sites internet réalisée par Doc-Micro.</li>
<li> Le CLIENT reconnaît avoir pris connaissance, au moment de la passation de la commande, des conditions
 générales et particulières et déclare expressément les accepter sans réserve.</li>
<hr></hr>
<p><div id="titre"> – OBLIGATION DE Doc-Micro</div> </p>

<li> Doc-Micro s'engage à concevoir et mettre en ligne le site Internet.</li>
<li> Doc-Micro s'engage à mettre tout en œuvre pour assurer la permanence, la continuité et la qualité des services fournis au client.</li>
<li> Il est expressément spécifié que Doc-Micro n’a qu’une obligation de moyens (respecter le cahier des charges) 
et en aucun cas ne saurait être tenu d’une obligation de résultats. Doc-Micro ne saurait donc être tenu responsable
 d’une quelconque dégradation du site ayant un impact direct ou indirect sur les résultats du site ou l’image de la société,
 étant donné le caractère incontrôlable d’Internet.</li>
<li> En aucun cas, la responsabilité de Doc-Micro ne pourra être recherchée en cas de : </li>
<ul><li>Faute, négligence, omission, non-respect des conseils donnés ou défaillance du CLIENT.
<li>Faute, négligence ou omission d’un tiers sur lequel Doc-Micro n’a aucun pouvoir de contrôle de surveillance.</li>
<li>Force majeure, événement ou incident indépendant de la volonté de Doc-Micro.</li>
<li>Doc-Micro ne pourra être tenu responsable envers le CLIENT des conséquences de l'introduction d'un virus informatique 
dans le serveur web ou dans le site ayant un effet sur son bon fonctionnement ou dans le réseau interne de Doc-Micro,
 de la migration du site dans un environnement matériel ou logiciel différent, des modifications apportées aux composants logiciels
 par Doc-Micro ou par une personne autre que Doc-Micro , d'une baisse du chiffre d'affaires consécutive au fonctionnement ou à l'absence de fonctionnement,
 ou à l'utilisation ou à l'absence d'utilisation du site ou des informations s'y trouvant ou devant s'y trouver,
 d'intrusion illégale ou non autorisée de tout tiers dans le serveur web ou dans le site, d'un encombrement temporaire de la bande passante,
 d'une interruption du service de connexion à Internet pour une cause hors de contrôle de Doc-Micro.</li>
</ul>
<hr></hr>
<p><div id="titre"> – OBLIGATION DU CLIENT</div></p>

<li> Le CLIENT reconnaît avoir reçu de Doc-Micro toutes les informations et conseils qui lui étaient nécessaires
 pour souscrire au présent engagement en connaissance de cause. Ainsi, les choix effectués par le CLIENT lors de la
 commande ainsi qu'éventuellement par la suite, demeurent sous son entière responsabilité. Le CLIENT s'engage à fournir
 à Doc-Micro tous les documents, renseignements et informations afin de lui permettre de réaliser le site conformément
 aux besoins et souhaits du client.</li>
<li> En application des dispositions légales et notamment de la loi du 30 septembre 1986 modifiée, le CLIENT
 est civilement et pénalement responsable du contenu de son site, des informations transmises, diffusées et/ou collectées,
 de leur exploitation, des liens hypertextes, des revendications de tiers et actions pénales qu'elles suscitent, notamment
 en matière de propriété intellectuelle, de droits de la personnalité et de protection des mineurs. Le CLIENT s'engage à 
respecter les lois et règlements en vigueur dont notamment les règles ayant trait au fonctionnement des services en ligne,
 au commerce électronique, aux droits d'auteur, aux bonnes mœurs et à l'ordre public ainsi que les principes universels d'usage 
de l'Internet, communément appelés "Netiquette". </li>
<li> Le CLIENT s'engage à respecter les dispositions relatives aux mentions légales obligatoires à insérer sur son site web
 en vertu de la loi du 30 septembre 1986 modifiée et celles relatives à l'informatique, aux fichiers et aux libertés, en particulier
 celles relatives aux déclarations des traitements automatisés d'informations nominatives auprès de la Commission Nationale de l'Informatique
 et des Libertés (C.N.I.L.).</li>
<li> Payer le prix convenu au présent contrat à la signature du devis, selon les modalités de paiement définies aux articles 10 et 11.</li>

<hr></hr>
<p><div id="titre"> – SIGNATURE DU SITE</div></p>

<li> Les coordonnées de Doc-Micro figureront en « signature sur le site » mais celles-ci peuvent être retirées à tout moment
 à la demande de Doc-Micro . Notamment, le CLIENT qui souhaite faire modifier le site par un tiers devra en informer Doc-Micro
 afin que celui-ci puisse décider s’il souhaite ou non laisser ses coordonnées sur le site modifié. Exemple de signature en pied
 de page « Site réalisé par la société Doc-Micro », avec les URL de redirections. </li>
<hr></hr>
<p><div id="titre"> – MODIFICATION PAR UN TIERS OU LE CLIENT</div></p>

<li> Le CLIENT sera libre d’exploiter son site comme il l’entend, ou de le faire modifier soit par Doc-Micro soit par un tiers
 ou lui-même. Il a expressément été convenu qu’en cas de modification par un tiers ou par le CLIENT, Doc-Micro signataire des présentes
 devra donner son accord pour que ses coordonnées demeurent sur le site, et ne sera plus responsable de son bon fonctionnement. </li>
<hr></hr>
<p><div id="titre"> - COMMANDE</div></p>

<li>  Toute commande passée par le client à Doc-Micro est formalisée par la production d’un devis reprenant les caractéristiques 
de la prestation demandée, remis en main propre ou envoyé par courrier ou e-mail. La commande devient effective dès lors que
 Doc-Micro a reçu le devis daté, signé par le client et accompagné du montant des ahres prévu.</li>
<li>  Le délai de validité du devis ainsi que le montant des ahres demandé sont stipulés dans le devis.</li>
<li>  Les commandes ne sont plus susceptibles de modification ou même d’annulation après acceptation par Doc-Micro du devis signé
 par le client.</li>
<li>  Les suppléments éventuellement commandés à une période ultérieure doivent faire l'objet d'un nouveau devis.</li>

<hr></hr>
<p><div id="titre"> – PRIX</div></p>

<li>  Le « Devis Doc-Micro » est valable pour une durée d'un mois, à compter de sa date de réalisation. Le montant total 
de la prestation figure sur le « Devis Doc-Micro ». Tous les prix s'entendent en euros toutes taxes comprise.</li>
<li>  Les renseignements présents sur le site Internet « Doc-Micro », ne sont donnés qu'à titre indicatif et peuvent, à ce titre,
 être modifiés par Doc-Micro sans préavis.
 Doc-Micro se réserve le droit de réviser ses tarifs à tout moment. Les parties conviennent que Doc-Micro peut, de plein droit, modifier 
ses tarifs sans autre formalité que d'en informer le client. Toute modification des conditions générales ou particulières ainsi que 
l'introduction de nouvelles formules, options ou prix fera l'objet d'une information en ligne sur le site www.Doc-Micro.fr, ou à toute
 autre adresse que Doc-Micro viendrait à lui substituer.</li>
<li>Le client est et reste entièrement responsable du paiement de l'ensemble des sommes facturées au titre du contrat passé avec Doc-Micro,
 y compris dans le cas où un tiers payeur intervient au nom et pour le compte du client, lequel devra dans tous les cas être préalablement agréé
 expressément par Doc-Micro . Le défaut total ou partiel de paiement 30 jours après l'échéance du terme de toute somme due au titre du contrat
 entraînera de plein droit et sans mise en demeure préalable : </li>
<ul><li>L'exigibilité immédiate de toutes les sommes restant dues par le client au titre du contrat, quel que soit le mode de règlement prévu.
<li>La suspension de toutes les prestations en cours, objet du contrat, sans préjudice pour Doc-Micro d'user de la faculté de résiliation du
 contrat stipulée à l'article "Résiliation".</li>
</ul>
<hr></hr>
<p><div id="titre"> – MOYENS DE PAIEMENT</div></p>

<li> Le règlement des prestations et services sera effectué par espèces, chèque à l’ordre de HELINCKX Michael ou prélèvement bancaire. </li>
<hr></hr>
<p><div id="titre"> – FACTURATION ET REGLEMENT</div></p>

<li> Le règlement s’effectuera à signature du « Devis Doc-Micro » par les moyens de paiement convenus à l’article 9.</li>
<li>  Toute modification du cahier des charges intervenant après le début d’exécution fera l’objet d’une facturation distincte. </li>
<hr></hr>
<p><div id="titre">– CONDITIONS FINANCIERES</div></p>

<li> Lors de l'acceptation des services, le CLIENT complètera et signera le devis, faisant office de bon de commande, émanant de Doc-Micro.
 Les modalités de paiement seront les suivantes :</li>
<ul><li>A la signature du devis : 30% du prix spécifié sur le devis.
<li>A la mise en line définitive du site : le solde de la facture.</li>
</ul>
<li> En cas d'annulation des services du Webmaster par le client après la signature et la réception du bon de commande et avant le démarrage
 des travaux, un pourcentage d'un montant de 10% du montant total de la facture sera demandé à titre de dommages et intérêts et de compensation
 pour services rendus. En cas d'annulation des services du Webmaster par le client pendant la réalisation des travaux, Doc-Micro se réserve le 
droit de retenir une somme au prorata des travaux effectués.</li>
<hr></hr>
<p><div id="titre"> – DUREE DU CONTRAT ET ENTREE EN VIGEUR</div></p>

<li> Les présentes conditions spécifiques de vente entrent en vigueur dès l’acceptation du devis par Doc-Micro.</li>
<hr></hr>
<p><div id="titre"> – HEBERGEMENT DU SITE</div></p>

<li> Lorsque l’hébergement du site est prévu au moment du devis, Doc-Micro fait appel à une société spécialisée dans l'hébergement de sites
 Internet pour placer le site du CLIENT. Doc-Micro ne peut en aucun cas fournir d'autres garanties que celles fournies par la société d'hébergement.
 Par l'acceptation de ce contrat, le CLIENT accepte également le contrat de la société d'hébergement choisie conjointement avec Doc-Micro.</li>
<hr></hr>
<p><div id="titre"> – NOMS DE DOMAINE ET ANTERIORITE</div></p>

<li> Doc-Micro enregistrera au nom du CLIENT tout nom de domaine dans l’extension choisie par le client dans les conditions définies entre les parties.
 Le CLIENT est donc propriétaire du nom de domaine. Il appartient toutefois au CLIENT d’effectuer toute recherche d’antériorité utile afin 
d’éviter tout conflit avec tout autre titulaire d’une marque ou d’un quelconque droit de propriété intellectuelle.</li>
<hr></hr>
<p><div id="titre"> – PROPRIETE DU SITE INTERNET</div></p>

<li> Le client est propriétaire de son site Internet à partir du moment où le solde est réglé.
 Dans le cas contraire, Doc-Micro reste l'unique propriétaire du site développé jusqu'au paiement du solde. </li>
<hr></hr>
<p><div id="titre"> – PROPRIETE DU CONTENU DU SITE INTERNET</div></p>

<li> Le CLIENT est propriétaire des informations se trouvant sur son site (logo, pages HTML, fichiers images, sons ...),
 des bases de données, fichier clients ou autres.</li>
<li> Le CLIENT s'engage expressément à ne pas proposer sur son site des marchandises
 illicites ou interdites par la loi, à respecter la propriété intellectuelle des autres sites et plus généralement des œuvres de l'esprit,
 littéraires, artistiques et autres ainsi que les droits d'auteurs et les propriétaires des marques. Il s'engage en outre à respecter les droits
 de la personnalité et le respect de la personne humaine. </li>
<li> Le CLIENT déclare être titulaire d'un droit de propriété ou d'un droit d'utilisation ou de licence des marques, brevets,
 logiciels utilisés ou cités sur le site. </li>
<hr></hr>
<p><div id="titre">– CLAUSE DE DIVISIBILITE CONTRACTUELLE</div></p>

<li> Dans le cas où l’une des clauses du présent contrat était contraire à une loi d’ordre public nationale ou internationale,
 seule la clause en question sera annulée, le contrat demeurant valable pour le surplus. Les parties négocieront de bonne foi la
 rédaction d’une nouvelle clause destinée à remplacer celle qui était nulle.</li>
<hr></hr>
<p><div id="titre"> – FORCE MAJEURE</div></p>

<li> En cas de force majeure telle que définie par la jurisprudence des tribunaux français, rendant impossible l'exécution par
 l'une ou l'autre partie de ses obligations, les obligations respectives de Doc-Micro et du client seront dans un premier temps suspendues.
 Au cas où la suspension excède un délai de deux mois, le contrat pourra être résilié de plein droit à l'initiative de l'une ou l'autre des parties.
 Doc-Micro et le client seront alors déliés de leurs engagements, sans qu'une quelconque indemnité soit due de part et d'autre de ce fait.
 Doc-Micro ne sera pas tenu pour responsable pour tout retard ou inexécution, lorsque la cause du retard ou de l'inexécution serait due
 à la survenance d'un cas de force majeure habituellement reconnue par la jurisprudence. </li>
<hr></hr>
<p><div id="titre"> – RESILIATION</div></p>

<li> En dehors du cas prévu aux articles 8 et 18, la possibilité de résilier le contrat s’opère :
En cas de non-respect par le CLIENT de ses obligations contractuelles souscrites au terme des présentes, la résiliation sera acquise 
un mois après la date d’envoi d’une lettre recommandée avec accusé de réception (sans attendre le terme échu), sans qu’aucune demande 
de dommages et intérêts de la part de l’une ou de l’autre des parties ne puisse être formée de ce chef. Les parties signataires renoncent
 à cet égard expressément à se réclamer de quelconque dommages et intérêts suite à une telle résiliation. </li>
<hr></hr>
<p><div id="titre">– RESPONSABILITES DE Doc-Micro</div> </p>

<li>Doc-Micro s'engage à mettre tous ses moyens en œuvre pour délivrer dans des conditions optimales ses services de webmaster, formateur et assistant informatque au CLIENT.
 La responsabilité de Doc-Micro envers le CLIENT ne pourrait être engagée que pour des faits établis qui lui seraient exclusivement imputables.
 Du fait des caractéristiques et limites de l'Internet, Doc-Micro ne saurait voir sa responsabilité engagée pour notamment : </li>
<ul><li> le contenu des informations transmises, diffusées ou collectées ainsi que tous fichiers, notamment les fichiers d'adresses,
 mais aussi le son, le texte, les images, les données accessibles sur le site et ce à quelque titre que ce soit ;</li>
<li> les difficultés d'accès au site hébergé du fait du non-respect total ou partiel d'une obligation du client, d'une défaillance
 et/ou d'une saturation à certaines périodes des opérateurs des réseaux de transport vers le monde Internet et en particulier de son ou ses fournisseurs d'accès ;</li>
<li> le non acheminement de courriers électroniques ou articles de forum de discussion ;</li>
<li> la contamination par virus des données et/ou logiciels du client, dont la protection incombe à ce dernier ;</li>
<li> les intrusions malveillantes de tiers sur le site du client et/ou dans les boîtes aux lettres électroniques du client ;</li>
<li> les dommages que pourraient subir les équipements connectés à la plate-forme d'hébergement (terminaux du client)
 ou leur mauvaise utilisation, ceux-ci étant sous l'entière responsabilité du client ;</li>
<li> les détournements éventuels de mots de passe, codes confidentiels, et plus généralement de toute information à caractère sensible pour le client ;</li>
<li> les préjudices indirects, c'est à dire tous ceux qui ne résultent pas directement et exclusivement de la défaillance 
partielle ou totale des services fournis par Doc-Micro , tels que préjudice commercial, perte de commandes, atteinte à l'image de marque,
 trouble commercial quelconque, perte de bénéfices ou de clients.</li>
</ul>
<li>Doc-Micro se réserve le droit d'interrompre temporairement l'accessibilité à ses services pour des raisons de maintenance sans droit à indemnités.
 Cependant, Doc-Micro s'engage à mettre en œuvre tous les moyens dont il dispose pour minimiser ce type d'interruption.
Doc-Micro est non responsable de la perte de revenus due à une interruption ou une défaillance de service.</li>
<hr></hr>
<p><div id="titre"> – CONFIDENTIALITE</div></p>

<li> Les parties conviennent de garder confidentiel les éléments du présent contrat ainsi que les opérations réalisées en application de ce dernier.</li>
<hr></hr>
<p><div id="titre"> – DROIT APPLICABLE ET JURIDICTION COMPETENTE</div></p>

<li> Les relations commerciales entre le CLIENT et Doc-Micro sont régies par la loi française.
 Les litiges éventuels entre les parties seront réglées à l’amiable ou à défaut au Tribunal de Commerce du siège social de Doc-Micro.</li>
<hr></hr>
<p><div id="titre"> – ELECTION DE DOMICILE</div></p>

<li> Doc-Micro , élit domicile à son siège social EI au 7 rue du Commandant POTHIER, 51200 Epernay.
 Toute correspondance concernant les prestations réalisées par Doc-Micro devra être envoyée à l'adresse ci-dessus pour lui être opposable. </li>


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