/**
 * Created with JetBrains PhpStorm.
 * User: JanGolle
 * Date: 28.07.14
 * Time: 16:47
 * To change this template use File | Settings | File Templates.
 */

ReferalPaymentList = {

    filterPickerId: '#check-filter-id',
    deactivateId: 'button.payment-deactivate',
    pageBaseUrl: '',
    activateId: 'button.payment-activate',
    getShow: 'all',

    init: function(data){
        var attributes = [
            'filterPickerId',
            'deactivateId',
            'pageBaseUrl',
            'activateId',
            'getShow'
        ];

        $.each(attributes, function(index, element){
            if(typeof data[element] != 'undefined')
                ReferalPaymentList[element] = data[element];
        });

        $('[data-text='+ReferalPaymentList.getShow+']').prop('selected', true);

        ReferalPaymentList.setHandlers();
    },

    setHandlers: function(){
        ReferalPaymentList.setSelectFilter();
        ReferalPaymentList.setDeactivate();
        ReferalPaymentList.setActivate();
    },

    setSelectFilter: function(){
        $(ReferalPaymentList.filterPickerId).on('change', function(el){
            var show = $('option:selected').val();

            if(show == 'new'){
                document.location.href = ReferalPaymentList.pageBaseUrl;
            } else {
                document.location.href = ReferalPaymentList.pageBaseUrl+'show/'+show;
            }
        });
    },

    setDeactivate: function(){
        $(ReferalPaymentList.deactivateId).on('click', function(){
            $(this).parent().parent().remove();
            $.ajax({
                type: 'POST',
                cache: false,
                url: $(this).attr('data-url'),
                data: 'moderateReferal=false',
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

    setActivate: function(){
        $(ReferalPaymentList.activateId).on('click', function(){
            $(this).parent().parent().remove();
            $.ajax({
                type: 'POST',
                cache: false,
                url: $(this).attr('data-url'),
                data: 'moderateReferal=true',
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
