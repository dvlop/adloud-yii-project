/**
 * Created with JetBrains PhpStorm.
 * User: JanGolle
 * Date: 22.09.14
 * Time: 14:41
 * To change this template use File | Settings | File Templates.
 */

StatsCategories = {
    dateSubmitId: '.datepicker-submit',
    categoriesId: 'stat-categories',
    colors: ['#2A3542','#6CCACA','#57C8F2','#FA6C60','#A9D86E','#90ADAB','#9540ED','#F8D346','#3D5059','#D5D5D5',
        '#3DAF2C','#A4BCC4','#BECEC4','#415464','#EB0045','#B3E2D9','#C2A377','#C67A8C','#566166','#7E9FB9','#6B8F8E',
        '#698FAA','#3B4F70','#9DC7CF'],
    categoriesData: [],

    init: function(data){
        if(typeof data != 'undefined'){
            var attributes = [
                'dateSubmitId',
                'categoriesId',
                'colors',
                'categoriesData'
            ];

            $.each(attributes, function(index, element){
                if(typeof data[element] != 'undefined')
                    StatsCategories[element] = data[element];
            });
        }

        StatsCategories.setHandlers();
    },

    setHandlers: function(){
        StatsCategories.setColors();
        StatsCategories.setCharts();
        StatsCategories.setDatePicker();
    },

    setColors: function(){
        for(var key in StatsCategories.categoriesData){
            StatsCategories.categoriesData[key].color = StatsCategories.colors[key];
        }
    },

    setCharts: function(){
        new Chart(document.getElementById(StatsCategories.categoriesId).getContext("2d")).Pie(StatsCategories.categoriesData);
    },

    setDatePicker: function(){
        var checkin = $('.dpd1').datepicker({
            onRender: function(date) {
                return date.valueOf() < now.valueOf() ? 'disabled' : '';
            },
            format: 'yyyy-mm-dd',
            maxDate: 0
        }).on('changeDate', function(ev) {
                if (ev.date.valueOf() > checkout.date.valueOf()) {
                    var newDate = new Date(ev.date)
                    newDate.setDate(newDate.getDate() + 1);
                    checkout.setValue(newDate);
                }
                checkin.hide();
                $('.dpd2')[0].focus();
            }).data('datepicker');
        var checkout = $('.dpd2').datepicker({
            onRender: function(date) {
                return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
            },
            format: 'yyyy-mm-dd',
            maxDate: 0
        }).on('changeDate', function(ev) {
                checkout.hide();
            }).data('datepicker');

        $(StatsCategories.dateSubmitId).on('click', function(){
            var from = $('input[name="from"]').val();
            var to = $('input[name="to"]').val();

            if(from !== '' && to !== ''){
                document.location = Main.removeFromUrl(['startDate','endDate'],document.location.href) + '?startDate=' + from + '&endDate=' + to;
            }
        });
    }
}