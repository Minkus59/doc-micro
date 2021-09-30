<?php

// Récupération des paramètres POST

$Ip=$_SERVER['REMOTE_ADDR']; 
$Pseudo=mysql_real_escape_string($_POST['pseudo']);
$Email=$_POST['email'];
$Hash_url=($_GET['id']);
$Pseudo_url=($_GET['pseudo']);
$Mdp_url=($_GET['mdp']);
$Email_url=$_GET['email'];
$Jour_url=$_GET['jour'];
$Mois_url=$_GET['mois'];
$Annee_url=$_GET['annee'];
$Genre_url=$_GET['genre'];
$Hote_url=$_GET['hote'];
$New_mdp=$_POST['new_mdp'];
$New_mdp2=$_POST['new_mdp2'];
$Hash=$_POST['hash'];
$Hote_url_created=$_GET['hote'];

?>