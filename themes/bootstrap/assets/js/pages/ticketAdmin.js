/**
 * Created with JetBrains PhpStorm.
 * User: JanGolle
 * Date: 31.07.14
 * Time: 12:44
 * To change this template use File | Settings | File Templates.
 */

TicketAdmin = {

    filterPickerId: '#check-filter-id',
    pageBaseUrl: '',
    closeId: 'button.ticket-close',
    getShow: 'opened',

    init: function(data){
        var attributes = [
            'filterPickerId',
            'pageBaseUrl',
            'closeId',
            'getShow'
        ];

        $.each(attributes, function(index, element){
            if(typeof data[element] != 'undefined')
                TicketAdmin[element] = data[element];
        });

        $('[data-text='+TicketAdmin.getShow+']').prop('selected', true);

        TicketAdmin.setHandlers();
    },

    setHandlers: function(){
        TicketAdmin.setSelectFilter();
        TicketAdmin.setClose();
    },

    setSelectFilter: function(){
        $(TicketAdmin.filterPickerId).on('change', function(el){
            var show = $('option:selected').val();

            if(show == 'opened'){
                document.location.href = TicketAdmin.pageBaseUrl;
            } else {
                document.location.href = TicketAdmin.pageBaseUrl+'show/'+show;
            }
        });
    },

    setClose: function(){
        $(TicketAdmin.closeId).on('click', function(){
            $(this).parent().parent().remove();
            $.ajax({
                type: 'POST',
                cache: false,
                url: $(this).attr('data-url'),
                data: 'status=0',
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