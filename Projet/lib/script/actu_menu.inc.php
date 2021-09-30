<?php
$menu2 = mysql_query("SELECT nom FROM commonweb_groupe WHERE pseudo_id='".$Pseudo."'");
?>
<li><a href="/Commonweb/Minkus/Accueil/">Public</a> 
  <?php while ($bouton2 = mysql_fetch_array($menu2)) { $groupe=explode("¤", $bouton2['nom']); ?>
  <a href="/Commonweb/Minkus/Accueil/?divers=<?php echo $bouton2['nom']; ?>">/ <?php echo $groupe[0]; ?></a> 
  <?php } ?>
</li>
<li>Point commun</li>
