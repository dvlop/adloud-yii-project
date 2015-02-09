Main = {

    autoLinkId: '.auto-link',
    dateFormat: 'yy-mm-dd',
    addTicketButtonId: '.add_ticket_btn a',
    addTicketId: '#add_ticket',
    notificationItemId: '.notifications-new-item',
    successMsgId: '.notifications-new-item.success-msg',
    errorMsgId: '.notifications-new-item.error-msg',
    formErrorsId: '.form-errors-msg',
    closeMsgId: '.notice .close-notice',
    msgId: '.notifications-new-item',
    msgContainerId: '.notice-wrap',
    msgHistoryBlockId: '.notifications-history',
    msgHistoryId: '.notifications-list',
    itemsCountId: '.notification-count',
    ticketsCountId: '.ticket-count',
    newNoticeId: '.new-notice',
    supportBtnId: '.open-support-bar',
    btnNotificationId: '.open-notifications-bar',
    notificationUrl: '',
    deleteButtonId: '.delete-button',
    actionButtonId: '.action-button',
    confirmDeleteText: 'Вы подтверждаете удаление элемента',
    confirmActionText: 'Вы подтверждаете действие',
    actionDataId: '.checkbox.checked input',
    footerId: '.bottom-menu',
    autoAjaxId: '.auto-ajax',
    editableAttrsId: '.editable-attr',
    editableWindowId: '.editable-window',
    editableWindowCloseId: '.editable-window-close',
    mainModalId: '#main-modal-window',
    mainModalCloserId: '#main-modal-window-closer',
    modalTitleId: '.modal-title',
    modalSubTitleId: 'modal-subtitle',
    modalContentId: '.modal-content',
    modalButtonOkId: '#main-modal-button-ok',
    modalButtonCancelId: '#main-modal-button-cancel',
    modalContentBlockId: '#main-modal-content-block',
    modalTextAreaId: '#main-modal-text',
    autoSelectId: '.auto-select',

    openedDiv: [],
    openedWindowsCount: 0,
    editableBlock: null,
    actionConfirmed: true,

    init: function(data){
        if(typeof data != 'undefined'){

            var attributes = [
                'autoLinkId',
                'dateFormat',
                'addTicketButtonId',
                'addTicketId',
                'notificationItemId',
                'successMsgId',
                'errorMsgId',
                'formErrorsId',
                'closeMsgId',
                'msgId',
                'msgContainerId',
                'msgHistoryBlockId',
                'msgHistoryId',
                'itemsCountId',
                'ticketsCountId',
                'newNoticeId',
                'supportBtnId',
                'btnNotificationId',
                'notificationUrl',
                'deleteButtonId',
                'confirmDeleteText',
                'footerId',
                'autoAjaxId',
                'editableAttrsId',
                'editableWindowId',
                'editableWindowCloseId',
                'mainModalId',
                'mainModalCloserId',
                'modalContentBlockId',
                'modalTextAreaId',
                'autoSelectId'
            ];

            $.each(attributes, function(index, element){
                if(typeof data[element] != 'undefined')
                    Main[element] = data[element];
            });
        }

        Main.setHandlers();
    },

    setHandlers: function(){
        Main.setAutoLink();
        Main.setAddTicket();
        Main.setSelectAll();
        Main.setCloseMessage();
        Main.setTooltip();
        Main.fixMessageBlock();
        Main.setMessageHistory();
        Main.setSupport();
        Main.setAskBeforeDelete();
        Main.setAskBeforeAction();
        Main.setAutoAjax();
        Main.setEditableAttrs();
        Main.setCloseModal();
        Main.setSwichersFix();
        Main.setNotificationScroll();
        Main.autoSelect();
        if($(Main.msgId)){
            Main.setNotificationListener();
        }
    },

    autoSelect: function(){
        $(Main.autoSelectId).on('change', function(){
            Main.select($(this));
        });
    },

    select: function(el){
        document.location.href = Main.addToUrl(el.data('attribute')+'='+el.val());
    },

    setCheckBoxesFix: function(){
        $('.checkbox').removeClass('checked');
    },

    setSwichersFix: function(){
        $('.camp_switch, .teaser_switch, .decks_switch, .blocks_switch').each(function(i,e){
            if ($(this).attr('data-checked') == 'checked') {
                $(this).parent().removeClass('switch-off').addClass('switch-on');
            } else {
                $(this).parent().removeClass('switch-on').addClass('switch-off');
            }
        });
    },

    setAutoLink: function(){
        $(Main.autoLinkId).on('click', function(){
            window.location = $(this).attr('data-url');
        });
    },

    setAddTicket: function(){
        $(Main.addTicketButtonId).on('click', function(){
            $(Main.addTicketId).css('display', 'block');
        });
    },

    setSelectAll: function(){
        // Table: Toggle all checkboxes
        /*$('.checkbox.toggle-all').on('click', function(){
                var ch = $('#checkbox-table-1').attr('checked');
                console.log(ch);
                $(this).closest('.table').find('tbody .adloud_checkbox').checkbox(ch ? 'check' : 'uncheck');
            }
        );

        // Table: Add class row selected
        $('.table tbody .adloud_checkbox').on('check uncheck toggle', function (e){
                var $this = $(this);
                var check = $this.prop('checked');
                var toggle = e.type == 'toggle';
                var checkboxes = $('.table tbody .adloud_checkbox');
                var checkAll = checkboxes.length == checkboxes.filter(':checked').length;
                $this.closest('tr')[check ? 'addClass' : 'removeClass']('selected-row');
                if (toggle) $this.closest('.table').find('.toggle-all .adloud_checkbox').checkbox(!checkAll ? 'check' : 'uncheck');
            }
        );*/

        $('label.toggle-all').on('click', function(){
            var checkAll = $(this);
            var table = checkAll.closest('table.adloud_table');
            var isChecked = checkAll.attr('for') == 'checkbox-table-campaign' ? checkAll.hasClass('checked') : !checkAll.hasClass('checked');

            $('tbody label.checkbox', table).each(function(){
                var checkBox = $(this);
                var input = $('input', checkBox);

                if(isChecked){
                    checkBox.addClass('checked');
                    input.attr('checked', 'checked');
                }else{
                    checkBox.removeClass('checked');
                    input.removeAttr('checked');
                }
            });
        });

    },

    setTooltip: function(){
        var tooltip = $("[data-toggle=tooltip]");
        if(tooltip.length)
            tooltip.tooltip('hide');
    },

    setCloseMessage: function(){
        $(Main.closeMsgId).on('click', function(){
            Main.closeMessage($(this).closest('div'+Main.msgId));
        });
    },

    setAskBeforeDelete: function(){
        $(Main.deleteButtonId).on('click', function(){
            var url = new String();
            var button = $(this);

            if(typeof button.attr('data-url') != 'undefined')
                url = button.attr('data-url');
            else if(typeof button.attr('href') != 'undefined')
                url = button.attr('href');
            else
                return true;

            var confirmText = new String();

            if(typeof button.attr('data-confirm') != 'undefined')
                confirmText = button.attr('data-confirm');
            else if(typeof button.attr('attr-label') != 'undefined')
                confirmText = Main.confirmDeleteText+' '+button.attr('attr-label');
            else
                confirmText = Main.confirmDeleteText;

            confirmText += '?';

            Main.actionConfirmed = Main.confirm(confirmText, url);
            return  Main.actionConfirmed;
        });
    },

    setAskBeforeAction: function(){
        $(Main.actionButtonId).on('click', function(){
            var url = new String();
            var button = $(this);

            if(typeof button.attr('data-url') != 'undefined')
                url = button.attr('data-url');
            else if(typeof button.attr('href') != 'undefined')
                url = button.attr('href');
            else
                return true;

            var confirmText = new String();

            if(typeof button.attr('data-confirm') != 'undefined')
                confirmText = button.attr('data-confirm');
            else if(typeof button.attr('attr-label') != 'undefined')
                confirmText = Main.confirmActionText+' '+button.attr('attr-label');
            else
                confirmText = Main.confirmActionText;

            confirmText += '?';

            Main.actionConfirmed = Main.confirm(confirmText, url);

            if(Main.actionConfirmed){
                var dataId = button.attr('data-id');
                if(typeof dataId == 'undefined')
                    dataId = Main.actionDataId;

                var data = $(dataId);

                if(data.length > 0){
                    var result = new String();

                    data.each(function(index, value){
                        var val = $(value).val();
                        if(val)
                            result += val+',';
                    });

                    if(result.length > 0)
                        result = result.substring(0, result.length-1);

                    if(result.length > 0){
                        var updateId = button.attr('data-update');
                        if(typeof updateId != 'undefined'){
                            Main.ajax({
                                url: url,
                                data: 'value='+result,
                                updateId: updateId
                            });
                        }else{
                            var successAction = button.attr('success-action');
                            if(typeof successAction != 'undefined'){
                                Main.ajax({
                                    url: url,
                                    data: 'value='+result,
                                    success: function(json){
                                        eval(successAction);
                                    }
                                });
                            }else{
                                Main.ajax({
                                    url: url,
                                    data: 'value='+result
                                });
                            }
                        }
                    }
                }else{
                    return true;
                }
            }

            return false;
        });
    },

    setAutoAjax: function(){
        $(Main.autoAjaxId).on('click', function(){
            if(!Main.actionConfirmed)
                return false;

            var elem = $(this);
            var autoUpdateId = elem.attr('data-update');
            if(typeof autoUpdateId != 'undefined'){
                console.log(autoUpdateId);
                $(this).ajaxAdloud({
                    updateId: autoUpdateId
                });
            }else{
                $(this).ajaxAdloud();
            }
            return false;
        });
    },

    setEditableAttrs: function(){
        $(Main.editableAttrsId).on('click', function(){
            if(!Main.actionConfirmed)
                return false;

            var block = $(this);

            var url = block.attr('data-url');
            var dataList = block.attr('data-list');
            var type = block.attr('data-type');
            var name = block.attr('data-name');
            var callback = block.attr('data-callback');
            var required = block.attr('no-required');
            var title = block.attr('data-title');

            var callbackClass = '';
            var callbackFunction = '';
            var requiredAttr = '';
            var titleText = 'Укажите значение атрибута';

            if(typeof url == 'undefined')
                url = '';
            if(typeof dataList == 'undefined')
                dataList = 'name=value';
            if(typeof type == 'undefined')
                type = 'test';
            if(typeof name == 'undefined')
                name = 'value';
            if(typeof callback == 'undefined'){
                Main.editableBlock = block;

                callbackClass = ' '.callback;
                var callbackData = '{';
                callbackData += 'url:\''+url+'\',';
                callbackData += 'dataList:\''+dataList+'\',';
                callbackData += 'type:\''+type+'\',';
                callbackData += 'name:\''+name+'\'';
                callbackData += '}';

                callbackFunction = 'return Main.editableCallback('+callbackData+', $(this).serialize());';
            }
            if(typeof required == 'undefined')
                requiredAttr = true;
            if(typeof title != 'undefined')
                titleText = title;

            var window = $(Main.editableWindowId);

            var titleWindow = $('.attr-tittle', window);
            var input = $('input[type="'+type+'"]', window);
            var form = $('form', window);
            var submitInput = $('input[type="submit"]', window);

            titleWindow.html(titleText);
            input.attr('name', name);
            input.val(block.html().replace('$', '').trim());
            form.attr('action', url);
            if(requiredAttr)
                input.attr('required', 'required');
            if(callbackFunction)
                form.attr('onSubmit', callbackFunction);
            if(callbackClass)
                submitInput.addClass(callbackClass);

            var top = block.offset().top+20;
            var left = block.offset().left;

            window.css('top', top+'px');
            window.css('left', left+'px');
            window.css('display', 'block');


            var editableInput = $('input[type="'+type+'"]', window);
            editableInput.focus();
            Main.setCursorPosition(editableInput, editableInput.val().length);

            $(Main.editableWindowCloseId).on('click', function(){
                window.css('display', 'none');
                return false;
            });

            $(document).click(function(event){
                if($(event.target).closest(Main.editableWindowId).length == 0 && $(event.target).closest(Main.editableAttrsId).length == 0){
                    $(Main.editableWindowId).css('display', 'none');
                    event.stopPropagation();
                }
            });
        });
    },

    setCloseModal: function(window){
        $(Main.mainModalId).on('click', Main.mainModalCloserId, function(){
            if(typeof window == 'undefined')
                window = $(Main.mainModalId);

            Main.closeModal(window);
        });
    },

    showModal: function(content, window){
        if(typeof window == 'undefined')
            window = $(Main.mainModalId);

        if(typeof content != 'undefined' && content != null){
            var contentBlock = $(Main.modalContentId);

            if(typeof content == 'string'){
                contentBlock.html(contentBlock.html()+content);
            }else if(typeof content == 'object'){
                var titleBlock = $(Main.modalTitleId);
                var subtitleBlock = $(Main.modalSubTitleId);
                var okBlock = $(Main.modalButtonOkId);
                var canselBlock = $(Main.modalButtonCancelId);

                if(typeof content.title != 'undefined' && titleBlock.length){
                    titleBlock.html(titleBlock.html()+content.title);
                }
                if(typeof content.content != 'undefined' && contentBlock.length){
                    contentBlock.html(contentBlock.html()+content.content);
                }
                if(typeof content.subtitle != 'undefined' && subtitleBlock.length){
                    subtitleBlock.html(subtitleBlock.html()+content.subtitle);
                }
                if(typeof content.buttonOk != 'undefined' && okBlock.length){
                    okBlock.html(okBlock.html()+content.buttonOk);
                }
                if(typeof content.buttonCancel != 'undefined' && canselBlock.length){
                    canselBlock.html(canselBlock.html()+content.buttonCancel);
                }
            }
        }

        Main.openModal(window);
    },

    showError: function(error, selector){
        if(typeof selector == 'undefined')
            selector = Main.errorMsgId;
        else
            Main.errorMsgId = selector;

        Main.openMessage(error, selector);
    },

    showMessage: function(message, selector){
        if(typeof selector == 'undefined')
            selector = Main.successMsgId;
        else
            Main.successMsgId = selector;

        Main.openMessage(message, selector);
    },

    openMessage: function(message, selector){
        if(message)
            $(selector+' p').html(message);

        var message = $(selector);
        message.css('display', 'block');
        if($(document).scrollTop() > 41){
            message.css({
                left: message.offset().left,
                position: 'fixed',
                top: '9px'
            });
        } else {
            message.css({
                left: '-112px',
                position: 'absolute',
                top: ''
            });
        }

        setTimeout(function(message){
            Main.closeMessage(message);
        }, 12000);
    },

    closeMessage: function(block){
        if(typeof block == 'undefined')
            block = $(Main.msgId);

        $('p', block).html('');
        block.css('display', 'none');
    },

    fixMessageBlock: function(){
        fixmessages();

        window.onscroll = (function(){
            fixmessages();
        });

        function fixmessages(){
            if ($(Main.msgContainerId).offset() !== undefined) {
                if($(Main.msgContainerId).offset().top + $(Main.msgContainerId).height() >= $(Main.footerId).offset().top - 10)
                    $(Main.msgContainerId).css({'position':'absolute','bottom':'10px'});
                if($(document).scrollTop() + window.innerHeight < $(Main.footerId).offset().top)
                    $(Main.msgContainerId).css({'position':'fixed','bottom':'0px'});
            }
        }
    },

    setNotificationScroll: function(){
        $(document).on('scroll', function(){
            if($(document).scrollTop() < 42 && $(Main.notificationItemId).css('position') == 'fixed'){
                $(Main.notificationItemId).css({
                    left: '-112px',
                    position: 'absolute',
                    top: ''
                });
            }
        });
    },

    setNotificationListener: function(){
        setInterval(function(){
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: '/index/getnotification/',
                data: 'listen=1',
                success: function(json){
                    if(json.icon){
                        var count = parseInt($(Main.itemsCountId).text()) + json.count;

                        $(Main.msgId+' img').attr('src',json.icon);
                        Main.showMessage(json.message);
                        $(Main.itemsCountId).text(count);
                        if($(Main.itemsCountId).hasClass('hide')){
                            $(Main.itemsCountId).toggleClass('hide');
                        }
                    }
                    if(json.tickets){
                        $(Main.ticketsCountId).text(json.tickets);
                        if($(Main.ticketsCountId).hasClass('hide')){
                            $(Main.ticketsCountId).toggleClass('hide');
                        }
                    }
                }
            });
        },30000);
    },

    setMessageHistory: function(){
        $(Main.btnNotificationId).on('click', function(){
            if($(Main.msgHistoryBlockId).hasClass('hide')){
                Main.closeMessage();
                $(Main.msgHistoryBlockId).toggleClass('hide');

                Main.ajax({
                    url: Main.notificationUrl,
                    dataType: 'html',
                    data: 'getlist=1',
                    success: function(data){
                        $(Main.msgHistoryId).empty();
                        $(Main.msgHistoryId).append(data);
                        $(Main.msgHistoryId).mCustomScrollbar();
                        if(!$(Main.itemsCountId).hasClass('hide')){
                            $(Main.itemsCountId).toggleClass('hide');
                        }
                        $(Main.itemsCountId).text(0);
                        setTimeout(function(){
                            $(Main.newNoticeId).animate({
                                backgroundColor: 'rgba(246, 234, 218, 0)'
                            },500,function(){
                                $(this).removeClass(Main.newNoticeId.replace('.',''))
                            });
                        },2000);
                    }
                });
            } else {
                $(Main.msgHistoryBlockId).toggleClass('hide');
                $(Main.msgHistoryId).remove();
                $('<ul/>', {
                    class: Main.msgHistoryId.replace('.','')
                }).appendTo($(Main.msgHistoryBlockId));
            }
        });
    },

    setSupport: function(){
        $(Main.supportBtnId).on('click', function(){
            document.location = $(this).attr('data-url');
        });
    },

    addToUrl: function(attributes, url){
        if(typeof url == 'undefined')
            url = document.URL;

        if(typeof attributes == 'undefined')
            return url;

        url = url.split('?');

        if(typeof url[1] != 'undefined'){
            var attrs = Main.sepUrlParams(url[1]);
            url = url[0];
        }else{
            var attrs = {};
            url = url[0];
        }

        var oldAttrs = '';
        var newAttrs = '';

        if(typeof attributes == 'string')
            attributes = Main.sepUrlParams(attributes)

        $.each(attributes, function(n, val){
            if(typeof val !== 'undefined')
                newAttrs += n+'='+val+'&';

            if(typeof attrs[n] != 'undefined')
                attrs[n] = null;
        });

        $.each(attrs, function(n, val){
            if(val !== null)
                oldAttrs += n+'='+val+'&';
        });

        if(newAttrs.length > 0)
            newAttrs = newAttrs.substr(0, newAttrs.length-1);

        if(oldAttrs.length > 0)
            oldAttrs = oldAttrs.substr(0, oldAttrs.length-1);

        if(oldAttrs.length > 0)
            url += '?'+oldAttrs;

        if(newAttrs.length > 0){
            if(url.indexOf('?') == -1)
                url += '?'+newAttrs;
            else
                url += '&'+newAttrs;
        }

        return url;
    },

    removeFromUrl: function(attributes, url){
        if(typeof url == 'undefined')
            url = document.URL;

        if(typeof attributes == 'undefined')
            return url;

        var attrs;
        url = url.split('?');

        if(typeof url[1] != 'undefined'){
            attrs = Main.sepUrlParams(url[1]);
            url = url[0];
        }else{
            attrs = {};
            url = url[0];
        }

        if(typeof attributes == 'string')
            attributes = Main.sepUrlParams(attributes);

        var newAttrs = '';

        if(attrs && attributes){
            $.each(attributes, function(index, val){
                attrs[val] = null;
            });

            $.each(attrs, function(index, val){
                if(val != null)
                    newAttrs += index+'='+val+'&';
            });

            if(newAttrs.length > 0){
                newAttrs = newAttrs.substr(0, newAttrs.length-1);
                url += '?'+newAttrs;
            }
        }

        return url;
    },

    ajaxHandler: function(url, data, success){
        if(typeof success == 'undefined'){
            success = function(json){
                if(json.error){
                    Main.showError(json.error);
                }else{
                    Main.showMessage(json.message);
                }
            }
        }

        $.ajax({
            type: 'POST',
            cache: false,
            url: url,
            data: data,
            dataType: 'json',
            success: function(json){
                success(json);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                console.log(errorThrown);
                //Main.showError(errorThrown);
            }
        });
    },

    handleFormErrors: function(errors, form){
        Main.openMessage(errors, Main.formErrorsId);
    },

    sepUrlParams: function(string){
        var params = Main.sep(string, '&');

        var result = {};
        var res = [];

        $.each(params, function(n, val){
            res = Main.sep(val, '=');
            result[res[0]] = res[1];
        });

        return result;
    },

    sep: function(string, sep){
        var result = {};

        if(string.length == 0)
            return result;

        var params = string.split(sep);

        $.each(params, function(n, val){
            result[n] = val;
        });

        return result;
    },

    ajax: function(attributes){
        if(typeof attributes.event != 'undefined')
            var event = attributes.event;
        else
            var event = $('body');

        event.ajaxAdloud(attributes);
    },

    ajaxBefore: function(){
        console.log('Before ajax');
    },

    ajaxAfter: function(){
        console.log('After ajax');
    },

    ajaxError: function(error){
        $('.notifications-new-item.error-msg').html(error).removeClass('hide');
    },

    ajaxSuccess: function(json, updateId){
        if(json.error){
            Main.showError(json.error);
        }else{
            if(json.message)
                Main.showMessage(json.message);

            if(json.html && typeof updateId != 'undefined'){
                if(typeof updateId == 'string')
                    $(updateId).html(json.html);
                else
                    updateId.html(json.html);
            }
        }
    },

    editableCallback: function(data, formData){
        Main.ajax({
            url: data.url,
            data: data.dataList+'&'+formData,
            success: function(json){
                if(json.html && Main.editableBlock != null){
                    Main.editableBlock.html(json.html);
                    $(Main.editableWindowId).css('display', 'none');
                }
            }
        });

        return false;
    },

    setCursorPosition: function(event, pos){
        $(event).each(function(index, elem) {
            if(elem.setSelectionRange){
                elem.setSelectionRange(pos, pos);
            }else if(elem.createTextRange){
                var range = elem.createTextRange();
                range.collapse(true);
                range.moveEnd('character', pos);
                range.moveStart('character', pos);
                range.select();
            }
        });
        return this;
    },

    openModal: function(window){
        if(typeof window == 'undefined')
            window = $(Main.mainModalId);

        window.removeClass('hide');
    },

    closeModal: function(window){
        if(typeof window == 'undefined')
            window = $(Main.mainModalId);
        window.addClass('hide');
    },

    confirm: function(text, url){
        return confirm(text);
    },

    preview: function(teasers, css, previewId){
        var isObj = typeof css == 'object';

        if(!isObj){
            css = decodeURIComponent((css+'').replace(/\+/g, '%20'));
            css = JSON.parse(css);
        }

        for(var i in css){
            var id = 'preview-block-style-id-'+i;
            $('#'+id).remove();

            var s = document.createElement('link');
            s.setAttribute('id', id);
            s.setAttribute('rel', 'stylesheet');
            s.setAttribute('href', css[i]);
            document.head.appendChild(s);
        }

        if(!isObj)
            teasers = decodeURIComponent((teasers+'').replace(/\+/g, '%20'));

        $(previewId).html(teasers);
    }
}

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
