AdsBlock = {

    iframeUrl: 'webmaster/block/getPreview',
    iframeContainerId: '#preview',
    blockTypeId: '#BlockForm_type',
    pervievId: '#preview',
    adsNumRowsId: '#ads-number-rows',
    adsNumColumnsId: '#ads-number-columns',
    defaultAdsType: 'Ads',
    defaultCssValue: 'defaultAdsCss',
    numberRowsId: '#BlockForm_adsNumberRows',
    numberColumnsId: '#BlockForm_adsNumberColumns',
    blockStyleId: '#BlockForm_css',

    init: function(data){
        var attributes = [
            'iframeUrl',
            'iframeContainerId',
            'blockTypeId',
            'pervievId',
            'adsNumRowsId',
            'adsNumColumnsId',
            'defaultAdsType',
            'defaultCssValue',
            'numberRowsId',
            'numberColumnsId',
            'blockStyleId'
        ];

        if(typeof data.iframeUrl == 'undefined')
            data.iframeUrl = $('input.slider').attr('data-url');

        $.each(attributes, function(index, element){
            if(typeof data[element] != 'undefined')
                AdsBlock[element] = data[element];
        });

        $(AdsBlock.iframeContainerId).html('<iframe src="'+AdsBlock.iframeUrl+'?type='+$(AdsBlock.blockTypeId).val()+'&css='+$(AdsBlock.blockStyleId).val()+'&numRows='+$(AdsBlock.numberRowsId).val()+'&numColumns='+$(AdsBlock.numberColumnsId).val()+'"></iframe>');

        $(AdsBlock.blockTypeId).closest('form').on('change', function(){
            AdsBlock.changeIframe();
        });

        $('#ads-number-rows').slider().on('slide', function(ev){
            $('#BlockForm_adsNumberRows').val(ev.value).change();
            AdsBlock.changeIframe(null, null, ev.value, null);
        });

        $('#ads-number-columns').slider().on('slide', function(ev){
            $('#BlockForm_adsNumberColumns').val(ev.value).change();
            AdsBlock.changeIframe(null, null, null, ev.value);
        });
    },

    changeIframe: function(type, css, rows, columns){

        if(typeof type == 'undefined' || type == null || type == false)
            type = $(AdsBlock.blockTypeId).val();

        if(typeof css == 'undefined' || css == null || css == false)
            css = $(AdsBlock.blockStyleId).val();

        if(typeof rows == 'undefined' || rows == null || rows == false)
            rows = $(AdsBlock.numberRowsId).val();

        if(typeof columns == 'undefined' || columns == null || columns == false)
            columns = $(AdsBlock.numberColumnsId).val();

        if(!type)
            type = AdsBlock.defaultAdsType;

        if(!css)
            css = AdsBlock.defaultCssValue;

        if(!rows)
            rows = 1;

        if(!columns)
            columns = 1;


        $(AdsBlock.pervievId).find('iframe').attr({src: AdsBlock.iframeUrl+'?type='+type+'&css='+css+'&numRows='+rows+'&numColumns='+columns});
    }

}

