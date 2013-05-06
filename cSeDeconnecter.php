<?php  
/** 
 * Script de contrôle et d'affichage du cas d'utilisation "Se déconnecter"
 * @package default
 * @todo  RAS
 */
  $repInclude = './include/';
  require($repInclude . "_init.inc.php");
  
  deconnecter() ;  
  header("Location:cAccueil.php");
  
?>