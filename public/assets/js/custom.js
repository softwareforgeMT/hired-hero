(function ($) {
    "use strict";

    $(document).ready(function () {


        function disablekey() {
            document.onkeydown = function (e) {
                return false;
            }
        }

        function enablekey() {
            document.onkeydown = function (e) {
                return true;
            }
        }


        $("button.alert-close").on('click', function () {
            $(this).parent().hide();
        });
        // **************************************  AJAX REQUESTS SECTION *****************************************

        // Status Start
        $(document).on('click', '.status', function () {
            var link = $(this).attr('data-href');
            $.get(link, function (data) {
            }).done(function (data) {
                table.ajax.reload();
                $('.alert-danger').hide();
                $('.alert-success').show();
                $('.alert-success p').html(data);
            })
        });
        // Status Ends

        // Status Start
        $(document).on('change','.droplinks',function () {
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
                table.ajax.reload();
                toastr.success("Status Updated Successfully");
        });
        // Status Ends

        // Category change Start
        $(document).on('change','.changecat',function () {
                var link = $(this).find(':selected').attr('data-value');
                window.location=link;
                
        });
        // Category change ENDS

        $('#confirm-delete').on('show.bs.modal', function(e) {
              $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
        });

        $('#confirm-delete .btn-ok').on('click', function (e) {
            $.ajax({
                type: "GET",
                url: $(this).attr('href'),
                success: function (data) {
                    $('#confirm-delete').modal('toggle');
                    table.ajax.reload();                   
                    toastr.success("Data Deleted Successfully!");
                }
            });
            return false;
        });


        // ADD / EDIT FORM SUBMIT FOR DATA TABLE
        $(document).on('submit', '#geniusformdata', function (e) {
            e.preventDefault();
            if (admin_loader == 1) {
                $('.submit-loader').show();
            }
            $('button.addProductSubmit-btn').prop('disabled', true);
            disablekey();
            $.ajax({
                method: "POST",
                url: $(this).prop('action'),
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    console.log(data);
                    if ((data.errors)) {
                        $('.alert-danger').show();
                        $('.alert-danger ul').html('');
                        for (var error in data.errors) {
                            $('.alert-danger ul').append('<li>' + data.errors[error] + '</li>');
                        }
                        if (admin_loader == 1) {
                            $('.submit-loader').hide();
                        }
                        $("#modal1 .modal-content .modal-body .alert-danger").focus();
                        $('button.addProductSubmit-btn').prop('disabled', false);
                        $('#geniusformdata input , #geniusformdata select , #geniusformdata textarea').eq(1).focus();
                    }
                    else {
                        table.ajax.reload();
                        $('.alert-success').show();
                        $('.alert-success p').html(data);
                        if (admin_loader == 1) {
                            $('.submit-loader').hide();
                        }
                        $('button.addProductSubmit-btn').prop('disabled', false);
                        $('#modal1,#modal2,#verify-modal').modal('hide');

                    }
                    enablekey();
                }

            });

        });

        // LOGIN FORM

        $("#loginform").on('submit', function (e) {
            e.preventDefault();
            $('button.submit-btn').prop('disabled', true);
            $('.alert-info').show();
            console.log($('#authdata').val());
            $('.alert-info p').html($('#authdata').val());
            $.ajax({
                method: "POST",
                url: $(this).prop('action'),
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if ((data.errors)) {
                        $('.alert-success').hide();
                        $('.alert-info').hide();
                        $('.alert-danger').show();
                        $('.alert-danger ul').html('');
                        for (var error in data.errors) {
                            $('.alert-danger p').html(data.errors[error]);
                        }
                    }
                    else {
                        $('.alert-info').hide();
                        $('.alert-danger').hide();
                        $('.alert-success').show();
                        $('.alert-success p').html('Success !');
                        window.location = data;
                    }
                    $('button.submit-btn').prop('disabled', false);
                }

            });
        });




    // **************************************  AJAX REQUESTS SECTION ENDS *****************************************

    })
})(jQuery);
