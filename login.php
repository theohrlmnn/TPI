<?php
/*
 * File: listTPI.php
 * Author: Théo Hurlimann
 * Date: 26.05.2020
 * Description: Page pour la connection
 * Version: 1.0 
*/
require_once("php/inc.all.php");


$btnLogin = filter_input(INPUT_POST, "btnLogin");
$email = "";

if (islogged()) {
	$role = min(getRoleUserSession());
	if ($role == RL_CANDIDATE) {
		header('Location: viewPDF.php');
		exit;
	}
	header('Location: home.php');
	exit;
}

if ($btnLogin) {
	$email = filter_input(INPUT_POST, "tbxEmail", FILTER_SANITIZE_STRING);
	$pswd = filter_input(INPUT_POST, "tbxPswd", FILTER_SANITIZE_STRING);



	if ($email != "" && $pswd != "") {
		$u = new cUser();
		$u->email = $email;
		if (signin($u, $pswd)) {
			$role = min(getRoleUserSession());
			$messages = array(
				array("message" => "Bienvenu " . getFirstNameUserSession(), "type" => AL_SUCESS)
			);
			setMessage($messages);
			setDisplayMessage(true);
			if ($role == RL_CANDIDATE) {
				header('Location: viewPDF.php');
				exit;
			}

			header('Location: home.php');
			exit;
		} else {
			$messages = array(
				array("message" => "Problème de connexion.", "type" => AL_DANGER)
			);
			setMessage($messages);
			setDisplayMessage(true);
		}
	} else {
		$messages = array(
			array("message" => "Veuillez remplir les champs.", "type" => AL_DANGER)
		);
		setMessage($messages);
		setDisplayMessage(true);
	}
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login - UIkit 3 KickOff</title>
	<link rel="icon" href="img/favicon.ico">
	<!-- CSS FILES -->
	<link rel="stylesheet" type="text/css" href="css/uikit.css">
</head>

<body class="uk-flex uk-flex-center uk-flex-middle uk-background-muted uk-height-viewport" data-uk-height-viewport>
	<div class="uk-width-medium uk-padding-small">
		<?php
		echo displayMessage();
		?>
		<!-- login -->
		<form class="toggle-class" action="login.php" method="POST">
			<fieldset class="uk-fieldset">
				<div class="uk-margin-small">
					<div class="uk-inline uk-width-1-1">
						<span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: user"></span>
						<input name="tbxEmail" value="<?= $email ?> " class="uk-input uk-border-pill" required placeholder="Email" type="email">
					</div>
				</div>
				<div class="uk-margin-small">
					<div class="uk-inline uk-width-1-1">
						<span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: lock"></span>
						<input name="tbxPswd" class="uk-input uk-border-pill" required placeholder="Password" type="password">
					</div>
				</div>
				<div class="uk-margin-bottom">
					<button name="btnLogin" value="Send" type="submit" class="uk-button uk-button-primary uk-border-pill uk-width-1-1">LOG IN</button>
				</div>
			</fieldset>
		</form>
		<!-- /login -->
	</div>

	<!-- JS FILES -->
	<script src="js/uikit.js"></script>
	<script src="js/uikit-icons.js"></script>
</body>

</html>