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

/**
 * Fonction permettant de récupérer  un tableau contenant tous les TPI
 *
 * @return array[cTpi] tableau si ok, false si problème
 */
function getAllTpi()
{
    $database = UserDbConnection();
    $arrTpi = array();

    $query = $database->prepare("SELECT tpiID,year,userCandidateID,userManagerID,tpiStatus, pdfPath FROM tpidbthh.tpis ;");
    if ($query->execute()) {
        $row = $query->fetchAll(PDO::FETCH_ASSOC);

        for ($i = 0; $i < count($row); $i++) {
            $tpi = new cTpi();
            $tpi->id = $row[$i]['tpiID'];
            $tpi->year = $row[$i]['year'];
            $tpi->userCandidateId = $row[$i]['userCandidateID'];
            $tpi->userManagerId = $row[$i]['userManagerID'];
            $tpi->tpiStatus = $row[$i]['tpiStatus'];
            $tpi->pdfPath = $row[$i]['pdfPath'];
            array_push($arrTpi, $tpi);
        }
        return $arrTpi;
    }
    return false;
}

/**
 * Fonction permettant de récupérer  un TPI avec ces critères d'évaluations. Utilisé pour récupérer les champs modifiable par un expert
 *
 * @param int $id l'id du TPI qu'on veut recevoir 
 * @return cTpi return le Tpi si Ok, false si problème
 */
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

        for ($i = 0; $i < 7; $i++) {
            if (isset($row[$i]['criterionGroup'])) {
                $criterion = new cEvaluationCriterion();
                $criterion->id = $row[$i]['evaluationCriterionID'];
                $criterion->criterionGroup = $row[$i]['criterionGroup'];
                $criterion->criterionNumber = $row[$i]['criterionNumber'];
                $criterion->criterionDescription = $row[$i]['criterionDescription'];

                array_push($tpi->evaluationCriterions, $criterion);
            } else {
                $criterion = new cEvaluationCriterion();
                array_push($tpi->evaluationCriterions, $criterion);
            }
        }

        return $tpi;
    }
}

/**
 * Fonction permettant de récupérer toutes les informations d'un TPI sans critère ou media
 *
 * @param int $id l'id du TPI qu'on veut recevoir 
 * @return cTpi return le Tpi si Ok, false si problème
 */
function getTpiByIdAllInfo($id)
{
    $database = UserDbConnection();

    $query = $database->prepare("SELECT year,userCandidateID,userManagerID,userExpert1ID,
    userExpert2ID,title,cfcDomain,abstract,sessionStart,sessionEnd,presentationDate,workplace,description,submissionDate, pdfPath
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
        $tpi->presentationDate = $row[0]['presentationDate'];
        $tpi->workplace = $row[0]['workplace'];
        $tpi->description = $row[0]['description'];
        $tpi->submissionDate = $row[0]['submissionDate'];
        $tpi->pdfPath = $row[0]['pdfPath'];
        return $tpi;
    }
    return null;
}

/**
 * Fonction permettant de récupérer un TPI avec statut,titre,domaine du cfc, réusmé et le nom du pdf
 *
 * @param int $id l'id du TPI qu'on veut recevoir 
 * @return cTpi return le Tpi si Ok, false si problème
 */
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

/**
 * Fonction permettant de récupérer un html pour afficher liste des TPI pour un Admin
 *
 * @param array[cTpi] $arrTpi tableau de tous les TPI
 * @param array[int] $arrRole tous les rôle de l'utilisateur
 * @param int $role role actuelle pour afficher le role en cours si l'utilisateur a plusieurs roles
 * @return string return html de la liste
 */
function displayTPIAdmin($arrTpi, $arrRoles, $role)
{
    $arrManager = getAllUserByRole(RL_MANAGER);
    $arrCandidat = getAllUserByRole(RL_CANDIDATE);



    $html = "<form action=\"listTPI.php\" method=\"POST\">";
    $html .= buttonChangeRoleListTpi($arrRoles, $role);
    $html .= "<div class=\"uk-container uk-container-expand\">";
    $html .= "<div class=\"uk-child-width-1-1@m uk-card-small \"uk-grid uk-scrollspy=\"cls: uk-animation-fade; target: .uk-card; delay: 10; repeat: false\">";

    foreach ($arrTpi as $tpi) {
        $managerName = getNameUserByIdByArray($tpi->userManagerId, $arrManager);
        $candidateName = getNameUserByIdByArray($tpi->userCandidateId, $arrCandidat);

        $html .= "<div>";
        $html .= "<div class=\"uk-margin-medium-top uk-card uk-card-default uk-card-body\">";
        $html .= "<h3 class=\"uk-card-title\">Candidat : " .  $candidateName . "</h3>";
        if ($tpi->tpiStatus == ST_DRAFT) {
            $html .= "<button name=\"btnDelete\" value=" . $tpi->id . " class=\"uk-margin-top uk-margin-right uk-border-pill uk-position-top-right uk-button uk-button-danger\">Supprimer</button>";
        }
        if ($tpi->tpiStatus == ST_SUBMITTED) {
            $html .= "<button name=\"btnInvalidate\" value=" . $tpi->id . " class=\"uk-margin-top uk-margin-right uk-border-pill uk-position-top-right uk-button uk-button-secondary\">Invalider</button>";
        }
        $html .= "<p>Chef de projet : " . $managerName . "</p>";
        $html .= "<p>Année TPI : " . $tpi->year . "</p>";
        $html .= "<button name=\"btnModify\" value=" . $tpi->id . " class=\"uk-button uk-button-default uk-border-pill\">MODIFIER</button>";
        $html .= "</form>";
        $html .= "</div>";
        $html .= "</div>";
    }

    $html .= "</div>";
    $html .= "</div>";

    return $html;
}

/**
 * Fonction permettant de récupérer un html pour afficher liste des TPI pour un expert
 *
 * @param array[cTpi] $arrTpi tableau de tous les TPI
 * @param array[int] $arrRole tous les rôle de l'utilisateur
 * @param int $role role actuelle pour afficher le role en cours si l'utilisateur a plusieurs roles
 * @return string return html de la liste
 */
function displayTPIExpert($arrTpi, $arrRoles, $role)
{
    $arrManager = getAllUserByRole(RL_MANAGER);
    $arrCandidat = getAllUserByRole(RL_CANDIDATE);

    $html = "<form action=\"listTPI.php\" method=\"POST\">";
    $html .= buttonChangeRoleListTpi($arrRoles, $role);
    $html .= "<div class=\"uk-container uk-container-expand\">";
    $html .= "<div class=\"uk-child-width-1-1@m uk-card-small \"uk-grid uk-scrollspy=\"cls: uk-animation-fade; target: .uk-card; delay: 10; repeat: false\">";

    foreach ($arrTpi as $tpi) {
        $managerName = getNameUserByIdByArray($tpi->userManagerId, $arrManager);
        $candidateName = getNameUserByIdByArray($tpi->userCandidateId, $arrCandidat);

        $html .= "<div>";
        $html .= "<div class=\"uk-margin-medium-top uk-card uk-card-default uk-card-body\">";

        $html .= "<h3  class=\"uk-card-title\"><a href=\"viewTPI.php?tpiId=" . $tpi->id . "\">Candidat : " .  $candidateName . "</a></h3>";
        if ($tpi->tpiStatus == ST_SUBMITTED) {
            $html .= "<button name=\"btnInvalidate\" value=" . $tpi->id . " class=\"uk-margin-top uk-margin-right uk-border-pill uk-position-top-right uk-button uk-button-secondary\">Invalider</button>";
        }
        $html .= "<p>Chef de projet : " . $managerName . "</p>";
        $html .= "<p>Année TPI : " . $tpi->year . "</p>";
        $html .= "<button name=\"btnModify\" value=" . $tpi->id . " class=\"uk-button uk-button-default uk-border-pill\">MODIFIER</button>";
        $html .= "</form>";
        $html .= "</div>";
        $html .= "</div>";
    }

    for ($i = 0; $i < count($arrTpi); $i++) {
    }

    $html .= "</div>";
    $html .= "</div>";

    return $html;
}

/**
 * Fonction permettant de récupérer  un html pour afficher liste des TPI pour un chef de projet/ manager
 *
 * @param array[cTpi] $arrTpi tableau de tous les TPI
 * @param array[int] $arrRole tous les rôle de l'utilisateur
 * @param int $role role actuelle pour afficher le role en cours si l'utilisateur a plusieurs roles
 * @return string return html de la liste
 */
function displayTPIManager($arrTpi, $arrRoles, $role)
{
    $arrManager = getAllUserByRole(RL_MANAGER);
    $arrCandidat = getAllUserByRole(RL_CANDIDATE);

    $html = "<form action=\"listTPI.php\" method=\"POST\">";
    $html .= buttonChangeRoleListTpi($arrRoles, $role);
    $html .= "<div class=\"uk-container uk-container-expand\">";
    $html .= "<div class=\"uk-child-width-1-1@m uk-card-small \"uk-grid uk-scrollspy=\"cls: uk-animation-fade; target: .uk-card; delay: 10; repeat: false\">";

    foreach ($arrTpi as $tpi) {
        $managerName = getNameUserByIdByArray($tpi->userManagerId, $arrManager);
        $candidateName = getNameUserByIdByArray($tpi->userCandidateId, $arrCandidat);

        $html .= "<div>";
        $html .= "<div class=\"uk-margin-medium-top uk-card uk-card-default uk-card-body\">";

        $html .= "<h3 class=\"uk-card-title\"><a href=\"viewTPI.php?tpiId=" . $tpi->id . "\">Candidat : " .  $candidateName . "</a></h3>";
        if ($tpi->tpiStatus == ST_DRAFT) {
            $html .= "<button name=\"btnSubmit\" value=" . $tpi->id . " class=\"uk-margin-top uk-margin-right uk-border-pill uk-position-top-right uk-button uk-button-primary\">Soumettre</button>";
        }
        $html .= "<p>Chef de projet : " . $managerName . "</p>";
        $html .= "<p>Année TPI : " . $tpi->year . "</p>";
        $html .= "<button name=\"btnModify\" value=" . $tpi->id . " class=\"uk-button uk-button-default uk-border-pill\">MODIFIER</button>";
        $html .= "</form>";
        $html .= "</div>";
        $html .= "</div>";
    }


    $html .= "</div>";
    $html .= "</div>";

    return $html;
}

/**
 * Fonction permettant de récupérer  un html pour afficher liste des utilisateurs dans un select
 *
 * @param string $textOption1 Valeur afficher par défaut exmple : Chef de project
 * @param int $idSelect name du select pour pouvoir le récupérer 
 * @param array[cUser] $arrUser tableau des users à afficher
 * @param bool $required optionnel s'il est obligatoire default = false
 * @param int $valueSelect1 optionnel si l'on veut donner une valeur au premier option default = ""
 * @return string return html du select
 */
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

/**
 * Fonction permettant de récupérer  un TPI avec statut,titre,domaine du cfc, résumé  et le nom du pdf
 *
 * @param cTpi $tpi Le Tpi qu'on veut créer dans la base de donnée 
 * @return bool return true si Ok, false si problème
 */
function createTpi($tpi)
{
    $database = UserDbConnection();
    $query = $database->prepare("INSERT INTO `tpidbthh`.`tpis` (`year`, `userCandidateID`, `userManagerID`, `userExpert1ID`, `userExpert2ID`, `title`, `cfcDomain`, `abstract`,
     `sessionStart`, `sessionEnd`, `presentationDate`, `workplace`) 
    VALUES (:year, :userCandidateID, :userManagerID, :userExpert1ID, :userExpert2ID, :title, :cfcDomain, :abstract, :sessionStart, :sessionEnd, :presentationDate, :workplace);");
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

    if ($query->execute()) {
        return true;
    } else {
        return false;
    }
}

/**
 * Fonction permettant de recevoir un tpi avec les champs qui sont modifiable par un expert
 * @param int $id id du TPI qu'on veut recevoir 
 * @return cTpi return true si Ok, false si problème
 */
function getTpiByIdToModifiyByExpert($id)
{
    $database = UserDbConnection();
    $query = $database->prepare("SELECT sessionEnd,presentationDate FROM tpidbthh.tpis WHERE tpiID = :tpiId");
    $query->bindParam(":tpiId", $id, PDO::PARAM_INT);

    if ($query->execute()) {
        $row = $query->fetchAll(PDO::FETCH_ASSOC);
        if (count($row) == 0) {
            return false;
        }
        $tpi = new cTpi();
        $tpi->id = $id;
        $tpi->sessionEnd = $row[0]['sessionEnd'];
        $tpi->presentationDate = $row[0]['presentationDate'];
        return $tpi;
    }
    return false;
}

/**
 * Fonction permettant de recevoir un tpi avec les champs qui sont modifiable par un admin
 * 
 * @param int $id id du TPI qu'on veut recevoir 
 * @return cTpi return true si Ok, false si problème
 */
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

/**
 * Fonction permettant de recevoir un tableau de TPI qui depuis un id de la session Expert
 * 
 * @return array[cTpi] return tableau de TPI si Ok, false si problème
 */
function getAllTpiByIdUserExpertSession()
{
    $id = getIdUserSession();
    $database = UserDbConnection();
    $query = $database->prepare("SELECT tpiID,year,userCandidateID,userManagerID, userExpert1ID, userExpert2ID, tpiStatus,pdfPath 
    FROM tpidbthh.tpis 
    WHERE userExpert1ID = :userExpert1ID OR userExpert2ID = :userExpert1ID;");
    $query->bindParam(":userExpert1ID", $id, PDO::PARAM_INT);
    if ($query->execute()) {
        $row = $query->fetchAll(PDO::FETCH_ASSOC);
        $arrTpi = array();
        for ($i = 0; $i < count($row); $i++) {
            $tpi = new cTpi();
            $tpi->id = $row[$i]['tpiID'];
            $tpi->year = $row[$i]['year'];
            $tpi->userCandidateId = $row[$i]['userCandidateID'];
            $tpi->userManagerId = $row[$i]['userManagerID'];
            $tpi->userExpertId = $row[$i]['userExpert1ID'];
            $tpi->userExpertId2 = $row[$i]['userExpert2ID'];
            $tpi->tpiStatus = $row[$i]['tpiStatus'];
            $tpi->pdfPath = $row[$i]['pdfPath'];
            array_push($arrTpi, $tpi);
        }
        return $arrTpi;
    }
    return false;
}

/**
 * Fonction permettant de recevoir un tableau de TPI qui depuis un id de la session Manager / Chef de projet
 * 
 * @return array[cTpi] return tableau de TPI si Ok, false si problème
 */
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

/**
 * Fonction permettant de modifier un TPI avec les champs modifiable par un admin
 *
 * @param cTpi $tpi Le Tpi qu'on veut modifier dans la base de donnée 
 * @return bool return true si Ok, false si problème
 */
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

/**
 * Fonction permettant de modifier un TPI avec les champs modifiable par un expert
 *
 * @param cTpi $tpi Le Tpi qu'on veut modifier dans la base de donnée 
 * @return bool return true si Ok, false si problème
 */
function modifyTpiByExpert($tpi)
{

    $database = UserDbConnection();
    $query = $database->prepare("UPDATE `tpidbthh`.`tpis` 
        SET `presentationDate` = :presentationDate
        WHERE (`tpiID` = :tpiID);");

    $query->bindParam(":presentationDate", $tpi->presentationDate, PDO::PARAM_STR);

    $query->bindParam(":tpiID", $tpi->id, PDO::PARAM_INT);


    if ($query->execute()) {
        return true;
    } else {
        return false;
    }
}

/**
 * Fonction permettant de modifier un TPI avec les champs modifiable par un Manager
 *
 * @param cTpi $tpi Le Tpi qu'on veut modifier dans la base de donnée 
 * @return bool return true si Ok, false si problème
 */
function modifyTpiByManager($tpi)
{
    try {
        $database = UserDbConnection();
        $database->beginTransaction();
        $query = $database->prepare("UPDATE `tpidbthh`.`tpis` 
        SET `title` = :title, `cfcDomain` = :cfcDomain, 
        `abstract` = :abstract, `sessionStart` = :sessionStart, 
        `sessionEnd` = :sessionEnd, `presentationDate` = :presentationDate, 
        `workplace` = :workplace, `description` = :description 
        WHERE (`tpiID` = :tpiID);");

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
                if ($cUpdate->criterionGroup != "" && $cUpdate->criterionNumber != "" && $cUpdate->criterionDescription != "") {
                    $cUpdate->tpiId = $tpi->id;
                    createEvaluationCriterion($cUpdate);
                }
            } else {
                foreach ($criterions as $c) {
                    if ($c->id == $cUpdate->id) {
                        updateCriterion($cUpdate);
                    }
                }
            }
        }
        $query->execute();
        $database->commit();
        return true;
    } catch (Exception $e) {
        $database->rollback();
        return false;
    }
}

/**
 * Fonction permettant de modifier un TPI avec les champs modifiable par un manager quand seulement la date peut être modifier
 *
 * @param cTpi $tpi Le Tpi qu'on veut modifier dans la base de donnée 
 * @return bool return true si Ok, false si problème
 */
function modifyTpiByManagerOnlyPresentationDate($tpi)
{
    $database = UserDbConnection();
    $query = $database->prepare("UPDATE `tpidbthh`.`tpis` 
        SET `presentationDate` = :presentationDate
        WHERE (`tpiID` = :tpiID);");

    $query->bindParam(":presentationDate", $tpi->presentationDate, PDO::PARAM_STR);

    $query->bindParam(":tpiID", $tpi->id, PDO::PARAM_INT);
    if ($query->execute()) {
        return true;
    } else {
        return false;
    }
}

/**
 * Fonction permettant de modifier de modifier un critère d'évalutation 
 *
 * @param cEvaluationCriterion $cUpdate critère qu'on veut mettre à jour
 * @return bool return true si Ok, false si problème
 */
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

/**
 * Fonction permettant de créer de modifier un critère d'évaluation 
 *
 * @param cEvaluationCriterion $cUpdate critère qu'on veut créer
 * @return bool return true si Ok, false si problème
 */
function createEvaluationCriterion($cUpdate)
{
    $database = UserDbConnection();
    $query = $database->prepare("INSERT INTO `tpidbthh`.`evaluation_criterions` 
    (`criterionGroup`, `criterionNumber`, `criterionDescription`, `tpiID`) 
    VALUES (:criterionGroup, :criterionNumber, :criterionDescription, :tpiID);");

    $query->bindParam(":criterionGroup", $cUpdate->criterionGroup, PDO::PARAM_STR);
    $query->bindParam(":criterionNumber", $cUpdate->criterionNumber, PDO::PARAM_STR);
    $query->bindParam(":criterionDescription", $cUpdate->criterionDescription, PDO::PARAM_STR);
    $query->bindParam(":tpiID", $cUpdate->tpiId, PDO::PARAM_STR);

    if ($query->execute()) {
        return true;
    } else {
        return false;
    }
}

/**
 * Fonction permettant de récupérer un tpi avec ses critère d'd'évaluation 
 *
 * @param int $id TPI qu'on veut récupérer avec ses critère
 * @return array[cEvaluationCriterion] return tableau critere du TPI si Ok, false si problème
 */
function getCriterionWithTpiId($id)
{
    $database = UserDbConnection();
    $query = $database->prepare("SELECT evaluationCriterionID, criterionGroup, criterionNumber, criterionDescription
     FROM `tpidbthh`.`evaluation_criterions` 
     WHERE (`tpiID` = :tpiID);");
    $query->bindParam(":tpiID", $id, PDO::PARAM_INT);

    if ($query->execute()) {
        $row = $query->fetchAll(PDO::FETCH_ASSOC);
        $arrEvaluationCriterions = array();
        if (count($row) == 0) {
            for ($i = 0; $i < 7; $i++) {
                $criterion = new cEvaluationCriterion();
                array_push($arrEvaluationCriterions, $criterion);
            }
            return $arrEvaluationCriterions;
        }


        for ($i = 0; $i < 7; $i++) {
            if (isset($row[$i]['criterionGroup'])) {
                $criterion = new cEvaluationCriterion();
                $criterion->id = $row[$i]['evaluationCriterionID'];
                $criterion->criterionGroup = $row[$i]['criterionGroup'];
                $criterion->criterionNumber = $row[$i]['criterionNumber'];
                $criterion->criterionDescription = $row[$i]['criterionDescription'];

                array_push($arrEvaluationCriterions, $criterion);
            } else {
                $criterion = new cEvaluationCriterion();
                array_push($arrEvaluationCriterions, $criterion);
            }
        }
        return $arrEvaluationCriterions;
    } else {
        return false;
    }
}

/**
 * Fonction permettant d'invalider un tpi 
 *
 * @param cTpi $tpi tpi qu'on veut invalider
 * @return bool return true si Ok, false si problème
 */
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

/**
 * Fonction permettant d'soumettre un tpi 
 *
 * @param cTpi $tpi tpi qu'on veut soumettre
 * @return bool return true si Ok, false si problème
 */
function submitTpi($tpi)
{
    $submissionDate = date("Y-m-d H:i:s");
    $pdfPath = createPdf($tpi, $submissionDate);
    if ($pdfPath) {
        $database = UserDbConnection();
        $query = $database->prepare("UPDATE `tpidbthh`.`tpis` SET `tpiStatus` = :tpiStatus, `pdfPath` = :pdfPath, `submissionDate` = :submissionDate WHERE (`tpiID` = :tpiID);");
        $query->bindValue(":tpiStatus", ST_SUBMITTED, PDO::PARAM_STR);
        $query->bindValue(":pdfPath", $pdfPath, PDO::PARAM_STR);
        $query->bindValue(":submissionDate", $tpi->submissionDate, PDO::PARAM_STR);
        $query->bindParam(":tpiID", $tpi->id, PDO::PARAM_STR);
        if ($query->execute()) {
            return true;
        } else {
            return false;
        }
    }
}


/**
 * Fonction permettant de supprimer un pdf 
 *
 * @param cTpi $tpi tpi qui contient le nom du pdf à supprimer
 * @return bool return true si Ok, false si problème
 */
function deletePdf($tpi)
{
    try {
        unlink(PATH_PDF . $tpi->pdfPath);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Fonction permettant de créer un pdf 
 *
 * @param cTpi $tpi tpi qui contient le nom du pdf à supprimer
 * @param cTpi $submissionDate date de soumission 
 * 
 * @return bool return true si Ok, false si problème
 */
function createPdf($tpi, $submissionDate)
{
    $tpi = getTpiByIdAllInfo($tpi->id);

    $manager = getUserById($tpi->userManagerId);
    $candidate = getUserById($tpi->userCandidateId);
    if ($tpi->userExpertId != null) {
        $expert1 = getUserById($tpi->userExpertId);
    } else {
        $expert1 = "";
    }
    if ($tpi->userExpertId2 != null) {
        $expert2 = getUserById($tpi->userExpertId2);
    } else {
        $expert2 = "";
    }

    $classe = getClasseByIdCandidat($tpi->userCandidateId);
    $tpi->submissionDate = $submissionDate;
    $dateTpi = getTimeAndDateToTpi($tpi);

    $tpi->evaluationCriterions = getCriterionWithTpiId($tpi->id);
    //"/var/www/html/TPI/php/modelspdf/Enonce_TPI_2020_76_Cart_Thibault.pdf"
    $html2pdf = new Html2Pdf();
    ob_start();
    require("php/includes/print_enonce.php");
    $var = ob_get_clean();
    $html2pdf->writeHTML($var);
    $name = "Enonce_TPI_" . $tpi->year . "_" . $tpi->id . "_" . $candidate->lastName . "_" . $candidate->firstName . ".pdf";
    $path = PATH_CREATEPDF . $name;
    $html2pdf->output($path, 'F');
    return $name;
}

/**
 * Fonction permettant de trouver un tpi dans un tableau de tpi 
 *
 * @param int $id id du tpi a trouver dans le tableau
 * @param array[cTpi] $arrTpi tableau de TPI
 * @return cTpi return TPI si Ok, false si problème
 */
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

/**
 * Fonction permettant de récupérer  un TPI avec ces media 
 *
 * @param int $id l'id du TPI qu'on veut recevoir 
 * @return cTpi return le Tpi si Ok, false si problème
 */
function getTpiByIdWithMedia($id)
{
    $database = UserDbConnection();
    $query = $database->prepare("SELECT t.tpiID,t.tpiStatus,m.mediaID,m.mediaPath 
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
        $tpi->id = $row[0]['tpiID'];
        $tpi->tpiStatus = $row[0]['tpiStatus'];
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

/**
 * Fonction permettant de supprimer un TPI
 *
 * @param cTpi $tpi le TPI qu'on veut supprimer 
 * @return bool return true si Ok, false si problème
 */
function deleteTpi($tpi)
{
    try {
        $database = UserDbConnection();
        deleteEvaluationCriterions($tpi);
        deleteAllMediaByTpiId($tpi);
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

/**
 * Fonction permettant de supprimer de critères d'un TPI
 *
 * @param cTpi $tpi le TPI dont on veut supprimer les critères
 * @return Exception return exception $e si problème
 */
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

/**
 * Fonction permettant de vérifier l'existence  d'une clée étrangère concernant un TPI
 *
 * @param cTpi $tpi le TPI dont on veut verifier
 * @param string $nameTable nom de la table a verifier
 * @return bool return true si true sinon false 
 */
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

/**
 * Fonction permettant de de récupérer  les dates et heures en tableau
 *
 * @param cTpi $tpi le TPI dont on veut récupérer  les heures et dates
 * @return array[string] return un tableau 2 dimensions avec les dates tpi 
 */
function getTimeAndDateToTpi($tpi)
{
    $sessionStart = explode(" ", $tpi->sessionStart);
    $sessionEnd = explode(" ", $tpi->sessionEnd);
    $presentation = explode(" ", $tpi->presentationDate);
    $submission = explode(" ", $tpi->submissionDate);

    if ($presentation[0] == "") {
        $presentation[] = "";
    }

    if ($sessionStart[0] == "") {
        $sessionStart[] = "";
    }

    if ($sessionEnd[0] == "") {
        $sessionEnd[] = "";
    }

    if ($submission[0] == "") {
        $submission[] = "";
    }

    $dateAndTimeTpi = array(
        "start"  => array("date" => $sessionStart[0], "time" => $sessionStart[1]),
        "end" => array("date" => $sessionEnd[0], "time" => $sessionEnd[1]),
        "presentation"   => array("date" => $presentation[0], "time" => $presentation[1]),
        "submission"   => array("date" => $submission[0], "time" => $submission[1])
    );
    return $dateAndTimeTpi;
}

/**
 * Fonction permettant de de récupérer  un formulaire  html pour admin
 *
 * @param cTpi $tpi le TPI dont on veut modifier
 * @param array[cUser] $arrUserManager tableau d’utilisateur manager
 * @param array[cUser] $arrUserExpert tableau d’utilisateur expert
 * @param array[cUser] $arrUserCandidat tableau d’utilisateur candidat
 * @return string return un formulaire html  
 */
function displayFormForAdminWithDisplayMessage($tpi, $arrUserManager, $arrUserExpert, $arrUserCandidat)
{
    $emptyExpert1 = false;
    $emptyExpert2 = false;

    $fullNameManager = getNameUserByIdByArray($tpi->userManagerId, $arrUserManager);
    $fullNameCandidat = getNameUserByIdByArray($tpi->userCandidateId, $arrUserCandidat);

    if ($tpi->userExpertId !== null) {
        $fullNameExpert1 = getNameUserByIdByArray($tpi->userExpertId, $arrUserExpert);
    } else {
        $fullNameExpert1 = "EXPERT 1";
    }

    if ($tpi->userExpertId2 !== null) {
        $fullNameExpert2 = getNameUserByIdByArray($tpi->userExpertId2, $arrUserExpert);
    } else {
        $fullNameExpert2 = "EXPERT 2";
    }

    $html = "<form class=\"toggle-class uk-flex uk-flex-center uk-background-muted uk-height-viewport\" action=\"modifyTPI.php?tpiId=" . $tpi->id . "\" method=\"POST\">";
    $html .= "<fieldset class=\"uk-fieldset uk-margin-medium-top\">";
    $html .= displayMessage();
    $html .= displayUserInSelect($fullNameManager, "selectManager", $arrUserManager, false, $tpi->userManagerId);
    $html .= displayUserInSelect($fullNameCandidat, "selectCandidat", $arrUserCandidat, false, $tpi->userCandidateId);
    $html .= displayUserInSelect($fullNameExpert1, "selectExpert1", $arrUserExpert, false, $tpi->userExpertId);
    $html .= displayUserInSelect($fullNameExpert2, "selectExpert2", $arrUserExpert, false, $tpi->userExpertId2);



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

/**
 * Fonction permettant de de récupérer  un formulaire  html pour expert
 *
 * @param cTpi $tpi le TPI dont on veut modifier
 * @return string return un formulaire html  
 */
function displayFormForExpertWithDisplayMessage($tpi)
{

    $arrDateTime = getTimeAndDateToTpi($tpi);

    $html = "<form class=\"toggle-class uk-flex uk-flex-center uk-background-muted uk-height-viewport\" action=\"modifyTPI.php?tpiId=" . $tpi->id . "\" method=\"POST\">";
    $html .= "<fieldset class=\"uk-fieldset uk-margin-medium-top\">";
    $html .= displayMessage();
    $html .=  "<div class=\"uk-margin-small\">";
    $html .=  "<div class=\"uk-inline uk-width-1-1\">";
    $html .=  "<label class=\"uk-form-label\" for=\"form-horizontal-text\">Date de la présentation du TPI :</label>";
    $html .=  "<span class=\"uk-form-icon uk-form-icon-flip\"></span>";
    if ($arrDateTime["presentation"]["date"] != "") {
        $html .=  "<input name=\"tbxDatePresentation\" value=" . $arrDateTime["presentation"]["date"] . " class=\"uk-input uk-border-pill\" type=\"date\">";
    } else {
        $html .=  "<input name=\"tbxDatePresentation\" class=\"uk-input uk-border-pill\" type=\"date\">";
    }
    $html .=  "</div>";
    $html .=  "</div>";
    $html .=  "<div class=\"uk-margin-small\">";
    $html .=  "<div class=\"uk-inline uk-width-1-1\">";
    $html .=  "<label class=\"uk-form-label\" for=\"form-horizontal-text\">Heure de la présentation du TPI :</label>";
    $html .=  "<span class=\"uk-form-icon uk-form-icon-flip\"></span>";
    if ($arrDateTime["presentation"]["time"] != "") {
        $html .=  "<input name=\"tbxTimePresentation\" value=" . $arrDateTime["presentation"]["time"] . " class=\"uk-input uk-border-pill\" type=\"time\">";
    } else {
        $html .=  "<input name=\"tbxTimePresentation\" class=\"uk-input uk-border-pill\" type=\"time\">";
    }
    $html .=  "</div>";
    $html .=  "</div>";
    $html .= "<div class=\"uk-margin-bottom\">";
    $html .= "<button name=\"btnModify\" value=\"Send\" type=\"submit\" class=\"uk-button uk-button-primary uk-border-pill uk-width-1-1\">Modifer le TPI</button>";
    $html .= "</div>";
    $html .= "</fieldset>";
    $html .= "</form>";

    return $html;
}

/**
 * Fonction permettant de de récupérer  un formulaire  html pour manager/ chef de projet
 *
 * @return string return un formulaire html  
 */
function displayFormForManagerWithDisplayMessage()
{

    ob_start();
    require("php/includes/formManager.php");
    return ob_get_clean();
}

/**
 * Fonction permettant de de récupérer  l'option pour la vue suivant le role à choisir
 *
 * @param array[string] $arrRole role de l'utilisateur
 * @param string $role role actuelle pour la vue choisit
 * @return string return les options html  
 */
function buttonChangeRoleListTpi($arrRole, $role)
{
    if (count($arrRole) > 1) {
        $html = "<div class=\"uk-margin-left uk-margin-top\">";
        $html .= "<div class=\"uk-margin uk-grid-small uk-child-width-auto uk-grid\">";
        foreach ($arrRole as $r) {
            if ($r == RL_ADMINISTRATOR) {
                if ($role == $r) {
                    $html .= "<label><input class=\"uk-radio\" type=\"radio\" value=" . RL_ADMINISTRATOR . " name=\"radioRole\" onchange=\"changeRole(" . RL_ADMINISTRATOR . ")\" checked> Liste TPI en tant qu'Administrateur</label>";
                } else {
                    $html .= "<label><input class=\"uk-radio\" type=\"radio\" value=" . RL_ADMINISTRATOR . " name=\"radioRole\" onchange=\"changeRole(" . RL_ADMINISTRATOR . ")\"> Liste TPI en tant qu'Administrateur</label>";
                }
            }
            if ($r == RL_EXPERT) {
                if ($role == $r) {
                    $html .= "<label><input class=\"uk-radio\" type=\"radio\" value=" . RL_EXPERT . " name=\"radioRole\" onchange=\"changeRole(" . RL_EXPERT . ")\" checked > Liste TPI en tant qu'Expert</label>";
                } else {
                    $html .= "<label><input class=\"uk-radio\" type=\"radio\" value=" . RL_EXPERT . " name=\"radioRole\" onchange=\"changeRole(" . RL_EXPERT . ")\" > Liste TPI en tant qu'Expert</label>";
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

/**
 * Fonction permettant de récupérer  un TPI d'un candidat
 *
 * @param string $id l'id du TPI qu'on veut recevoir 
 * @return cTpi return le Tpi si Ok, false si problème
 */
function getTpiByCandidateId($id)
{
    $database = UserDbConnection();

    $query = $database->prepare("SELECT sessionStart, tpiStatus,pdfPath FROM tpidbthh.tpis WHERE userCandidateID = :userCandidateID; LIMIT 1");
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

/**
 * Fonction permettant de concatener une date avec heure pour insertion dans base de donnée
 *
 * @param string $date la date
 * @param string $time l'heure
 * @return cTpi return la date et l'heure si Ok, false si problème
 */
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
