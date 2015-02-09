AdsList = {

    editableContainerId: '.notice',
    editableAttrId: '.editable-data',
    statusChangerId: 'input.teaser_switch',
    datepickerId: '#datepicker-01',
    periodId: '#period',
    dateFormat: 'yy-mm-dd',
    currentDate: new Date(),
    startDate: '',
    endDate: '',
    statusBtn: '.campaign-status-btn',

    setStartAndEndText: null,
    monthNames: null,
    monthNamesShort: null,
    dayNames: null,
    dayNamesShort: null,
    dayNamesMin: null,

    init: function(data){
        if(typeof data != 'undefined'){
            var attributes = [
                'editableAttrId',
                'datepickerId',
                'periodId',
                'dateFormat',
                'currentDate',
                'startDate',
                'endDate',
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
                    AdsList[element] = data[element];
            });
        }

        AdsList.setHandlers();
    },

    setHandlers: function(){
        AdsList.setChangeStatus();
        AdsList.setCalendar();
        AdsList.setCampaignStatus();
        //AdsList.setEditableAttributes();

        Main.setCheckBoxesFix();
    },

    setEditableAttributes: function(){
        $(AdsList.editableContainerId).on('click', AdsList.editableAttrId, function(){
            console.log($(this).attr('data-list'));
            return false;
        });
    },

    setChangeStatus: function(){
        $(AdsList.statusChangerId).bootstrapSwitch().on('change', function(event){
            var element = $(this);

            Main.ajax({
                url: element.attr('data-url'),
                data: 'checked='+event.target.checked
            });
        });
    },

    setCalendar: function(){
        $('.calendar').datepickerDark({
            datepickerSelector: AdsList.datepickerId,
            periodSelector: AdsList.periodId,
            format: AdsList.dateFormat,
            currentDate: AdsList.currentDate,
            startDate: AdsList.startDate,
            endDate: AdsList.endDate,
            monthNames: AdsList.monthNames,
            monthNamesShort: AdsList.monthNamesShort,
            dayNames: AdsList.dayNames,
            dayNamesShort: AdsList.dayNamesShort,
            dayNamesMin: AdsList.dayNamesMin,
            onChange: function(start, end){
                window.location = Main.addToUrl({startDate: start, endDate: end}, document.URL);
            }
        });
    },

    setCampaignStatus: function(){
        $(AdsList.statusBtn).on('click', function(){
            var url = Main.removeFromUrl(['status']);
            window.location = Main.addToUrl({status: $(this).attr('data-status')}, url);
        });
    }

};