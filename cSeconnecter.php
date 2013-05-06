<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <title>Intranet du Laboratoire Galaxy-Swiss Bourdin</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <link href="./styles/styles.css" rel="stylesheet" type="text/css" />
        <link rel="shortcut icon" type="image/x-icon" href="./images/favicon.ico" />
    </head>
    <?php
    /**
     * Script de contrôle et d'affichage du cas d'utilisation "Se connecter"
     * @package default
     * @todo  RAS
     */
    $repInclude = './include/';
    require($repInclude . "_init.inc.php");

// est-on au 1er appel du programme ou non ?
    $etape = (count($_POST) != 0) ? 'validerConnexion' : 'demanderConnexion';

    if ($etape == 'validerConnexion') { // un client demande à s'authentifier
        // acquisition des données envoyées, ici login et mot de passe
        $login = lireDonneePost("txtLogin");
        $mdp = sha1(lireDonneePost("txtMdp"));
        $lgUser = verifierInfosConnexion($idConnexion, $login, $mdp);
        // si l'id utilisateur a été trouvé, donc informations fournies sous forme de tableau
        if (is_array($lgUser)) {
            affecterInfosConnecte($lgUser["id"], $lgUser["login"]);
        } else {
            ajouterErreur($tabErreurs, "Pseudo et/ou mot de passe incorrects");
        }
    }
    if ($etape == "validerConnexion" && nbErreurs($tabErreurs) == 0) {
        header("Location:cAccueil.php");
    }

//  require($repInclude . "_entete.inc.html");
    ?>
    <body>
        <div id="page">
            <div id="entete">
                <img src="./images/logo.jpg" id="logoGSB" alt="Laboratoire Galaxy-Swiss Bourdin" title="Laboratoire Galaxy-Swiss Bourdin" />
                <h1>Suivi du remboursement des frais</h1>
            </div>
            <?php
            require($repInclude . "_sommaire.inc.php");
            ?>
            <!-- Division pour le contenu principal -->
            <div id="contenu">
                <h2>Identification utilisateur</h2>
                <?php
                if ($etape == "validerConnexion") {
                    if (nbErreurs($tabErreurs) > 0) {
                        echo toStringErreurs($tabErreurs);
                    }
                }
                ?>               
                <form id="frmConnexion" action="" method="post">
                    <div class="corpsForm">
                        <input type="hidden" name="etape" id="etape" value="validerConnexion" />
                        <p>
                            <label for="txtLogin" accesskey="n">* Login : </label>
                            <input type="text" id="txtLogin" name="txtLogin" maxlength="20" size="15" value="" title="Entrez votre login" />
                        </p>
                        <p>
                            <label for="txtMdp" accesskey="m">* Mot de passe : </label>
                            <input type="password" id="txtMdp" name="txtMdp" maxlength="8" size="15" value=""  title="Entrez votre mot de passe"/>
                        </p>
                    </div>
                    <div class="piedForm">
                        <p>
                            <input type="submit" id="ok" value="Valider" />
                            <input type="reset" id="annuler" value="Effacer" />
                        </p> 
                    </div>
                </form>
            </div>
            <?php
            require($repInclude . "_pied.inc.html");
            require($repInclude . "_fin.inc.php");
            ?>