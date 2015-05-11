jQuery(document).ready(function($)
{
    $('body')
        .append('<div class="wbb-banner wbb-banner-left"></div>')
        .append('<div class="wbb-banner wbb-banner-right"><input type="text"></div>');

    moveBanners();
    $(window).resize(function()
    {
        moveBanners();
    });
});

function moveBanners()
{
    var window_size = jQuery(window).width();

    if (window_size >= 1500)
    {
        var outer_margin = Math.round((window_size - 1500) / 2);
        jQuery('.wbb-banner-left').css('left', outer_margin.toString()+'px');
        jQuery('.wbb-banner-right').css('right', outer_margin.toString()+'px');
    }
}