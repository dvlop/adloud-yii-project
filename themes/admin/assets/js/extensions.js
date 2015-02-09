
$.fn.ajx = function(attributes){
    var type = 'POST';
    var url = $(this).data('url');
    var data = $(this).data('params');
    var dataType = 'json';
    var cache = true;
    var beforeSend = function(){};
    var success = function(json){};
    var complete = function(){};
    var error = function(){};
    var updateId = $(this).data('update');
    var parent = $(this).data('parent');
    var closest = $(this).data('closest');
    var formId = $(this).data('form');
    var addId = $(this).data('add');

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
        if(typeof attributes.formId != 'undefined')
            formId = attributes.formId;
        if(typeof attributes.addId != 'undefined')
            addId = attributes.addId;
    }

    if(typeof updateId == 'undefined'){
        if(typeof parent != 'undefined')
            updateId = $(this).parent(parent);
        if(typeof closest != 'undefined')
            updateId = $(this).closest(closest);
    }

    if(typeof data == 'undefined')
        data = 'name=value';

    if(typeof formId != 'undefined' && formId){
        var form;

        if(formId == 1)
            form = $(this).closest('form');
        else
            form = $(formId);

        if(form.length)
            data += '&'+form.serialize();
    }

    $.ajax({
        type: type,
        url: url,
        data: data,
        dataType: dataType,
        cache: cache,
        beforeSend: function(){
            Main.beforeAjax();
            beforeSend();
        },
        success: function(json){
            Main.successAjax(json, updateId, addId);
            success(json);
        },
        complete: function(){
            Main.afterAjax();
            complete();
        },
        error: function(XMLHttpRequest, textStatus, errorThrown){
            Main.errorAjax(errorThrown);
            error();
        }
    });
};

$.fn.serializeObject = function() {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name]) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

$.fn.modalWindow = function(attributes){
    var autoOpen = false;
    var submit = null;
    var modalId = Main.modalId;
    var content = $(this).data('content');
    var url = $(this).data('url');
    var title = $(this).data('title');
    var okButtonText = $(this).data('okbutton');
    var cancelButtonText = $(this).data('cancelbutton');
    var noContent = $(this).data('nocontent');
    var noSubmit = $(this).data('nosubmit');
    var noCancel = $(this).data('nocancel');
    var notShowSubmit = $(this).data('notshowsubmit');
    var closeId = Main.modalCloseId;
    var modalOk = Main.modalOkId;
    var modalCancel = Main.modalCancelId;
    var modalContent = Main.modalContentId;
    var modalTitle = Main.modalTitleId;
    var form = $(this).data('form');

    if(typeof attributes != 'undefined'){
        if(typeof attributes == 'string'){
            if(attributes == 'close'){
                $(modalId).addClass('modal');
                $(modalId).addClass('fade');
            }
        }else{
            if(typeof attributes.autoOpen != 'undefined')
                autoOpen = attributes.autoOpen;
            if(typeof attributes.submit != 'undefined')
                submit = attributes.submit;
            if(typeof attributes.modalId != 'undefined')
                modalId = attributes.modalId;
            if(typeof attributes.content != 'undefined')
                content = attributes.content;
            if(typeof attributes.url != 'undefined')
                url = attributes.url;
            if(typeof attributes.okButtonText != 'undefined')
                okButtonText = attributes.okButtonText;
            if(typeof attributes.cancelButtonText != 'undefined')
                cancelButtonText = attributes.cancelButtonText;
            if(typeof attributes.title != 'undefined')
                title = attributes.title;
            if(typeof attributes.closeId != 'undefined')
                closeId = attributes.closeId;
            if(typeof attributes.noContent != 'undefined')
                noContent = attributes.noContent;
            if(typeof attributes.noSubmit != 'undefined')
                noSubmit = attributes.noSubmit;
            if(typeof attributes.noCancel != 'undefined')
                noCancel = attributes.noCancel;
            if(typeof attributes.noSubmit != 'undefined')
                noSubmit = attributes.notShowSubmit;
            if(typeof attributes.notShowSubmit != 'undefined')
                notShowSubmit = attributes.notShowSubmit;
            if(typeof attributes.modalCancel != 'undefined')
                modalCancel = attributes.modalCancel;
            if(typeof attributes.modalContent != 'undefined')
                modalContent = attributes.modalContent;
            if(typeof attributes.modalTitle != 'undefined')
                modalTitle = attributes.modalTitle;
            if(typeof attributes.form != 'undefined')
                form = attributes.form;
        }
    }

    if(typeof notShowSubmit != 'undefined' && notShowSubmit)
        $(modalOk).addClass('hide');

    if(typeof noSubmit == 'undefined' || noSubmit == '0' || !noSubmit)
        noSubmit = false;
    else
        noSubmit = true;

    if(typeof noCancel == 'undefined' || noCancel == '0' || !noCancel)
        noCancel = false;
    else
        noCancel = true;

    function openModal(){
        if(typeof title != 'undefined' && title){
            $(modalTitle).html(title);
        }

        if(typeof okButtonText != 'undefined' && okButtonText){
            $(modalOk).html(okButtonText);
        }

        if(typeof cancelButtonText != 'undefined' && cancelButtonText){
            $(modalCancel).html(cancelButtonText);
        }

        var contentHtml = $(content);
        if(contentHtml.length)
            contentHtml = $(content).html();
        else
            contentHtml = content;

        if(contentHtml && !noContent){
            $(modalContent).html(contentHtml);

            var modalWindow = $(modalId);

            modalWindow.removeClass('modal');
            modalWindow.removeClass('fade');
        }

        var realForm;
        if(typeof form != 'undefined' && form)
            realForm = $(form);
        else
            realForm = $(modalId+' form');

        if(realForm.length && !noSubmit && submit == null){
            $(modalOk).on('click', function(){
                realForm.submit();
            });
        }

        if(submit != null && typeof submit == 'function'){
            $(modalOk).on('click', function(){
                submit($(this));
            });
        }

        if(!noSubmit){
            if(typeof url != 'undefined' && url){
                document.location.href = url;
            }else{
                $(modalOk).on('click', function(){
                    $(modalId).addClass('modal');
                    $(modalId).addClass('fade');
                });
            }
        }

        $(closeId).on('click', function(){
            $(modalId).addClass('modal');
            $(modalId).addClass('fade');
        });

        if(!noCancel){
            $(modalCancel).on('click', function(){
                $(modalId).addClass('modal');
                $(modalId).addClass('fade');
            });
        }
    }

    $(this).on('click', function(){
        openModal();
        return false;
    });

    if(autoOpen){
        openModal();
    }

};
