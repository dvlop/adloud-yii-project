$(document).ready(function(){

    $("#AdsForm_showButton").bootstrapSwitch({
        onText: "Да",
        offText: "Нет",
        onSwitchChange: function(event, state){
            var buttonTextContainer = $("#AdsForm_buttonText").closest("div.form-group");
            if(state)
                buttonTextContainer.removeClass("hidden");
            else
                buttonTextContainer.addClass("hidden");

            Ads.changeAds();
        }
    });

    $(Ads.containerId).closest("form").on("change", "input, textarea", function(){
        Ads.changeAds($(this));
    });
});

Ads = {
    url: 'upload.php',
    containerId: '#ads-creating',
    previewId: '#ads-preview',
    uploadId: '#AdsForm_imageUpload',
    uploadHiddenId: '#AdsForm_image',
    errorId: '#AdsForm_error',
    errorText: 'Возникли некоторые ошибки:',
    allowedFormats: 'jpg|jpeg|png|bmp|gif|ico',
    showSystemErrors: false,
    name: 'upload-file',
    errorClass: 'has-error',
    form: {},
    urlFieldId: '#AdsForm_url',
    captionFieldId: '#AdsForm_caption',
    descriptionFieldId: '#AdsForm_description',
    buttonTextFieldId: '#AdsForm_buttonText',
    beforeUploadImageId: '#cropping-window-modal',
    beforeUploadImageContainerId: '#crop-image-container-id',
    imageForCropId: '#ads-image-crop-id',
    modalWindowHidden: '#ads-model-window-hidden-id',
    ccordinates: { x: 0, x2: 0, y: 0, y2: 0, w: 0, h: 0 },
    submitCroppingId: '#submit-crop-image',
    imgContainerClass: '.adloud-img-conatainer',
    modal: {},
    jcrop: {},
    defaultCroppingWidth: 100,
    defaultCroppingHeight: 100,

    init: function(data){

        var attributes = [
            'url',
            'containerId',
            'previewId',
            'uploadId',
            'errorId',
            'errorText',
            'allowedFormats',
            'showSystemErrors',
            'name',
            'errorClass',
            'form',
            'beforeUploadImageId',
            'beforeUploadUrl',
            'submitCroppingId',
            'modalWindowHidden',
            'beforeUploadImageContainerId',
            'imgContainerClass',
            'defaultCroppingWidth',
            'defaultCroppingHeight'
        ];

        $.each(attributes, function(index, element){
            if(typeof data[element] != 'undefined')
                Ads[element] = data[element];
        });

        Ads.initUploader();
        Ads.changeAds();
    },

    changeAds: function(input){
        Ads.form = $(Ads.containerId).closest("form");
        var type = 'text';

        if(typeof input != 'undefined'){
            type = input.attr('type');
        }else{
            input = $(Ads.uploadId);
        }

        if(type != 'file'){
            $.ajax({
                type: 'POST',
                cache: false,
                url: Ads.url,
                data: Ads.form.serialize(),
                dataType: 'json',
                success: function(json){

                    if(json.error){
                        if(Ads.showSystemErrors) Ads.previewError(json.error, json.invalidInputs, input);
                    }else{
                        $(Ads.errorId).addClass('hidden');
                        input.closest('div.form-group').removeClass(Ads.errorClass);
                        Ads.addStyles(json.css);
                        $(Ads.previewId).html(json.html);
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    var error = Ads.showSystemErrors ? errorThrown : 'Не удалось создать предпросмотр';
                    //Ads.previewError(error);
                }
            });
        }
    },

    initUploader: function(){
        Ads.form = $(Ads.containerId).closest("form");

        Ads.form.fileupload({
            url: Ads.beforeUploadUrl,
            dataType: 'json',
            done: function (e, data) {
                var result = data._response.result;

                if(result.error){
                    var error = Ads.showSystemErrors ? data.error : 'Не удалось загрузить изображение';
                    Ads.previewError(error);
                }else{
                    $(Ads.beforeUploadImageContainerId).html(result.img);

                    $(Ads.beforeUploadImageContainerId+' img').imgAreaSelect({
                        aspectRatio: '1:1',
                        handles: true,
                        //x1: 0,
                        //y1: 0,
                        //x2: Ads.defaultCroppingWidth,
                        //y2: Ads.defaultCroppingHeight,
                        onSelectEnd: function(img, selection){
                            $(Ads.modalWindowHidden+' .image-height').val(selection.height);
                            $(Ads.modalWindowHidden+' .image-width').val(selection.width);
                            $(Ads.modalWindowHidden+' .image-x1').val(selection.x1);
                            $(Ads.modalWindowHidden+' .image-x2').val(selection.x2);
                            $(Ads.modalWindowHidden+' .image-y1').val(selection.y1);
                            $(Ads.modalWindowHidden+' .image-y2').val(selection.y2);
                        }
                    });

                    $(Ads.uploadHiddenId).val(result.imageUrl);
                    $(Ads.beforeUploadImageId).modal('show');
                }
            }
        });

        $(Ads.beforeUploadImageId+' button').on('click', function(){
            if($(this).attr('id') != $(Ads.submitCroppingId).attr('id')){
                Ads.clearJcrop();
            }
        });

        $(Ads.submitCroppingId).on('click', function(){
            Ads.uploadImageForCrop();
            Ads.clearJcrop();
        });
    },

    uploadImageForCrop: function(){

        var data = 'coordinates[h]='+$(Ads.modalWindowHidden+' .image-height').val()+
            '&coordinates[w]='+$(Ads.modalWindowHidden+' .image-width').val()+
            '&coordinates[x]='+$(Ads.modalWindowHidden+' .image-x1').val()+
            '&coordinates[x2]='+$(Ads.modalWindowHidden+' .image-x2').val()+
            '&coordinates[y]='+$(Ads.modalWindowHidden+' .image-y1').val()+
            '&coordinates[y2]='+$(Ads.modalWindowHidden+' .image-y2').val()+
            '&coordinates[image]='+$(Ads.uploadHiddenId).val();

        $.ajax({
            type: 'POST',
            cache: false,
            url: Ads.beforeUploadUrl,
            data: data,
            dataType: 'json',
            success: function(json){
                if(json.error){
                    var error = Ads.showSystemErrors ? json.error: 'Возникли проблемы при загрузке файла';
                    Ads.previewError(error);
                }else{
                    $(Ads.beforeUploadImageId).modal('hide');

                    $(Ads.previewId+' '+Ads.imgContainerClass).html(json.img);
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                var error = Ads.showSystemErrors ? errorThrown : 'Не удалось создать предпросмотр';
                Ads.previewError(error);
            }
        });
    },

    clearJcrop: function(){
        $(Ads.beforeUploadImageId+' img').imgAreaSelect({remove:true});
    },

    previewError: function(error, invalidInputs, input){
        if(typeof input == 'undefined')
            input = $(Ads.uploadId);

        if(typeof invalidInputs == 'object'){
            for(num in invalidInputs){
                if(input.attr('name') == invalidInputs[num])
                    input.closest('div.form-group').addClass(Ads.errorClass);
            }
        }

        var errorHtml = '<div class="errorSummary">'+Ads.errorText+'<ul><li>'+error+'</li></ul></div>';
        $(Ads.errorId).removeClass('hidden');
        $(Ads.errorId).html(errorHtml);
    },

    addStyles: function(styles){

        var head = document.head || document.getElementsByTagName('head')[0];
        var stylesEl = document.createElement('style');

        stylesEl.type = 'text/css';

        if (stylesEl.styleSheet){
            stylesEl.styleSheet.cssText = styles;
        } else {
            stylesEl.appendChild(document.createTextNode(styles));
        }

        head.appendChild(stylesEl);
    }

}



