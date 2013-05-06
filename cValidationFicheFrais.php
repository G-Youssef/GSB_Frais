<?php
$repInclude = './include/';
require($repInclude . "_init.inc.php");
require($repInclude . "_entete.inc.html");
require($repInclude . "_sommaire.inc.php");

/**
 * On verifie si un utilisateur est bien connécté, s'il ne l'est pas la page inaccessible
 */
if (!estConnecte()) {
    header("Location: cSeConnecter.php");
}
/**
 * Variables qui vont nous servir pour les requetes
 */
$unEtat = "VA";
$idConnexion = obtenirIdUserConnecte();
$VisiteurChoisi = lireDonnee("lstVisiteur", "");
$MoisChoisi = lireDonnee("lstMois", "");
$etape = lireDonnee("etape", "demanderSaisie");
$unJustificatif = lireDonnee("hcMontant", "");
$idLigneHF = lireDonnee("idLigneHF", "");
$dateLigneHF = lireDonnee("dateLigneHF", "");
$libelleLigneHF = lireDonnee("libelleLigneHF", "");
$montantLigneHF = lireDonnee("montantLigneHF", "");
$ETP = lireDonnee("etape2","");
$NUI = lireDonnee("nuitee","");
$KM = lireDonnee("km","");
$REP = lireDonnee("repas","");
$Message = lireDonnee("Message","");






/**
 * Si la variable prend la valeur "ValiderFicheFrais alors on execute une requete qui modifie la fiche de frais (montant, etat)
 */
if ($etape == "validerFicheFrais") {

    //Requête permettant de récupérer les coefficient des frais forfaitisés
$requeteRecupInfoBase = mysql_query("select id,montant from fraisforfait");
while ($lgeltsforfaitBase = mysql_fetch_array($requeteRecupInfoBase)){
    switch ($lgeltsforfaitBase['id']) {
        case "ETP":
            $etpBase = $lgeltsforfaitBase['montant'];
            break;
        case "REP":
            $repBase = $lgeltsforfaitBase['montant'];
            break;
        case "NUI":
            $nuiBase = $lgeltsforfaitBase['montant'];
            break;
        case "KM":
            $kmBase = $lgeltsforfaitBase['montant'];
            break;
    }
}
mysql_free_result($requeteRecupInfoBase);
//requête permettant de récupérer tous les montants des lignes Hors Forfaits d'un visiteur et mois donnée dont le libelle de la fiche ne commence pas par REFUSE
$RequeteHF ="Select montant from lignefraishorsforfait where idVisiteur='".$VisiteurChoisi."' and mois='".$MoisChoisi."' and libelle not like 'REFUSE%'";
$idJeuFraisForfaitHF=  mysql_query($RequeteHF);
$lgForfaitHF =  mysql_fetch_assoc($idJeuFraisForfaitHF);
$CalculHF=0;
//Boucle Tant que si un tableau est retourné
while(is_array($lgForfaitHF)){
    $CalculHF+= $lgForfaitHF['montant'];
    $lgForfaitHF = mysql_fetch_assoc($idJeuFraisForfaitHF);
}
mysql_free_result($idJeuFraisForfaitHF);

//Calcul permettant de calculé le montant total de la fiche de frais (frais forfaitisé et hors forfait)
$unMontant = ($KM * $kmBase) + ($ETP * $etpBase) + ($NUI * $nuiBase) + ($REP * $repBase) + $CalculHF;
//appel de la fonction modifierEtatFicheFrais permettant de mofier l'etat de la fiche en cours
    modifierEtatFicheFrais($idConnexion, $MoisChoisi, $VisiteurChoisi, $unEtat, $unMontant, $unJustificatif);
    $Message = 'La fiche de frais a bien été validée';
    header("Location: ./cValidationFicheFrais.php?Message=".$Message);
}
/**
 * Si la variable prend la valeur "refusHF" alors on execute une requete qui modifie la fiche de frais (on place REFUSE au debut du libelle de la ligne hors forfait)
 */
else if ($etape == "refusHF") {
//Test si le mot Refuse est deja présent pour ne pas le répeter plusieurs fois
      if(!preg_match("#REFUSE#", $libelleLigneHF))       {       $libelleLigneHF = "REFUSE : ".$libelleLigneHF;
                                                          modifierLibelleHorsForfait($idLigneHF,$dateLigneHF, $VisiteurChoisi, $libelleLigneHF);
                                                  }
$Message = 'Les modifications ont bien été prises en compte';
      header("Location: ./cValidationFicheFrais.php?lstVisiteur=".$VisiteurChoisi."&lstMois=".$MoisChoisi."&Message=".$Message);
    }
    /**
     * Si la variable prend la valeur "validerModifLigneHF" alors on execute une requete qui modifie la fiche de frais (modifie la date,le libelle, le montant d'une ligne hors forfait)
     */
else if ($etape == "validerModifLigneHF") {
    modifierLigneHF($idLigneHF, $dateLigneHF, $libelleLigneHF, $montantLigneHF);
    $Message = 'Les modifications ont bien été prises en compte';
    header("Location: ./cValidationFicheFrais.php?lstVisiteur=".$VisiteurChoisi."&lstMois=".$MoisChoisi."&Message=".$Message);
}
/**
 * Si la variable prend la valeur "ValiderFraisForfait" alors on execute une requete qui modifie les frais forfait de la  fiche de frais (etp,km,nui,rep)
 */
else if ($etape == "ValiderFraisForfait") {

      $tabEltsForfait[ETP] = $ETP;
      $tabEltsForfait[KM] = $KM;
      $tabEltsForfait[NUI] = $NUI;
      $tabEltsForfait[REP] = $REP;
      
      modifierEltsForfait(obtenirIdUserConnecte(), $MoisChoisi, $VisiteurChoisi, $tabEltsForfait);
      $Message = "Les Frais Forfaitisés ont bien  été actualisés";
      header("Location: ./cValidationFicheFrais.php?lstVisiteur=".$VisiteurChoisi."&lstMois=".$MoisChoisi."&Message=".$Message);
      
    }


?>

<div id="contenu">
    <title>Validation des frais de visite</title>


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
            background-color:rgb(238,136,68);
            border : 0.1em solid #980101;
            color:black;
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
            

    <p align="center" id="Message" name="Message" style="color:red;background-color: gold;margin-left: 18em;margin-right: 18em;"><strong><?php
      if (isset($Message)) echo $Message; ?></strong></p>

    <form id="idFormVisit" method="post"  action="cValidationFicheFrais.php" form="cValidationFicheFrais.php">
    
        <h1> Validation des frais par visiteur </h1>

     <table>
         <tr>
            <td><label class="titreVisit">Choisir le visiteur :</label></td>
            <!-- Liste déroulante contenant tous les visiteurs ayant des fiches frais à CL -->
            <td><select name="lstVisiteur" id="lstVisiteur" onChange="changeVisit();">
            <option value="0" hidden="hidden" >--Liste des visiteurs--</option>
            <?php
            $recupVisiteur = "select distinct id,nom,prenom from visiteur inner join  fichefrais on visiteur.id = fichefrais.idVisiteur where idEtat='CL' ";
            $JeuVisiteur = mysql_query($recupVisiteur);
            while ($lgVisiteur = mysql_fetch_array($JeuVisiteur)) {
                if ($lgVisiteur['id'] == $VisiteurChoisi) {
                    ?>
                    <option value="<?php echo $lgVisiteur['id']; ?> " selected="selected" > 
                        <?php echo $lgVisiteur['nom'] . " " . $lgVisiteur['prenom']; ?>
                    </option>
                <?php } else {
                    ?>
                    <option value="<?php echo $lgVisiteur['id']; ?>">  
                        <?php echo $lgVisiteur['nom'] . " " . $lgVisiteur['prenom']; ?>
                    </option>
                    <?php
                }
            }
            mysql_free_result($JeuVisiteur);
            ?> 
               </select></td>
          </tr>
       </table>
    </form>
    <br>
    <?php if ($VisiteurChoisi) { ?>
        <form id="idFormMois" method="POST" action="cValidationFicheFrais.php" form="cValidationFicheFrais.php">
            <table>
                <!-- Liste déroulante contenant tous les mois d'un visiteur dont l'etat est à CL -->
           <tr> <td><label class="titreMois">Choisir le mois :</label></td>
            <input type="hidden" name="lstVisiteur" value="<?php echo $VisiteurChoisi ?>" >
            <td><select name="lstMois" id="lstMois" onChange="changeMois();">
                <option value="0" hidden="hidden">--Liste des mois--</option>
                <?php
                $reqMois = ("SELECT DISTINCT mois FROM fichefrais WHERE idVisiteur = '" . $VisiteurChoisi . "' and idEtat='CL'  ");
                $JeuMois = mysql_query($reqMois);
                while ($lgMois = mysql_fetch_array($JeuMois)) {
                    $mois = $lgMois["mois"];
                    $noMois = intval(substr($mois, 4, 2));
                    $annee = intval(substr($mois, 0, 4));
                    ?>
                    <option value="<?php echo $mois; ?>"<?php if ($MoisChoisi == $mois) { ?> selected="selected"<?php } ?> > 
                        <?php echo obtenirLibelleMois($noMois) . " " . $annee; ?></option>
                    <?php
                }
                mysql_free_result($JeuMois);
                ?> 
            </select>
                </td>
        </tr>
        </table>
        </form>
    <?php } ?>
    <?php if ($VisiteurChoisi && $MoisChoisi) { ?>
        <form name="RecupInfoFrais" method="post" action="cValidationFicheFrais.php" form="cValidationFicheFrais.php">
            <input type="hidden" name="lstVisiteur" value="<?php echo $VisiteurChoisi ?>" >
            <input type="hidden" name="lstMois" value="<?php echo $MoisChoisi ?>" >
            <h2 align="center">Frais au forfait </h2>
            <table border="3">
                <tr><th>Etape</th><th>Km </th><th>Nuit&eacute;e</th><th>Repas midi </th><th>Situation</th></tr>
                <tr id="f" align="center">
                    <?php
                    // requête permettant de récuperer toutes les quantité des lignefraisforfait d'un visiteur et mois donnée
                    $recupTableau1 = mysql_query("SELECT idFraisForfait, quantite from lignefraisforfait 
                        WHERE idVisiteur = '" . $VisiteurChoisi . "' and mois='" . $MoisChoisi . "' ");
                    while ($lgTableau1 = mysql_fetch_array($recupTableau1)) {
                        switch ($lgTableau1['idFraisForfait']) {
                            case "ETP":
                                ?>
                                <td width="80" ><input type="text" size="3" id="etape2" name="etape2" value="<?php echo $lgTableau1['quantite'] ?>" ></td>
                                <?php
                                break;
                            case "NUI":
                                ?>
                                <td width="80"><input type="text" size="3" id="nuitee" name="nuitee" value="<?php echo $lgTableau1['quantite'] ?>" ></td> 
                                <?php
                                break;
                            case "KM":
                                ?>
                                <td width="80"> <input type="text" size="3" id="km" name="km" value="<?php echo $lgTableau1['quantite'] ?>" ></td>
                                <?php
                                break;
                            case "REP":
                                ?>
                                <td width="80"> <input type="text" size="3" id="repas" name="repas" value="<?php echo $lgTableau1['quantite'] ?>" ></td>
                                <?php break; ?>
                                <td width="80"> <input type="checked"> </td>
                            <?php
                        }
                    }
                    ?>
                               <!-- Lien permettant d'actualisé les frais forfaitisés -->
                                <td><a onclick="actualiserFraisForfait();"
                                               title="Valider frais forfaitisé" style="cursor:pointer;color:black;" required>Valider</a></td>
                </tr>
            </table>
            <br>
            <?php
            $lgFraisHorsF = mysql_query("SELECT id, date, libelle, montant  from lignefraishorsforfait 
                WHERE idVisiteur = '" . $VisiteurChoisi . "' and mois='" . $MoisChoisi . "' ");
            /**
             * Si la requete renvoie 1 ou plusieurs ligne alors on affiche le tableau sinon il n'apparait pas à l'écran
             */
            if (mysql_fetch_row($lgFraisHorsF) > 0) {
                ?>
                <h2 align="center">Frais Hors Forfait</h2>
                <table border="3">
                    <tr><th>Date</th><th>Libell&eacute;</th><th>Montant</th><th>Modifier</th><th>Supprimer</th></tr>
                <?php
                }
                /**
                 * Requete permettant de récupérer des données dans la base 
                 * (id, date, libelle et le montant des lignes hors forfait pour ensuite les afficher dans le tableau
                 */
                $jeuIdFraisHF = mysql_query("SELECT id, date, libelle, montant  from lignefraishorsforfait 
                    WHERE idVisiteur = '" . $VisiteurChoisi . "' and mois='" . $MoisChoisi . "' ");
                while ($lgFraisHF = mysql_fetch_array($jeuIdFraisHF)) {
                    ?>
                    <input type="hidden" id="idLigneHF" value="<?php echo $lgFraisHF["id"]; ?>">
                    <tr id="hf"align="center">
                        <td width="100" ><input type="text"  id="dateLigneHF<?php echo $lgFraisHF["id"]; ?>" size="12" name="dateLigneHF" value="<?php echo $lgFraisHF['date']; ?> "></td>
                        <td width="220"><input type="text" id="libelleLigneHF<?php echo $lgFraisHF["id"]; ?>" size="30" name="libelleLigneHF" value="<?php echo $lgFraisHF['libelle']; ?>" ></td> 
                        <td width="90"> <input type="text" id="montantLigneHF<?php echo $lgFraisHF["id"]; ?>" size="10" name="montantLigneHF" value="<?php echo $lgFraisHF['montant']; ?>" ></td>
                      
               <td><a onclick="modifLigneHF(<?php echo $lgFraisHF["id"]; ?>);"
                                               title="Modifier la ligne de frais hors forfait" style="cursor:pointer;color:black;">Modifier</a></td>
          
                        
                        <td><a href="cValidationFicheFrais.php?etape=refusHF&dateLigneHF=<?php echo $lgFraisHF['date'] ?>&lstVisiteur=<?php echo $VisiteurChoisi; ?>&libelleLigneHF=<?php echo $lgFraisHF['libelle']; ?>&idLigneHF=<?php echo $lgFraisHF["id"]; ?>&lstMois=<?php echo $MoisChoisi; ?>"
                                               onclick="return confirm('Voulez-vous vraiment refuser cette ligne de frais hors forfait ?');"
                                               title="Refuser la ligne de frais hors forfait" style="color:black">Refuser</a><br /></td>

                    </tr>
    <?php } ?>
            </table>

            <p class="titre"></p>
            <div class="titre">Nb Justificatifs<input type="text" class="zone" size="4" id="hcMontant" name="hcMontant" required/></div></br>
           <center> <input  id="etape" name="etape" value="Valider la Fiche Frais" class="zone"type="submit" /></center>
        </form>
<?php } ?>
</div>

<script language="Javascript">
<?php require("function.js"); ?>
        </script>
<?php
require($repInclude . "_pied.inc.html");
?>
