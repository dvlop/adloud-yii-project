PrepaymentList = {

    selectFilterId: '#check-filter-id',
    selectUsersFilterId: '#check-users-filter-id',
    selectDateId: '#prepayment-date-diapason-id',
    payoutDateId: '.payout-datepicker',
    deactivateId: 'button.prepayment-deactivate',
    activateId: 'button.prepayment-activate',
    tableLineId: '#prepayment-requests-list tbody td',
    filters: [],

    init: function(data){
        var attributes = [
            'selectFilterId',
            'selectUsersFilterId',
            'selectDateId',
            'payoutDateId',
            'deactivateId',
            'activateId',
            'tableLineId',
            'filters'
        ];

        $.each(attributes, function(index, element){
            if(typeof data[element] != 'undefined')
                PrepaymentList[element] = data[element];
        });

        PrepaymentList.setHandlers();
    },

    setHandlers: function(){
        PrepaymentList.setSelectFilter();
        PrepaymentList.setUsersFilter();
        PrepaymentList.setDateRangePicker()
        PrepaymentList.setDatePicker();
        PrepaymentList.setDeactivate();
        PrepaymentList.setActivate();
        PrepaymentList.setTableLineClick();

        console.log(PrepaymentList.filters);
    },

    selectFilter: function(element, checked){
        console.log(element.context);
        console.log(checked);
    },

    setSelectFilter: function(){
        $(PrepaymentList.selectFilterId).multiselect({
            numberDisplayed: 2,
            includeSelectAllOption: true,
            selectAllText: 'Выбрать всё',
            selectAllValue: 'all',
            nonSelectedText: 'Ничего не выборано',
            onChange: function(element, checked){
                PrepaymentList.selectFilter(element, checked);
            },
            label: function(element){
                if($(element).val() != 'all')
                    return $(element).attr('data-text');
                else
                    return $(element).html();
            },
            buttonText: function(options, select){
                if (options.length == 0) {
                    return this.nonSelectedText + ' <b class="caret"></b>';
                }
                else {
                    if (options.length > this.numberDisplayed) {
                        return options.length+' '+this.nSelectedText+' <b class="caret"></b>';
                    }
                    else {
                        var selected = '';
                        options.each(function() {
                            var label = ($(this).attr('data-text') !== undefined) ? $(this).attr('data-text') : $(this).html();

                            selected += label+', ';
                        });
                        return selected.substr(0, selected.length - 2)+' <b class="caret"></b>';
                    }
                }
            }
        });
    },

    setUsersFilter: function(){
        $(PrepaymentList.selectUsersFilterId).multiselect({
            numberDisplayed: 4,
            includeSelectAllOption: true,
            selectAllText: 'Выбрать всех',
            selectAllValue: 'all',
            nonSelectedText: 'Только для пользователей:',
            onChange: function(element, checked){
                PrepaymentList.selectFilter(element, checked);
            },
            enableFiltering: true,
            filterPlaceholder: 'Укажите email пользователя',
            label: function(element){
                if($(element).val() != 'all')
                    return $(element).html()+' ('+$(element).attr('data-text')+')';
                else
                    return $(element).html();
            }
        });
    },

    setDateRangePicker: function(){
        $(PrepaymentList.selectDateId).daterangepicker({
            format: 'YYYY-MM-DD'
        });
    },

    setDatePicker: function(){
        $(PrepaymentList.payoutDateId).datepicker({
            format: 'yyyy-mm-dd'
        }).on('changeDate', function(event){
            $.ajax({
                type: 'POST',
                cache: false,
                url: $(this).attr('data-url'),
                data: 'dateValue='+$('input', this).val(),
                dataType: 'json',
                success: function(json){
                    if(json.error){
                        Main.showError(json.error);
                    }else{
                        Main.showMessage(json.message);
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    Main.showError(errorThrown);
                }
            });
        });
    },

    setDeactivate: function(){
        $(PrepaymentList.deactivateId).on('click', function(){
            $.ajax({
                type: 'POST',
                cache: false,
                url: $(this).attr('data-url'),
                data: 'activatePrepayment=false',
                dataType: 'json',
                success: function(json){
                    if(json.error){
                        Main.showError(json.error);
                    }else{
                        Main.showMessage(json.message);
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    Main.showError(errorThrown);
                }
            });
        });
    },

    setActivate: function(){
        $(PrepaymentList.activateId).on('click', function(){
            $.ajax({
                type: 'POST',
                cache: false,
                url: $(this).attr('data-url'),
                data: 'activatePrepayment=true',
                dataType: 'json',
                success: function(json){
                    if(json.error){
                        Main.showError(json.error);
                    }else{
                        Main.showMessage(json.message);
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    Main.showError(errorThrown);
                }
            });
        });
    },

    setTableLineClick: function(){
        $(PrepaymentList.tableLineId).on('click', function(){
            console.log($(this));
        });
    }
}
