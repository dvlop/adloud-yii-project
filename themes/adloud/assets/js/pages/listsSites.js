ListsSites = {

    listId: null,
    listFormId: '#sites_lists',
    addListButtonId: '.add_black_and_white',
    selectAllId: '.checkbox.select-all',
    campaignsId: '.form-goup .checkbox.adloud_label',
    listTypeId: '.radio.adloud_label',

    campaigns: null,
    campaignsInputs: null,

    init: function(data){

        if(typeof data != 'undefined'){
            var attributes = [
                'listId',
                'listFormId',
                'addListButtonId',
                'selectAllId',
                'campaignsId',
                'listTypeId'
            ];

            $.each(attributes, function(index, element){
                if(typeof data[element] != 'undefined')
                    ListsSites[element] = data[element];
            });
        }

        ListsSites.setHandlers();
    },

    setHandlers: function(){
        ListsSites.setListFormToggle();
        ListsSites.removeHiddenCampaigns();
        ListsSites.setCheckCampaign();
        ListsSites.setSelectAll();
    },

    setListFormToggle: function(){
        var button = $(ListsSites.addListButtonId);
        button.on('click', function(){
            $(ListsSites.listFormId).toggle();
        });
    },

    setCheckCampaign: function(){
        ListsSites.getCampaigns().on('click', function(){
            $(this).toggleClass('checked');
            $('input[value!="0"]', this).attr('checked', $(this).hasClass('checked'));
            return false;
        });
    },

    setSelectAll: function(){
        $(ListsSites.selectAllId).on('click', function(){
            var chekbox = $(this);
            var campaigns = ListsSites.getCampaigns();
            var isCheked = !chekbox.hasClass('checked');

            if(isCheked)
                campaigns.addClass('checked');
            else
                campaigns.removeClass('checked');

            $.each(ListsSites.getCampaignsInputs(), function(index, value){
                value.attr('checked', isCheked);
            });
        });
    },

    removeHiddenCampaigns: function(){
        $.each(ListsSites.getCampaigns(), function(index, value){
            $('input[value="0"]', value).remove();
            var input = $('input[value!="0"]', value);
            input.attr('name', input.attr('name')+'[]');
        });
    },

    getCampaigns: function(){
        if(ListsSites.campaigns == null){
            ListsSites.campaigns = $(ListsSites.campaignsId);
        }
        return ListsSites.campaigns;
    },

    getCampaignsInputs: function(){
        if(ListsSites.campaignsInputs == null){
            ListsSites.campaignsInputs = [];

            $.each(ListsSites.getCampaigns(), function(index, value){
                ListsSites.campaignsInputs[index] = $('input[value!="0"]', value);
            });
        }
        return ListsSites.campaignsInputs;
    }
}