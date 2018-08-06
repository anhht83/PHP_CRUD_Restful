$(function() {
    var base_api = '../server/api';
    var $itemTable = $('#items');
    var $itemForm = $('#item-form');
    // show list item
    var table = $itemTable.DataTable({
        "order": [],
        "ajax": base_api + '/stock',
        "columns": [
            {"data": "product_name"},
            {"data": "createdAt"},
            {"data": "quantity"},
            {"data": "price"},
            {"data": "total"},
        ],
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api();

            // Total over all pages
            total = api
                .column( 4 )
                .data()
                .reduce( function (a, b) {
                    return parseFloat(a) + parseFloat(b);
                }, 0 );

            // Total over this page
            pageTotal = api
                .column( 4, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return parseFloat(a) + parseFloat(b);
                }, 0 );

            // Update footer
            $( api.column( 4 ).footer() ).html(
                '$'+pageTotal
            );
        }
    });

    // submit form when is validated
    var saveItem = function(item) {
        var action = item.id ? 'edit' : 'create';
        $itemForm.addClass('whirl');
        $.ajax({
            url: base_api + '/stock',
            method: 'POST',
            dataType: 'json',
            data: item,
            success: function(data) {
                $itemForm.find('.alert-message').removeClass('alert-error').addClass('alert-success').html('Successful !!!').show();
                $itemForm.removeClass('whirl');
                table.row.add(data).draw();
            },
            error: function() {
                $itemForm.find('.alert-message').removeClass('alert-success').addClass('alert-error').html('Error !!!').show();
                $itemForm.removeClass('whirl');
            }
        });
    }

    // validation item form
    var form = $itemForm[0];
    form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
        } else {
            saveItem({
                product_name: $(form).find('[name="product_name"]').val(),
                quantity: $(form).find('[name="quantity"]').val(),
                price: $(form).find('[name="price"]').val()
            });
        }
        form.classList.add('was-validated');
    }, false);

});