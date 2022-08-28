jQuery(document).ready(function($) {
    $("#create_product").submit(function(e) {
        e.preventDefault();
        var form = $(this)
        console.log('test');
        var data = new FormData($(this)[0]);
        $.ajax({
            type: "POST",
            url: "/wp-content/themes/storefront/action-create-product.php",
            data: data,
            processData: false,
            contentType: false,
            success: function(msg) {
                if(msg == 'success') {
                    form[0].reset()
                    $('#note').html('');
                    Swal.fire('Продукт успешно создан!', '','success')
                } else { $('#note').html(msg); }
            }
        });
        return false;
    });
});
