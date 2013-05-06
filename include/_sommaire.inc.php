<?php
/** 
 * Contient la division pour le sommaire, sujet à des variations suivant la 
 * connexion ou non d'un utilisateur, et dans l'avenir, suivant le type de cet utilisateur 
 * @todo  RAS
 */

?>
    <!-- Division pour le sommaire -->
    <div id="menuGauche">
     <div id="infosUtil">
    <?php      
      if (estConnecte() ) {
          $idUser = obtenirIdUserConnecte() ;
          $lgUser = obtenirDetailVisiteur($idConnexion, $idUser);
          $nom = $lgUser['nom'];
		  $idCategorie = obtenirCategorieVisiteur($idConnexion, $idUser);
          $prenom = $lgUser['prenom'];            
    ?>
        <h2>
			<?php  
					echo $nom." ".$prenom;
					if($idCategorie == "comptable")			echo "<h4>Comptable</h4>";
					else							        echo '<h4>Visiteur médical</h4>';}
			?> 
		</h2>
      </div>  
	  
	  
<?php      
  if (estConnecte() ) {
?>
        <ul id="menuList">
           <li class="smenu">
              <a href="cAccueil.php" title="Page d'accueil">Accueil</a>
           </li>
           <li class="smenu">
              <a href="cSeDeconnecter.php" title="Se déconnecter">Se déconnecter</a>
           </li>
           <li class="smenu">
              <?php
              if ($idCategorie=="comptable")  {  ?>
			  
              <a  href="cValidationFicheFrais.php" title="Validation fiches de frais">Validation fiches de frais</a>
			  <a  href="cValiderPaiementFicheFrais.php" title="Remboursement des fiches frais">Remboursement des fiches frais</a>
			  
			  <?php }else{ ?>
			  
			  <a  href="cSaisieFicheFrais.php" title="Saisie fiche de frais">Saisie fiche de frais</a>
			  <a href="cConsultFichesFrais.php" title="Consultation de mes fiches de frais">Mes fiches de frais</a>
			  <?php } ?>
          </li>
      
         </ul>
        <?php
          // affichage des éventuelles erreurs déjà détectées
          if ( nbErreurs($tabErreurs) > 0 ) {
              echo toStringErreurs($tabErreurs) ;
          }
  }
        ?>
    </div>