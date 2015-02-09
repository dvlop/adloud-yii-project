AdvStats = {

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
    statUrl: '/advertiser/stats/',
    setStartAndEndId: '.ui-datepicker-buttonpane',

    setStartAndEndText: null,
    monthNames: null,
    monthNamesShort: null,
    dayNames: null,
    dayNamesShort: null,
    dayNamesMin: null,

    init: function(data){
        if(typeof data != 'undefined'){
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
                'setStartAndEndId',
                'setStartAndEndText',
                'monthNames',
                'monthNamesShort',
                'dayNames',
                'dayNamesShort',
                'dayNamesMin'
            ];

            $.each(attributes, function(index, element){
                if(typeof data[element] != 'undefined')
                    AdvStats[element] = data[element];
            });
        }

        AdvStats.setHandlers();
    },

    setHandlers: function(){
        AdvStats.setTypeSelector();
        AdvStats.setPeriodSelector();
        AdvStats.setCalendar();
        if(AdvStats.type == 'date'){
            AdvStats.setCaret();
        }
        if(AdvStats.showsData.length > 0){
            AdvStats.setChart();
        }
    },

    setTypeSelector: function(){
        $(AdvStats.typeSelectorId).selectpicker({
            style: 'btn btn-default'
        }).on('change', function(){
            var url = Main.removeFromUrl(['type']);
            document.location.href = Main.addToUrl('type='+$(this).val(), url);
        });
    },

    setPeriodSelector: function(){
        $(AdvStats.periodSelectorId).selectpicker({
            style: 'btn btn-default'
        }).on('change', function(){
            var url = Main.removeFromUrl(['status']);
            window.location = Main.addToUrl({type: AdvStats.type, startDate: AdvStats.startDate, endDate: AdvStats.endDate, status: $(this).val()}, url);
        });
    },

    setCalendar: function(){
        var bottomLine = $(AdvStats.setStartAndEndId);
        bottomLine.html(AdvStats.setStartAndEndText+bottomLine.html());

        $('.calendar').datepickerDark({
            datepickerSelector: AdvStats.datepickerId,
            periodSelector: AdvStats.periodId,
            format: AdvStats.dateFormat,
            currentDate: AdvStats.currentDate,
            startDate: AdvStats.startDate,
            endDate: AdvStats.endDate,
            monthNames: AdvStats.monthNames,
            monthNamesShort: AdvStats.monthNamesShort,
            dayNames: AdvStats.dayNames,
            dayNamesShort: AdvStats.dayNamesShort,
            dayNamesMin: AdvStats.dayNamesMin
        });
    },

    setChart: function(){
        var lineChartData = {
            labels : AdvStats.chartLabels,
            datasets : [
                {
                    fillColor : "rgba(243, 156, 18, 0.3)",
                    strokeColor : "#f39c12",
                    pointColor : "#f39c12",
                    pointStrokeColor : "#fff",
                    pointHighlightFill : "#2c3e50",
                    pointHighlightStroke : "#fff",
                    data : AdvStats.showsData
                },
                {
                    fillColor : "rgba(150, 186, 219, 0.3)",
                    strokeColor : "#5B90BF",
                    pointColor : "#5B90BF",
                    pointStrokeColor : "#fff",
                    pointHighlightFill : "#2c3e50",
                    pointHighlightStroke : "#fff",
                    data : AdvStats.clicksData
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

        $(AdvStats.toggleChartId).on('click', function(){
            $('.chart-container, .chart-title').toggle();
            $(AdvStats.toggleChartId).toggleClass(' hide show');
        })
    },

    setCaret: function(){
        $('.selected-statistic-period-day').on('click', function(){
            var selected_period =  $(this).data('date');
            $(this).parents('tr').nextAll('tr[data-day='+selected_period+']').toggleClass('hide');
            $(this).children('.caret').toggleClass('open');
        });
    }

};
