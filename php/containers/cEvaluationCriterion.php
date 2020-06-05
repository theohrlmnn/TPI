<?php
/*
 * File: cEvaluationCriterion.php
 * Author: Théo Hurlimann
 * Date: 25.05.2020
 * Description: Contient les informations utile pour un critère d'évaluation
 * Version: 1.0 
*/
/**
 * La classe cEvaluationCriterions contient les informations complémentaire à un critère d'évaluation
 * Ex: Nombre, description, Tpi associé, etc.
 */
class cEvaluationCriterion{

     /**
     * @brief   Class Constructor avec paramètres par défaut pour construire l'objet
     */
    public function __construct($InEvaluationCriterionId = -1, $InCriterionGroup = "", $InCriterionNumber = "", $InCriterionDescription = "", $InTpiId = ""){
        $this->id = $InEvaluationCriterionId;
        $this->criterionGroup = $InCriterionGroup;
        $this->criterionNumber = $InCriterionNumber;
        $this->criterionDescription = $InCriterionDescription;
        $this->tpiId = $InTpiId;
    }
    /** @var [int] Id unique du critère d'évaluation */
    public $id;

    /** @var [ENUM('A', 'B', 'C')] Groupe de correction du critère d'évaluation*/
    public $criterionGroup;

    /** @var [int] Numéro du critère d'évaluation */
    public $criterionNumber;

    /** @var [string] Description du critère d'évaluation */
    public $criterionDescription;

    /** @var [int] TPI associé au critère d'évaluation */
    public $tpiId;

}

?>