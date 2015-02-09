/**
 * Created with JetBrains PhpStorm.
 * User: JanGolle
 * Date: 22.09.14
 * Time: 14:39
 * To change this template use File | Settings | File Templates.
 */

StatsTraffic = {
    dateSubmitId: '.datepicker-submit',
    showsId: 'stat-shows',
    clicksId: 'stat-clicks',
    showsLabels: [],
    showsData: [],
    clicksLabels: [],
    clicksData: [],

    init: function(data){
        if(typeof data != 'undefined'){
            var attributes = [
                'dateSubmitId',
                'showsId',
                'clicksId',
                'showsLabels',
                'showsData',
                'clicksLabels',
                'clicksData'
            ];

            $.each(attributes, function(index, element){
                if(typeof data[element] != 'undefined')
                    StatsTraffic[element] = data[element];
            });
        }

        StatsTraffic.setHandlers();
    },

    setHandlers: function(){
        StatsTraffic.setCharts();
        StatsTraffic.setDatePicker();
    },

    setCharts: function(){
        var options = {
            responsive: true,
            legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"
        };

        var showsChartData = {
            showTooltips: true,
            tooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= value %>",
            tooltipEvents: ["mousemove", "touchstart", "touchmove"],
            labels : StatsTraffic.showsLabels,
            datasets : [
                {
                    fillColor : "rgba(220,220,220,0.5)",
                    strokeColor : "rgba(220,220,220,1)",
                    pointColor : "rgba(220,220,220,1)",
                    pointStrokeColor : "#fff",
                    data : StatsTraffic.showsData
                }
            ]

        };
        var clicksChartData = {
            showTooltips: true,
            tooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= value %>",
            tooltipEvents: ["mousemove", "touchstart", "touchmove"],
            labels : StatsTraffic.clicksLabels,
            datasets : [
                {
                    fillColor : "rgba(159,199,225,0.5)",
                    strokeColor : "rgba(100,135,215,0.3)",
                    pointColor : "rgba(121,127,105,1)",
                    pointStrokeColor : "#fff",
                    data : StatsTraffic.clicksData
                }
            ]

        };
        new Chart(document.getElementById(StatsTraffic.showsId).getContext("2d")).Line(showsChartData, options);
        new Chart(document.getElementById(StatsTraffic.clicksId).getContext("2d")).Line(clicksChartData, options);
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

        $(StatsTraffic.dateSubmitId).on('click', function(){
            var from = $('input[name="from"]').val();
            var to = $('input[name="to"]').val();

            if(from !== '' && to !== ''){
                document.location = Main.removeFromUrl(['startDate','endDate'],document.location.href) + '?startDate=' + from + '&endDate=' + to;
            }
        });
    }
}