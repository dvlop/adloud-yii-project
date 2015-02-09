WebmasterStats = {

    datepickerId: '#datepicker-01',
    typeSelectorId: '#type',
    periodSelectorId: '#day-period',
    period: 'month',
    periodId: '#period',
    dateFormat: 'yy-mm-dd',
    currentDate: new Date(),
    startDate: '',
    endDate: '',
    type: '',
    chartLabels: '',
    showsData: '',
    clicksData: '',
    toggleChartId: '.toggle-chart',
    statUrl: '/webmaster/stats/',

    setStartAndEndText: null,
    monthNames: null,
    monthNamesShort: null,
    dayNames: null,
    dayNamesShort: null,
    dayNamesMin: null,

    init: function(data){
        var attributes = [
            'datepickerId',
            'typeSelectorId',
            'periodSelectorId',
            'period',
            'dateFormat',
            'currentDate',
            'startDate',
            'endDate',
            'type',
            'chartLabels',
            'showsData',
            'clicksData',
            'toggleChartId',
            'showChart',
            'statUrl',
            'setStartAndEndText',
            'monthNames',
            'monthNamesShort',
            'dayNames',
            'dayNamesShort',
            'dayNamesMin'
        ];

        $.each(attributes, function(index, element){
            if(typeof data[element] != 'undefined')
                WebmasterStats[element] = data[element];
        });

        WebmasterStats.setHandlers();
    },

    setHandlers: function(){
        WebmasterStats.setTypeSelector();
        WebmasterStats.setPeriodSelector();
        WebmasterStats.setCalendar();
        if(WebmasterStats.type == 'date'){
            WebmasterStats.setCaret();
        }
        if(WebmasterStats.showsData.length > 0){
            WebmasterStats.setChart();
        }
    },

    setTypeSelector: function(){
        $(WebmasterStats.typeSelectorId).selectpicker({
            style: 'btn btn-default'
        }).on('change', function(){
                var url = Main.removeFromUrl(['period', 'startDate', 'endDate']);
                document.location.href = Main.addToUrl('type='+$(this).val(), url);
            });
    },

    setPeriodSelector: function(){
        $(WebmasterStats.periodSelectorId).selectpicker({
            style: 'btn btn-default'
        }).on('change', function(){
                var url = Main.removeFromUrl(['status']);
                window.location = Main.addToUrl({type: WebmasterStats.type, startDate: WebmasterStats.startDate, endDate: WebmasterStats.endDate, status: $(this).val()}, url);
            });
    },

    setCalendar: function(){
        $('.calendar').datepickerDark({
            datepickerSelector: WebmasterStats.datepickerId,
            periodSelector: WebmasterStats.periodId,
            format: WebmasterStats.dateFormat,
            currentDate: WebmasterStats.currentDate,
            startDate: WebmasterStats.startDate,
            endDate: WebmasterStats.endDate,
            monthNames: WebmasterStats.monthNames,
            monthNamesShort: WebmasterStats.monthNamesShort,
            dayNames: WebmasterStats.dayNames,
            dayNamesShort: WebmasterStats.dayNamesShort,
            dayNamesMin: WebmasterStats.dayNamesMin
        });
    },

    setChart: function(){
        var lineChartData = {
            labels : WebmasterStats.chartLabels,
            datasets : [
                {
                    fillColor : "rgba(243, 156, 18, 0.3)",
                    strokeColor : "#f39c12",
                    pointColor : "#f39c12",
                    pointStrokeColor : "#fff",
                    pointHighlightFill : "#2c3e50",
                    pointHighlightStroke : "#fff",
                    data : WebmasterStats.showsData
                },
                {
                    fillColor : "rgba(150, 186, 219, 0.3)",
                    strokeColor : "#5B90BF",
                    pointColor : "#5B90BF",
                    pointStrokeColor : "#fff",
                    pointHighlightFill : "#2c3e50",
                    pointHighlightStroke : "#fff",
                    data : WebmasterStats.clicksData
                }
            ]

        };

        window.onload = function(){
            var ctx = document.getElementById("chart").getContext("2d");
            window.myLine = new Chart(ctx).Line(lineChartData, {
                scaleLineColor: "rgba(0,0,0,0)",
                scaleFontFamily: "'Open Sans', sans-serif",
                scaleFontColor: "#9ea7b3",
                responsive: true,
                tooltipFillColor: "#2c3e50",
                tooltipFontFamily: "'Open Sans', sans-serif",
                tooltipFontSize: 12,
                tooltipYPadding: 9,
                tooltipXPadding: 12,
                tooltipCaretSize: 6,
                tooltipTemplate: "<%= value %>",
                scaleShowGridLines : false,
                bezierCurveTension : 0.2,
                pointDotRadius : 6,
                pointDotStrokeWidth : 2,
                datasetStrokeWidth : 4,
            });
        };

        $(WebmasterStats.toggleChartId).on('click', function(){
            $('.chart-container, .chart-title').toggle();
            $(WebmasterStats.toggleChartId).toggleClass(' hide show');
        })
    },

    setCaret: function(){
        $('.selected-statistic-period-day').on('click', function(){
            var selected_period =  $(this).data('date');
            $(this).parents('tr').nextAll('tr[data-day='+selected_period+']').toggleClass('hide');
            $(this).children('.caret').toggleClass('open');
        });
    }

}
