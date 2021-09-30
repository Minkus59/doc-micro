<?php
// recup correspondance pseudo hash
session_start();
$Hash=$_SESSION['hash'];
$Pseudo=$_SESSION['pseudo'];

if (!isset($Hash)) {
header('Location:http://www.doc-micro.fr/Commonweb/Minkus/');
}

if (!isset($Pseudo)) {
header('Location:http://www.doc-micro.fr/Commonweb/Minkus/');
}

?>