<?php
/*
 * File: listTPI.php
 * Author: Théo Hurlimann
 * Date: 26.05.2020
 * Description: Page pour deconnexion de la liste des TPIS
 * Version: 1.0 
*/
require_once("php/inc.all.php");

destroySession();
header('Location: login.php');
	exit;
?>