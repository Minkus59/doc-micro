<html>
<head>
<title>Document sans titre</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>

<?php
  /*
   * Les trois exemples suivants afficheront
   * tous "blablabla".
   */

 // echo $HTTP_COOKIE_VARS['Commonweb']; // exemple 2
  echo $_COOKIE['Commonweb_cnx']; // exemple 3 (si on est sur PHP 4.1.0 ou plus)

?>


</body>
</html>
