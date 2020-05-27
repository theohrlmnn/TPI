<?php
require_once("php/inc.all.php");

if (!islogged()) {

    $messages = array(
        array("message" => "Vous devez être connecté pour voir ceci.", "type" => AL_Danger)
    );
    setMessage($messages);
    setDisplayMessage(true);

    header('Location: login.php');
    exit;
}

$arrRoles = getRoleUserSession();
$highRole = min($arrRoles);


switch ($highRole) {
    case RL_Administrator:
        $arrTpi = getAllTpi();
        break;
    case RL_Expert:

        break;
    case RL_Manager:

        break;
    case RL_Nobody:
        $messages = array(
            array("message" => "Vous ne pouvez pas voir la liste de TPI.", "type" => AL_Warning)
        );
        setMessage($messages);
        setDisplayMessage(true);

        header('Location: home.php');
        exit;
        break;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste TPI</title>
    <!-- CSS FILES -->
    <link rel="stylesheet" type="text/css" href="css/uikit.css">
    <link rel="stylesheet" href="css/cssNavBar.css">
</head>

<body>
    <?php include_once("php/includes/nav.php");
    echo displayTPIAdmin($arrTpi);
    ?>
    <!-- JS FILES -->
    <script src="js/uikit.js"></script>
    <script src="js/uikit-icons.js"></script>
</body>

</html>