/**
 * Created with JetBrains PhpStorm.
 * User: JanGolle
 * Date: 18.09.14
 * Time: 19:08
 * To change this template use File | Settings | File Templates.
 */

Dashboard = {
    categoryTodayId: 'category-today',
    categoryAlltimeId: 'category-all',
    siteTodayId: 'site-today',
    siteAlltimeId: 'site-all',
    colors: ['#2A3542','#6CCACA','#57C8F2','#FA6C60','#A9D86E','#90ADAB','#9540ED','#F8D346','#3D5059','#D5D5D5',
        '#3DAF2C','#A4BCC4','#BECEC4','#415464','#EB0045','#B3E2D9','#C2A377','#C67A8C','#566166','#7E9FB9','#6B8F8E',
        '#698FAA','#3B4F70','#9DC7CF'],
    categoryTodayData: [],
    categoryAlltimeData: [],
    siteTodayData: [],
    siteAlltimeData: [],

    init: function(data){
        if(typeof data != 'undefined'){
            var attributes = [
                'categoryTodayId',
                'categoryAlltimeId',
                'siteTodayId',
                'siteAlltimeId',
                'colors',
                'categoryTodayData',
                'categoryAlltimeData',
                'siteTodayData',
                'siteAlltimeData'
            ];

            $.each(attributes, function(index, element){
                if(typeof data[element] != 'undefined')
                    Dashboard[element] = data[element];
            });
        }

        Dashboard.setHandlers();
    },

    setHandlers: function(){
        Dashboard.setColors();
        Dashboard.setCharts();
    },

    setColors: function(){
        for(var key in Dashboard.categoryTodayData){
            Dashboard.categoryTodayData[key].color = Dashboard.colors[key];
        }
        for(var key in Dashboard.categoryAlltimeData){
            Dashboard.categoryAlltimeData[key].color = Dashboard.colors[key];
        }
        for(var key in Dashboard.siteTodayData){
            Dashboard.siteTodayData[key].color = Dashboard.colors[key];
        }
        for(var key in Dashboard.siteAlltimeData){
            Dashboard.siteAlltimeData[key].color = Dashboard.colors[key];
        }
    },

    setCharts: function(){
        new Chart(document.getElementById(Dashboard.categoryTodayId).getContext("2d")).Pie(Dashboard.categoryTodayData);
        new Chart(document.getElementById(Dashboard.categoryAlltimeId).getContext("2d")).Pie(Dashboard.categoryAlltimeData);
        new Chart(document.getElementById(Dashboard.siteTodayId).getContext("2d")).Pie(Dashboard.siteTodayData);
        new Chart(document.getElementById(Dashboard.siteAlltimeId).getContext("2d")).Pie(Dashboard.siteAlltimeData);
    }
}