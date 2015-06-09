jQuery(document).ready(function($)
{
    var wbb_calendar = $('#wbb-calendar');

    wbb_calendar.on('click', '.wbb-day', function()
    {
        var day = parseInt($(this).data('day'));
        if (day > 0)
        {
            var wbb_entry = $('#wbb-entry');

            wbb_entry.find('.day-number').text(day);
            wbb_entry.data('day', day);
            showEntriesForToday();

            $('#wbb-boxes').slideUp(500, function()
            {
                $('#wbb-entry').slideDown();
                $('html, body').animate({
                    scrollTop: $('body').offset().top
                }, 500);
            });
        }
    });

    wbb_calendar.on('click', '#wbb-cancel-entry', function(e)
    {
        e.preventDefault();
        $('#wbb-entry').slideUp(500, function()
        {
            $('#wbb-boxes').slideDown();
        });
    });

    wbb_calendar.on('change', '#location-id', function(e)
    {
        var location_id = $('#wbb-entry').find('#location-id').val();
        var temp_array = location_id.split('|');
        var val = '';
        if (temp_array[1] != '0')
        {
            val = temp_array[1];
        }
        $('#wbb-calendar').find('#miles').val(val);
    });

    wbb_calendar.on('click', '#wbb-submit-entry', function(e)
    {
        e.preventDefault();

        var wbb_entry = $('#wbb-entry');

        var location_id = wbb_entry.find('#location-id').val();
        var temp_array = location_id.split('|');
        location_id = temp_array[0];
        var title = wbb_entry.find('#title').val();
        var miles = wbb_entry.find('#miles').val();

        if (location_id == '0' && title.length == 0)
        {
            alert('Please choose a location or enter a new one');
        }
        else if (miles.length == 0)
        {
            alert('Please enter the number of miles');
        }
        else
        {
            $.ajax({
                url: WbbAjax.ajax_url,
                type: 'POST',
                data: {
                    cache: false,
                    action: 'add-entry',
                    entry_nonce: WbbAjax.entry_nonce,
                    day: wbb_entry.data('day'),
                    month: wbb_entry.data('month'),
                    year: wbb_entry.data('year'),
                    location_id: location_id,
                    title: title,
                    miles: miles,
                    mode: wbb_entry.find('#mode').val()
                },
                error: function ()
                {
                    alert('There was an error. Please try again.')
                },
                success: function (json)
                {
                    if (json.success == '0')
                    {
                        alert(json.error);
                    }
                    else
                    {
                        $('#wbb-entries').append('' +
                        '<div class="wbb-single-entry" id="wbb-entry-'+json.id+'" data-day="'+json.day+'">' +
                        json.miles + ' miles to ' + json.title +
                        '</div>');

                        var location_id = wbb_entry.find('#location-id').val('0');
                        var title = wbb_entry.find('#title').val('');
                        var miles = wbb_entry.find('#miles').val('');
                    }

                    showEntriesForToday();
                }
            });
        }
    });
});

var wbb_day = 0;

function showEntriesForToday()
{
    wbb_day = jQuery('#wbb-entry').data('day');

    jQuery('.wbb-single-entry').each(function()
    {
        var day = jQuery(this).data('day');
        if (day == wbb_day)
        {
            jQuery(this).show();
        }
        else
        {
            jQuery(this).hide();
        }
    });
}