FormatsStat = {

    statusSwitcherId: 'input[name="switch-format-status"]',

    init: function(data){
        if(typeof data != 'undefined'){
            var attributes = [
                'statusSwitcherId'
            ];

            $.each(attributes, function(index, element){
                if(typeof data[element] != 'undefined')
                    FormatsStat[element] = data[element];
            });
        }

        FormatsStat.setHandlers();
    },

    setHandlers: function(){
        FormatsStat.setStatusSwitcher();
    },

    setStatusSwitcher: function(){
        $(FormatsStat.statusSwitcherId).bootstrapSwitch({
            onSwitchChange: function(event, state){
                var element = $(event.currentTarget);
                Main.ajax({
                    url: element.attr('data-url'),
                    data: 'status='+state+'&format='+element.attr('data-list')
                });
            }
        });
    }
}
