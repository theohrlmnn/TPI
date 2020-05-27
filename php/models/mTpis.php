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

//name select = select + id role = select3 
function displayUserInSelect($textOption1,$id, $arrUser, $required = false)
{
    $html = "<div uk-form-custom=\"target: > * > span:first-child\">";
    if ($required) {
        $html .= "<span uk-icon=\"icon: warning\"></span><select name=" . $id . ">";
    }
    else {
        $html .= "<select name=" . $id . ">";
    }
    
    $html .= "<option value=\"\">" . $textOption1 . "</option>";

    for ($i = 0; $i < count($arrUser); $i++) {
        $html .= "<option value=" . $arrUser[$i]->id . ">" . $arrUser[$i]->firstName . " " . $arrUser[$i]->lastName . "</option>";
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
    $query->bindParam(":year", $tpi->year, PDO::PARAM_STR);
    $query->bindParam(":userCandidateID", $tpi->userCandidateId, PDO::PARAM_STR);
    $query->bindParam(":userManagerID", $tpi->userManagerId, PDO::PARAM_STR);
    $query->bindParam(":userExpert1ID", $tpi->userExpertId, PDO::PARAM_STR);
    $query->bindParam(":userExpert2ID", $tpi->userExpertId2, PDO::PARAM_STR);
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
    }
    else {
        return false;
    }
}