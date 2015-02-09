<?php

date_default_timezone_set('UTC');

if(!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)){
    die();
}

$adsTypes = ['160x600','200x300','200x600', '240x400', '240x600', '300x250', '300x600', '336x280', '468x60', '600x300', '640x300', '728x90'];
$colorSchemes = ['black', 'cheerful', 'france', 'greeny', 'juice', 'purple', 'sky', 'standart', 'surf'];
$backgroundSchemes = ['border', 'gray', 'white'];
$allowAdult = isset($_GET['allowAdult']) ? (bool) $_GET['allowAdult'] == 'true' :  false;
$allowShock = isset($_GET['allowShock']) ? (bool) $_GET['allowShock'] == 'true'  :  false;

if(!isset($_GET['type']) || !in_array($_GET['type'], $adsTypes)){
    die();
}

if(!isset($_GET['colorScheme']) || !in_array($_GET['colorScheme'], $colorSchemes)){
    die();
}

if(!isset($_GET['backgroundScheme']) || !in_array($_GET['backgroundScheme'], $backgroundSchemes)){
    die();
}


$id = $_GET['id'];

require_once 'core'.DIRECTORY_SEPARATOR.'AutoLoader.php';

AutoLoader::register();

$renderer = \models\BlockRenderer::getInstance();

list($css, $teasers) = $renderer->render($id, [
    'type' => $_GET['type'],
    'backgroundScheme' => $_GET['backgroundScheme'],
    'colorScheme' => $_GET['colorScheme'],
    'allowAdult' => $allowAdult,
    'allowShock' => $allowShock
]);

$size = explode('x', $_GET['type']);
$teasers = urlencode($teasers) ;
$css = urlencode(json_encode($css)) ;
$script = "
    Adloud_jsonp_callback('{$teasers}', {$css});
";

echo $script;