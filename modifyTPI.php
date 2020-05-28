<?php
/*
 * File: modifyTPI.php
 * Author: Théo Hurlimann
 * Date: 27.05.2020
 * Description: Permet de modifier un tpi
 * Version: 1.0 
*/
require_once("php/inc.all.php");

if (!islogged() && min(getRoleUserSession()) != RL_Administrator) {

    $messages = array(
        array("message" => "Vous n'avez pas les droits pour voir ceci.", "type" => AL_Danger)
    );
    setMessage($messages);
    setDisplayMessage(true);

    header('Location: home.php');
    exit;
}

$ok = true;
$problem = false;
$emptyExpert1 = false;
$emptyExpert2 = false;

$idTpi = filter_input(INPUT_GET, "idTpi", FILTER_SANITIZE_NUMBER_INT);
$btnModify = filter_input(INPUT_POST, "btnModify");

$tpi = getTpiByIdToModifiyByAdmin($idTpi);
$arrUserManager = getAllUserByRole(RL_Manager);
$arrUserCandidat = getAllUserByRole(RL_Candidate);
$arrUserExpert = getAllUserByRole(RL_Expert);

$fullNameManager = getNameUserByRoleByArray($tpi->userManagerId, $arrUserManager);
$fullNameCandidat = getNameUserByRoleByArray($tpi->userCandidateId, $arrUserCandidat);

if ($tpi->userExpertId !== "") {
    $fullNameExpert1 = getNameUserByRoleByArray($tpi->userExpertId, $arrUserExpert);
} else {
    $emptyExpert1 = true;
}

if ($tpi->userExpertId2 !== "") {
    $fullNameExpert2 = getNameUserByRoleByArray($tpi->userExpertId2, $arrUserExpert);
} else {
    $emptyExpert2 = true;
}


if (!$tpi) {
    $problem = true;
    $messages = array(
        array("message" => "Impossible de récupérer/mettre à jour les données du TPI demandé.", "type" => AL_Danger)
    );
    setMessage($messages);
    setDisplayMessage(true);
    $ok = false;
}

if ($btnModify) {
    $manager = filter_input(INPUT_POST, "selectManager", FILTER_SANITIZE_NUMBER_INT);
    $candidat = filter_input(INPUT_POST, "selectCandidat", FILTER_SANITIZE_NUMBER_INT);
    $year = filter_input(INPUT_POST, "tbxYear", FILTER_SANITIZE_STRING);


    if (!ctype_digit($year)) {
        $messages = array(
            array("message" => "Veuillez mettre une année en nombre", "type" => AL_Danger)
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

        if ($tpi->year != $year || $tpi->userCandidateId != $candidat || $tpi->userManagerId = $manager || $tpi->userExpertId = $expert1 || $tpi->userExpertId2 = $expert2) {
            $tpi = new cTpi();
            $tpi->id = $idTpi;
            $tpi->year = $year;
            $tpi->userCandidateId = $candidat;
            $tpi->userManagerId = $manager;
            $tpi->userExpertId = $expert1;
            $tpi->userExpertId2 = $expert2;

            if ( modifyTpi($tpi)) {
                $messages = array(
                    array("message" => "Le TPI a bien été mis à jour.", "type" => AL_Sucess)
                );
                setMessage($messages);
                setDisplayMessage(true);
            }
            else {
                $messages = array(
                    array("message" => "Un problème est apparu lors de la mise à jour du TPI", "type" => AL_Danger)
                );
                setMessage($messages);
                setDisplayMessage(true);
            }
           
        }
    }
}





?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier TPI</title>
    <!-- CSS FILES -->
    <link rel="stylesheet" type="text/css" href="css/uikit.css">
    <link rel="stylesheet" href="css/cssNavBar.css">
</head>

<body>
    <?php include_once("php/includes/nav.php");
    ?>
    <form class="toggle-class uk-flex uk-flex-center uk-background-muted uk-height-viewport" action="modifyTPI.php?idTpi=<?=$idTpi?>" method="POST">

        <fieldset class="uk-fieldset uk-margin-medium-top">
            <?php
            echo displayMessage();

            if ($problem) {
                echo displayUserInSelect("Chef de Projet", "selectManager", $arrUserManager, false);
                echo displayUserInSelect("Candidat", "selectCandidat", $arrUserCandidat, false);
                echo displayUserInSelect("Expert 1", "selectExpert1", $arrUserExpert);
                echo displayUserInSelect("Expert 2", "selectExpert2", $arrUserExpert);
            } else {
                echo displayUserInSelect($fullNameManager, "selectManager", $arrUserManager, false, $tpi->userManagerId);
                echo displayUserInSelect($fullNameCandidat, "selectCandidat", $arrUserCandidat, false, $tpi->userCandidateId);
                if ($emptyExpert1)
                    echo displayUserInSelect("Expert 1", "selectExpert1", $arrUserExpert, false, $tpi->userExpertId);
                else
                    echo displayUserInSelect($fullNameExpert1, "selectExpert1", $arrUserExpert, false, $tpi->userExpertId);

                if ($emptyExpert2)
                    echo displayUserInSelect("Expert 2", "selectExpert2", $arrUserExpert, false, $tpi->userExpertId2);
                else
                    echo displayUserInSelect($fullNameExpert2, "selectExpert2", $arrUserExpert, false, $tpi->userExpertId2);
            } ?>


            <div class="uk-margin-small">
                <label class="uk-form-label " for="form-horizontal-text">Année du TPI</label>
                <div class="uk-inline uk-width-1-1">
                    <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: calendar"></span>
                    <input name="tbxYear" value="<?=$tpi->year?>" class="uk-input uk-border-pill" required placeholder="2020" type="number">
                </div>
            </div>
            <div class="uk-margin-bottom">
                <button name="btnModify" value="Send" type="submit" class="uk-button uk-button-primary uk-border-pill uk-width-1-1">Modifer le TPI</button>
            </div>
        </fieldset>
    </form>
    <script src="js/uikit.js"></script>
    <script src="js/uikit-icons.js"></script>
</body>

</html>