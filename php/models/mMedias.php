<?php
/*
 * File: mMedias.php
 * Author: Théo Hurlimann
 * Date: 26.05.2020
 * Description: Contient les fonctions utile pour un media
 * Version: 1.0 
*/
require_once("php/inc.all.php");

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

function getFormatMedia($type)
{
    $arrType = explode("/", $type);

    if ($arrType[0] != "image") {
        return null;
    }
    return $arrType[0];
}

function getRealExtensionMedia($type)
{
    $arrType = explode("/", $type);
    return $arrType[1];
}
