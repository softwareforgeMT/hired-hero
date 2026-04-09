$(document).ready(function () {
    
    //User Dashboard make links active
    $("#usersidebarlist li").each(function() {
      var currentUrl = window.location.href.split(/[?#]/)[0];
        $(this).find("a.nav--link,a.nav-link").each(function() {
            // Check if the link's href attribute matches the current URL
            if ($(this).attr("href") == currentUrl) {
                // Add the "active" class to the link's parent list item
                $(this).addClass("active");

                // If the link is inside a collapsed menu, open the menu
                $(this).parents(".menu-dropdown").addClass("show");
            }
        });
    
    });

    // Resend Verification
    $(document).on('click', '.resendcodelk', function () {
        var link = $(this).data('href');
        var $alert = $('#verificationModal .alert');
        $alert.hide().filter('.alert-info').show().find('p').html('Sending...');
        $.get(link)
        .done(function (data) {
            if (data.success) {
                toastr.success(data.success);
            } else if (data.error) {
                toastr.error(data.error);
            }
            $alert.filter('.alert-info').hide();
        });
    });

    // Send Verification link
    $("#verify_account,#orderStatusForm,#orderRatingForm,.ajaxForm").submit(function(e) {
        e.preventDefault();
        var $form = $(this);
        var $alert = $form.find('.alert');
        var $btn = $form.find('button.submit-btn');
        var formData = new FormData(this);
        $btn.prop('disabled', true);
        $alert.hide().filter('.alert-info').show().find('p').html("Processing...");

        $.ajax({
            method: "POST",
            url: $form.prop('action'),
            data: formData,
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                if (data.success) {
                    window.location.href = data.route;
                     // Check if data.msg is present and not an empty string before showing the toast
                    if (data.msg) {
                        toastr.success(data.msg);
                    }
                } else if (data.error) {
                     $alert.hide().filter('.alert-danger').show().find('p').html(data.error);
                }else if (data.errors) {
                    var errorHtml = '';
                    for(var error in data.errors){
                      errorHtml += '<li>' + data.errors[error] + '</li>';
                    }
                    $alert.hide().filter('.alert-danger').show().find('p').html(errorHtml);
                }
                scrollToAlert($alert);
                $btn.prop('disabled', false);
            }
        });
    });

    function scrollToAlert($alert) {
        $('html, body').animate({
            scrollTop: $alert.offset().top - 90 // Adjust the offset as needed
        }, 500);
    }

    //Change Product/offer status user panel
    // Status Start
    $(document).on('change','.offerstatus',function () {
            var link = $(this).val();
            var data = $(this).find(':selected').attr('data-val');
            if(data == 0)
            {
              $(this).removeClass("drop-success").addClass("drop-danger");
            }
            else{
              $(this).removeClass("drop-danger").addClass("drop-success");
            }
            $.get(link);
            // table.ajax.reload();
            toastr.success("Status Updated Successfully");
    });
    // Status Ends
    $(document).on('click','a.changeprice',function (event) {
            event.preventDefault();
            const csrfToken = $('meta[name="csrf-token"]').attr('content');
            const newPrice = $('input.product-price-input').val();
            // Send an AJAX request to update the product price
            $.ajax({
                url: $(this).data('route'),
                type: 'post',
                data: {
                    price: newPrice
                },
                headers: {
                'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                   toastr.success(response.message);
                },
                error: function(xhr, status, error) {
                    console.error('There was a problem updating the price:', error);
                }
            });
     });
    //Change Product/offer status user panel ends
    
    //Top navbar game search
    $('.gameSearchInput').on('keyup', function() {
        var searchText = $(this).val().toLowerCase();
        var searchList = $(this).attr('data-search-in');

        $('#' + searchList + ' a').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(searchText) > -1);
        });
    });
    //Top navbar game search ends
    var previewableImageInput = document.querySelector("#previewable-image-input");
    if (previewableImageInput) {
         document.querySelector("#previewable-image-input").addEventListener("change", function () {
        var preview = document.querySelector("#preview-img");
        var file = document.querySelector("#previewable-image-input").files[0];
        var reader = new FileReader();
        reader.addEventListener("load", function () {
          preview.src = reader.result;
        }, false);

        if (file) {
          reader.readAsDataURL(file);
        }
    });
    }

    //Notifications
    $(document).on('click','#page-header-notifications-dropdown', function() {
        var url=$(this).attr('data-href');
        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                // var count = response.count;
                $('#notificationItemsTabContent').addClass('py-2 ps-2').html(response.view);
                console.log(response.count);
                $('.notifications-count').text(response.count);
            },
            error: function(response) {
                console.log(response);
            }
        });
    });


});
