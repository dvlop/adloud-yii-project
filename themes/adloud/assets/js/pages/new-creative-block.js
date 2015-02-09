NewCreativeBlock = {

    blockId: '',
    previewUrl: '',
    teaserTableId: '.adld-tsr-table',
    leftPreviewBgId: '.left-preview-bg',
    topPreviewBgId: '.top-preview-bg',
    previewId: '#preview-block',
    allowAdultInputId: '#allowSms_input',
    allowShockInputId: '#allowShock_input',
    allowSmsInputId: '#allowSms_input',
    borderWidthInputId: '',
    selectSizeId: '',
    sizeInputId: '',
    blockDescription: '',
    horizontalCountId: '#teaser-horizontal-amount',
    verticalCountId: '#teaser-vertical-amount',
    horizontalCountInput: '',
    verticalCountInput: '',
    typeInputId: '',
    captionColorId: '',
    textColorId: '',
    buttonColorId: '',
    backgroundColorId: '',
    borderId: '',
    borderColorId: '',
    panelId: '.toggle-panel',
    colorPickerId: '.color-value',
    previewTableId: '#teasers-container',
    marketFormat: 'market',
    opacityId: 'span.color-opacity',
    captionOpacityId: '',
    textOpacityId: '',
    buttonOpacityId: '',
    backgroundOpacityId: '',
    borderOpacityId: '',

    colorSelector: null,
    property: null,
    teaserFormat: null,

    init: function(data){
        if(typeof data != 'undefined'){


            var attributes = [
                'blockId',
                'previewUrl',
                'teaserTableId',
                'leftPreviewBgId',
                'topPreviewBgId',
                'previewId',
                'allowAdultInputId',
                'allowShockInputId',
                'allowSmsInputId',
                'borderWidthInputId',
                'selectSizeId',
                'blockDescription',
                'formatId',
                'horizontalCountId',
                'verticalCountId',
                'horizontalCountInput',
                'verticalCountInput',
                'typeInputId',
                'sizeInputId',
                'captionColorId',
                'textColorId',
                'buttonColorId',
                'backgroundColorId',
                'borderId',
                'borderColorId',
                'panelId',
                'colorPickerId',
                'previewTableId',
                'marketFormat',
                'opacityId',
                'captionOpacityId',
                'textOpacityId',
                'buttonOpacityId',
                'backgroundOpacityId',
                'borderOpacityId'
            ];

            $.each(attributes, function(index, element){
                if(typeof data[element] != 'undefined')
                    NewCreativeBlock[element] = data[element];
            });
        }

        NewCreativeBlock.setHandlers();
    },

    setHandlers: function(){
        NewCreativeBlock.loadPreview();
        NewCreativeBlock.setSizePicker();
        NewCreativeBlock.setCountCheckers();
        NewCreativeBlock.setColorPicker();
        NewCreativeBlock.setBorderWidth();
        NewCreativeBlock.setHideMenu();
        NewCreativeBlock.setPicker();
    },

    setSizePicker: function(){
        $(NewCreativeBlock.selectSizeId).selectpicker({
            style: 'btn btn-default'
        }).on('change', function(){
            NewCreativeBlock.loadPreview();
        });
    },

    setCountCheckers: function(){
        String.prototype.repeat = function(num) {
            return new Array(num + 1).join(this);
        };
        $.fn.addSliderSegments = function (amount, orientation) {
            return this.each(function () {
                if (orientation == "vertical") {
                    var output = ''
                        , i;
                    for (i = 1; i <= amount - 2; i++) {
                        output += '<div class="ui-slider-segment" style="top:' + 100 / (amount - 1) * i + '%;"></div>';
                    };
                    $(this).prepend(output);
                } else {
                    var segmentGap = 100 / (amount - 1) + "%"
                        , segment = '<div class="ui-slider-segment" style="margin-left: ' + segmentGap + ';"></div>';
                    $(this).prepend(segment.repeat(amount - 2));
                }
            });
        };


        //Функция для создания нескольких копий элемента (мультиклонирование jquery)
        $.fn.duplicate = function(count, cloneEvents) {
            var tmp = [];
            for ( var i = 0; i < count; i++ ) {
                $.merge( tmp, this.clone( cloneEvents ).get() );
            }
            return this.pushStack( tmp );
        };

        var params = NewCreativeBlock.getParams();

        //Горизонтальный ползунок (Jquery Ui slider)
        var $slider = $(NewCreativeBlock.horizontalCountId);
        if($slider.length > 0){
            $slider.slider({
                min: 1,
                max: 7,
                value: params.horizontalCount,
                orientation: "horizontal",
                range: "min",
                change: function(event, ui) {
                    $(NewCreativeBlock.horizontalCountInput).val(ui.value);
                    NewCreativeBlock.loadPreview();
                },
                slide: function (event, ui) {
                    $(NewCreativeBlock.horizontalCountId+' .ui-slider-handle.ui-state-default.ui-corner-all').text(ui.value);//записываем текущее значение ползунка гор блоков
                }
            }).addSliderSegments($slider.slider("option").max);
            $('.ui-slider-handle.ui-state-default.ui-corner-all').text('1');
        };


        //Вертикальный ползунок (Jquery Ui slider)
        var $slidervert = $(NewCreativeBlock.verticalCountId);
        if($slidervert.length > 0){
            $slidervert.slider({
                min: 1,
                max: 15,
                value: params.verticalCount,
                orientation: "horizontal",
                range: "min",
                change: function(event, uivert) {
                    $(NewCreativeBlock.verticalCountInput).val(uivert.value);
                    NewCreativeBlock.loadPreview();
                },
                slide: function (event, uivert) {
                    $(NewCreativeBlock.verticalCountId+' .ui-slider-handle.ui-state-default.ui-corner-all').text(uivert.value);//записываем текущее значение ползунка верт блоков
                }
            }).addSliderSegments($slidervert.slider("option").max);
            $('.ui-slider-handle.ui-state-default.ui-corner-all').text('1');
        };


    },

    setColorPicker: function(){
        var styles = NewCreativeBlock.getParams();
        var teaser_type = styles.format;
        var colorsInput = $(NewCreativeBlock.colorPickerId);

        colorsInput.colpick({
            layout:'hex',
            submit:0,
            colorScheme:'dark',
            onBeforeShow: function(hsb, hex, rgb, el, bySetColor){
                var el = $(this);

                if(el.val() == 'transparent')
                    el.colpickSetColor('ffffff');
                else
                    el.colpickSetColor(this.value);

                $(NewCreativeBlock.opacityId, hsb).attr('data-element-id', $(this).attr('id'));
            },
            onShow: function(){
                var top_pos = $(this).offset().top - 200;
                var left_pos = $(this).offset().left - 53;

                var property = $(this).data(teaser_type+'-property'); //свойство которое применяектся к селектору цветов

                $('.colpick').css({'top':top_pos, 'left':left_pos});
            },
            onChange:function(hsb, hex, rgb, el, bySetColor){
                var color_selector = $(el).data(teaser_type+'-selector'); //Селектор к котрому применяются стили цветов
                var property = $(el).data(teaser_type+'-property'); //свойство которое применяектся к селектору цветов


                var opacity = parseInt($($(el).data('opacity-id')).val());
                if(!opacity)
                    opacity = 0;

                opacity = opacity == 0 ? 1 : 0;

                var rgbaCol = 'rgba(' + parseInt(hex.slice(-6,-4),16)+ ',' + parseInt(hex.slice(-4,-2),16)+ ',' + parseInt(hex.slice(-2),16) + ','+opacity + ')';

                $(color_selector).css(property, rgbaCol);

                if($(el).val() == 'transparent' && hex == 'ffffff'){
                    $(el).val('transparent')
                }else{
                    $(el).val(hex);
                }

                $(el).css({'border-color': rgbaCol,'border-right-width':'36px'});
            }
        }).keyup(function(){
            $(this).colpickSetColor(this.value);
        });

        colorsInput.each(function(index, value){
            NewCreativeBlock.formatPicker($(value));
        });
    },

    setPicker: function(){
        $(NewCreativeBlock.opacityId).on('click', function(){
            var pickerOpacity = $(this);
            var el = $('#'+pickerOpacity.data('element-id'));
            var hidden = $(el.data('opacity-id'));
            var childSpan = $('span', pickerOpacity);

            childSpan.toggleClass('fui-check');

            var teaser_type = NewCreativeBlock.getParams().format;
            var selector = el.data(teaser_type+'-selector');
            var property = el.data(teaser_type+'-property');
            //var colorExample = $('div.colpick_new_color', picker.closest('div.colpick'));

            var opacity = 1;

            if(hidden.val()){
                hidden.val('');
                opacity = 1;
            }else{
                hidden.val(1);
                opacity = 0;
            }

            var hex = '#'+el.val();
            var rgbaCol = 'rgba(' + parseInt(hex.slice(-6,-4),16)+ ',' + parseInt(hex.slice(-4,-2),16)+ ',' + parseInt(hex.slice(-2),16) + ','+opacity + ')';

            $(selector).css(property, rgbaCol);

            el.css({'border-color': rgbaCol,'border-right-width':'36px'});
            //colorExample.css('background-color', rgbaCol);
        });
    },

    formatPicker: function(input){
        input.css({
            'border-style': 'solid',
            'border-right-width': '36px',
            'border-color': '#'+input.val()
        });
    },

    setBorderWidth: function(){

        var styles = null;
        var format = 'simple';

        $('.border-width-tool .spinner').spinner({
            min: 0,
            max: 50,
            spin: function(event, border){
                var params = NewCreativeBlock.getParams();
                format = params.format;

                if(format == NewCreativeBlock.marketFormat){
                    styles = {
                        'width': (params.width*params.horizontalCount)+'px',
                        'height': (params.height*params.verticalCount)+'px',
                        'background': 'none repeat scroll 0% 0% '+params.backgroundColor,
                        'border-color': params.borderColor,
                        'border-width': border.value+'px'
                    };
                }
            },
            stop: function(event, ui){
                if(format == NewCreativeBlock.marketFormat){
                    $(NewCreativeBlock.previewTableId).css(styles);
                    NewCreativeBlock.formatPreview();
                }
            }
        });
    },

    formatPreview: function(){
        var params = NewCreativeBlock.getParams();
        var previewContainer = $(NewCreativeBlock.previewId);

        $('body div.wrapper:first').addClass('creative-blocks-'+params.format);

        var tsr_table_width = params.horizontalCount*params.width;//Новая ширина таблицы тизеров
        var tsr_table_height = params.verticalCount*params.height;//Новая высота таблицы тизеров
        var tsr_table_border_width_value = parseInt(params.border);//Граница таблицы тизеров в px

        var leftBg = $(NewCreativeBlock.leftPreviewBgId);
        var topBg = $(NewCreativeBlock.topPreviewBgId);
        var teaserTable = $(NewCreativeBlock.previewTableId);

        topBg.removeClass('hide');
        previewContainer.removeAttr('style');

        if(tsr_table_width >= 925){
            topBg.addClass('hide');
            previewContainer.css({
                left: '50%',
                'margin-left': '-'+ (tsr_table_width/2) + 'px',
                position: 'absolute'
            })
        }

        if(params.format == NewCreativeBlock.marketFormat){
            var bordersWidth = 2*tsr_table_border_width_value;

            leftBg.width(925 - tsr_table_width - bordersWidth);//Новая ширина левого бг
            leftBg.height(100 + tsr_table_height + bordersWidth);//Новая высота левого бг
            topBg.width(tsr_table_width + bordersWidth);//новая ширина верхнего бг
        }else{
            var bordersHorWidth = 2*params.horizontalCount;
            var bordersVerWidth = 2*params.verticalCount;

            leftBg.width(925 - tsr_table_width - bordersHorWidth);
            leftBg.height(100 + tsr_table_height + bordersVerWidth);
            topBg.width(tsr_table_width + bordersHorWidth);
            teaserTable.width(tsr_table_width + bordersHorWidth);
            teaserTable.height(tsr_table_height + bordersVerWidth);
        }
    },

    loadPreview: function(){
        Main.ajax({
            url: NewCreativeBlock.previewUrl,
            data: NewCreativeBlock.getParams(),
            success: function(json){
                if(json.teasers && json.css){
                    Main.preview(json.teasers, json.css, NewCreativeBlock.previewId);
                    NewCreativeBlock.formatPreview();
                }
            }
        });
    },

    getParams: function(){
        var params = {
            description : $(NewCreativeBlock.blockDescription).val(),
            format : $(NewCreativeBlock.typeInputId).val(),
            size: $(NewCreativeBlock.sizeInputId).val(),
            verticalCount : $(NewCreativeBlock.verticalCountInput).val(),
            horizontalCount : $(NewCreativeBlock.horizontalCountInput).val(),
            captionColor : $(NewCreativeBlock.captionColorId).val(),
            textColor : $(NewCreativeBlock.textColorId).val(),
            buttonColor : $(NewCreativeBlock.buttonColorId).val(),
            backgroundColor : $(NewCreativeBlock.backgroundColorId).val(),
            borderColor : $(NewCreativeBlock.borderColorId).val(),
            border : $(NewCreativeBlock.borderId).val(),
            allowAdult: $(NewCreativeBlock.allowAdultInputId).val() ? true : false,
            allowShock: $(NewCreativeBlock.allowShockInputId).val() ? true : false,
            allowSms: $(NewCreativeBlock.allowSmsInputId).val() ? true : false,
            captionOpacity: $(NewCreativeBlock.captionOpacityId).val() ? true : false,
            textOpacity: $(NewCreativeBlock.textOpacityId).val() ? true : false,
            buttonOpacity: $(NewCreativeBlock.buttonOpacityId).val() ? true : false,
            backgroundOpacity: $(NewCreativeBlock.backgroundOpacityId).val() ? true : false,
            borderOpacity: $(NewCreativeBlock.borderOpacityId).val() ? true : false,
            id: NewCreativeBlock.blockId,
            width: 0,
            height: 0
        };

        if(!params.verticalCount || params.verticalCount == '0')
            params.verticalCount = 1;
        if(!params.horizontalCount || params.horizontalCount == '0')
            params.horizontalCount = 1;

        var format = params.size.split('x');

        params.width = format[0];
        params.height = format[1];

        return params;
    },

    setHideMenu: function(){
        $(NewCreativeBlock.panelId).click(function(){
            $(this).toggleClass('onfooter');
            $(this).children('.fa').toggleClass('fa-chevron-down fa-chevron-up');
            $('.creative-blocks-tools-wrapper').toggle();
        });
    }
}
