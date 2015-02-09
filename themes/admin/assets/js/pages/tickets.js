Tickets = {


    init: function(data){
        if(typeof data != 'undefined'){
            var attributes = [

            ];

            $.each(attributes, function(index, element){
                if(typeof data[element] != 'undefined')
                    Tickets[element] = data[element];
            });
        }

        Tickets.setHandlers();
    },

    setHandlers: function(){

    }

    /* Handlers */



    /* END Handlers */
}
