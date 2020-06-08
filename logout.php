<?php
/*
 * File: listTPI.php
 * Author: Théo Hurlimann
 * Date: 26.05.2020
 * Description: Page pour deconnexion de la liste des TPIS
 * Version: 1.0 
*/
require_once("php/inc.all.php");
foreach ($arrRight as $r) {
	if ($r == "logout") {
		destroySession();
		header('Location: login.php');
		exit;
	}
}
$messages = array(
	array("message" => "Vous ne pouvez pas vous déconnecter", "type" => AL_DANGER)
);
setMessage($messages);
setDisplayMessage(true);
header('Location: login.php');
exit;
