<?php
/*
 * File: mMessages.php
 * Author: Théo Hurlimann
 * Date: 26.05.2020
 * Description: Contient les fonctions utile pour un message
 * Version: 1.0 
*/
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
 * Fonction permettant de définir si les messages doivent être affiché
 *
 * @return bool Retourne true si le message doit être afficher ou false si non
 */
function doesDisplayMessage()
{
    if (isset($_SESSION['message']['doesDisplayMessage']))
        return $_SESSION['message']['doesDisplayMessage'];
}

/**
 * Fonction permettant de définir si les messages doivent être affiché
 *
 * @param [bool] $bool Bool si message doit être affiché ou non
 */
function setDisplayMessage($bool)
{
    $_SESSION['message']['doesDisplayMessage'] = $bool;
}

/**
 * Fonction permettant de récupérer  les messages dans un tableau pour affichage. SetDisplayMessage à false après affichage
 *
 * @return string Retourne un html qui contient des alertes
 */
function displayMessage()
{
    if (doesDisplayMessage()) {
        $arrMessage = $_SESSION["message"]["messages"];
        $html = "";

        for ($i = 0; $i < count($arrMessage); $i++) {
            $alert = "<div class=\"uk-alert-" . $arrMessage[$i]['type'] . "\" uk-alert>";
            $alert .= "<a class=\"uk-alert-close\" uk-close></a>";
            $alert .= "<p>" . $arrMessage[$i]['message'] . "</p>";
            $alert .= "</div>";
            $html .= $alert;
        }
        setDisplayMessage(false);
        resetMessages();
        return $html;
    }
}

/**
 * Fonction permettant de réinitialiser  les messages dans un tableau
 *
 */
function resetMessages()
{
    $_SESSION["message"]["messages"] = "";
}
