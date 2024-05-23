import './app'

$(document).on('change', '#counter', function(e) {
    $(this).parent().submit()
})
