$.fn.selectpickerSmart = function(data){
    var selector = new Selector();
    selector.select = $(this);
    selector.init(data);
};

Selector = function(){
    var SPS = {
        style: 'select-multiple',
        change: function(evant, val){},
        hiddenName: null,
        hiddenId: null,
        moreCats: 0,
        selectedAdditional: null,
        nonSelectedText: 'Ничего не выбрано',
        checkboxes: false,
        size: 7,

        select: null,
        picker: null,
        selectedOptions: [],
        selectedValues: [],
        selectedValuesString: '',
        hiddenInput: null,
        hiddenInputSelector: 'option[checked="checked"]',
        selectedCount: 0,
        parentDiv: null,

        init: function(data){

            var attributes = [
                'style',
                'change',
                'hiddenName',
                'hiddenId',
                'moreCats',
                'selectedAdditional',
                'nonSelectedText',
                'checkboxes',
                'size'
            ];

            $.each(attributes, function(index, element){
                if(typeof data[element] != 'undefined')
                    SPS[element] = data[element];
            });

            SPS.setHandlers();
        },

        setHandlers: function(){
            SPS.setSelectedValues();

            SPS.setSelectPicker();

            if(SPS.moreCats > 0)
                SPS.setMoreCats();

            if(SPS.hiddenName != null || SPS.hiddenId != null)
                SPS.createHiddenField();

            if(SPS.checkboxes)
                SPS.setCheckboxes();

            SPS.setSelectPickerChange();
        },

        setSelectedValues: function(){
            SPS.selectedOptions = $('option[checked="checked"]', SPS.select);

            if(SPS.selectedOptions.size() > 0){
                SPS.selectedOptions.each(function(index, val){
                    SPS.selectedValues[index] = $(val).val();
                });
            }

            SPS.selectedValues = $(SPS.selectedValues);
        },

        createHiddenField: function(){
            SPS.hiddenInputSelector = SPS.hiddenName == null ? '#'+SPS.hiddenId : 'input[name="'+SPS.hiddenName+'"]';
            $(SPS.hiddenInputSelector).remove();

            SPS.hiddenName = SPS.hiddenName == null ? 'hidden-seleckpicker-name' : SPS.hiddenName;

            var hiddenInput = '<input';

            if(SPS.hiddenId !== null)
                hiddenInput += ' id="'+SPS.hiddenId+'"';



            if(SPS.selectedValues.size() > 0){
                SPS.selectedValuesString = SPS.getSelectedValues(SPS.selectedValues);
            }

            hiddenInput += ' type="hidden" name="'+SPS.hiddenName+'" value="'+SPS.selectedValuesString+'">';

            var form = SPS.select.closest('form');

            if(form)
                form.append(hiddenInput);

            SPS.hiddenInput = $(SPS.hiddenInputSelector);
        },

        setSelectPicker: function(){
            SPS.picker = SPS.select.selectpicker({
                style: SPS.style,
                nonSelectedText: SPS.nonSelectedText,
                size: SPS.size
            }).selectpicker('val', SPS.selectedValues);

            if(SPS.selectedValues.size() == 0)
                SPS.setNoneSelectedName();
        },

        setSelectPickerChange: function(){
            SPS.picker.on('change', function(){
                var value = $(this).val();
                SPS.change($(this), value);

                if(SPS.hiddenInput != null){
                    SPS.hiddenInput.val(value);
                }

                if(SPS.moreCats > 0){
                    SPS.selectedCount++;
                    if(SPS.selectedCount == SPS.moreCats){
                        SPS.picker.attr('disabled', 'disabled');
                        $('ul.dropdown-menu', SPS.select.parent('div')).hide();
                    }
                }

                if($('ul.dropdown-menu li.selected', SPS.parentDiv).size() == 0){
                    $('button span.filter-option', SPS.parentDiv).html(SPS.nonSelectedText);
                }
            });
        },

        setMoreCats: function(){
            SPS.select.attr('multiple', 'multiple');
            var container = SPS.select.parent('div');
            var newSelectorId = 'select[name="'+SPS.select.attr('name')+'"]';
            var newSelectorHtml = SPS.select[0];

            $('p', container).remove();
            $('div.select', container).remove();
            SPS.select.remove();

            container.append(newSelectorHtml);

            $(newSelectorId+' option[checked="checked"]').removeAttr('checked');
            $(newSelectorId+' option[selected="selected"]').removeAttr('selected').attr('checked', 'checked');

            SPS.select = $(newSelectorId);

            SPS.setSelectedValues();
            SPS.setSelectPicker();
        },

        getSelectedValues: function(select){
            var result = '';

            select.each(function(index, value){
                if(typeof value == 'string')
                    result += value+',';
                else
                    result += $(value).val()+',';
            });

            if(result.length > 0)
                result = result.substr(0, result.length-1);

            return result;
        },

        setNoneSelectedName: function(){
            var parentDiv = SPS.select.closest('div');
            $('button.dropdown-toggle span.filter-option', parentDiv).html(SPS.nonSelectedText);
        },

        setCheckboxes: function(){
            SPS.parentDiv = SPS.select.closest('div');
            var span = '';
            var label = '';

            $('ul.dropdown-menu li a', SPS.parentDiv).each(function(index, val){
                label = $(val).html();

                if($(val).parent('li').hasClass('selected')){
                    span = '<label class="checkbox checked" for="check_adult_ad">';
                }else{
                    span = '<label class="checkbox" for="check_adult_ad">';
                }

                span += '<span class="icons">';
                span += '<span class="first-icon fui-checkbox-unchecked"></span>';
                span += '<span class="second-icon fui-checkbox-checked"></span>';
                span += '</span>';
                span += label;
                span += '</label>';

                $(val).html(span);
            });

            SPS.checkBoxClickHandler();
        },

        checkBoxClickHandler: function(){
           $('ul.dropdown-menu', SPS.parentDiv).on('click', 'li', function(){
               var label = $('label.checkbox', $(this));

               if(label.hasClass('checked'))
                   label.removeClass('checked');
               else
                   label.addClass('checked');
           });
        }
    };

    return SPS;
};

