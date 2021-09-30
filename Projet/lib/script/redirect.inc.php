<?php
session_start();
$Hash=$_SESSION['hash'];
$Email=$_SESSION['email'];

if (!isset($Hash)) {
	header('Location:http://www.doc-micro.fr/Projet/');
}
if (!isset($Email)) {
	header('Location:http://www.doc-micro.fr/Projet/');
}
?>