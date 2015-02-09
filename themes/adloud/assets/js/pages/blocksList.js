BlocksList = {

    getModalUrl: '',
    statusCheckBoxId: 'input.blocks_switch',
    datepickerId: '#datepicker-01',
    periodId: '#period',
    dateFormat: 'yy-mm-dd',
    currentDate: new Date(),
    startDate: '',
    endDate: '',
    showModal: false,
    clipboardPath: '',
    addBlockButtonId: 'a.btn-add-block',
    proFormatName: '',
    lightFormatName: '',
    lightFormatUrl: '',
    proFormatUrl: '',
    blockTypeSelectorId: '.block_type_selector',
    blockType: '',
    showBlockCodeId: 'a.show-block-code',
    selectTypeLabel: 'label.block_type_selector',
    messageText: 'Код копирован в буфер',
    statusBtn: '.campaign-status-btn',

    siteId: null,
    setStartAndEndText: null,
    monthNames: null,
    monthNamesShort: null,
    dayNames: null,
    dayNamesShort: null,
    dayNamesMin: null,

    init: function(data){

        var attributes = [
            'getModalUrl',
            'statusCheckBoxId',
            'periodId',
            'datepickerId',
            'dateFormat',
            'currentDate',
            'startDate',
            'endDate',
            'showModal',
            'clipboardPath',
            'addBlockButtonId',
            'proFormatName',
            'lightFormatName',
            'lightFormatUrl',
            'proFormatUrl',
            'blockTypeSelectorId',
            'blockType',
            'showBlockCodeId',
            'selectTypeLabel',
            'messageText',
            'statusBtn',
            'setStartAndEndText',
            'monthNames',
            'monthNamesShort',
            'dayNames',
            'dayNamesShort',
            'dayNamesMin'
        ];

        $.each(attributes, function(index, element){
            if(typeof data[element] != 'undefined')
                BlocksList[element] = data[element];
        });

        BlocksList.setHandlers();
    },

    setHandlers: function(){
        BlocksList.setShowModal();
        BlocksList.setCheckBoxStatus();
        BlocksList.setCalendar();
        BlocksList.setAddBlock();
        BlocksList.setShowBlockCode();
        BlocksList.setSelectTypeLabel();
        BlocksList.setCampaignStatus();

        Main.setCheckBoxesFix();
    },

    setShowModal: function(){
        if(BlocksList.showModal){
            Main.showModal();
            BlocksList.setModalSubmit();
            BlocksList.setToCash();
        }
    },

    setModalSubmit: function(){
        $(Main.modalButtonCancelId).on('click', function(){
            Main.closeModal();
            return false;
        });
    },

    setToCash: function(){
        var client = new ZeroClipboard($(Main.modalButtonOkId));
        client.on('ready', function(readyEvent){
            client.on('aftercopy', function(event){
                //event.target.style.display = 'none';
                $(event.target).attr('disabled', true);
                Main.showMessage(BlocksList.messageText);
            });
        });
    },

    setCheckBoxStatus: function(){
        $(BlocksList.statusCheckBoxId).on('change', function(event){
            Main.ajax({
                url: $(event.target).attr('data-url'),
                data: 'checked='+event.target.checked
            });
        });
    },

    setCalendar: function(){
        $('.calendar').datepickerDark({
            datepickerSelector: BlocksList.datepickerId,
            periodSelector: BlocksList.periodId,
            format: BlocksList.dateFormat,
            currentDate: BlocksList.currentDate,
            startDate: BlocksList.startDate,
            endDate: BlocksList.endDate,
            monthNames: BlocksList.monthNames,
            monthNamesShort: BlocksList.monthNamesShort,
            dayNames: BlocksList.dayNames,
            dayNamesShort: BlocksList.dayNamesShort,
            dayNamesMin: BlocksList.dayNamesMin,
            onChange: function(start, end){
                window.location = Main.addToUrl({startDate: start, endDate: end}, document.URL);
            }
        });
    },

    setAddBlock: function(){
        /*$(BlocksList.addBlockButtonId).on('click', function(){
            BlocksList.getBlockPreview();
            return false;
        });

        if(typeof SitesList != 'undefined'){
            $(SitesList.addBlockButtonId).on('click', function(){
                BlocksList.siteId = $(this).attr('data-id');
                var url = Main.addToUrl({id: BlocksList.siteId}, BlocksList.getModalUrl);
                BlocksList.getBlockPreview(url);
                return false;
            });
        }*/
    },

    getBlockPreview: function(url){

        if(typeof url == 'undefined')
            url = BlocksList.getModalUrl;

        Main.ajax({
            url: url,
            data: 'addNewBlock=1',
            success: function(json){
                if(json.html){
                    $(Main.mainModalId).html(json.html);
                    BlocksList.setBlockTypeSubmit();
                    BlocksList.setBlockTypeCancel();
                    BlocksList.setSelectFormat();
                    Main.showModal();
                }
            }
        });
    },

    setSelectFormat: function(){
        BlocksList.blockType = BlocksList.lightFormatName;

        $(BlocksList.blockTypeSelectorId).on('change', function(){
            BlocksList.blockType = $(this).val();
        });
    },

    setBlockTypeSubmit: function(){
        $(Main.modalButtonOkId).on('click', function(){
            if(BlocksList.blockType == BlocksList.lightFormatName)
                var url = BlocksList.lightFormatUrl;
            else
                var url = BlocksList.proFormatUrl;

            if(BlocksList.siteId != null)
                url = Main.addToUrl({siteId: BlocksList.siteId}, url);

            window.location = url;
        });
    },

    setBlockTypeCancel: function(){
        $(Main.modalButtonCancelId).on('click', function(){
            Main.closeModal();
            return false;
        });
    },

    setShowBlockCode: function(){
        $(BlocksList.showBlockCodeId).on('click', function(){
            Main.ajax({
                url: $(this).attr('href'),
                data: 'showBlockCode=1',
                success: function(json){
                    if(json.html){
                        $(Main.mainModalId).html(json.html);
                        BlocksList.setToCash();
                        BlocksList.setBlockTypeCancel();
                        Main.showModal();
                    }
                }
            });

            return false;
        });
    },

    setSelectTypeLabel: function(){
        $(Main.mainModalId).on('click', BlocksList.selectTypeLabel, function(){
            $(this).prev().click();
        });
    },

    setCampaignStatus: function(){
        $(BlocksList.statusBtn).on('click', function(){
            var url = Main.removeFromUrl(['status']);
            window.location = Main.addToUrl({status: $(this).attr('data-status')}, url);
        });
    }
}