<?php
// R�cup�ration des param�tres POST

$Ip=$_SERVER['REMOTE_ADDR']; 
$Pseudo = $_POST['pseudo'];
$Hash= $_POST['hash'];

// Connexion � la base de donn�es
			   
 include ('/homez.764/docmicro/www/Commonweb/Minkus/impinfbdd/ovh.inc.php');
			   
	@mysql_pconnect($BD_serveur, $BD_utilisateur, $BD_mdp) or die("Impossible de se connecter au serveur de bases de donn�es.");
	@mysql_select_db($BD_base) or die("Impossible de se connecter � la base de donn�es.");
               
        // insertion hote
		$requete6 = 'INSERT INTO commonweb_securite (hote, pseudo_id)									
					 VALUES ("'.$Ip.'", "'.$Pseudo.'")';  
		
		$result6 = @mysql_query($requete6);

	   	if (!$result6){

		echo "<br/>L'enregistrement de vos donn�es a �chou�.<br/>";
		echo "<br/>Veuillez reessayer ulterieurement.<br/>";
		echo '<br/><input type="button" value="Retour" onclick="javascript:history.back()"><br/>';
		mysql_close(); }
		
	else { 
		@mysql_query("UPDATE commonweb_user SET derniere_cnx = NOW() WHERE pseudo = '".$Pseudo."'");
		mysql_close();
		// setcookie('Commonweb_cnx', $Pseudo, (time() + 60));
		session_start();
		$_SESSION['pseudo'] = $Pseudo;
		$_SESSION['hash'] = $Hash;
		header('Location:http://www.doc-micro.fr/Commonweb/Minkus/Accueil/');
	}

?>