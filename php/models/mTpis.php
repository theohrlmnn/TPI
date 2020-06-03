<?php
/*
 * File: mTpis.php
 * Author: Théo Hurlimann
 * Date: 26.05.2020
 * Description: Contient les fonctions utile pour un TPI
 * Version: 1.0 
*/
require_once("php/inc.all.php");

require "vendor/autoload.php";

use Spipu\Html2Pdf\Html2Pdf;

function getAllTpi()
{
    $database = UserDbConnection();
    $arrTpi = array();

    $query = $database->prepare("SELECT tpiID,tpiStatus,title,cfcDomain,abstract,pdfPath FROM tpidbthh.tpis ;");
    if ($query->execute()) {
        $row = $query->fetchAll(PDO::FETCH_ASSOC);

        for ($i = 0; $i < count($row); $i++) {
            $tpi = new cTpi();
            $tpi->id = $row[$i]['tpiID'];
            $tpi->tpiStatus = $row[$i]['tpiStatus'];
            $tpi->title = $row[$i]['title'];
            $tpi->cfcDomain = $row[$i]['cfcDomain'];
            $tpi->abstract = $row[$i]['abstract'];
            $tpi->pdfPath = $row[$i]['pdfPath'];
            array_push($arrTpi, $tpi);
        }
        return $arrTpi;
    }
    return false;
}

function getTpiByIDWithCriterion($id)
{
    $database = UserDbConnection();

    $query = $database->prepare("SELECT year,userManagerID,tpiStatus,title,cfcDomain,
    abstract,description,sessionStart,sessionEnd,presentationDate,workplace,
    evaluationCriterionID,criterionGroup,criterionNumber,criterionDescription
    FROM tpidbthh.tpis as t
    LEFT JOIN evaluation_criterions as c
    ON c.tpiID = t.tpiID
    WHERE t.tpiID = :tpiID");
    $query->bindParam(":tpiID", $id, PDO::PARAM_INT);

    if ($query->execute()) {
        $row = $query->fetchAll(PDO::FETCH_ASSOC);
        if (count($row) == 0) {
            return false;
        }
        $tpi = new cTpi();
        $tpi->id = $id;
        $tpi->year = $row[0]['year'];
        $tpi->userManagerId = $row[0]['userManagerID'];
        $tpi->tpiStatus = $row[0]['tpiStatus'];
        $tpi->title = $row[0]['title'];
        $tpi->cfcDomain = $row[0]['cfcDomain'];
        $tpi->abstract = $row[0]['abstract'];
        $tpi->description = $row[0]['description'];
        $tpi->sessionStart = $row[0]['sessionStart'];
        $tpi->sessionEnd = $row[0]['sessionEnd'];
        $tpi->presentationDate = $row[0]['presentationDate'];
        $tpi->workplace = $row[0]['workplace'];

        foreach ($row as $r) {
            if ($r['criterionGroup'] != null) {
                $criterion = new cEvaluationCriterion();
                $criterion->id = $r['evaluationCriterionID'];
                $criterion->criterionGroup = $r['criterionGroup'];
                $criterion->criterionNumber = $r['criterionNumber'];
                $criterion->criterionDescription = $r['criterionDescription'];

                array_push($tpi->evaluationCriterions, $criterion);
            }
        }

        return $tpi;
    }
}

function getTpiByIdAllInfo($id)
{
    $database = UserDbConnection();

    $query = $database->prepare("SELECT year,userCandidateID,userManagerID,userExpert1ID,
    userExpert2ID,title,cfcDomain,abstract,sessionStart,sessionEnd,workplace,description,submissionDate
    FROM tpidbthh.tpis 
    WHERE tpiID = :tpiId;");
    $query->bindParam(":tpiId", $id, PDO::PARAM_INT);

    if ($query->execute()) {
        $row = $query->fetchAll(PDO::FETCH_ASSOC);
        if (count($row) == 0) {
            return false;
        }
        $tpi = new cTpi();
        $tpi->id = $id;
        $tpi->year = $row[0]['year'];
        $tpi->userCandidateId = $row[0]['userCandidateID'];
        $tpi->userManagerId = $row[0]['userManagerID'];
        $tpi->userExpertId = $row[0]['userExpert1ID'];
        $tpi->userExpertId2 = $row[0]['userExpert2ID'];
        $tpi->title = $row[0]['title'];
        $tpi->cfcDomain = $row[0]['cfcDomain'];
        $tpi->abstract = $row[0]['abstract'];
        $tpi->sessionStart = $row[0]['sessionStart'];
        $tpi->sessionEnd = $row[0]['sessionEnd'];
        $tpi->workplace = $row[0]['workplace'];
        $tpi->description = $row[0]['description'];
        $tpi->submissionDate = $row[0]['submissionDate'];
        return $tpi;
    }
    return null;
}

function getTpiByID($id)
{
    $database = UserDbConnection();

    $query = $database->prepare("SELECT tpiStatus,title,cfcDomain,abstract,pdfPath FROM tpidbthh.tpis WHERE tpiID = :tpiId;");
    $query->bindParam(":tpiId", $id, PDO::PARAM_INT);

    if ($query->execute()) {
        $row = $query->fetchAll(PDO::FETCH_ASSOC);
        if (count($row) == 0) {
            return false;
        }
        $tpi = new cTpi();
        $tpi->id = $id;
        $tpi->tpiStatus = $row[0]['tpiStatus'];
        $tpi->title = $row[0]['title'];
        $tpi->cfcDomain = $row[0]['cfcDomain'];
        $tpi->abstract = $row[0]['abstract'];
        $tpi->pdfPath = $row[0]['pdfPath'];
        return $tpi;
    }
}

function displayTPIAdmin($arrTpi)
{
    $html = "<div class=\"uk-container uk-container-expand\">";
    $html .= "<div class=\"uk-child-width-1-1@m uk-card-small \"uk-grid uk-scrollspy=\"cls: uk-animation-fade; target: .uk-card; delay: 10; repeat: false\">";

    for ($i = 0; $i < count($arrTpi); $i++) {
        $html .= "<div>";
        $html .= "<div class=\"uk-margin-medium-top uk-card uk-card-default uk-card-body\">";
        $html .= "<form action=\"listTPI.php\" method=\"POST\">";
        $html .= "<h3 class=\"uk-card-title\">TPI : " . $arrTpi[$i]->title . "</h3>";
        if ($arrTpi[$i]->tpiStatus == ST_DRAFT) {
            $html .= "<button name=\"btnDelete\" value=" . $arrTpi[$i]->id . " class=\"uk-margin-top uk-margin-right uk-border-pill uk-position-top-right uk-button uk-button-danger\">Supprimer</button>";
        }
        if ($arrTpi[$i]->tpiStatus == ST_SUBMITTED) {
            $html .= "<button name=\"btnInvalidate\" value=" . $arrTpi[$i]->id . " class=\"uk-margin-top uk-margin-right uk-border-pill uk-position-top-right uk-button uk-button-secondary\">Invalider</button>";
        }
        $html .= "<p>Resumé : " . $arrTpi[$i]->abstract . "</p>";
        $html .= "<button name=\"btnModify\" value=" . $arrTpi[$i]->id . " class=\"uk-button uk-button-default uk-border-pill\">MODIFIER</button>";
        $html .= "</form>";
        $html .= "</div>";
        $html .= "</div>";
    }

    $html .= "</div>";
    $html .= "</div>";

    return $html;
}

function displayTPIExpert($arrTpi)
{
    $html = "<div class=\"uk-container uk-container-expand\">";
    $html .= "<div class=\"uk-child-width-1-1@m uk-card-small \"uk-grid uk-scrollspy=\"cls: uk-animation-fade; target: .uk-card; delay: 10; repeat: false\">";

    for ($i = 0; $i < count($arrTpi); $i++) {
        $html .= "<div>";
        $html .= "<div class=\"uk-margin-medium-top uk-card uk-card-default uk-card-body\">";
        $html .= "<form action=\"listTPI.php\" method=\"POST\">";
        $html .= "<h3  class=\"uk-card-title\"><a href=\"viewTPI.php?tpiId=" . $arrTpi[$i]->id . "\">TPI : " . $arrTpi[$i]->title . "</a></h3>";
        if ($arrTpi[$i]->tpiStatus == ST_SUBMITTED) {
            $html .= "<button name=\"btnInvalidate\" value=" . $arrTpi[$i]->id . " class=\"uk-margin-top uk-margin-right uk-border-pill uk-position-top-right uk-button uk-button-secondary\">Invalider</button>";
        }
        $html .= "<p>Resumé : " . $arrTpi[$i]->abstract . "</p>";
        $html .= "<button name=\"btnModify\" value=" . $arrTpi[$i]->id . " class=\"uk-button uk-button-default uk-border-pill\">MODIFIER</button>";
        $html .= "</form>";
        $html .= "</div>";
        $html .= "</div>";
    }

    $html .= "</div>";
    $html .= "</div>";

    return $html;
}

function displayTPIManager($arrTpi)
{
    $html = "<div class=\"uk-container uk-container-expand\">";
    $html .= "<div class=\"uk-child-width-1-1@m uk-card-small \"uk-grid uk-scrollspy=\"cls: uk-animation-fade; target: .uk-card; delay: 10; repeat: false\">";

    for ($i = 0; $i < count($arrTpi); $i++) {
        $html .= "<div>";
        $html .= "<div class=\"uk-margin-medium-top uk-card uk-card-default uk-card-body\">";
        $html .= "<form action=\"listTPI.php\" method=\"POST\">";
        $html .= "<h3 class=\"uk-card-title\"><a href=\"viewTPI.php?tpiId=" . $arrTpi[$i]->id . "\">TPI : " . $arrTpi[$i]->title . "</a></h3>";
        if ($arrTpi[$i]->tpiStatus == ST_DRAFT) {
            $html .= "<button name=\"btnSubmit\" value=" . $arrTpi[$i]->id . " class=\"uk-margin-top uk-margin-right uk-border-pill uk-position-top-right uk-button uk-button-primary\">Soumettre</button>";
        }
        $html .= "<p>Resumé : " . $arrTpi[$i]->abstract . "</p>";
        $html .= "<button name=\"btnModify\" value=" . $arrTpi[$i]->id . " class=\"uk-button uk-button-default uk-border-pill\">MODIFIER</button>";
        $html .= "</form>";
        $html .= "</div>";
        $html .= "</div>";
    }

    $html .= "</div>";
    $html .= "</div>";

    return $html;
}

function displayUserInSelect($textOption1, $idSelect, $arrUser, $required = false, $valueSelect1 = "")
{
    $html = "<div uk-form-custom=\"target: > * > span:first-child\">";
    if ($required) {
        $html .= "<span uk-icon=\"icon: warning\"></span><select required name=" . $idSelect . ">";
    } else {
        $html .= "<select name=" . $idSelect . ">";
    }

    $html .= "<option value=" . $valueSelect1 . ">" . $textOption1 . "</option>";

    for ($i = 0; $i < count($arrUser); $i++) {
        if ($valueSelect1 != $arrUser[$i]->id) {
            $html .= "<option value=" . $arrUser[$i]->id . ">" . $arrUser[$i]->firstName . " " . $arrUser[$i]->lastName . "</option>";
        }
    }
    $html .= "</select>";
    $html .= "<button class=\"uk-button uk-button-default\" type=\"button\" tabindex=\"-1\">";
    $html .= "<span></span>";
    $html .= "<span uk-icon=\"icon: chevron-down\"></span>";
    $html .= "</button>";
    $html .= "</div>";

    return $html;
}

function createTpi($tpi)
{
    $database = UserDbConnection();
    $query = $database->prepare("INSERT INTO `tpidbthh`.`tpis` (`year`, `userCandidateID`, `userManagerID`, `userExpert1ID`, `userExpert2ID`, `title`, `cfcDomain`, `abstract`,
     `sessionStart`, `sessionEnd`, `presentationDate`, `workplace`, `submissionDate`) 
    VALUES (:year, :userCandidateID, :userManagerID, :userExpert1ID, :userExpert2ID, :title, :cfcDomain, :abstract, :sessionStart, :sessionEnd, :presentationDate, :workplace, :submissionDate);");
    $query->bindParam(":year", $tpi->year, PDO::PARAM_INT);
    $query->bindParam(":userCandidateID", $tpi->userCandidateId, PDO::PARAM_INT);
    $query->bindParam(":userManagerID", $tpi->userManagerId, PDO::PARAM_INT);
    $query->bindParam(":userExpert1ID", $tpi->userExpertId, PDO::PARAM_INT);
    $query->bindParam(":userExpert2ID", $tpi->userExpertId2, PDO::PARAM_INT);
    $query->bindParam(":title", $tpi->title, PDO::PARAM_STR);
    $query->bindParam(":cfcDomain", $tpi->cfcDomain, PDO::PARAM_STR);
    $query->bindParam(":abstract", $tpi->abstract, PDO::PARAM_STR);
    $query->bindParam(":sessionStart", $tpi->sessionStart, PDO::PARAM_STR);
    $query->bindParam(":sessionEnd", $tpi->sessionEnd, PDO::PARAM_STR);
    $query->bindParam(":presentationDate", $tpi->presentationDate, PDO::PARAM_STR);
    $query->bindParam(":workplace", $tpi->workplace, PDO::PARAM_STR);
    $query->bindParam(":submissionDate", $tpi->submissionDate, PDO::PARAM_STR);

    if ($query->execute()) {
        return true;
    } else {
        return false;
    }
}

function getTpiByIdToModifiyByExpert($id)
{
    $database = UserDbConnection();
    $query = $database->prepare("SELECT presentationDate FROM tpidbthh.tpis WHERE tpiID = :tpiId");
    $query->bindParam(":tpiId", $id, PDO::PARAM_INT);

    if ($query->execute()) {
        $row = $query->fetchAll(PDO::FETCH_ASSOC);
        if (count($row) == 0) {
            return false;
        }
        $tpi = new cTpi();
        $tpi->id = $id;
        $tpi->presentationDate = $row[0]['presentationDate'];
        return $tpi;
    }
    return false;
}

function getTpiByIdToModifiyByAdmin($id)
{
    $database = UserDbConnection();
    $query = $database->prepare("SELECT year, userCandidateID, userManagerID, userExpert1ID, userExpert2ID FROM tpidbthh.tpis WHERE tpiID = :tpiId");
    $query->bindParam(":tpiId", $id, PDO::PARAM_INT);

    if ($query->execute()) {
        $row = $query->fetchAll(PDO::FETCH_ASSOC);
        if (count($row) == 0) {
            return false;
        }
        $tpi = new cTpi();
        $tpi->id = $id;
        $tpi->year = $row[0]['year'];
        $tpi->userCandidateId = $row[0]['userCandidateID'];
        $tpi->userManagerId = $row[0]['userManagerID'];
        $tpi->userExpertId = $row[0]['userExpert1ID'];
        $tpi->userExpertId2 = $row[0]['userExpert2ID'];
        return $tpi;
    }
    return false;
}

function getAllTpiByIdUserExpertSession()
{
    $id = getIdUserSession();
    $database = UserDbConnection();
    $query = $database->prepare("SELECT tpiID, userExpert1ID, userExpert2ID, tpiStatus,title,cfcDomain,abstract,pdfPath 
    FROM tpidbthh.tpis 
    WHERE userExpert1ID = :userExpert1ID OR userExpert2ID = :userExpert1ID;");
    $query->bindParam(":userExpert1ID", $id, PDO::PARAM_INT);
    if ($query->execute()) {
        $row = $query->fetchAll(PDO::FETCH_ASSOC);
        $arrTpi = array();
        for ($i = 0; $i < count($row); $i++) {
            $tpi = new cTpi();
            $tpi->id = $row[$i]['tpiID'];
            $tpi->userExpertId = $row[$i]['userExpert1ID'];
            $tpi->userExpertId2 = $row[$i]['userExpert2ID'];
            $tpi->tpiStatus = $row[$i]['tpiStatus'];
            $tpi->title = $row[$i]['title'];
            $tpi->cfcDomain = $row[$i]['cfcDomain'];
            $tpi->abstract = $row[$i]['abstract'];
            $tpi->pdfPath = $row[$i]['pdfPath'];
            array_push($arrTpi, $tpi);
        }
        return $arrTpi;
    }
    return false;
}

function getAllTpiByIdUserManagerSession()
{
    $id = getIdUserSession();
    $database = UserDbConnection();
    $query = $database->prepare("SELECT tpiID, year, userCandidateID, userManagerID, tpiStatus, title, cfcDomain, abstract, description, pdfPath 
    FROM tpidbthh.tpis 
    WHERE userManagerID = :userManagerID");
    $query->bindParam(":userManagerID", $id, PDO::PARAM_INT);
    if ($query->execute()) {
        $row = $query->fetchAll(PDO::FETCH_ASSOC);
        $arrTpi = array();
        for ($i = 0; $i < count($row); $i++) {
            $tpi = new cTpi();
            $tpi->id = $row[$i]['tpiID'];
            $tpi->year = $row[$i]['year'];
            $tpi->userCandidateId = $row[$i]['userCandidateID'];
            $tpi->userManagerId = $row[$i]['userManagerID'];
            $tpi->tpiStatus = $row[$i]['tpiStatus'];
            $tpi->title = $row[$i]['title'];
            $tpi->cfcDomain = $row[$i]['cfcDomain'];
            $tpi->abstract = $row[$i]['abstract'];
            $tpi->description = $row[$i]['description'];
            $tpi->pdfPath = $row[$i]['pdfPath'];
            array_push($arrTpi, $tpi);
        }
        return $arrTpi;
    }
    return false;
}

function modifyTpiByAdmin($tpi)
{
    $database = UserDbConnection();
    $query = $database->prepare("UPDATE `tpidbthh`.`tpis` SET `year` = :year, `userCandidateID` = :userCandidateID,
    `userManagerID` = :userManagerID, `userExpert1ID` = :userExpert1ID, `userExpert2ID` = :userExpert2ID 
    WHERE (`tpiID` = :tpiId);");

    $query->bindParam(":year", $tpi->year, PDO::PARAM_INT);
    $query->bindParam(":userCandidateID", $tpi->userCandidateId, PDO::PARAM_INT);
    $query->bindParam(":userManagerID", $tpi->userManagerId, PDO::PARAM_INT);
    $query->bindParam(":userExpert1ID", $tpi->userExpertId, PDO::PARAM_INT);
    $query->bindParam(":userExpert2ID", $tpi->userExpertId2, PDO::PARAM_INT);
    $query->bindParam(":tpiId", $tpi->id, PDO::PARAM_INT);

    if ($query->execute()) {
        return true;
    } else {
        return false;
    }
}

function modifyTpiByExpert($tpi)
{
    try {
        $database = UserDbConnection();
        $query = $database->prepare("UPDATE `tpidbthh`.`tpis` SET `year` = :year, `title` = :title, 
        `cfcDomain` = :cfcDomain, `abstract` = :abstract, `sessionStart` = :sessionStart, 
        `sessionEnd` = :sessionEnd, `presentationDate` = :presentationDate, `workplace` = :workplace, 
        `description` = :description
        WHERE (`tpiID` = :tpiID);");

        $query->bindParam(":year", $tpi->year, PDO::PARAM_STR);
        $query->bindParam(":title", $tpi->title, PDO::PARAM_STR);
        $query->bindParam(":cfcDomain", $tpi->cfcDomain, PDO::PARAM_STR);
        $query->bindParam(":abstract", $tpi->abstract, PDO::PARAM_STR);
        $query->bindParam(":sessionStart", $tpi->sessionStart, PDO::PARAM_STR);
        $query->bindParam(":sessionEnd", $tpi->sessionEnd, PDO::PARAM_STR);
        $query->bindParam(":presentationDate", $tpi->presentationDate, PDO::PARAM_STR);
        $query->bindParam(":workplace", $tpi->workplace, PDO::PARAM_STR);
        $query->bindParam(":description", $tpi->description, PDO::PARAM_STR);
        $query->bindParam(":tpiID", $tpi->id, PDO::PARAM_INT);
        $criterions = getCriterionWithTpiId($tpi->id);

        foreach ($tpi->evaluationCriterions as $cUpdate) {
            if ($cUpdate->id == -1) {
                createCriterion($cUpdate, $tpi->id);
            } else {
                foreach ($criterions as $c) {
                    if ($c->id == $cUpdate->id) {
                        updateCriterion($cUpdate);
                    }
                }
            }
        }

    } catch (Exception $e) {
        return false;
    }

    if ($query->execute()) {
        return true;
    } else {
        return false;
    }
}

function updateCriterion($cUpdate)
{
    $database = UserDbConnection();
    $query = $database->prepare("UPDATE `tpidbthh`.`evaluation_criterions` 
    SET `criterionGroup` = :criterionGroup, `criterionNumber` = :criterionNumber, `criterionDescription` = :criterionDescription 
    WHERE (`evaluationCriterionID` = :evaluationCriterionID);");

    $query->bindParam(":criterionGroup", $cUpdate->criterionGroup, PDO::PARAM_STR);
    $query->bindParam(":criterionNumber", $cUpdate->criterionNumber, PDO::PARAM_STR);
    $query->bindParam(":criterionDescription", $cUpdate->criterionDescription, PDO::PARAM_STR);
    $query->bindParam(":evaluationCriterionID", $cUpdate->id, PDO::PARAM_STR);

    if ($query->execute()) {
        return true;
    } else {
        return false;
    }
}

function createCriterion($cUpdate, $tpiId)
{
    $database = UserDbConnection();
    $query = $database->prepare("INSERT INTO `tpidbthh`.`evaluation_criterions` 
    (`criterionGroup`, `criterionNumber`, `criterionDescription`, `tpiID`) 
    VALUES (:criterionGroup, :criterionNumber, :criterionDescription, :tpiID);");

    $query->bindParam(":criterionGroup", $cUpdate->criterionGroup, PDO::PARAM_STR);
    $query->bindParam(":criterionNumber", $cUpdate->criterionNumber, PDO::PARAM_STR);
    $query->bindParam(":criterionDescription", $cUpdate->criterionDescription, PDO::PARAM_STR);
    $query->bindParam(":tpiID", $tpiId, PDO::PARAM_STR);

    if ($query->execute()) {
        return true;
    } else {
        return false;
    }
}

function getCriterionWithTpiId($id)
{
    $database = UserDbConnection();
    $query = $database->prepare("SELECT evaluationCriterionID, criterionGroup, criterionNumber, criterionDescription
     FROM `tpidbthh`.`evaluation_criterions` 
     WHERE (`tpiID` = :tpiID);");
    $query->bindParam(":tpiID", $id, PDO::PARAM_INT);

    if ($query->execute()) {
        $row = $query->fetchAll(PDO::FETCH_ASSOC);
        if (count($row) == 0) {
            return false;
        }

        $arrEvaluationCriterions = array();

        foreach ($row as $r) {
            if ($r['criterionGroup'] != null) {
                $criterion = new cEvaluationCriterion();
                $criterion->id = $r['evaluationCriterionID'];
                $criterion->criterionGroup = $r['criterionGroup'];
                $criterion->criterionNumber = $r['criterionNumber'];
                $criterion->criterionDescription = $r['criterionDescription'];

                array_push($arrEvaluationCriterions, $criterion);
            }
        }
        return $arrEvaluationCriterions;
    } else {
        return false;
    }
}

function invalidateTpi($tpi)
{
    deletePdf($tpi);
    $database = UserDbConnection();
    $query = $database->prepare("UPDATE `tpidbthh`.`tpis` SET `tpiStatus` = :tpiStatus, `pdfPath` = NULL, `submissionDate` = NULL WHERE (`tpiID` = :tpiID);");
    $query->bindValue(":tpiStatus", ST_DRAFT, PDO::PARAM_STR);
    $query->bindParam(":tpiID", $tpi->id, PDO::PARAM_STR);
    if ($query->execute()) {
        return true;
    } else {
        return false;
    }
}

function submitTpi($tpi)
{
    $dateSubmission = date("Y-m-d H:i:s");
    $pdfPath = createPdf($tpi, $dateSubmission);
    if ($pdfPath) {
        $database = UserDbConnection();
        $query = $database->prepare("UPDATE `tpidbthh`.`tpis` SET `tpiStatus` = :tpiStatus, `pdfPath` = :pdfPath, `submissionDate` = :submissionDate WHERE (`tpiID` = :tpiID);");
        $query->bindValue(":tpiStatus", ST_SUBMITTED, PDO::PARAM_STR);
        $query->bindValue(":pdfPath", $pdfPath, PDO::PARAM_STR);
        $query->bindValue(":submissionDate", $dateSubmission, PDO::PARAM_STR);
        $query->bindParam(":tpiID", $tpi->id, PDO::PARAM_STR);
        if ($query->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
//a voir
function deletePdf($tpi)
{
    try {
        unlink(PATH_PDF . $tpi->pdfPath);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

//TO DO try catch return false
function createPdf($tpi, $dateSubmission)
{
    $user = getUserById($tpi->userCandidateId);
    $tpi = getTpiByIdAllInfo($tpi->id);
    $dateTtpi = getTimeAndDateToTpi($tpi);
    //"/var/www/html/TPI/php/modelspdf/Enonce_TPI_2020_76_Cart_Thibault.pdf"
    $html2pdf = new Html2Pdf();
    ob_start();
    require("php/includes/print_enonce.php");
    $var = ob_get_clean();
    $html2pdf->writeHTML($var);
    $html2pdf->writeHTML($tpi->description);
    $path = PATH_CREATEPDF . "Enonce_TPI_" . $tpi->year . "_" . $tpi->id . "_" . $user->lastName . "_" . $user->firstName . ".pdf";
    $html2pdf->output($path, 'F');
}

function getTpiByIdInArray($id, $arrTpi)
{
    foreach ($arrTpi as $tpi) {
        if ($tpi->id == $id) {
            return $tpi;
        }
    }
    $tpi = new cTpi();
    $tpi->tpiStatus = "";

    return $tpi;
}

function getTpiByIdWithMedia($id)
{
    $database = UserDbConnection();
    $query = $database->prepare("SELECT t.tpiID,m.mediaID,m.mediaPath 
    FROM tpidbthh.tpis as t 
    LEFT JOIN medias as m
    ON m.tpiID = t.tpiID
    WHERE t.tpiID = :tpiID");

    $query->bindParam(":tpiID", $id, PDO::PARAM_INT);
    if ($query->execute()) {
        $row = $query->fetchAll(PDO::FETCH_ASSOC);
        if (count($row) == 0) {
            return false;
        }
        $tpi = new cTpi();
        $tpi->id = $id;
        $tpi->id = $row[0]['tpiID'];
        if ($row[0]['mediaID'] != null) {
            foreach ($row as $line) {
                $media = new cMedia();
                $media->id = $line['mediaID'];
                $media->mediaPath = $line['mediaPath'];
                array_push($tpi->medias, $media);
            }
        }


        return $tpi;
    }
    return false;
}

function deleteTpi($tpi)
{
    try {
        //deleteEvaluationCriterions($tpi);
        deleteAllMediaByTpiId($tpi);
        $database = UserDbConnection();
        $database->beginTransaction();
        $query = $database->prepare("DELETE FROM `tpidbthh`.`tpis` WHERE (`tpiID` = :tpiID);");
        $query->bindParam(":tpiID", $tpi->id, PDO::PARAM_INT);
        $query->execute();
        $database->commit();
        return true;
    } catch (Exception $e) {
        $database->rollback();
        return false;
    }
}

function addEvaluationCriterions($tpi)
{
    $database = UserDbConnection();
    $query = $database->prepare("INSERT INTO `tpidbthh`.`evaluation_criterions` (`criterionGroup`, `criterionNumber`, `criterionDescription`, `tpiID`) 
    VALUES (:criterionGroup, :criterionNumber, :criterionDescription, :tpiID);");
    foreach ($tpi->evaluationCriterions as $ec) {
        $query->bindParam(":criterionGroup", $ec->criterionGroup, PDO::PARAM_STR);
        $query->bindParam(":criterionNumber", $ec->criterionNumber, PDO::PARAM_STR);
        $query->bindParam(":criterionDescription", $ec->criterionDescription, PDO::PARAM_STR);
        $query->bindParam(":tpiID", $tpi->id, PDO::PARAM_INT);
        if (!$query->execute()) {
            return false;
        }
    }
    return true;
}

function deleteEvaluationCriterions($tpi)
{

    try {
        $database = UserDbConnection();
        $query = $database->prepare("DELETE FROM `tpidbthh`.`evaluation_criterions` WHERE (`tpiID` = :tpiID);");
        $query->bindParam(":tpiID", $tpi->id, PDO::PARAM_INT);
        $query->execute();
    } catch (Exception $e) {
        return $e;
    }
}

function tpiExistIn($tpi, $nameTable)
{
    // pas exactement sûr de ca
    $sql = "SELECT tpiID FROM " . $nameTable . " WHERE (`tpiID` = :tpiID)";
    $database = UserDbConnection();
    $query = $database->prepare($sql);
    //$query->bindParam(":nameTable", $nameTable, PDO::PARAM_STR);
    $query->bindParam(":tpiID", $tpi->id, PDO::PARAM_INT);
    if ($query->execute()) {
        $row = $query->fetchAll(PDO::FETCH_ASSOC);

        if (count($row) != 0) {
            return true;
        }
        return false;
    }
}

function getTimeAndDateToTpi($tpi)
{
    $sessionStart = explode(" ", $tpi->sessionStart);
    $sessionEnd = explode(" ", $tpi->sessionEnd);
    $presentation = explode(" ", $tpi->presentationDate);

    if ($presentation[0] == "") {
        $presentation[] = "";
    }

    if ($sessionStart[0] == "") {
        $sessionStart[] = "";
    }

    if ($sessionEnd[0] == "") {
        $sessionEnd[] = "";
    }
    $dateAndTimeTpi = array(
        "start"  => array("date" => $sessionStart[0], "time" => $sessionStart[1]),
        "end" => array("date" => $sessionEnd[0], "time" => $sessionEnd[1]),
        "presentation"   => array("date" => $presentation[0], "time" => $presentation[1])
    );
    return $dateAndTimeTpi;
}

function displayFormForAdminWithDisplayMessage($tpi, $arrUserManager, $arrUserExpert, $arrUserCandidat, $problem)
{
    $emptyExpert1 = false;
    $emptyExpert2 = false;

    $fullNameManager = getNameUserByIdByArray($tpi->userManagerId, $arrUserManager);
    $fullNameCandidat = getNameUserByIdByArray($tpi->userCandidateId, $arrUserCandidat);

    if ($tpi->userExpertId !== "") {
        $fullNameExpert1 = getNameUserByIdByArray($tpi->userExpertId, $arrUserExpert);
    } else {
        $emptyExpert1 = true;
    }

    if ($tpi->userExpertId2 !== "") {
        $fullNameExpert2 = getNameUserByIdByArray($tpi->userExpertId2, $arrUserExpert);
    } else {
        $emptyExpert2 = true;
    }

    $html = "<form class=\"toggle-class uk-flex uk-flex-center uk-background-muted uk-height-viewport\" action=\"modifyTPI.php?tpiId=" . $tpi->id . "\" method=\"POST\">";
    $html .= "<fieldset class=\"uk-fieldset uk-margin-medium-top\">";
    $html .= displayMessage();
    if ($problem) {
        $html .= displayUserInSelect("Chef de Projet", "selectManager", $arrUserManager, false);
        $html .= displayUserInSelect("Candidat", "selectCandidat", $arrUserCandidat, false);
        $html .= displayUserInSelect("Expert 1", "selectExpert1", $arrUserExpert);
        $html .= displayUserInSelect("Expert 2", "selectExpert2", $arrUserExpert);
    } else {
        $html .= displayUserInSelect($fullNameManager, "selectManager", $arrUserManager, false, $tpi->userManagerId);
        $html .= displayUserInSelect($fullNameCandidat, "selectCandidat", $arrUserCandidat, false, $tpi->userCandidateId);
        if ($emptyExpert1)
            $html .= displayUserInSelect("Expert 1", "selectExpert1", $arrUserExpert, false, $tpi->userExpertId);
        else
            $html .= displayUserInSelect($fullNameExpert1, "selectExpert1", $arrUserExpert, false, $tpi->userExpertId);

        if ($emptyExpert2)
            $html .= displayUserInSelect("Expert 2", "selectExpert2", $arrUserExpert, false, $tpi->userExpertId2);
        else
            $html .= displayUserInSelect($fullNameExpert2, "selectExpert2", $arrUserExpert, false, $tpi->userExpertId2);
    }


    $html .= "<div class=\"uk-margin-small\">";
    $html .= "<label class=\"uk-form-label\" for=\"form-horizontal-text\">Année du TPI</label>";
    $html .= "<div class=\"uk-inline uk-width-1-1\">";
    $html .= "<span class=\"uk-form-icon uk-form-icon-flip\" data-uk-icon=\"icon: calendar\"></span>";
    $html .= "<input name=\"tbxYear\" value=" . $tpi->year . " class=\"uk-input uk-border-pill\" required placeholder=\"2020\" type=\"number\">";
    $html .= "</div>";
    $html .= "</div>";
    $html .= "<div class=\"uk-margin-bottom\">";
    $html .= "<button name=\"btnModify\" value=\"Send\" type=\"submit\" class=\"uk-button uk-button-primary uk-border-pill uk-width-1-1\">Modifer le TPI</button>";
    $html .= "</div>";
    $html .= "</fieldset>";
    $html .= "</form>";

    return $html;
}

function displayFormForExpertWithDisplayMessage($tpi)
{

    $arrDateTime = getTimeAndDateToTpi($tpi);

    $html = "<form class=\"toggle-class uk-flex uk-flex-center uk-background-muted uk-height-viewport\" action=\"modifyTPI.php?tpiId=" . $tpi->id . "\" method=\"POST\">";
    $html .= "<fieldset class=\"uk-fieldset uk-margin-medium-top\">";
    $html .= displayMessage();
    if (count($arrDateTime) == 2) {
        $html .=  "<div class=\"uk-margin-small\">";
        $html .=  "<div class=\"uk-inline uk-width-1-1\">";
        $html .=  "<label class=\"uk-form-label\" for=\"form-horizontal-text\">Date de la présentation du TPI :</label>";
        $html .=  "<span class=\"uk-form-icon uk-form-icon-flip\"></span>";
        $html .=  "<input name=\"tbxDatePresentation\" value=" . $arrDateTime[0] . " class=\"uk-input uk-border-pill\" type=\"date\">";
        $html .=  "</div>";
        $html .=  "</div>";
        $html .=  "<div class=\"uk-margin-small\">";
        $html .=  "<div class=\"uk-inline uk-width-1-1\">";
        $html .=  "<label class=\"uk-form-label\" for=\"form-horizontal-text\">Heure de la présentation du TPI :</label>";
        $html .=  "<span class=\"uk-form-icon uk-form-icon-flip\"></span>";
        $html .=  "<input name=\"tbxTimePresentation\" value=" . $arrDateTime[1] . " class=\"uk-input uk-border-pill\" type=\"time\">";
        $html .=  "</div>";
        $html .=  "</div>";
    } else {
        $html .=  "<div class=\"uk-margin-small\">";
        $html .=  "<div class=\"uk-inline uk-width-1-1\">";
        $html .=  "<label class=\"uk-form-label\" for=\"form-horizontal-text\">Date de la présentation du TPI :</label>";
        $html .=  "<span class=\"uk-form-icon uk-form-icon-flip\"></span>";
        $html .=  "<input name=\"tbxDatePresentation\" class=\"uk-input uk-border-pill\" type=\"date\">";
        $html .=  "</div>";
        $html .=  "</div>";
        $html .=  "<div class=\"uk-margin-small\">";
        $html .=  "<div class=\"uk-inline uk-width-1-1\">";
        $html .=  "<label class=\"uk-form-label\" for=\"form-horizontal-text\">Date de la présentation du TPI :</label>";
        $html .=  "<span class=\"uk-form-icon uk-form-icon-flip\"></span>";
        $html .=  "<input name=\"tbxTimePresentation\" class=\"uk-input uk-border-pill\" type=\"time\">";
        $html .=  "</div>";
        $html .=  "</div>";
    }


    $html .= "<div class=\"uk-margin-bottom\">";
    $html .= "<button name=\"btnModify\" value=\"Send\" type=\"submit\" class=\"uk-button uk-button-primary uk-border-pill uk-width-1-1\">Modifer le TPI</button>";
    $html .= "</div>";
    $html .= "</fieldset>";
    $html .= "</form>";

    return $html;
}

function displayFormForManagerWithDisplayMessage($tpi)
{
}

function buttonChangeRoleListTpi($arrRole, $role)
{
    if (count($arrRole) > 1) {
        $html = "<div class=\"uk-margin-left uk-margin-top\">";
        $html .= "<div class=\"uk-margin uk-grid-small uk-child-width-auto uk-grid\">";
        foreach ($arrRole as $r) {
            if ($r == RL_ADMINISTRATOR) {
                if ($role == $r) {
                    $html .= "<label><input class=\"uk-radio\" type=\"radio\" value=" . RL_ADMINISTRATOR . " name=\"radioRole\" onchange=\"changeRole(" . RL_ADMINISTRATOR . ")\" checked> Liste TPI en tant que Administrateur</label>";
                } else {
                    $html .= "<label><input class=\"uk-radio\" type=\"radio\" value=" . RL_ADMINISTRATOR . " name=\"radioRole\" onchange=\"changeRole(" . RL_ADMINISTRATOR . ")\"> Liste TPI en tant que Administrateur</label>";
                }
            }
            if ($r == RL_EXPERT) {
                if ($role == $r) {
                    $html .= "<label><input class=\"uk-radio\" type=\"radio\" value=" . RL_EXPERT . " name=\"radioRole\" onchange=\"changeRole(" . RL_EXPERT . ")\" checked > Liste TPI en tant que Expert</label>";
                } else {
                    $html .= "<label><input class=\"uk-radio\" type=\"radio\" value=" . RL_EXPERT . " name=\"radioRole\" onchange=\"changeRole(" . RL_EXPERT . ")\" > Liste TPI en tant que Expert</label>";
                }
            }
            if ($r == RL_MANAGER) {
                if ($role == $r) {
                    $html .= "<label><input class=\"uk-radio\" type=\"radio\" value=" . RL_MANAGER . " name=\"radioRole\" onchange=\"changeRole(" . RL_MANAGER . ")\" checked> Liste TPI en tant que Chef de Projet</label>";
                } else {
                    $html .= "<label><input class=\"uk-radio\" type=\"radio\" value=" . RL_MANAGER . " name=\"radioRole\" onchange=\"changeRole(" . RL_MANAGER . ")\" > Liste TPI en tant que Chef de Projet</label>";
                }
            }
        }
        $html .= "</div>";
        $html .= "</div>";

        return $html;
    }
}

function getTpiByCandidateId($id)
{
    $database = UserDbConnection();

    $query = $database->prepare("SELECT sessionStart, tpiStatus,pdfPath FROM tpidbthh.tpis WHERE userCandidateID = :userCandidateID;");
    $query->bindParam(":userCandidateID", $id, PDO::PARAM_INT);

    if ($query->execute()) {
        $row = $query->fetchAll(PDO::FETCH_ASSOC);
        if (count($row) == 0) {
            return false;
        }
        $tpi = new cTpi();
        $tpi->tpiStatus = $row[0]['tpiStatus'];
        $tpi->sessionStart = $row[0]['sessionStart'];
        $tpi->pdfPath = $row[0]['pdfPath'];
        return $tpi;
    }
}

function formatDateAndTime($date, $time)
{

    if ($time == null) {
        $time = "000000";
    }

    if ($date == null) {
        return null;
    }
    $date = $date . " " . $time;

    for ($i = 0; $i < strlen($date); $i++) {
        if ($i == 13) {
            $y = $i + 1;
            $c12 = $date[$y];
            $y++;
            $date[$i] = ":";
            $date[$y] = $c12;
        }
        if ($i == 16) {
            $date[$i] = ":";
            $date .= $time[4] . $time[5];
        }
    }

    return $date;
}
