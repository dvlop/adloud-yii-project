<?php

date_default_timezone_set('UTC');

if(!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT))
    die();
$id = $_GET['id'];

require_once 'core'.DIRECTORY_SEPARATOR.'AutoLoader.php';

AutoLoader::register();

$formats = \models\Block::getNewFormats();

if(!isset($_GET['format']) || !in_array($_GET['format'], $formats))
    die();
$format = $_GET['format'];

$settings = [
    'format' => $format,
    'verticalCount' => isset($_GET['verticalCount']) ? (int)$_GET['verticalCount'] : 1,
    'horizontalCount' => isset($_GET['horizontalCount']) ? (int)$_GET['horizontalCount'] : 1,
    'captionColor' => isset($_GET['captionColor']) ? (string)$_GET['captionColor'] : null,
    'textColor' => isset($_GET['textColor']) ? (string)$_GET['textColor'] : null,
    'buttonColor' => isset($_GET['buttonColor']) ? (string)$_GET['buttonColor'] : null,
    'backgroundColor' => isset($_GET['backgroundColor']) ? (string)$_GET['backgroundColor'] : null,
    'borderColor' => isset($_GET['borderColor']) ? (string)$_GET['borderColor'] : null,
    'border' => isset($_GET['border']) ? (int)$_GET['border'] : 1,
    'allowAdult' => isset($_GET['allowAdult']) ? $_GET['allowAdult'] == 'true' :  false,
    'allowShock' => isset($_GET['allowShock']) ? $_GET['allowShock'] == 'true'  :  false,
    'allowSms' => isset($_GET['allowSms']) ? $_GET['allowSms'] == 'true'  :  false,
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

$renderer = \models\BlockRenderer::getInstance();

list($css, $teasers) = $renderer->newRender($id, $settings);

$teasers = urlencode($teasers);
$css = json_encode($css);

$script = "
    Adloud_jsonp_callback('{$teasers}', '{$css}', {$id});
";

//\core\RedisIO::incr("block-type-shows:{$splitFormat}_{$format}_{$id}");
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
