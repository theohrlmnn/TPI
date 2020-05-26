<?php
session_start();
/**
 * INCLUDE DE LA CONNEXION A LA BDD
 */
require_once 'server/databaseInformations.php';
require_once 'server/databaseConnection.php';

/**
 * INCLUDE DES MODELS
 */
require_once 'models/mUsers.php';
require_once 'models/mSessions.php';


/**
 * INCLUDE DES CONSTANTES
 */
require_once 'constants.php';

/**
 * INCLUDE DES CONTAINERS
 */
require_once 'containers/cEvaluationCriterion.php';
require_once 'containers/cMedia.php';
require_once 'containers/cTpi.php';
require_once 'containers/cUser.php';
?>