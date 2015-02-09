Blocks = {


    init: function(data){
        if(typeof data != 'undefined'){
            var attributes = [

            ];

            $.each(attributes, function(index, element){
                if(typeof data[element] != 'undefined')
                    Blocks[element] = data[element];
            });
        }

        Blocks.setHandlers();
    },

    setHandlers: function(){

    }

    /* Handlers */



    /* END Handlers */
}
