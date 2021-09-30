<?php
// Redirection $_Session
//require ('/homez.764/docmicro/www/Commonweb/Minkus/lib/script/redirect1.inc.php');

// on teste si le visiteur a soumis le formulaire de connexion
if (isset($_POST['connexion']) && $_POST['connexion'] == 'Connexion') {
	if ((isset($_POST['pseudo']) && !empty($_POST['pseudo'])) && (isset($_POST['mdp']) && !empty($_POST['mdp']))) {
	
// Récupération des paramètres POST

$Pseudo=$_POST['pseudo'];
$Ip=$_SERVER['REMOTE_ADDR']; 
$Mdp=$_POST['mdp'];
$Mdpre=$_POST['mdpre'];
$Email=$_POST['email'];
$Jour=$_POST['jour'];
$Mois=$_POST['mois'];
$Annee=$_POST['annee'];
$Genre=$_POST['genre'];

// Connexion à la base de données
include ('/homez.764/docmicro/www/Commonweb/Minkus/impinfbdd/ovh.inc.php');
			   
@mysql_pconnect($BD_serveur, $BD_utilisateur, $BD_mdp)or die("Impossible de se connecter au serveur de bases de données.");
@mysql_select_db($BD_base)or die("Impossible de se connecter à la base de données.");

//CHerche le pseudo 

$pseudo_exist = @mysql_fetch_array(@mysql_query('SELECT COUNT(*) AS verif_exist FROM commonweb_user WHERE pseudo = "'.$Pseudo.'"'));

if ($pseudo_exist['verif_exist']) {
	$created= @mysql_fetch_array(@mysql_query('SELECT created FROM commonweb_user WHERE pseudo="'.$Pseudo.'"'));
	$salt=md5($created['created']);
	$mdp_crypt=crypt($Mdp, $salt);
		
// on teste si une entrée de la base contient ce couple login / pass
$data3 = @mysql_fetch_array(@mysql_query('SELECT * FROM commonweb_user WHERE pseudo = "'.$Pseudo.'" AND mdp ="'.$mdp_crypt.'"'));
$data2 = @mysql_fetch_array(@mysql_query('SELECT COUNT(*) AS verif_exist FROM commonweb_user WHERE pseudo = "'.$Pseudo.'" AND mdp ="'.$mdp_crypt.'"'));

// si on obtient une réponse, alors l'utilisateur est un membre
if ($data2['verif_exist']) {

		//  VERIF hote ---------------------------------------------------------------------------------------
		$verif_hote=@mysql_fetch_array(@mysql_query("SELECT hote AS verif_exist FROM commonweb_securite WHERE hote='".$Ip."' AND pseudo_id='".$data3['pseudo']."'"));

		// Verif formatage du pseudo
		if (!preg_match("#^[A-Z][a-z-._]+[0-9]+$#", $Pseudo)){ 
			echo 'Le pseudo doit commencer par une majuscule et doit comporter des chiffres !<br />';
			echo 'Ils ne doit pas contenir de caractères speciaux !<br />';
			echo '<br/>Veuillez le modifier<br />';
			echo '<br/><input type="button" value="Retour" onclick="javascript:history.back()"><br/>';
			}
		
		// VERIF Mail
		elseif ($data3['email_validation']==0) {
			echo "Votre compte n'a pas ete activé<br />"; 
			echo "<br/>veuillez valider votre compte en cliquant sur le lien reçu par email<br />";
			echo "vous pouvais toujour revevoir le mail a nouveau en cliquant sur recevoir";
			 ?><form action='/homez.764/docmicro/www/Commonweb/Minkus/Validation/renvoi_mail.php' method='post'/>
			 <input type='hidden' name="pseudo" value="<?php echo $_POST['pseudo']; ?>"/>
			 <input type='submit' name='recevoir' value="Recevoir"/>
			 </form><?php 
			}
			
		// VERIF hote				
		elseif(!$verif_hote['verif_exist']) { 
			echo "<br/>Veullez identifier cette nouvelle adresse !<br />";
			?><form action='/Commonweb/Minkus/Securite/envoi_hote.php' method='post'/>
			<input type='hidden' name="pseudo" value="<?php echo $data3['pseudo']; ?>"/>
			<input type='hidden' name="hash" value="<?php echo $data3['hash']; ?>"/>
			<input type='submit' name='ok' value="Ok"/>
			</form><?php 
			}
			
				else {
				@mysql_query("UPDATE commonweb_user SET derniere_cnx = NOW() WHERE pseudo = '".$Pseudo."'");
				mysql_close();
				//	setcookie('Commonweb_cnx', $Pseudo, (time() + 60));
				session_start();
				$_SESSION['pseudo'] = $data3['pseudo'];
				$_SESSION['hash'] = $data3['hash'];
				header('Location: http://www.doc-micro.fr/Commonweb/Minkus/Accueil/');
				}
			}
		else { $erreur = 'Compte non reconnu.'; }
		}
	else { $erreur = 'Compte non reconnu.'; }
	}
else { $erreur = 'Au moins un des champs est vide.'; }
}

//------------------------------------------------------------------------------------------------------------------------------------------------
// PARTIE INSCRIPTION ----------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------------------------
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
								
								$headers1 ='From: "no-reply@commonweb.com"<contact@doc-micro.fr>'."\n"; 						
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
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta name="author" content="">
<meta name="description" content="">
<meta name="keywords" content="">
<title>Connexion</title>
<link href="lib/css/commonweb.css" rel="stylesheet" type="text/css"/>
</head>

<body>
<center>
  <div id="cadre_font"> 
<div id="cadre_pub"><div id="interrieur_titre">Publicité</div><div id="interrieur">BLABLABLABLABLABLABLABLABLABLABLABLABLABL ABLABLABLABLABLABLABLABLABLABLABLA BLABLABLABLABLABLABLABLABLAB LABLABLABLABLABLABLABLABLABLA BLABLABLABLABLABLABLABLABLABLAB LABLABLABLABLABLA</div></div>
<div id="cadre_post"><div id="interrieur_titre">Connexion</div><div id="interrieur">
<?php
if (isset($erreur)) echo $erreur.'<br />'.$erreur2.'<br />';

?>
<TABLE BORDER="0"><form action='' method='post'>
<TR><TD> Pseudo<strong><font color="#FF0000" size="1">*</font></strong> : </TD><TD> <input type='text' name='pseudo' minlengtg='5'/> </TD></TR>
<TR><TD> Mot de passe<strong><font color="#FF0000" size="1">*</font></strong> :</TD><TD> <input type='password' name='mdp'/> </TD></TR>
<TR><TD><input type='reset' name='effacer' value="Effacer"/></TD><TD><input type='submit' name='connexion' value="Connexion"/></TD></TR></form>
</TABLE>

<p><strong><font color="#FF0000" size="1"> * Renseignement essentiel a la connexion</font></strong></p>
<p><a href="/homez.764/docmicro/www/Commonweb/Minkus/Securite/envoi_mail.php">   Mot de passe oublié ?</a></p>
</div></div>
<div id="cadre_fermer"><div id="interrieur_titre">Inscription</div><div id="interrieur">
Inscription Fermé desolé
</div></div>
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
______________________________________________________________________________________________________________________________________________________
</div>
</center>
</body>
</html>