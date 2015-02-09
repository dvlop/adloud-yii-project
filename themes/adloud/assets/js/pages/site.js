Site = {

    categoriesListId: 'select[name="site-category"]',
    bannedCatsListId: 'select[name="ban_site_category"]',
    additionalCatsListId: 'select[name="site-additional-category"]',
    addSiteUrlId: '.add_site_mirror label',
    addCatId: '#add-category',
    mirrorId: '.site_mirror',
    hiddenBannedCatsName: 'SiteForm[bannedCategories]',
    hiddenCatName: 'SiteForm[category]',
    hiddenAdditionalCatName: 'SiteForm[additionalCategory]',
    additionalCatNameId: '#additional-cat-name',
    showAllText: 'Показывать все объявления',
    nonSelText: 'Ничего не выбрано',

    init: function(data){

        if(typeof data != 'undefined'){
            var attributes = [
                'categoriesListId',
                'addSiteUrlId',
                'additionalCatsListId',
                'addCatId',
                'mirrorId',
                'hiddenBannedCatsName',
                'hiddenCatName',
                'hiddenAdditionalCatName',
                'additionalCatNameId',
                'showAllText',
                'nonSelText'
            ];

            $.each(attributes, function(index, element){
                if(typeof data[element] != 'undefined')
                    Site[element] = data[element];
            });
        }

        Site.setHandlers();
    },

    setHandlers: function(){
        Site.setCategoriesList();
        Site.setAddSite();
        Site.setAdditionalCatsList();
        Site.setBannedCatsList();
        Site.setAddCat();
        //Site.dslfkjsdlf();
    },

    setCategoriesList: function(){
        $(Site.categoriesListId).selectpickerSmart({
            style: 'btn-block select-multiple category',
            hiddenName: Site.hiddenCatName,
            nonSelectedText: Site.nonSelText
        });
    },

    setAdditionalCatsList: function(){
        $(Site.additionalCatsListId).selectpickerSmart({
            style: 'btn-block select-multiple additional-category',
            hiddenName: Site.hiddenAdditionalCatName,
            nonSelectedText: Site.nonSelText
        });
        $(Site.additionalCatsListId).parent('div').attr('style', 'display: none;');
    },

    setBannedCatsList: function(){
        $(Site.bannedCatsListId).selectpickerSmart({
            style: 'btn-block select-multiple hidden-cats',
            hiddenName: Site.hiddenBannedCatsName,
            nonSelectedText: Site.showAllText,
            checkboxes: true
        });
    },

    setAddSite: function(){
        $(Site.addSiteUrlId).on('click', function(){
            $(Site.mirrorId).toggle();
        });
    },

    setAddCat: function(){
        $(Site.addCatId).on('click', function(){
            $(Site.additionalCatsListId).parent('div').toggle(function(){
                if($(this).attr('style') == 'display: none;'){
                    $(Site.additionalCatNameId).remove();
                    $('input[name="'+Site.hiddenAdditionalCatName+'"]').val('');
                }
            });
            $(Site.addCatId+' label').toggle();
        });
    }
}