<?php
$repInclude = 'include/';
require($repInclude . "_init.inc.php");
require($repInclude . "_entete.inc.html");
require($repInclude . "_sommaire.inc.php");
/**
 * On verifie si un utilisateur est bien connécté, s'il ne l'est pas la page inaccessible
 */
if (!estConnecte()) {
    header("Location: cSeConnecter.php");
}


$etape = lireDonnee("etape", "demanderSaisie");
$Message = lireDonnee("Message","");

/**
 * Si la variable prend la valeur "ValiderPaiementFicheFrais" alors on execute une requete qui change l'etat de la fiche forfait 
 */
if ($etape == "ValiderPaiementFicheFrais")
{
    $id = lireDonnee("idVisiteur","");
    $mois = lireDonnee("moisFicheFrais","");
    miseEnPaiementEtRemboursement($id,$mois);
    $Message="Les Modifications ont bien été pris en comptes";

    header("Location: ./cValiderPaiementFicheFrais.php?Message=".$Message);
}    
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
    /* Styles pour les tableaux de la page principale */
    #contenu table {
        background-color:white;
        border : 0.1em solid #980101;
        color:#980101;
        margin-right : auto ;
        margin-left:0.3em;
        border-collapse : collapse;
    }
    /* Style pour les lignes d'en-tête des tableaux */
    #contenu th {  
        background-color:rgb(238,136,68);
        height:21px;
        text-align:left;
        vertical-align:top;
        font-weight:bold;
        border-bottom:0.1em solid #980101;
        font-size:1.1em;
        color:#980101;
    }
    #contenu td {
        border :1px solid #980101;
    }
</style>
<!-- Division principale -->
<div id="contenu">
    <p align="center" id="Message" name="Message" style="color:red;background-color: gold;margin-left: 18em;margin-right: 18em;"><strong><?php
      if (isset($Message)) echo $Message; ?></strong></p>
    <form id="idFormVisit" method="post"  action="cValiderPaiementFicheFrais.php" form="cValiderPaiementFicheFrais.php">
    <h2>Suivi de paiement</h2>
    <?php
    $idUser = obtenirIdUserConnecte();
    /**
     * Requete qui va récuperer toutes les fiches de frais dont l'état est VA (validé)
     * S'il n'y en a pas alors un message apparait à l'écran
     * S'il la requete renvoie une ou plusieurs ligne alors un tableau va être créé
     */
    $req = mysql_query("SELECT idVisiteur, mois, nbJustificatifs, montantValide, dateModif, idEtat, nom,prenom 
                        FROM fichefrais INNER JOIN visiteur ON fichefrais.idVisiteur=visiteur.id
                        WHERE idEtat = 'VA'");
    if (mysql_num_rows($req) == false) {
        echo "Il n'y a pas de fiche VALIDER à rembourser !";
    } else {
        /**
         * Tableau affichant les fiches de frais de la base de données
         */
        ?>
        <table>
            <tr>
                <th>id Visiteur</th>
                <th>Nom</th>
                <th>Prenom</th>
                <th>Mois</th>
                <th>Nombre justificatifs</th>
                <th>Montant</th>
                <th>Date modification</th>
                <th>Valider</th>
            </tr>
            <?php
            while ($ligne = mysql_fetch_assoc($req)) {
                /**
                 * $id identifiant du visiteur
                 * $nom nom du visiteur
                 * $prenom prenom du visiteur
                 * $noMois mois de la fiche de frais
                 * $annee année de la fiche de frais
                 * $nbJustificatifs nombre de justificatifs pour la fiche de frais
                 * $montant montant totale de la fiche de frais (forfait et hors forfait)
                 * $dateModif date a laquelle la fiche a été validé
                 */
                $idVisiteur = $ligne['idVisiteur'];
                $nom = $ligne['nom'];
                $prenom = $ligne['prenom'];
                $mois = $ligne['mois'];
                $noMois = intval(substr($mois, 4, 2));
                $annee = intval(substr($mois, 0, 4));
                $nbJustificatifs = $ligne['nbJustificatifs'];
                $montant = $ligne['montantValide'];
                $dateModif = $ligne['dateModif'];
                ?>		
                <tr>
                    <td><?php echo $idVisiteur; ?></td>
                    <td><?php echo $nom; ?></td>
                    <td><?php echo $prenom; ?></td>
                    <td><?php echo obtenirLibelleMois($noMois) . " " . $annee; ?></td>
                    <td><?php echo $nbJustificatifs; ?></td>
                    <td><?php echo $montant; ?></td>
                    <td><?php echo $dateModif; ?></td>
                    <td><a href="cValiderPaiementFicheFrais.php?etape=ValiderPaiementFicheFrais&idVisiteur=<?php echo $idVisiteur; ?>&moisFicheFrais=<?php echo $mois; ?>">Valider</a></td>
                </tr>
                <?php
            }
        }
        ?>
    </table>
    </form>
</div>
<?php
require($repInclude . "_pied.inc.html");
require($repInclude . "_fin.inc.php");
?>