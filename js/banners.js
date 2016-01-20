jQuery(document).ready(function($)
{
    $('body')
        .append('<div class="wbb-banner wbb-banner-vertical wbb-banner-left"></div>')
        .append('<div class="wbb-banner wbb-banner-vertical  wbb-banner-right"><input type="text"></div>');

    if (wbb.wp_user_id == 0)
    {
        /*
        $('#header_main').append('' +
        '<div class="wbb-banner wbb-banner-horizontal">' +
        '<p><span>Enter your home address</span> to see if you\'re eligible for a free packet and gift!</p>' +
        '<input type="text">' +
        '</div>');
        */
    }

    moveBanners();
    $(window).resize(function()
    {
        moveBanners();
    });

    $('.wbb-banner input').bind('keypress', function(e)
    {
        var code = e.keyCode || e.which;
        if (code == 13)
        {
            var address = encodeURIComponent($(this).val()).replace(/%20/g, '+');
            window.location = '/?p='+wbb.shortcode_page_id+'&wbb_action=address&wbb_data='+address;
        }
    });
});

function moveBanners()
{
    var window_size = jQuery(window).width();

    if (window_size >= 1400)
    {
        var outer_margin = Math.round((window_size - 1400) / 2);
        jQuery('.wbb-banner-left').css('left', outer_margin.toString()+'px');
        jQuery('.wbb-banner-right').css('right', outer_margin.toString()+'px');
    }
}