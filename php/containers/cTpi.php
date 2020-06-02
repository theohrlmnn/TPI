<?php
/*
 * File: cTpi.php
 * Author: Théo Hurlimann
 * Date: 25.05.2020
 * Description: Contient les informations utile pour un TPI
 * Version: 1.0 
*/
/**
 * La classe cTpi contient les informations complémentaire à un TPI
 * Ex: Année, Titre, description, etc.
 */
class cTpi{

     /**
     * @brief   Class Constructor avec paramètres par défaut pour construire l'objet
     */
    public function __construct($InTpiId = -1,$InYear = null, $InUserCandidateId = null, $InUserManagerId = null,
        $InUserExpertId = null, $InUserExpertId2 = null, $InTpiStatus = null, $InTitle = null, $InCfcDomain = null,
        $InAbstract = null, $InSessionStart = null, $SessionEnd = null, $InPresentationDate = null, $InWorkplace = null, 
        $InDescription = null, $InSubmissionDate = null, $InPdfPath = null,$InEvaluationCriterions = array(),$InMedias = array()){
        $this->id = $InTpiId;
        $this->year = $InYear;
        $this->userCandidateId = $InUserCandidateId;
        $this->userManagerId = $InUserManagerId;
        $this->userExpertId = $InUserExpertId;
        $this->userExpertId2 = $InUserExpertId2;
        $this->tpiStatus = $InTpiStatus;
        $this->title = $InTitle;
        $this->cfcDomain = $InCfcDomain;
        $this->abstract = $InAbstract;
        $this->sessionStart = $InSessionStart;
        $this->sessionEnd = $SessionEnd;
        $this->presentationDate = $InPresentationDate;
        $this->workplace = $InWorkplace;
        $this->description = $InDescription;
        $this->submissionDate = $InSubmissionDate;
        $this->pdfPath = $InPdfPath;
        $this->evaluationCriterions = $InEvaluationCriterions;
        $this->medias = $InMedias;
    }
    /** @var [int] Id unique du TPI */
    public $id;

    /** @var [string] Année du TPI */
    public $year;

    /** @var [int] Candidat au TPI */
    public $userCandidateId;

    /** @var [int] Chef de projet / Professeur / Manager du TPI */
    public $userManagerId;

    /** @var [int] Expert 1 du TPI */
    public $userExpertId;

    /** @var [int] Expert 2 du TPI */
    public $userExpertId2;

    /** @var [ENUM('draft', 'submitted', 'valid')] Statut du TPI */
    public $tpiStatus;

    /** @var [string] Titre du TPI */
    public $title;

    /** @var [string] Domaine cfc du TPI */
    public $cfcDomain;

    /** @var [string] Resumé du TPI */
    public $abstract;

    /** @var [string] Date de début du TPI */
    public $sessionStart;

    /** @var [string] Date de fin du TPI */
    public $sessionEnd;

    /** @var [string] Date de présentation du TPI */
    public $presentationDate;

    /** @var [string] Lieu de travail pour le TPI */
    public $workplace;

    /** @var [string] Descrition du TPI */
    public $description;

    /** @var [string] Date de rendu du TPI */
    public $submissionDate;

    /** @var [string] Chemin vers le pdf */
    public $pdfPath;

    /** @var [cEvaluationCriterion] Tableau de critères du TPI */
    public $evaluationCriterions;
    
    /** @var [cEvaluationCriterion] Tableau de media du TPI */
    public $medias;
}
