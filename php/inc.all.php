<?php
session_start();
/**
 * INCLUDE DE LA CONNEXION A LA BDD
 */
require_once $_SERVER['DOCUMENT_ROOT'].'server/databaseInformations.php';
require_once $_SERVER['DOCUMENT_ROOT'].'server/databaseConnection.php';

/**
 * INCLUDE DES MODELS
 */

require_once $_SERVER['DOCUMENT_ROOT'].'models/mUsers.php';


/**
 * INCLUDE DES CONSTANTES
 */
require_once $_SERVER['DOCUMENT_ROOT'].'/php/includes/constants.php';

/**
 * INCLUDE DES CONTAINERS
 */
require_once $_SERVER['DOCUMENT_ROOT'].'containers/cMedia.php';
require_once $_SERVER['DOCUMENT_ROOT'].'containers/cEvaluationCriterions.php';
require_once $_SERVER['DOCUMENT_ROOT'].'containers/cTpi.php';
require_once $_SERVER['DOCUMENT_ROOT'].'containers/cUser.php';
?>