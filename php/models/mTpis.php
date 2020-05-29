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

function displayTPIExpert($arrTpi)
{
    $html = "<div class=\"uk-container uk-container-expand\">";
    $html .= "<div class=\"uk-child-width-1-1@m uk-card-small \"uk-grid uk-scrollspy=\"cls: uk-animation-fade; target: .uk-card; delay: 10; repeat: false\">";

    for ($i = 0; $i < count($arrTpi); $i++) {
        $html .= "<div>";
        $html .= "<div class=\"uk-margin-medium-top uk-card uk-card-default uk-card-body\">";
        $html .= "<form action=\"listTPI.php\" method=\"POST\">";
        $html .= "<h3 class=\"uk-card-title\">TPI : " . $arrTpi[$i]->title . "</h3>";
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

function getAllTpiByIdUserSession()
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

function deletePdf($tpi)
{
    try {
        unlink(PATH_PDF . $tpi->pdfPath);
        return true;
    } catch (Exception $e) {
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
        deleteEvaluationCriterions($tpi);
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

function displayFormForAdminWithDisplayMessage($tpi, $arrUserManager, $arrUserExpert, $arrUserCandidat, $problem)
{
    $emptyExpert1 = false;
    $emptyExpert2 = false;

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

    $html = "<form class=\"toggle-class uk-flex uk-flex-center uk-background-muted uk-height-viewport\" action=\"modifyTPI.php?idTpi=" . $tpi->id . " method=\"POST\">";
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

function displayFormForExpertWithDisplayMessage($tpi, $problem)
{

    $html = "<form class=\"toggle-class uk-flex uk-flex-center uk-background-muted uk-height-viewport\" action=\"modifyTPI.php?idTpi=" . $tpi->id . " method=\"POST\">";
    $html .= "<fieldset class=\"uk-fieldset uk-margin-medium-top\">";
    $html .= displayMessage();
    if ($problem) {
        $html .=  "<div class=\"uk-margin-small\">";
        $html .=  "<div class=\"uk-inline uk-width-1-1\">";
        $html .=  "<label class=\"uk-form-label\" for=\"form-horizontal-text\">Date de la présentation du TPI :</label>";
        $html .=  "<span class=\"uk-form-icon uk-form-icon-flip\"></span>";
        $html .=  "<input name=\"tbxDatePresentation\" value=" . $tpi->presentationDate . " class=\"uk-input uk-border-pill\" placeholder=\"Résumé\" type=\"date\">";
        $html .=  "</div>";
        $html .=  "</div>";
    } else {
        $html .=  "<div class=\"uk-margin-small\">";
        $html .=  "<div class=\"uk-inline uk-width-1-1\">";
        $html .=  "<label class=\"uk-form-label\" for=\"form-horizontal-text\">Date de la présentation du TPI :</label>";
        $html .=  "<span class=\"uk-form-icon uk-form-icon-flip\"></span>";
        $html .=  "<input name=\"tbxDatePresentation\" class=\"uk-input uk-border-pill\" placeholder=\"Résumé\" type=\"time\">";
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
