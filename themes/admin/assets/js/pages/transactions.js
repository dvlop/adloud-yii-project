Transactions = {


    init: function(data){
        if(typeof data != 'undefined'){
            var attributes = [

            ];

            $.each(attributes, function(index, element){
                if(typeof data[element] != 'undefined')
                    Transactions[element] = data[element];
            });
        }

        Transactions.setHandlers();
    },

    setHandlers: function(){

    }

    /* Handlers */



    /* END Handlers */
}
