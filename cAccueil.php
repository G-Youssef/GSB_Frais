<?php
/**
 * Page d'accueil de l'application web AppliFrais
 * @package default
 * @todo  RAS
 */
$repInclude = 'include/';
require($repInclude . "_init.inc.php");

// page inaccessible si visiteur non connecté
if (!estConnecte()) {
    header("Location: cSeConnecter.php");
}
require($repInclude . "_entete.inc.html");
require($repInclude . "_sommaire.inc.php");
if (estConnecte()) {
    $idUser = obtenirIdUserConnecte();
    $lgUser = obtenirDetailVisiteur($idConnexion, $idUser);
    $idCategorie = obtenirCategorieVisiteur($idConnexion, $idUser);
    if ($idCategorie == "comptable") {
        ?>
        <style>
            #contenu h2 {
                font-family: Verdana, Arial, Helvetica, sans-serif;
                font-size: 14px;
                font-weight: bold;
                color: #980101;
                text-decoration: none;
                border : 1px solid #980101;
                padding-left: 25px;
                background-color: moccasin;
                height : 28px;
            }
        </style>
        <!-- Division principale -->
        <div id="contenu">
            <h2>Bienvenue sur l'intranet GSB</h2>
        </div>
        <?php } else {
        ?>
        <!-- Division principale -->
        <div id="contenu">
            <h2>Bienvenue sur l'intranet GSB</h2>
        </div>
        <?php
    }
}
require($repInclude . "_pied.inc.html");
require($repInclude . "_fin.inc.php");
?>
