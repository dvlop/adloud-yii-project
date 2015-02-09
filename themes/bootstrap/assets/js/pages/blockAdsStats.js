BlockAdsStats = {

    statusSwitcherId: 'input[name="switch-ads-status"]',

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

        BlockAdsStats.setHandlers();
    },

    setHandlers: function(){
        BlockAdsStats.setStatusSwitcher();
    },

    setStatusSwitcher: function(){
        $(BlockAdsStats.statusSwitcherId).bootstrapSwitch({
            onSwitchChange: function(event, state){
                var element = $(event.currentTarget);
                Main.ajax({
                    url: element.attr('data-url'),
                    data: 'status='+state+'&adsId='+element.attr('data-id')
                });
            }
        });
    }
}
