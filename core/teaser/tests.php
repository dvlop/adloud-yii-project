<?php
include "../AutoLoader.php";
AutoLoader::register();

$help = "usage: ";

if(!isset($argv[1])){
    die($help);
}

try{
    switch($argv[1]){
        case 'createUser':
            createUsers(isset($argv[2]) ? intval($argv[2]) : 1);
            break;
        case 'createCampaign':
            if(!isset($argv[2])){
                die('invalid param');
            }
            createCampaigns(intval($argv[2]), isset($argv[3]) ? intval($argv[3]) : intval($argv[2]));
            break;
        case 'createAds':
            if(!isset($argv[2])){
                die('invalid param');
            }
            createAds(intval($argv[2]), isset($argv[3]) ? intval($argv[3]) : intval($argv[2]));
            break;
        case 'publishAds':
            if(!isset($argv[2])){
                die('invalid param');
            }
            publishAds(intval($argv[2]), isset($argv[3]) ? intval($argv[3]) : intval($argv[2]));
            break;
        case 'unPublishAds':
            if(!isset($argv[2])){
                die('invalid param');
            }
            publishAds(intval($argv[2]), isset($argv[3]) ? intval($argv[3]) : intval($argv[2]), true);
            break;
        case 'setMoneyBalance':
            if(!isset($argv[2])){
                die('invalid param');
            }
            setMoneyBalance(intval($argv[2]), isset($argv[3]) ? intval($argv[3]) : intval($argv[2]));
            break;
        case 'addBlocks':
            if(!isset($argv[2])){
                die('invalid param');
            }
            addBlocks(intval($argv[2]), isset($argv[3]) ? intval($argv[3]) : intval($argv[2]));
            break;
        case 'startBlocksRendering':
            if(!isset($argv[2])){
                die('invalid param');
            }
            startBlocksRendering(intval($argv[2]), isset($argv[3]) ? intval($argv[3]) : intval($argv[2]));
            break;
        default:
            die($help);

    }
} catch (Exception $e){
    die($e->getMessage());
}





function createUsers($num){
    for($i = 0; $i < $num; $i++){
        $user = \models\User::getInstance();
        $user->email = randomString(5) . '@' . randomString(5) . '.ru';
        $user->fullName = randomString(5);
        $user->password = '1234';
        $id = $user->save();

        echo "user id:{$id} created \n";
    }
}

function createCampaigns($userStartId, $userEndId){
    for($userId = $userStartId; $userId <= $userEndId; $userId++){
        \core\Session::getInstance()->setUserId($userId);
        for($count = 0; $count < rand(5, 20); $count++){
            $campaign = \models\Campaign::getInstance();
            $campaign->description = randomString(10);
            $campaign->clickPrice = rand(1, 20) / 100;
            $categories = [];
            $campaign->categories = getRandomCategories();
            $id = $campaign->save();
            echo "user campaign:{$id} created \n";
        }
    }
}

function createAds($userStartId, $userEndId){
    for($userId = $userStartId; $userId <= $userEndId; $userId++){
        \core\Session::getInstance()->setUserId($userId);
        $campaignList = \models\Campaign::getInstance()->getList();
        foreach($campaignList as $campaign){
            for($count = 0; $count < rand(3, 15); $count++){
                $ads = \models\Ads::getInstance();
                $ads->text = randomString(rand(3, 10)) . ' ' . randomString(rand(3, 10)) . ' ' . randomString(rand(3, 10));
                $ads->url = 'http://google.com';
                $ads->campaignId = $campaign['id'];
                $categories = [];
                $ads->additionalCategories = getRandomCategories();
                $ads->clickPrice =  rand(1, 20) / 100;
                $ads->imageLocalPath = 'd:\pW64n6CRn4Y.jpg';
                $id = $ads->save();
                echo "ads:{$id} created \n";
            }
        }
    }
}

function publishAds($userStartId, $userEndId, $unpublish = false){
    for($userId = $userStartId; $userId <= $userEndId; $userId++){
        \core\Session::getInstance()->setUserId($userId);
        $campaignList = \models\Campaign::getInstance()->getList();
        foreach($campaignList as $campaign){
            if($unpublish){
                \models\Publisher::getInstance()->unPublishCampaign($campaign['id']);
            } else {
                \models\Publisher::getInstance()->publishCampaign($campaign['id']);
            }

            echo "campaign:{$campaign['id']} published \n";
        }
    }
}

function addBlocks($userStartId, $userEndId){
    for($userId = $userStartId; $userId <= $userEndId; $userId++){
        \core\Session::getInstance()->setUserId($userId);
        for($j = 0; $j < rand(1,3);$j++){
            $site = \models\Site::getInstance();
            $site->description = randomString(10);
            $site->categories = getRandomCategories();
            $site->url = 'http://google.com';
            $siteId = $site->save();
            for($i = 0; $i < rand(1,3);$i++){
                $block = \models\Block::getInstance();
                $block->categories = getRandomCategories();
                $block->adsNumberRows = rand(2, 6);
                $block->adsNumberColumns = rand(2, 6);
                $block->siteId = $siteId;
                $block->description = randomString(10);
                $id = $block->save();
                \models\Publisher::getInstance()->publishBlock($id);
                echo "block:{$id} created \n";
            }
        }

    }
}

function setMoneyBalance($userStartId, $userEndId){
    for($userId = $userStartId; $userId <= $userEndId; $userId++){
        \core\Session::getInstance()->setUserId($userId);
        $user = \models\User::getInstance();
        $user->initById($userId);
        $user->addMoneyBalance(rand(100,1000), 'money income');
    }
}

function startBlocksRendering($firstBlockId, $lastBlockID){
    while(true){
        $start = microtime();
        \models\BlockRenderer::getInstance()->render(rand($firstBlockId, $lastBlockID));
        echo microtime() - $start . "\n";
    }
}

function getRandomCategories(){
    $categories = [];
    for($i = 1; $i < rand(2, 4); $i++){
        $cat = rand(1, 28);
        $cat = ($cat == 3 || $cat == 15) ? 1 : $cat;
        $categories[] = $cat;
    }
    return $categories;
}

function randomString($length){
    $string = md5(microtime());
    return substr($string, 0, $length);
}