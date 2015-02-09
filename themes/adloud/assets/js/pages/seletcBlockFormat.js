SelectFormat = {

    cbLogoId: '.creative-blocks-logo > img',
    nawItemId: '.crtv-blocks-type-nav-item',
    typeItemId: '.crtv-blocks-type-item',
    typeVariant: '.crtv-blocks-type-variant',

    init: function(data){
        if(typeof data != 'undefined'){
            var attributes = [
                'cbLogoId',
                'nawItemId',
                'typeItemId',
                'typeVariant'
            ];

            $.each(attributes, function(index, element){
                if(typeof data[element] != 'undefined')
                    SelectFormat[element] = data[element];
            });
        }

        SelectFormat.setHandlers();
    },

    setHandlers: function(){
        SelectFormat.hideScrollBar();
        SelectFormat.setAnimation();
        SelectFormat.setSelectRow();
        SelectFormat.setScroll();
    },

    hideScrollBar: function(){
        $('html,body').css({
            'overflow': 'hidden'
        });
    },

    setAnimation: function(){
        $(SelectFormat.cbLogoId).animate(
            {
                width:282
            },
            {
                duration: 500,
                easing: 'linear',
                complete: function(){
                    $('.crtv-blocks-type-variant, .crtv-blocks-type-note').delay(500).slideDown(500, function(){
                        $('.crtv-blocks-type-note span').tooltip();
                    });
                }
            }
        );
    },

    setSelectRow: function(){
        $(SelectFormat.nawItemId).click(function(){
            if($(this).hasClass('active') !== true) {

                $(SelectFormat.nawItemId+'.active').removeClass('active');
                $(this).addClass('active');

                var scrollTo = $(this).data('section');
                $(SelectFormat.typeItemId+'.active,'+scrollTo).toggleClass('active');

                $(SelectFormat.typeVariant).animate({
                    'margin-top': 200-($(this).prevAll().length*254)
                });
            }
        });
    },

    setScroll: function(){
        var body = $('body');

        body.bind('DOMMouseScroll', function(e){
            var event = e.originalEvent;
            var detail = event.detail;

            SelectFormat.scroll(event, detail);
        });

        body.bind('mousewheel', function(e){
            var event = e.originalEvent;
            var detail = event.wheelDelta;

            SelectFormat.scroll(event, detail);
        });
    },

    scroll: function(event, detail){
        var item = $(SelectFormat.nawItemId+'.active');

        if(detail > 0)
            SelectFormat.scrollUp(item);
        else
            SelectFormat.scrollDown(item);
    },

    scrollDown: function(item){
        var next = item.next(SelectFormat.nawItemId);
        if(next.length)
            next.click();
    },

    scrollUp: function(item){
        var prev = item.prev(SelectFormat.nawItemId);
        if(prev.length)
            prev.click();
    }

}