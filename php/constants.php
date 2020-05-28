<?php
/*
* File: constants.php
* Author: Théo Hurlimann
* Date: 15.05.2020
* Description: Contient les constantes utiles pour le projet
* Version: 1.0 
*/
/**
* Fichier des constantes
*/

// Les rôles définis dans la base de données
// @see roles table
define ('RL_NOBODY', 5);
define ('RL_CANDIDATE', 4);
define ('RL_MANAGER', 3);
define ('RL_EXPERT', 2);
define ('RL_ADMINISTRATOR', 1);

// Les types définis d'alerte pour uilkit
define ('AL_PRIMARY', 'primary');
define ('AL_SUCESS', 'sucess');
define ('AL_WARNING', 'warning');
define ('AL_DANGER', 'danger');

// Les différents etat d'un TPI
define ('ST_DRAFT','draft');
define ('ST_SUBMITTED','submitted');
define ('ST_VALID','valid');

//Les différents chemin utiles
define ('PATH_PDF','pdf/');
define ('PATH_MEDID','medias/');
?>