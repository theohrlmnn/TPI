<?php
/*
 * File: mMedias.php
 * Author: Théo Hurlimann
 * Date: 26.05.2020
 * Description: Contient les fonctions utile pour un media
 * Version: 1.0 
*/
require_once("php/inc.all.php");

/**
 * Fonction permettant de récupérer d'ajouter un media
 *
 * @param cMedia $media media qu'on veut ajouter dans la base de donnée
 * @return bool return true si Ok, false si problème
 */
function addMedia($media)
{
    $database = UserDbConnection();
    $query = $database->prepare("INSERT INTO `tpidbthh`.`medias` (`originalName`, `mediaPath`, `mimeType`, `tpiID`) 
    VALUES (:originalName, :mediaPath, :mimeType, :tpiID);");

    $query->bindParam(":originalName", $media->originalName, PDO::PARAM_INT);
    $query->bindParam(":mediaPath", $media->mediaPath, PDO::PARAM_INT);
    $query->bindParam(":mimeType", $media->mimeType, PDO::PARAM_INT);
    $query->bindParam(":tpiID", $media->tpiId, PDO::PARAM_INT);

    if ($query->execute()) {
        return true;
    }
    return false;

}

/**
 * Fonction permettant de supprimer tous les media d'un TPI via son Id
 *
 * @param cTpi $tpi tpi qui contient l'id
 * @return bool return $e Exception si problème
 */
function deleteAllMediaByTpiId($tpi)
{
    try {
        $database = UserDbConnection();
        $query = $database->prepare("DELETE FROM `tpidbthh`.`medias` WHERE (`tpiID` = :tpiID);");
        $query->bindParam(":tpiID", $tpi->id, PDO::PARAM_INT);
        $query->execute();
    } catch (Exception $e) {
        return $e;
    }
}

/**
 * Fonction permettant de verifier le type du media est une image
 *
 * @param string $type mime du media
 * @return string return "image" si oui, null si non
 */
function getFormatMedia($type)
{
    $arrType = explode("/", $type);

    if ($arrType[0] != "image") {
        return null;
    }
    return $arrType[0];
}

/**
 * Fonction permettant de déterminier le réel extension du media
 *
 * @param string $type mime du media
 * @return string return l'exentsion
 */
function getRealExtensionMedia($type)
{
    $arrType = explode("/", $type);
    return $arrType[1];
}
