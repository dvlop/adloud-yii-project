<?php
namespace core;
use core\clickFilter\ClickErrors;
use core\clickFilter\CookieFilter;
use core\clickFilter\FilterHandler;
use core\clickFilter\RefererFilter;
use core\clickFilter\SpentTimeFilter;

date_default_timezone_set('UTC');
if(!isset($_GET['url'])){
    die();
}

$url = base64_decode($_GET['url']);

if(!filter_var($url, FILTER_VALIDATE_URL)){
    die();
}

function sendResponse($url)
{
    $html = '<!DOCTYPE html>';
    $html .= '<html>';
    $html .= '<head></head>';
    $html .= '<body>';

    $html .= '<script>';
    $html .= 'window.location.href="'.$url.'"';
    $html .= '</script>';

    $html .= '</body>';
    $html .= '</html>';

    echo $html;
    exit();
}

if(!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)){
    sendResponse($url);
}

if(!isset($_GET['block']) || !filter_var($_GET['block'], FILTER_VALIDATE_INT)){
    sendResponse($url);
}

$id = $_GET['id'];

$block = $_GET['block'];

require_once 'core' . DIRECTORY_SEPARATOR . 'AutoLoader.php';

\AutoLoader::register();
$blockData = ClickHandler::getBlockData($block);
$adsData = ClickHandler::getAdsData($id);

if($blockData && $adsData){
    //$url .= (strpos($url,'?') !== false ? '&' : '?') . "utm_medium=adloud&utm_source={$blockData['siteId']}&utm_campaign={$adsData['campaignId']}&utm_content={$id}";
} else {
    sendResponse($url);
}

$clickFilterHandler = new FilterHandler();
$clickFilterHandler->setAdsData($adsData);
$clickFilterHandler->setBlockData($blockData);
$clickFilterHandler->setViewerSession(ViewerSession::getInstance());

$url = isset($adsData['content']['url']) ? $adsData['content']['url'] : $url;
$url = str_replace('[site_id]',$blockData['siteId'], $url );
$url = str_replace('[ad_id]',$id, $url );
$url = str_replace('[camp_id]',$adsData['campaignId'], $url );
//$clickFilterHandler->addFilter(new IpFilterWorker(new IpWorker(),$_SERVER['REMOTE_ADDR'], 5));
$clickFilterHandler->addFilter(new SpentTimeFilter());
$clickFilterHandler->addFilter(new RefererFilter());
$clickFilterHandler->addFilter(new CookieFilter());
$result = $clickFilterHandler->filter();

if($result !== ClickErrors::OK){
    \core\RedisIO::incr("block-click-errors:{$blockData['id']}:{$result}");
    sendResponse($url);
}

ClickHandler::handle($adsData, $blockData);

\core\RedisIO::incr("block-type-clicks:{$_GET['type']}_{$block}");
if(\models\Block::getSplitTestTypesByFormat($_GET['type'])){
    \core\RedisIO::incr("block-type-clicks:{$_GET['type']}");
}

sendResponse($url);