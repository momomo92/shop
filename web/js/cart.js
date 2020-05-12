$('.removeFromCart').click(function() {
    const id = $(this).attr('data-id');
    const postUrl = $(this).attr('href');

    $.post(postUrl, function() {
        const elementTotalPrice = $('#elementTotalPrice[data-id="' + id + '"]').text();
        const totalPrice = $('#totalPrice').text() - elementTotalPrice;
        $('tr[data-id="' + id + '"]').remove();
        $('#totalPrice').html(Number(totalPrice));
    });

    return false;
});