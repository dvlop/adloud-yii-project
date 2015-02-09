PeferalPayments = {

    init: function(data){
        if(typeof data != 'undefined'){
            var attributes = [

            ];

            $.each(attributes, function(index, element){
                if(typeof data[element] != 'undefined')
                    PeferalPayments[element] = data[element];
            });
        }

        PeferalPayments.setHandlers();
    },

    setHandlers: function(){

    }

    /* Handlers */



    /* END Handlers */
}
