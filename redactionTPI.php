<?php
require_once("php/inc.all.php");

$arrRole = getRoleUserSession();
if (!islogged() || $arrRole[0]  == RL_CANDIDATE) {

    $messages = array(
        array("message" => "Vous n'avez pas les droits pour voir ceci.", "type" => AL_DANGER)
    );
    setMessage($messages);
    setDisplayMessage(true);

    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redaction TPI</title>
    <!-- CSS FILES -->
    <link rel="stylesheet" type="text/css" href="css/uikit.css">
    <link rel="stylesheet" href="css/cssNavBar.css">
</head>

<body>
    <?php include_once("php/includes/nav.php");
    echo displayMessage();
    ?>
    <div class="uk-container uk-margin uk-marin-top ">
        <article class="uk-article  ">

            <h1 class="uk-article-title"><a class="uk-link-reset" href="">Collège d'Experts Informatique de Genève</a></h1>

            <h4 class="uk-text-large"><a class="uk-link-reset" href="">La rédaction des énoncés des TPIs</a></h4>

            <p>Ce module permet de gérer les contenus des énoncés de TPIs, l’attribution des TPIs aux experts et la
                relation entre apprentis, experts et profs/chefs de projet. Les énoncés peuvent inclure des images</p>
            <p>Cette partie du développement a été développé par Hurlimann Théo</p>
        </article>
    </div>
    <script src="js/uikit.js"></script>
    <script src="js/uikit-icons.js"></script>
</body>

</html>