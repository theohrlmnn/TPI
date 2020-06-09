<?php
require_once("php/inc.all.php");


if (!islogged()) {

    $messages = array(
        array("message" => "Vous n'avez pas les droits pour ceci.", "type" => AL_DANGER)
    );
    setMessage($messages);
    setDisplayMessage(true);

    header('Location: login.php');
    exit;
}

$id = getIdTpiSession();


$uploadOk = true;

//Je prends les informations utiles de $_FILES
$name = $_FILES["image" . $id]["name"];
$tmpName = $_FILES["image" . $id]["tmp_name"];
$target_file = $_FILES["image" . $id]["name"];



//J'identifie le type du media ainsi que sa réelle extension
$type_extension = mime_content_type($tmpName);
$type = getFormatMedia($type_extension);
$fileExtension = getRealExtensionMedia($type_extension);
$newName = uniqid();


//$newName = changeMediaName();
$target_file = $newName . "." . $fileExtension;

if ($type == null) {
    $uploadOk = false;
}

if ($uploadOk) {
    if (move_uploaded_file($tmpName, PATH_MEDIA . $target_file)) {
        $media = new cMedia();
        $media->originalName = $name;
        $media->mediaPath = $target_file;
        $media->mimeType = $type_extension;
        $media->tpiId = $id;

        if (addMedia($media)) {

            $data['success'] = true;
            $data['link'] = PATH_MEDIA . $media->mediaPath;

            header('Content-type:application/json;charset=utf-8');
            echo json_encode($data);
        } else {
            unlink(PATH_MEDIA . $media->mediaPath);
            $data['success'] = false;
        }
    } else {
        $data['success'] = false;
    }
}

    
