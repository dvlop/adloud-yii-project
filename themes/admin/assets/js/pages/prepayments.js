Prepayments = {

    messageId: '.write-message',
    messageFormId: '.admin-ticket-form',
    userInputId: 'input[name="ticket[userId]"]',
    formErrorId: '.error-group',

    init: function(data){
        if(typeof data != 'undefined'){
            var attributes = [
                'messageId',
                'messageFormId',
                'userInputId',
                'formErrorId'
            ];

            $.each(attributes, function(index, element){
                if(typeof data[element] != 'undefined')
                    Prepayments[element] = data[element];
            });
        }

        Prepayments.setHandlers();
    },

    setHandlers: function(){
        Prepayments.writeMessage();
    },

    /* Handlers */

    writeMessage: function(){
        $(Prepayments.messageId).on('click', function(){
            $(Prepayments.userInputId).val($(this).data('user'));
            $(Prepayments.formErrorId).html('');

            $(this).modalWindow({
                autoOpen: true,
                submit: function(button){
                    $(Prepayments.messageFormId+' input[type="submit"]').click();
                }
            });
        });
    }

    /* END Handlers */
}
