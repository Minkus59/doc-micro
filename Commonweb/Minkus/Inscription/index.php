<?php 
// Redirection $_Session
require ('/homez.764/docmicro/www/Commonweb/Minkus/lib/script/redirect1.inc.php');

// Récupération des paramètres POST
$Pseudo=$_POST['pseudo'];
$Mdp=$_POST['mdp'];
$Mdpre=$_POST['mdpre'];
$Email=$_POST['email'];
$Jour=$_POST['jour'];
$Mois=$_POST['mois'];
$Annee=$_POST['annee'];
$Genre=$_POST['genre'];
$Ip=$_SERVER['REMOTE_ADDR']; 

// Une fois le formulaire envoyé
if(isset($_POST["valider"])) {

// fonction Taille des entrer
require ('/homez.764/docmicro/www/Commonweb/Minkus/lib/script/fonction_taille.inc.php');
	
// Creation cle unique md5
$hash = md5(uniqid(rand(), true));

//VERIFFFF -----------------------------------------------------------------------------------------

if ($_POST['pseudo'] == ''){
	echo 'Le champ "pseudo" est vide !<br />';
	echo '<br/><input type="button" value="Retour" onclick="javascript:history.back()"><br/>';}

elseif (!taille_champ('pseudo',8,25)){
	echo'Le pseudo doit contenir entre 8 et 25 caractères !<br />';
	echo '<br/><input type="button" value="Retour" onclick="javascript:history.back()"><br/>';}

elseif (!preg_match("#^[A-Z][a-z-._]+[0-9]+$#", $_POST['pseudo'])){ 
	echo 'Le pseudo doit commencer par une majuscule et doit comporter des chiffres !<br />';
	echo 'Ils ne doit pas contenir de caractères speciaux !<br />';
	echo '<br/>Veuillez le modifier<br />';
	echo '<br/><input type="button" value="Retour" onclick="javascript:history.back()"><br/>';}

elseif ($_POST['mdp'] == ''){
	echo 'Le champ "Mot de passe" est vide !<br />';
	echo '<br/><input type="button" value="Retour" onclick="javascript:history.back()"><br/>';}
		
elseif (!taille_champ('mdp',8,25)){
	echo'Le mot de passe doit contenir entre 8 et 25 caractères !<br />';
	echo '<br/><input type="button" value="Retour" onclick="javascript:history.back()"><br/>';}

elseif ($_POST['mdp'] == $_POST['pseudo']){
	echo 'Le mot de passe doit etre different du pseudo !<br />';
	echo '<br/><input type="button" value="Retour" onclick="javascript:history.back()"><br/>';}

elseif  ($_POST['mdp']!=($_POST['mdpre'])) {
	echo 'Les champs "Mot de passe" et "Retapez votre mot de passe" ne sont pas identique, veuillez corriger.<br />';
	echo '<br/><input type="button" value="Retour" onclick="javascript:history.back()"><br/>';}
		
elseif ($_POST['mdpre'] == ''){
	echo 'Le champ "Retapez le mot de passe" est vide !<br />';
	echo '<br/><input type="button" value="Retour" onclick="javascript:history.back()"><br/>';}

elseif ($_POST['email'] == ''){
	echo 'Le champ "Email" est vide !<br />';
	echo '<br/><input type="button" value="Retour" onclick="javascript:history.back()"><br/>';}
		
elseif (!preg_match("#^[A-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $_POST['email'])){ 
	echo "<br/>L'adresse email n'est pas valide";
	echo '<br/><input type="button" value="Retour" onclick="javascript:history.back()"><br/>';}
 		
elseif ($_POST['jour'] == ''){
	echo 'Le champ "Jour" est vide !<br />';
	echo '<br/><input type="button" value="Retour" onclick="javascript:history.back()"><br/>';}
		
elseif ($_POST['mois'] == ''){
	echo 'Le champ "Mois" est vide !<br />';
	echo '<br/><input type="button" value="Retour" onclick="javascript:history.back()"><br/>';}

elseif ($_POST['annee'] == ''){
	echo 'Le champ "Année" est vide !<br />';
	echo '<br/><input type="button" value="Retour" onclick="javascript:history.back()"><br/>';}
		
elseif(!taille_champ('annee',0,4)){
	echo "L'année doit contenir au moin 4 caractères !<br />";
	echo '<br/><input type="button" value="Retour" onclick="javascript:history.back()"><br/>';}

elseif (!preg_match("#^[1-2][0-08-9][0-9][0-9]$#", $_POST['annee'])){ 
 	echo "L'année de naissance n'est pas correct<br />";
	echo '<br/><input type="button" value="Retour" onclick="javascript:history.back()"><br/>';}
		
elseif ($_POST['genre'] == ''){
	echo 'Le champ "Genre" est vide !<br />';
	echo '<br/><input type="button" value="Retour" onclick="javascript:history.back()"><br/>';}

		else {

				//Cnx a la base ---------------------------------------------------------------------------
				include ('/homez.764/docmicro/www/Commonweb/Minkus/impinfbdd/ovh.inc.php');
				@mysql_pconnect($BD_serveur, $BD_utilisateur, $BD_mdp) or die("Impossible de se connecter au serveur de bases de données.");			
				@mysql_select_db($BD_base) or die("Impossible de se connecter à la base de données.");

				//  VERIF DOUBLON ---------------------------------------------------------------------------------------

				$verif_pseudo=mysql_fetch_array(mysql_query("SELECT COUNT(*) AS verif_exist FROM commonweb_user WHERE pseudo='$Pseudo'"));			
				$verif_email=mysql_fetch_array(mysql_query("SELECT COUNT(*) AS verif_exist FROM commonweb_user WHERE email='$Email'"));
			
					if($verif_pseudo['verif_exist']) {
						echo "Ce Pseudo existe déja, veuillez en choisir un autre !<br/>";
						echo '<br/><input type="button" value="Retour" onclick="javascript:history.back()"><br/>';			
						mysql_close(); }
			
					elseif ($verif_email['verif_exist']) {			
						echo "Cette adresse email existe déja, veuillez en choisir une autre !<br/>";			
						echo '<br/><input type="button" value="Retour" onclick="javascript:history.back()"><br/>';			
						mysql_close(); }
			
							else {

								// declaration du mail
								
								$headers1 ='From: "no-reply@commonweb.com"<perso@doc-micro.fr>'."\n"; 						
								$headers1 .='Content-Type: text/html; charset="iso-8859-1"'."\n"; 						
								$headers1 .='Content-Transfer-Encoding: 8bit'; 																		
								$message1 ="<html><head><title>Validation d'inscription</title></head>						
											<body><p>Validation d'inscription</p>					
											<p>Veuillez cliquer sur le lien suivant pour valider votre inscription.</p>						
											<p><a href='http://www.doc-micro.fr/Commonweb/Minkus/Validation/validation_mail.php?id=$hash&pseudo=$Pseudo&email=$Email&jour=$Jour&mois=$Mois&annee=$Annee&genre=$Genre&hote=$Ip'>Cliquez ici</a><p>						
											</body></html>" ; 						

								// Envoi mail confirmation --------------------------------------------------------------------------
							
								if (!mail($_POST['email'], "Validation d'inscription", $message1, $headers1)) { 							
									echo "L'email de confirmation n'a pu etre envoyé, vérifiez que vous l'avez entré correctement !";							
									echo '<br/><input type="button" value="Retour" onclick="javascript:history.back()"><br/>';								
									mysql_close(); }
							
								// Insertion des donnees ----------------------------------------------------------------------------

										else {
																				  									
											$requete1 = 'INSERT INTO commonweb_user (hash, pseudo, mdp, jour, mois, annee, genre, email, created)									
											VALUES ("'.$hash.'", "'.$Pseudo.'", "'.(md5($_POST['mdp'])).'", "'.$Jour.'", "'.$Mois.'", "'.$Annee.'", "'.$Genre.'", "'.$Email.'", NOW())';
									
											$requete2 = 'INSERT INTO commonweb_securite (hote, pseudo_id)									
											VALUES ("'.$Ip.'", "'.$Pseudo.'")';  
											
											$requete3 = 'INSERT INTO commonweb_amis (pseudo_amis, created, groupe, accept, pseudo_id)									
											VALUES ("'.$Pseudo.'", NOW(), "'.$Pseudo.'", "1", "'.$Pseudo.'")'; 
																				
											$result1 = @mysql_query($requete1);									
											$result2 = @mysql_query($requete2);
																												
											if (!$result1){
												echo "<br/>L'enregistrement de vos données a échoué.<br/>";											
												echo "<br/>Veuillez reessayer ulterieurement.<br/>";									
												echo '<br/><input type="button" value="Retour" onclick="javascript:history.back()"><br/>';									
												mysql_close();	}
									
											elseif (!$result2){									
												//$requete3="DELETE FROM commonweb_user WHERE pseudo='$Pseudo'";
												$result3 = @mysql_query($requete3);												
												echo "Echec de l'enregistrement de l'hote.<br />";										
												echo "Désactivez votre proxy et réessayez a nouveau.<br />";
												echo '<br/><input type="button" value="Retour" onclick="javascript:history.back()"><br/>';										
												mysql_close(); }
								
													else {
															echo "Bonjour ".$Pseudo.",<br />";													
															echo "Merci de vous etres inscrit sur www.commonweb.com.<br />";													
															echo "Un email de confirmation vous a été envoyer a l'adresse suivante : ".$Email.".<br />";													
															echo '<br/><input type="button" value="Fermer" onclick="self.close()"><br />';													
															mysql_close();

					}
				}
			}
		}
	}
 ?>

<html>
<head>
<title>Document sans titre</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../lib/css/commonweb.css" rel="stylesheet" type="text/css"/>
</head>

<body onselectstart="return false" oncontextmenu="return false" ondragstart="return false" onMouseOver="window.status='..message perso .. '; return true;" > 
<center>
  <div id="cadre_font"> 
<div id="cadre_fermer"><div id="interrieur_titre">Fermer</div><div id="interrieur">
Inscription Fermé desolé
<p><a href="/Commonweb/Minkus">Connexion</a></p>
</div></div>

<div id="cadre_pub"><div id="interrieur_titre">Publicité</div><div id="interrieur">BLABLABLABLABLABLABLABLABLABLABLABLABLABL ABLABLABLABLABLABLABLABLABLABLABLA BLABLABLABLABLABLABLABLABLAB LABLABLABLABLABLABLABLABLABLA BLABLABLABLABLABLABLABLABLABLAB LABLABLABLABLABLA</div></div>
<div id="cadre_inscription"><div id="interrieur_titre">Inscription</div><div id="interrieur">
<TABLE BORDER="0"><form action="" method='post'>
<TR><TD> Pseudo<strong><font color="#FF0000" size="1">*</font></strong> : </TD><TD><input type='text' name='pseudo' minlengtg='5'/></TD></TR>
	<TR><TD> Mot de passe<strong><font color="#FF0000" size="1">*</font></strong> : </TD><TD> <input type='password' name='mdp'/> </TD></TR><TR>
    <TD> Retapez le mot de passe<strong><font color="#FF0000" size="1">* </font></strong>: </TD><TD> <input type='password' name="mdpre"/> </TD></TR><TR>
    <TD> Adresse email<strong><font color="#FF0000" size="1">*</font></strong> : </TD><TD> <input type='text' name='email' /> </TD></TR><TR>
    <TD> Date de naissance<strong><font color="#FF0000" size="1">*</font></strong> : </TD>
	<TD> <select name="jour">
  	<option value="">--</option>
    <option value="1">1</option>
    <option value="2">2</option>
    <option value="3">3</option>
    <option value="4">4</option>
    <option value="5">5</option>
    <option value="6">6</option>
    <option value="7">7</option>
    <option value="8">8</option>
    <option value="9">9</option>
    <option value="10">10</option>
    <option value="11">11</option>
    <option value="12">12</option>
    <option value="13">13</option>
    <option value="14">14</option>
    <option value="15">15</option>
    <option value="16">16</option>
    <option value="17">17</option>
    <option value="18">18</option>
    <option value="19">19</option>
    <option value="20">20</option>
    <option value="21">21</option>
    <option value="22">22</option>
    <option value="23">23</option>
    <option value="24">24</option>
    <option value="25">25</option>
    <option value="26">26</option>
    <option value="27">27</option>
    <option value="28">28</option>
    <option value="29">29</option>
    <option value="30">30</option>
    <option value="31">31</option>
  </select>
  / 
  <select name="mois">
  	<option value="">Choisissez</option>
    <option value="janvier">Janvier</option>
    <option value="fevrier">Fevrier</option>
    <option value="mars">Mars</option>
    <option value="avril">Avril</option>
    <option value="mai">Mai</option>
    <option value="juin">Juin</option>
    <option value="juillet">Juillet</option>
    <option value="aout">Aout</option>
    <option value="septembre">Septembre</option>
    <option value="octobre">Octobre</option>
    <option value="novembre">Novembre</option>
    <option value="decembre">Decembre</option>
  </select>
  / 
  <input type='text' name="annee" maxlength="4" size="4">   
  </select> </TD></TR><TR>
    <TD> Genre<strong><font color="#FF0000" size="1">*</font></strong> : </TD>
	<TD> <select name="genre">
  		<option value="">--</option>
		<option value="Homme">Homme</option>
		<option value="Femme">Femme</option>
		</select> </TD></TR>
<TR><TD><input type='reset' name='effacer' value="Effacer"/></TD><TD><input type='submit' name='valider' value="S'inscrire"/></TD></TR></form>
</TABLE> 
<p> <font color="#FF0000" size="1">* Renseignement essentiel a l'inscription</font></p>
<p><font color="#FF0000" size="1">- Le Pseudo doit commencé par une majuscule 
  et doit se terminé par des chiffres, il doit contenir entre 8 et 16 caractères 
  mais aucun caractères speciaux.</font></p>
<p><font color="#FF0000" size="1">- Le mot de passe doit contenir entre 8 et 25 
  caractères et doit etre different du pseudo.</font></p>
</div></div>
</div>
</center>
</body>
</html>