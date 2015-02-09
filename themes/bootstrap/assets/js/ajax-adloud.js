$.fn.ajaxAdloud = function(attributes){
    var type = 'POST';
    var url = $(this).attr('data-url');
    var data = $(this).attr('data-list');
    var dataType = 'json';
    var cache = true;
    var beforeSend = function(){};
    var success = function(json){};
    var complete = function(){};
    var error = function(){};
    var updateId = '#ajax-update-id';

    if(typeof attributes != 'undefined'){
        if(typeof attributes.type != 'undefined')
            type = attributes.type;
        if(typeof attributes.url != 'undefined')
            url = attributes.url;
        if(typeof attributes.data != 'undefined')
            data = attributes.data;
        if(typeof attributes.dataType != 'undefined')
            dataType = attributes.dataType;
        if(typeof attributes.cache != 'undefined')
            cache = attributes.cache;
        if(typeof attributes.beforeSend != 'undefined')
            beforeSend = attributes.beforeSend;
        if(typeof attributes.success != 'undefined')
            success = attributes.success;
        if(typeof attributes.complete != 'undefined')
            complete = attributes.complete;
        if(typeof attributes.error != 'undefined')
            error = attributes.error;
        if(typeof attributes.updateId != 'undefined')
            updateId = attributes.updateId;
    }

    if(typeof data == 'undefined')
        data = 'name=value';

    $.ajax({
        type: type,
        url: url,
        data: data,
        dataType: dataType,
        cache: cache,
        beforeSend: function(){
            Main.ajaxBefore();
            beforeSend();
        },
        success: function(json){
            Main.ajaxSuccess(json, updateId);
            success(json);
        },
        complete: function(){
            Main.ajaxAfter();
            complete();
        },
        error: function(XMLHttpRequest, textStatus, errorThrown){
            Main.ajaxError(errorThrown);
            error();
        }
    });
}
