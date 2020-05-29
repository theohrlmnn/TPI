<?php
/*
 * File: inc.all.php
 * Author: Théo Hurlimann
 * Date: 15.05.2020
 * Description: Contient les fichiers utile pour le bon fonctionnement du projet
 * Version: 1.0 
*/
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
require_once 'models/mMessages.php';
require_once 'models/mTpis.php';
require_once 'models/mMedias.php';

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

session_start();

setIfLogged();
?>