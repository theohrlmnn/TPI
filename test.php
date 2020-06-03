<?php
/*
 * File: createTPI.php
 * Author: Théo Hurlimann
 * Date: 27.05.2020
 * Description: Permet de créer un TPI
 * Version: 1.0 
*/
require_once("php/inc.all.php");




$idUser = getIdUserSession();
$idTpi = filter_input(INPUT_GET, "tpiId", FILTER_SANITIZE_NUMBER_INT);
$ok = true;
$btnModify = filter_input(INPUT_POST, "btnModify");
$tpi = getTpiByIDWithCriterion($idTpi);
setIdTpiSession($idTpi);
$arrDateTime = getTimeAndDateToTpi($tpi);
if (!$tpi || $tpi->userManagerId != getIdUserSession()) {
    $problem = true;
    $messages = array(
        array("message" => "Impossible de récupérer/mettre à jour les données du TPI demandé.", "type" => AL_DANGER)
    );
    setMessage($messages);
    setDisplayMessage(true);
    $ok = false;
}

if ($btnModify) {

    if (true) { //&& 


        $tpi->userManagerId = null;

        if (true) { //$tpi->tpiStatus == ST_DRAFT
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

            $year = filter_input(INPUT_POST, "tbxYear", FILTER_SANITIZE_NUMBER_INT);
            if (empty($year))
                $year = null;

            $abstract = filter_input(INPUT_POST, "tbxAbstract", FILTER_SANITIZE_STRING);
            if (empty($abstract))
                $abstract = null;

            $description = filter_input(INPUT_POST, "editor"); //FILTER_SANITIZE_SPECIAL_CHARS
            if (empty($description))
                $description = null;
            for ($i = 1; $i <= 7; $i++) {
                ${'criterionNumber' . $i} = filter_input(INPUT_POST, 'tbxCriterionNumber' . $i, FILTER_SANITIZE_NUMBER_INT);
                if (empty(${'criterionNumber' . $i}))
                    ${'criterionNumber' . $i} = null;

                ${'criterionGroup' . $i} = filter_input(INPUT_POST, 'tbxCriterionGroup' . $i, FILTER_SANITIZE_STRING);
                if (empty(${'criterionGroup' . $i}))
                    ${'criterionGroup' . $i} = null;

                ${'criterionDescription' . $i} = filter_input(INPUT_POST, 'tbxCriterionDescription' . $i, FILTER_SANITIZE_STRING);
                if (empty(${'criterionDescription' . $i}))
                    ${'criterionDescription' . $i} = null;
            }

            $sessionStart = formatDateAndTime($sessionStart, $sessionStartTime);
            $sessionEnd = formatDateAndTime($sessionEnd, $sessionStartTime);
            $presentationDate = formatDateAndTime($presentationDate, $presentationTime);

            if ($sessionStart > $sessionEnd) {
                $messages = array(
                    array("message" => "La date de début de session ne peut pas commencé après la fin celle-ci.", "type" => AL_DANGER)
                );
                setMessage($messages);
                setDisplayMessage(true);
                $ok = false;
            }

            if ($presentationDate < $sessionEnd && $presentationDate != null) {
                $messages = array(
                    array("message" => "La date de présentation ne peut pas êre avant la fin de la session.", "type" => AL_DANGER)
                );
                setMessage($messages);
                setDisplayMessage(true);
                $ok = false;
            }

            if ($ok) {


                $tpiUpdate = new cTpi();
                $tpiUpdate->id = $idTpi;
                $tpiUpdate->year = $year;
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
                    if ($criterion->criterionGroup != null || $criterion->criterionNumber != null || $criterion->criterionDescription != null) {
                        array_push($tpiUpdate->evaluationCriterions, $criterion);
                    }
                }

                if ($tpi != $tpiUpdate) {
                    if (modifyTpiByExpert($tpiUpdate)) {
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
                if (modifyTpiByExpert($tpiUpdate)) {
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
    } else {
        $messages = array(
            array("message" => "Vous ne pouvez pas modifier ce TPI", "type" => AL_DANGER)
        );
        setMessage($messages);
    }
}


?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TEST</title>
    <!-- CSS FILES -->
    <link rel="stylesheet" type="text/css" href="css/uikit.css">
    <link rel="stylesheet" href="css/cssNavBar.css">
    <link rel="stylesheet" href="node_modules/trumbowyg/dist/ui/trumbowyg.min.css">
</head>

<body>
    <?php include_once("php/includes/nav.php");
    echo displayMessage();
    ?>
    <form class="toggle-class uk-height-viewport" action="test.php?tpiId=<?= $idTpi ?>" method="POST">
        <div class="uk-container uk-margin-top">
            <div class=" uk-child-width-1-3@s uk-grid-column-small" uk-grid>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Titre</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: user"></span>
                        <input name="tbxTitle" value="<?= $tpi->title ?>" class="uk-input uk-border-pill" placeholder="Outil de collaboration pour le collège d’experts, modules Répartition et ..." type="text">
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Lieu de travail</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: home"></span>
                        <input name="tbxWorkplace" value="<?= $tpi->workplace ?>" class="uk-input uk-border-pill" placeholder="A domicile" type="text">
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Domaine CFC</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: info"></span>
                        <input name="tbxDomainCFC" value="<?= $tpi->cfcDomain ?>" class="uk-input uk-border-pill" placeholder="Développement d'applications" type="text">
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Date du début de la session :</label>
                    <span class="uk-form-icon uk-form-icon-flip"></span>
                    <input name="tbxDateStartSession" value="<?= $arrDateTime['start']['date'] ?>" class="uk-input uk-border-pill" type="date">
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Date de la fin de la session :</label>
                    <span class="uk-form-icon uk-form-icon-flip"></span>
                    <input name="tbxDateEndSession" value="<?= $arrDateTime['end']['date'] ?>" class="uk-input uk-border-pill" type="date">
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Date de la présentation du TPI :</label>
                    <span class="uk-form-icon uk-form-icon-flip"></span>
                    <input name="tbxDatePresentation" value="<?= $arrDateTime['presentation']['date'] ?>" class="uk-input uk-border-pill" type="date">
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Date du début de la session :</label>
                    <span class="uk-form-icon uk-form-icon-flip"></span>
                    <input name="tbxTimeStartSession" value="<?= $arrDateTime['start']['time'] ?>" class="uk-input uk-border-pill" type="time">
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Date de la fin de la session :</label>
                    <span class="uk-form-icon uk-form-icon-flip"></span>
                    <input name="tbxTimeEndSession" value="<?= $arrDateTime['end']['time'] ?>" class="uk-input uk-border-pill" type="time">
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Date de la présentation du TPI :</label>
                    <span class="uk-form-icon uk-form-icon-flip"></span>
                    <input name="tbxTimePresentation" value="<?= $arrDateTime['presentation']['time'] ?>" class="uk-input uk-border-pill" type="time">
                </div>
                <div>
                    <label class="uk-form-label " for="form-horizontal-text">Année du TPI</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: calendar"></span>
                        <input name="tbxYear" value="<?= $tpi->year ?>" class="uk-input uk-border-pill" placeholder="2020" type="number">
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Résumé</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <textarea name="tbxAbstract" rows="17" class="uk-textarea " placeholder="Le but principal de cette application est de donner aux membres du collège ..." type="text"><?= $tpi->abstract ?></textarea>
                    </div>
                </div>
                <div class="uk-width-expand@m">
                    <label class="uk-form-label" for="form-horizontal-text">Description</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <textarea id="editor" name="editor"></textarea>
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Numéro du Critère 1</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionNumber1" value="<?= $tpi->evaluationCriterions[0]->criterionNumber ?>" class="uk-input uk-border-pill" placeholder="14" type="text">
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Groupe Critère 1</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionGroup1" value="<?= $tpi->evaluationCriterions[0]->criterionGroup ?>" class="uk-input uk-border-pill" placeholder="A" type="text">
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Description du Critère 1</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionDescription1" value="<?= $tpi->evaluationCriterions[0]->criterionDescription ?>" class="uk-input uk-border-pill" placeholder="Tenue du journal de travail" type="text">
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Numéro du Critère 2</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionNumber2" value="<?= $tpi->evaluationCriterions[1]->criterionNumber ?>" class="uk-input uk-border-pill" placeholder="15" type="text">
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Groupe Critère 2</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionGroup2" value="<?= $tpi->evaluationCriterions[1]->criterionGroup ?>" class="uk-input uk-border-pill" placeholder="A" type="text">
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Description du Critère 2</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionDescription2" value="<?= $tpi->evaluationCriterions[1]->criterionDescription ?>" class="uk-input uk-border-pill" placeholder="Tenue du journal de travail" type="text">
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Numéro du Critère 3</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionNumber3" value="<?= $tpi->evaluationCriterions[2]->criterionNumber ?>" class="uk-input uk-border-pill" placeholder="16" type="text">
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Groupe Critère 3</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionGroup3" value="<?= $tpi->evaluationCriterions[2]->criterionGroup ?>" class="uk-input uk-border-pill" placeholder="A" type="text">
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Description du Critère 3</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionDescription3" value="<?= $tpi->evaluationCriterions[2]->criterionDescription ?>" class="uk-input uk-border-pill" placeholder="Tenue du journal de travail" type="text">
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Numéro du Critère 4</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionNumber4" value="<?= $tpi->evaluationCriterions[3]->criterionNumber ?>" class="uk-input uk-border-pill" placeholder="17" type="text">
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Groupe Critère 4</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionGroup4" value="<?= $tpi->evaluationCriterions[3]->criterionGroup ?>" class="uk-input uk-border-pill" placeholder="A" type="text">
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Description du Critère 4</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionDescription4" value="<?= $tpi->evaluationCriterions[3]->criterionDescription ?>" class="uk-input uk-border-pill" placeholder="Tenue du journal de travail" type="text">
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Numéro du Critère 5</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionNumber5" value="<?= $tpi->evaluationCriterions[4]->criterionNumber ?>" class="uk-input uk-border-pill" placeholder="18" type="text">
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Groupe Critère 5</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionGroup5" value="<?= $tpi->evaluationCriterions[4]->criterionGroup ?>" class="uk-input uk-border-pill" placeholder="A" type="text">
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Description du Critère 5</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionDescription5" value="<?= $tpi->evaluationCriterions[4]->criterionDescription ?>" class="uk-input uk-border-pill" placeholder="Tenue du journal de travail" type="text">
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Numéro du Critère 6</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionNumber6" value="<?= $tpi->evaluationCriterions[5]->criterionNumber ?>" class="uk-input uk-border-pill" placeholder="19" type="text">
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Groupe Critère 6</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionGroup6" value="<?= $tpi->evaluationCriterions[5]->criterionGroup ?>" class="uk-input uk-border-pill" placeholder="A" type="text">
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Description du Critère 6</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionDescription6" value="<?= $tpi->evaluationCriterions[5]->criterionDescription ?>" class="uk-input uk-border-pill" placeholder="Tenue du journal de travail" type="text">
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Numéro du Critère 7</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionNumber7" value="<?= $tpi->evaluationCriterions[6]->criterionNumber ?>" class="uk-input uk-border-pill" placeholder="20" type="text">
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Groupe Critère 7</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionGroup7" value="<?= $tpi->evaluationCriterions[6]->criterionGroup ?>" class="uk-input uk-border-pill" placeholder="A" type="text">
                    </div>
                </div>
                <div>
                    <label class="uk-form-label" for="form-horizontal-text">Description du Critère 7</label>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon uk-form-icon-flip"></span>
                        <input name="tbxCriterionDescription7" value="<?= $tpi->evaluationCriterions[6]->criterionDescription ?>" class="uk-input uk-border-pill" placeholder="Tenue du journal de travail" type="text">
                    </div>
                </div>
            </div>
        </div>
        <div class=" uk-child-width-1-1@s uk-grid-column-small uk-margin-top" uk-grid>
            <div>
                <div class="uk-margin-bottom uk-flex uk-flex-center">
                    <button name="btnModify" value="Send" type="submit" class="uk-button uk-button-primary uk-border-pill uk-width-1-2">Modifier TPI</button>
                </div>
            </div>
        </div>
    </form>
    <script src="js/uikit.js"></script>
    <script src="js/uikit-icons.js"></script>
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
</body>

</html>