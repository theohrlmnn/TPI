<?php
/*
 * File: viewPdf.php
 * Author: Théo Hurlimann
 * Date: 26.05.2020
 * Description: Page pour l'affichage du pdf d'un candidat
 * Version: 1.0 
*/
require_once("php/inc.all.php");

require "vendor/autoload.php";

use Spipu\Html2Pdf\Html2Pdf;

if (!islogged()) {

    $messages = array(
        array("message" => "Vous devez être connecté pour voir ceci.", "type" => AL_DANGER)
    );
    setMessage($messages);
    setDisplayMessage(true);

    header('Location: login.php');
    exit;
}

$btnPdf = filter_input(INPUT_POST, "btnPdf", FILTER_SANITIZE_STRING);

$arrRoles = getRoleUserSession();
$role = min($arrRoles);
$tpi = "";
if ($role == RL_CANDIDATE) {
    $tpi = getTpiByCandidateId(getIdUserSession());
}

if ($btnPdf) {
    $now = date("Y-m-d H:i:s");
    if ($tpi->tpiStatus != ST_VALID || $tpi->sessionStart >= $now) {
        $messages = array(
            array("message" => "Votre TPI n'a pas encore commencé.", "type" => AL_DANGER)
        );
        setMessage($messages);
        setDisplayMessage(true);
    } else {
        header("Content-type: application/pdf");
        header("Content-Disposition: inline; filename=filename.pdf");
        @readfile(PATH_PDF . $tpi->pdfPath);
    }
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste TPI</title>
    <!-- CSS FILES -->
    <link rel="stylesheet" type="text/css" href="css/uikit.css">
    <link rel="stylesheet" href="css/cssNavBar.css">
</head>

<body>
    <?php include_once("php/includes/nav.php");
    echo displayMessage();
    ?>
    <div class="uk-container uk-margin-top">
        <form action="viewPdf.php" class="uk-flex uk-flex-center" method="POST">
            <fieldset class="uk-fieldset">
                <div class="uk-margin-bottom">
                    <button name="btnPdf" value="Send" type="submit" class="uk-button uk-button-primary uk-border-pill uk-width-1-1">Voir votre TPI</button>
                </div>
            </fieldset>
        </form>
    </div>
    <!-- JS FILES -->
    <script src="js/uikit.js"></script>
    <script src="js/uikit-icons.js"></script>
</body>

</html>