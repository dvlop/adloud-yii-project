var ua;
//var url ='http://teasernetwork/';
//var url = 'http://cp.stage.adloud.net/';
var url = 'http://pre1.adloud.net/';

function Adloud_jsonp_callback(data, css, id){
    css = decodeURIComponent((css+'').replace(/\+/g, '%20'));
    css = JSON.parse(css);
    for(var i in css){
        var s = document.createElement('link');
        s.setAttribute('rel', 'stylesheet');
        s.setAttribute('href', css[i]);
        document.head.appendChild(s);
    }
    var block = document.getElementById('adloud-block-'+ id);
    if(typeof block == "undefined"){
        return;
    }
    data = decodeURIComponent((data+'').replace(/\+/g, '%20'));
    block.style.display = 'none';
    block.innerHTML = data + block.innerHTML;

    var int = setInterval(function(){
        var loadCheckElement = document.getElementsByClassName('adloud-load-check');
        if(typeof loadCheckElement[0] != "undefined"){
            var isLoaded = window.getComputedStyle(loadCheckElement[0]).display == 'none';
            if(isLoaded){
                block.style.display = 'block';
            }
        }
    }, 10)
}

var Adloud_init = function(data){

    var block = document.getElementById('adloud-block-'+ data.id);
    if(typeof block == "undefined"){
        return;
    }

    var scriptUa = document.createElement('script');

    scriptUa.src = url+"extensions/uaParser/ua-parser.min.js";
    document.documentElement.appendChild(scriptUa);

    function afterLoad() {
        var parser = new UAParser();

        var browser = parser.getBrowser();
        var os = parser.getOS();
        var device = parser.getDevice();

        if(device.vendor === 'Apple'){
            device.vendor = device.model;
            if(device.type === 'mobile'){
                if(device.vendor === 'iPhone'){
                    if(checkScreen(320,480)){
                        if(window.devicePixelRatio > 1){
                            device.model = '4/4S';
                        } else {
                            device.model = '3G';
                        }
                    }
                    if(checkScreen(320,568)){
                        device.model = '5/5S';
                    }
                } else if (device.vendor === 'iPod'){
                    if(window.devicePixelRatio > 1){
                        device.model = 'touch 4G';
                    } else {
                        device.model = 'touch 3G';
                    }
                    if(checkScreen(320,568)){
                        device.model = 'touch 5G';
                    }
                }
            } else if (device.type === 'tablet'){
                if(checkScreen(1024,768)){
                    if(window.devicePixelRatio > 1){
                        device.model = '3/4/Air';
                    } else {
                        device.model = 'Mini';
                    }
                }
            }
        }

        if(typeof device.vendor == 'undefined'){
            device.vendor = "PC";
            device.model = "PC";
        }

        var ua = {
            browser:browser.name,
            osname:os.name,
            osver:os.version,
            devname:device.vendor,
            devmodel:device.model
        };


        var res = getResolution();

        var script = document.createElement('script');
        script.src = url + "block.php?id="
            + data.id
            + "&type=" + data.type
            + "&colorScheme=" + data.colorScheme
            + "&backgroundScheme=" + data.backgroundScheme
            + "&allowAdult=" + data.allowAdult
            + "&allowShock=" + data.allowShock
            + "&allowSms=" + data.allowSms
            + "&browser=" + ua.browser
            + "&osname=" + ua.osname
            + "&osver=" + ua.osver
            + "&devname=" + ua.devname
            + "&devmodel=" + ua.devmodel
            + "&res=" + res;

        if(typeof data.num != 'undefined'){
            script.src += "&num=" + data.num;
        }
        script.charset = 'UTF-8';
        //script.setAttribute('async', 'async');
        var div = document.createElement('div');
        div.id = "Adloud_script-"+data.id;
        div.appendChild(script);
        block.appendChild(div);

        var iframeAdded = false;

        document.onmousemove=function(){
            if(!iframeAdded){
                var iframe = document.createElement('iframe');
                iframe.src = url + 'iframe.php';
                iframe.width = '0';
                iframe.height = '0';
                iframe.border = '0';
                iframe.id = 'adld-ifrm-hddn';
                iframe.style = 'display:none;';
                block.appendChild(iframe);
                iframeAdded = true;
            }
        };
    }

    scriptUa.onload = scriptUa.onerror = function() {
        if (!this.executed) {
            this.executed = true;
            window.ua = afterLoad();
        }
    };

    scriptUa.onreadystatechange = function() {
        var self = this;
        if (this.readyState == "complete" || this.readyState == "loaded") {
            setTimeout(function() { self.onload() }, 0);
        }
    };
};

var Adloud_new_init = function(data){
    var block = document.getElementById('adloud-block-'+ data.id);
    if(typeof block == "undefined"){
        return;
    }

    var scriptUa = document.createElement('script');

    scriptUa.src = url+"extensions/uaParser/ua-parser.min.js";
    document.documentElement.appendChild(scriptUa);

    function afterLoad() {
        var parser = new UAParser();

        var browser = parser.getBrowser();
        var os = parser.getOS();
        var device = parser.getDevice();

        if(device.vendor === 'Apple'){
            device.vendor = device.model;
            if(device.type === 'mobile'){
                if(device.vendor === 'iPhone'){
                    if(checkScreen(320,480)){
                        if(window.devicePixelRatio > 1){
                            device.model = '4/4S';
                        } else {
                            device.model = '3G';
                        }
                    }
                    if(checkScreen(320,568)){
                        device.model = '5/5S';
                    }
                } else if (device.vendor === 'iPod'){
                    if(window.devicePixelRatio > 1){
                        device.model = 'touch 4G';
                    } else {
                        device.model = 'touch 3G';
                    }
                    if(checkScreen(320,568)){
                        device.model = 'touch 5G';
                    }
                }
            } else if (device.type === 'tablet'){
                if(checkScreen(1024,768)){
                    if(window.devicePixelRatio > 1){
                        device.model = '3/4/Air';
                    } else {
                        device.model = 'Mini';
                    }
                }
            }
        }

        if(typeof device.vendor == 'undefined'){
            device.vendor = "PC";
            device.model = "PC";
        }

        var ua = {
            browser:browser.name,
            osname:os.name,
            osver:os.version,
            devname:device.vendor,
            devmodel:device.model
        };

        var res = getResolution();

    var script = document.createElement('script');
    script.src = url+"newBlock.php?id="
        + data.id
        + "&format=" + data.format
        + "&verticalCount=" + data.verticalCount
        + "&horizontalCount=" + data.horizontalCount
        + "&captionColor=" + data.captionColor
        + "&textColor=" + data.textColor
        + "&buttonColor=" + data.buttonColor
        + "&backgroundColor=" + data.backgroundColor
        + "&borderColor=" + data.borderColor
        + "&border=" + data.border
        + "&allowAdult=" + data.allowAdult
        + "&allowShock=" + data.allowShock
        + "&allowSms=" + data.allowSms
        + "&browser=" + ua.browser
        + "&osname=" + ua.osname
        + "&osver=" + ua.osver
        + "&devname=" + ua.devname
        + "&devmodel=" + ua.devmodel
        + "&res=" + res;

        script.charset = 'UTF-8';
        //script.setAttribute('async', 'async');
        var div = document.createElement('div');
        div.id = "Adloud_script-"+data.id;
        div.appendChild(script);
        block.appendChild(div);

        var iframeAdded = false;

        document.onmousemove=function(){
            if(!iframeAdded){
                var iframe = document.createElement('iframe');
                iframe.src = url + 'iframe.php';
                iframe.width = '0';
                iframe.height = '0';
                iframe.border = '0';
                iframe.id = 'adld-ifrm-hddn';
                iframe.style = 'display:none;';
                block.appendChild(iframe);
                iframeAdded = true;
            }
        };

    }

    scriptUa.onload = scriptUa.onerror = function() {
        if (!this.executed) {
            this.executed = true;
            window.ua = afterLoad();
        }
    };

    scriptUa.onreadystatechange = function() {
        var self = this;
        if (this.readyState == "complete" || this.readyState == "loaded") {
            setTimeout(function() { self.onload() }, 0);
        }
    };
};

var checkScreen = function(w,h){
    return window.screen.width == w && window.screen.height == h || window.screen.width == h && window.screen.height == w;
};

var getResolution = function(){
    return window.screen.width + 'x' + window.screen.height + "*" + window.devicePixelRatio;
}
