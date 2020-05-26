<?php
/**
 * Fichier des constantes
 */

 // Les rôles définis dans la base de données
 // @see ROLES table
define ('ERL_STUDENT', 1);
define ('ERL_ADMIN', 99);

/**
 * @brief Les constantes pour les contextes de la search bar
 */
define ('SB_CONTEXT_NONE', 0);
define ('SB_CONTEXT_ADS', 1);
define ('SB_CONTEXT_USER', 2);
define ('SB_CONTEXT_GESTION', 3);

 // Les statuts des annonces définis dans la base de données
 // @see STATUS table
define ('ADS_STATUS_ACTIVATED', 1);
define ('ADS_STATUS_DEACTIVATED', 0);
define ('ADS_STATUS_ALL', -1);
?>