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
    <title>Home</title>
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

            <h4 class="uk-text-large"><a class="uk-link-reset" href="">Outil de collaboration et de gestion des TPIs</a></h4>

            <p>Le but de cette application est de donner aux membres du collège d'experts en informatique
                du canton de Genève un outil leur permettant de gérer leur travail tout au long de l'année scolaire.
                Le développement a été confié a des élèves de l'école d'informatique, dans le cadre de leur TPI.
                Les modules développés initialement sont :</p>
            <ul>
                <li>L'administration des utilisateurs</li>
                <li>La rédaction des énoncés des TPIs</li>
                <li>La répartition des TPIs entre les experts</li>
                <li>La validation des énoncés</li>
                <li>L'évaluation des TPis</li>
            </ul>
            <p>Cette partie du développement concerne le module de la rédaction des énoncés des TPIs, développé par Hurlimann Théo</p>
        </article>
    </div>
    <script src="js/uikit.js"></script>
    <script src="js/uikit-icons.js"></script>
</body>

</html>