<?php
/*
 * File: modifyTPI.php
 * Author: Théo Hurlimann
 * Date: 27.05.2020
 * Description: Permet de modifier un tpi
 * Version: 1.0 
*/
require_once("php/inc.all.php");

if (!islogged()) {

    $messages = array(
        array("message" => "Vous devez vous connecter pour voir ceci.", "type" => AL_DANGER)
    );
    setMessage($messages);
    setDisplayMessage(true);

    header('Location: login.php');
    exit;
}

$btnModify = filter_input(INPUT_POST, "btnModify");
$idTpi = filter_input(INPUT_GET, "tpiId", FILTER_SANITIZE_NUMBER_INT);


$arrRoles = getRoleUserSession();
$highRole = min($arrRoles);

switch ($highRole) {
    case RL_ADMINISTRATOR:
        $ok = true;
        $problem = false;
        $emptyExpert1 = false;
        $emptyExpert2 = false;



        $arrUserManager = getAllUserByRole(RL_MANAGER);
        $arrUserCandidat = getAllUserByRole(RL_CANDIDATE);
        $arrUserExpert = getAllUserByRole(RL_EXPERT);

        $tpi = getTpiByIdToModifiyByAdmin($idTpi); 

        if (!$tpi) {
            $problem = true;
            $messages = array(
                array("message" => "Impossible de récupérer/mettre à jour les données du TPI demandé.", "type" => AL_DANGER)
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

                if ($tpi->year != $year || $tpi->userCandidateId != $candidat || $tpi->userManagerId != $manager || $tpi->userExpertId != $expert1 || $tpi->userExpertId2 != $expert2) {
                    $tpi->id = $idTpi;
                    $tpi->year = $year;
                    $tpi->userCandidateId = $candidat;
                    $tpi->userManagerId = $manager;
                    $tpi->userExpertId = $expert1;
                    $tpi->userExpertId2 = $expert2;

                    if (modifyTpi($tpi)) {
                        $messages = array(
                            array("message" => "Le TPI a bien été mis à jour.", "type" => AL_SUCESS)
                        );
                        setMessage($messages);
                        setDisplayMessage(true);
                    } else {
                        $messages = array(
                            array("message" => "Un problème est apparu lors de la mise à jour du TPI", "type" => AL_DANGER)
                        );
                        setMessage($messages);
                        setDisplayMessage(true);
                    }
                }
            }
        }
        $form = displayFormForAdminWithDisplayMessage($tpi, $arrUserManager, $arrUserExpert, $arrUserCandidat, $problem);
        break;
    case RL_EXPERT:
        $ok = true;
        $problem = false;
        $emptyExpert1 = false;
        $emptyExpert2 = false;

        $tpi = getTpiByIdToModifiyByExpert($idTpi);

        if (!$tpi) {
            $problem = true;
            $messages = array(
                array("message" => "Impossible de récupérer/mettre à jour les données du TPI demandé.", "type" => AL_DANGER)
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

                if ($tpi->year != $year || $tpi->userCandidateId != $candidat || $tpi->userManagerId != $manager || $tpi->userExpertId != $expert1 || $tpi->userExpertId2 != $expert2) {
                    $tpi->id = $idTpi;
                    $tpi->year = $year;
                    $tpi->userCandidateId = $candidat;
                    $tpi->userManagerId = $manager;
                    $tpi->userExpertId = $expert1;
                    $tpi->userExpertId2 = $expert2;

                    if (modifyTpi($tpi)) {
                        $messages = array(
                            array("message" => "Le TPI a bien été mis à jour.", "type" => AL_SUCESS)
                        );
                        setMessage($messages);
                        setDisplayMessage(true);
                    } else {
                        $messages = array(
                            array("message" => "Un problème est apparu lors de la mise à jour du TPI", "type" => AL_DANGER)
                        );
                        setMessage($messages);
                        setDisplayMessage(true);
                    }
                }
            }
        }
        $form = displayFormForExpertWithDisplayMessage($tpi, $problem);
        break;
    case RL_MANAGER:
       
        break;
    default:
        $messages = array(
            array("message" => "Vous ne pouvez pas voir la liste de TPI.", "type" => AL_WARNING)
        );
        setMessage($messages);
        setDisplayMessage(true);

        header('Location: home.php');
        exit;
        break;
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
    echo $form;
    ?>
    <script src="js/uikit.js"></script>
    <script src="js/uikit-icons.js"></script>
</body>

</html>