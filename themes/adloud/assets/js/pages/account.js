Account = {

    selectFileId: '.upload_img .fileinput-new',
    changeFileId: '.upload_img .fileinput-exists',
    fileInputId: '#UserInfoModel_image',
    previewModalId: '#preview-modal',
    cropDoneId: '#crop-done',
    dialogWindow: null,
    cropper: null,
    previewImageId: 'div.fileinput-preview',
    cropParamsId: '#UserInfoModel_cropParams',
    imgSrcId: 'div.fileinput-preview',
    cropImgId: '#crop-source',
    cropParam: 1/1,
    cropResetId: '#crop-reset',
    cropPreviewId: '#crop-preview',
    setPassButtonId: '#pass-submit',
    oldPassInputId: 'input[name="UserInfoModel[password]"]',
    newPassInputId: 'input[name="UserInfoModel[newPassword]"]',
    retypePassInputId: 'input[name="UserInfoModel[newPassword2]"]',

    init: function(data){
        if(typeof data != 'undefined'){
            var attributes = [
                'selectFileId',
                'changeFileId',
                'fileInputId',
                'previewModalId',
                'cropDoneId',
                'previewImageId',
                'cropDoneButtonId',
                'cropParamsId',
                'imgSrcId',
                'cropImgId',
                'cropParam',
                'cropResetId',
                'cropPreviewId',
                'setPassButtonId',
                'oldPassInputId',
                'newPassInputId',
                'retypePassInputId'
            ];

            $.each(attributes, function(index, element){
                if(typeof data[element] != 'undefined')
                    Account[element] = data[element];
            });
        }

        Account.setHandlers();
    },

    setHandlers: function(){
        Account.setSelectFile();
        Account.setCropDone();
        Account.setCropReset();
        Account.setPasswordChange();
    },

    setSelectFile: function(){
        Account.dialogWindow = $(Account.previewModalId).dialog({
            width: 600,
            autoOpen: false
        });

        $(Account.imgSrcId).bind('DOMNodeInserted', function(){
            $(Account.cropImgId).html($(this).html());
            Account.dialogWindow.dialog('open');
            Account.setImageCropper();
        });
    },

    setImageCropper: function(){
        $(Account.cropImgId+' img').on('click', function(){
            var img = $(Account.cropPreviewId+' img');

            Account.cropper = $(this).cropper({
                aspectRatio: Account.cropParam,
                //preview: Account.cropPreviewId,
                done: function(data){
                    //img.attr('style', 'width: 100%; height: 100%; position: absolute; clip: rect('+data.y1+'px '+data.x1+'px '+data.y2+'px '+data.x2+'px); left: -'+data.x2+'px; top: -'+data.y1+'px;');
                }
            });
        });
    },

    setCropDone: function(){
        $(Account.cropDoneId).on('click', function(){

            if(Account.cropper == null){
                alert('Выберите участок изображения');
                return;
            }

            $(Account.cropParamsId).val(JSON.stringify(Account.cropper.cropper('getData')));
            Account.dialogWindow.hide();
        });
    },

    setCropReset: function(){
        $(Account.cropResetId).on('click', function(){
            Account.dialogWindow.hide();
            $(Account.fileInputId).val('');
            $(Account.cropParamsId).val('');
        });
    },

    setPasswordChange: function(){
        $(Account.setPassButtonId).on('click', function(){
            var oldPass = $(Account.oldPassInputId).val();
            if(!oldPass){
                Main.showError('Неоходимо указать текущий пароль', 'error');
                return false;
            }

            var newPassword = $(Account.newPassInputId).val();
            if(!newPassword){
                Main.showError('Пожалуйста, укажите пароль', 'error');
                return false;
            }

            var retypePassword = $(Account.retypePassInputId).val();
            if(!retypePassword){
                Main.showError('Пожалуйста, повторите пароль', 'error');
                return false;
            }

            if(newPassword != retypePassword){
                Main.showError('Введённые пароли не совпадают', 'error');
                return false;
            }

            $.ajax({
                type: 'POST',
                cache: false,
                url: $(this).attr('data-url'),
                data: 'oldPassword='+oldPass+'&newPassword='+newPassword,
                dataType: 'json',
                success: function(json){
                    if(json.error){
                        Main.showError(json.error);
                        return false;
                    }else{
                        Main.showMessage(json.message);
                        return false;
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    console.log(errorThrown);
                    //Main.showError(errorThrown);
                    return false;
                }
            });

            return false;
        });
    }
}