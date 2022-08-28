jQuery(function($){
    $('.upload_image_button').click(function( event ){

        event.preventDefault();
        const button = $(this);

        const customUploader = wp.media({
            title: 'Выберите изображение',
            library : {
                type : 'image'
            },
            button: {
                text: 'Выбрать изображение'
            },
            multiple: false
        });

        customUploader.on('select', function() {

            const image = customUploader.state().get('selection').first().toJSON();

            button.parent().prev().attr( 'src', image.url );
            button.prev().val( image.id );

        });

        customUploader.open();
    });

    $('.remove_image_button').click(function(event){
        event.preventDefault();
        if ( true == confirm( "Подтвердите удаление" ) ) {
            const src = $(this).parent().prev().data('src');
            $(this).parent().prev().attr('src', src);
            $(this).prev().prev().val('');
        }
    });

    $('.clear_fields').click(function(event){
        event.preventDefault();
        $('input[name="date_product"]').val('')
        const src = $('img#img_product').data('src');
        $('img#img_product').attr('src', src)
        $('input#image_product').val('')
        $('select#type_product').val('-')
    });

    $('.update_fields').click(function(event){
        event.preventDefault();
        $('input#publish').trigger('click')
    });
});
