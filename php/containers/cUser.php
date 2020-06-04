<?php
/*
 * File: cUser.php
 * Author: Théo Hurlimann
 * Date: 25.05.2020
 * Description: Contient les informations utile pour un Utilisateur
 * Version: 1.0 
*/
/**
 * La classe cUser contient les informations complémentaire à un utilisateur
 * Ex: nom, prénom, email, etc.
 */
class cUser{

     /**
     * @brief   Class Constructor avec paramètres par défaut pour construire l'objet
     */
    public function __construct($InUserId = -1,$InlastName = "", $InfirstName = "", $InCompagnyName = "", $InAddress = "", $InEmail = "", $InPhone = "",$InRole = array(),$InRight = array()){
        $this->id = $InUserId;
        $this->lastName = $InlastName;
        $this->firstName = $InfirstName;
        $this->compagnyName = $InCompagnyName;
        $this->adress = $InAddress;
        $this->email = $InEmail;
        $this->phone = $InPhone;
        $this->role = $InRole;
        $this->right = $InRight;
    }
    
    /** @var [int] Id unique de l'utilisateur */
    public $id;

    /** @var [string] Nom famille de l'utilisateur */
    public $lastName;

    /** @var [string] Prénom de l'utilisateur */
    public $firstName;

    /** @var [string] Entreprise de l'utilisateur */
    public $compagnyName;

    /** @var [string] Adresse de l'utilisateur */
    public $adress;

    /** @var [string] Email de l'utilisateur */
    public $email;

    /** @var [string] Numéro de téléphonne de l'utilisateur */
    public $phone;

    /** @var [string] Tableau du role de l'utilisateur */
    public $role;

    /** @var [string] Tableau de droit de l'utilisateur */
    public $right;
}

?>