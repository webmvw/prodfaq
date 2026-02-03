jQuery(function ($) {

    $('.prodfaq-question').on('click', function () {
        const item = $(this).closest('.prodfaq-item');
        const answer = item.find('.prodfaq-answer');
        const icon = $(this).find('.prodfaq-icon');

        if (answer.is(':visible')) {
            answer.slideUp(200);
            icon.text('+');
            $(this).attr('aria-expanded', 'false');
            item.removeClass('active');
        } else {
            answer.slideDown(200);
            icon.text('–');
            $(this).attr('aria-expanded', 'true');
            item.addClass('active');
        }
    });

});
