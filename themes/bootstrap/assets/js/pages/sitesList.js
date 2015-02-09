Sites = {

    statusSelectorsId: '.site-status-button',
    statusFilterId: '#sites-status-selector-id',

    init: function(data){
        if(typeof data != 'undefined'){
            var attributes = [
                'statusSelectorsId',
                'statusFilterId'
            ];

            $.each(attributes, function(index, element){
                if(typeof data[element] != 'undefined')
                    Sites[element] = data[element];
            });
        }

        Sites.setHandlers();
    },

    setHandlers: function(){
        Sites.setStatusSelector();
        Sites.setStatusFiler();
    },

    setStatusSelector: function(){
        $(Sites.statusSelectorsId).on('click', function(){
            Main.ajax({
                url: $(this).attr('href'),
                data: 'setSiteStatus=1',
                updateId: $(this).parent('td').prev()
            });
            return false;
        });
    },

    setStatusFiler: function(){
        $(Sites.statusFilterId).on('change', function(){
            var selector = $(this);
            var name = selector.attr('name');
            var value = selector.val();

            window.location = Main.addToUrl(name+'='+value, document.URL);
        });
    }

}
