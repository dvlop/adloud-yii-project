/**
 * Created with JetBrains PhpStorm.
 * User: JanGolle
 * Date: 22.09.14
 * Time: 14:44
 * To change this template use File | Settings | File Templates.
 */

StatsUser = {
    dateSubmitId: '.datepicker-submit',
    usersId: 'stat-users',
    usersLabels: [],
    usersData: [],

    init: function(data){
        if(typeof data != 'undefined'){
            var attributes = [
                'dateSubmitId',
                'usersId',
                'usersLabels',
                'usersData',
            ];

            $.each(attributes, function(index, element){
                if(typeof data[element] != 'undefined')
                    StatsUser[element] = data[element];
            });
        }

        StatsUser.setHandlers();
    },

    setHandlers: function(){
        StatsUser.setCharts();
        StatsUser.setDatePicker();
    },

    setCharts: function(){
        var options = {
            responsive: true,
            legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"
        };

        var usersChartData = {
            showTooltips: true,
            tooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= value %>",
            tooltipEvents: ["mousemove", "touchstart", "touchmove"],
            labels : StatsUser.usersLabels,
            datasets : [
                {
                    fillColor : "rgba(220,220,220,0.5)",
                    strokeColor : "rgba(220,220,220,1)",
                    pointColor : "rgba(220,220,220,1)",
                    pointStrokeColor : "#fff",
                    data : StatsUser.usersData
                }
            ]

        };
        new Chart(document.getElementById(StatsUser.usersId).getContext("2d")).Line(usersChartData, options);
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

        $(StatsUser.dateSubmitId).on('click', function(){
            var from = $('input[name="from"]').val();
            var to = $('input[name="to"]').val();

            if(from !== '' && to !== ''){
                document.location = Main.removeFromUrl(['startDate','endDate'],document.location.href) + '?startDate=' + from + '&endDate=' + to;
            }
        });
    }
}