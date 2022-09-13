<?php

/**
 * Redirects the browser to the given location
 */
function redirect($iocation)
{
    header("Location: {$iocation}");
    exit;
}

function uploadProfile()
{
    $uploadsDir = dirname(__FILE__) . "./public/uploads/profiles/";
    $name = "";
    $tmpPath = "";
    $newPath = "";

    if (!is_dir($uploadsDir)) {
        if (!mkdir($uploadsDir, 0777, true)) {
            return null;
        }
    }

    if (isset($_FILES["profile_picture"]) && ($_FILES["profile_picture"]["error"] === UPLOAD_ERR_OK)) {
        $name = basename($_FILES["profile_picture"]["name"]);
        $tmpPath  = $_FILES["profile_picture"]["tmp_name"];
        $newPath =  $uploadsDir . $name;
    } else {
        $name = basename($uploadsDir . "user-profile.png");
        $newPath = $uploadsDir . $name;
    }

    if (!file_exists($newPath)) {
        move_uploaded_file($tmpPath, $newPath);
    }

    return $name;
}

function formatParams($arr)
{
    $newArr = array();
    foreach ($arr as $key => $value) $newArr[":{$key}"] = $value;
    return $newArr;
}
