MoneyGet = {

    prepaymentLink: '#prepayment-link',
    dateTitleId: '.auto_withdrawal_date',
    tableId: 'table.payments_stat',

    init: function(data){
        if(typeof data != 'undefined'){
            var attributes = [
                'prepaymentLink',
                'dateTitleId',
                'tableId'
            ];

            $.each(attributes, function(index, element){
                if(typeof data[element] != 'undefined')
                    MoneyGet[element] = data[element];
            });
        }

        MoneyGet.setHandlers();
    },

    setHandlers: function(){
        $(MoneyGet.prepaymentLink).on('click', function(){
            MoneyGet.moneyPrepayment($(this).attr('data-url'));
        });
    },

    moneyPrepayment: function(url){
        Main.ajax({
            url: url,
            data: 'moneyPrepayment=1',
            success: function(json){
                if(json.html){
                    var title =  $(MoneyGet.dateTitleId+' time');
                    title.attr('date', json.html.title);
                    title.html(json.html.title);

                    $(MoneyGet.tableId+' tbody').prepend(json.html.row);
                }
            }
        });
    }
}