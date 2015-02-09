/**
 * Created with JetBrains PhpStorm.
 * User: JanGolle
 * Date: 28.07.14
 * Time: 12:19
 * To change this template use File | Settings | File Templates.
 */

Referals = {

    copyButtonId: '#copy-btn',
    clipboardPath: '',

    init: function(data){
        var attributes = [
            'clipboardPath',
            'copyButtonId'
        ];

        $.each(attributes, function(index, element){
            if(typeof data[element] != 'undefined')
                Referals[element] = data[element];
        });

        Referals.setHandlers();
    },

    setHandlers: function(){
        Referals.setToCash();
    },

    setToCash: function(){
        var client = new ZeroClipboard($(Referals.copyButtonId), {
            moviePath: Referals.clipboardPath+'/ZeroClipboard.swf',
            debug: false
        });

        client.on('load', function(client){
            client.on('complete', function(client, args){
                client.setText(args.text);
            });
        });
    }
}
