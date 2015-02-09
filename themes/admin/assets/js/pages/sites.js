Sites = {


    init: function(data){
        if(typeof data != 'undefined'){
            var attributes = [

            ];

            $.each(attributes, function(index, element){
                if(typeof data[element] != 'undefined')
                    Sites[element] = data[element];
            });
        }

        Sites.setHandlers();
    },

    setHandlers: function(){

    }

    /* Handlers */



    /* END Handlers */
}
