<?php

/**
 * Fonction permettant de définir des messages dans un tableau
 *
 * @param [array[string]] $arrMessages contient des messages et un type d'alerte 
 */
function setMessage($arrMessages)
{
    $_SESSION["message"]["messages"] = $arrMessages;
}

/**
 * Fonction permettant de définir des messages dans un tableau
 *
 * @return bool Retourne true si le message doit être afficher ou false si non
 */
function doesDisplayMessage()
{
    if(isset($_SESSION['message']['doesDisplayMessage']))
        return $_SESSION['message']['doesDisplayMessage'];
}

/**
 * Fonction permettant de définir des messages dans un tableau
 *
 * @param [bool] $nool Bool si message doit être affiché ou non
 */
function setDisplayMessage($bool)
{
    $_SESSION['message']['doesDisplayMessage'] = $bool;
}

/**
 * Fonction permettant de définir des messages dans un tableau
 * Met automatique à false avec setDisplayMessage(false)
 *
 * @return string Retourne un html qui contient des alertes
 */
function displayMessage()
{
    $arrMessage = $_SESSION["message"]["messages"];
    $html = "";

    for ($i=0; $i < count($arrMessage) ; $i++) { 
        $alert = "<div class=\"uk-alert-" . $arrMessage[$i]['type'] . "\" uk-alert>";
        $alert .= "<a class=\"uk-alert-close\" uk-close></a>";
        $alert .= "<p>".$arrMessage[$i]['message'] ."</p>";
        $alert .= "</div>";
        $html .= $alert;
    }
    setDisplayMessage(false);
    return $html;
}
?>