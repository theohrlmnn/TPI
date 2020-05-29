<?php
/*
 * File: listTPI.php
 * Author: Théo Hurlimann
 * Date: 26.05.2020
 * Description: Page pour l'affichage de la liste des TPIS
 * Version: 1.0 
*/
require_once("php/inc.all.php");

if (!islogged()) {

    $messages = array(
        array("message" => "Vous devez être connecté pour voir ceci.", "type" => AL_DANGER)
    );
    setMessage($messages);
    setDisplayMessage(true);

    header('Location: login.php');
    exit;
}

$arrRoles = getRoleUserSession();
$highRole = min($arrRoles);

$btnModify = filter_input(INPUT_POST, "btnModify", FILTER_SANITIZE_NUMBER_INT);
$btnDelete = filter_input(INPUT_POST, "btnDelete", FILTER_SANITIZE_NUMBER_INT);
$btnInvalidate = filter_input(INPUT_POST, "btnInvalidate", FILTER_SANITIZE_NUMBER_INT);

switch ($highRole) {
    case RL_ADMINISTRATOR:
        $arrTpi = getAllTpi();
        $tpiExistIn = false;
        if ($btnModify) {
            header('Location: modifyTPI.php?idTpi=' . $btnModify);
            exit;
        }

        if ($btnDelete) {
            $tpi = getTpiByIdWithMedia($btnDelete);
            $listTable = array(
                "wishes", "tpi_validations", "evaluation_criterions", "tpi_evaluations", "tpi_evaluations_criterions"
            );

            foreach ($listTable as $t) {
                if (tpiExistIn($tpi, $t)) {
                    $tpiExistIn = true;
                }
            }

            if (!$tpiExistIn && $tpi->tpiStatus == ST_DRAFT) {
                if (deleteTpi($tpi)) {

                    foreach ($arrTpi as $indexArray => $t) {
                        if ($tpi->id == $t->id) {
                            unset($arrTpi[$indexArray]);
                        }
                    }

                    $messages = array(
                        array("message" => "Le TPI a bien été supprimer.", "type" => AL_SUCESS)
                    );
                    setMessage($messages);
                    setDisplayMessage(true);
                };
            } else {
                # TO DO : Gerer information dans plusieurs table Confirmation bien supprimer tpi
            }
        }

        if ($btnInvalidate) {
            $tpi = getTpiByIdInArray($btnInvalidate, $arrTpi);
            if ($tpi->tpiStatus == ST_SUBMITTED) {
                if (invalidateTpi($tpi)) {
                    $tpiUpdate = getTpiByID($tpi->id);
                    foreach ($arrTpi as $indexArray => $tpi) {
                        if ($tpi->id == $tpiUpdate->id) {
                            $arrTpi[$indexArray] = $tpiUpdate;
                        }
                    }
                    $messages = array(
                        array("message" => "Le TPI a bien été invalidé.", "type" => AL_SUCESS)
                    );
                    setMessage($messages);
                    setDisplayMessage(true);
                } else {
                    $messages = array(
                        array("message" => "Une erreur est survenue.", "type" => AL_DANGER)
                    );
                    setMessage($messages);
                    setDisplayMessage(true);
                }
            } else {
                $messages = array(
                    array("message" => "Une erreur est survenue.", "type" => AL_DANGER)
                );
                setMessage($messages);
                setDisplayMessage(true);
            }
        }
        $displayTPI = displayTPIAdmin($arrTpi);
        break;
    case RL_EXPERT:
        $arrTpi = getAllTpiByIdUserSession();

        if ($btnInvalidate) {
            $tpi = getTpiByIdInArray($btnInvalidate, $arrTpi);
            $idUser = getIdUserSession();
            if (
                $tpi->tpiStatus == ST_SUBMITTED && $tpi->userExpertId == $idUser ||
                $tpi->tpiStatus == ST_SUBMITTED && $tpi->userExpertId2 == $idUser
            ) {
                if (invalidateTpi($tpi)) {
                    $tpiUpdate = getTpiByID($tpi->id);
                    foreach ($arrTpi as $indexArray => $tpi) {
                        if ($tpi->id == $tpiUpdate->id) {
                            $arrTpi[$indexArray] = $tpiUpdate;
                        }
                    }
                    $messages = array(
                        array("message" => "Le TPI a bien été invalidé.", "type" => AL_SUCESS)
                    );
                    setMessage($messages);
                    setDisplayMessage(true);
                } else {
                    $messages = array(
                        array("message" => "Une erreur est survenue.", "type" => AL_DANGER)
                    );
                    setMessage($messages);
                    setDisplayMessage(true);
                }
            } else {
                $messages = array(
                    array("message" => "Une erreur est survenue.", "type" => AL_DANGER)
                );
                setMessage($messages);
                setDisplayMessage(true);
            }
        }

        $displayTPI = displayTPIExpert($arrTpi);
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
    <title>Liste TPI</title>
    <!-- CSS FILES -->
    <link rel="stylesheet" type="text/css" href="css/uikit.css">
    <link rel="stylesheet" href="css/cssNavBar.css">
</head>

<body>
    <?php include_once("php/includes/nav.php");
    echo displayMessage();
    echo $displayTPI;
    ?>
    <!-- JS FILES -->
    <script src="js/uikit.js"></script>
    <script src="js/uikit-icons.js"></script>
</body>

</html>