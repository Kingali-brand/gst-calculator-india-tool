jQuery(document).ready(function($) {
    $('#gst-calculate-btn').on('click', function() {
        var originalPrice = parseFloat($('#gst-original-price').val());
        var gstRate = parseFloat($('#gst-rate').val());
        
        if (isNaN(originalPrice) || originalPrice <= 0) {
            alert('Please enter a valid price');
            return;
        }

        $.ajax({
            url: gstCalculator.ajaxurl,
            type: 'POST',
            data: {
                action: 'calculate_gst',
                original_price: originalPrice,
                gst_rate: gstRate
            },
            success: function(response) {
                if (response.success) {
                    $('#gst-amount').text('₹' + response.gst_amount);
                    $('#gst-total-price').text('₹' + response.total_price);
                    $('#cgst-amount').text('₹' + response.cgst);
                    $('#sgst-amount').text('₹' + response.sgst);
                    $('.cgst-percent').text(response.cgst_percent);
                    $('.sgst-percent').text(response.sgst_percent);
                    $('#gst-results').fadeIn();
                }
            }
        });
    });
});
