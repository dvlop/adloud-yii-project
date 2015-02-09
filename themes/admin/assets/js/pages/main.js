Main = {

    datatableId: '.datatable',
    autoUrlId: '.auto-url',
    autoAjaxId: '.auto-ajax',
    modalId: '#main-modal',
    modalCloseId: 'button.close',
    modalOkId: 'button.success',
    modalCancelId: 'button.cancel',
    modalContentId: '.modal-body',
    modalTitleId: '.modal-title',
    autoModalId: '.auto-modal',
    notificationId: '#header_notification_bar',
    autoSelectId: '.auto-select',

    init: function(data){
        if(typeof data != 'undefined'){
            var attributes = [
                'datatableId',
                'autoUrlId',
                'autoAjaxId',
                'modalId',
                'modalCloseId',
                'modalOkId',
                'modalCancelId',
                'modalContentId',
                'modalTitleId',
                'autoModalId',
                'notificationId',
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
        Main.dataTables();
        Main.autoUrl();
        Main.autoAjax();
        Main.autoModal();
        Main.autoSelect();
    },


    /* Handlers */

    dataTables: function(){
        var dataTable = $(Main.datatableId);

        if(!dataTable.length)
            return false;

        var options = {
            oLanguage: {
                sSearch: 'Поиск',
                sLengthMenu: '_MENU_ элементов на страницу',
                sZeroRecords: 'Ничего не найдено',
                sInfo: '_START_ - _END_ из _TOTAL_',
                sInfoEmpty: 'Список пуст',
                sInfoFiltered: '(отфильтровано из _MAX_ записей)'
            }
        };

        var ajaxOptions = dataTable.data('ajax');

        if(typeof ajaxOptions != 'undefined' && ajaxOptions){
            var url = dataTable.data('url');
            if(typeof url == 'undefined')
                url = ajaxOptions;

            options.bProcessing = true;
            options.bServerSide = true;
            options.sAjaxSource = url;
        }

        var table = dataTable.dataTable(options);

        var filtering = dataTable.data('filter');

        if(typeof filtering != 'undefined' && filtering){
            var asInitVals = [];

            $(filtering+' input').on('input', function(){
                table.fnFilter(this.value, $(filtering+' input').index(this));
            });

            $(filtering+' input').each(function(i){
                asInitVals[i] = this.value;
            });

            $(filtering+' input').focus(function(){
                if(this.className == 'search_init'){
                    this.className = '';
                    this.value = '';
                }
            });

            $(filtering+' input').blur(function(i){
                if(this.value == ''){
                    this.className = 'search_init';
                    this.value = asInitVals[$(filtering+' input').index(this)];
                }
            } );
        }
    },

    autoUrl: function(){
        $('body').on('click', Main.autoUrlId, function(){
            document.location.href = $(this).data('url');
        });
    },

    autoAjax: function(){
        $('body').on('click', Main.autoAjaxId, function(){
            $(this).ajx();
        });
    },

    autoModal: function(){
        $(Main.autoModalId).modalWindow();
    },

    autoSelect: function(){
        $(Main.autoSelectId).on('change', function(){
            Main.select($(this));
        });
    },

    /* END Handlers */


    /* Public functions */

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

    addNotify: function(text, type, notClass){
        if(typeof notClass == 'undefined')
            notClass = 'label-success';

        var types = {
            success: 'label-success',
            error: 'label-danger',
            message: 'label-info'
        };

        if(typeof type == 'undefined')
            type = types.success;

        if(types[type] != undefined)
            notClass = types[type];

        var li = '<li>';
        li += '<a href="#">';
        li += '<span class="label '+notClass+'">';
        li += '<i class="fa fa-bell"></i>';
        li += '</span>';
        li += text;
        li += '</a>';
        li += '</li>';

        Main.setNotifyText(li);
    },

    select: function(el){
        document.location.href = Main.addToUrl(el.data('attribute')+'='+el.val());
    },

    /* END Public functions */


    /* Ajax */

    beforeAjax: function(){
        console.log('before ajax');
    },

    successAjax: function(json, updateId, addId){
        if(json.error){
            Main.errorAjax(json.error);
        }else{
            var html;

            if(updateId){
                var updateContainer = $(updateId);

                if(updateContainer.length){

                    if(json.html)
                        html = json.html;
                    else
                        html = json.toString();

                    if(html !== 'remove-this'){
                        updateContainer.html(html);
                    }else{
                        updateContainer.remove();
                    }
                }
            }else if(addId){
                var addContainer = $(addId);

                if(addContainer.length){

                    if(json.html)
                        html = json.html;
                    else
                        html = json.toString();

                    addContainer.append(html);
                }
            }

            if(json.message)
                Main.addNotify(json.message, 'message');
            if(json.success)
                Main.addNotify(json.success, 'success');
        }
    },

    afterAjax: function(){

    },

    errorAjax: function(errorThrown){
        Main.addNotify(errorThrown, 'error');
    },

    /* END Ajax */


    /* Private Functions */

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

    openNotify: function(){
        $(Main.notificationId).addClass('open');
    },

    closeNotify: function(){
        Main.clearNotify();
        $(Main.notificationId).removeClass('open');
    },

    setNotifyText: function(text){
        $(Main.notificationId+' ul.dropdown-menu').append(text);
        Main.openNotify();
        setTimeout(function(){
            Main.closeNotify();
        }, 7000);
    },

    clearNotify: function(){
        $(Main.notificationId+' ul.dropdown-menu li').remove();
    }

    /* END Private Functions */
}