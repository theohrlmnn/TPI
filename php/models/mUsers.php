<?php
/*
 * File: mSessions.php
 * Author: Théo Hurlimann
 * Date: 26.05.2020
 * Description: Contient les fonctions utile pour un utilisateur
 * Version: 1.0 
*/
require_once("php/inc.all.php");

/**
 * Fonction permettant de connecter un user avec son mot de passe haché et son email
 *
 * @param [User] $u L'objet du user voulant se connecter
 * @param [string] $pswd Mot de passe
 * 
 * @return bool true si ok, false si problème
 */
function signIn($u, $pswd)
{
    $database = UserDbConnection();

    $query = $database->prepare("SELECT userID,lastName,firstName,companyName,address,email,phone,pwdHash,pwdSalt FROM tpidbthh.users WHERE SHA1(CONCAT(:pswd,pwdSalt)) = pwdHash AND :email = email LIMIT 1 ;");
    $query->bindParam(":pswd", $pswd, PDO::PARAM_STR);
    $query->bindParam(":email", $u->email, PDO::PARAM_STR);


    if ($query->execute()) {
        $row = $query->fetchAll(PDO::FETCH_ASSOC);

        if (count($row) != 0) {

            $u->id = $row[0]["userID"];
            $u->lastName = $row[0]["lastName"];
            $u->firstName = $row[0]["firstName"];
            $u->companyName = $row[0]["companyName"];
            $u->address = $row[0]["address"];
            $u->phone = $row[0]["phone"];

            /*$query = $database->prepare("SELECT * FROM tpidbthh.user_roles WHERE userID = :id;");
            $query->bindParam(":id", $u->id, PDO::PARAM_STR);*/
            $query = $database->prepare("SELECT * FROM tpidbthh.user_rights WHERE userID = :id;");
            $query->bindParam(":id", $u->id, PDO::PARAM_STR);

            if ($query->execute()) {
                $row = $query->fetchAll(PDO::FETCH_ASSOC);
                /*$arrRole = array();
                for ($i = 0; $i < count($row); $i++) {
                    $role = $row[$i]["roleID"];
                    array_push($arrRole, $role);
                }
                $u->role = $arrRole;*/

                foreach ($row as $r) {
                    $right = $r["rightName"];
                    array_push($u->right, $right);
                }
                $query = $database->prepare("SELECT * FROM tpidbthh.user_roles WHERE userID = :id;");
                $query->bindParam(":id", $u->id, PDO::PARAM_STR);
                if ($query->execute()) {
                    $row = $query->fetchAll(PDO::FETCH_ASSOC);
                    
                    for ($i = 0; $i < count($row); $i++) {
                        $role = $row[$i]["roleID"];
                        array_push($u->role, $role);
                    }
                    
                }

                setSessionUser($u);
                return true;
            }
        }
        return false;
    }
}

/**
 * Fonction permettant de récuperer tous les users d'un role
 *
 * @param [string] $roleID Role
 * 
 * @return array[cUser] tableau si ok, false si problème
 */
function getAllUserByRole($roleID)
{
    $arrUser = array();
    $database = UserDbConnection();

    $query = $database->prepare("SELECT users.userID, users.lastName, users.firstName, user_roles.roleID FROM user_roles , users WHERE user_roles.userID = users.userID AND user_roles.roleID =:roleID;");
    $query->bindParam(":roleID", $roleID, PDO::PARAM_STR);

    if ($query->execute()) {
        $row = $query->fetchAll(PDO::FETCH_ASSOC);
        for ($i = 0; $i < count($row); $i++) {
            $u = new cUser();
            $u->id = $row[$i]["userID"];
            $u->lastName = $row[$i]["lastName"];
            $u->firstName = $row[$i]["firstName"];
            $u->role = $row[$i]["roleID"];
            array_push($arrUser, $u);
        }
        return $arrUser;
    }
    return false;
}

/**
 * Fonction permettant de récupérer le nom et le prénom dans bon format
 *
 * @param [string] $id de l'utilisateur
 * @param [array[cUser]] $arrUser Tableau d'utilisateur
 * 
 * @return string return prénom et nom si ok, false si problème
 */
function getNameUserByIdByArray($id, $arrUser)
{
    foreach ($arrUser as $user) {
        if ($user->id == $id) {
            return $user->firstName . " " . $user->lastName;
        }
    }

    return false;
}

/**
 * Fonction permettant de récupérer l'utilisateur avec un id
 *
 * @param [string] $id de l'utilisateur
 * 
 * @return cUser return cUser ok, false si problème
 */
function getUserById($id)
{
    $arrUser = array();
    $database = UserDbConnection();

    $query = $database->prepare("SELECT lastName, firstName, companyName, address, email, phone FROM users WHERE userID = :userID");
    $query->bindParam(":userID", $id, PDO::PARAM_STR);

    if ($query->execute()) {
        $row = $query->fetchAll(PDO::FETCH_ASSOC);

        $u = new cUser();
        $u->lastName = $row[0]["lastName"];
        $u->firstName = $row[0]["firstName"];
        $u->compagnyName = $row[0]["companyName"];
        $u->address = $row[0]["address"];
        $u->email = $row[0]["email"];
        $u->phone = $row[0]["phone"];

        return $u;
    }
    return false;
}

/**
 * Fonction permettant de récupérer la classe d'un candidat
 *
 * @param [string] $id de l'utilisateur
 * 
 * @return string return classe ok, false si problème
 */
function getClasseByIdCandidat($id)
{
    $database = UserDbConnection();

    $query = $database->prepare("SELECT className
    FROM tpidbthh.classes as c 
    LEFT JOIN user_classes as uc
    ON uc.ClassID = c.classID
    WHERE uc.userCandidateID = :userCandidateID");
    $query->bindParam(":userCandidateID", $id, PDO::PARAM_STR);
    if ($query->execute()) {
        $row = $query->fetch(PDO::FETCH_ASSOC);
        return $row["className"];
    }
    return false;
}