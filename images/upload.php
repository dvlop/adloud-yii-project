<?php

if(empty($_FILES)){
    die(json_encode(array('error' => 0)));
}

$config = json_decode(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'config.json'), true);

if(!in_array(getimagesize($_FILES["file"]["tmp_name"])['mime'], $config['allowedFileTypes'])){
    die(json_encode(array('error' => 2)));
}

if($_FILES["file"]["size"] > $config['maxSize']){
    die(json_encode(array('error' => 1)));
}

$folder = prepareFolder($config);

switch(getimagesize($_FILES["file"]["tmp_name"])['mime']) {
    case 'image/gif':
        $extension = '.gif';
        break;

    case 'image/jpeg':
        $extension = '.jpeg';
        break;

    case 'image/jpg':
    case 'image/pjpeg':
        $extension = '.jpg';
        break;

    case 'image/x-png':
    case 'image/png':
        $extension = '.png';
        break;

    default:
        $extension = '';
}

$fileName = prepareFileName($_FILES["file"]["name"] . $extension, $folder);

move_uploaded_file($_FILES["file"]["tmp_name"], $folder . $fileName);

die(json_encode(array('image' => str_replace(__DIR__ , '', $folder . $fileName))));

function prepareFolder(array $config){
    $folderPath = __DIR__ . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR;
    for($i = 0; $i < $config['deep']; $i++){
        $folderPath .= rand(1, $config['folderCount']);
        if(!file_exists($folderPath)){
            mkdir($folderPath);
            chmod($folderPath, 0777);
        }
        $folderPath .= DIRECTORY_SEPARATOR;
    }
    return $folderPath;
}

function prepareFileName($fileName, $folder){
    $data = explode('.', $fileName);
    $fileName = $data[0];
    $fileExtension = end($data);
    do {
        $fileName = md5($fileName);
        $preparedFileName =  $fileName . '.' .$fileExtension;
        $fileExists = file_exists($folder . $preparedFileName);
    } while ($fileExists);
    return $preparedFileName;
}