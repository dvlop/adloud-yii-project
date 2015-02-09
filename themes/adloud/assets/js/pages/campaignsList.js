CampList = {

    statusCheckBoxId: 'table.adloud_table td div.switch',
    datepickerId: '#datepicker-01',
    periodId: '#period',
    dateFormat: 'dd-mm-yy',
    currentDate: new Date(),
    startDate: '',
    endDate: '',
    changeStatusId: 'input.camp_switch',
    statusBtn: '.campaign-status-btn',

    setStartAndEndText: null,
    monthNames: null,
    monthNamesShort: null,
    dayNames: null,
    dayNamesShort: null,
    dayNamesMin: null,

    init: function(data){

        var attributes = [
            'statusCheckBoxId',
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
                CampList[element] = data[element];
        });

        CampList.setHandlers();
    },

    setHandlers: function(){
        CampList.setCalendar();
        CampList.setChangeStatus();
        CampList.setCampaignStatus();
        Main.setCheckBoxesFix();
    },

    setCalendar: function(){
        $('.calendar').datepickerDark({
            datepickerSelector: CampList.statusCheckBoxId,
            periodSelector: CampList.periodId,
            format: CampList.dateFormat,
            currentDate: CampList.currentDate,
            startDate: CampList.startDate,
            endDate: CampList.endDate,
            monthNames: CampList.monthNames,
            monthNamesShort: CampList.monthNamesShort,
            dayNames: CampList.dayNames,
            dayNamesShort: CampList.dayNamesShort,
            dayNamesMin:CampList.dayNamesMin,
            onChange: function(start, end){
                window.location = Main.addToUrl({startDate: start, endDate: end}, document.URL);
            }
        });
    },

    setChangeStatus: function(){
        $(CampList.changeStatusId).bootstrapSwitch().on('change', function(event){
            var element = $(this);
            Main.ajax({
                event: element,
                data: 'checked='+event.target.checked+'&id='+element.attr('data-id')
            });
        });
    },

    setCampaignStatus: function(){
        $(CampList.statusBtn).on('click', function(){
            var url = Main.removeFromUrl(['status']);
            window.location = Main.addToUrl({status: $(this).attr('data-status')}, url);
        });
    }
};