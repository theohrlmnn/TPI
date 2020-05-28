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
 * Fonction permettant de récuperer un user avec son mot de passe haché et son email pour se connecter
 *
 * @param [User] $u L'objet du user voulant se connecter
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

            $query = $database->prepare("SELECT * FROM tpidbthh.user_roles WHERE userID = :id;");
            $query->bindParam(":id", $u->id, PDO::PARAM_STR);

            if ($query->execute()) {
                $row = $query->fetchAll(PDO::FETCH_ASSOC);
                $arrRole = array();
                for ($i = 0; $i < count($row); $i++) {
                    $role = $row[$i]["roleID"];
                    array_push($arrRole, $role);
                }

                $u->role = $arrRole;

                setSessionUser($u);
                return true;
            }
        }
        return false;
    }
}

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

function getNameUserByRoleByArray($id, $arrUser)
{
    foreach ($arrUser as $user) {
        if ($user->id == $id) {
            return $user->firstName. " " . $user->lastName; 
        }
    }

    return false;
}