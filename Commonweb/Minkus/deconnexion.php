<?php
session_start();
session_unset();
session_destroy();
header('Location: http://www.doc-micro.fr/Commonweb/Minkus/');
exit();
?>