/**
 * Created with JetBrains PhpStorm.
 * User: JanGolle
 * Date: 22.09.14
 * Time: 14:44
 * To change this template use File | Settings | File Templates.
 */

StatsFinance = {
    dateSubmitId: '.datepicker-submit',
    incomeId: 'stat-income',
    moneyInOutId: 'stat-money',
    incomeLabels: [],
    incomeData: [],
    moneyInLabels: [],
    moneyInData: [],
    moneyOutLabels: [],
    moneyOutData: [],

    init: function(data){
        if(typeof data != 'undefined'){
            var attributes = [
                'dateSubmitId',
                'incomeId',
                'moneyInOutId',
                'incomeLabels',
                'incomeData',
                'moneyInLabels',
                'moneyInData',
                'moneyOutLabels',
                'moneyOutData'
            ];

            $.each(attributes, function(index, element){
                if(typeof data[element] != 'undefined')
                    StatsFinance[element] = data[element];
            });
        }

        StatsFinance.setHandlers();
    },

    setHandlers: function(){
        StatsFinance.setCharts();
        StatsFinance.setDatePicker();
    },

    setCharts: function(){
        var options = {
            responsive: true,
            legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"
        };

        var incomeChartData = {
            showTooltips: true,
            tooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= value %>",
            tooltipEvents: ["mousemove", "touchstart", "touchmove"],
            labels : StatsFinance.incomeLabels,
            datasets : [
                {
                    fillColor : "rgba(220,220,220,0.5)",
                    strokeColor : "rgba(220,220,220,1)",
                    pointColor : "rgba(220,220,220,1)",
                    pointStrokeColor : "#fff",
                    data : StatsFinance.incomeData
                }
            ]

        };
        var moneyInOutChartData = {
            showTooltips: true,
            tooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= value %>",
            tooltipEvents: ["mousemove", "touchstart", "touchmove"],
            labels : StatsFinance.moneyInLabels,
            datasets : [
                {
                    fillColor : "rgba(151,187,205,0.5)",
                    strokeColor : "rgba(151,187,205,1)",
                    pointColor : "rgba(151,187,205,1)",
                    pointStrokeColor : "#fff",
                    data : StatsFinance.moneyInData
                },
                {
                    fillColor : "rgba(159,199,225,0.5)",
                    strokeColor : "rgba(100,135,215,0.3)",
                    pointColor : "rgba(121,127,105,1)",
                    pointStrokeColor : "#fff",
                    data : StatsFinance.moneyOutData
                }
            ]

        };
        new Chart(document.getElementById(StatsFinance.incomeId).getContext("2d")).Line(incomeChartData, options);
        new Chart(document.getElementById(StatsFinance.moneyInOutId).getContext("2d")).Line(moneyInOutChartData, options);
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

        $(StatsFinance.dateSubmitId).on('click', function(){
            var from = $('input[name="from"]').val();
            var to = $('input[name="to"]').val();

            if(from !== '' && to !== ''){
                document.location = Main.removeFromUrl(['startDate','endDate'],document.location.href) + '?startDate=' + from + '&endDate=' + to;
            }
        });
    }
}