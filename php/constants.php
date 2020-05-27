<?php
/**
* Fichier des constantes
*/

// Les rôles définis dans la base de données
// @see roles table
define ('RL_Nobody', 5);
define ('RL_Candidate', 4);
define ('RL_Manager', 3);
define ('RL_Expert', 2);
define ('RL_Administrator', 1);

// Les types définis d'alerte pour uilkit
define ('AL_Primary', 'primary');
define ('AL_Sucess', 'sucess');
define ('AL_Warning', 'warning');
define ('AL_Danger', 'danger');

// Les différents etat d'un TPI
define ('ST_Draft','draft');
define ('ST_Submitted','submitted');
define ('ST_Valid','valid');
?>