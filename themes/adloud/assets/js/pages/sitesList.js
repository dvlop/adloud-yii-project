SitesList = {

    statusCheckBoxId: 'table.adloud_table td div.switch',
    datepickerId: '#datepicker-01',
    periodId: '#period',
    dateFormat: 'dd-mm-yy',
    currentDate: new Date(),
    startDate: '',
    endDate: '',
    addBlockButtonId: 'a.add-block',
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
                'statusCheckBoxId',
                'datepickerId',
                'periodId',
                'dateFormat',
                'currentDate',
                'startDate',
                'endDate',
                'addBlockButtonId',
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
                    SitesList[element] = data[element];
            });
        }

        SitesList.setHandlers();
    },

    setHandlers: function(){
        Main.setCheckBoxesFix();

        SitesList.setCheckBoxStatus();
        SitesList.setCalendar();
        SitesList.setCampaignStatus();
    },

    setCheckBoxStatus: function(){
        $(SitesList.statusCheckBoxId).on('change', function(event){
            $.ajax({
                type: 'POST',
                cache: false,
                url: $(this).attr('data-url'),
                data: 'checked='+event.target.checked,
                dataType: 'json',
                success: function(json){
                    if(json.error){
                        Main.showError(json.error);
                    }else{
                        Main.showMessage(json.message);
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    console.log(errorThrown);
                    //Main.showError(errorThrown);
                }
            });
        });
    },

    setCalendar: function(){
        $('.calendar').datepickerDark({
            datepickerSelector: SitesList.statusCheckBoxId,
            periodSelector: SitesList.periodId,
            format: SitesList.dateFormat,
            currentDate: SitesList.currentDate,
            startDate: SitesList.startDate,
            endDate: SitesList.endDate,
            monthNames: SitesList.monthNames,
            monthNamesShort: SitesList.monthNamesShort,
            dayNames: SitesList.dayNames,
            dayNamesShort: SitesList.dayNamesShort,
            dayNamesMin:SitesList.dayNamesMin,
            onChange: function(start, end){
               window.location = Main.addToUrl({startDate: start, endDate: end}, document.URL);
            }
        });
    },

    setCampaignStatus: function(){
        $(SitesList.statusBtn).on('click', function(){
            var url = Main.removeFromUrl(['status']);
            window.location = Main.addToUrl({status: $(this).attr('data-status')}, url);
        });
    }
}