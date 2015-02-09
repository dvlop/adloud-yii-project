/**
 * Created by dima on 07.05.14.
 */

Block = {
    previewUrl: '',
    baseUrl: '',
    returnUrl: '',
    teaserSize: '160x600',
    teaserBg: 'gray',
    teaserColor: 'standart',
    defaultColor: 'standart',
    hiddenContainerId: '#block-hidden-fields',
    hiddenSizeId: '#BlockForm_size',
    hiddenColorId: '#BlockForm_color',
    hiddenBdId: '#BlockForm_bg',
    iframe: null,
    previewId: '#block-preview',

    init: function(data){

        var attributes = [
            'previewUrl',
            'baseUrl',
            'returnUrl',
            'teaserSize',
            'teaserBg',
            'teaserColor',
            'defaultColor',
            'hiddenContainerId',
            'previewId'
        ];

        $.each(attributes, function(index, element){
            if(typeof data[element] != 'undefined')
                Block[element] = data[element];
        });
        
        $('.teaser_block_fill').find('label[cheked]');
        
        Block.setHandlers();
    },

    setHandlers: function(){
        Block.setReset();
        Block.setSelectorBlockSize();
        Block.setPreloader();
        Block.setBlockPreview();
    },

    setReset: function(){
        $('button[type="reset"]').on('click', function(){
            window.location = Block.returnUrl;
        });
    },

    setSelectorBlockSize: function(){
        $(".teaser_block_size").selectpicker({
            style: 'btn btn-default btn-block'
        });
    },

    setPreloader: function(){
        $(document).ready(function(){
            $('#pre_add_block').delay(1500).fadeOut(1000);
        });
    },

    setBlockPreview: function(){
        var size = $(Block.hiddenSizeId).val();
        var color = $(Block.hiddenColorId).val();
        var bg = $(Block.hiddenBdId).val();

        if(size != Block.teaserSize){
            Block.teaserSize = size;
        }
        if(color != Block.teaserColor){
            Block.teaserColor = color;
        }
        if(bg != Block.teaserBg){
            Block.teaserBg = bg;
        }

        var teaser_size = Block.teaserSize; //формат тизера
        var teaser_bg = Block.teaserBg; //цвет фона тизера
        var teaser_color = Block.teaserColor; //цветовая схема тизера

        Block.iframe = $(Block.previewId);

        Block.getPreview(teaser_size, teaser_color, teaser_bg);

        Block.setChangeSize();
        Block.setChangeColor();
        Block.setChangeBg();
    },

    setChangeSize: function(){
        $('.teaser_block_size').on('change', function(){

            var teaser_size = $(this).val();
            var teaser_bg = $('.teaser_block_fill.checked').children('input').val();
            var teaser_color = $('.scheme_item.selected').data('color-scheme');

            Block.changeSize(teaser_size, teaser_color, teaser_bg);
        });
    },

    setChangeColor: function(){
        $('.teaser_block_fill').on('click', function(){

            var teaser_size = $('.teaser_block_size').val();
            var teaser_bg = $(this).children('input').val();
            var teaser_color = $('.scheme_item.selected').data('color-scheme');

            if(typeof teaser_size == 'undefined')
                teaser_size = Block.teaserSize;

            if(typeof teaser_bg == 'undefined')
                teaser_bg = Block.teaserBg;

            if(typeof teaser_color == 'undefined')
                teaser_color = Block.teaserColor;

            $(Block.hiddenContainerId+' input.bg').val(teaser_bg);

            if($('.scheme_item').is('.selected')) {//если выбрана не стандартная цветовая схема
                Block.getPreview(teaser_size, teaser_color, teaser_bg);
            }

            else{//если юзер не изменял цветовую схему
                Block.getPreview(teaser_size, Block.defaultColor, teaser_bg);
            }
        });
    },

    setChangeBg: function(){
        $('.scheme_item div').on('click', function(){

            //Выделение выбранной цветовой схемы в блоке "Выберите цветовое решение:"
            $('.scheme_item.selected').removeClass('selected');
            $(this).parent('.scheme_item').addClass('selected');


            var teaser_size = $('.teaser_block_size').val();
            var teaser_color = $(this).parent('.scheme_item').data('color-scheme');
            var teaser_bg = $('.teaser_block_fill.checked').children('input').val();

            if(typeof teaser_size == 'undefined')
                teaser_size = Block.teaserSize;

            if(typeof teaser_bg == 'undefined')
                teaser_bg = Block.teaserBg;

            if(typeof teaser_color == 'undefined')
                teaser_color = Block.teaserColor;

            $(Block.hiddenContainerId+' input.color').val(teaser_color);

            if($('.scheme_item').is('.selected')) {//если выбрана не стандартная цветовая схема
                Block.getPreview(teaser_size, teaser_color, teaser_bg);
            }

            else{//если юзер не изменял цветовую схему
                Block.getPreview(teaser_size, Block.defaultColor, teaser_bg);
            }
        });
    },

    changeSize: function(teaser_size, teaser_color, teaser_bg){
        $(Block.hiddenContainerId+' input.size').val(teaser_size);

        var iframe_width = $('.teaser_block_size :selected').data('width');//определяем необходимую широту iframe
        var iframe_height = $('.teaser_block_size :selected').data('height');//определяем необходимую высоту iframe



        if(typeof teaser_size == 'undefined')
            teaser_size = Block.teaserSize;

        if(typeof teaser_bg == 'undefined')
            teaser_bg = Block.teaserBg;

        if(typeof teaser_color == 'undefined')
            teaser_color = Block.teaserColor;

        Block.iframe.css({'width':iframe_width, 'height': iframe_height});//устанавливаем ширину и высоту iframe
        Block.iframe.parent('.preview-block-window').attr('class','row preview-block-window preview-'+teaser_size+'');//устанавливаем соответствующий класс для родителя iframe

        if($('.scheme_item').is('.selected')) {//если выбрана не стандартная цветовая схема
            Block.getPreview(teaser_size, teaser_color, teaser_bg);
        }

        else {//если юзер не изменял цветовую схему
            Block.getPreview(teaser_size, Block.defaultColor, teaser_bg);
        }
    },

    getPreview: function(teaser_size, teaser_color, teaser_bg, previewId){
        if(typeof previewId == 'undefined')
            previewId = Block.previewId;

        Main.ajax({
            url: Block.previewUrl,
            data: '&previewTeaser=true&teaserSize='+teaser_size+'&teaserColor='+teaser_color+'&teaserBg='+teaser_bg,
            success: function(json){
                if(json.teasers && json.css){
                    Main.preview(json.teasers, json.css, previewId);
                }
            }
        });
    }
}