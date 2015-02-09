/**
 * Created by t0m on 24.03.14.
 */

$(function(){
    $('[data-tooltip="tooltip"]').tooltip();

    $Admin.init();
});

var $Admin = {
    init: function(){
        var $scope = $('#List');

        $scope.on('click', '.btnModerateAccept', function () {
            var shock = $(this).parent().parent().find('.shock-checkbox').attr("checked") ? true : false,
                adult = $(this).parent().parent().find('.adult-checkbox').attr("checked") ? true: false;
            $('#confirmModerateButton').attr('href', $(this).attr('data-url') + '&shock=' + shock + '&adult=' + adult);
        });

        $scope.on('click', '.btnModerateDecline', function () {
            $('#declineModerateButton').attr('href', $(this).attr('data-url'));
        });

    }
}