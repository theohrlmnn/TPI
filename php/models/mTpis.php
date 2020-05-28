<?php
/*
 * File: mTpis.php
 * Author: Théo Hurlimann
 * Date: 26.05.2020
 * Description: Contient les fonctions utile pour un TPI
 * Version: 1.0 
*/
require_once("php/inc.all.php");

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

function modifyTpi($tpi)
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

function invalidateTpi($tpi)
{
    //deletePdf($tpi);
    $database = UserDbConnection();
    $query = $database->prepare("UPDATE `tpidbthh`.`tpis` SET `tpiStatus` = :tpiStatus, `pdfPath` = NULL WHERE (`tpiID` = :tpiID);");
    $query->bindValue(":tpiStatus", ST_DRAFT, PDO::PARAM_STR);
    $query->bindParam(":tpiID", $tpi->id, PDO::PARAM_STR);
    if ($query->execute()) {
        return true;
    } else {
        return false;
    }
}

function deletePdf($tpi)
{
    if (unlink(PATH_PDF . $tpi->pdfPath)) {
        return true;
    } else {
        return false;
    }
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
    WHERE m.tpiID = :tpiID");

    $query->bindParam(":tpiID", $id, PDO::PARAM_INT);
    if ($query->execute()) {
        $row = $query->fetchAll(PDO::FETCH_ASSOC);
        if (count($row) == 0) {
            
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

function deleteTpi($tpi)
{
    try {
        deleteEvaluationCriterions($tpi);
        deleteAllMediaByTpiId($tpi);
        $database = UserDbConnection();
        $database->beginTransaction();
        $query = $database->prepare("UPDATE `tpidbthh`.`tpis` SET `tpiStatus` = :tpiStatus WHERE (`tpiID` = :tpiID);");
        $query->bindParam(":tpiID", $tpi->id, PDO::PARAM_INT);
        $query->execute();
        $database->commit();
    } catch (Exception $e) {
        $database->rollback();
        return false;
    }
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
