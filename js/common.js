jQuery(document).ready(function($)
{
    var body = $('body');
    body.append('<div id="wbb-modal-background"></div>');
    body.append('<div id="wbb-modal-popup"></div>');

    $('#wbb-modal-popup').on('click', '.close span', function()
    {
        jQuery('#wbb-modal-background').hide();
        jQuery('#wbb-modal-popup').hide();
    });

    if (typeof wbb_popup_width !== 'undefined')
    {
        wbbPopUp(wbb_popup_width, wbb_popup_height, wbb_popup_html);
    }

    $('#wbb-newsletter-form').on('click', '.submit', function(e)
    {
        e.preventDefault();
        var email = $('#wbb-newsletter-form').find('input').val();
        if (email.length == 0)
        {
            alert('Please enter your email address');
        }
        else
        {
            $.ajax({
                url: WbbAjax.ajax_url,
                type: 'POST',
                data: {
                    cache: false,
                    action: 'subscribe',
                    entry_nonce: WbbAjax.entry_nonce,
                    email: email
                },
                error: function ()
                {
                    alert('There was an error. Please try again.')
                },
                success: function ()
                {
                    wbbPopUp(400, 200, '<img src="' + wbb.plugin_dir + '/images/thanks.png"><br><br>You may be contacted by the Walk Bike Bus staff.');
                }
            });
        }
    });
});

function wbbPopUp(w, h, html)
{
    var width = jQuery(document).width();
    var height = jQuery(document).height();

    jQuery('#wbb-modal-background')
        .css({
            height: height,
            width: width
        })
        .show();

    width = jQuery(window).width();
    height = jQuery(window).height();
    var top = jQuery(window).scrollTop();

    if (width < w)
    {
        w = width;
    }

    if (height < h)
    {
        h = height;
    }

    jQuery('#wbb-modal-popup')
        .css({
            width: w,
            height: h,
            top: top+(height/2)-(h/2),
            left: (width/2)-(w/2)

        })
        .html(html)
        .prepend('<p class="close"><span>&times;</span></p>')
        .show();
}