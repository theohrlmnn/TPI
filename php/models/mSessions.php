<?php

 /**
 * Fonction permettant de définir si un utilisateur est connecté ou non
 *
 */
function setIfLogged()
{
    if (!isset($_SESSION['user']['userLogged'])) 
        $_SESSION['user']['isLogged'] = false;
    
}

 /**
 * Fonction permettant de d'envoyer les données d'un utilisateur dans une session et d'infomrer qu'il est connecté
 *
 * @param [User] $u L'objet du user voulant se connecter
 */
 function setSessionUser($u)
 {
    $_SESSION['user']['userLogged'] = $u;
    $_SESSION['user']['isLogged'] = true;
 }

 /**
 * Fonction permettant de savoir si un utilisateur est connecté
 *
 * @return bool true si oui, false si non
 */
function isLogged()
{
    return $_SESSION['user']['isLogged'];
}

 /**
 * Fonction permettant de connaitre le prénom d'un utilisateur si il est connecté
 *
 * @return string Retourne le prénom
 */
function getFirstNameUserSession()
{
    if (!isset($_SESSION['user']['userLogged'])) 
        return $_SESSION['user']['userLogged']->firstName;
}
?>