Users = {

    autoLoginId: '.auto-login',
    messageId: '.write-message',
    messageFormId: '.admin-ticket-form',
    userInputId: 'input[name="ticket[userId]"]',
    formErrorId: '.error-group',

    init: function(data){
        if(typeof data != 'undefined'){
            var attributes = [
                'autoLoginId',
                'messageId',
                'messageFormId',
                'userInputId',
                'formErrorId'
            ];

            $.each(attributes, function(index, element){
                if(typeof data[element] != 'undefined')
                    Users[element] = data[element];
            });
        }

        Users.setHandlers();
    },

    setHandlers: function(){
        Users.autoLogin();
        Users.writeMessage();
    },

    /* Handlers */

    autoLogin: function(){
        $(Users.autoLoginId).on('click', function(){
            return confirm($(this).data('text'));
        });
    },

    writeMessage: function(){
        $(Users.messageId).on('click', function(){
            $(Users.userInputId).val($(this).data('user'));
            $(Users.formErrorId).html('');

            $(this).modalWindow({
                autoOpen: true,
                submit: function(button){
                    $(Users.messageFormId+' input[type="submit"]').click();
                }
            });
        });
    }

    /* END Handlers */
}