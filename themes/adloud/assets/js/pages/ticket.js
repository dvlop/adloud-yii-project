/**
 * Created with JetBrains PhpStorm.
 * User: JanGolle
 * Date: 30.07.14
 * Time: 13:21
 * To change this template use File | Settings | File Templates.
 */

Ticket = {

    addTicketId: '.add_ticket_btn button',
    addTicketForm: '#add_ticket',
    radioId: '.radio',
    addButtonId: '#send_ticket',
    talksFormId: '.add-new-ticket',
    addAnswer: '#send_msg',
    scrolledDivId: '.chat_inner',
    contentId: '#add-new-ticket-text',
    messageListId: '.ticket-msg-list',
    ticketAddForm: '.add-new-ticket-text-wrapper',
    messageImg: '.ticket-msg-item-content img',
    fileLoaderId: '#ticket-msg-file-uploader',

    init: function(data){

        var attributes = [
            'addTicketId',
            'addTicketForm',
            'radioId',
            'addButtonId',
            'talksFormId',
            'scrolledDivId',
            'contentId',
            'messageListId',
            'ticketAddForm',
            'messageImg',
            'fileLoaderId'
        ];

        $.each(attributes, function(index, element){
            if(typeof data[element] != 'undefined')
                Ticket[element] = data[element];
        });

        var scrollForm = $(Ticket.scrolledDivId);

        if(scrollForm[0] != undefined){
            var height = scrollForm[0].scrollHeight;
            scrollForm.scrollTop(height);
        }

        Ticket.setHandlers();
    },

    setHandlers: function(){
        Ticket.setAddTicket();
        Ticket.setRadioSwitcher();
        Ticket.setSendTicket();
        Ticket.setTalksAnswer();
        Ticket.setScroll();
        Ticket.setZoomIn();
        Ticket.setFileLoader();
    },

    setAddTicket: function(){
        $(Ticket.addTicketId).on('click', function(){
            $(Ticket.addTicketForm).css('display','block')
        });
    },

    setRadioSwitcher: function(){
        $(Ticket.radioId).on('click', function(){
           if($(this).hasClass('checked')) {
               return false;
           } else {
               var last = $(Ticket.radioId+' .checked');

               last.removeClass('checked');
               last.last().removeAttr('checked');

               $(this).addClass('checked');
               $(this).children().last().attr('checked', 'checked');
           }
        });
    },

    setSendTicket: function(){
        $(Ticket.addTicketForm).on('submit', function(){
            var params = {
                category: $('.radio'+'.checked').children().last().val(),
                name: $('[name="ticket_theme"]').val(),
                message: $('[name="ticket_message"]').val()
            };

            Main.ajax({
                url: $(Ticket.addButtonId).attr('data-url'),
                data: params,
                success: function(json){
                    document.location.reload();
                }
            });

            return false;
        });
    },

    setTalksAnswer: function(){
        $(Ticket.talksFormId).on('submit', function(){
            var content = $(Ticket.contentId).html();

            if (content == ''){
                return false;
            }

            var params = {
                message: content,
                ticket_id: $(Ticket.addAnswer).attr('data-ticket')
            };

            console.log(params);

            Main.ajax({
                url: $(Ticket.addAnswer).attr('data-url'),
                data: params,
                success: function(json){
                    document.location.reload();
                }
            });

            return false;
        });
    },

    setScroll: function(){
        $(Ticket.messageListId).mCustomScrollbar({
            'setTop':'-9999px'
        });
        $(Ticket.ticketAddForm).mCustomScrollbar();
    },

    setZoomIn: function(){
        $(Ticket.messageImg).on('click',function(){
            $('.zoom-bg').toggleClass('hidden');
            $(this).toggleClass('zoomin');
        });
        $('.zoom-bg').on('click',function(){
            $(this).toggleClass('hidden');
            $('.ticket-msg-item-content img.zoomin').removeClass('zoomin');
        });
    },

    handleFileSelect: function(evt){
        var files = evt.target.files; // FileList object

        // Loop through the FileList and render image files as thumbnails.
        for (var i = 0, f; f = files[i]; i++) {

            // Only process image files.
            if (!f.type.match('image.*')) {
                continue;
            }

            var reader = new FileReader();

            // Closure to capture the file information.
            reader.onload = (function(theFile) {
                return function(e) {
                    // Render thumbnail.
                    Main.ajax({
                        url: $(Ticket.fileLoaderId).attr('data-url'),
                        data: {img: e.target.result},
                        success: function(json){
                            $('<br>').appendTo($(Ticket.contentId));
                            $('<img/>',{
                                src: json.image,
                                title: escape(theFile.name)
                            }).appendTo($(Ticket.contentId));
//                            var div = document.createElement('div');
//                            div.innerHTML = ['<img src="', json.image,
//                                '" title="', escape(theFile.name), '"/>'].join('');
//                            document.getElementById('add-new-ticket-text').insertBefore(div, null);
                        }
                    });
                };
            })(f);

            // Read in the image file as a data URL.
            reader.readAsDataURL(f);
        }
    },

    setFileLoader: function(){
        document.getElementById('ticket-msg-file-uploader').addEventListener('change', Ticket.handleFileSelect, false);
    }
}