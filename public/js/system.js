$(document).ready(function() {
    $('[data-toggle=captcha-refresh]').on('click', function(e) {
        e.preventDefault();

        var url = $(this).data('url') || $(this).prop('href');

        if (url) {
            $(this).closest('.captcha-row').find('img:first').attr('src', url + '?' + (new Date()).getTime());
        }

        return false;
    });
});