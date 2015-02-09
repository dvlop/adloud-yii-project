Ads = {

    titleInputId: '#application_models_Ads_caption',
    deskInputId: '#application_models_Ads_description',
    buttonInputId: '#application_models_Ads_buttonText',
    textUrlInputId: '#application_models_Ads_showUrl',
    clickUrlInputId: '#application_models_Ads_url',
    imageInputId: '#application_models_Ads_image',
    previewId: '#preview-section',
    showButtonId: '.action_button',
    previewUrl: '',
    selectSizeId: 'ul.teaser_preview_size li a',
    previewContentId: '.tab-content',
    modalId: '#preview-modal',
    cropSourceId: '#crop-source',
    hiddenShowButtonId: '#show-button',
    cropDoneButtonId: '#crop-done',
    removeImgId: '#remove-img',
    defaultImg: '',

    image: null,
    imageParams: null,
    cropperEnabled: false,


    init: function(data){
        if(typeof data != 'undefined'){
            var attributes = [
                'titleInputId',
                'titleInputId',
                'deskInputId',
                'buttonInputId',
                'imageInputId',
                'previewId',
                'showButtonId',
                'textUrlInputId',
                'clickUrlInputId',
                'previewUrl',
                'selectSizeId',
                'previewContentId',
                'modalId',
                'cropSourceId',
                'hiddenShowButtonId',
                'cropDoneButtonId',
                'removeImgId',
                'defaultImg'
            ];

            $.each(attributes, function(index, element){
                if(typeof data[element] != 'undefined')
                    Ads[element] = data[element];
            });
        }

        Ads.setHandlers();
    },

    setHandlers: function(){
        Ads.setBlockPreview();
        Ads.setCropper();
        Ads.setTextLimit();
        Ads.setSelectPrevSize();
        Ads.setSowButton();
    },

    setBlockPreview: function(){
        Ads.getAdsPreview();

        //Texts preview
        $(Ads.titleInputId).on('input', function(){
            $(Ads.previewId+' .preview_title').text(this.value);
        });
        $(Ads.deskInputId).on('input', function(){
            $(Ads.previewId+' .preview_description').text(this.value);
        });
        $(Ads.buttonInputId).on('input', function(){
            $(Ads.previewId+' .preview_btn').text(this.value);
        });

        //url preview
        $(Ads.textUrlInputId).on('input', function(){
            $(Ads.previewId+' .preview_txt_url').text(this.value);
        });
        $(Ads.clickUrlInputId).on('input', function(){
            $(Ads.previewId+' .preview_txt_url').attr('href', this.value);
        });
    },

    setCropper: function(){
        var dialog = $(Ads.modalId).dialog({
            width: 600,
            autoOpen: false
        });

        var cropper = null;
        $(Ads.cropSourceId).bind("DOMNodeInserted", function(){
            setTimeout(function(){
                if(!Ads.cropperEnabled){
                    cropper = $(Ads.cropSourceId+' img');
                    cropper.cropper({
                        aspectRatio: 1,
                        preview: ".image-preview"
                    });
                    Ads.cropperEnabled = true;
                }
            }, 1);
        });

        $(Ads.imageInputId).change(function(){
            var preview = $(Ads.previewId);

            dialog.dialog('open');
            $('.ui-dialog-titlebar').hide();
            $('.ui-dialog').css({top: -1050, left: preview.offset().left-600});
        });

        $(Ads.cropDoneButtonId).on('click', function(){
            $("#crop-params").val(JSON.stringify(cropper.cropper('getData')));
            $(Ads.modalId).dialog('close');
            Ads.image = $(Ads.previewId+' img')[0];
            Ads.imageParams = $(Ads.previewId+' img').attr('style');
            Ads.cropperEnabled = false;
        });

        $(Ads.removeImgId).on('click', function(){
            cropper = null;
            dialog.dialog('close');
            if(Ads.defaultImg){
                $(Ads.previewId+' img').parent('div').html(Ads.defaultImg);
                Ads.image = null;
            }

            return false;
        });
    },

    setTextLimit: function(){
        var titleInput = $(Ads.titleInputId);
        var deskInput = $(Ads.deskInputId);
        var buttonInput = $(Ads.buttonInputId);
        titleInput.limit(titleInput.attr('maxlength'), Ads.titleInputId+'+span>span');
        deskInput.limit(deskInput.attr('maxlength'), Ads.deskInputId+'+span>span');
        buttonInput.limit(buttonInput.attr('maxlength'), Ads.buttonInputId+'+span>span');
    },

    setSelectPrevSize: function(){
        var selectors = $(Ads.selectSizeId);
        selectors.on('click', function(){
            selectors.each(function(index, selector){
                $(selector).parent('li').removeClass('active');
            });

            var selector = $(this);
            selector.parent('li').addClass('active');
            Ads.getAdsPreview(selector.attr('data-id'));
            return false;
        });
    },

    setSowButton: function(){
        $('label.radio').on('click', function(){
            if($('input', $(this)).val() == 1){
                $(Ads.buttonInputId).closest('.form-group').css('display', 'block');
                $(Ads.previewId+' .preview_btn').css('display', 'block');
                $(Ads.hiddenShowButtonId).val(1);
            }else{
                $(Ads.buttonInputId).closest('.form-group').css('display', 'none');
                $(Ads.previewId+' .preview_btn').css('display', 'none');
                $(Ads.hiddenShowButtonId).val(0);
            }
        });
    },

    getAdsPreview: function(adsType){
        var title = $(Ads.titleInputId).val();
        var desk = $(Ads.deskInputId).val();
        var buttonText = $(Ads.buttonInputId).val();
        var url = $(Ads.clickUrlInputId).val();
        var urlText = $(Ads.textUrlInputId).val();
        var showButton = $('.radio.adloud_label.checked input[data-toggle="radio"]').val();
        var adsTypeAttr = '';

        if(typeof adsType != 'undefined')
            adsTypeAttr = '&adsType='+adsType;

        Main.ajax({
            url: Ads.previewUrl,
            data: 'title='+title+'&desk='+desk+'&buttonText='+buttonText+'&url='+url+'&urlText='+urlText+'&showButton='+showButton+adsTypeAttr,
            updateId: Ads.previewId+' '+Ads.previewContentId,
            success: function(json){
                if(Ads.image != null && typeof Ads.image != 'undefined'){
                    $(Ads.previewId+' img').parent('div').html(Ads.image);
                }
                if(!Ads.defaultImg){
                    Ads.defaultImg = $(Ads.previewId+' img')[0];
                }
            }
        });
    }

}