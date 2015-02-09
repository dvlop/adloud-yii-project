AdsList = {

    getModalUrl: '',
    changeContentStatusInputId: 'input.set-ads-bool',
    statusSelectorId: '#ads-status-selector-id',
    buttonsContainerId: 'td.button-column',
    statusChangerId: '.ads-status-button',
    adsStatus: 1000,

    init: function(data){
        if(typeof data != 'undefined'){
            var attributes = [
                'getModalUrl',
                'changeContentStatusInputId',
                'statusSelectorId',
                'buttonsContainerId',
                'statusChangerId',
                'adsStatus'
            ];

            $.each(attributes, function(index, element){
                if(typeof data[element] != 'undefined')
                    AdsList[element] = data[element];
            });
        }

        AdsList.setHandlers();
    },

    setHandlers: function(){
        AdsList.setStatusSelector();
    },

    setStatusSelector: function(){
        $(AdsList.statusSelectorId).on('change', function(){
            var selector = $(this);
            var name = selector.attr('name');
            var value = selector.val();

            window.location = Main.addToUrl(name+'='+value, document.URL);
        });
    },

    changeContentType: function(input){
        Main.ajax({
            url: input.attr('data-url'),
            data: 'value='+input.prop('checked')
        });
    },

    openModalWindow: function(button){
        Main.ajax({
            url: AdsList.getModalUrl,
            //data: 'adsId='+button.attr('data-id'),
            data: 'adsId=1',
            success: function(json){
                if(json.html){
                    $(Main.mainModalId).html(json.html);
                    Main.openModal();
                    AdsList.setModalButtons(button);
                }
            }
        });

        return false;
    },

    setModalButtons: function(button){
        $(Main.modalButtonCancelId).on('click', function(){
            Main.closeModal();
        });

        $(Main.modalButtonOkId).on('click', function(){
            AdsList.changeAdsStatus(button);
        });
    },

    changeAdsStatus: function(button){
        Main.ajax({
            url: button.attr('href'),
            data: 'message='+$(Main.modalTextAreaId).val(),
            success: function(json){
                if(!json.error){
                    Main.closeModal();
                }

                if(json.message && json.message == 'remove'){
                    button.parent('td').parent('tr').remove();
                }else if(json.html){
                    button.parent('td').html(json.html);
                }
            }
        });

        return false;
    }
}