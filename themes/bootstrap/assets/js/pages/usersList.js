
Users = {

    moderateButtonId: 'button.user-moderate',
    activateUserButtonId: 'button.user-activate',
    deactivateUserButtonId: 'button.user-deactivate',
    banUserButtonId: 'button.user-ban',
    disbanUserButtonId: 'button.user-disban',
    checkBanId: '#check-ban-id',
    checkActiveId: '#check-activity-id',
    getBan: 'normal',
    getActive: 'active',
    pageBaseUrl: '',

    init: function(data){
        if(typeof data != 'undefined'){
            var attributes = [
                'moderateButtonId',
                'activateUserButtonId',
                'deactivateUserButtonId',
                'banUserButtonId',
                'disbanUserButtonId',
                'checkBanId',
                'checkActiveId',
                'getBan',
                'getActive',
                'pageBaseUrl'
            ];

            $.each(attributes, function(index, element){
                if(typeof data[element] != 'undefined')
                    Users[element] = data[element];
            });
        }

        $('[data-ban='+Users.getBan+']').prop('selected', true);
        $('[data-active='+Users.getActive+']').prop('selected', true);

        Users.setHandlers();
    },

    setHandlers: function(){
        $(Users.moderateButtonId).on('click', function(){
            window.location = $(this).attr('data-url');
        });

        /*$(Users.activateUserButtonId).on('click', function(){
            Users.activateUser($(this).attr('data-url'));
        });

        $(Users.deactivateUserButtonId).on('click', function(){
            Users.deactivateUser($(this).attr('data-url'));
        });*/

        $(Users.banUserButtonId).on('click', function(){
            Users.banUser($(this).data('url'));
        });

        $(Users.disbanUserButtonId).on('click', function(){
            Users.disbanUser($(this).data('url'));
        });

        Users.setBanFilter();
        Users.setActivityFilter();
    },

    activateUser: function(url){
        Main.ajax({
            url: url,
            data: 'activateUser=1',
            success: function(json){
                location.reload();
            }
        });
    },

    banUser: function(url){
        Main.ajax({
            url: url,
            data: 'banUser=1',
            success: function(json){
                location.reload();
            }
        });
    },

    deactivateUser: function(url){
        $.ajax({
            type: 'POST',
            cache: false,
            url: url,
            data: 'activateUser=0',
            dataType: 'json',
            success: function(json){
                if(json.error){
                    console.log(json.error);
                }else{
                    console.log(json.message);
                    location.reload();
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                console.log(errorThrown);
            }
        });
    },

    disbanUser: function(url){
        $.ajax({
            type: 'POST',
            cache: false,
            url: url,
            data: 'banUser=0',
            dataType: 'json',
            success: function(json){
                if(json.error){
                    console.log(json.error);
                }else{
                    console.log(json.message);
                    location.reload();
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                console.log(errorThrown);
            }
        });
    },

    setBanFilter: function(){
        $(Users.checkBanId).on('change', function(el){
            var ban = $('option:selected').val();

            if(ban == 'normal'){
                document.location.href = Main.addToUrl({'status': 'normal'});
            } else {
                document.location.href = Main.addToUrl({'status': 'banned'});
            }
        });
    },

    setActivityFilter: function(){
        $(Users.checkActiveId).on('change', function(el){
            var active = $(this).find('option:selected').val();

            console.log(active);

            if(active == 'active'){
                document.location.href = Main.addToUrl({'activity': 'active'});
            } else {
                document.location.href = Main.addToUrl({'activity': 'passive'});
            }
        });
    }

}
