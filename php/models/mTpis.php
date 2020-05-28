<?php
/*
 * File: mSessions.php
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

    $query = $database->prepare("SELECT tpiID,tpiStatus,title,cfcDomain,abstract FROM tpidbthh.tpis ;");
    if ($query->execute()) {
        $row = $query->fetchAll(PDO::FETCH_ASSOC);

        for ($i = 0; $i < count($row); $i++) {
            $tpi = new cTpi();
            $tpi->id = $row[$i]['tpiID'];
            $tpi->tpiStatus = $row[$i]['tpiStatus'];
            $tpi->title = $row[$i]['title'];
            $tpi->cfcDomain = $row[$i]['cfcDomain'];
            $tpi->abstract = $row[$i]['abstract'];
            array_push($arrTpi, $tpi);
        }
        return $arrTpi;
    }
    return false;
}

function displayTPIAdmin($arrTPI)
{
    $html = "<div class=\"uk-container uk-container-expand\">";
    $html .= "<div class=\"uk-child-width-1-1@m uk-card-small \"uk-grid uk-scrollspy=\"cls: uk-animation-fade; target: .uk-card; delay: 25; repeat: false\">";

    for ($i = 0; $i < count($arrTPI); $i++) {
        $html .= "<div>";
        $html .= "<div class=\"uk-margin-medium-top uk-card uk-card-default uk-card-body\">";
        $html .= "<h3 class=\"uk-card-title\">TPI : " . $arrTPI[$i]->title . "</h3>";
        $html .= "<p>Resumé : " . $arrTPI[$i]->abstract . "</p>";
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
