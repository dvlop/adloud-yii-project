/**
 * Created with JetBrains PhpStorm.
 * User: JanGolle
 * Date: 22.08.14
 * Time: 14:59
 * To change this template use File | Settings | File Templates.
 */

UserAgentAdmin = {

    filterPickerId: '#check-filter-id',
    pageBaseUrl: '',
    allowId: 'button.allow-ua',
    banId: 'button.ban-ua',
    getShow: 'opened',

    init: function(data){
        var attributes = [
            'filterPickerId',
            'pageBaseUrl',
            'allowId',
            'banId',
            'getShow'
        ];

        $.each(attributes, function(index, element){
            if(typeof data[element] != 'undefined')
                UserAgentAdmin[element] = data[element];
        });

        $('[data-text='+UserAgentAdmin.getShow+']').prop('selected', true);

        UserAgentAdmin.setHandlers();
    },

    setHandlers: function(){
        UserAgentAdmin.setSelectFilter();
        UserAgentAdmin.setAllow();
        UserAgentAdmin.setBan();
    },

    setSelectFilter: function(){
        $(UserAgentAdmin.filterPickerId).on('change', function(el){
            var show = $('option:selected').val();

            if(show == 'device'){
                document.location.href = UserAgentAdmin.pageBaseUrl;
            } else {
                document.location.href = UserAgentAdmin.pageBaseUrl+'type/'+show;
            }
        });
    },

    setAllow: function(){
        $(UserAgentAdmin.allowId).on('click', function(){
            var td = $(this).parent();
            $(this).remove();

            td.text('Добавлен');
            $.ajax({
                type: 'POST',
                cache: false,
                url: $(this).attr('data-url'),
                data: 'is_checked=TRUE',
                dataType: 'json',
                success: function(json){
                    if(json.error){
                        Main.showError(json.error);
                    }else{
                        Main.showMessage(json.message);
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    Main.showError(errorThrown);
                }
            });
        });
    },

    setBan: function(){
        $(UserAgentAdmin.banId).on('click', function(){
            var td = $(this).parent();
            $(this).remove();

            td.text('Запрещен');
            $.ajax({
                type: 'POST',
                cache: false,
                url: $(this).attr('data-url'),
                data: 'is_checked=FALSE',
                dataType: 'json',
                success: function(json){
                    if(json.error){
                        Main.showError(json.error);
                    }else{
                        Main.showMessage(json.message);
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    Main.showError(errorThrown);
                }
            });
        });
    }
}