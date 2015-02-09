/**
 * Created with JetBrains PhpStorm.
 * User: JanGolle
 * Date: 03.09.14
 * Time: 11:40
 * To change this template use File | Settings | File Templates.
 */
var Adloud_target_init = function(data){
//    var mainUrl = 'http://pre1.adloud.net/';
    var mainUrl = 'http://cp.stage.adloud.net/';

    var iframe = document.createElement('iframe');
    iframe.id = 'adloud_tracker';
    iframe.width = '0px';
    iframe.height = '0px';
    iframe.style.border='0';
    iframe.src = mainUrl+"target.php?key="+data.key;
    document.documentElement.appendChild(iframe);
};