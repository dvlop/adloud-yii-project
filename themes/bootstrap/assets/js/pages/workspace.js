/**
 * Created by psyhonut on 2/8/14.
 */

function c(m) {
    console.log(m);
}

$(function () {
    $('[data-tooltip="tooltip"]').tooltip();


    $Workspace.init();
    $Workspace.loadCampaignFromUrl();

});

$Workspace = {
    init: function () {
        var $scope = $('#List');

        $scope.on('click', 'a.btn', function(e){
            e.stopPropagation();
        });

        // Загрузить контент при открытии "папки"
        $scope.on('click', '.elExpand', function () {
            var $current = $(this);
            var $icon = $current.find('i.iconExpand');
            var $contentRow = $current.nextAll('tr').first();

            $icon.toggleClass('icon-folder-open-alt').toggleClass('icon-folder-close-alt').end();
            $contentRow.toggle();

            if ($contentRow.find('.ajaxContent').text() == '') {
                $.ajax({
                    url: $current.data('content-url'),
                    dataType: 'html',
                    method: 'get',
                    success: function (data) {
                        $contentRow
                            .find('.ajaxContent').html(data)
                        ;

                        $('body').trigger('content.added');
                    },
                    complete: function(){
                        $contentRow
                            .find('.progress').hide().end()
                        ;
                    }
                });
            }
        });
        // ---

        // Переключить ширину контентного блока на весь экран
        $('body').on('click', 'a.toggleContentWidth:visible', function () {
            var $current = $('a.toggleContentWidth');

            $current.closest('li').toggleClass('active');
            $('#contentContainer').toggleClass('container').toggleClass('stretched');

            $.cookie('toggleContentWidthStatus' + window.location.toString(), $current.closest('li').hasClass('active'));

            return false;
        });
        if($.cookie('toggleContentWidthStatus' + window.location.toString()) != undefined) {
            if($.cookie('toggleContentWidthStatus' + window.location.toString())){
                $('a.toggleContentWidth:visible').trigger('click');
            }
        }
        // ---


        // Плавающее меню
        var $menu = $('#navigation');
        var $fixedMenu = $menu.clone().addClass("navbar-fixed-top").hide().appendTo($menu.parent());
        $(window).scroll(function () {
            if ($(this).scrollTop() > $menu.offset().top) {
                $fixedMenu.show();
            } else if ($(this).scrollTop() <= $menu.offset().top && $fixedMenu.is(':visible')) {
                $fixedMenu.hide();
            }
        });
        // ---

        $('.expandNextBlock').click(function(){
            var $current = $(this);

            $current
                .find('i').toggleClass('icon-expand-alt').toggleClass('icon-collapse-alt').end()
                .parent('div').next().stop().slideToggle()
            ;

            return false;
        });

        $('.multiselect').multiselect({
            maxHeight: '200',
            enableFiltering: true,
            includeSelectAllOption: true,
            selectAllText: 'Выбрать все',
            filterPlaceholder: 'Поиск',
            nonSelectedText: 'Не выбрано',
            nSelectedText: 'выбрано',
            enableCaseInsensitiveFiltering: true
        });

        $Workspace.campaign.init($scope);
        $Workspace.site.init($scope);
    },

    loadCampaignFromUrl: function () {
        var pattern = /#.+/gi;
        if (window.location.hash.match(pattern) != null) {
            var contentID = window.location.hash.match(pattern)[0].replace('#', '').replace('/', '');
            $('#' + contentID).trigger('click');
            $.scrollTo($('#' + contentID).parent().offset().top - $('#navigation').height() - 5, 800);
        }
    },

    campaign: {
        init: function($scope) {
            $scope.on('click', '.btnCampaignDelete', function () {
                $('#confirmCampaignDelete').attr('href', $(this).attr('data-url'));
            });

            $scope.on('click', '.btnCampaignPublish', function () {
                $('#confirmCampaignPublish').attr('href', $(this).attr('data-url'));
            });

            $scope.on('click', '.btnCampaignUnPublish', function () {
                $('#confirmCampaignUnPublish').attr('href', $(this).attr('data-url'));
            });

            $Workspace.campaign.ads.init($scope);
        },

        ads: {
//            _popoverIsLoading: false,

            init: function($scope) {
                $scope.on('click', '.btnAdsDelete', function () {
                    $('#confirmAdsDelete').attr('href', $(this).attr('data-url'));
                });

                $scope.on('click', '.btnAdsPublish', function () {
                    $('#confirmAdsPublish').attr('href', $(this).attr('data-url'));
                });

                $scope.on('click', '.btnAdsUnPublish', function () {
                    $('#confirmAdsUnPublish').attr('href', $(this).attr('data-url'));
                });

                $('body').on('content.added', function(){
                    // Тултип
                    $scope.find('[data-tooltip="tooltip"]').tooltip();

                    // Поповер
                    $scope.find('[data-poload]').bind('hover',function() {
                        var e=$(this);
                        e.unbind('hover');


                        $.get(e.data('poload'))
                            .done(function(d) {
                                e
                                    .popover({
                                        content: d,
                                        trigger: 'hover',
                                        html: true,
                                        placement: 'top',
                                        animation: false,
                                        delay: 5
                                    })
//                                    .popover('show')
                                ;
                            })
                        ;
                    });
                    // ---
                });
            }
        }
    },

    site: {
        init: function($scope) {
            $scope.on('click', '.btnSiteDelete', function () {
                $('#confirmSiteDelete').attr('href', $(this).attr('data-url'));
            });

            $Workspace.site.block.init('#block-editor');
            $Workspace.site.block.initList($scope);
        },

        block: {
            init: function(scope) {
                var $scope = $(scope);
                var iframe = $("#preview").find('iframe');

                $scope.on('click', '.teaser-element', function(e) {
                    var $this = $(this);
                    var type = $this.data('type');

                    $('#block-editor-tabs').find('a[href="#' + type + '"]').tab('show');

                    e.preventDefault();
                    e.stopPropagation();
                });

                $scope.on('click', '#teaser-preview-tab', function(){
                    $('#block-editor-tabs').find('a[href="#home"]').tab('show');
                });

                $scope.on('click', '#block-preview-tab', function(){
                    $('#block-editor-tabs').find('a[href="#block"]').tab('show');
                });

                $scope.on('click', '.teaser-preview-tab', function(){
                    $('#block-preview-tabs').find('a[href="#teaser-preview"]').tab('show');
                });

                $scope.on('click', '.block-preview-tab', function(){
                    $('#block-preview-tabs').find('a[href="#block-preview"]').tab('show');
                });
                $('#BlockForm_razmerRamki').on('slide', function(ev){
                    $('.twm-block').css('border', ev.value + 'px solid');
                });
                $('#BlockForm_razmerShriftaOpisania').on('change', function(ev){
                    var $current = $(this);
                    $('.twm-block').find('a').css('font-size', $current.val() + 'px');
                });

                /*
                $('#BlockForm_adsNumberRows').on('change', function(ev){
                    var $current = $(this);
                    var $blockOriginal = $('#twm-block-original');
                    var adsCount = parseInt($current.val());
                    $('.twm-cont').html('');
                    for(var i = 1; i <= adsCount; i++){
                        $('.twm-cont').append($blockOriginal.clone().attr('id', '').css('width', (100 - adsCount) / adsCount + '%'));
                    }
                });

                var $blockOriginal = $('#twm-block-original');
                var adsCount = $('#BlockForm_adsNumber').val();
                $('.twm-cont').html('');
                for(var i = 1; i <= adsCount; i++){
                    $('.twm-cont').append($blockOriginal.clone().attr('id', '').css('width', (100 - adsCount) / adsCount + '%'));
                }
                */

                $('.color-picker').colorpicker({
                    format: 'hex',
                    component: 'input-group-addon'
                });
                $('#BlockForm_cvetRamki').on('changeColor', function(ev){
                    $('.twm-block').css('border-color', ev.color.toHex());
                });
                $('#BlockForm_cvetFona').colorpicker({format: 'hex'}).on('changeColor', function(ev){
                    $('.twm-block').css('background-color', ev.color.toHex());
                });
                $('#BlockForm_cvetShriftaOpisania').colorpicker({format: 'hex'}).on('changeColor', function(ev){
                    $('.twm-block').find('a').css('color', ev.color.toHex());
                });
            },

            initList: function($scope) {
                $scope.on('click', '.btnBlockDelete', function () {
                    $('#confirmBlockDelete').attr('href', $(this).attr('data-url'));
                });

            }
        }
    }
};