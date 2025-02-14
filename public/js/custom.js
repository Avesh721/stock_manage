jQuery('#product_data').submit(function (e) {
    e.preventDefault();
    jQuery('.field_error').html('');

    var token = jQuery('meta[name="csrf-token"]').attr('content');

    jQuery.ajax({
        url: '/product_data',
        data: jQuery("#product_data").serialize(),
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': token
        },

        success: function (data) {
            if (data.status === "error") {
                jQuery.each(data.error, function (key, value) {
                    jQuery('#' + key + '_error').html(value[0]);
                });
            } else if (data.status === "success") {


                jQuery('#product_data')[0].reset();

                // Hide the form
                jQuery('#add-form').addClass('hidden');

                // Show SweetAlert success message
                Swal.fire({
                    icon: 'success',
                    title: data.msg, // Dynamic message from backend
                    showConfirmButton: false,
                    timer: 2000 // Auto close after 2 seconds
                }).then(() => {
                    // Close the modal after the alert
                    closeModal();
                    location.reload();

                });
            }
        }
    });
});

jQuery('#edit_product_data').submit(function (e) {
    e.preventDefault();
    jQuery('.field_error').html('');

    var formData = $(this).serialize();

    $.ajax({
        url: '/edit_product_data',
        type: 'POST',
        data: formData,
        dataType: 'json',

        success: function (data) {
            if (data.status === "error") {
                jQuery.each(data.error, function (key, value) {
                    jQuery('#' + key + '_error').html(value[0]);
                });
            } else if (data.status === "success") {

                // Reset the form
                jQuery('#edit_product_data')[0].reset();

                // Hide the modal
                jQuery('#editProductModal').addClass('hidden');

                // Show SweetAlert success message
                Swal.fire({
                    icon: 'success',
                    title: data.msg,
                    showConfirmButton: false,
                    timer: 2000
                }).then(() => {
                    location.reload();
                });
            }
        },

        error: function () {
            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: 'Something went wrong. Please try again.',
            });
        }
    });
});

function deleteProduct(id) {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/delete_products/" + id,
                type: "DELETE",
                data: {
                    _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                success: function(data) {

                    if(data.status=='success'){
                    Swal.fire("Deleted!", "Product has been deleted.", "success");
                    location.reload(); // Reload to reflect changes

                }
                },
                error: function() {
                    Swal.fire("Error!", "There was an issue deleting the product.", "error");
                }
            });
        }
    });
}





