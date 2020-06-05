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

$idUser = getIdUserSession();
$tpi = getTpiByIdToModifiyByAdmin($idTpi);

if (!$tpi) {
    $problem = true;
    $messages = array(
        array("message" => "Impossible de récupérer/mettre à jour les données du TPI demandé.", "type" => AL_DANGER)
    );
    setMessage($messages);
    setDisplayMessage(true);
    header('Location: listTPI.php');
    exit;
}
if ($tpi->userManagerId == $idUser) {
    $role = RL_MANAGER;
} else if ($tpi->userExpertId == $idUser || $tpi->userExpertId2 == $idUser) {
    $role = RL_EXPERT;
} else if(min(getRoleUserSession()) != RL_ADMINISTRATOR){
    $messages = array(
        array("message" => "Impossible de récuper les informations du TPI.", "type" => AL_DANGER)
    );
    setMessage($messages);
    setDisplayMessage(true);

    header('Location: listTPI.php');
    exit;
}
else {
    $role = RL_ADMINISTRATOR;
}




switch ($role) {
    case RL_ADMINISTRATOR:
        $ok = true;
        $problem = false;
        $emptyExpert1 = false;
        $emptyExpert2 = false;



        $arrUserManager = getAllUserByRole(RL_MANAGER);
        $arrUserCandidat = getAllUserByRole(RL_CANDIDATE);
        $arrUserExpert = getAllUserByRole(RL_EXPERT);



        

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
                    if (modifyTpiByAdmin($tpi)) {
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

        $tpi = getTpiByIdToModifiyByExpert($idTpi);

        if (!$tpi) {
            $problem = true;
            $messages = array(
                array("message" => "Impossible de récupérer/mettre à jour les données du TPI demandé.", "type" => AL_DANGER)
            );
            setMessage($messages);
            setDisplayMessage(true);
            header('Location: listTPI.php');
            exit;
        }

        if ($btnModify) {
            $presentationTime = filter_input(INPUT_POST, "tbxTimePresentation", FILTER_SANITIZE_NUMBER_INT);
            $presentationDate = filter_input(INPUT_POST, "tbxDatePresentation", FILTER_SANITIZE_NUMBER_INT);

            $presentationDate = formatDateAndTime($presentationDate, $presentationTime);

            if ($tpi->sessionEnd != null && $tpi->sessionEnd > $presentationDate) {
                $messages = array(
                    array("message" => "La date de présentation ne peut pas être avant la fin de session qui est le ".$tpi->sessionEnd, "type" => AL_DANGER)
                );
                setMessage($messages);
                setDisplayMessage(true);
                $ok = false;
            }

            /*if (empty($presentationTime)) {
                $messages = array(
                    array("message" => "Veuillez renseiger une heure.", "type" => AL_DANGER)
                );
                setMessage($messages);
                setDisplayMessage(true);
                $ok = false;
            }

            if (empty($presentationDate)) {
                $messages = array(
                    array("message" => "Veuillez renseiger une date.", "type" => AL_DANGER)
                );
                setMessage($messages);
                setDisplayMessage(true);
                $ok = false;
            }*/

            if ($ok) {

                

                if ($tpi->presentationDate != $presentationDate) {
                    $tpi->presentationDate = $presentationDate;
                    if (modifyTpiByExpert($tpi)) {
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
        $form = displayFormForExpertWithDisplayMessage($tpi);
        break;
    case RL_MANAGER:
        $idUser = getIdUserSession();
        $idTpi = filter_input(INPUT_GET, "tpiId", FILTER_SANITIZE_NUMBER_INT);
        $ok = true;
        $btnModify = filter_input(INPUT_POST, "btnModify");
        $tpi = getTpiByIDWithCriterion($idTpi);
        $arrDateTime = getTimeAndDateToTpi($tpi);
        setIdTpiSession($idTpi);

        if (!$tpi || $tpi->userManagerId != getIdUserSession()) {
            $problem = true;
            $messages = array(
                array("message" => "Impossible de récupérer/mettre à jour les données du TPI demandé.", "type" => AL_DANGER)
            );
            setMessage($messages);
            setDisplayMessage(true);
            header('Location: listTPI.php');
            exit;
        }

        if ($btnModify) {



            $tpi->year = null;
            $tpi->userManagerId = null;

            if ($tpi->tpiStatus == ST_DRAFT) { 
                $tpi->tpiStatus = null;
                $title = filter_input(INPUT_POST, "tbxTitle", FILTER_SANITIZE_STRING);
                if (empty($title))
                    $title = null;

                $workplace = filter_input(INPUT_POST, "tbxWorkplace", FILTER_SANITIZE_STRING);
                if (empty($workplace))
                    $workplace = null;

                $cfcDomain = filter_input(INPUT_POST, "tbxDomainCFC", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
                if (empty($cfcDomain))
                    $cfcDomain = null;

                $sessionStart = filter_input(INPUT_POST, "tbxDateStartSession", FILTER_SANITIZE_NUMBER_INT);
                if (empty($sessionStart))
                    $sessionStart = null;

                $sessionEnd = filter_input(INPUT_POST, "tbxDateEndSession", FILTER_SANITIZE_NUMBER_INT);
                if (empty($sessionEnd))
                    $sessionEnd = null;

                $presentationDate = filter_input(INPUT_POST, "tbxDatePresentation", FILTER_SANITIZE_NUMBER_INT);
                if (empty($presentationDate))
                    $presentationDate = null;

                $presentationTime = filter_input(INPUT_POST, "tbxTimePresentation", FILTER_SANITIZE_NUMBER_INT);
                if (empty($presentationTime))
                    $presentationTime = null;

                $sessionStartTime = filter_input(INPUT_POST, "tbxTimeStartSession", FILTER_SANITIZE_NUMBER_INT);
                if (empty($sessionStartTime))
                    $sessionStartTime = null;

                $sessionEndTime = filter_input(INPUT_POST, "tbxTimeEndSession", FILTER_SANITIZE_NUMBER_INT);
                if (empty($sessionEndTime))
                    $sessionEndTime = null;

                $abstract = filter_input(INPUT_POST, "tbxAbstract", FILTER_SANITIZE_STRING);
                if (empty($abstract))
                    $abstract = null;

                $description = filter_input(INPUT_POST, "editor"); //FILTER_SANITIZE_SPECIAL_CHARS
                if (empty($description))
                    $description = null;
                for ($i = 1; $i <= 7; $i++) {
                    ${'criterionNumber' . $i} = filter_input(INPUT_POST, 'tbxCriterionNumber' . $i, FILTER_SANITIZE_NUMBER_INT);

                    ${'criterionGroup' . $i} = filter_input(INPUT_POST, 'tbxCriterionGroup' . $i, FILTER_SANITIZE_STRING);

                    ${'criterionDescription' . $i} = filter_input(INPUT_POST, 'tbxCriterionDescription' . $i, FILTER_SANITIZE_STRING);
                }

                $sessionStart = formatDateAndTime($sessionStart, $sessionStartTime);
                $sessionEnd = formatDateAndTime($sessionEnd, $sessionEndTime);
                $presentationDate = formatDateAndTime($presentationDate, $presentationTime);

                if ($sessionStart > $sessionEnd) {
                    $messages = array(
                        array("message" => "La date de début de session ne peut pas commencé après la fin celle-ci.", "type" => AL_DANGER)
                    );
                    setMessage($messages);
                    setDisplayMessage(true);
                    $ok = false;
                }

                if ($presentationDate < $sessionEnd) {
                    if ($presentationDate != null) {
                        $messages = array(
                            array("message" => "La date de présentation ne peut pas êre avant la fin de la session.", "type" => AL_DANGER)
                        );
                        setMessage($messages);
                        setDisplayMessage(true);
                        $ok = false;
                    }
                }

                if ($ok) {


                    $tpiUpdate = new cTpi();
                    $tpiUpdate->id = $idTpi;
                    $tpiUpdate->title = $title;
                    $tpiUpdate->cfcDomain = $cfcDomain;
                    $tpiUpdate->abstract = $abstract;
                    $tpiUpdate->description = $description;
                    $tpiUpdate->sessionStart = $sessionStart;
                    $tpiUpdate->sessionEnd = $sessionEnd;
                    $tpiUpdate->presentationDate = $presentationDate;
                    $tpiUpdate->workplace = $workplace;


                    for ($i = 1; $i <= 7; $i++) {
                        $y = $i - 1;
                        $criterion = new cEvaluationCriterion();
                        if (isset($tpi->evaluationCriterions[$y]->id)) {
                            $criterion->id = $tpi->evaluationCriterions[$y]->id;
                        }
                        $criterion->criterionGroup = ${'criterionGroup' . $i};
                        $criterion->criterionNumber = ${'criterionNumber' . $i};
                        $criterion->criterionDescription = ${'criterionDescription' . $i};
                        array_push($tpiUpdate->evaluationCriterions, $criterion);
                    }

                    if ($tpi != $tpiUpdate) {
                        if (modifyTpiByManager($tpiUpdate)) {
                            $messages = array(
                                array("message" => "Le TPI a bien été mis à jour.", "type" => AL_SUCESS)
                            );
                            setMessage($messages);
                            setDisplayMessage(true);
                            $tpi = $tpiUpdate;
                        } else {
                            $messages = array(
                                array("message" => "Un problème est apparu lors de la mise à jour du TPI", "type" => AL_DANGER)
                            );
                            setMessage($messages);
                            setDisplayMessage(true);
                        }
                    }
                }
            } else {
                $presentationDate = filter_input(INPUT_POST, "tbxDatePresentation", FILTER_SANITIZE_NUMBER_INT);
                if (empty($presentationDate))
                    $presentationDate = null;
                
                if ($tpi->presentationDate != $presentationDate) {
                    $tpi->presentationDate = $presentationDate;
                    if (modifyTpiByManagerOnlyPresentationDate($tpi)) {
                        $messages = array(
                            array("message" => "Le date de présentation a bien été mis à jour.", "type" => AL_SUCESS)
                        );
                        setMessage($messages);
                        setDisplayMessage(true);
                        $tpi = $tpiUpdate;
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
        $form = displayFormForManagerWithDisplayMessage($tpi, $arrDateTime);
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
    <link rel="stylesheet" href="node_modules/trumbowyg/dist/ui/trumbowyg.min.css">
</head>

<body>
    <?php include_once("php/includes/nav.php"); ?>
    <div class="uk-child-width-expand@s " uk-grid>
        <?php if ($form == null) {
            include_once("php/includes/formManager.php");
        } else {
            echo $form;
        }; ?>
    </div>

    <script src="js/uikit.js"></script>
    <script src="js/uikit-icons.js"></script>
    <?php if ($role == RL_MANAGER) { ?>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script>
            window.jQuery || document.write('<script src="js/vendor/jquery-3.3.1.min.js"><\/script>')
        </script>
        <script src="node_modules/trumbowyg/dist/trumbowyg.min.js"></script>
        <!--Import dependency for Resizimg. For a production setup, follow install instructions here: https://github.com/RickStrahl/jquery-resizable -->
        <script src="//rawcdn.githack.com/RickStrahl/jquery-resizable/master/dist/jquery-resizable.min.js"></script>

        <!-- Import Trumbowyg -->
        <script src="node_modules/trumbowyg/dist/trumbowyg.js"></script>

        <!-- Import Trumbowyg colors JS -->
        <script src="node_modules/trumbowyg/dist/plugins/colors/trumbowyg.colors.min.js"></script>

        <!-- Import Trumbowyg Resize JS -->
        <script src="node_modules/trumbowyg/dist/plugins/resizimg/resize.with.canvas.min.js"></script>
        <script src="node_modules/trumbowyg/dist/plugins/resizimg/trumbowyg.resizimg.min.js"></script>

        <!-- Import Trumbowyg Upload Image JS -->
        <script src="node_modules/trumbowyg/dist/plugins/upload/trumbowyg.upload.min.js"></script>

        <!-- Import Trumbowyg Template -->
        <script src="trumbowyg/dist/plugins/template/trumbowyg.template.min.js"></script>

        <!-- Import all plugins you want AFTER importing jQuery and Trumbowyg -->
        <script src="trumbowyg/dist/plugins/history/trumbowyg.history.min.js"></script>

        <!-- Import all plugins you want AFTER importing jQuery and Trumbowyg -->
        <script src="trumbowyg/dist/plugins/fontsize/trumbowyg.fontsize.min.js"></script>

        <!-- Import Uilkit JS -->
        <script src="node_modules/uikit-3.4.3/js/uikit.js"></script>
        <script>
            $(document).ready(function() {
                $("#editor").trumbowyg({
                    btns: [
                        ["formatting"],
                        ['foreColor', 'backColor'],
                        ["strong", "em", "del"],
                        ["superscript", "subscript"],
                        ['upload'],
                        ["justifyLeft", "justifyCenter", "justifyRight", "justifyFull"],
                        ["unorderedList", "orderedList"],
                        ["horizontalRule"],
                        ["removeformat"],
                        ["fullscreen"],
                        ['historyUndo', 'historyRedo']
                    ],
                    plugins: {
                        upload: {
                            serverPath: 'uploadMedia.php',
                            fileFieldName: 'image' + <?= $tpi->id ?>,
                            urlPropertyName: 'link',
                        }
                    }
                });
                $('#editor').trumbowyg('html', <?php echo json_encode($tpi->description); ?>)
            });
        </script>
    <?php } ?>
</body>

</html>