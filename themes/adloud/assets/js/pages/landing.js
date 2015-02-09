Landing = {

    navBarId: '.land-fixed-nav.navbar',
    openFormId: '.form-opener',
    regButtonId: '#register-button',
    loginButtonId: '#login-button',
    restoreButtonId: '#restore-button',
    closeFormsWrapperId: '.close-forms',
    formSectionId: '.form-section',

    init: function(data){

        var attributes = [
            'navBarId',
            'openFormId',
            'regButtonId',
            'loginButtonId',
            'restoreButtonId',
            'closeFormsWrapperId',
            'formSectionId'
        ];

        $.each(attributes, function(index, element){
            if(typeof data[element] != 'undefined')
                Landing[element] = data[element];
        });

        Landing.setHandlers();
    },

    setHandlers: function(){
        Landing.toggleNavBar();
        Landing.setFormOpeners();
        Landing.setFormsCloser();
        Landing.setLoginAction();
        Landing.setRegisterAction();
        Landing.setRestoreAction();
    },

    setFormOpeners: function(){
        $(Landing.openFormId).on('click', function(){
            Landing.closeAllForms();
            Landing.openForm($(this).attr('data-id'));
        });
    },

    setFormsCloser: function(){
        $(Landing.closeFormsWrapperId).on('click', function(){
            Landing.closeAllForms();
        });
    },

    setLoginAction: function(){
        $(Landing.loginButtonId).on('click', function(){
            var button = $(this);

            var success = function(json){
                if(json.error){
                    Landing.closeAllForms();
                    Main.handleFormErrors(json.error, button.closest('form'));
                }else{
                    window.location = json.url;
                }
            };

            return Landing.formsActionsHandle(button, success);
        });
    },

    setRegisterAction: function(){
        $(Landing.regButtonId).on('click', function(){
            var button = $(this);
            var form = button.closest('form');

            var success = function(json){
                if(json.error){
                    Landing.closeAllForms();
                    Main.handleFormErrors(json.error, form);
                }else{
                    window.location = json.url;
                }
            };

            return Landing.formsActionsHandle(button, success);
        });
    },

    setRestoreAction: function(){
        $(Landing.restoreButtonId).on('click', function(){
            var button = $(this);

            var success = function(json){
                if(json.error){
                    Landing.closeAllForms();
                    Main.handleFormErrors(json.error, button.closest('form'));
                }else{
                    Landing.closeAllForms();
                    Main.showMessage(json.message);
                }
            };

            return Landing.formsActionsHandle(button, success);
        });
    },

    formsActionsHandle: function(button, success){
        var form = button.closest('form');

        if(form[0].checkValidity())
            Main.ajaxHandler(form.attr('action'), form.serialize(), success);
        else
            return true;

        return false;
    },

    closeAllForms: function(){
        $(Landing.formSectionId).fadeOut(100);
    },

    openForm: function(formId){
        $(formId).fadeIn(100);
    },

    toggleNavBar: function(){
        var window_h = window.innerHeight;
        var land_h = $('.landing-page').innerHeight();
        var scroll_stop = land_h - window_h;
        window.onscroll = (function () {
            if (window.pageYOffset > window_h  && window.pageYOffset < scroll_stop) {
                $(Landing.navBarId).css('display','block');
            }
            else {
                if (window.pageYOffset < window_h || window.pageYOffset >= scroll_stop) {
                    $(Landing.navBarId).css('display','none');
                }
            }
        });
    }

}
