jQuery(function($){
    let index = prodfaqData.faqs;

    $('#prodfaq-add').on('click', function(){
        $('#prodfaq-wrapper').append(`
            <div class="prodfaq-row">
                <input type="text" name="prodfaq[${index}][question]" placeholder="Question" />
                <textarea name="prodfaq[${index}][answer]" placeholder="Answer"></textarea>
                <button class="button prodfaq-remove">Remove</button>
            </div>
        `);
        index++;
    });

    $(document).on('click', '.prodfaq-remove', function(e){
        e.preventDefault();
        $(this).closest('.prodfaq-row').remove();
    });
});