<?php

/**
 * La classe cMedia contient les informations complémentaire à un media
 * Ex: nom original, chemin, Tpi associé, etc.
 */
class cMedia{

     /**
     * @brief   Class Constructor avec paramètres par défaut pour construire l'objet
     */
    public function __construct($InMediaId = -1,$InOriginalName = "", $InMediaPath = "", $InMimeType = "", $InTpiId = ""){
        $this->id = $InMediaId;
        $this->originalName = $InOriginalName;
        $this->mediaPath = $InMediaPath;
        $this->mimeType = $InMimeType;
        $this->tpiID = $InTpiId;
    }
    /** @var [int] Id unique du media */
    public $id;

    /** @var [string] Nom original du media */
    public $originalName;

    /** @var [int] Chemin du media */
    public $mediaPath;

    /** @var [int] Type du mime du media */
    public $mimeType;

    /** @var [int] TPI associé au media */
    public $tpiID;

}

?>