Teasers = {

    checkboxId: '.ads-checkbox',

    init: function(data){
        if(typeof data != 'undefined'){
            var attributes = [
                'checkboxId'
            ];

            $.each(attributes, function(index, element){
                if(typeof data[element] != 'undefined')
                    Teasers[element] = data[element];
            });
        }

        Teasers.setHandlers();
    },

    setHandlers: function(){
        Teasers.setCheckBoxValue();
    },

    /* Handlers */

    setCheckBoxValue: function(){
        $(Teasers.checkboxId).on('change', function(){
            $(this).ajx({
                data: 'value='+$(this).is(':checked')
            });
        });
    }

    /* END Handlers */


    /* Private functions */


    /* END Private functions */
}
