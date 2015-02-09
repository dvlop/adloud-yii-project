<?php
/**
 * Created by JetBrains PhpStorm.
 * User: JanGolle
 * Date: 03.09.14
 * Time: 17:58
 * To change this template use File | Settings | File Templates.
 */

require_once 'core'.DIRECTORY_SEPARATOR.'AutoLoader.php';
AutoLoader::register();

function isAjax(){
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}
?>

<?php if(!isAjax()):?>
<!DOCTYPE html>
<html>
<head>
    <meta charset=utf-8 />
    <title>Adloud Tracker</title>
    <script src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script type="text/javascript" src="extensions/evercookie/js/swfobject-2.2.min.js"></script>
    <script type="text/javascript" src="extensions/evercookie/js/evercookie.js"></script>
</head>
<body>
<script>
    var addTracker = function(data){
        var ec = new Evercookie({
//            baseurl: 'http://pre1.adloud.net',
            baseurl: 'http://cp.stage.adloud.net',
            asseturi: '/extensions/evercookie/assets',
            phpuri: '/extensions/evercookie/php'
        });
        ec.get("adloud_tkey", function(val){
            var codes = [];
            var newCode = '';

            if(!val){
                ec.set("adloud_tkey", String(data.key));
                incrementUser();
            } else {
                codes = val.split(', ');

                if(!inArray(codes,String(data.key))){
                    codes.push(String(data.key));
                    newCode = codes.join(', ');

                    ec.set("adloud_tkey", newCode);
                    incrementUser();
                }
            }
        });
    };

    var inArray = function(arr,obj) {
        return (arr.indexOf(obj) != -1);
    };

    var incrementUser = function(){
        $.ajax({
            type: "POST",
            data: "id=<?=strval($_GET['key'])?>",
            url: "target.php"
        });
    };

    addTracker(<?=json_encode(['key' => $_GET['key']])?>);
</script>
</body>
</html>

<?php
    else:
        if(isset($_POST['id']) && $_POST['id']){
            \core\RedisIO::incr("target-users:{$_POST['id']}");
        }
    endif;
?>