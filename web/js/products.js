$(function () {
    setMaxValueToAmountInputs();
});

$('.addToCard').click(function() {
    const id = $(this).attr('data-id');
    const amountField = $(this).closest('td').prev('td');
    const available = $(this).closest('td').prev('td').text();
    const amount = $('.amount[data-id="' + id + '"]').val();

    if (amount) {
        const postUrl = $(this).attr('href') + "&amount=" + amount;

        $.post(postUrl, function(response) {
            console.info(response);
            amountField.html(available - amount);
            $('.amount[data-id="' + id + '"]').val("");
        });
    } else {
        alert("Musisz podać ilość.");
    }

    return false;
});

$('.amount').change(function() {
   if ($(this).val() > $(this).attr('max')) {
       $(this).val($(this).attr('max'));
   }
});

function setMaxValueToAmountInputs() {
    $('.amount').each(function() {
        setMAxValueToAmountInput($(this));
    });
}

function setMAxValueToAmountInput(input) {
    const available = input.closest('td').prev('td').text();
    input.attr('max', available);
}