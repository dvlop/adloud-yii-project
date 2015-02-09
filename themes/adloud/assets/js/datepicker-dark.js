$.fn.datepickerDark = function(data){
    var cur = -1,
        prv = -1;

    var d1, d2;
    var startDate = new Date(data.startDate);
    var endDate = new Date(data.endDate);

    var startObj = {
        day: startDate.getDate(),
        month: startDate.getMonth(),
        year: startDate.getFullYear()
    };

    var endObj = {
        day: endDate.getDate(),
        month: endDate.getMonth(),
        year: endDate.getFullYear()
    };

    if(typeof data.monthNames == 'undefined' || !data.monthNames)
        data.monthNames = ["Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"];

    if(typeof data.monthNamesShort == 'undefined' || !data.monthNamesShort)
        data.monthNamesShort = ["Янв","Фев","Мар","Апр","Май","Июн","Июл","Авг","Сен","Окт","Ноя","Дек"];

    if(typeof data.dayNames == 'undefined' || !data.dayNames)
        data.dayNames = ["воскресенье","понедельник","вторник","среда","четверг","пятница","суббота"];

    if(typeof data.dayNamesShort == 'undefined' || !data.dayNamesShort)
        data.dayNamesShort = ["вск","пнд","втр","срд","чтв","птн","сбт"];

    if(typeof data.dayNamesMin == 'undefined' || !data.dayNamesMin)
        data.dayNamesMin = ["Вс","Пн","Вт","Ср","Чт","Пт","Сб"];

    $.datepicker.regional['ru'] = {
        prevText: '<span class="fui-triangle-left-large"></span>',
        nextText: '<span class="fui-triangle-right-large"></span>',
        monthNames: data.monthNames,
        monthNamesShort: data.monthNamesShort,
        dayNames: data.dayNames,
        dayNamesShort: data.dayNamesShort,
        dayNamesMin: data.dayNamesMin,
        weekHeader: 'Не',
        dateFormat: 'd MM, yy'

    };

    $.datepicker.setDefaults($.datepicker.regional['ru']);

    $("#datepicker").datepicker({
        altField: '.datepicker',
        numberOfMonths: 2,
        showOtherMonths: true,
        maxDate: +0,
        firstDay: 1,
        yearRange: '-1:+1',

        //Выделение выбранного дипазона
        beforeShowDay: function ( date ) {
            return [true, ( (date.getTime() >= Math.min(prv, cur) && date.getTime() <= Math.max(prv, cur)) ? 'date-range-selected' : '')];
        },

        //Передача значения начальной и конечной даты в инпут
        onSelect: function ( dateText, inst ) {
            prv = cur;
            cur = (new Date(inst.selectedYear, inst.selectedMonth, inst.selectedDay)).getTime();
            if (prv == -1 || prv == cur) {
                prv = cur;
                $('.datepicker').val(dateText);

                d1_format = $.datepicker.formatDate('yy-mm-dd', new Date(Math.min(prv, cur)), {});
                $('input[name="startDate"]').val(d1_format);
                $('input[name="endDate"]').val('');
            } else {
                d1 = $.datepicker.formatDate('d MM, y', new Date(Math.min(prv, cur)), {});
                d2 = $.datepicker.formatDate('d MM, y', new Date(Math.max(prv, cur)), {});

                d1_format = $.datepicker.formatDate('yy-mm-dd', new Date(Math.min(prv, cur)), {});
                d2_format = $.datepicker.formatDate('yy-mm-dd', new Date(Math.max(prv, cur)), {});

                $('input[name="startDate"]').val(d1_format);
                $('input[name="endDate"]').val(d2_format);

                $('.datepicker').val(d1 + ' - ' + d2);
            }
        }

    });

    //Открытие календаря по клику на инпут
    $('.datepicker, .datepicker-container .input-icon').click(function(){
        $('.hasDatepicker, .ui-datepicker-buttonpane, .datepicker-period-panel').toggle();
    });

    $('.close-calendar').click(function(){
        $('.hasDatepicker, .ui-datepicker-buttonpane, .datepicker-period-panel').hide();
    });

    //Дата по умолчанию (сегодняшняя дата)
    $('.ui-datepicker-today').click();
    $('[data-month="'+ startObj['month'] +'"][data-year="'+ startObj['year'] +'"] > a:contains("'+ startObj['day'] +'")').click();
    $('[data-month="'+ endObj['month'] +'"][data-year="'+ endObj['year'] +'"] > a:contains("'+ endObj['day'] +'")').click();

    //Выделение периодов
    $('.datepicker-period-panel li').click(function(){

        $('.datepicker-period-panel li.active').removeClass('active');
        $('.date-range-selected').removeClass('date-range-selected');
        $('.ui-state-active').removeClass('ui-state-active');

        $(this).addClass('active');

    });

    var today_date = new Date();//сегодняшняя дата
    var day_number = today_date.getDate();//число месяца
    var month = new Array(12);

    month[0] = "января";
    month[1] = "февраля";
    month[2] = "марта";
    month[3] = "апреля";
    month[4] = "мая";
    month[5] = "июня";
    month[6] = "июля";
    month[7] = "августа";
    month[8] = "сентября";
    month[9] = "октября";
    month[10] = "ноября";
    month[11] = "декабря";

    var month_name = month[today_date.getMonth()];//имя текущего месяца
    var year = today_date.getFullYear();//номер года
    var today = ''+day_number+' '+month_name+', '+year+'';//сегодняшняя дата в формате 11 января, 2014

    //Выделение сегодняшнего дня
    $('.today-period').click(function(){

        $('.ui-datepicker-today').click();
        $('.ui-datepicker-today').click();
    });

    //Выделение вчерашнего дня
    $('.yesterday-period').click(function(){
        if($('.ui-datepicker-today').prev('td').is('td')) {
            $('.ui-datepicker-today').prev('td').click();
            $('.ui-datepicker-today').prev('td').click();
        } else if($('.ui-datepicker-today').parent('tr').is('tr')) {
            $('.ui-datepicker-today').parent('tr').prev('tr').children('td:last-child').click();
            $('.ui-datepicker-today').parent('tr').prev('tr').children('td:last-child').click();
        } else {
            $('.ui-datepicker-group-first').find('table > tbody > :last-child > :not(.ui-state-disabled)').last()[0].click();
            $('.ui-datepicker-group-first').find('table > tbody > :last-child > :not(.ui-state-disabled)').last()[0].click();
        }
    });

    //Выделение текущей недели
    $('.week-period').click(function(){

        $('.ui-datepicker-today').click();
        $('.ui-datepicker-today').parent('tr').children().first()[0].click();

    });

    //Выделение прошлой недели
    $('.last-week-period').click(function(){
        if($('.ui-datepicker-today').parent().prev().children('td:not(.ui-state-disabled)').length < 7) {
            $('.ui-datepicker-group-first').find('table > tbody > :last-child > :first-child')[0].click();
            $('.ui-datepicker-today').parent().prev().children('td:not(.ui-state-disabled)')[$('.ui-datepicker-today').parent().prev().children('td:not(.ui-state-disabled)').length - 1].click();
        } else {
            $('.ui-datepicker-today').parent().prev().children('td:not(.ui-state-disabled)')[0].click();
            $('.ui-datepicker-today').parent().prev().children('td:not(.ui-state-disabled)')[$('.ui-datepicker-today').parent().prev().children('td:not(.ui-state-disabled)').length - 1].click();
        }
    });

    //Выделение месяца
    $('.month-period').click(function(){

        var month = $('.ui-datepicker-today').data('month');
        $('[data-month='+month+']')[0].click();
        $('[data-month='+month+']')[$('[data-month='+month+']').length - 1].click();
    });


    //Выделение прошлого месяца
    $('.last-month-period').click(function(){

        var last_month = $('.ui-datepicker-today').data('month') - 1;
        $('[data-month='+last_month+']')[0].click();
        $('[data-month='+last_month+']')[$('[data-month='+last_month+']').length - 1].click();
    });

    //Выделение года
    $('.year-period').click(function(){

        var year = $('.ui-datepicker-today').data('year');
        $('[data-year='+year+']')[0].click();
        $('[data-year='+year+']')[$('[data-year='+year+']').length - 1].click();
    });

    $('button.check_date').on('click', function(){
        var startDate = $('input[name="startDate"]').val();
        var endDate = $('input[name="endDate"]').val();

        if(startDate != '' && endDate === ''){
            var url = Main.removeFromUrl(['period', 'startDate', 'endDate']);
            window.location = Main.addToUrl({startDate: startDate}, url);
        } else if(startDate != '' && endDate != '') {
            var url = Main.removeFromUrl(['period', 'startDate', 'endDate']);
            window.location = Main.addToUrl({startDate: startDate, endDate: endDate}, url);
        }
    });

};
//$.fn.datepickerDark = function(data){
//    var datepickerSelector = '#datepicker-01';
//    var periodSelector = '#period';
//    var format = 'yy-mm-dd';
//    var currentDate = new Date();
//    var startDate = '';
//    var endDate = '';
//    var onChange = function(start, end){};
//
//    if(typeof data != 'undefined'){
//        if(typeof data.datepickerId != 'undefined')
//            datepickerSelector = data.datepickerId;
//
//        if(typeof data.periodId != 'undefined')
//            periodSelector = data.periodId;
//
//        if(typeof data.format != 'undefined')
//            format = data.format;
//
//        if(typeof data.currentDate != 'undefined')
//            currentDate = data.currentDate;
//
//        if(typeof data.startDate != 'undefined')
//            startDate = data.startDate;
//
//        if(typeof data.endDate != 'undefined')
//            endDate = data.endDate;
//
//        if(typeof data.onChange != 'undefined')
//            onChange = data.onChange;
//    }
//
//    if(!startDate || startDate == '')
//        startDate = null;
//
//    if(!endDate || endDate == '')
//        endDate = null;
//
//    //смена стиля кнопки вызова календаря при открытии самого календаря + открытие календаря если раннее он был закрыт
//    $(this).click(function(){
//        if($('.ui-datepicker').is(':hidden')){
//            $(datepickerSelector).addClass('active_calendar');//Смена стиля кнопки вызова календаря при открытии календаря
//            $(datepickerSelector).next().addClass('active_calendar');//Смена стиля иконки кнопки вызова календаря при открытии календаря
//        }
//        else {
//            $(datepickerSelector).removeClass('active_calendar');//Смена стиля кнопки вызова календаря при закрытии календаря
//            $(datepickerSelector).next().removeClass('active_calendar');//Смена стиля иконки кнопки вызова календаря при закрытии календаря
//        }
//    });
//
//    var datepickerRange = {
//        startDate: null,
//        endDate: null,
//        currentDate: new Date(currentDate),
//        selectCount: 0,
//        checkDays: function(datepicker){
//            var self = this;
//            if(this.startDate && this.endDate){
//                setTimeout(function() {
//                    //обрабатываем для каждого месяца
//                    datepicker.dpDiv.find('.ui-datepicker-calendar').each(function(monthIndex){
//                        var calendar = $(this);
//                        var currMonth = datepicker.drawMonth+monthIndex;  //Берем начальный месяц отрисовки, к нему прибавлям текущий месяц итерации (месяцы zero-based)
//                        var currYear = datepicker.drawYear;
//                        //Обработка стыка годов
//                        if (currMonth > 11) {
//                            currYear++;
//                            currMonth = datepicker.drawMonth - 12 + monthIndex; //magic ))
//                        }
//                        calendar.find('td>a.ui-state-default').each(function (dayIndex) {
//                            var day = dayIndex+1;
//                            self.checkDay(this, day, currMonth, currYear);
//                        });
//                    })
//                }, 0);
//            }
//        },
//        checkDay: function(elem, day, month, year){
//            var date = new Date(year, month, day);
//            if(date.getTime()>=this.startDate.getTime()&& date.getTime()<=this.endDate.getTime()){
//                $(elem).addClass('ui-state-active').removeClass('ui-state-highlight');
//            }
//        },
//        getSelectedDate: function(inst){
//            return new Date(inst.selectedYear, inst.selectedMonth, inst.selectedDay);
//        }
//    };
//
//    $.datepicker._defaults.onAfterUpdate = null;
//
//    var datepicker__updateDatepicker = $.datepicker._updateDatepicker;
//
//    $.datepicker._updateDatepicker = function( inst ) {
//        datepicker__updateDatepicker.call( this, inst );
//        var onAfterUpdate = this._get(inst, 'onAfterUpdate');
//        if (onAfterUpdate)
//            onAfterUpdate.apply((inst.input ? inst.input[0] : null),
//                [(inst.input ? inst.input.val() : ''), inst]);
//    };
//
//    $(datepickerSelector).datepicker({
//        firstDay:[1], //первый день недели понедельник
//        numberOfMonths: 2, //количество отображаемых месяцев
//        maxDate: +0,//запрет выбора дат больше текущей
//        monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь', 'Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
//        monthNamesShort: ['Янв','Фев','Мар','Апр','Май','Июн', 'Июл','Авг','Сен','Окт','Ноя','Дек'],
//        dayNames: ['воскресенье','понедельник','вторник','среда','четверг','пятница','суббота'],
//        dayNamesShort: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
//        dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
//        dateFormat: format,
//        defaultDate: currentDate,
//        onSelect: function (dateText, inst) {
//            $(this).data().datepicker.inline = true;
//            datepickerRange.selectCount++;
//            datepickerRange.currentDate = datepickerRange.getSelectedDate(inst);
//            if(datepickerRange.selectCount<2){
//                datepickerRange.startDate = datepickerRange.getSelectedDate(inst);
//                datepickerRange.endDate = null;
//                $(periodSelector).html(''+dateText+'');
//            }
//            else {
//                datepickerRange.selectCount = 0;
//                datepickerRange.endDate = datepickerRange.getSelectedDate(inst);
//                if(datepickerRange.startDate.getTime()>datepickerRange.endDate.getTime()){
//                    datepickerRange.endDate = datepickerRange.startDate;
//                    datepickerRange.startDate = datepickerRange.currentDate;
//                    $(periodSelector).prepend(''+dateText+' - ');
//                    var start = $(periodSelector).text();//записываем начальную дату в скрытый span
//                    $(datepickerSelector).val(start);//передаем значение начальной даты в input
//                }
//                else {
//                    $(periodSelector).append(' - '+dateText);
//                    var finish = $(periodSelector).text();//записываем конечную дату в скрытый span
//                    $(datepickerSelector).val(finish);//передаем значение конечной даты в input
//                }
//                datepickerRange.checkDays(inst);
//            }
//            return false;
//        },
//        onChangeMonthYear: function(year, month, inst) {
//            datepickerRange.currentDate = datepickerRange.getSelectedDate(inst);
//            datepickerRange.checkDays(inst);
//        },
//        onClose:function(){
//            delete $(this).data().datepicker.first;
//            $(this).data().datepicker.inline = false;
//            $(datepickerSelector).removeClass('active_calendar');//Смена стиля кнопки вызова календаря при закрытии календаря
//            $(datepickerSelector).next().removeClass('active_calendar');//Смена стиля иконки кнопки вызова календаря при закрытии календаря
//        },
//        onAfterUpdate: function ( inst ) {
//            $('<div class="ui-datepicker-buttonpane ui-widget-content"><div class="col-sm-9 writedate">Установите начальную и конечную дату</div></div>').appendTo($('#ui-datepicker-div'));//Добавление текста под календарь
//
//            //Добавление кнопки закрытия календаря
//            $('<div class="col-sm-3"><div class="row"><button type="button" class="ui-datepicker-close ui-state-default ui-priority-primary ui-corner-all btn-block" data-handler="hide" data-event="click">Применить</button></div></div>').appendTo('.ui-datepicker-buttonpane').click(function(){
//
//                var period = $(periodSelector).text().split(' - ');
//                onChange(period[0], period[1]);
//
//                $('.ui-datepicker').hide();//закрытие календаря по клику на кнопку "Применить"
//                $(datepickerSelector).removeClass('active_calendar');//Смена стиля кнопки вызова календаря при закрытии календаря кнопкой "Применить"
//                $(datepickerSelector).next().removeClass('active_calendar');//Смена стиля иконки кнопки вызова календаря при закрытии календаря кнопкой "Применить"
//            });
//        },
//        yearRange: '-1:+1'
//    }).prev('.btn').on('click', function (e) {
//        e && e.preventDefault();
//        $(datepickerSelector).focus();
//    });
//
//    if(startDate != null && endDate != null){
//        $(datepickerSelector).datepicker("setDate", endDate);
//        if(startDate == endDate)
//            var dateDiapasonText = startDate;
//        else
//            var dateDiapasonText = startDate+' - '+endDate;
//
//        $(periodSelector).html(dateDiapasonText);
//        $(datepickerSelector).val(dateDiapasonText);
//    }else{
//        $(datepickerSelector).datepicker("setDate", currentDate);
//    }
//
//    $(datepickerSelector).datepicker('widget').css({'margin-left': -$(datepickerSelector).outerWidth()});//Позиционирование календаря
//}