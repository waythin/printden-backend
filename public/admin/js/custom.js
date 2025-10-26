$(document).ready(function () {
    
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 1500,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })
    // Function to show the toast notification
    window.showToast = function(icon, title) {
        Toast.fire({
            icon: icon,
            title: title
        });
    };
    // use tooltip
    $('[data-toggle="tooltip"]').tooltip();

    function initializeTooltips() {
        // Dispose of previously initialized tooltips
        $('[data-toggle="tooltip"]').tooltip('dispose');

        // Re-initialize tooltips
        $('.inner-table [data-toggle="tooltip"]').tooltip({
            boundary: 'window',
            placement: 'top'
        });
        $('[data-toggle="tooltip"]').tooltip();
    }

    //Upload button 
    $(document).on("change", ".actual-btn", function () {
        //console.log($(this).siblings('.file-chosen'));
        $(this).siblings('.file-chosen').text(this.files[0].name);
    });
    //Check Admin Password is correct or not
    $("#current_password").blur(function () {
        var current_password = $("#current_password").val();
        // alert(current_password);
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            type: "post",
            url: "/admin/check-admin-password",
            data: { current_password: current_password },
            success: function (response) {
                //console.log(response.data);
                if (response.data == false) {
                    $("#check_password").html(
                        "<font color='red'>Current Password is Incorrect!</font>"
                    );
                } else if (response.data == true) {
                    $("#check_password").html(
                        "<font color='green'>Current Password is Correct!</font>"
                    );
                }
            },
            error: function (e) {
                console.log(e);
                alert("error");
            },
        });
    });
    
    //Toggle button click buyer/seller toggle
    $(document).on("click", ".toggle-button", function () {
        $(".toggle-button").removeClass("active");
        $(this).addClass("active");
        $.ajax({
            url: $(this).attr("data-url"),
            method: 'GET',
            success: function (response) {

                if (response.success) {
                    Toast.fire({
                        icon: 'success',
                        title: response.message
                    })
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                }
                else {
                    Toast.fire({
                        icon: 'error',
                        title: response.message
                    })
                }
            },
            error: function (error) {
                // Handle errors, if any
                console.error(error);
                Toast.fire({
                    icon: 'error',
                    title: "Something went wrong"
                })
            }
        });
    });

    //User dropdown button click 
    $(document).on("click", "#userDropdown", function () {
        $("#userDropdown .img-profile").toggleClass('rotate');
    });
    //Product list view button toggle 

    $(document).on("click", ".accordion-toggle", function () {
        $(this).toggleClass('active')
        if ($(this).hasClass('active')) {
            $(this).find(".toggle-imgae-change").attr("src", "/admin/img/icons/password-show.svg");
        } else {
            $(this).find(".toggle-imgae-change").attr("src", "/admin/img/icons/password.svg");
        }
    });

    $(document).on("click", ".confirmReturn", function () {
        Swal.fire({
            customClass: {
                icon: 'mt-4'
            },
            title: 'Are you sure?',
            text: msg,
            color: textColor,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, confirm msg!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                //after clicking yes this
            }
        })
    });


    //ALL form submit
    $(document).on("click", "#saveBtn", function (e) {
        e.preventDefault();
        var form = $('.form');
        var url = form.attr('action');
        var modal = $('.form_modal');
        $(document).find("span.text-danger").remove();
        var admin_id = form.find('#admin_id').val();
        var merchant_id = form.find('#merchant_id').val();
        //var form_data = form.serialize() + '&updated_by=' + admin_id;
        // console.log(form);
        // console.log(url);
        // console.log(modal);
        var suggestValue = $(this).data('suggest');
          // Add suggestValue to FormData if it is defined
        if (suggestValue !== undefined) {
            form_data.append("suggest", suggestValue);
        }


        var form_data = new FormData(form[0]);
        form_data.append("updated_by", admin_id); // add field to formdata
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: url,
            data: form_data,
            dataType: "JSON",
            processData: false,
            contentType: false,
            success: function (response) {
                //console.log(response);
                if (response.success_message) {
                   
                    modal.hide();
                    Toast.fire({
                        icon: 'success',
                        title: response.success_message
                    })
                    setTimeout(() => {
                        location.reload();
                    }, 1500);

                }
                // else if (response.type == "certificate") {
                //     modal.hide();
                //     Toast.fire({
                //         icon: 'success',
                //         title: response.message
                //     });
                
                //     setTimeout(() => {
                //         // Make an AJAX request to get the updated content
                //         $.ajax({
                //             url: '/admin/certification/list/' + merchant_id, // Replace with your URL to fetch updated content
                //             method: 'GET',
                //             success: function(data) {
                //                 // Replace the content of the div with the updated content
                //                 $('#add_certificate_modal').html(data);
                //             },
                //             error: function(xhr, status, error) {
                //                 console.error('Error fetching updated content:', error);
                //             }
                //         });
                //     }, 1500);
                // }
                else if (response.error_message) {
                    console.log(response);
                    Swal.fire({
                        customClass: {
                            icon: 'mt-4'
                        },
                        position: 'center',
                        icon: 'error',
                        title: response.error_message,
                        showConfirmButton: true,
                        // timer: 2000
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                }
                else if (response.validation_error) {
                    console.log(response);
                    $.each(response.validation_error, function (field_name, error) {
                        $(document).find('[name=' + field_name + ']').after('<span class="text-strong text-danger">' + error + '</span>')
                    })

                }
            },
            error: function (xhr) {
                console.log(xhr);
                Toast.fire({
                    icon: 'error',
                    title: "Something went wrong"
                })
            }
        });
    });

    // Find clicked btn (add/ edit)
    $(document).on("click", ".click-check", function () {

        var module = $(this).attr("module");
        if ($(this).hasClass("add-btn")) {
            $('.form_modal').find('.modal-title').html("Add " + module);
            $('.form_modal').find('form').trigger('reset');
            $('.form_modal').find('.select-box .selected').html('Select Options');
           // console.log( $('.form_modal').find('.select-box .selected'));
        } else if ($(this).hasClass("show-btn")) {
            $('.form_modal').find('.modal-title').html("Edit " + module);
            $('.form_modal').find('.select-box .selected').html('Select Options');
        }
    });

    //ALL form Edit
    $(document).on("click", ".show-btn", function (e) {
        e.preventDefault();
        var module = $(this).attr("module");
        var id = $(this).attr("data-id");
        var url = $(this).attr("data-url");
        var modal = $('.form_modal');
        console.log(module);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'get',
            url: url,
            success: function (response) {
                
                console.log(response.data); 

                modal.find('.form').attr('action', url);
                console.log(url);
                if (module === 'gallery') {
                    // Remove the old input
                    $('#imageurl').replaceWith(
                        '<input type="file" class="form-control" id="imageurl" name="image_url">'
                    );
                }
                $(".option input[type='checkbox']").prop('checked', false);
                $.each(response.data, function (field_name, value) {
                    //console.log(field_name + ':'+value);
                    var input_field = modal.find('[name=' + field_name + ']');
                    

                    if (input_field.attr('name') == 'details') {
                        CKEDITOR.instances['details'].setData(value)
                    }
                    if (input_field.attr('type') == 'file') {
                        if (input_field.attr('name') == 'image_url') {
                            input_field.closest(".form-group").find(".append-file").html('<img class="img-thumbnail" style="width: 7rem;" src="/' + value + '" alt="Image">');
                        }
                        //console.log(input_field.next());
                    }
                    else {
                        modal.find('[name=' + field_name + ']').val(value);
                    }

                })
            },
            error: function (xhr) {
                console.log(xhr);
                Toast.fire({
                    icon: 'error',
                    title: "Something went wrong"
                })
            }
        });
    });

    //Confirm Change Status (sweet Alert Library)
    $(document).on("change", ".updateStatus", function (e) {
        e.preventDefault();
        var module = $(this).attr('module');
        var data_id = $(this).attr('data_id');
        var data_admin_id = $(this).attr('data_admin_id');
        var status = $(this).find(":selected").val();
        url = "/admin/update-" + module + "-status";
        //console.log(module + "  " + status + "  " + data_id);
        Swal.fire({
            customClass: {
                icon: 'mt-4'
            },
            title: 'Are you sure?',
            text: "You want to update?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'post',
                    url: url,
                    data: { status: status, data_id: data_id, updated_by: data_admin_id },
                    success: function (response) {
                        Toast.fire({
                            icon: 'success',
                            title: response.success_message
                        })
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    },
                    // error: function (jqXHR) {
                    //     console.log(jqXHR);
                    //     Toast.fire({
                    //         icon: 'error',
                    //         title: "Something went wrong"
                    //     })
                    // }
                    error: function (jqXHR) {
                        console.log(jqXHR);
            
                        // Display error message
                        let errorMessage = "Something went wrong"; // Default error message
                        if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                            errorMessage = jqXHR.responseJSON.message; // Laravel-specific error message
                        } else if (jqXHR.statusText) {
                            errorMessage = jqXHR.statusText; // General HTTP error message
                        }
            
                        Toast.fire({
                            icon: 'error',
                            title: errorMessage
                        });
                    }

                });
            }
        })
    });

    //Confirm Deletation (sweet Alert Library)
    $(document).on("click", ".confirmDelete", function (e) {

        // console.log("hiiiiiiss");
        e.preventDefault();
        var module = $(this).attr('module');
        var moduleId = $(this).attr('moduleid');
        url = "/admin/delete-" + module + "/" + moduleId;
        //console.log(url);
        Swal.fire({
            customClass: {
                icon: 'mt-4'
            },
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'get',
                    url: url,
                    success: function (response) {
                        Toast.fire({
                            icon: 'success',
                            title: response.success_message
                        })
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    },
                    error: function (xhr) {
                        console.log(xhr);
                        Toast.fire({
                            icon: 'error',
                            title: "Something went wrong"
                        })
                    }
                });
            }
        })
    });


    // clear product and child id in the variable 
    $('#rfq_modal_g').on('hidden.bs.modal', function () {
    $('#rfq_modal_g').hide();
    Product_child_ids.length = 0;
    Product_ids.length = 0;
    // $('#rfq_type_public').prop('checked', true);
    });



   
    $('.dropdown-button').on('click', function(event) {
        event.stopPropagation(); // Prevent the event from bubbling up to the document
        var $dropdownMenu = $(this).next('.dropdown-menu');
        $dropdownMenu.toggle(); // Toggle visibility of the dropdown menu
    });

    // Close dropdown when clicking outside
    $(document).on('click', function(event) {
        // if (!$(event.target).closest('.dropdown').length) {
        //     $('.dropdown-menu').hide(); // Hide all dropdown menus
        // }
            var $dropdown = $(event.target).closest('.dropdown');
            if (!$dropdown.is('#topbarDropdownMenu')) {
                $('.dropdown-menu').addClass('hide'); // Add the 'hide' class
            }
    });

    // rfq type radio  [public or private] --- first old
    // $('input[name="rfq_type"]').on('change', function() {
    //     if ($(this).val() === 'private') {
    //         $.ajax({
    //             headers: {
    //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //             },
    //             type: 'GET',
    //             url: '/admin/fav-list',
    //             data: {
    //                 product_ids: Product_ids,
    //                 product_child_ids: Product_child_ids,
    //                 // type: 'all',
    //             },

    //             success: function(response) {
    //                 $('input[name="merchant_type_radio"][value="fav"]').prop('checked', true);
    //                 $('.rfq_merchant_list').html(response.html);
    //                 $('.rfq_filter_page').html(response.html2);
    //                 $('.merchant_list_radio').show();
    //             },
    //             error: function(xhr) {
    //                 console.error('Error loading private RFQ content:', xhr);
    //             }
    //         });
    //     } else {
    //         $('.rfq_merchant_list').empty(); // Clear the content if 'public' is selected
    //         $('.merchant_list_radio').hide();
    //         $('.rfq_filter_page').empty();

            
    //     }
    // });


    // new
    $('input[name="rfq_type"]').on('change', function() {
        if ($(this).val() === 'private') {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'GET',
                url: '/admin/fav-list',
                data: {
                    product_ids: Product_ids,
                    product_child_ids: Product_child_ids,
                    type: 'all',
                },

                success: function(response) {
                    // $('input[name="merchant_type_radio"][value="fav"]').prop('checked', true);
                    $('.rfq_merchant_list').html(response.html);
                    $('.rfq_filter_page').html(response.html2);
                    $('.advance_filter').show();
                },
                error: function(xhr) {
                    console.error('Error loading private RFQ content:', xhr);
                }
            });
        } else {
            $('.rfq_merchant_list').empty(); // Clear the content if 'public' is selected
            $('.advance_filter').hide();
            $('.rfq_filter_page').empty();

            
        }
    });
// ------------------------------------------


        function getActiveButtonId() {
            return $('.tog-button.active').attr('id');
        }

        // Example usage
        var activeButtonVal = getActiveButtonId();

    function sendSelectedData() {
        let certificateIds = [];
        let nominationIds = [];
    
        // Collect selected certificate IDs
        $('.certificate-checkbox:checked').each(function () {
            certificateIds.push($(this).val());
        });
    
        // Collect selected nomination IDs
        $('.nomination-checkbox:checked').each(function () {
            nominationIds.push($(this).val());
        });
    
        // Send AJAX request
        $.ajax({
            url: '/admin/fav-list',
            method: 'GET',
            data: {
                certificate_ids: certificateIds,
                nomination_ids: nominationIds,
                product_ids: Product_ids, // Ensure these variables are defined
                product_child_ids: Product_child_ids, // Ensure these variables are defined
                type: activeButtonVal
            },
            success: function (response) {
                $('.rfq_merchant_list').html(response.html);
            },
            error: function (xhr) {
                console.error('Error sending data:', xhr);
            }
        });
    }
    
    // // Trigger AJAX request when checkboxes are changed
    // $(document).on('change', '.certificate-checkbox, .nomination-checkbox', function () {
    //     sendSelectedData();
    //     console.log("Checkbox changed and data sent.");
    // });

        $(document).on('click', '.nomination-checkbox, .certificate-checkbox', function () {
        // Your function logic here
        var nominationId = $(this).val();
        var isChecked = $(this).is(':checked');

        sendSelectedData();
        
       
    });
    
   

    // search
    $(document).on('keyup', '#search', function() {
        console.log('hioiiiiiiiiii');
        var searchTerm = $(this).val();
        
        $.ajax({
            url: '/admin/fav-list', // URL to your search route
            method: 'GET',
            data: {
                product_ids: Product_ids,
                product_child_ids: Product_child_ids,
                type: activeButtonVal,
                search_input: searchTerm // Pass the search term
            },
            success: function(response) {
                var searchResults = $('.rfq_merchant_list');
                searchResults.empty(); 
                searchResults.html(response.html); // Update the UI with the search results
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });
    
    
// ---------------------------------


    // merchant list for rfq ---2nd
    var all_or_fav = null ;

    // merchant list for rfq   old

    // $(document).on("change", 'input[name="merchant_type_radio"]', function () {
    //     var type = $(this).val();
    //     all_or_fav = type;
    //     console.log(type);
    //     loadMerchants();
    // });

    $(document).on("change", 'input[name="merchant_type_radio"]', function () {
        var type = $(this).val();
        all_or_fav = type;
        console.log(type);
        loadMerchants();
    });

     // merchant list for rfq   new
    $(document).on("click", '.tog-button', function () {
        $('.tog-button').removeClass('active');
        $(this).addClass('active');
    
        // Get the ID of the clicked button and assign it to the 'all_or_fav' variable
        var type = $(this).attr('id');
        all_or_fav = type;
        
        // Log the type (all or fav) to the console
        console.log(type);
        
        // Call the function to load merchants based on the selected type
        loadMerchants();
    });


    // filter for nominated and certified
    $('input[type="checkbox"][name="merchant_type_filter"]').on('change', function() {
        loadMerchants();
    });

    function loadMerchants() {
        var selectedTypes = [];
        $('input[type="checkbox"][name="merchant_type_filter"]:checked').each(function() {
            selectedTypes.push($(this).val());
        });

        console.log(all_or_fav);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/admin/fav-list',
            method: 'GET',
            data: {
                product_ids: Product_ids,
                product_child_ids: Product_child_ids,
                type: all_or_fav,
                filters: selectedTypes
            },
            success: function(response) {
                $('.rfq_merchant_list').html(response.html);
            },
            error: function(xhr) {
                console.error('Error loading merchants:', xhr);
            }
        });
    }



    // select all merchant 
    $(document).on("change", '#select_all',  function() {
        var isChecked = $(this).prop('checked');
        $('input[name="selected_merchant[]"]').prop('checked', isChecked);
    });

    // If all individual checkboxes are checked, check the Select All checkbox
    $(document).on("change", 'input[name="selected_merchant[]"]',  function() {
        if ($('input[name="selected_merchant[]"]:checked').length === $('input[name="selected_merchant[]"]').length) {
            $('#select_all').prop('checked', true);
        } else {
            $('#select_all').prop('checked', false);
        }
    });


    


    
  
    // add to fav
    $('.add_to_fav').click(function() {
        var url = $(this).data('url');
        var button = $(this);
        var unfavImg = button.data('unfav-img');
        var favImg = button.data('fav-img');
        var removeTitle = button.data('remove-title');
        var addTitle = button.data('add-title');

        console.log(url);
        $.ajax({
            url: url,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log(response);
                if (response.success == true) {
                    Toast.fire({
                        icon: 'success',
                        title: response.message
                    });
                    if (button.hasClass('remove')) {
                        button.removeClass('remove').addClass('add');
                        button.find('img').attr('src', unfavImg);
                        button.attr('data-original-title', addTitle).tooltip('update');
                    } else {
                        button.removeClass('add').addClass('remove');
                        button.find('img').attr('src', favImg);
                        button.attr('data-original-title', removeTitle).tooltip('update');
                    }
                    setTimeout(() => {
                        // location.reload();
                        // table.ajax.reload(null, false);
                    }, 1500);
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: response.message
                    });
                    setTimeout(() => {}, 1500);
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });

    
      // Function to change the type attribute to date
      function changeToDatePicker() {
        // Set the minimum date to today
        var today = new Date();
        var minDate = today.toISOString().split('T')[0];

        $(this).prop('type', 'date');
        $(this).attr('min', minDate); // Set the min attribute to today's date
        $(this).removeAttr('onfocus');
        $(this).removeAttr('onblur');
    }

    // Function to change the type attribute to text
    function changeToText() {
        $(this).prop('type', 'text');
        $(this).attr('onfocus', "changeToDatePicker.call(this)");
        $(this).attr('onblur', "if(this.value == '') changeToText.call(this)");
    }

    // Event handler for input[type="text"] focus within .ddate
    $(document).on("focusin", '.ddate input[type="text"]', function () {
        changeToDatePicker.call(this);
    });

    // Event handler for blur
    $(document).on("blur", '.ddate input[type="date"]', function () {
        changeToText.call(this);
    });
    $(document).on("click", "#add-product-child", function () {

        var index = $(this).data('index');
        var html = '';
        html +=' <div class="product_child_div" id="product_child_item_' + index + '"><input type="text" class="form-control mb-2 mr-2"  name="product_child_name[]" value="" placeholder="Type product child name...">'
        html += '<button class="btn product-child-item-cross cross-btn mb-2" data-id="product_child_item_' + index + '"><img src="/admin/img/icons/cancel.svg"></button></div>';
        $('#product_child_append').append(html);

        index++;
        $(this).data('index', index);
        // $('#item_count').val(index);
    });
    //Rfq Item Cross button click
    $(document).on("click", ".product-child-item-cross", function (e) {
        e.preventDefault();
        var div_id = $(this).data('id');
        $('#' + div_id).remove();
        $(this).remove();
        
    });
    $(document).on("click", "#add-rfq-item", function () {

        index = $(this).data('index');
        var html = '';
        html += '<div class="row align-items-start justify-content-between mt-5" id="rfq_item_' + index + '">';
        html += '<div class="form-group cat">';
        html += '<select class="form-control rfq-cat-select" name="items[' + index + '][category_id]" id="category_id_' + index + '">'
        html += '<option value=""> Select product type</option>';
        html += '</select></div><div class="form-group prod">';
        html += '<select class="form-control rfq-product-select" name="items[' + index + '][product_id]">';
        html += '<option value="">Select product</option></select></div>';
        
        html += '<div class="form-group prod-child">';
        html += '<select class="form-control rfq-product-child-select" name="items[' + index + '][product_child_id]">';
        html += '<option value="">Select sub-category</option></select></div>';

        html += '<div class="form-group d-flex rfq-qty-div">';
        html += '<input type="number" class="form-control" name="items[' + index + '][quantity]" value="" placeholder="Quantity">';
        html += '<label for="items[' + index + '][quantity]" id="product_' + index + '_unit" class="col-form-label">gm</label>';
        html += '<input type="hidden" class="form-control product_unit" name="items[' + index + '][unit_id]"></div>';
        html += '<div class="form-group ddate">';
        html += '<input type="text" placeholder="Delivery Date" class="form-control" name="items[' + index + '][delivery_date]"></div>';
        html += '<div class="form-group remr"><input type="text" class="form-control" name="items[' + index + '][remarks]" value="" placeholder="Remarks"></div>';
        html += '<div class="col-lg-12 append-rfq-spacs"></div>';
        html += '<div class="append-rfq-cross-button" style="display: grid"><button class="btn rfq-item-cross cross-btn" data-id="rfq_item_' + index + '"><img src="/admin/img/icons/cancel.svg"></button></div>';
        $('#append-rfq-form').append(html);

        $.each(categories, function (i, value) {
            $('#category_id_' + index + '').append('<option value="' + value.id + '" dataName="' + value.name + '">' + value.name + '</option>');
        })
        index++;
        $(this).data('index', index);
        $('#item_count').val(index);
    });
    //Rfq Item Cross button click
    $(document).on("click", ".rfq-item-cross", function (e) {
        e.preventDefault();
        var div_id = $(this).data('id');
        //itemCount = $('#item_count').val();
        //$('#item_count').val(itemCount-1);
        $('#' + div_id).remove();
        $(this).remove();
        
    });

    

    const Product_ids = [];
    const Product_child_ids = [];
  

    //RFQ form submit
    $(document).on("click", "#save-rfq", function (e) {
        e.preventDefault();
        var savedataTarget = $(this);
        var form = $('.rfq-form');
        var url = form.attr('action');
        $(document).find("span.text-danger").remove();
        //var form_data = form.serialize();
        var form_data = new FormData(form[0]);

        if ($('#rfq_type_public').is(':checked')) {
            $('.merchant_list_radio').hide();
        }

         if ($('#rfq_type_public').is(':checked')) {
            $('.advance_filter').hide();
        }
      

        $('#rfq_modal_table_body').empty();
        for (var i = 0; i < form.find('input[name="item_count"]').val(); i++) {
            if ($('#rfq_item_' + i).length) {
                //console.log("Element with ID 'rfq_item_" + i + "' exists.");
                var item_files = 'items[' + i + '][attachments]';
            // Find all input fields related to attachments for a specific item
            var inputFields = form.find('input[name^="' + item_files + '"]');
            var attachmentsHtml = '';

            // Check if there are input fields and if there is more than one input field
            if (inputFields.length > 1) {
                attachmentsHtml += '<div class="rfq-attachment-show">';
                
                for (var j = 0; j < inputFields.length; j++) {
                    // Skip the first input field (j = 0)
                    if (j === 0) {
                        continue;
                    }

                    var files = inputFields[j].files;

                    // Check if the input field has the 'files' property
                    if (files) {
                        for (var k = 0; k < files.length; k++) {
                            form_data.append(item_files + '[]', files[k]);
                            var fileName = files[k].name;
                            attachmentsHtml += '<p>' + fileName + '</p>';
                            //console.log(fileName);
                        }
                    }
                }

                attachmentsHtml += '</div>';
            }
            //console.log(attachmentsHtml);
            var pattern = 'items[' + i + '][description]';
            var specificationsHtml = '';
                $(form.find('input[name^="' + pattern + '"]')).each(function(index) {
                    var attribute = $(this).attr('name').split('][');
                    //specificationsHtml += attribute[attribute.length - 1].replace(']', '') +": <b>"+$(this).val() +"</b>";
                    var value = $(this).val();
                    if (value !== null && value !== '') {
                        specificationsHtml += attribute[attribute.length - 1].replace(']', '') + ": <b>" + value + "</b>";
                        // Check if there are more input fields with non-empty values
                        var hasNextNonEmptyValue = $(form.find('input[name^="' + pattern + '"]')).slice(index + 1).filter(function() {
                            return $(this).val() !== null && $(this).val() !== '';
                        }).length > 0;

                        // Append semicolon only if there are more input fields with non-empty values
                        if (hasNextNonEmptyValue) {
                            specificationsHtml += '; ';
                        }
                    }      
                });
            var delivery_date = new Date(form.find('input[name="items[' + i + '][delivery_date]"]').val());
            var options = { month: 'short', day: 'numeric', year: 'numeric' };;
            delivery_date = delivery_date.toLocaleDateString('en-US', options);


            var product_id = form.find('select[name="items[' + i + '][product_id]"]').val();
            var product_child_id = form.find('select[name="items[' + i + '][product_child_id]"]').val();

            if (product_id) {
                if (product_child_id) {
                    if (!Product_child_ids.includes(product_child_id)) {
                        Product_child_ids.push(product_child_id);
                    }
                } else {
                    if (!Product_ids.includes(product_id)) {
                        Product_ids.push(product_id);
                    }
                }
            }

            var productChildDataName = form.find('select[name="items[' + i + '][product_child_id]"] option:selected').attr('dataName');
            var html = `<tr>
            <td style="width: 25%;padding-right: 1rem;">`+ form.find('select[name="items[' + i + '][product_id]"] option:selected').attr('dataName') + (productChildDataName ? ' | ' + productChildDataName : '')+`</td>
            <td style="width: 30%;padding-right: 1rem;">${specificationsHtml} </td>
            <td style="width: 15%;padding-right: 1rem;">`+ form.find('input[name="items[' + i + '][quantity]"]').val() + ` ` + form.find('#product_' + i + '_unit').html() + `</td>
            <td style="width: 15%;padding-right: 1rem;">`+ delivery_date + `</td>
            <td style="width: 15%;padding-right: 1rem;">${attachmentsHtml}</td>
            </tr><br>`;
            $('#rfq_modal_table_body').append(html);  
            }   
        }

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: url,
            data: form_data,
            // dataType: "JSON",
            processData: false,
            contentType: false,
            success: function (response) {
                
                if (response.error_message) {
                    
                    Swal.fire({
                        customClass: {
                            icon: 'mt-4'
                        },
                        position: 'center',
                        icon: 'error',
                        title: response.error_message,
                        showConfirmButton: true,
                        // timer: 2000
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                }
                else if (response.validation_error) {
                    console.log(response.validation_error);
                    var keys = "";
                    $.each(response.validation_error, function (field_name, error) {
                        // $(document).find('[name=' + field_name + ']').after('<span class="text-strong text-danger">' + error + '</span>');
                        keys = field_name.split('.');
                        if (keys.length > 1) {
                            //$(document).find('[name="' + keys[0] + "[" + keys[1] + "]" + "[" + keys[2] + "]" + "" + '"]').after('<span class="text-strong text-danger">' + error + '</span>');
                            var multi_layer_field = $(document).find('[name="' + keys[0] + "[" + keys[1] + "]" + "[" + keys[2] + "]" + "" + '"]');
                            //console.log(multi_layer_field);
                            multi_layer_field.attr( { "data-toggle":"tooltip", title:error } );
                            multi_layer_field.tooltip({
                                template: '<div class="tooltip custom-tooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>',
                                boundary: 'window',
                                placement: 'top',
                            }).tooltip('show');
                        }
                        else {
                            //$(document).find('[name=' + field_name + ']').after('<span class="text-strong text-danger">' + error + '</span>');
                            var field = $(document).find('[name=' + field_name + ']');
                            field.attr( { "data-toggle":"tooltip", title:error });
                            field.tooltip({
                                template: '<div class="tooltip custom-tooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>',
                                boundary: 'window',
                                placement: 'top',
                            }).tooltip('show');
                        }
                    })

                }
                else if (response.order === "not_found") {
                    console.log("hello");
                    $('#order_form_modal').modal('show');
                    // Show toast message
                    Toast.fire({
                        icon: 'error',
                        title: "Order not found. Create order first"
                    });
                }
                else {
                    if (savedataTarget.attr('data-target') != '.rfq-details-modal-lg') {

                        savedataTarget.attr('data-target', '.rfq-details-modal-lg');
                        savedataTarget.trigger('click');
                    }
                    // savedataTarget.trigger('click');
                    // savedataTarget.off('click');
                }
            },
            error: function (xhr) {
                console.log(xhr);
                Toast.fire({
                    icon: 'error',
                    title: "Something went wrong"
                })
            }
        });

    });

    // tutorial play
    $(document).on("click", ".play-button", function () {
        var video_url = $(this).next('video').find('source').attr('src');
        $('#tutorial_modal .modal-body').html('');
        $('#tutorial_modal .modal-body').html('<video class="modal-video" width="100%" height="auto" controls><source src = "' + video_url + '" type = "video/mp4">Your browser does not support the video tag.</video >');
        var video = $('.modal-video')[0];
        $('#tutorial_modal').show();
        video.play();
    });
    // tutorial pause
    $('#tutorial_modal').on('hidden.bs.modal', function () {
        $('#tutorial_modal').hide();
        $('.modal-video')[0].pause();
    });


  


    //admin end  Toggle button click on product list
    $(document).on("click", ".product-toggle", function () {
        $(".product-toggle").removeClass("active");
        $(this).addClass("active");
    });

 
    // on category select get products (RFQ section)
    $(document).on("change", ".rfq-cat-select", function () {
        var category_id = $(this).find(":selected").val();
        var url = "/admin/single-cat-products/" + category_id;
        var current_product_div = $(this).parent().siblings().find('.rfq-product-select');
        //console.log(current_div);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'get',
            url: url,
            success: function (response) {
                //console.log(response.data);
                current_product_div.empty();
                current_product_div.append('<option value="#" dataName="#">Select product</option>');
                $.each(response.data, function (index, value) {
                    current_product_div.append('<option value="' + value.id + '" dataName="' + value.name + '">' + value.name + '</option>');

                })
            },
            error: function (xhr) {
                console.log(xhr);
                Toast.fire({
                    icon: 'error',
                    title: "Something went wrong"
                })
            }
        });
    });
    // on category select get products (merchant product section)
    $(document).on("change", ".merchant-product-cat", function () {
        var category_id = $(this).find(":selected").val();
        var url = "/admin/single-cat-products-merchent-products/" + category_id;
        //console.log(category_id);
        //console.log(current_product_div);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'get',
            url: url,
            success: function (response) {
                //console.log(response.products);
                //console.log(response.merchent_products);
                $('#merchant-product').empty();
                var products = response.products;
                var merchentProducts = response.merchent_products;

                // Add the "Select Options" option at the beginning
                var selectOptionsOption = $('<option>', {
                    value: '#',
                    text: 'Select Options',
                    disabled: true,
                    selected: true
                });
            
                // Append the "Select Options" option to the dropdown
                $('#merchant-product').append(selectOptionsOption);

                products.forEach(function(product) {
                // Check if the product has a corresponding entry in the merchent_products array
                var isDisabled = merchentProducts.some(function(merpro) {
                    return product.id === merpro.product_id;
                });

                // Create the option element
                
                option = $('<option>', {
                    value: product.id,
                    text: product.name,
                    disabled: isDisabled,
                    class: isDisabled ? 'text-warning' : ''
                });

                // Append the option to the dropdown
                $('#merchant-product').append(option);
                
                // Refresh the SelectPicker to reflect the changes
                $('#merchant-product').selectpicker('refresh');
                });
            },
            error: function (xhr) {
                console.log(xhr);
                Toast.fire({
                    icon: 'error',
                    title: "Something went wrong"
                })
            }
        });
    });
    //Product on select get child products
    $(document).on("change", "#merchant-product", function () {

        var product_id = $(this).val();
        var url = $(this).data('url');
        url = url.replace(':product_id', product_id);
        var modal = $('.form_modal');
        var product_child_div = modal.find('.merchant-product-child-select .options-container');
        //console.log(product_child_div);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'get',
            url: url,
            success: function (response) {
                //console.log(response.data);
                // modal.find('.form').attr('action', url);
                //$("#category_id").val(response.data.category_id);
                $("#unit_id").val(response.data.unit_id);
                if(response.data.child_products){
                    product_child_div.empty();
                    //product_child_div.append('<option value="#" dataName="#">Select child product</option>');
                    $.each(response.data.child_products, function (index, value) {
                        var html = `<div class="option">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input child-product-checkbox" id="child-product-`+ value.id +`" name="product_child_id[]" value="`+ value.id +`" title="`+ value.name +`" >
                            <label class="custom-control-label" for="child-product-`+ value.id +`">`+ value.name +`</label>
                        </div>
                    </div>`;
                        product_child_div.append(html)
                        //product_child_div.append('<option value="' + value.id + '" dataName="' + value.name + '">' + value.name + '</option>');

                    })
                }
            },
            error: function (xhr) {
                console.log(xhr);
                Toast.fire({
                    icon: 'error',
                    title: "Something went wrong"
                })
            }
        });

    });
    
    //Bid now button click get rfq data
    $(document).on("click", ".bid-btn", function (e) {
        e.preventDefault();
        var url = $(this).attr("data-url");
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'get',
            url: url,
            success: function (response) {
                console.log(response.data);
                $('#bid_modal .modal-content').html(response.html);
                initializeTooltips();
                
            },
            error: function (xhr) {
                console.log(xhr);
                Toast.fire({
                    icon: 'error',
                    title: "Something went wrong"
                })
            }
        });
    });

    // Bid price calculation
    $(document).on("change", ".unit_price, .quantity", function () {
        var parent = $(this).closest('tr');
        var unit_price = parent.find('.unit_price').val();
        var quantity = parent.find('.quantity').val();
        var quantity = parent.find('.quantity').val();

        
        unit_price = parseFloat(unit_price) || 0;
        quantity = parseFloat(quantity) || 0;

        // bid quantity max value check
        var max_qty = parseFloat(parent.find('.quantity').prop('max'));
        if (quantity > max_qty) {
            parent.find('.quantity').val(max_qty); // Set the value to the maximum allowed value
          }

        var total_price = parseFloat((unit_price * quantity).toFixed(2));
        parent.find('.total-price').val(total_price);
    });

    //Bid form submit
    $(document).on("click", "#save-bid", function (e) {
        e.preventDefault();
        var savedataTarget = $(this);
        var modal = $('#bid_modal');
        var form = $('.bid-form');
        var url = form.attr('action');
        $(document).find("span.text-danger").remove();
        var form_data = form.serialize();
        //console.log(form_data);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: url,
            data: form_data,
            success: function (response) {
                //console.log(response);
                if (response.success_message) {
                    modal.hide();
                    Toast.fire({
                        icon: 'success',
                        title: response.success_message
                    })
                    setTimeout(() => {
                        location.reload();
                    }, 1500);

                }
                else if (response.error_message) {

                    Swal.fire({
                        customClass: {
                            icon: 'mt-4'
                        },
                        position: 'center',
                        icon: 'error',
                        title: response.error_message,
                        showConfirmButton: true,
                        // timer: 2000
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                }
                else if (response.validation_error) {
                    console.log(response.validation_error);
                    var keys = "";
                    $.each(response.validation_error, function (field_name, error) {
                        // $(document).find('[name=' + field_name + ']').after('<span class="text-strong text-danger">' + error + '</span>');
                        keys = field_name.split('.');
                        if (keys.length > 1) {
                            //$(document).find('[name="' + keys[0] + "[" + keys[1] + "]" + "[" + keys[2] + "]" + "" + '"]').after('<span class="text-strong text-danger">' + error + '</span>');
                            var multi_layer_field = $(document).find('[name="' + keys[0] + "[" + keys[1] + "]" + "[" + keys[2] + "]" + "" + '"]');
                            //console.log(multi_layer_field);
                            multi_layer_field.attr( { "data-toggle":"tooltip", title:error } );
                            multi_layer_field.tooltip({
                                template: '<div class="tooltip custom-tooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>',
                                boundary: 'window',
                                placement: 'top',
                            }).tooltip('show');
                        }
                        else {
                            //$(document).find('[name=' + field_name + ']').after('<span class="text-strong text-danger">' + error + '</span>');
                            var field = $(document).find('[name=' + field_name + ']');
                            field.attr( { "data-toggle":"tooltip", title:error });
                            field.tooltip({
                                template: '<div class="tooltip custom-tooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>',
                                boundary: 'window',
                                placement: 'top',
                            }).tooltip('show');
                        }
                    })
                  
                  }
            },
            error: function (xhr) {
                console.log(xhr);
                Toast.fire({
                    icon: 'error',
                    title: "Something went wrong"
                })
            }
        });
    });
    //Confirm Bid Withdraw
    $(document).on("click", ".withdraw-btn", function (e) {
        e.preventDefault();
        var url = $(this).attr('data-url');
        //console.log(url);
        Swal.fire({
            customClass: {
                icon: 'mt-4'
            },
            title: 'Are you sure you want to withdraw this bid?',
            //icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF717D',
            cancelButtonColor: '#384A52',
            confirmButtonText: 'Confirm',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'post',
                    url: url,
                    success: function (response) {
                        //console.log(response.data);
                        Toast.fire({
                            icon: 'success',
                            title: response.success_message
                        })
                        // setTimeout(() => {
                        //     location.reload();
                        // }, 1500);
                    },
                    error: function (xhr) {
                        console.log(xhr);
                        Toast.fire({
                            icon: 'error',
                            title: "Something went wrong"
                        })
                    }
                });
            }
        })
    });
    //Single RFQ Bids 
    $(document).on("click", ".single-rfq-bids", function (e) {
        e.preventDefault();
        $('.open-rfq').find('.show-bids').html('');
        $(this).find('img').toggleClass('arrow-toggle');
        $('.open-rfq .single-rfq-bids img').not($(this).find('img')).removeClass('arrow-toggle');
        
        var target_class = $(this).closest('tr').next('.show-bids');
        var url = $(this).attr("data-url");

        if ($(this).find('img').hasClass('arrow-toggle')) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'get',
                url: url,
                success: function (response) {
                   //console.log(response.data);
                    target_class.html(response.html);
                    $(".bid-details-product-div").each(function () {
                        $(this).find(".bid_details_section:first").click();
                    });
                    initializeTooltips(); //call tooltip.
                },
                error: function (xhr) {
                    console.log(xhr);
                    Toast.fire({
                        icon: 'error',
                        title: "Something went wrong"
                    })
                }
            });
        } else {

            target_class.html('');
        }
    });


    // rfq get 
    $('.get_update_single_rfq').click(function() {
        var url = $(this).data('url');
       
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'get',
            url: url,
            success: function(response) {
               
                console.log(response);
                $('#rfq_form_edit .modal-content').html(response.html);

                // Show the modal
                $('#rfq_form_edit').modal('show');
            },
            error: function(xhr) {
                console.log(xhr);
                Toast.fire({
                    icon: 'error',
                    title: "Something went wrong"
                });
            }
        });
    });

    //ReBid button click bid data
    $(document).on("click", ".rebid-btn", function (e) {
        e.preventDefault();
        var url = $(this).attr("data-url");;
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'get',
            url: url,
            success: function (response) {

                $('#bid_modal .modal-content').html(response.html);
                initializeTooltips(); //call tooltip.
                
            },
            error: function (xhr) {
                console.log(xhr);
                Toast.fire({
                    icon: 'error',
                    title: "Something went wrong"
                })
            }
        });
    });
    //Close rfq bullon click
    $(document).on("click", ".close-rfq-btn", function (e) {
        e.preventDefault();
        var url = $(this).attr("data-url");
        var id = $(this).attr("data-id");
        var modal = $('.close_bid_modal');
        modal.find('.close_rfq_form').attr('action', url);
        //console.log(modal);
        modal.find('#id').val(id);
    });
    //Close rfq form submit
    $(document).on("click", "#closeRfqBtn", function (e) {
        e.preventDefault();
        var form = $('.close_bid_modal').find('.form');
        var url = form.attr("action");;
        var modal = $('.close_bid_modal');
        var updated_by = form.find('#updated_by').val();
        var form_data = form.serialize();
        $(document).find("span.text-danger").remove();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: url,
            data: form_data,
            success: function (response) {
                //console.log(response.data);
                if (response.success_message) {
                    modal.hide();
                    Toast.fire({
                        icon: 'success',
                        title: response.success_message
                    })
                    setTimeout(() => {
                        location.reload();
                    }, 1500);

                }
                else if (response.error_message) {

                    Swal.fire({
                        customClass: {
                            icon: 'mt-4'
                        },
                        position: 'center',
                        icon: 'error',
                        title: response.error_message,
                        showConfirmButton: true,
                        // timer: 2000
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                }
                else if (response.validation_error) {
                    console.log(response);
                    $.each(response.validation_error, function (field_name, error) {
                        $(document).find('[name=' + field_name + ']').after('<span class="text-strong text-danger">' + error + '</span>')
                    })

                }
            },
            error: function (xhr) {
                console.log(xhr);
                //console.log(response.data);
                Toast.fire({
                    icon: 'error',
                    title: "Something went wrong"
                })
            }
        });
    });

    //merchent Toggle button click on merchent list
    $(document).on("click", ".merchant-toggle", function () {
        $(".merchant-toggle").removeClass("active");
        $(this).addClass("active");
    });
    //cancel membership bullon click
    $(document).on("click", ".cancel-membership", function (e) {
        e.preventDefault();
        var parent = $(this).closest('tr');
        var company = parent.find('.company-name').html();
        var url = $(this).attr("data-url");
        var modal = $('.cancel_membership_modal');
        modal.find('.form').attr('action', url);
        modal.find('.company').html(company);
        //console.log(company);
    });
    //cancel membership form submit
    $(document).on("click", "#cancelMembershipBtn", function (e) {
        e.preventDefault();
        var form = $('.cancel_membership_modal').find('.form');
        var url = form.attr("action");;
        var modal = $('.cancel_membership_modal');
        var form_data = form.serialize();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: url,
            data: form_data,
            success: function (response) {
                //console.log(response.data);
                if (response.success_message) {
                    modal.hide();
                    Toast.fire({
                        icon: 'success',
                        title: response.success_message
                    })
                    setTimeout(() => {
                        location.reload();
                    }, 1500);

                }
                else if (response.error_message) {

                    Swal.fire({
                        customClass: {
                            icon: 'mt-4'
                        },
                        position: 'center',
                        icon: 'error',
                        title: response.error_message,
                        showConfirmButton: true,
                        // timer: 2000
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                }
            },
            error: function (xhr) {
                console.log(xhr);
                //console.log(response.data);
                Toast.fire({
                    icon: 'error',
                    title: "Something went wrong"
                })
            }
        });
    });

    //Proceed to PO
    $(document).on("click", "#proceed-po", function (e) {
        e.preventDefault();
        var url = $(this).attr("data-url");
        $('#po_modal_table_body').empty();
        var form = $('.bid_details_submit_form');
        var url = form.attr('action');
        var form_data = form.serialize();

        if (form_data.indexOf("selected_bid_id") !== -1) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'post',
                url: url,
                data: form_data,
                success: function (response) {
                    //console.log(response.data);
                        if (response.success) {
                            //console.log(response.data);
                            $('#po_info_tab').empty();
                            $('#po_company_name').empty();
                            $('#po_submit_form').empty();
                            $('#po_modal_table_body').empty();
                            $('#terms_del_ins').empty();
                            
                            $('.po_modal .modal-content').html(response.html);
                            
    
                            $('.po_modal .po_sent_success_div').hide();
                           // Trigger click event on the active button
                            $('.po_info_btn.active').click();
                            
                            initializeTooltips(); //call tooltip.
                        }
                        else if (response.error_message) {
    
                            Swal.fire({
                                customClass: {
                                    icon: 'mt-4'
                                },
                                position: 'center',
                                icon: 'error',
                                title: response.error_message,
                                showConfirmButton: true,
                                // timer: 2000
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            });
                        }
                    
                },
                error: function (xhr) {
                    console.log(xhr);
                    Toast.fire({
                        icon: 'error',
                        title: "Something went wrong"
                    })
                }
            });
        }
        else {
            Swal.fire({
                customClass: {
                    icon: 'mt-4'
                },
                position: 'center',
                icon: 'warning',
                title: 'Please select bids first',
                showConfirmButton: true,
                confirmButtonColor: '#4D7CFF'
                // timer: 2000
            }).then((result) => {
                if (result.isConfirmed) {
                }
            });
        }


    });
    

    //PO info button click 
    $(document).on("click", ".po_info_btn", function () {
        var poId = $(this).data('id');
        $('.po_info_btn').removeClass('active');
        $(this).addClass('active');
        $('.po_modal .po_sent_success_div').hide();
        $('.po_modal .modal-footer').show();
        //$('.po_modal .modal-content .po_content').html('');

        var url = "/admin/get-purchase-order/"+poId;
        // Check if the input value is not blank
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'get',
                url: url,
                success: function (response) {
                    console.log(response.data);
                    if (response.success) {
                        $('#single_po_container').html(response.html);

                    }
                },
                error: function (xhr) {
                    console.log(xhr);
                    Toast.fire({
                        icon: 'error',
                        title: "Something went wrong"
                    })
                }
            });
        
    });

    $(document).on("click", ".confirm_po", function (e) {
        e.preventDefault();
        $(document).find("span.text-danger").remove();
        var button = $(this); // Reference to the clicked button
        var form = $('.po_submit_form');
        var url = button.data('url');
        var form_data = form.serialize();
        // var form_data = new FormData(form[0]);
        button.prop('disabled', true); // Disable the button
        //console.log(form_data);
        
        // Find the .single-rfq-po-pi button
        var singleRfqPoPi = $('.single-rfq-po-pi');
        var singleRfqPoPiId ='';
        if (singleRfqPoPi.find('.arrow-toggle').length > 0) {
            singleRfqPoPiId = singleRfqPoPi.attr('id'); 
        }
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: url,
            data: form_data,
            success: function (response) {
                //console.log(response.pdf);
                if (response.success) {
                    if(response.type == 'preview'){
                        // Decode base64 PDF content
                        var pdfData = atob(response.pdf);

                        // Convert binary string to ArrayBuffer
                        var dataArray = new Uint8Array(pdfData.length);
                        for (var i = 0; i < pdfData.length; i++) {
                            dataArray[i] = pdfData.charCodeAt(i);
                        }
                        var pdfArray = new Uint8Array(dataArray);

                        // Create blob object
                        var blob = new Blob([pdfArray], { type: 'application/pdf' });
                        var url = URL.createObjectURL(blob);
                        window.open(url, '_blank');
                    }
                    else if(response.type == 'save'){
                        $('.po_modal .po_content').hide();
                        $('.po_modal .modal-footer').hide();
                        $('.po_modal .po_sent_success_div').show();
                        
                         // Set the href attribute of the preview link dynamically
                         $('.po_sent_success_div .preview_po').attr('href', "/admin/purchase-order-log/preview/" + response.po_id);

                        // Find the active button
                        var activeButton = $('.po_info_btn.active');
                        $('.po_sent_success_div .order-log-btn').hide();

                        // Check if there's a next button
                        var nextButton = activeButton.next('.po_info_btn');
                        if (nextButton.length > 0) {
                            // If there's a next button, get its id
                            var nextButtonId = nextButton.attr('id');
                            
                            // Trigger click event on the next button
                            $('.po_sent_success_div .next_po_btn').data('id', nextButtonId);
                        } else {
                            // If there's no next button, do something else
                            $('.po_sent_success_div .next_po_btn').hide();
                            $('.po_sent_success_div .order-log-btn').show();
                        }

                        Toast.fire({
                            icon: 'success',
                            title: response.message
                        })
                    }else if(response.type == 'reverted'){
                        
                        Toast.fire({
                            icon: 'success',
                            title: response.message
                        })
                        sessionStorage.setItem('singleRfqPoPiId', singleRfqPoPiId); // Store the singleRfqPoPiId to session
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    }

                }
                else if (response.validation_error) {
                    console.log(response.validation_error);
                    var keys = "";
                    $.each(response.validation_error, function (field_name, error) {
                        // $(document).find('[name=' + field_name + ']').after('<span class="text-strong text-danger">' + error + '</span>');
                        keys = field_name.split('.');
                        if (keys.length > 1) {
                            //$(document).find('[name="' + keys[0] + "[" + keys[1] + "]" + "[" + keys[2] + "]" + "" + '"]').after('<span class="text-strong text-danger">' + error + '</span>');
                            var multi_layer_field = $(document).find('[name="' + keys[0] + "[" + keys[1] + "]" + "[" + keys[2] + "]" + "" + '"]');
                            //console.log(multi_layer_field);
                            multi_layer_field.attr( { "data-toggle":"tooltip", title:error } );
                            multi_layer_field.tooltip({
                                template: '<div class="tooltip custom-tooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>',
                                boundary: 'window',
                                placement: 'top',
                            }).tooltip('show');
                        }
                        else {
                            //$(document).find('[name=' + field_name + ']').after('<span class="text-strong text-danger">' + error + '</span>');
                            var field = $(document).find('[name=' + field_name + ']');
                            field.attr( { "data-toggle":"tooltip", title:error });
                            field.tooltip({
                                template: '<div class="tooltip custom-tooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>',
                                boundary: 'window',
                                placement: 'top',
                            }).tooltip('show');
                        }
                    })
                  
                }
                else {
                    Toast.fire({
                        icon: 'error',
                        title: response.message
                    })
                }

            },
            error: function (xhr) {
                console.log(xhr);
                Toast.fire({
                    icon: 'error',
                    title: "Something went wrong"
                })
            },
            complete: function () {
                // Enable the button in the complete callback
                button.prop('disabled', false);
                
            }
        });
    });

    // reload active section on order log
    var singleRfqPoPiId = sessionStorage.getItem('singleRfqPoPiId');
    $(document).ready(function () {
        if (singleRfqPoPiId) {
            $('#' + singleRfqPoPiId).click();
            sessionStorage.removeItem('singleRfqPoPiId'); // Remove the stored ID
        }
    });
    //end reload active section on order log

    $(document).on("hidden.bs.modal", "#po_modal", function () {
        var idsToDelete = [];
        $('#po_info_tab button').each(function() {
            idsToDelete.push($(this).data('id')); // Add the data-id value to the array
        });
        console.log(idsToDelete);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/admin/delete-purchase-order', // Endpoint to handle delete operation
            type: 'post',
            data: { purchase_order_ids: idsToDelete},
            success: function(response) {
                // Handle success response
                console.log(response.message);
            },
            error: function(xhr, status, error) {
                // Handle error response
                console.error(xhr);
            }
        });
    });
    $(document).on("click", ".po-cancel-btn", function () {
        var idsToDelete = [];
        $('#po_info_tab button').each(function() {
            idsToDelete.push($(this).data('id')); // Add the data-id value to the array
        });
        console.log(idsToDelete);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/admin/delete-purchase-order', // Endpoint to handle delete operation
            type: 'post',
            data: { purchase_order_ids: idsToDelete},
            success: function(response) {
                // Handle success response
                console.log(response.message);
            },
            error: function(xhr, status, error) {
                // Handle error response
                console.error(xhr);
            }
        });
    });
    //PO edit button click 
    $(document).on("click", ".po_edit_btn", function () {
        var poId = $(this).data('id');
        var dataType = $(this).data('type');
        
        $('.edit_po_modal .modal-content').html('');

        var url = "/admin/get-purchase-order/"+poId;
        // Check if the input value is not blank
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'get',
                url: url,
                data: {data_type: dataType},
                success: function (response) {
                    //console.log(response.data);
                    //console.log(response.html);
                    if (response.success) {
                        $('.edit_po_modal .modal-content').html(response.html);
                        $('.edit_po_modal .po_sent_success_div').hide();
                        $('.edit_po_modal .modal-footer').show();

                    }
                },
                error: function (xhr) {
                    console.log(xhr);
                    Toast.fire({
                        icon: 'error',
                        title: "Something went wrong"
                    })
                }
            });
        
    });
    //PI edit button click 
    $(document).on("click", ".pi_revise_btn", function () {
        var piId = $(this).data('id');
        var dataType = $(this).data('type');
        
        $('.revise_pi_modal .modal-content').html('');

        var url = "/admin/get-proforma-invoice/"+piId;
        // Check if the input value is not blank
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'get',
                url: url,
                data: {data_type: dataType},
                success: function (response) {
                   // console.log(response.data);
                    //console.log(response.html);
                    if (response.success) {
                        $('.revise_pi_modal .modal-content').html(response.html);
                        $('.revise_pi_modal .po_sent_success_div').hide();
                        $('.revise_pi_modal .modal-footer').show();

                    }
                },
                error: function (xhr) {
                    console.log(xhr);
                    Toast.fire({
                        icon: 'error',
                        title: "Something went wrong"
                    })
                }
            });
        
    });

    //Lc App edit button click 
    $(document).on("click", ".lc_app_edit_btn", function () {
        var lcId = $(this).data('id');
        var dataType = $(this).data('type');
        
        $('.edit_lc_modal .modal-content').html('');

        var url = "/admin/get-lc/"+lcId;
        // Check if the input value is not blank
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'get',
                url: url,
                data: {data_type: dataType},
                success: function (response) {
                    //console.log(response.data);
                    //console.log(response.html);
                    if (response.success) {
                        $('.edit_lc_modal .modal-content').html(response.html);
                       // $('.edit_lc_modal .po_sent_success_div').hide();
                        //$('.edit_lc_modal .modal-footer').show();

                    }
                },
                error: function (xhr) {
                    console.log(xhr);
                    Toast.fire({
                        icon: 'error',
                        title: "Something went wrong"
                    })
                }
            });
        
    });
    
    $(document).on('hidden.bs.modal', '#generate_pi_modal', function (e) {
        var piId = $('#generate_pi_modal .pi_submit_form').attr('id');
         console.log(piId);
              $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/admin/delete-proforma-invoice/'+piId, // Endpoint to handle delete operation
            type: 'get',
            success: function(response) {
                // Handle success response
                console.log(response.message);
            },
            error: function(xhr, status, error) {
                // Handle error response
                console.error(xhr);
            }
        });
    });
    $(document).on('click', '.pi-cancel-btn', function (e) {
        var piId = $('#generate_pi_modal .pi_submit_form').attr('id');
         console.log(piId);
              $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/admin/delete-proforma-invoice/'+piId, // Endpoint to handle delete operation
            type: 'get',
            success: function(response) {
                // Handle success response
                console.log(response.message);
            },
            error: function(xhr, status, error) {
                // Handle error response
                console.error(xhr);
            }
        });
    });
    //Generate PI button click 
    $(document).on("click", ".add-po-pi-item", function () {
        var target = $(this).parent().prev('.append_po_pi_section');
        
        target.append($('.div-to-clone').clone());
        target.find('.pi-progress-div').removeClass('div-to-clone').removeClass('d-none');

    });
    //Generate PI button click 
    $(document).on("click", ".pi_generate_btn", function () {
        var url = $(this).data('url');
        //$('#generate_pi_modal').hide();
        // Check if the input value is not blank
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'get',
                url: url,
                success: function (response) {
                    console.log(response.data);
                    if (response.success) {
                        $('#generate_pi_modal .modal-content').html(response.html);
                        initializeTooltips();
                    }
                },
                error: function (xhr) {
                    console.log(xhr);
                    Toast.fire({
                        icon: 'error',
                        title: "Something went wrong"
                    })
                }
            });
        
    });
    $(document).on("click", ".confirm_pi", function (e) {
        e.preventDefault();
        $(document).find("span.text-danger").remove();
        var button = $(this); // Reference to the clicked button
        var form = $('.pi_submit_form');
        var url = button.data('url');
        var form_data = form.serialize();
        // var form_data = new FormData(form[0]);
        button.prop('disabled', true); // Disable the button
        //console.log(form_data);

        // Find the .single-rfq-po-pi button
        var singleRfqPoPi = $('.single-rfq-po-pi');
        var singleRfqPoPiId ='';
        if (singleRfqPoPi.find('.arrow-toggle').length > 0) {
            singleRfqPoPiId = singleRfqPoPi.attr('id'); 
        }
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: url,
            data: form_data,
            success: function (response) {
                //console.log(response.pdf);
                if (response.success) {
                    if(response.type == 'preview'){
                        // Decode base64 PDF content
                        var pdfData = atob(response.pdf);

                        // Convert binary string to ArrayBuffer
                        var dataArray = new Uint8Array(pdfData.length);
                        for (var i = 0; i < pdfData.length; i++) {
                            dataArray[i] = pdfData.charCodeAt(i);
                        }
                        var pdfArray = new Uint8Array(dataArray);

                        // Create blob object
                        var blob = new Blob([pdfArray], { type: 'application/pdf' });
                        var url = URL.createObjectURL(blob);
                        window.open(url, '_blank');
                    }
                    else if(response.type == 'save'){
                        
                        Toast.fire({
                            icon: 'success',
                            title: response.message
                        })
                        sessionStorage.setItem('singleRfqPoPiId', singleRfqPoPiId); // Store the singleRfqPoPiId to session
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    }
                    else if(response.type == 'reverted'){
                        
                        Toast.fire({
                            icon: 'success',
                            title: response.message
                        })
                        sessionStorage.setItem('singleRfqPoPiId', singleRfqPoPiId); // Store the singleRfqPoPiId to session
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    }

                }
                else if (response.validation_error) {
                    console.log(response.validation_error);
                    var keys = "";
                    // Dispose of previously initialized tooltips
                    //$('[data-toggle="tooltip"]').tooltip('dispose');
                    $.each(response.validation_error, function (field_name, error) {
                       // $(document).find('[name=' + field_name + ']').after('<span class="text-strong text-danger">' + error + '</span>');
                        keys = field_name.split('.');
                        if (keys.length > 1) {
                            //$(document).find('[name="' + keys[0] + "[" + keys[1] + "]" + "[" + keys[2] + "]" + "" + '"]').after('<span class="text-strong text-danger">' + error + '</span>');
                            var multi_layer_field = $(document).find('[name="' + keys[0] + "[" + keys[1] + "]" + "[" + keys[2] + "]" + "" + '"]');
                            //console.log(multi_layer_field);
                            multi_layer_field.attr( { "data-toggle":"tooltip", title:error } );
                            multi_layer_field.tooltip({
                                template: '<div class="tooltip custom-tooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>',
                                boundary: 'window',
                                placement: 'top',
                            }).tooltip('show');
                        }
                        else {
                            //$(document).find('[name=' + field_name + ']').after('<span class="text-strong text-danger">' + error + '</span>');
                            
                            var field = $(document).find('[name=' + field_name + ']');
                            field.attr( { "data-toggle":"tooltip", title:error });
                            field.tooltip({
                                template: '<div class="tooltip custom-tooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>',
                                boundary: 'window',
                                placement: 'top',
                            }).tooltip('show');
                        }
                    })
                  
                }
                else {
                    Toast.fire({
                        icon: 'error',
                        title: response.message
                    })
                }

            },
            error: function (xhr) {
                console.log(xhr);
                Toast.fire({
                    icon: 'error',
                    title: "Something went wrong"
                })
            },
            complete: function () {
                // Enable the button in the complete callback
                button.prop('disabled', false);
            }
        });
    });
    $(document).on("click", ".po_view_btn", function (e) {
        e.preventDefault();
        var po_id = $(this).data('id');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'get',
            url: '/admin/approve-reject-po/'+po_id,
            success: function (response) {
                //console.log(response.pdf);
                if (response.success) {

                    // Decode base64 PDF content
                    var pdfData = atob(response.pdf);

                    // Convert binary string to ArrayBuffer
                    var dataArray = new Uint8Array(pdfData.length);
                    for (var i = 0; i < pdfData.length; i++) {
                        dataArray[i] = pdfData.charCodeAt(i);
                    }
                    var pdfArray = new Uint8Array(dataArray);

                    // Create blob object
                    var blob = new Blob([pdfArray], { type: 'application/pdf' });
                    var url = URL.createObjectURL(blob);

                    // Set the source attribute of the iframe using jQuery
                    $('#pdfViewer').attr('src', url);
                    $('#po_id_for_approve_reject').val(po_id);
                    
                }
                else {
                    Toast.fire({
                        icon: 'error',
                        title: response.message
                    })
                }

            },
            error: function (xhr) {
                console.log(xhr);
                Toast.fire({
                    icon: 'error',
                    title: "Something went wrong"
                })
            },
            complete: function () {
                
            }
        });
    });
    $(document).on("click", ".approve_reject_po", function (e) {
        e.preventDefault();
        $(document).find("span.text-danger").remove();
        var button = $(this); // Reference to the clicked button
        $('#status_for_approve_reject_po').val($(this).data('action'));
        var form = $('.approve_reject_po_form');
        var url = '/admin/approve-reject-po';
        var form_data = form.serialize();
        // var form_data = new FormData(form[0]);
        button.prop('disabled', true); // Disable the button
        //console.log(form_data);

        // Find the .single-rfq-po-pi button
        var singleRfqPoPi = $('.single-rfq-po-pi');
        var singleRfqPoPiId ='';
        if (singleRfqPoPi.find('.arrow-toggle').length > 0) {
            singleRfqPoPiId = singleRfqPoPi.attr('id'); 
        }
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: url,
            data: form_data,
            success: function (response) {
                console.log(response.data);
                if (response.success) {
                    Toast.fire({
                        icon: 'success',
                        title: response.message
                    })
                    sessionStorage.setItem('singleRfqPoPiId', singleRfqPoPiId); // Store the singleRfqPoPiId to session
                    setTimeout(() => {
                        location.reload();
                    }, 1500);

                }
                else if (response.validation_error) {
                    console.log(response.validation_error);
                    $.each(response.validation_error, function (field_name, error) {
                        var field = $(document).find('.view_po_modal [name=' + field_name + ']');
                        field.tooltip('dispose');
                        field.attr( { "data-toggle":"tooltip", title:error });
                        field.tooltip({
                            template: '<div class="tooltip custom-tooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>',
                            boundary: 'window',
                            placement: 'top',
                        }).tooltip('show');
                    })
                  
                }
                else {
                    Toast.fire({
                        icon: 'error',
                        title: response.message
                    })
                }

            },
            error: function (xhr) {
                console.log(xhr);
                Toast.fire({
                    icon: 'error',
                    title: "Something went wrong"
                })
            },
            complete: function () {
                // Enable the button in the complete callback
                button.prop('disabled', false);
            }
        });
    });
    $(document).on("click", ".pi_view_btn", function (e) {
        e.preventDefault();
        var pi_id = $(this).data('id');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'get',
            url: '/admin/approve-reject-pi/'+pi_id,
            success: function (response) {
                //console.log(response.pdf);
                if (response.success) {

                    // Decode base64 PDF content
                    var pdfData = atob(response.pdf);

                    // Convert binary string to ArrayBuffer
                    var dataArray = new Uint8Array(pdfData.length);
                    for (var i = 0; i < pdfData.length; i++) {
                        dataArray[i] = pdfData.charCodeAt(i);
                    }
                    var pdfArray = new Uint8Array(dataArray);

                    // Create blob object
                    var blob = new Blob([pdfArray], { type: 'application/pdf' });
                    var url = URL.createObjectURL(blob);

                    // Set the source attribute of the iframe using jQuery
                    $('#piPdfViewer').attr('src', url);
                    $('#pi_id_for_approve_reject').val(pi_id);
                    
                }
                else {
                    Toast.fire({
                        icon: 'error',
                        title: response.message
                    })
                }

            },
            error: function (xhr) {
                console.log(xhr);
                Toast.fire({
                    icon: 'error',
                    title: "Something went wrong"
                })
            },
            complete: function () {
                
            }
        });
    });
    $(document).on("click", ".approve_reject_pi", function (e) {
        e.preventDefault();
        $(document).find("span.text-danger").remove();
        var button = $(this); // Reference to the clicked button
        $('#status_for_approve_reject_pi').val($(this).data('action'));
        var form = $('.approve_reject_pi_form');
        var url = '/admin/approve-reject-pi';
        var form_data = form.serialize();
        // var form_data = new FormData(form[0]);
        button.prop('disabled', true); // Disable the button
        //console.log(form_data);

        // Find the .single-rfq-po-pi button
        var singleRfqPoPi = $('.single-rfq-po-pi');
        var singleRfqPoPiId ='';
        if (singleRfqPoPi.find('.arrow-toggle').length > 0) {
            singleRfqPoPiId = singleRfqPoPi.attr('id'); 
        }
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: url,
            data: form_data,
            success: function (response) {
                //console.log(response.data);
                if (response.success) {
                    Toast.fire({
                        icon: 'success',
                        title: response.message
                    })
                    sessionStorage.setItem('singleRfqPoPiId', singleRfqPoPiId); // Store the singleRfqPoPiId to session
                    setTimeout(() => {
                        location.reload();
                    }, 1500);

                }
                else if (response.validation_error) {
                    console.log(response.validation_error);
                    $.each(response.validation_error, function (field_name, error) {
                        var field = $(document).find('.view_pi_modal [name=' + field_name + ']');
                        // Remove any existing tooltip
                        field.tooltip('dispose');
                        field.attr( { "data-toggle":"tooltip", title:error });
                        field.tooltip({
                            template: '<div class="tooltip custom-tooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>',
                            boundary: 'window',
                            placement: 'top',
                        }).tooltip('show');
                    })
                  
                }
                else {
                    Toast.fire({
                        icon: 'error',
                        title: response.message
                    })
                }

            },
            error: function (xhr) {
                console.log(xhr);
                Toast.fire({
                    icon: 'error',
                    title: "Something went wrong"
                })
            },
            complete: function () {
                // Enable the button in the complete callback
                button.prop('disabled', false);
            }
        });
    });

    
    // set po & pi id button click
    $(document).on("click", ".lc_app_generate_btn", function () {
        // Get the value of data-id attribute
        var poId = $(this).data('poid');
        var piId = $(this).data('piid');
        
        // Trigger click event based on the data-id value
        $('.lc_app_submit_form #po_id ').val(poId);
        $('.lc_app_submit_form #pi_id ').val(piId);

    });

    // generate doc modal
    $(document).on("click", ".generate_del_doc_btn", function () {
        
        var url = $(this).data('url');
        var poId = $(this).data('poid');
        var piId = $(this).data('piid');
       
        
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'get',
            url: url,
            success: function (response) {
                console.log(response.data);
                if (response.success) {
                    $('#generate_del_docs_modal .modal-content').html(response.html);
                    initializeTooltips();
                }
            },
            error: function (xhr) {
                console.log(xhr);
                Toast.fire({
                    icon: 'error',
                    title: "Something went wrong"
                })
            }
        });

        // Trigger click event based on the data-id value
        $('.post_del_docs_submit_form #po_id ').val(poId);
        $('.post_del_docs_submit_form #pi_id ').val(piId);

    });

    // try 

    // $(document).on("click", ".generate_del_doc_btn", function () {
    //     var url = $(this).data('url');
       
    //         $.ajax({
    //             headers: {
    //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //             },
    //             type: 'get',
    //             url: url,
    //             success: function (response) {
    //                 console.log(response.data);
    //                 if (response.success) {
    //                     $('#generate_pi_modal .modal-content').html(response.html);
    //                     initializeTooltips();
    //                 }
    //             },
    //             error: function (xhr) {
    //                 console.log(xhr);
    //                 Toast.fire({
    //                     icon: 'error',
    //                     title: "Something went wrong"
    //                 })
    //             }
    //         });
        
    // });

    // 

    
    $(document).on("click", ".view_doc_btn", function () {
        var url = $(this).data('url');
        var pi_id = $(this).data('pi_id');
        var set_no = $(this).data('set_no'); 
        // console.log(url);
        // console.log(pi_id);
        // console.log(set_no);
        // console.log(url + '/' + pi_id + '/' + set_no);
        $('.view_doc_modal .modal-content').html('');

        // Check if the input value is not blank
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'get',
                url: url,
                
                success: function (response) {
                    //console.log(response.data);
                    //console.log(response.html);
                    if (response.success) {
                        $('.view_doc_modal .modal-content').html(response.html);
                    }
                },
                error: function (xhr) {
                    console.log(xhr);
                    Toast.fire({
                        icon: 'error',
                        title: "Something went wrong"
                    })
                }
            });
        
    });
    
    // ggwp
    $(document).on("click", ".confirm_lc_app", function (e) {
        e.preventDefault();
        $(document).find("span.text-danger").remove();
        var button = $(this); // Reference to the clicked button
        var form = $('.lc_app_submit_form');
        var url = button.data('url');
        var form_data = form.serialize();
        // var form_data = new FormData(form[0]);
        button.prop('disabled', true); // Disable the button
        //console.log(form_data);

        // Find the .single-rfq-po-pi button
        var singleRfqPoPi = $('.single-rfq-po-pi');
        var singleRfqPoPiId ='';
        if (singleRfqPoPi.find('.arrow-toggle').length > 0) {
            singleRfqPoPiId = singleRfqPoPi.attr('id'); 
        }
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: url,
            data: form_data,
            success: function (response) {
                //console.log(response.pdf);
                if (response.success) {
                    if(response.type == 'preview'){
                        // Decode base64 PDF content
                        var pdfData = atob(response.pdf);

                        // Convert binary string to ArrayBuffer
                        var dataArray = new Uint8Array(pdfData.length);
                        for (var i = 0; i < pdfData.length; i++) {
                            dataArray[i] = pdfData.charCodeAt(i);
                        }
                        var pdfArray = new Uint8Array(dataArray);

                        // Create blob object
                        var blob = new Blob([pdfArray], { type: 'application/pdf' });
                        var url = URL.createObjectURL(blob);
                        window.open(url, '_blank');
                    }
                    else if(response.type == 'save'){
                        Toast.fire({
                            icon: 'success',
                            title: response.message
                        })
                        sessionStorage.setItem('singleRfqPoPiId', singleRfqPoPiId); // Store the singleRfqPoPiId to session
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    }
                    else if(response.type == 'reverted'){
                        Toast.fire({
                            icon: 'success',
                            title: response.message
                        })
                        sessionStorage.setItem('singleRfqPoPiId', singleRfqPoPiId); // Store the singleRfqPoPiId to session
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    }

                }
                else if (response.validation_error) {
                    console.log(response.validation_error);
                    var keys = "";
                    // Dispose of previously initialized tooltips
                    //$('[data-toggle="tooltip"]').tooltip('dispose');
                    $.each(response.validation_error, function (field_name, error) {
                       // $(document).find('[name=' + field_name + ']').after('<span class="text-strong text-danger">' + error + '</span>');
                        keys = field_name.split('.');
                        if (keys.length > 1) {
                            //$(document).find('[name="' + keys[0] + "[" + keys[1] + "]" + "[" + keys[2] + "]" + "" + '"]').after('<span class="text-strong text-danger">' + error + '</span>');
                            var multi_layer_field = $(document).find('[name="' + keys[0] + "[" + keys[1] + "]" + "[" + keys[2] + "]" + "" + '"]');
                            //console.log(multi_layer_field);
                            multi_layer_field.attr( { "data-toggle":"tooltip", title:error } );
                            multi_layer_field.tooltip({
                                template: '<div class="tooltip custom-tooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>',
                                boundary: 'window',
                                placement: 'top',
                            }).tooltip('show');
                        }
                        else {
                            //$(document).find('[name=' + field_name + ']').after('<span class="text-strong text-danger">' + error + '</span>');
                            
                            var field = $(document).find('[name=' + field_name + ']');
                            field.attr( { "data-toggle":"tooltip", title:error });
                            field.tooltip({
                                template: '<div class="tooltip custom-tooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>',
                                boundary: 'window',
                                placement: 'top',
                            }).tooltip('show');
                        }
                    })
                  
                }
                else {
                    Toast.fire({
                        icon: 'error',
                        title: response.message
                    })
                }

            },
            error: function (xhr) {
                console.log(xhr);
                Toast.fire({
                    icon: 'error',
                    title: "Something went wrong"
                })
            },
            complete: function () {
                // Enable the button in the complete callback
                button.prop('disabled', false);
            }
        });
    });

    // post_delivery 
    $(document).on("click", ".confirm_post_del_docs", function (e) {
        e.preventDefault();
        $(document).find("span.text-danger").remove();
        var button = $(this); // Reference to the clicked button
        var form = $('.post_del_docs_submit_form');
        var url = button.data('url');
        var form_data = form.serialize();
        // var form_data = new FormData(form[0]);
        button.prop('disabled', true); // Disable the button
        //console.log(form_data);

        // Find the .single-rfq-po-pi button
        var singleRfqPoPi = $('.single-rfq-po-pi');
        var singleRfqPoPiId ='';
        if (singleRfqPoPi.find('.arrow-toggle').length > 0) {
            singleRfqPoPiId = singleRfqPoPi.attr('id'); 
        }
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: url,
            data: form_data,
            success: function (response) {
                console.log(response.data);
                if (response.success) {
                        Toast.fire({
                            icon: 'success',
                            title: response.message
                        })
                        sessionStorage.setItem('singleRfqPoPiId', singleRfqPoPiId); // Store the singleRfqPoPiId to session
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    }
                else if (response.validation_error) {
                        console.log(response);
                        $.each(response.validation_error, function(field_name, error) {
                            var field = $(document).find('[name=' + field_name +
                                ']');
                            field.tooltip('dispose');
                            field.attr({
                                "data-toggle": "tooltip",
                                title: error
                            });
                            field.tooltip({
                                template: '<div class="tooltip custom-tooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>',
                                placement: 'top',
                            }).tooltip('show');
                        })
                    }
                else {
                    Toast.fire({
                        icon: 'error',
                        title: response.message
                    })
                }

            },
            error: function (xhr) {
                console.log(xhr);
                Toast.fire({
                    icon: 'error',
                    title: "Something went wrong"
                })
            },
            complete: function () {
                // Enable the button in the complete callback
                button.prop('disabled', false);
            }
        });
    });
    //
    $(document).on("click", ".pi-upload-btn", function (e) {
        e.preventDefault(); 
        var button = $(this).prev().prop('id');
        $("#"+button).trigger('click');

    });
    $(document).on("change", "input.signed_pi", function (e) {
        e.preventDefault(); // Prevent default form submission
       
        //var button = $(this).attr('id');
        //console.log(button);
        var form = $(this).closest('.pi-upload-form');
        var formData = new FormData(form[0]); // Create FormData object
        var url = form.attr('action'); // Get the form action URL

        // Find the .single-rfq-po-pi button
        var singleRfqPoPi = $('.single-rfq-po-pi');
        var singleRfqPoPiId ='';
        if (singleRfqPoPi.find('.arrow-toggle').length > 0) {
            singleRfqPoPiId = singleRfqPoPi.attr('id'); 
        }
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: url, // Replace 'url' with your actual URL
            data: formData, // Use FormData object
            processData: false, // Prevent jQuery from processing the data
            contentType: false, // Prevent jQuery from setting contentType
            success: function (response) {
                console.log(response.data);
                Toast.fire({
                    icon: 'success',
                    title: response.message
                });
                sessionStorage.setItem('singleRfqPoPiId', singleRfqPoPiId); // Store the singleRfqPoPiId to session
                setTimeout(() => {
                    location.reload();
                }, 1500);
            },
            error: function (xhr) {
                console.log(xhr);
                Toast.fire({
                    icon: 'error',
                    title: "Something went wrong"
                });
            }
        });
    });
    $(document).on("click", ".lc-upload-btn", function (e) {
        e.preventDefault(); 
        var button = $(this).prev().prop('id');
        $("#"+button).trigger('click');

    });
    $(document).on("change", "input.signed_lc", function (e) {
        e.preventDefault(); // Prevent default form submission
        
        var form = $(this).closest('.lc-upload-form'); // Get the form element
        var formData = new FormData(form[0]); // Create FormData object
        var url = form.attr('action'); // Get the form action URL

        // Find the .single-rfq-po-pi button
        var singleRfqPoPi = $('.single-rfq-po-pi');
        var singleRfqPoPiId ='';
        if (singleRfqPoPi.find('.arrow-toggle').length > 0) {
            singleRfqPoPiId = singleRfqPoPi.attr('id'); 
        }
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: url, // Replace 'url' with your actual URL
            data: formData, // Use FormData object
            processData: false, // Prevent jQuery from processing the data
            contentType: false, // Prevent jQuery from setting contentType
            success: function (response) {
                Toast.fire({
                    icon: 'success',
                    title: response.message
                });
                sessionStorage.setItem('singleRfqPoPiId', singleRfqPoPiId); // Store the singleRfqPoPiId to session
                setTimeout(() => {
                    location.reload();
                }, 1500);
            },
            error: function (xhr) {
                console.log(xhr);
                Toast.fire({
                    icon: 'error',
                    title: "Something went wrong"
                });
            }
        });
    });
    $(document).on("click", ".lc-acc-upload-btn", function (e) {
        e.preventDefault(); 
        var button = $(this).prev().prop('id');
        $("#"+button).trigger('click');

    });
    $(document).on("change", "input.lc_acceptance", function (e) {
        e.preventDefault(); // Prevent default form submission

        var form = $(this).closest('.lc-acceptance-upload-form'); // Get the form element
        var formData = new FormData(form[0]); // Create FormData object
        var url = form.attr('action'); // Get the form action URL

        // Find the .single-rfq-po-pi button
        var singleRfqPoPi = $('.single-rfq-po-pi');
        var singleRfqPoPiId ='';
        if (singleRfqPoPi.find('.arrow-toggle').length > 0) {
            singleRfqPoPiId = singleRfqPoPi.attr('id'); 
        }
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: url, // Replace 'url' with your actual URL
            data: formData, // Use FormData object
            processData: false, // Prevent jQuery from processing the data
            contentType: false, // Prevent jQuery from setting contentType
            success: function (response) {
                Toast.fire({
                    icon: 'success',
                    title: response.message
                });
                sessionStorage.setItem('singleRfqPoPiId', singleRfqPoPiId); // Store the singleRfqPoPiId to session
                setTimeout(() => {
                    location.reload();
                }, 1500);
            },
            error: function (xhr) {
                console.log(xhr);
                Toast.fire({
                    icon: 'error',
                    title: "Something went wrong"
                });
            }
        });
    });
    $(document).on("click", ".bid_details_section", function (e) {
        e.preventDefault();
        // var parent = $(this).parent('.single-bid-table');
        // $(this).parent().find(".bid_details_section").removeClass("active");
        $(this).toggleClass('active');
        var bid_id = $(this).attr('bid_id');
        var bidder_id = $(this).attr('bidder_id');
        if ($(document).find('[selected_bid_id=' + bid_id + ']').length > 0) {
            //console.log('selected bid id exists');
            $(document).find('[selected_bid_id=' + bid_id + ']').remove();
        }
        else {
            $('#bid_details_form').append(`<input type="hidden" selected_bid_id="` + bid_id + `" name="selected_bid_id[]" value="` + bid_id + `">`);

        }
    });

    
    // Next po button click
    $(document).on("click", ".next_po_btn", function () {
        // Get the value of data-id attribute
        var dataId = $(this).data('id');
        
        // Trigger click event based on the data-id value
        $('#' + dataId).click();

    });

    $(document).on("click", ".merchantApproveReject", function (e) {
        e.preventDefault();
        var data_id = $(this).attr('merchant_id');
        var merchant_admin_id = $(this).attr('merchant_admin_id');
        var data_admin_id = $(this).attr('updated_by');
        var status = $(this).attr("status");
       var msg = status == 'active' ? 'You want to approve this merchant?'
        : status == 'initiated' ? 'You want to set this merchant as incomplete profile?'
        : 'You want to restrict this merchant?';
        url = "/admin/update-merchant-status";
        //console.log(data_id + "  " + status + "  " + data_id);
        Swal.fire({
            customClass: {
                icon: 'mt-4'
            },
            title: 'Are you sure?',
            text: msg,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'post',
                    url: url,
                    data: { status: status, data_id: data_id, updated_by: data_admin_id, merchant_admin_id: merchant_admin_id },
                    success: function (response) {
                        Toast.fire({
                            icon: 'success',
                            title: response.success_message
                        })
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    },
                    error: function (xhr) {
                        console.log(xhr);
                        Toast.fire({
                            icon: 'error',
                            title: "Something went wrong"
                        })
                    }
                });
            }
        })
    });

    //Rating review form submit

    $(document).on("click", "#rating-review-btn", function (e) {
        e.preventDefault();
        var savedataTarget = $(this);
        var modal = $('#rating_modal');
        var form = $('.review-form');
        var url = form.attr('action');
        $(document).find("span.text-danger").remove();
        var form_data = form.serialize();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: url,
            data: form_data,
            success: function (response) {
                if (response.validation_error) {
                    console.log(response.validation_error);
                    var keys = "";
                    $.each(response.validation_error, function (field_name, error) {
                        // $(document).find('[name=' + field_name + ']').after('<span class="text-strong text-danger">' + error + '</span>');
                        keys = field_name.split('.');
                        if (keys.length > 1) {
                            $(document).find('[name="' + keys[0] + "[" + keys[1] + "]" + "[" + keys[2] + "]" + "" + '"]').parent().parent().after('<span class="text-strong text-danger">' + error + '</span>');
                        }
                        else {
                            $(document).find('[name=' + field_name + ']').parent().parent().after('<span class="text-strong text-danger">' + error + '</span>');
                        }

                    })

                }
                else if (response?.success) {
                    modal.hide();
                    Toast.fire({
                        icon: 'success',
                        title: response.message
                    })
                    setTimeout(() => {
                        location.reload();
                    }, 1500);

                }
                else if (!response?.success) {

                    Swal.fire({
                        customClass: {
                            icon: 'mt-4'
                        },
                        position: 'center',
                        icon: 'error',
                        title: response.message,
                        showConfirmButton: true,
                        // timer: 2000
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                }
            },
            error: function (xhr) {
                console.log(xhr);
                Toast.fire({
                    icon: 'error',
                    title: "Something went wrong"
                })
            }
        });
    });

    //Cancel subscription by merchant
    $(document).on("click", "#cancel_subscription", function (e) {
        e.preventDefault();
        var package_id = $(this).attr('package_id');
        url = "/admin/cancel-subcription";
        //console.log(data_id + "  " + status + "  " + data_id);
        Swal.fire({
            customClass: {
                icon: 'mt-4'
            },
            title: 'Are you sure?',
            text: 'You are going to cancel the subscription',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'post',
                    url: url,
                    data: { package_id: package_id },
                    success: function (response) {
                        Toast.fire({
                            icon: 'success',
                            title: response.message
                        })
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    },
                    error: function (xhr) {
                        console.log(xhr);
                        Toast.fire({
                            icon: 'error',
                            title: "Something went wrong"
                        })
                    }
                });
            }
        })
    });

    $(document).on("click", ".activate_full_member_btn", function (e) {
        e.preventDefault();
        var merchant_id = $(this).attr('merchant_id');
        //console.log('merchant_id : '+merchant_id);
        var url = '';
        if ($(this).attr('data-target') != '#activate_member_modal') {
        $('#industry_modal_body').append('<input type="hidden" name="merchant_id" value="'+merchant_id+'" > ');
            $(this).attr('data-target', '#activate_member_modal');
            $(this).trigger('click');
        }
    });

      // product-sugg (onboard)
      $(document).on("click", "#product_sugg_btn", function (e) {
        e.preventDefault();
        var form = $('.form_sugg_product');
        var url = form.attr('action');
        var modal = $('.form_modal');
        $(document).find("span.text-danger").remove();
        var form_data = form.serialize();
        //console.log(url);
        //console.log(form_data);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: url,
            data: form_data,
            success: function (response) {
                
                if (response.success_message) {
                    //console.log(response.success_message);
                    modal.hide();
                    Toast.fire({
                        icon: 'success',
                        title: response.success_message
                    })
                    setTimeout(() => {
                        location.reload();
                    }, 1500);

                }
                else if (response.error_message) {
                    console.log(response.error_message);
                    Swal.fire({
                        customClass: {
                            icon: 'mt-4'
                        },
                        position: 'center',
                        icon: 'error',
                        title: response.error_message,
                        showConfirmButton: true,
                        // timer: 2000
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                }
                else if (response.validation_error) {
                    console.log(response.validation_error);
                    $.each(response.validation_error, function (field_name, error) {
                        $(document).find('[name=' + field_name + ']').after('<span class="text-strong text-danger">' + error + '</span>')
                    })

                }
            },
            error: function (xhr) {
                console.log(xhr);
                Toast.fire({
                    icon: 'error',
                    title: "Something went wrong"
                })
            }
        });
    });
      // product-sugg (buyer)
      $(document).on("click", ".product-suggestion", function (e) {
        e.preventDefault();
        var form = $('.form_sugg_product');
        var url = form.attr('action');
        var modal = $('#sugg_pro_form_modal');
        $(document).find("span.text-danger").remove();
        var admin_id = form.find('#admin_id').val();
        var status = $(this).data('value');
        var form_data = form.serialize() + '&approved_by=' + admin_id + '&updated_by=' + admin_id + '&status=' + status;
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: url,
            data: form_data,
            success: function (response) {
                if (response.success_message) {
                    
                    Toast.fire({
                        icon: 'success',
                        title: response.success_message
                    })
                    setTimeout(() => {
                        modal.modal('hide');;
                    }, 1500);

                }
                else if (response.error_message) {

                    Swal.fire({
                        customClass: {
                            icon: 'mt-4'
                        },
                        position: 'center',
                        icon: 'error',
                        title: response.error_message,
                        showConfirmButton: true,
                        // timer: 2000
                    }).then((result) => {
                        if (result.isConfirmed) {
                            modal.modal('hide');
                        }
                    });
                }
                else if (response.validation_error) {
                    console.log(response.validation_error);
                    $.each(response.validation_error, function (field_name, error) {
                        $(document).find('[name=' + field_name + ']').after('<span class="text-strong text-danger">' + error + '</span>')
                    })

                }
            },
            error: function (xhr) {
                console.log(xhr);
                Toast.fire({
                    icon: 'error',
                    title: "Something went wrong"
                })
            }
        });
    });

    // company-profile-view (prevent tbody click to fire)
    $(document).on("click", ".company-profile-view", function (e) {
    // Prevent the tbody click event from firing
        e.stopPropagation();
    });

    //traubleshoot form submit
    $(document).on("click", "#problemBtn", function (e) {
        e.preventDefault();
        var form = $('.report_problem_form');
        var url = form.attr('action');
        var modal = $('.report_problem_modal');
        $(document).find("span.text-danger").remove();
        //var form_data = form.serialize() + '&updated_by=' + admin_id;
        var form_data = new FormData(form[0]);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: url,
            data: form_data,
            dataType: "JSON",
            processData: false,
            contentType: false,
            success: function (response) {
                //console.log(response);
                if (response.success_message) {
                    modal.hide();
                    Toast.fire({
                        icon: 'success',
                        title: response.success_message
                    })
                    setTimeout(() => {
                        location.reload();
                    }, 1500);

                }
                else if (response.error_message) {
                    console.log(response);
                    Swal.fire({
                        customClass: {
                            icon: 'mt-4'
                        },
                        position: 'center',
                        icon: 'error',
                        title: response.error_message,
                        showConfirmButton: true,
                        // timer: 2000
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                }
                else if (response.validation_error) {
                    console.log(response);
                    $.each(response.validation_error, function (field_name, error) {
                        $(document).find('[name=' + field_name + ']').after('<span class="text-strong text-danger">' + error + '</span>')
                    })

                }
            },
            error: function (xhr) {
                console.log(xhr);
                Toast.fire({
                    icon: 'error',
                    title: "Something went wrong"
                })
            }
        });
    });

    // Order no on blur event
    $(document).on("blur", '#rfq_order_no', function () {
        var orderNo = $(this).val();
        $('#order_form_modal #order_no').val(orderNo);
        var url = "/admin/check-order-no";
        // Check if the input value is not blank
        if (orderNo.trim() !== '') {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'get',
                url: url,
                data: { orderNo: orderNo },
                success: function (response) {
                    if (response.exists) {
                        console.log('Value exists in the database. Returning to the form.');
                       // $('#rfq_order_no').val(response.order_id);
                    } else {
                        console.log('Value does not exist in the database. Opening a modal.');
                        // Open order modal
                       // $('#order_form_modal').modal('show');
                        // Reset the modal state
                            $('#order_form_modal').modal('hide');
                            $('body').removeClass('modal-open');
                            $('.modal-backdrop').remove();

                            // Open order modal
                            $('#order_form_modal').modal('show');
                    }
                },
                error: function (xhr) {
                    console.log(xhr);
                    Toast.fire({
                        icon: 'error',
                        title: "Something went wrong"
                    })
                }
            });
        }
    });
    //Order creation add new buyer
    $('#buyer_id').on('change', function () {
        var selectedValue = $(this).val();
        if (selectedValue === 'addNew') {
             // Show the input field for adding a new record
        $('#newRecordInputDiv').show();
        $('#newRecordInputDiv').addClass('add-buyer-div');
        //$(this).selectpicker('refresh');
        } else {
            // Hide the input field if another option is selected
            $('#newRecordInputDiv').hide();
        }
    });
    //Add external buyer from order creation 
    $(document).on("click", "#addExtBuyer", function (e) {
        e.preventDefault();
        var form = $('.form');
        var url = '/admin/add-edit-buyer';
        var modal = $('.form_modal');
        $(document).find("span.text-danger").remove();
        var buyer_name = form.find('#newRecordInput').val();
        var admin_id = form.find('#admin_id').val();
        //var form_data = form.serialize() + '&updated_by=' + admin_id;
        // console.log(form);
        // console.log(url);
        // console.log(modal);
        var form_data = new FormData();
        form_data.append('name', buyer_name);
        form_data.append('updated_by', admin_id);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: url,
            data: form_data,
            dataType: "JSON",
            processData: false,
            contentType: false,
            success: function (response) {
                //console.log(response);
                if (response.success_message) {
                    //modal.hide();
                    Toast.fire({
                        icon: 'success',
                        title: response.success_message
                    })
                    setTimeout(() => {
                       // location.reload();
                    
                       // Hide the input field
                        $('#newRecordInputDiv').hide();
                       // Assuming the response contains the selectedValue
                        var selectedValue = response.data.id;
                        //console.log(selectedValue);

                        // Add a new option dynamically to the dropdown before the "Add New Record" option
                        $('#buyer_id option[value="addNew"]').before('<option value="' + response.data.id + '" selected>' + response.data.name + '</option>');

                        // Refresh the Selectpicker
                        $('#buyer_id').selectpicker('refresh');
                    }, 1500);

                }
                else if (response.error_message) {
                    console.log(response);
                    Swal.fire({
                        customClass: {
                            icon: 'mt-4'
                        },
                        position: 'center',
                        icon: 'error',
                        title: response.error_message,
                        showConfirmButton: true,
                        // timer: 2000
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                }
                else if (response.validation_error) {
                    console.log(response);
                    $.each(response.validation_error, function (field_name, error) {
                        //$(document).find('[name=' + field_name + ']').after('<span class="text-strong text-danger">' + error + '</span>')
                        var field = $(document).find('[name=' + field_name + ']');
                            field.attr( { "data-toggle":"tooltip", title:error });
                            field.tooltip({
                                template: '<div class="tooltip custom-tooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>',
                                placement: 'top',
                            }).tooltip('show');
                    })

                }
            },
            error: function (xhr) {
                console.log(xhr);
                Toast.fire({
                    icon: 'error',
                    title: "Something went wrong"
                })
            }
        });
    });

    $('#merchandiser .march-checkbox').on('change', function () {
        // Check if the "Add Merchandiser" option is selected
        if ($(this).val() === 'addNew') {
            // Hide the newMerchInputDiv initially
            $('#newMerchInputDiv').hide();
                
            // Show the newMerchInputDiv when "Add Merchandiser" is clicked
            $('#addMerchandiser').on('click', function () {
                $('#newMerchInputDiv').toggle();
            });
            $('#newRecordInputDiv').addClass('new-merchantiser-div');
        } else {
            // Hide the newMerchInputDiv
            $('#newMerchInputDiv').hide();
        }
    });

    $(".addNewMerchandiser").click(function (e) {
        e.preventDefault();
        $("#newMerchInputDiv").toggle();
        $('#newRecordInputDiv').addClass('new-merchantiser-div');
    });

    $("#closeNewMerchInputDiv").click(function () {
        $("#newMerchInputDiv").hide();
    })

    //Add order creation 
    $(document).on("click", "#addOrderBtn", function (e) {
        e.preventDefault();
        var form = $('#order_form_modal .form');
        var url = form.attr('action');
        var modal = $('#order_form_modal');
        $(document).find("span.text-danger").remove();
        var merchant_id = form.find('#merchant_id').val();
        var admin_id = form.find('#admin_id').val();
        //var form_data = form.serialize() + '&updated_by=' + admin_id;
        // console.log(form);
        // console.log(url);
        // console.log(modal);
        var form_data = new FormData(form[0]);
        form_data.append('merchant_id', merchant_id);
        form_data.append('updated_by', admin_id);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: url,
            data: form_data,
            dataType: "JSON",
            processData: false,
            contentType: false,
            success: function (response) {
                //console.log(response);
                if (response.success_message) {
                    modal.hide();
                    //$('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                    Toast.fire({
                        icon: 'success',
                        title: response.success_message
                    })
                    setTimeout(() => {
                       // location.reload();
                       // console.log(selectedValue);
                    }, 1500);

                }
                else if (response.error_message) {
                    console.log(response);
                    Swal.fire({
                        customClass: {
                            icon: 'mt-4'
                        },
                        position: 'center',
                        icon: 'error',
                        title: response.error_message,
                        showConfirmButton: true,
                        // timer: 2000
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                }
                else if (response.validation_error) {
                    console.log(response);
                    $.each(response.validation_error, function (field_name, error) {
                        //$(document).find('[name=' + field_name + ']').after('<span class="text-strong text-danger">' + error + '</span>')
                        var field = $(document).find('[name=' + field_name + ']');
                            field.attr( { "data-toggle":"tooltip", title:error });
                            field.tooltip({
                                template: '<div class="tooltip custom-tooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>',
                                placement: 'top',
                            }).tooltip('show');
                    })

                }
            },
            error: function (xhr) {
                console.log(xhr);
                Toast.fire({
                    icon: 'error',
                    title: "Something went wrong"
                })
            }
        });
    });
       // Change Financial details
       $(document).on("change", ".change_bank", function (e) {
        e.preventDefault();
        var id = $(this).find(":selected").val();
        url = "/admin/finincial-details/"+id;
        $('.finincial-detail input').val('');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'get',
            url: url,
            success: function (response) {
                //console.log(response.data);
                // Iterate over each key in the response data
                $.each(response.data, function(key, value) {
                    // Find input field within the .finincial-details class by name attribute and set its value
                    $('.finincial-details [name="' + key + '"]').val(value);
                });
            },
            error: function (xhr) {
                console.log(xhr);
                Toast.fire({
                    icon: 'error',
                    title: "Something went wrong"
                })
            }
        });
    });
     //Single RFQ PO & PI 
     $(document).on("click", ".single-rfq-po-pi", function (e) {
        e.preventDefault();
        $('.order-log').find('.show-po-pi').html('');
        $(this).find('img').toggleClass('arrow-toggle');
        $('.order-log .single-rfq-po-pi img').not($(this).find('img')).removeClass('arrow-toggle');
        
        var target_class = $(this).closest('tr').next('.show-po-pi');
        var url = $(this).attr("data-url");

        if ($(this).find('img').hasClass('arrow-toggle')) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'get',
                url: url,
                success: function (response) {
                   console.log(response.data);
                    target_class.html(response.html);
                    initializeTooltips(); //call tooltip.
                },
                error: function (xhr) {
                    console.log(xhr);
                    Toast.fire({
                        icon: 'error',
                        title: "Something went wrong"
                    })
                }
            });
        } else {

            target_class.html('');
        }
    });
    //Followup datas for single merchant 
    $(document).on("click", ".followup-btn", function (e) {
        e.preventDefault();
        var modal = $('#followup_form_modal');
        modal.find('#followup_notes').html('');
        var company_name = $(this).closest('tr').find('.company-name').html();
        var url = $(this).attr("data-url");
        var merchant_id = $(this).attr("data-id");
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'get',
            url: url,
            success: function (response) {
             
               modal.find('#followup_notes').html(response.html);
                $('.modal-title').html(company_name);
                modal.find('#merchant_id').val(merchant_id);
                modal.find('#latest_bid').html(response.latestBid);
                modal.find('#latest_rfq').html(response.latestRfq);
            },
            error: function (xhr) {
                console.log(xhr);
                Toast.fire({
                    icon: 'error',
                    title: "Something went wrong"
                })
            }
        });
    });
    //Add Followup
    $(document).on("click", "#addFollowupBtn", function (e) {
        e.preventDefault();
        var form = $('#followup_form_modal .form');
        var url = form.attr('action');
        var modal = $('#followup_form_modal');
        $(document).find("span.text-danger").remove();
        var form_data = new FormData(form[0]);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: url,
            data: form_data,
            dataType: "JSON",
            processData: false,
            contentType: false,
            success: function (response) {
                //console.log(response);
                if (response.success_message) {
                    modal.hide();
                    Toast.fire({
                        icon: 'success',
                        title: response.success_message
                    })
                    setTimeout(() => {
                        location.reload();
                    }, 1500);

                }
                else if (response.error_message) {
                    console.log(response);
                    Swal.fire({
                        customClass: {
                            icon: 'mt-4'
                        },
                        position: 'center',
                        icon: 'error',
                        title: response.error_message,
                        showConfirmButton: true,
                        // timer: 2000
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                }
                else if (response.validation_error) {
                    console.log(response);
                    $.each(response.validation_error, function (field_name, error) {
                        //$(document).find('[name=' + field_name + ']').after('<span class="text-strong text-danger">' + error + '</span>')
                        var field = $(document).find('[name=' + field_name + ']');
                            field.attr( { "data-toggle":"tooltip", title:error });
                            field.tooltip({
                                template: '<div class="tooltip custom-tooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>',
                                placement: 'top',
                            }).tooltip('show');
                    })

                }
            },
            error: function (xhr) {
                console.log(xhr);
                Toast.fire({
                    icon: 'error',
                    title: "Something went wrong"
                })
            }
        });
    });

    // Change ACM 
    $(document).on("change", ".change_acm", function (e) {
        e.preventDefault();
        var data_id = $(this).attr('data_id');
        var data_admin_id = $(this).attr('data_admin_id');
        var data_acm_id = $(this).find(":selected").val();
        url = "/admin/csd/update-acm";
        Swal.fire({
            customClass: {
                icon: 'mt-4'
            },
            title: 'Are you sure?',
            text: "You want to update?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'post',
                    url: url,
                    data: { data_acm_id: data_acm_id, data_id: data_id, updated_by: data_admin_id },
                    success: function (response) {
                        Toast.fire({
                            icon: 'success',
                            title: response.success_message
                        })
                        setTimeout(() => {
                            //location.reload();
                        }, 1500);
                    },
                    error: function (xhr) {
                        console.log(xhr);
                        Toast.fire({
                            icon: 'error',
                            title: "Something went wrong"
                        })
                    }
                });
            }
        })
    });

    // whatsapp notification active inactive
    $(document).on("change", ".change_whatsapp_noti_status", function (e) {
        e.preventDefault();
        var data_id = $(this).attr('data_id');
        var data_admin_id = $(this).attr('data_admin_id');
        var status = $(this).find(":selected").val();
      
        url = "/admin/update-merchant-whatsapp-status";
        Swal.fire({
            customClass: {
                icon: 'mt-4'
            },
            title: 'Are you sure?',
            text: "You want to update?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'post',
                    url: url,
                    data: { status: status, data_id: data_id, updated_by: data_admin_id },
                    success: function (response) {
                        Toast.fire({
                            icon: 'success',
                            title: response.success_message
                        })
                        setTimeout(() => {
                            //location.reload();
                        }, 1500);
                    },
                    error: function (xhr) {
                        console.log(xhr);
                        Toast.fire({
                            icon: 'error',
                            title: "Something went wrong"
                        })
                    }
                });
            }
        })
    });

     //Add Followup
     $(document).on("click", "#addTargetBtn", function (e) {
        e.preventDefault();
        var form = $('#target_form_modal .form');
        var url = form.attr('action');
        var modal = $('#target_form_modal');
        $(document).find("span.text-danger").remove();
        var form_data = new FormData(form[0]);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: url,
            data: form_data,
            dataType: "JSON",
            processData: false,
            contentType: false,
            success: function (response) {
                //console.log(response);
                if (response.success_message) {
                    modal.hide();
                    Toast.fire({
                        icon: 'success',
                        title: response.success_message
                    })
                    setTimeout(() => {
                        location.reload();
                    }, 1500);

                }
                else if (response.error_message) {
                    console.log(response);
                    Swal.fire({
                        customClass: {
                            icon: 'mt-4'
                        },
                        position: 'center',
                        icon: 'error',
                        title: response.error_message,
                        showConfirmButton: true,
                        // timer: 2000
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                }
                else if (response.validation_error) {
                    console.log(response);
                    $.each(response.validation_error, function (field_name, error) {
                        //$(document).find('[name=' + field_name + ']').after('<span class="text-strong text-danger">' + error + '</span>')
                        var field = $(document).find('[name=' + field_name + ']');
                            field.attr( { "data-toggle":"tooltip", title:error });
                            field.tooltip({
                                template: '<div class="tooltip custom-tooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>',
                                placement: 'top',
                            }).tooltip('show');
                    })

                }
            },
            error: function (xhr) {
                console.log(xhr);
                Toast.fire({
                    icon: 'error',
                    title: "Something went wrong"
                })
            }
        });
    });

    //Single Buyer Industry wise Analytics 
    $(document).on("click", ".single-buyer-industry-wise-analytics", function (e) {
        e.preventDefault();
        $('.buyer-cs-table .show-industry-wise-analytics').remove();
        $(this).find('img').toggleClass('arrow-toggle');
        $('.buyer-cs-table .single-buyer-industry-wise-analytics img').not($(this).find('img')).removeClass('arrow-toggle');
        
        var target_class = $(this).closest('tr');
        var url = $(this).attr("data-url");

        if ($(this).find('img').hasClass('arrow-toggle')) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'get',
                url: url,
                success: function (response) {
                   //console.log(response.html);
                   //console.log(target_class);
                    target_class.after(response.html);
                },
                error: function (xhr) {
                    console.log(xhr);
                    Toast.fire({
                        icon: 'error',
                        title: "Something went wrong"
                    })
                }
            });
        } else {

            target_class.next('.show-industry-wise-analytics').remove();
        }
    });
    //accept reject toc 
    $(document).on('click', '.change_value_btn', function(e) {
        e.preventDefault();
        var fieldName = $(this).data("name");
        var fieldValue = $(this).data("value");
        var fieldType = $(this).data("type");
        if(fieldType == 'checkbox'){
            $('input[name="'+fieldName+'"][value="'+fieldValue+'"]').prop('checked', true);
        }else if(fieldType == 'dropdown'){
            $('select[name="'+fieldName+'"]').val(fieldValue);
        }else if(fieldType == 'text'){
            $('input[name="'+fieldName+'"]').val(fieldValue);
        }
        
        // Get the paragraph element
        var paragraph = $(this).closest('.acc-rej-toc').find('p');
        
        // Check if the clicked button is for accepting or rejecting
        if ($(this).hasClass('accept_change')) {
            // Change text color to green
            paragraph.css('color', '#4D7CFF');
        } else if ($(this).hasClass('reject_change')) {
            // Change text color to red
            paragraph.css('color', '#EF717D');
        }
    });
    // Master Lc on blur event
    $(document).on("blur", '.export_lc_no', function () {
        var exportLcNo = $(this).val();
        var url = "/admin/check-export-lc-no";
        $('.lc-app input').val('');
        // Check if the input value is not blank
        if (exportLcNo.trim() !== '') {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'get',
                url: url,
                data: { export_lc_no: exportLcNo },
                success: function (response) {
                    if (response.exists) {
                        //console.log(response.data);
                        $.each(response.data, function(key, value) {
                            var field = $('.lc-app [name="' + key + '"]');
                            if(key = 'export_lc_no'){}
                            // Check if it's a select element
                            if (field.is('select')) {
                                // Iterate over each option in the select
                                field.find('option').each(function() {
                                    if ($(this).val() == value) {
                                        $(this).prop('selected', true);
                                    }
                                });
                            } else if (field.attr('type') === 'date') {
                                // Convert the date value to a format compatible with the date input field (YYYY-MM-DD)
                                var formattedDate = new Date(value).toISOString().split('T')[0];
                                field.val(formattedDate);
                            } else {
                                field.val(value);
                            }
                        });
                    } else {
                        $('.export_lc_no').val(exportLcNo);
                        //console.log(exportLcNo);
                    }
                },
                error: function (xhr) {
                    console.log(xhr);
                    Toast.fire({
                        icon: 'error',
                        title: "Something went wrong"
                    })
                }
            });
        }
    });

    $('#searchInput').on('keyup', function() {
        var searchText = $(this).val().toLowerCase();
        //console.log(searchText);
        $('.card').each(function() {
            var orderNo = $(this).find('.order-title').text().toLowerCase();
            var rfqNo = $(this).find('.rfq-title').text().toLowerCase();
            if (orderNo.includes(searchText) || rfqNo.includes(searchText)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    //check subscribtion monthly count
    $(document).on("click", ".check-subscribtion", function (e) {
        e.preventDefault();
        var tag = $(this).data("tag");
        var url = 'subscription/count-check/'+tag;
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'get',
            url: url,
            success: function (response) {
               console.log(response);
               var url = 'subscription/plans'
               if (!response.success) {
                if(response.data != 1){
                    Swal.fire({
                        customClass: {
                            icon: 'mt-4',
                            popup: 'custom-swal-modal'
                        },
                        title: response.message,
                        //text: response.message,
                        icon: 'warning',
                        showCancelButton: true,
                        cancelButtonText: 'OK',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Upgrade Plan',
                        reverseButtons: true,
                        }).then((result) => {
                            if (result.value) {
                                window.location.href=url;
                            }else{
                                location.reload();
                            } 
                        })
                   }
                }
                
               
               
            },
            error: function (xhr) {
                console.log(xhr);
                Toast.fire({
                    icon: 'error',
                    title: "Something went wrong"
                })
            }
        });
    });
});