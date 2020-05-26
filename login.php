<?php
require_once("php/inc.all.php");

$btnLogin = filter_input(INPUT_POST, "btnLogin");

if (islogged()) {
	header('Location: home.php');
exit;
}

if ($btnLogin) {
	$email = filter_input(INPUT_POST, "tbxEmail", FILTER_SANITIZE_STRING);
	$pswd = filter_input(INPUT_POST, "tbxPswd", FILTER_SANITIZE_STRING);

	$u = new cUser();
	$u->email = $email;

	if (signin($u,$pswd)) {
		
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
			<!-- login -->
			<form class="toggle-class" action="login.php" method="POST">
				<fieldset class="uk-fieldset">
					<div class="uk-margin-small">
						<div class="uk-inline uk-width-1-1">
							<span class="uk-form-icon uk-form-icon-flip" data-uk-icon="icon: user"></span>
							<input name="tbxEmail" class="uk-input uk-border-pill" required placeholder="Email" type="text">
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
		<script src="https://cdn.jsdelivr.net/npm/uikit@latest/dist/js/uikit.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/uikit@latest/dist/js/uikit-icons.min.js"></script>
	</body>
</html>