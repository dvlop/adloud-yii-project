MainBlock = {

    id: '',
    format: '',
    previewUrl: '',
    modelName: '',
    previewId: '#main-block-preview',
    formId: '#main-CB-form',
    formItemId: 'div.crtv-blocks-tools-item',
    widthSliderId: '.adld-tsr-tbl-width-slider',
    unitButtonId: '.adld-tsr-tbl-width-units',
    adsCountId: 'div.crtv-blocks-slider',
    colorPickerId: '.crtv-blocks-colorpicker-btn',
    opacityId: '.colorpicker-opacity',
    colorpickerWindowId: '#colorpicker',
    pickerId: '.crtv-blocks-colorpicker',
    pickerInputsId: '.colorpicker-current-color-value',
    adsPaddingId: '.adld-tsr-cell-padding-slider',
    borderPaddingId: '.adld-tsr-cell-inner-padding-slider',
    imgWidthId: '.adld-adld-tsr-img-width-slider',
    imgBorderRadiusId: '.adld-adld-tsr-img-border-radius-slider',
    fontFamilyId: '.adld-tsr-fontfamily',
    adsStylesId: '#ADLOUD-teasers-styles',
    descriptionId: 'input[name="ads-description"]',
    adsTableId: '.adld-tsr-tbl',
    caption: '',
    width: '',
    horizontalCount: '',
    verticalCount: '',
    backgroundColor: '',
    borderColor: '',
    border: '',
    borderType: '',
    indentAds: '',
    indentBorder: '',
    adsBorderColor: '',
    adsBorder: '',
    adsBorderType: '',
    adsBackColor: '',
    backHoverColor: '',
    imgWidth: '',
    borderRadius: '',
    alignment: '',
    imgBorderColor: '',
    imgBorderWidth: '',
    imgBorderType: '',
    font: '',
    useDescription: '',
    textPosition: '',
    captionColor: '',
    captionFontSize: '',
    captionHoverColor: '',
    captionHoverFontSize: '',
    descFontSize: '',
    textColor: '',
    descLimit: '',
    borderOpacity: '',
    adsBorderOpacity: '',
    adsBackOpacity: '',
    backHoverOpacity: '',
    imgBorderOpacity: '',
    captionHoverOpacity: '',
    backgroundOpacity: '',
    captionOpacity: '',
    textOpacity: '',
    widthStyle: '',
    captionStyle: '',
    captionHoverStyle: '',
    descStyle: '',
    descText: '',

    sliderLimit: 20,

    init: function(data){
        if(typeof data != 'undefined'){
            var attributes = [
                'id',
                'format',
                'previewUrl',
                'modelName',
                'previewId',
                'formId',
                'formItemId',
                'widthSliderId',
                'unitButtonId',
                'adsCountId',
                'colorPickerId',
                'opacityId',
                'colorpickerWindowId',
                'pickerId',
                'pickerInputsId',
                'adsPaddingId',
                'borderPaddingId',
                'imgWidthId',
                'imgBorderRadiusId',
                'fontFamilyId',
                'adsStylesId',
                'descriptionId',
                'adsTableId',
                'caption',
                'width',
                'horizontalCount',
                'verticalCount',
                'backgroundColor',
                'borderColor',
                'border',
                'borderType',
                'indentAds',
                'indentBorder',
                'adsBorderColor',
                'adsBorder',
                'adsBorderType',
                'adsBackColor',
                'backHoverColor',
                'imgWidth',
                'borderRadius',
                'alignment',
                'imgBorderColor',
                'imgBorderWidth',
                'imgBorderType',
                'font',
                'useDescription',
                'textPosition',
                'captionColor',
                'captionFontSize',
                'captionHoverColor',
                'captionHoverFontSize',
                'descFontSize',
                'textColor',
                'descLimit',
                'borderOpacity',
                'adsBorderOpacity',
                'adsBackOpacity',
                'backHoverOpacity',
                'imgBorderOpacity',
                'captionHoverOpacity',
                'backgroundOpacity',
                'captionOpacity',
                'widthStyle',
                'captionStyle',
                'captionHoverStyle',
                'descStyle'
            ];

            $.each(attributes, function(index, element){
                if(typeof data[element] != 'undefined')
                    MainBlock[element] = data[element];
            });
        }

        MainBlock.setHandlers();
    },

    setHandlers: function(){
        MainBlock.setPreview();
        MainBlock.setTabs();
        MainBlock.setHideMenu();
        MainBlock.setWidthSliders();
        MainBlock.setColorPicker();
        MainBlock.setWidthInputs();
        MainBlock.setAdsPadding();
        MainBlock.setBorderPadding();
        MainBlock.setTypeSelectors();
        MainBlock.setTextTypes();
        MainBlock.setUseDescription();
        MainBlock.setSelectFontFamily();
    },

    setTabs: function(){
        $('.crtv-blocks-tools-type-btn').click(function(){
            if($(this).hasClass('active') !== true){
                var show_block = $(this).data('toolstype');
                $(show_block+',.show.crtv-blocks-tools-type-item').toggleClass('show hide');
                $('.crtv-blocks-tools-type-btn.active').removeClass('active');
                $(this).addClass('active');
            }
        });
    },

    setHideMenu: function(){
        $('.crtv-blocks-toggle-btn').click(function(){
            $(this).toggleClass('onfooter');
            $('.fa', $(this)).toggleClass('fa-chevron-down fa-chevron-up');
            $('.crtv-blocks-panel').toggle();

            if($(this).hasClass('onfooter')){
                $(this).css('bottom', '30px');
            }else{
                $(this).css('bottom', '135px');
            }
        });
    },

    setPreview: function(){
        MainBlock.loadPreview();
    },

    setWidthSliders: function(){
        MainBlock.setTableWidth();
        MainBlock.setAdsCount(MainBlock.input('horizontalCount'));
        MainBlock.setAdsCount(MainBlock.input('verticalCount'));
        MainBlock.setImgWidth();
        MainBlock.setImgBorderRadius();
    },

    setImgWidth: function(){
        var slider = $(MainBlock.imgWidthId);
        var input = MainBlock.input('imgWidth');

        MainBlock.setSlider(slider, input, '.adld-tsr-img', 'width', 300, 80);
    },

    setImgBorderRadius: function(){
        var slider = $(MainBlock.imgBorderRadiusId);
        var input = MainBlock.input('borderRadius');
        var limit = 50;

        MainBlock.setSlider(slider, input, '.adld-tsr-img', 'border-radius', limit);
    },

    setTableWidth: function(){
        var TsrTableWidthUnits = MainBlock.param('widthStyle');
        var TsrTableMaxWidth;
        var TsrTableMinWidth;


        if(TsrTableWidthUnits == '%'){
            TsrTableMaxWidth = 100;
            TsrTableMinWidth = 0;
        }else{
            TsrTableMaxWidth = 940;
            TsrTableMinWidth = 100;
        }

        var $TsrTableWidthSlider = $(MainBlock.widthSliderId);
        var widthInput = MainBlock.input('width');

        $TsrTableWidthSlider.slider({
            min: TsrTableMinWidth,
            max: TsrTableMaxWidth,
            value: widthInput.val(),
            orientation: "horizontal",
            range: "min",
            slide: function(event, TsrTableWidth) {
                widthInput.val(TsrTableWidth.value);

                var adsStyles = $(MainBlock.adsStylesId);
                var new_style = ' '+MainBlock.adsTableId+'{ width: '+TsrTableWidth.value+TsrTableWidthUnits+' !important; } ';

                adsStyles.append(new_style);
            }
        });

        $(MainBlock.unitButtonId).on('click', function(){
            var button = $(this);

            $(MainBlock.unitButtonId).removeClass('active');
            button.addClass('active');
            TsrTableWidthUnits = button.data('units');
            MainBlock.param('widthStyle', TsrTableWidthUnits);

            var value = widthInput.val();

            if(TsrTableWidthUnits == '%'){
                TsrTableMaxWidth = 100;
                TsrTableMinWidth = 0;

                value = ((value/940)*100).toFixed(0);

                $TsrTableWidthSlider.slider({
                    min: TsrTableMinWidth,
                    max : TsrTableMaxWidth,
                    value : value
                });
            }else{
                TsrTableMaxWidth = 940;
                TsrTableMinWidth = 100;
                value = ((value/100)*940).toFixed(0);

                $TsrTableWidthSlider.slider({
                    min: TsrTableMinWidth,
                    max : TsrTableMaxWidth,
                    value : value
                });
            }

            $(MainBlock.previewId+' table').css({
                'width': value+TsrTableWidthUnits
            });

            widthInput.val(value);
        });

        widthInput.on('input', function(){
            var value = $(this).val();

            if(value > TsrTableMaxWidth){
                value = TsrTableMaxWidth;
                $(this).val(TsrTableMaxWidth);
            }

            $TsrTableWidthSlider.slider({
                min: TsrTableMinWidth,
                max : TsrTableMaxWidth,
                value : value
            });

            var adsStyles = $(MainBlock.adsStylesId);
            var new_style = ' '+MainBlock.adsTableId+'{ width: '+value+TsrTableWidthUnits+' !important; } ';

            adsStyles.append(new_style);
        });
    },

    setColorPicker: function(){
        function rgb2hex(rgb){
            rgb = rgb.match(/^rgba?[\s+]?\([\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?/i);
            return (rgb && rgb.length === 4) ?
                ("0" + parseInt(rgb[1],10).toString(16)).slice(-2) +
                    ("0" + parseInt(rgb[2],10).toString(16)).slice(-2) +
                    ("0" + parseInt(rgb[3],10).toString(16)).slice(-2) : '';
        };

        var hexvalue;
        var opacity_ch;
        var opacity_val;
        var colorpicker_btn;
        var opacity_input;
        var color_input;
        var color_selector;
        var color_property;
        var colorpicker_pos;
        var adsStyles;
        var new_style;

        $(MainBlock.colorPickerId).click(function(colorpickerbtn){
            opacity_ch = $(MainBlock.opacityId);//Чекбокс прозрачности
            colorpicker_btn = $(this);//Определяем кнопку кликом по которой был открыт колорпикер
            color_input = colorpicker_btn.prev('.colorpicker-current-color-value');//Определяем инпут в который будем передавать значение колорпикера
            opacity_input = MainBlock.input(color_input.data('opacity'));
            color_selector = color_input.data('selector');//Определяем css класс элемента фон, цвет, цвет границы и т.д. которого будем изменять
            color_property = color_input.data('property');//Определяем что именно (фон, цвет текста, цвет границы и т.д) будем изменять
            colorpicker_pos = colorpicker_btn.offset();//Определяем позицию кнопки кликом по которой был открыт колорпикер
            opacity_val = parseInt(opacity_input.val());
            adsStyles = $(MainBlock.adsStylesId);

            if(opacity_val)
                opacity_ch.addClass('checked');
            else
                opacity_ch.removeClass('checked');

            if ($(colorpicker_btn).children().css('background-color') == 'rgba(255,255,255,0)') {
                var colorpicker_value = 'ffffff'
            } else {
                var colorpicker_value = rgb2hex($(colorpicker_btn).children().css('background-color'));//Определяем какой цвет должен быть установлен в колорпикере при его открытии (то есть текущее значение текущего колрпикера)
            }
            //Позиционируем колрпикер над кнопкой кликом по которой он был открыт
            $(MainBlock.pickerId).css({
                'top': -132,
                'left':(colorpicker_pos.left-120)
            }).toggleClass('show');//Показ/Скрытие колорпикера

            var colorPicker = $(MainBlock.colorpickerWindowId);

            //Параметры колорпикера
            colorPicker.colpick({
                flat:true,
                layout:'hex',
                submit:0,
                colorScheme:'dark',
                onChange: function(hsb,hex,rgb,el,bySetColor) {
                    if ($(MainBlock.pickerId).hasClass('show') && bySetColor !== 1) {
                        opacity_val = 0;
                        opacity_input.val(opacity_val);
                        opacity_ch.removeClass('checked');

                        hexvalue = hex;//значение колорпикера
                        colorpicker_btn.children('span').css('background','#'+hex);//При перемещении курсора в колорпикере передаем его (колорпикера) значение в шестиугольник
                        colorpicker_btn.children('span').children('span:first-child').css('border-bottom-color','#'+hex);//При перемещении курсора в колорпикере передаем его (колорпикера) значение в шестиугольник
                        colorpicker_btn.children('span').children('span:last-child').css('border-top-color','#'+hex);//При перемещении курсора в колорпикере передаем его (колорпикера) значение в шестиугольник
                        color_input.val('#'+hex);//При перемещении курсора в колорпикере передаем его (колорпикера) значение в соответствующий инпут


                        var styleString = color_selector+' { '+color_property+': #'+hex+' !important; }';
                        adsStyles.append(styleString);
                    }
                }
            }).colpickSetColor(colorpicker_value);

            //Прозрачность
            opacity_ch.change(function(){
                if (opacity_ch.hasClass('checked')) {
                    opacity_input.val(1);
                    $(color_selector).css(color_property,'rgba(255,255,255,0)');//Ставим прозрачность если таковая выбрана
                    colorpicker_btn.children('span').css('background','rgba(255,255,255,0)');//При перемещении курсора в колорпикере передаем его (колорпикера) значение в шестиугольник
                    colorpicker_btn.children('span').children('span:first-child').css('border-bottom-color','rgba(255,255,255,0)');//При перемещении курсора в колорпикере передаем его (колорпикера) значение в шестиугольник
                    colorpicker_btn.children('span').children('span:last-child').css('border-top-color','rgba(255,255,255,0)');//При перемещении курсора в колорпикере передаем его (колорпикера) значение в шестиугольник

                    adsStyles = $(MainBlock.adsStylesId);
                    new_style = ' '+color_selector+'{ '+color_property+': rgba(255,255,255,0) !important; } ';

                    adsStyles.append(new_style);
                } else {
                    opacity_input.val('');
                    $(color_selector).css(color_property,color_input.val());//Ставим текущее значение колорпикера если прозрачность снята
                    colorpicker_btn.children('span').css('background',color_input.val());//При перемещении курсора в колорпикере передаем его (колорпикера) значение в шестиугольник
                    colorpicker_btn.children('span').children('span:first-child').css('border-bottom-color',color_input.val());//При перемещении курсора в колорпикере передаем его (колорпикера) значение в шестиугольник
                    colorpicker_btn.children('span').children('span:last-child').css('border-top-color',color_input.val());//При перемещении курсора в колорпикере передаем его (колорпикера) значение в шестиугольник

                    adsStyles = $(MainBlock.adsStylesId);
                    var color = color_input.val();
                    if(color.indexOf('#') == -1 && color.indexOf('rgb') == -1)
                        color = '#'+color;
                    new_style = ' '+color_selector+'{ '+color_property+': '+color+' !important; } ';

                    adsStyles.append(new_style);
                }
            });

            $(document).on('click', function(event){
                if(!$(event.target).closest(MainBlock.colorpickerWindowId).length && !$(event.target).closest(MainBlock.colorPickerId).length && !$(event.target).closest(MainBlock.opacityId).length){
                    $(MainBlock.pickerId).removeClass('show');
                }
            });
        });

        $(MainBlock.pickerInputsId).on('input', function(){
            color_input = $(this);
            opacity_input = MainBlock.input(color_input.data('opacity'));
            opacity_ch = $(MainBlock.opacityId);

            opacity_val = 0;
            opacity_input.val(opacity_val);
            opacity_ch.removeClass('checked');

            color_selector = color_input.data('selector');
            color_property = color_input.data('property');
            colorpicker_btn = color_input.next('.crtv-blocks-colorpicker-btn');

            colorpicker_btn.children('span').css('background',color_input.val());
            colorpicker_btn.children('span').children('span:first-child').css('border-bottom-color',color_input.val());
            colorpicker_btn.children('span').children('span:last-child').css('border-top-color',color_input.val());

            adsStyles = $(MainBlock.adsStylesId);
            var color = color_input.val();
            if(color.indexOf('#') == -1 && color.indexOf('rgb') == -1)
                color = '#'+color;

            new_style = ' '+color_selector+'{ '+color_property+': '+color+' !important; }';

            console.log(new_style);

            adsStyles.append(new_style);
        });
    },

    setWidthInputs: function(){
        MainBlock.setWidthInput(MainBlock.input('border'));
        MainBlock.setWidthInput(MainBlock.input('adsBorder'));
        MainBlock.setWidthInput(MainBlock.input('imgBorderWidth'));
        MainBlock.setWidthInput(MainBlock.input('captionFontSize'), 24);
        MainBlock.setWidthInput(MainBlock.input('captionHoverFontSize'), 24);
        MainBlock.setWidthInput(MainBlock.input('descFontSize'), 24);
        MainBlock.setWidthInput(MainBlock.input('descLimit'), 50, function(value){
            $(MainBlock.descriptionId).each(function(index, val){
                var input = $(val);

                input.next().text(input.val().substr(0, value));
            });
        });
    },

    setWidthInput: function(input, limit, callback){
        var selector = input.data('selector');
        var property = input.data('property');

        if(typeof limit == 'undefined')
            limit = 10;

        input.spinner({
            max: limit,
            min: 0,
            value: 0,
            spin: function(event,TsrCellBorderWidth) {
                if(input.data('hover') != 'undefined' && input.data('hover')){
                    var style = ' '+selector+'{ '+property+': '+TsrCellBorderWidth.value+'px !important; } ';
                    $(MainBlock.adsStylesId).append(style);
                }else{
                    var object = $(selector);
                    if(object.length){
                        var adsStyles = $(MainBlock.adsStylesId);
                        var new_style = ' '+selector+'{ '+property+': '+TsrCellBorderWidth.value+'px !important; }';

                        adsStyles.append(new_style);
                    }
                }

                if(typeof callback == 'function'){
                    callback(TsrCellBorderWidth.value);
                }
            }
        });

        input.on('input',function(){
            if(input.data('hover') != 'undefined' && input.data('hover')){
                var style = ' '+selector+'{ '+property+': '+$(this).val()+'px !important; } ';
                $(MainBlock.adsStylesId).append(style);
            }else{
                var object = $(selector);
                if(object.length){
                    var adsStyles = $(MainBlock.adsStylesId);
                    var new_style = ' '+selector+'{ '+property+': '+$(this).val()+'px !important; }';

                    adsStyles.append(new_style);
                }
            }

            input.spinner({
                value: input.val()
            });

            if(typeof callback == 'function'){
                callback(input.val());
            }
        });
    },

    setAdsCount: function(input, limit){
        if(typeof limit == 'undefined')
            limit = 10;

        var slider = input.next(MainBlock.adsCountId);

        MainBlock.setSlider(slider, input, null, null, limit, 1);
    },

    setAdsPadding: function(){

        var slider = $(MainBlock.adsPaddingId);
        var input = MainBlock.input('indentAds');

        MainBlock.setSlider(slider, input, MainBlock.previewId+' table', 'border-spacing');
    },

    setBorderPadding: function(){
        var slider = $(MainBlock.borderPaddingId);
        var input = MainBlock.input('indentBorder');

        MainBlock.setSlider(slider, input, '.adld-tsr-cell', 'padding');
    },

    setTypeSelectors: function(){
        $('.crtv-blocks-tools :radio').on('toggle', function(){
            var selector = $(this).data('selector');
            var property = $(this).data('property');
            var selector2 = $(this).data('selector2');

            var value = $(this).val();
            var newStyle = ' '+selector+'{ '+property+': '+value+' !important; } ';

            if(typeof selector2 != 'undefined' && selector2){
                var property2 = $(this).data('property2');
                var value2 = $(this).data('value2');

                newStyle += ' '+selector2+'{ '+property2+': '+value2+' !important; } ';

                MainBlock.param('textPosition', newStyle);
            }

            $(MainBlock.adsStylesId).append(newStyle);
        });
    },

    setTextTypes: function(){
        $('.txt-style > .btn').click(function(){
            $(this).toggleClass('active');
            var txt_style_selector = $(this).data('selector');
            var txt_style_property = $(this).data('property');
            var txt_style_value = $(this).data('value');
            var input = MainBlock.input($(this).data('input'));
            var value = $(this).data('name');
            var correntVal = input.val();
            var checked = $(this).hasClass('active');

            var hover_val = $(this).data('hover');
            var styles = $(MainBlock.adsStylesId);
            var new_style;

            if(checked) {
                $(txt_style_selector).css(txt_style_property, txt_style_value);

                if(correntVal.indexOf(value) == -1){
                    input.val(correntVal+value);

                    if(typeof hover_val != 'undefined' && hover_val){
                        new_style = ' '+txt_style_selector+'{ '+txt_style_property+': '+txt_style_value+' !important; } ';
                        styles.append(new_style);
                    }
                }
            } else {
                $(txt_style_selector).css(txt_style_property, 'initial');

                if(correntVal.indexOf(value) != -1){
                    input.val(correntVal.replace(value, ''));

                    if(typeof hover_val != 'undefined' && hover_val){
                        new_style = ' '+txt_style_selector+'{ '+txt_style_property+': '+hover_val+' !important; } ';
                        styles.append(new_style);
                    }
                }
            }

            if(input.data('hover') != 'undefined' && input.data('hover')){
                if(checked)
                    var newVal = txt_style_value;
                else
                    var newVal = input.data('hover');

                var style = ' '+txt_style_selector+'{ '+txt_style_property+': '+newVal+' !important; } ';
                $(MainBlock.adsStylesId).append(style);
            }
        });
    },

    setUseDescription: function(){
        MainBlock.input('useDescription').on('change', function(){
            var input = $(this);

            var label = input.closest('label.checkbox');
            if(label.hasClass('checked')){
                $(input.data('selector')).attr('style', 'display: block !important;');
            }else{
                $(input.data('selector')).attr('style', 'display: none !important;');
            }
        });
    },

    setSelectFontFamily: function(){
        $(MainBlock.fontFamilyId).selectpicker({
            style: 'btn pull-left'
        }).on('change', function(){
            var event = $(this);
            var selector = event.data('selector');
            var attribute = event.data('attribute');

            var ads_styles = $(MainBlock.adsStylesId);
            var new_styles = ' '+selector+'{ '+attribute+': '+event.val()+' !important; } ';

            ads_styles.append(new_styles);
        });
    },

    setSlider: function(slider, input, attr, param, limit, min, fnct, params){
        if(typeof limit == 'undefined' || !limit)
            limit = MainBlock.sliderLimit;

        if(typeof min == 'undefined' || !min)
            min = 0;

        var selector = attr ? attr : input.data('selector');
        var property = param ? param : input.data('property');
        var new_styles;

        slider.slider({
            min: min,
            max: limit,
            value: input.val(),
            orientation: "horizontal",
            range: "min",
            slide:  function(event, TsrCellInnerPadding) {
                input.val(TsrCellInnerPadding.value);

                if(selector){
                    new_styles = selector+'{ '+property+': ';

                    if(param == 'border-radius')
                        new_styles += TsrCellInnerPadding.value+'%';
                    else
                        new_styles += TsrCellInnerPadding.value+'px';


                    new_styles += ' !important; } ';
                    $(MainBlock.adsStylesId).append(new_styles);
                }else{
                    MainBlock.loadPreview();
                }

                if(typeof fnct != 'undefined'){
                    fnct(params);
                };
            }
        });

        input.on('input', function(){
            var value = $(this).val();

            if(value > limit){
                value = limit;
                $(this).val(value);
            }

            slider.slider({
                value: value
            });

            if(attr !== null && attr){
                new_styles = selector+'{ '+property+': '+value+'px !important; } ';
                $(MainBlock.adsStylesId).append(new_styles);
            }else{
                MainBlock.loadPreview();
            }

            if(typeof fnct != 'undefined'){
                fnct(params);
            };
        });
    },

    loadPreview: function(){
        Main.ajax({
            url: MainBlock.previewUrl,
            data: MainBlock.getParams(),
            success: function(json){
                if(json.teasers && json.css){
                    Main.preview(json.teasers, json.css, MainBlock.previewId);
                }
            }
        });
    },

    param: function(name, value){
        if(typeof value != 'undefined'){
            MainBlock.setParam(name, value);
            return true;
        }else{
            return MainBlock.getParam(name);
        }
    },

    setParam: function(name, value){
        var input = MainBlock.input(name);

        if(input.length)
            input.val(value);
    },

    getParam: function(name){
        var input = MainBlock.input(name);

        if(input.length)
            return input.val();
        else
            return null;
    },

    input: function(id){
        id = '#'+MainBlock[id].replace('#', '');
        return $(id);
    },

    getParams: function(){
        var params = {};

        $.each($(MainBlock.formId).serializeObject(), function(index, val){
            var name = index.replace(MainBlock.modelName, '').replace('[', '').replace(']', '');
            params[name] = val;
        });

        params.id = MainBlock.id;
        params.format = MainBlock.format;

        return params;
    }

}
