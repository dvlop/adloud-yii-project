Payment = {

    paymentUrl: '',
    moneyUrl: '',
    formContainerId: '#payment-form-container',
    hiddenFieldsId: '#payment-hidden-fields',
    moneyFieldId: '#payment-money-id',
    currencyId: '#replenishment_balance input[type=radio]',
    paymentSelectorId: 'label.radio',

    init: function(data){
        if(typeof data != 'undefined'){
            var attributes = [
                'paymentUrl',
                'moneyUrl',
                'formContainerId',
                'payment-hidden-fields',
                'moneyFieldId',
                'currencyId',
                'paymentSelectorId'
            ];

            $.each(attributes, function(index, element){
                if(typeof data[element] != 'undefined')
                    Payment[element] = data[element];
            });
        }

        Payment.setHandlers();
    },

    setHandlers: function(){
        Payment.selectPayment(null);
        Payment.selectCurrency();
        Payment.enterMoney();
    },

    selectPayment: function(element){
        var value = '1';

        if(element != null)
            value = element.val();

        $.ajax({
            method: 'POST',
            url: Payment.paymentUrl,
            data: 'payment='+value,
            dataType: 'json',
            success: function(json){
                if(json.error){
                    Main.showError(json.error)
                }else{
                    $(Payment.formContainerId).html(json.html);
                }
            }
        });
    },

    selectCurrency: function(){
        var selectors = $(Payment.paymentSelectorId);

        $(Payment.currencyId).on('toggle', function(){
            selectors.removeClass('checked');
            Payment.selectPayment($(this));
        });
    },

    enterMoney: function(){
        $(Payment.formContainerId).on('click', 'form button[type=submit]', function(){
            var form = $(this).closest('form');

            $.ajax({
                method: 'POST',
                url: Payment.moneyUrl,
                data: form.serialize()+'&moneyAmount='+$(Payment.moneyFieldId).val(),
                dataType: 'json',
                success: function(json){
                    if(json.error){
                        Main.showError(json.error);
                    }else{
                        $(Payment.hiddenFieldsId).html(json.html);
                        form.submit();
                    }
                }
            });

            return false;
        });
    }
}