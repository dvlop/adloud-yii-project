Campaign = {

    hiddenListsId: '.open_section',
    addCampNameId: '.ad_camp_theme',
    regionListId: '.region_list',
    selectpicker: null,
    companyNameId: '#application_models_Campaign_description',
    catsSelectpickerId: '.ad_camp_theme',
    regionSectionId: '.open_region.active',
    disabledRegionSectionId: '.open_region.passive',
    geoBlockId: '.geography',
    countryCheckId: 'label.region_check.city_check',
    cityCheckId: 'label.region_check',
    submitButtonId: 'button[type="submit"]',
    regionsId: 'label.region_check',
    formId: '#create_campaign',
    createLabelId: '#create-label-button',
    selectLabelId: '#select-label-button',
    deleteLabelId: '#delete-label-button',
    labelSelectorId: 'select.select-previous-mark',
    labelColorSelectorId: 'select.select-mark-color',
    existingLabelId: '#existing-label',
    newLabelInputId: '#new-label input',

    init: function(data){
        if(typeof data != 'undefined'){
            var attributes = [
                'hiddenListsId',
                'addCampNameId',
                'campNameInputId',
                'regionListId',
                'companyNameId',
                'catsSelectpickerId',
                'regionSectionId',
                'disabledRegionSectionId',
                'geoBlockId',
                'countryCheckId',
                'cityCheckId',
                'submitButtonId',
                'regionsId',
                'formId',
                'createLabelId',
                'selectLabelId',
                'deleteLabelId',
                'labelSelectorId',
                'labelColorSelectorId',
                'existingLabelId',
                'newLabelInputId'
            ];

            $.each(attributes, function(index, element){
                if(typeof data[element] != 'undefined')
                    Campaign[element] = data[element];
            });
        }

        Campaign.setHandlers();
    },

    setHandlers: function(){
        Campaign.setSectionToggle();
        Campaign.setTextLimits();
        Campaign.setCatsSelectpicker();
        Campaign.setGeoListHandler();
        Campaign.setUaListHandler();
        Campaign.setFormSubmit();
        Campaign.setAddLabel();
        Campaign.setSelectLabel();
        Campaign.setDeleteLabel();
        Campaign.selectLabel();
        Campaign.selectLabelColor();
        Campaign.setSelectLabelName();
    },

    setSectionToggle: function(){
        $(Campaign.hiddenListsId).on('click', function(){
            $(this).parent().siblings('.section').toggle();
            $(this).children('.caret').toggleClass('open_caret');
        });
    },

    setTextLimits: function(){
        $(Campaign.companyNameId).limit('30', '.title_adloud_note');
    },

    setCatsSelectpicker: function(){
        Campaign.selectpicker = $(Campaign.catsSelectpickerId).selectpicker({
            style: 'btn btn-default btn-block'
        });
    },

    setGeoListHandler: function(){
        Campaign.toggleRegionsList();
        Campaign.openSelectedRegions();
        Campaign.setRegionClick();
    },

    setUaListHandler: function(){
        Campaign.setUaClick();
    },

    toggleRegionsList: function(){
        $(Campaign.regionSectionId).on('click', function(){
            $(this).parent().siblings('.region_list').toggle();
            $(this).toggleClass('open_caret');
            $(this).siblings('.caret').toggleClass('open_caret');
        });
    },

    openSelectedRegions: function(){
        var checkedRegions = $(Campaign.geoBlockId+' label.checkbox.checked');
        if(checkedRegions.size() > 0){
            $(Campaign.geoBlockId).css('display', 'block');

            var countriesBlock = $(Campaign.geoBlockId+' '+Campaign.regionListId+':first');
            countriesBlock.css('display', 'block');
            var countries = $('>li', countriesBlock);

            countries.each(function(index, country){
                var regionsBlock = $('>'+Campaign.regionListId, country);

                var checkedRegions = $(Campaign.regionListId+' label.checkbox.checked', country);
                if(checkedRegions.size() > 0){
                    regionsBlock.css('display', 'block');
                    $('b.caret:first', regionsBlock.closest('li')).addClass('open_caret');
                }

                if($(Campaign.countryCheckId, country).hasClass('checked'))
                    $(Campaign.cityCheckId, regionsBlock).addClass('checked');
            });
        }
    },

    setRegionClick: function(){
        var regionSelector = $('label.checkbox');

        regionSelector.on('click', function(){
            var label = $(this);
            return Campaign.selectRegion(label);
        });

        $('span'+Campaign.disabledRegionSectionId).on('click', function(){
            var label = $(this).prev('label.checkbox');

            return Campaign.selectRegion(label);
        });
    },

    setUaClick: function(){
        var uaSelector = $('[data-ua], [data-target]');

        uaSelector.on('click', function(){
            var label = $(this);
            Campaign.selectUa(label);
        });
    },

    setAddLabel: function(){
        $(Campaign.createLabelId).on('click', function(){
            var labelSelector = $($(Campaign.selectLabelId).data('label'));
            if(!labelSelector.hasClass('hide'))
                labelSelector.addClass('hide');

            $($(this).data('label')).toggleClass('hide');
            return false;
        });
    },

    setSelectLabel: function(){
        $(Campaign.selectLabelId).on('click', function(){
            var labelSelector = $($(Campaign.createLabelId).data('label'));
            if(!labelSelector.hasClass('hide'))
                labelSelector.addClass('hide');

            $($(Campaign.deleteLabelId).data('input')).val('');

            $($(this).data('label')).toggleClass('hide');
            return false;
        });
    },

    setDeleteLabel: function(){
        $(Campaign.deleteLabelId).on('click', function(){
            if(confirm($(this).data('confirm'))){
                var button = $(this);

                Campaign.createNewLabel('', '');
                $(button.data('input')).val(1);
            }
        });
    },

    selectLabel: function(){
        $(Campaign.labelSelectorId).on('change', function(){
            var index = this.selectedIndex;
            var option = $($(this).context[index]);

            $($(Campaign.deleteLabelId).data('input')).val('');

            Campaign.createNewLabel(option.data('color'), option.data('text'));
        });
    },

    selectLabelColor: function(){
        $(Campaign.labelColorSelectorId).on('change', function(){
            var text = $(Campaign.newLabelInputId).val();
            if(!text)
                text = 'New label';

            $($(Campaign.deleteLabelId).data('input')).val('');

            Campaign.createNewLabel($(this).val(), text);
        });
    },

    setSelectLabelName: function(){
        $(Campaign.newLabelInputId).on('input', function(){
            var color = $(Campaign.labelColorSelectorId).val();

            $($(Campaign.deleteLabelId).data('input')).val('');

            Campaign.createNewLabel(color, $(this).val());
        });
    },

    createNewLabel: function(color, name){
        var newColor = '';
        newColor += '<span class="campaighn-mark-color" style="background-color: '+color+'; ?>;"></span>';
        newColor += '<span class="campaighn-mark-name black-mark open">'+name+'</span>';

        $(Campaign.existingLabelId).html(newColor);
    },

    selectRegion: function(label){
        if(label.hasClass('checked')){
            return Campaign.uncheck(label);
        }else{
            return Campaign.check(label);
        }
    },

    selectUa: function(label){
        if(label.hasClass('checked')){
            Campaign.checkUa(label);
        }else{
            Campaign.uncheckUa(label);
        }
    },

    check: function(label){
        var subRegions = Campaign.getSubRegons(label);

        label.addClass('checked');
        $('input', label).attr('checked', 'checked');

        if(subRegions.length > 0){
            var checkedCountry = $('label.checkbox.country.checked');

            if(checkedCountry.length > 1){
                label.removeClass('checked');
                $('input', label).removeAttr('checked');
                return false;
            }

            subRegions.addClass('checked');
        }else{
            Campaign.checkAllRegions(label, true);
        }

        return false;
    },

    checkUa: function(label){
        var getSub = label.parent('p').next().children();

        $('input', label).attr('checked', 'checked');

        if(getSub.length > 0){
            getSub.find('input').attr('checked', 'checked');
        }
    },

    uncheck: function(label){
        var subRegions = Campaign.getSubRegons(label);

        label.removeClass('checked');
        $('input', label).removeAttr('checked');

        if(subRegions.length > 0){
            subRegions.removeClass('checked');
            $('input', subRegions).removeAttr('checked');
        }else{
            Campaign.checkAllRegions(label, false);
        }

        return false;
    },

    uncheckUa: function(label){
        $('input', label).removeAttr('checked');
    },

    checkAllRegions: function(label, check){
        var country = $('label.checkbox', label.closest('ul').prev('p'));
        var allRegions = $('label.checkbox', label.closest('ul'));
        var enabledRegions = $('label.checkbox.checked', label.closest('ul'));
        var allLength = allRegions.length;
        var enabledLength = enabledRegions.length;

        $('input', country).removeAttr('checked');

        if(allLength == enabledLength){
            var checkedCountry = $('label.checkbox.country.checked');
            if(checkedCountry.length == 0){
                country.addClass('checked');
                $('input', country).attr('checked', 'checked');
                $('input', allRegions).removeAttr('checked');
            }else{
                $('input', allRegions).attr('checked', 'checked');
            }
        }else if(allLength - enabledLength == 1){
            country.removeClass('checked');
//            $('input', allRegions).attr('checked', 'checked');
            $('input', label).removeAttr('checked');
        }
    },

    getSubRegons: function(label){
        return $('ul label.checkbox', label.closest('li'));
    },

    setFormSubmit: function(){
        var form = $(Campaign.formId);

        $(Campaign.submitButtonId).on('click', function(){
            $(Campaign.regionsId+'.checked').each(function(index, value){
                var input = $('input[checked="checked"]', value);

                if(input.length){
                    var newInput = document.createElement('input');
                    newInput.type = 'hidden';
                    newInput.name = input.attr('name');
                    newInput.value = input.val();

                    form.append(newInput);
                    input.remove();
                }
            });

            return true;
        });
    }
}