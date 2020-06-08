<?php
/*
 * File: mSessions.php
 * Author: Théo Hurlimann
 * Date: 26.05.2020
 * Description: Contient les fonctions utile pour une session
 * Version: 1.0 
*/

/**
 * Fonction permettant de définir si un utilisateur est n'est pas connecté
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
    if (isset($_SESSION['user']['isLogged']))
        return $_SESSION['user']['isLogged'];
    else {

        return false;
    }
}

/**
 * Fonction permettant de récupérer l'id du TPI dans la session
 *
 */
function getIdTpiSession()
{
    return $_SESSION['tpi']['id'];
}

/**
 * Fonction permettant d'envoyer l'id du TPI dans la session
 *
 */
function setIdTpiSession($id)
{
    $_SESSION['tpi']['id'] = $id;
}

/**
 * Fonction permettant de connaitre le prénom d'un utilisateur si il est connecté
 *
 * @return string Retourne le prénom
 */
function getFirstNameUserSession()
{
    if (isset($_SESSION['user']['userLogged']))
        return $_SESSION['user']['userLogged']->firstName;
}

/**
 * Fonction permettant de connaitre le nom d'un utilisateur si il est connecté
 *
 * @return string Retourne le prénom
 */
function getLastNameUserSession()
{
    if (isset($_SESSION['user']['userLogged']))
        return $_SESSION['user']['userLogged']->lastName;
}

/**
 * Fonction permettant de connaitre le id d'un utilisateur si il est connecté
 *
 * @return string Retourne l'id
 */
function getIdUserSession()
{
    if (isset($_SESSION['user']['userLogged']))
        return $_SESSION['user']['userLogged']->id;
}

/**
 * Fonction permettant de connaitre le rôle  d'un utilisateur si il est connecté
 *
 * @return array Retourne le rôle de l'utilisateur connnecté
 */
function getRoleUserSession()
{
    if (isset($_SESSION['user']['userLogged']))
        return $_SESSION['user']['userLogged']->role;
}

/**
 * Fonction permettant de connaitre les droits d'un utilisateur si il est connecté
 *
 * @return array Retourne le tableau droit
 */
function getRightUserSession()
{
    if (isset($_SESSION['user']['userLogged']))
        return $_SESSION['user']['userLogged']->right;
}

/**
 * Fonction permettant de connaitre le nom du/des rôle d'un utilisateur 
 *
 * @return array Retourne le tableau de rôle
 */
function getRoleNameUserSession()
{
    $role = $_SESSION['user']['userLogged']->role;
    $arrayNameRole = array();

    foreach ($role as $r) {
        switch ($r) {
            case RL_ADMINISTRATOR:
                array_push($arrayNameRole,"Administrateur");
                break;
            case RL_EXPERT:
                array_push($arrayNameRole,"Expert");
                break;
            case RL_MANAGER:
                array_push($arrayNameRole,"Chef de projet");
                break;
            case RL_CANDIDATE:
                array_push($arrayNameRole,"Candidat");
                break;
            default:
                return "";
                break;
        }
    }
    return $arrayNameRole;
    
}

/**
 * Fonction permettant de détruire la session 
 *
 */
function destroySession()
{
    session_destroy();
}
