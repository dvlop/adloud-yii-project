<?php

date_default_timezone_set('UTC');

if(!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)){
    die();
}

$adsTypes = ['160x600','200x300','200x600', '240x400', '240x600', '240x4400', '300x250', '300x600', '336x280', '468x60', '600x300', '640x300', '728x90'];
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
//$formats = \models\Block::$_formats;

$types = \models\Block::getSplitTestTypesByFormat($id);
$splitType = $id;
if(!$types){
    $types = \models\Block::getSplitTestTypesByFormat($_GET['type']);
    $splitType = $_GET['type'];
}

$formatSplitTest = false;

if($types){
    $formatSplitTest = true;
    $format = $types[rand(0, count($types) - 1)];
}else{
    $format = \models\Block::FORMAT_ADS_STANDARD;
}

$settings = [
    'type' => $_GET['type'],
    'format' => $format,
    'backgroundScheme' => $_GET['backgroundScheme'],
    'colorScheme' => $_GET['colorScheme'],
    'allowAdult' => $allowAdult,
    'allowShock' => $allowShock,
    'num' => isset($_GET['num']) ? $_GET['num'] : null
];

$additionalSettings = [
    'device' => $_GET['devname'],
    'deviceModel' => $_GET['devmodel'],
    'os' => $_GET['osname'],
    'osVer' => $_GET['osver'],
    'browser' => $_GET['browser']
];

$retargetSettings = [
    'targets' => $_COOKIE['adloud_tkey']
];

if(isset($_GET['browser'])){
    $settings = array_merge($settings, $additionalSettings);
}

if(isset($_COOKIE['adloud_tkey'])){
    $settings = array_merge($settings, $retargetSettings);
}

list($css, $teasers) = $renderer->render($id, $settings);
$size = explode('x', $_GET['type']);
$teasers = urlencode($teasers) ;
$css = json_encode($css) ;
$script = "
    Adloud_jsonp_callback('{$teasers}', '{$css}', {$id});
";
\core\RedisIO::incr("block-type-shows:{$format}_{$_GET['type']}_{$id}");

if($formatSplitTest){
    \core\RedisIO::incr("block-type-shows:{$format}_{$splitType}");
}

echo $script;

if(isset($_GET['browser'])){
    checkUserAgent([
        'browser' => [
            'name' => $_GET['browser'],
            'value' => null
        ],
        'os' => [
            'name' => $_GET['osname'],
            'value' => $_GET['osver']
        ],
        'device' => [
            'name' => $_GET['devname'],
            'value' => $_GET['devmodel'],
            'res' => $_GET['res']
        ],
    ]);
}

function checkUserAgent(array $params){
    $db = \core\PostgreSQL::getInstance()->getConnection(\core\Session::getInstance(), 'actual_data');

    $getSql = "SELECT
                    *
               FROM
                    user_agent
               WHERE
                    (type = 'browser' AND name = :browser)
               OR (type = 'os' AND name = :osname AND value = :osver)";
    if(isApple($params['device'])){
        $getSql .= " OR (type = 'device' AND name = :devname AND value = :devmodel AND resolution = :res)";
    } else {
        $getSql .= " OR (type = 'device' AND name = :devname AND value = :devmodel)";
    }

    if(!$params['device']['res'] && isApple($params['device']))
        $params['device']['res'] = 'undefined';

    $statement = $db->prepare($getSql);
    $statement->bindParam(':browser', $params['browser']['name'], \PDO::PARAM_STR);
    $statement->bindParam(':osname', $params['os']['name'], \PDO::PARAM_STR);
    $statement->bindParam(':osver', $params['os']['value'], \PDO::PARAM_STR);
    $statement->bindParam(':devname', $params['device']['name'], \PDO::PARAM_STR);
    $statement->bindParam(':devmodel', $params['device']['value'], \PDO::PARAM_STR);
    if(isApple($params['device'])){
        $statement->bindParam(':res', $params['device']['res'], \PDO::PARAM_STR);
    }
    $statement->execute();

    $get_result = $statement->fetchAll(\PDO::FETCH_ASSOC);

    foreach($get_result as $res){
        \core\RedisIO::incr("user-agent:{$res['id']}");
        unset($params[$res['type']]);
    }

    foreach($params as $key => $ua){
        insertTargetItem($key, $ua, $db);
    }
}

function insertTargetItem($type, $item, $db){
    $sql = 'INSERT INTO user_agent ('.(isApple($item) ? 'resolution, ' : '' ).'value, name, type, is_checked)
            VALUES ('.(isApple($item) ? ':res, ' : '' ).':value, :name, :type, FALSE)';

    $statement = $db->prepare($sql);
    $params = [
        ':value' => $item['value'],
        ':name' => $item['name'],
        ':type' => $type
    ];
    if(isApple($item)){
        $params[':res'] = $item['res'];
    }
    $statement->execute($params);
}

function isApple($item){
    return $item['name'] == "iPhone" || $item['name'] == "iPad" || $item['name'] == "iPod";
}
