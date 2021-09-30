<?php
require('/homez.585/docmicro/www/Jeu/lib/script/fonction_perso.inc.php');
require('/homez.585/docmicro/www/Jeu/lib/script/variable.inc.php');

$gala=$_GET["galaxie"];

if (empty($gala)) {
	$gala=1;
}

$Galaxie=$cnx->query("SELECT * FROM projet_planetes where galaxie=$gala AND system=1");
$System2=$Galaxie->fetch(PDO::FETCH_OBJ);
?>
<!DOCTYPE html>
<html>
<head>
<META http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<META name="description" content="" >
<META name="category" content="">
<META name="keywords" content="">
<META name="robots" content="noindex, nofollow">
<META name="author" content="">
<META name="publisher" content="">
<META name="reply-to" content="">
<META name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />

<link rel="shortcut icon" href="" />
<link href="http://www.doc-micro.fr/Jeu/lib/css/projet_jeu.css" rel="stylesheet" type="text/css"/>

<title></title>
</head>

<body>
<CENTER>
<div id="all">

<header>
	<?php require("/homez.585/docmicro/www/Jeu/lib/script/header.inc.php"); ?>
</header>

<nav>
	<?php require("/homez.585/docmicro/www/Jeu/lib/script/nav.inc.php"); ?>
</nav>

<div id="content">
<div id="int_content">
<div id="fond" class="galaxie<?php echo $System2->galaxie; ?>">
<?php
while($System=$Galaxie->fetch(PDO::FETCH_OBJ)) {
?>
<div id="planete">
<img src="http://www.doc-micro.fr/Jeu/lib/img/system/mini/<?php echo $System->image; ?>.png"
</div>
<?php
}
?>
</div>

</div>
</div>

<footer>
	<?php require("/homez.585/docmicro/www/Jeu/lib/script/footer.inc.php"); ?>
</footer>

</div>

</CENTER>
</body>
</html>