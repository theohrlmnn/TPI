<?php
/*
 * File: createTPI.php
 * Author: Théo Hurlimann
 * Date: 27.05.2020
 * Description: Permet de créer un TPI
 * Version: 1.0 
*/
require_once("php/inc.all.php");

if (!islogged() || min(getRoleUserSession()) != RL_ADMINISTRATOR) {

    $messages = array(
        array("message" => "Vous ne pouvez pas accéder à cette page.", "type" => AL_DANGER)
    );
    setMessage($messages);
    setDisplayMessage(true);

    header('Location: home.php');
    exit;
}

$ok = true;

$btnCreate = filter_input(INPUT_POST, "btnCreate");

if ($btnCreate) {
    $manager = filter_input(INPUT_POST, "selectManager", FILTER_SANITIZE_NUMBER_INT);
    $candidat = filter_input(INPUT_POST, "selectCandidat", FILTER_SANITIZE_NUMBER_INT);
    $year = filter_input(INPUT_POST, "tbxYear", FILTER_SANITIZE_NUMBER_INT);

    if ($manager == "" || $candidat == "" || $year == "") {
        $messages = array(
            array("message" => "Veuillez renseigner les champs obligatoire", "type" => AL_DANGER)
        );
        setMessage($messages);
        setDisplayMessage(true);
        $ok = false;
    }

    if (!ctype_digit($year)) {
        $messages = array(
            array("message" => "Veuillez mettre une année en nombre", "type" => AL_DANGER)
        );
        setMessage($messages);
        setDisplayMessage(true);
        $ok = false;
    }

    if ($ok) {
        $expert1 = filter_input(INPUT_POST, "selectExpert1", FILTER_SANITIZE_NUMBER_INT);
        if (!is_numeric($expert1)) {
            $expert1 = null;
        }
        $expert2 = filter_input(INPUT_POST, "selectExpert2", FILTER_SANITIZE_NUMBER_INT);
        if (!is_numeric($expert2)) {
            $expert2 = null;
        }
        $title = filter_input(INPUT_POST, "tbxTitle", FILTER_SANITIZE_STRING);
        $domaineCFC = filter_input(INPUT_POST, "tbxDomainCFC", FILTER_SANITIZE_STRING);
        $abstract = filter_input(INPUT_POST, "tbxAbstract", FILTER_SANITIZE_STRING);
        $workplace = filter_input(INPUT_POST, "tbxWorkPlace", FILTER_SANITIZE_STRING);

        $dateStartSession = filter_input(INPUT_POST, "tbxDateStartSession", FILTER_SANITIZE_STRING);
        if (empty($dateStartSession)) {
            $dateStartSession = null;
        }
        $dateEndSession = filter_input(INPUT_POST, "tbxDateEndSession", FILTER_SANITIZE_STRING);
        if (empty($dateEndSession)) {
            $dateEndSession = null;
        }
        $datePresentation = filter_input(INPUT_POST, "tbxDatePresentation", FILTER_SANITIZE_STRING);
        if (empty($datePresentation)) {
            $datePresentation = null;
        }
        $dateSubmission = date("Y-m-d H:i:s");


        $tpi = new cTpi();
        $tpi->year = $year;
        $tpi->userCandidateId = $candidat;
        $tpi->userManagerId = $manager;
        $tpi->userExpertId = $expert1;
        $tpi->userExpertId2 = $expert2;
        $tpi->title = $title;
        $tpi->cfcDomain = $domaineCFC;
        $tpi->abstract = $abstract;
        $tpi->sessionStart = $dateStartSession;
        $tpi->sessionEnd = $dateEndSession;
        $tpi->presentationDate = $datePresentation;
        $tpi->workplace = $workplace;
        $tpi->submissionDate = $dateSubmission;

        if (createTpi($tpi)) {
            $messages = array(
                array("message" => "Le TPI a bien été créé", "type" => AL_SUCESS)
            );
            setMessage($messages);
            setDisplayMessage(true);
        }
    }
}

$arrUserManager = getAllUserByRole(RL_MANAGER);
$arrUserCandidat = getAllUserByRole(RL_CANDIDATE);
$arrUserExpert = getAllUserByRole(RL_EXPERT);



?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer TPI</title>
    <!-- CSS FILES -->
    <link rel="stylesheet" type="text/css" href="css/uikit.css">
    <link rel="stylesheet" href="css/cssNavBar.css">
</head>

<body>
    <?php include_once("php/includes/nav.php");
    ?>
    <form class="toggle-class uk-flex uk-flex-center uk-background-muted uk-height-viewport" action="createTPI.php" method="POST">

        <fieldset class="uk-fieldset uk-margin-medium-top">
            <?php
            echo displayMessage();
            ?>
            <?php echo displayUserInSelect("Chef de Projet", "selectManager", $arrUserManager, true); ?>
            <?php echo displayUserInSelect("Candidat", "selectCandidat", $arrUserCandidat, true); ?>
            <?php echo displayUserInSelect("Expert 1", "selectExpert1", $arrUserExpert); ?>
            <?php echo displayUserInSelect("Expert 2", "selectExpert2", $arrUserExpert); ?>

            <div class="uk-margin-small">
                <span uk-icon="icon: warning"></span>
                <label class="uk-form-label " for="form-horizontal-text">Année du TPI</label>
                <div class="uk-inline uk-width-1-1">
                    <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: calendar"></span>
                    <input name="tbxYear" class="uk-input uk-border-pill" required placeholder="2020" type="number">
                </div>
            </div>
            <div class="uk-margin-small">
                <label class="uk-form-label" for="form-horizontal-text">Titre</label>
                <div class="uk-inline uk-width-1-1">
                    <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: user"></span>
                    <input name="tbxTitle" class="uk-input uk-border-pill" placeholder="Outil de collaboration pour le collège d’experts, modules Répartition et ..." type="text">
                </div>
            </div>
            <div class="uk-margin-small">
                <label class="uk-form-label" for="form-horizontal-text">Domaine CFC</label>
                <div class="uk-inline uk-width-1-1">
                    <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: info"></span>
                    <input name="tbxDomainCFC" class="uk-input uk-border-pill" placeholder="Développement d'applications" type="text">
                </div>
            </div>
            <div class="uk-margin-small">
                <label class="uk-form-label" for="form-horizontal-text">Résumé</label>
                <div class="uk-inline uk-width-1-1">
                    <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: comment"></span>
                    <input name="tbxAbstract" class="uk-input uk-border-pill" placeholder="Le but principal de cette application est de donner aux membres du collège ..." type="text">
                </div>
            </div>
            <div class="uk-margin-small">
                <label class="uk-form-label" for="form-horizontal-text">Lieu de travail</label>
                <div class="uk-inline uk-width-1-1">
                    <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: home"></span>
                    <input name="tbxWorkPlace" class="uk-input uk-border-pill" placeholder="A domicile" type="text">
                </div>
            </div>
            <div class="uk-margin-bottom">
                <button name="btnCreate" value="Send" type="submit" class="uk-button uk-button-primary uk-border-pill uk-width-1-1">Créer TPI</button>
            </div>

        </fieldset>
        <fieldset class="uk-fieldset uk-flex-left uk-margin-medium-left uk-margin-medium-top">
            <div class="uk-margin-small">
                <div class="uk-inline uk-width-1-1">
                    <label class="uk-form-label" for="form-horizontal-text">Date du début de la session :</label>
                    <span class="uk-form-icon uk-form-icon-flip"></span>
                    <input name="tbxDateStartSession" class="uk-input uk-border-pill" type="date">
                </div>
            </div>
            <div class="uk-margin-small">
                <div class="uk-inline uk-width-1-1">
                    <label class="uk-form-label" for="form-horizontal-text">Date de la fin de la session :</label>
                    <span class="uk-form-icon uk-form-icon-flip"></span>
                    <input name="tbxDateEndSession" class="uk-input uk-border-pill" type="date">
                </div>
            </div>
            <div class="uk-margin-small">
                <div class="uk-inline uk-width-1-1">
                    <label class="uk-form-label" for="form-horizontal-text">Date de la présentation du TPI :</label>
                    <span class="uk-form-icon uk-form-icon-flip"></span>
                    <input name="tbxDatePresentation" class="uk-input uk-border-pill" type="date">
                </div>
            </div>
            <div>
                <article class="uk-comment uk-comment-primary"><span uk-icon="icon: warning"></span> est obligatoire.</article>


            </div>
        </fieldset>

    </form>
    <script src="js/uikit.js"></script>
    <script src="js/uikit-icons.js"></script>
</body>

</html>