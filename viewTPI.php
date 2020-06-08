<?php
/*
 * File: viewTPI.php
 * Author: Théo Hurlimann
 * Date: 26.05.2020
 * Description: Page pour l'affichage d'un TPI 
 * Version: 1.0 
*/
require_once("php/inc.all.php");

require "vendor/autoload.php";

use Spipu\Html2Pdf\Html2Pdf;
$role = min($arrRoles);

if (!islogged() || $role == RL_CANDIDATE) {

    $messages = array(
        array("message" => "Vous devez être connecté pour voir ceci.", "type" => AL_DANGER)
    );
    setMessage($messages);
    setDisplayMessage(true);

    header('Location: login.php');
    exit;
}

$id = filter_input(INPUT_GET, "tpiId", FILTER_SANITIZE_NUMBER_INT);

$btnPdf = filter_input(INPUT_POST, "btnPdf", FILTER_SANITIZE_STRING);

$arrRoles = getRoleUserSession();

$tpi = getTpiByIDAllInfo($id);
$tpi->evaluationCriterions = getCriterionWithTpiId($tpi->id);
$arrDateTime = getTimeAndDateToTpi($tpi);
$candidat = getUserById($tpi->userCandidateId);

if ($btnPdf) {
    if ($tpi->pdfPath != null) {

        header("Content-type: application/pdf");
        header("Content-Disposition: inline; filename=filename.pdf");
        @readfile(PATH_PDF . $tpi->pdfPath);
        exit;
    }
    else {
        $messages = array(
            array("message" => "Le PDF n'a pas encore été créé.", "type" => AL_DANGER)
        );
        setMessage($messages);
        setDisplayMessage(true);
    }
}

if ($tpi->userExpertId != null) {
    $expert1 = getUserById($tpi->userExpertId);
} else {
    $expert1 = new cUser;
    $expert1->firstName = "Expert";
    $expert1->lastName = "1";
}
if ($tpi->userExpertId2 != null) {
    $expert2 = getUserById($tpi->userExpertId2);
} else {
    $expert2 = new cUser;
    $expert2->firstName = "Expert";
    $expert2->lastName = "2";
}
$manager = getUserById($tpi->userManagerId);

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vue TPI</title>
    <!-- CSS FILES -->
    <link rel="stylesheet" type="text/css" href="css/uikit.css">
    <link rel="stylesheet" href="css/cssNavBar.css">
</head>

<body>
    <?php include_once("php/includes/nav.php");
    echo displayMessage();
    ?>
    <div class="toggle-class uk-height-viewport uk-margin-bottom">
        <div class="uk-container uk-margin-top">
            <div class=" uk-child-width-1-3@s uk-grid-column-small" uk-grid>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Titre</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: user"></span>
                        <input name="tbxTitle" class="uk-input uk-border-pill" value="<?= $tpi->title ?>" type="text" disabled>
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Lieu de travail</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: home"></span>
                        <input name="tbxWorkPlace" class="uk-input uk-border-pill" value="<?= $tpi->workplace ?>" type="text" disabled>
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Domaine CFC</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: info"></span>
                        <input name="tbxDomainCFC" class="uk-input uk-border-pill" value="<?= $tpi->cfcDomain ?>" type="text" disabled>
                    </div>
                </div>
                <div>
                    <label class="uk-form-label " for="form-horizontal-text">Année du TPI</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: calendar"></span>
                        <input name="tbxYear" class="uk-input uk-border-pill" value="<?= $tpi->year ?>" type="number" disabled>
                    </div>
                </div>
                <div></div>
                <div></div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Experts : </label>
                    <div class="uk-margin-top" uk-form-custom="target:> * > span:first-child\">
                        <button class="uk-button uk-button-default" type="button" tabindex="-1\" disabled>
                            <span><?php echo $expert1->firstName . " " . $expert1->lastName ?></span>
                        </button>
                    </div>
                    <div disable class="uk-margin-top uk-margin-left" uk-form-custom="target:> * > span:first-child\">
                        <button class="uk-button uk-button-default" type="button" tabindex="-1\" disabled>
                            <span><?php echo $expert2->firstName . " " . $expert2->lastName ?></span>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Chef de projet : </label>
                    <div class="uk-margin-top" uk-form-custom="target:> * > span:first-child\">
                        <button class="uk-button uk-button-default" type="button" tabindex="-1\" disabled>
                            <span><?php echo $manager->firstName . " " . $manager->lastName ?></span>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Candidat : </label>
                    <div class="uk-margin-top" uk-form-custom="target:> * > span:first-child\">
                        <button class="uk-button uk-button-default" type="button" tabindex="-1\" disabled>
                            <span><?php echo $candidat->firstName . " " . $candidat->lastName ?></span>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Date du début de la session :</label>
                    <span class="uk-form-icon uk-form-icon-flip"></span>
                    <input name="tbxDateStartSession" value="<?= $arrDateTime["start"]["date"] ?>" class="uk-input uk-border-pill" type="date" disabled>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Date de la fin de la session :</label>
                    <span class="uk-form-icon uk-form-icon-flip"></span>
                    <input name="tbxDateEndSession" value="<?= $arrDateTime["end"]["date"] ?>" class="uk-input uk-border-pill" type="date" disabled>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Date de la présentation du TPI :</label>
                    <span class="uk-form-icon uk-form-icon-flip"></span>
                    <input name="tbxDatePresentation" value="<?= $arrDateTime["presentation"]["date"] ?>" class="uk-input uk-border-pill" type="date" disabled>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Numéro du Critère 1</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionNumber1" value="<?= $tpi->evaluationCriterions[0]->criterionNumber ?>" class="uk-input uk-border-pill" type="text" disabled>
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Groupe Critère 1</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionGroup1" value="<?= $tpi->evaluationCriterions[0]->criterionGroup ?>" class="uk-input uk-border-pill" type="text" disabled>
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Description du Critère 1</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionDescription1" value="<?= $tpi->evaluationCriterions[0]->criterionDescription ?>" class="uk-input uk-border-pill" type="text" disabled>
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Numéro du Critère 2</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionNumber2" value="<?= $tpi->evaluationCriterions[1]->criterionNumber ?>" class="uk-input uk-border-pill" type="text" disabled>
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Groupe Critère 2</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionGroup2" value="<?= $tpi->evaluationCriterions[1]->criterionGroup ?>" class="uk-input uk-border-pill" type="text" disabled>
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Description du Critère 2</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionDescription2" value="<?= $tpi->evaluationCriterions[1]->criterionDescription ?>" class="uk-input uk-border-pill" type="text" disabled>
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Numéro du Critère 3</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionNumber3" value="<?= $tpi->evaluationCriterions[2]->criterionNumber ?>" class="uk-input uk-border-pill" type="text" disabled>
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Groupe Critère 3</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionGroup3" value="<?= $tpi->evaluationCriterions[2]->criterionGroup ?>" class="uk-input uk-border-pill" type="text" disabled>
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Description du Critère 3</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionDescription3" value="<?= $tpi->evaluationCriterions[2]->criterionDescription ?>" class="uk-input uk-border-pill" type="text" disabled>
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Numéro du Critère 4</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionNumber4" value="<?= $tpi->evaluationCriterions[3]->criterionNumber ?>" class="uk-input uk-border-pill" type="text" disabled>
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Groupe Critère 4</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionGroup4" value="<?= $tpi->evaluationCriterions[3]->criterionGroup ?>" class="uk-input uk-border-pill" type="text" disabled>
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Description du Critère 4</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionDescription4" value="<?= $tpi->evaluationCriterions[3]->criterionDescription ?>" class="uk-input uk-border-pill" type="text" disabled>
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Numéro du Critère 5</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionNumber5" value="<?= $tpi->evaluationCriterions[4]->criterionNumber ?>" class="uk-input uk-border-pill" type="text" disabled>
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Groupe Critère 5</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionGroup5" value="<?= $tpi->evaluationCriterions[4]->criterionGroup ?>" class="uk-input uk-border-pill" type="text" disabled>
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Description du Critère 5</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionDescription5" value="<?= $tpi->evaluationCriterions[4]->criterionDescription ?>" class="uk-input uk-border-pill" type="text" disabled>
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Numéro du Critère 6</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionNumber6" value="<?= $tpi->evaluationCriterions[5]->criterionNumber ?>" class="uk-input uk-border-pill" type="text" disabled>
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Groupe Critère 6</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionGroup6" value="<?= $tpi->evaluationCriterions[5]->criterionGroup ?>" class="uk-input uk-border-pill" type="text" disabled>
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Description du Critère 6</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionDescription6" value="<?= $tpi->evaluationCriterions[5]->criterionDescription ?>" class="uk-input uk-border-pill" type="text" disabled>
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Numéro du Critère 7</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionNumber7" value="<?= $tpi->evaluationCriterions[6]->criterionNumber ?>" class="uk-input uk-border-pill" type="text" disabled>
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Groupe Critère 7</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionGroup7" value="<?= $tpi->evaluationCriterions[6]->criterionGroup ?>" class="uk-input uk-border-pill" type="text" disabled>
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Description Critère 7</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionGroup7" value="<?= $tpi->evaluationCriterions[6]->criterionDescription ?>" class="uk-input uk-border-pill" type="text" disabled>
                    </div>
                </div>
                <div>
                    <div>
                        <div class="uk-inline uk-width-1-1 uk-margin-top">
                            <form action="viewTPI.php?tpiId=<?= $id ?>" class="uk-flex uk-flex-center" method="POST">
                                <button name="btnPdf" value="Send" type="submit" class="uk-button uk-button-primary uk-border-pill uk-width-1-2">Voir le PDF</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class=" uk-child-width-1-1@s uk-grid-column-small" uk-grid>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Résumé</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: comment"></span>
                        <textarea name="tbxAbstract" class="uk-textarea " rows="6" type="text" disabled><?= $tpi->abstract ?></textarea>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- JS FILES -->
    <script src="js/uikit.js"></script>
    <script src="js/uikit-icons.js"></script>
</body>

</html>