/**
 * Created with JetBrains PhpStorm.
 * User: JanGolle
 * Date: 04.09.14
 * Time: 15:13
 * To change this template use File | Settings | File Templates.
 */

TargetList = {
    showModal: false,
    clipboardPath: '',
    showBlockCodeId: 'button.show-block-code',
    toggleTargetId: '.toggle-add-target',
    addCategoriesId: '.add-target-categories',
    selectPickerId: '.selectpicker',
    listNameId: '#application_models_TargetList_name',
    messageText: 'Код копирован в буфер',

    init: function(data){

        var attributes = [
            'showModal',
            'clipboardPath',
            'showBlockCodeId',
            'toggleTargetId',
            'addCategoriesId',
            'selectPickerId',
            'listNameId',
            'messageText'
        ];

        $.each(attributes, function(index, element){
            if(typeof data[element] != 'undefined')
                TargetList[element] = data[element];
        });

        TargetList.setHandlers();
    },

    setHandlers: function(){
        TargetList.setShowModal();
        TargetList.setShowBlockCode();
        TargetList.setToggleTarget();
        TargetList.setCategorySelect();
        TargetList.setTextLimits();
    },

    setShowModal: function(){
        if(TargetList.showModal){
            Main.showModal();
            TargetList.setModalSubmit();
            TargetList.setToCash();
        }
    },

    setModalSubmit: function(){
        $(Main.modalButtonCancelId).on('click', function(){
            Main.closeModal();
            return false;
        });
    },

    setToCash: function(){
        var client = new ZeroClipboard($(Main.modalButtonOkId));
        client.on('ready', function(readyEvent){
            client.on('aftercopy', function(event){
                //event.target.style.display = 'none';
                $(event.target).attr('disabled', true);
                Main.showMessage(TargetList.messageText);
            });
        });
    },

    setBlockTypeCancel: function(){
        $(Main.modalButtonCancelId).on('click', function(){
            Main.closeModal();
            return false;
        });
    },

    setShowBlockCode: function(){
        $(TargetList.showBlockCodeId).on('click', function(){
            Main.ajax({
                url: $(this).attr('data-url'),
                data: 'showBlockCode=1',
                success: function(json){
                    if(json.html){
                        $(Main.mainModalId).html(json.html);
                        TargetList.setToCash();
                        TargetList.setBlockTypeCancel();
                        Main.showModal();
                    }
                }
            });

            return false;
        });
    },

    setToggleTarget: function(){
        setInterval(function(){
            if($(Main.mainModalId).hasClass('hide'))
                $('.target-title').focus();
        },1000);

        $(TargetList.toggleTargetId).on('click', function(){
            if($('.add-target').is(':visible')){
                $('.add-target').fadeOut(150);
                $(this).delay(150).removeClass('open-state');
                $(this).html('<span class="input-icon fui-plus pull-left"></span>Добавить ретаргетинг');
            } else {
                $('.add-target').delay(150).fadeIn(150);
                $(this).addClass('open-state');
                $(this).html('<span class="input-icon fui-cross pull-left"></span>Отменить');
            }
        });
    },

    setCategorySelect: function(){
        $(TargetList.selectPickerId).selectpicker({
            style: 'btn btn-block',
            title: 'Выберите категорию вашего таргетинга',
            noneSelectedText : 'Выберите категорию вашего таргетинга'
        });
        $(TargetList.addCategoriesId).find('.select').find('.dropdown-menu').mCustomScrollbar({scrollInertia:2500});
    },

    setTextLimits: function(){
        $(TargetList.listNameId).limit('30', '.title_adloud_note');
    }
};