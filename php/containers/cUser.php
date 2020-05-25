<?php

/**
 * La classe cUser contient les informations complémentaire à un utilisateur
 * Ex: nom, prénom, email, etc.
 */
class cUser{

     /**
     * @brief   Class Constructor avec paramètres par défaut pour construire l'objet
     */
    public function __construct($InUserId = -1,$InlastName = "", $InfirstName = "", $InCompagnyName = "", $InAddress = "", $InEmail = "", $InPhone = ""){
        $this->id = $InUserId;
        $this->lastName = $InlastName;
        $this->firstName = $InfirstName;
        $this->compagnyName = $InCompagnyName;
        $this->adress = $InAddress;
        $this->email = $InEmail;
        $this->phone = $InPhone;
    }
    /** @var [int] Id unique de l'utilisateur */
    public $id;

    /** @var [string] Nom famille de l'utilisateur */
    public $lastName;

    /** @var [int] Prénom de l'utilisateur */
    public $firstName;

    /** @var [int] Entreprise de l'utilisateur */
    public $compagnyName;

    /** @var [int] Adresse de l'utilisateur */
    public $adress;

    /** @var [int] Email de l'utilisateur */
    public $email;

    /** @var [string] Numéro de téléphonne de l'utilisateur */
    public $phone;

}

?>