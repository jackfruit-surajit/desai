/* global full_path */

// window height //
var currentRequest = null;

function ajaxindicatorstart() {
    if (jQuery('body').find('#resultLoading').attr('id') != 'resultLoading') {
        jQuery('body').append('<div id="resultLoading" style="display:none"><div><i style="font-size: 46px;color: #4179eb;" class="fa fa-spinner fa-spin fa-2x fa-fw" aria-hidden="true"></i></div><div class="bg"></div></div>');
    }
    jQuery('#resultLoading').css({
        'width': '100%',
        'height': '100%',
        'position': 'fixed',
        'z-index': '10000000',
        'top': '0',
        'left': '0',
        'right': '0',
        'bottom': '0',
        'margin': 'auto'
    });
    jQuery('#resultLoading .bg').css({
        'background': '#ffffff',
        'opacity': '0.8',
        'width': '100%',
        'height': '100%',
        'position': 'absolute',
        'top': '0'
    });
    jQuery('#resultLoading>div:first').css({
        'width': '250px',
        'height': '75px',
        'text-align': 'center',
        'position': 'fixed',
        'top': '0',
        'left': '0',
        'right': '0',
        'bottom': '0',
        'margin': 'auto',
        'font-size': '16px',
        'z-index': '10',
        'color': '#ffffff'
    });
    jQuery('#resultLoading .bg').height('100%');
    jQuery('#resultLoading').fadeIn(300);
    jQuery('body').css('cursor', 'wait');
}

function ajaxindicatorstop() {
    jQuery('#resultLoading .bg').height('100%');
    jQuery('#resultLoading').fadeOut(300);
    jQuery('body').css('cursor', 'default');
}

function success_msg(msg) {
    swal({
      title:"Success...",
      type:"success",
      text: msg,
      timer: 2000,
      showCloseButton: true
    });
}
function  error_msg(msg) {
  swal({
      title:"Oops !",
      type:"error",
      text: msg,
      timer: 2000,
      showCloseButton: true
    });
}


$(document).ready(function () {
    $('.select2').select2();
    $(document).on('change','#state',function () {
        var link = $(this).find(':selected').attr('data-href');
        if(link != "")
        {
          $('#city').load(link);
          $('#city').prop('disabled',false);
          $('#school').empty();
          $("#school"). prepend("<option value=' ' selected='selected'>Select School</option>");
          $('#school').prop('disabled',true);
        }
    });
    $(document).on('change','#city',function () {
        var link = $(this).find(':selected').attr('data-href');
        if(link != "")
        {
          $('#school').load(link);
          $('#school').prop('disabled',false);
        }
    });
    $(document).on('change','#category',function () {
        var link = $(this).find(':selected').attr('data-href');
        if(link != "")
        {
          $('#subcategory').load(link);
          $('#subcategory').prop('disabled',false);
        }
    });
    // $(document).on('change','.size-guide-select',function () {
    //     var product_id = $(this).find(':selected').attr('data-product_id');
    //     var value = $(this).value;
    //     alert(value);
    //     if(product_id != "")
    //     {
    //     //   $('#subcategory').load(link);
    //     //   $('#subcategory').prop('disabled',false);
    //     }
    // });
    
    $("#forgot_password_by").change(function() {
        var value = this.value;
        $('#forget_type').val(value);
        if(value=='by_email') {
            $("#email-section").show();
            $("#phone-section").hide();
        }
        else
        {
            $("#phone-section").show();
            $("#email-section").hide();
    
        }
    });
    $('.register-form').submit(function (event) {
        event.preventDefault();
        ajaxindicatorstart();
        $('.help-block').html('').closest('.form-group').removeClass('has-error');
        var url = $(this).attr('action');
        var csrf_token = $('input[name=_token]').val();
        var data = new FormData($(this)[0]);
        $.ajax({
            url: url,
            headers: {'X-CSRF-TOKEN': csrf_token},
            type: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            data: data,
            success: function (resp) {

                if(resp.status == 'success'){
                    ajaxindicatorstop();
                    $('.register-form')[0].reset();
                    success_msg(resp.msg);
                    setTimeout(() => {
                        window.location.href = resp.url;
                    }, 1000);
                }
                if(resp.status == 'error'){
                    ajaxindicatorstop();
                    $('.register-form')[0].reset();
                    error_msg(resp.msg);
                }
                
            },
            error: function (resp) {
                $.each(resp.responseJSON.errors, function (key, val) {
                    $("#error-" + key).html(val[0]).closest('.form-group').addClass('has-error');
                });
                ajaxindicatorstop();
            }
        })
    });
    
    $('#active-account-form').submit(function (event) {
        event.preventDefault();
        ajaxindicatorstart();
        $('.help-block').html('').closest('.form-group').removeClass('has-error');
        var url = $(this).attr('action');
        var csrf_token = $('input[name=_token]').val();
        var data = new FormData($(this)[0]);
        $.ajax({
            url: url,
            headers: {'X-CSRF-TOKEN': csrf_token},
            type: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            data: data,
            success: function (resp) {

                ajaxindicatorstop();
                success_msg(resp.msg);
                $('#active-account-form')[0].reset();
                setTimeout(() => {
                    
                    window.location.href = resp.url;
                }, 1000);
                
            },
            error: function (resp) {
                $.each(resp.responseJSON.errors, function (key, val) {
                    $("#error-" + key).html(val[0]).closest('.form-group').addClass('has-error');
                });
                ajaxindicatorstop();
            }
        })
    });


    $('.login-form').submit(function (event) {
        event.preventDefault();
        ajaxindicatorstart();
        $('.help-block').html('').closest('.form-group').removeClass('has-error');
        var url = $(this).attr('action');
        var csrf_token = $('input[name=_token]').val();
        var data = new FormData($(this)[0]);
        $.ajax({
            url: url,
            headers: {'X-CSRF-TOKEN': csrf_token},
            type: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            data: data,
            success: function (resp) {

                ajaxindicatorstop();
                success_msg(resp.msg);
                $('.login-form')[0].reset();
                setTimeout(() => {
                    
                    window.location.href = resp.url;
                }, 1000);
                
            },
            error: function (resp) {
                $.each(resp.responseJSON.errors, function (key, val) {
                    $("#error-" + key).html(val[0]).closest('.form-group').addClass('has-error');
                });
                ajaxindicatorstop();
            }
        })
    });


    $('#forgot-form').submit(function (event) {
        event.preventDefault();
        ajaxindicatorstart();
        $('.help-block').html('').closest('.form-group').removeClass('has-error');
        var url = $(this).attr('action');
        var csrf_token = $('input[name=_token]').val();
        var data = new FormData($(this)[0]);
        $.ajax({
            url: url,
            headers: {'X-CSRF-TOKEN': csrf_token},
            type: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            data: data,
            success: function (resp) {
                ajaxindicatorstop();
                success_msg(resp.msg);
                $('#user_id').val(resp.user_id);
                $('#forgot-form')[0].reset();
                $("#second_step").show();
                $("#first_step").hide();
                
            },
            error: function (resp) {
                $.each(resp.responseJSON.errors, function (key, val) {
                    $("#error-" + key).html(val[0]).closest('.form-group').addClass('has-error');
                });
                ajaxindicatorstop();
            }
        })
    });
    
    $('#forgot-otp-form').submit(function (event) {
        event.preventDefault();
        ajaxindicatorstart();
        $('.help-block').html('').closest('.form-group').removeClass('has-error');
        var url = $(this).attr('action');
        var csrf_token = $('input[name=_token]').val();
        var data = new FormData($(this)[0]);
        $.ajax({
            url: url,
            headers: {'X-CSRF-TOKEN': csrf_token},
            type: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            data: data,
            success: function (resp) {
                ajaxindicatorstop();
                success_msg(resp.msg);
                $('#forgot-otp-form')[0].reset();
                setTimeout(() => {
                    
                    window.location.href = resp.url;
                }, 1000);
                
            },
            error: function (resp) {
                $.each(resp.responseJSON.errors, function (key, val) {
                    $("#error-" + key).html(val[0]).closest('.form-group').addClass('has-error');
                });
                ajaxindicatorstop();
            }
        })
    });

    $('#reset-password-form').submit(function (event) {
        event.preventDefault();
        ajaxindicatorstart();
        $('.help-block').html('').closest('.form-group').removeClass('has-error');
        var url = $(this).attr('action');
        var csrf_token = $('input[name=_token]').val();
        var data = new FormData($(this)[0]);
        $.ajax({
            url: url,
            headers: {'X-CSRF-TOKEN': csrf_token},
            type: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            data: data,
            success: function (resp) {

                if(resp.status == 'success'){
                    ajaxindicatorstop();
                    $('#reset-password-form')[0].reset();
                    success_msg(resp.msg);
                    setTimeout(() => {
                        window.location.href = resp.url;
                    }, 1000);
                }

                if(resp.status == 'error'){
                    ajaxindicatorstop();
                    $('#reset-password-form')[0].reset();
                    error_msg(resp.msg);
                    setTimeout(() => {
                        window.location.href = resp.url;
                    }, 1000);
                }
                
                
            },
            error: function (resp) {
                $.each(resp.responseJSON.errors, function (key, val) {
                    $("#error-" + key).html(val[0]).closest('.form-group').addClass('has-error');
                });

                ajaxindicatorstop();
            }
        })
    });

    $('#contact-form').submit( function (event) {
        event.preventDefault();
        ajaxindicatorstart();
        $('.help-block').html('');
        var url = $(this).attr('action');
        var csrf_token = $('input[name=_token]').val();
        var data = new FormData($(this)[0]);
        $.ajax({
            url: url,
            headers: {'X-CSRF-TOKEN': csrf_token},
            type: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            data: data,
            success: function (resp) {
                $('#contact-form')[0].reset();
                success_msg(resp.msg);
                ajaxindicatorstop();
            },
            error: function (resp) {
                $.each(resp.responseJSON.errors, function (key, val) {
                    $("#error-" + key).html(val[0]);
                });

                ajaxindicatorstop();
            }
        });
    });
    $('#order-track-form').submit(function (event) {
        event.preventDefault();
        ajaxindicatorstart();
        $('.help-block').html('').closest('.form-group').removeClass('has-error');
        var url = $(this).attr('action');
        var csrf_token = $('input[name=_token]').val();
        var data = new FormData($(this)[0]);
        $.ajax({
            url: url,
            headers: {'X-CSRF-TOKEN': csrf_token},
            type: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            data: data,
            success: function (resp) {

                ajaxindicatorstop();
                $('#order-track-details').html(resp.content);
                $('#order-track-details').removeClass('d-none');
                success_msg(resp.msg);
                var section = document.getElementById("order-track-details");

                section.scrollIntoView({ behavior: "smooth" });
                
                
            },
            error: function (resp) {
                $('#order-track-details').html('');
                $('#order-track-details').addClass('d-none');
                $.each(resp.responseJSON.errors, function (key, val) {
                    $("#error-" + key).html(val[0]).closest('.form-group').addClass('has-error');
                });
                ajaxindicatorstop();
            }
        })
    });
    

    $('#inquiry-form').submit( function (event) {
        event.preventDefault();
        ajaxindicatorstart();
        $('.help-block').html('');
        var url = $(this).attr('action');
        var csrf_token = $('input[name=_token]').val();
        var data = new FormData($(this)[0]);
        $.ajax({
            url: url,
            headers: {'X-CSRF-TOKEN': csrf_token},
            type: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            data: data,
            success: function (resp) {

                $('#inquiry-form')[0].reset();
                ajaxindicatorstop();
                success_msg(resp.msg);
                setTimeout(() => {
                    window.location.href = resp.url;
                }, 1000);
            },
            error: function (resp) {
                $.each(resp.responseJSON.errors, function (key, val) {
                    $("#error-" + key).html(val[0]);
                });

                ajaxindicatorstop();
            }
        });
    });

    $('#basic-service-inquiry-form').submit( function (event) {
        event.preventDefault();
        ajaxindicatorstart();
        $('.help-block').html('');
        var url = $(this).attr('action');
        var csrf_token = $('input[name=_token]').val();
        var data = new FormData($(this)[0]);
        $.ajax({
            url: url,
            headers: {'X-CSRF-TOKEN': csrf_token},
            type: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            data: data,
            success: function (resp) {

                $('#basic-service-inquiry-form')[0].reset();
                ajaxindicatorstop();
                success_msg(resp.msg);

                setTimeout(() => {
                    window.location.href = resp.url;
                }, 1000);
                
            },
            error: function (resp) {
                $.each(resp.responseJSON.errors, function (key, val) {
                    $("#error-" + key).html(val[0]);
                });

                ajaxindicatorstop();
            }
        });
    });

    $('#detailed-service-requirements-form').submit( function (event) {
        event.preventDefault();
        ajaxindicatorstart();
        $('.help-block').html('');
        var url = $(this).attr('action');
        var csrf_token = $('input[name=_token]').val();
        var data = new FormData($(this)[0]);
        $.ajax({
            url: url,
            headers: {'X-CSRF-TOKEN': csrf_token},
            type: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            data: data,
            success: function (resp) {

                $('#detailed-service-requirements-form')[0].reset();
                ajaxindicatorstop();
                success_msg(resp.msg);

                setTimeout(() => {
                    window.location.href = resp.url;
                }, 1000);
                
            },
            error: function (resp) {
                $.each(resp.responseJSON.errors, function (key, val) {
                    $("#error-" + key).html(val[0]);
                });

                ajaxindicatorstop();
            }
        });
    });

    // user dashboard

    $('#upload-userImg').on('change',function (event) {
        event.preventDefault();
        ajaxindicatorstart();
        $('.help-block').html('').closest('.form-group').removeClass('has-error');
        var url = $('#profile-picture-form').attr('action');
        var csrf_token = $('input[name=_token]').val();
        var data = new FormData($('#profile-picture-form')[0]);
        $.ajax({
            url: url,
            headers: {'X-CSRF-TOKEN': csrf_token},
            type: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            data: data,
            success: function (resp) {

                ajaxindicatorstop();
                success_msg(resp.msg);
                $('#profile-picture-form')[0].reset();
                $('#userImg').attr("src",resp.image_path);
                
            },
            error: function (resp) {
                $.each(resp.responseJSON.errors, function (key, val) {
                    $("#error-" + key).html(val[0]).closest('.form-group').addClass('has-error');
                });
                ajaxindicatorstop();
            }
        })
    });

    $('#update-profile-form').submit(function (event) {
        event.preventDefault();
        ajaxindicatorstart();
        $('.help-block').html('').closest('.form-group').removeClass('has-error');
        var url = $(this).attr('action');
        var csrf_token = $('input[name=_token]').val();
        var data = new FormData($(this)[0]);
        $.ajax({
            url: url,
            headers: {'X-CSRF-TOKEN': csrf_token},
            type: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            data: data,
            success: function (resp) {

                ajaxindicatorstop();
                success_msg(resp.msg);
                $('#update-profile-form')[0].reset();

                setTimeout(() => {
                    window.location.href = resp.url;
                }, 1000);
                
            },
            error: function (resp) {
                $.each(resp.responseJSON.errors, function (key, val) {
                    $("#error-" + key).html(val[0]).closest('.form-group').addClass('has-error');
                });
                ajaxindicatorstop();
            }
        })
    });
    $('#update-address-form').submit(function (event) {
        event.preventDefault();
        ajaxindicatorstart();
        $('.help-block').html('').closest('.form-group').removeClass('has-error');
        var url = $(this).attr('action');
        var csrf_token = $('input[name=_token]').val();
        var data = new FormData($(this)[0]);
        $.ajax({
            url: url,
            headers: {'X-CSRF-TOKEN': csrf_token},
            type: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            data: data,
            success: function (resp) {

                ajaxindicatorstop();
                success_msg(resp.msg);
                $('#update-profile-form')[0].reset();

                setTimeout(() => {
                    window.location.href = resp.url;
                }, 1000);
                
            },
            error: function (resp) {
                $.each(resp.responseJSON.errors, function (key, val) {
                    $("#error-" + key).html(val[0]).closest('.form-group').addClass('has-error');
                });
                ajaxindicatorstop();
            }
        })
    });

    $('#account-settings-form').submit(function (event) {
        event.preventDefault();
        ajaxindicatorstart();
        $('.help-block').html('').closest('.form-group').removeClass('has-error');
        var url = $(this).attr('action');
        var csrf_token = $('input[name=_token]').val();
        var data = new FormData($(this)[0]);
        $.ajax({
            url: url,
            headers: {'X-CSRF-TOKEN': csrf_token},
            type: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            data: data,
            success: function (resp) {

                ajaxindicatorstop();
                success_msg(resp.msg);
                $('#account-settings-form')[0].reset();

                setTimeout(() => {
                    window.location.href = resp.url;
                }, 1000);
                
            },
            error: function (resp) {
                $.each(resp.responseJSON.errors, function (key, val) {
                    $("#error-" + key).html(val[0]).closest('.form-group').addClass('has-error');
                });
                ajaxindicatorstop();
            }
        })
    });

    
    
    $('#payment-checkout-form').submit(function (event) {
        event.preventDefault();
        ajaxindicatorstart();
        $('.help-block').html('').closest('.form-group').removeClass('has-error');
        var url = $(this).attr('action');
        var csrf_token = $('input[name=_token]').val();
        var data = new FormData($(this)[0]);
        $.ajax({
            url: url,
            headers: {'X-CSRF-TOKEN': csrf_token},
            type: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            data: data,
            success: function (resp) {
                if(resp.status=='success'){
                    if(resp.type == 'offline'){
                        ajaxindicatorstop();
                        success_msg(resp.msg);
                        // $('#payment-checkout-form')[0].reset();
                        setTimeout(() => {
                            window.location.href = resp.url;
                        }, 1000);
                    }else{
                        $('#order_id').val(resp.order_id);
                        $('#pay_amount').val(resp.pay_amount);
                        document.getElementById('razorpay-form').submit();
                    }

                }else{
                    ajaxindicatorstop();
                    error_msg(resp.msg);
                }


                
            },
            error: function (resp) {
                $.each(resp.responseJSON.errors, function (key, val) {
                    $("#error-" + key).html(val[0]).closest('.form-group').addClass('has-error');
                });
                ajaxindicatorstop();
            }
        })
    });
   

    // services

    $('#turnkey-ems-services-form').submit( function (event) {
        event.preventDefault();
        ajaxindicatorstart();
        $('.help-block').html('');
        var url = $(this).attr('action');
        var csrf_token = $('input[name=_token]').val();
        var data = new FormData($(this)[0]);
        $.ajax({
            url: url,
            headers: {'X-CSRF-TOKEN': csrf_token},
            type: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            data: data,
            success: function (resp) {

                $('#turnkey-ems-services-form')[0].reset();
                ajaxindicatorstop();
                success_msg(resp.msg);

                setTimeout(() => {
                    window.location.href = resp.url;
                }, 1000);
                
            },
            error: function (resp) {
                $.each(resp.responseJSON.errors, function (key, val) {
                    $("#error-" + key).html(val[0]);
                });

                ajaxindicatorstop();
            }
        });
    });

    $('#pcb-assembly-services-form').submit( function (event) {
        event.preventDefault();
        ajaxindicatorstart();
        $('.help-block').html('');
        var url = $(this).attr('action');
        var csrf_token = $('input[name=_token]').val();
        var data = new FormData($(this)[0]);
        $.ajax({
            url: url,
            headers: {'X-CSRF-TOKEN': csrf_token},
            type: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            data: data,
            success: function (resp) {

                $('#pcb-assembly-services-form')[0].reset();
                ajaxindicatorstop();
                success_msg(resp.msg);

                setTimeout(() => {
                    window.location.href = resp.url;
                }, 1000);
                
            },
            error: function (resp) {
                $.each(resp.responseJSON.errors, function (key, val) {
                    $("#error-" + key).html(val[0]);
                });

                ajaxindicatorstop();
            }
        });
    });

    $('#through-hole-assembly-services-form').submit( function (event) {
        event.preventDefault();
        ajaxindicatorstart();
        $('.help-block').html('');
        var url = $(this).attr('action');
        var csrf_token = $('input[name=_token]').val();
        var data = new FormData($(this)[0]);
        $.ajax({
            url: url,
            headers: {'X-CSRF-TOKEN': csrf_token},
            type: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            data: data,
            success: function (resp) {

                $('#through-hole-assembly-services-form')[0].reset();
                ajaxindicatorstop();
                success_msg(resp.msg);

                setTimeout(() => {
                    window.location.href = resp.url;
                }, 1000);
                
            },
            error: function (resp) {
                $.each(resp.responseJSON.errors, function (key, val) {
                    $("#error-" + key).html(val[0]);
                });

                ajaxindicatorstop();
            }
        });
    });

    $('#battery-pack-assembly-services-form').submit( function (event) {
        event.preventDefault();
        ajaxindicatorstart();
        $('.help-block').html('');
        var url = $(this).attr('action');
        var csrf_token = $('input[name=_token]').val();
        var data = new FormData($(this)[0]);
        $.ajax({
            url: url,
            headers: {'X-CSRF-TOKEN': csrf_token},
            type: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            data: data,
            success: function (resp) {

                $('#battery-pack-assembly-services-form')[0].reset();
                ajaxindicatorstop();
                success_msg(resp.msg);

                setTimeout(() => {
                    window.location.href = resp.url;
                }, 1000);
                
            },
            error: function (resp) {
                $.each(resp.responseJSON.errors, function (key, val) {
                    $("#error-" + key).html(val[0]);
                });

                ajaxindicatorstop();
            }
        });
    });

    $('#warehousing-services-form').submit( function (event) {
        event.preventDefault();
        ajaxindicatorstart();
        $('.help-block').html('');
        var url = $(this).attr('action');
        var csrf_token = $('input[name=_token]').val();
        var data = new FormData($(this)[0]);
        $.ajax({
            url: url,
            headers: {'X-CSRF-TOKEN': csrf_token},
            type: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            data: data,
            success: function (resp) {

                $('#warehousing-services-form')[0].reset();
                ajaxindicatorstop();
                success_msg(resp.msg);

                setTimeout(() => {
                    window.location.href = resp.url;
                }, 1000);
                
            },
            error: function (resp) {
                $.each(resp.responseJSON.errors, function (key, val) {
                    $("#error-" + key).html(val[0]);
                });

                ajaxindicatorstop();
            }
        });
    });

    $('#mro-services-form').submit( function (event) {
        event.preventDefault();
        ajaxindicatorstart();
        $('.help-block').html('');
        var url = $(this).attr('action');
        var csrf_token = $('input[name=_token]').val();
        var data = new FormData($(this)[0]);
        $.ajax({
            url: url,
            headers: {'X-CSRF-TOKEN': csrf_token},
            type: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            data: data,
            success: function (resp) {

                $('#mro-services-form')[0].reset();
                ajaxindicatorstop();
                success_msg(resp.msg);

                setTimeout(() => {
                    window.location.href = resp.url;
                }, 1000);
                
            },
            error: function (resp) {
                $.each(resp.responseJSON.errors, function (key, val) {
                    $("#error-" + key).html(val[0]);
                });

                ajaxindicatorstop();
            }
        });
    });

    $('#box-building-services-form').submit( function (event) {
        event.preventDefault();
        ajaxindicatorstart();
        $('.help-block').html('');
        var url = $(this).attr('action');
        var csrf_token = $('input[name=_token]').val();
        var data = new FormData($(this)[0]);
        $.ajax({
            url: url,
            headers: {'X-CSRF-TOKEN': csrf_token},
            type: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            data: data,
            success: function (resp) {

                $('#box-building-services-form')[0].reset();
                ajaxindicatorstop();
                success_msg(resp.msg);

                setTimeout(() => {
                    window.location.href = resp.url;
                }, 1000);
                
            },
            error: function (resp) {
                $.each(resp.responseJSON.errors, function (key, val) {
                    $("#error-" + key).html(val[0]);
                });

                ajaxindicatorstop();
            }
        });
    });

    $('#engineering-services-form').submit( function (event) {
        event.preventDefault();
        ajaxindicatorstart();
        $('.help-block').html('');
        var url = $(this).attr('action');
        var csrf_token = $('input[name=_token]').val();
        var data = new FormData($(this)[0]);
        $.ajax({
            url: url,
            headers: {'X-CSRF-TOKEN': csrf_token},
            type: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            data: data,
            success: function (resp) {

                $('#engineering-services-form')[0].reset();
                ajaxindicatorstop();
                success_msg(resp.msg);

                setTimeout(() => {
                    window.location.href = resp.url;
                }, 1000);
                
            },
            error: function (resp) {
                $.each(resp.responseJSON.errors, function (key, val) {
                    $("#error-" + key).html(val[0]);
                });

                ajaxindicatorstop();
            }
        });
    });

    $('#qa-qc-services-form').submit( function (event) {
        event.preventDefault();
        ajaxindicatorstart();
        $('.help-block').html('');
        var url = $(this).attr('action');
        var csrf_token = $('input[name=_token]').val();
        var data = new FormData($(this)[0]);
        $.ajax({
            url: url,
            headers: {'X-CSRF-TOKEN': csrf_token},
            type: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            data: data,
            success: function (resp) {

                $('#qa-qc-services-form')[0].reset();
                ajaxindicatorstop();
                success_msg(resp.msg);

                setTimeout(() => {
                    window.location.href = resp.url;
                }, 1000);
                
            },
            error: function (resp) {
                $.each(resp.responseJSON.errors, function (key, val) {
                    $("#error-" + key).html(val[0]);
                });

                ajaxindicatorstop();
            }
        });
    });

    $('#sourcing-services-form').submit( function (event) {
        event.preventDefault();
        ajaxindicatorstart();
        $('.help-block').html('');
        var url = $(this).attr('action');
        var csrf_token = $('input[name=_token]').val();
        var data = new FormData($(this)[0]);
        $.ajax({
            url: url,
            headers: {'X-CSRF-TOKEN': csrf_token},
            type: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            data: data,
            success: function (resp) {

                $('#sourcing-services-form')[0].reset();
                ajaxindicatorstop();
                success_msg(resp.msg);

                setTimeout(() => {
                    window.location.href = resp.url;
                }, 1000);
                
            },
            error: function (resp) {
                $.each(resp.responseJSON.errors, function (key, val) {
                    $("#error-" + key).html(val[0]);
                });

                ajaxindicatorstop();
            }
        });
    });

    $('#logistics-services-form').submit( function (event) {
        event.preventDefault();
        ajaxindicatorstart();
        $('.help-block').html('');
        var url = $(this).attr('action');
        var csrf_token = $('input[name=_token]').val();
        var data = new FormData($(this)[0]);
        $.ajax({
            url: url,
            headers: {'X-CSRF-TOKEN': csrf_token},
            type: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            data: data,
            success: function (resp) {
                console.log(resp);

                $('#logistics-services-form')[0].reset();
                ajaxindicatorstop();
                success_msg(resp.msg);

                setTimeout(() => {
                    window.location.href = resp.url;
                }, 1000);
                
            },
            error: function (resp) {
                $.each(resp.responseJSON.errors, function (key, val) {
                    $("#error-" + key).html(val[0]);
                });

                ajaxindicatorstop();
            }
        });
    });

    // services end

});


/********** Service list image upload ************/

$(document).on("click", ".browse", function () {
    var file = $(this).parents().find(".file");
    file.trigger("click");
});
$('input[type="file"]').change(function (e) {
    var fileName = e.target.files[0].name;
    $("#file").val(fileName);
});

function viewInnovation(obj) {
    ajaxindicatorstart();
    var innovation_id = $(obj).data('innovation_id');

    $.ajax({
        type: 'GET',
        url: full_path + 'innovation-view',
        data: {innovation_id:innovation_id},
        success: function (resp) {
            ajaxindicatorstop();
            if (resp.type === 1) {
                $('.innovation-body').html(resp.content);
            } 
        }
    });
}


function resendOTP(obj) {
    
    var url = $(obj).data('href');
    // var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.confirm({
        title: 'Resend OTP',
        content: 'Are you sure to resend OTP ?',
        type: 'red',
        typeAnimated: true,
        buttons: {
            confirm: {
                text: '<i class="fa fa-check" aria-hidden="true"></i> Confirm',
                btnClass: 'btn-red',
                action: function () {
                    ajaxindicatorstart();
                    $.ajax({
                        url: url,
                        // headers: {'X-CSRF-TOKEN': csrf_token},
                        type: 'get',
                        dataType: 'json',
                        success: function (resp) {
                            if (resp.status && resp.status === 200) {
                                ajaxindicatorstop();
                                success_msg(resp.msg);

                                setTimeout(function(){
                                    location.reload();
                                },1000);

                            } else {
                                ajaxindicatorstop();
                                error_msg(resp.msg);
                            }
                        }
                    });
                }
            },
            cancel: function () {}
        }
    });

}
function AddtoWishlist(obj) {
    ajaxindicatorstart();
    var item_id = $(obj).data('item_id');
    var model = $(obj).data('model');
    var csrf_token = $('input[name=_token]').val();
    currentRequest = $.ajax({
        type: 'POST',
        headers: {'X-CSRF-TOKEN': csrf_token},
        url: full_path + 'add-to-wishlist',
        dataType: 'json',
        data: {item_id: item_id,model:model},
        beforeSend: function () {
            if (currentRequest !== null) {
                currentRequest.abort();
            }
        },
        success: function (resp) {
            if (resp.type === 1) {
                $('.wishlist_count').html(resp.wishlist_count);
                // $('#wishlist-icon-'+product_id).html(resp.html)
                // $('#wishlist-icons-'+product_id).html('<i class="fa-solid fa-heart"></i> ADDED TO WISHLIST')
                
                success_msg(resp.msg);
            } else {
                error_msg(resp.msg);
            }
            ajaxindicatorstop();
        }
    });
}
function removeWishlist(id, obj) {
    $.confirm({
        title: 'Delete',
        content: 'Are you sure to delete this item from wishlist?',
        buttons: {
            confirm: {
                btnClass: 'btn-success',
                action: function () {
                    ajaxindicatorstart();
                    var csrf_token = $('input[name=_token]').val();
                    $.ajax({
                        type: 'POST',
                        headers: {'X-CSRF-TOKEN': csrf_token},
                        url: full_path + 'remove-wishlist',
                        dataType: 'json',
                        data: {wishlist_id: id},
                        success: function (resp) {
                            if (resp.type === 1) {
                                $(obj).closest('tr').remove();
                                $('.wishlist_count').html(resp.wishlist_count);
                                if (resp.wishlist_count == 0) {
                                    location.reload();
                                }
                                success_msg(resp.msg);
                            } else {
                                error_msg(resp.msg);
                            }
                            ajaxindicatorstop();
                        }
                    });
                }
            },
            cancel: {
                btnClass: 'btn-danger'
                        //
            },
        }
    });
}
function AddtoCart(obj) {
    
    var product_id = $(obj).data('product_id');
    var allow_product_size = $(obj).data('allow_product_size');
    // var size_id = $(obj).data('size_id');
    var size_id = $('#size_selector_'+product_id).val();
    if(allow_product_size == 1){
        if(size_id ==  ''){
            error_msg('Please Select Your Size');
            return false;
        }
    }
    ajaxindicatorstart();
    var qty = $('#product'+product_id).val();
    var csrf_token = $('input[name=_token]').val();
    currentRequest = $.ajax({
        type: 'POST',
        headers: {'X-CSRF-TOKEN': csrf_token},
        url: full_path + 'add-cart',
        dataType: 'json',
        data: {product_id: product_id,size_id: size_id,qty:qty},
        beforeSend: function () {
            if (currentRequest !== null) {
                currentRequest.abort();
            }
        },
        success: function (resp) {
            if (resp.type === 1) {
                $('.cart_count').html(resp.cart_count);
                success_msg(resp.msg);
            } else {
                error_msg(resp.msg);
            }
            ajaxindicatorstop();
        }
    });
}
function BuyNow(obj) {
    var product_id = $(obj).data('product_id');
    var allow_product_size = $(obj).data('allow_product_size');
    // var size_id = $(obj).data('size_id');
    var size_id = $('#size_selector_'+product_id).val();
    if(allow_product_size == 1){
        if(size_id ==  ''){
            error_msg('Please Select Your Size');
            return false;
        }
    }
    ajaxindicatorstart();
    var qty = $('#product'+product_id).val();
    var csrf_token = $('input[name=_token]').val();
    currentRequest = $.ajax({
        type: 'POST',
        headers: {'X-CSRF-TOKEN': csrf_token},
        url: full_path + 'buy-now',
        dataType: 'json',
        data: {product_id: product_id,size_id: size_id,qty:qty},
        beforeSend: function () {
            if (currentRequest !== null) {
                currentRequest.abort();
            }
        },
        success: function (resp) {
            if (resp.type === 1) {
                $('.cart_count').html(resp.cart_count);
                success_msg(resp.msg);
                setTimeout(() => {
                //   console.log("Delayed for 1 second.");
                   window.location.href = resp.url;
                }, "1000");
            } else {
                error_msg(resp.msg);
            }
            ajaxindicatorstop();
        }
    });
}
function removeCart(id, obj) {
    $.confirm({
        title: 'Delete',
        content: 'Are you sure to delete this product from cart?',
        buttons: {
            confirm: {
                btnClass: 'btn-success',
                action: function () {
                    ajaxindicatorstart();
                    var csrf_token = $('input[name=_token]').val();
                    $.ajax({
                        type: 'POST',
                        headers: {'X-CSRF-TOKEN': csrf_token},
                        url: full_path + 'remove-cart',
                        dataType: 'json',
                        data: {cart_id: id},
                        success: function (resp) {
                            if (resp.type === 1) {
                                $(obj).closest('tr').remove();
                                $('.cart_count').html(resp.cart_count);
                                $('.cart_total_price').html(resp.total);
                                if (resp.cart_count == 0) {
                                    location.reload();
                                }
                                success_msg(resp.msg);
                            } else {
                                error_msg(resp.msg);
                            }
                            ajaxindicatorstop();
                        }
                    });
                }
            },
            cancel: {
                btnClass: 'btn-danger'
                        //
            },
        }
    });
}
function loadProducts(set_offset) {
    $('#filterProductForm').submit(function (e) {
        e.preventDefault();
    });
    ajaxindicatorstart();
    if (set_offset === 0) {
        $('#offset').val(0);
    }
    $(".search_load_btn").addClass('d-none');
    var data = new FormData($('#filterProductForm')[0]);
    var csrf_token = $('input[name=_token]').val();
    currentRequest = $.ajax({
        url: $('#filterProductForm').attr('action'),
        headers: {'X-CSRF-TOKEN': csrf_token},
        type: 'POST',
        dataType: 'json',
        processData: false,
        contentType: false,
        data: data,
        beforeSend: function () {
            if (currentRequest !== null) {
                currentRequest.abort();
            }
        },
        success: function (resp) {
            if (resp.type === 'success') {
                if (set_offset === 1) {
                    $('#fetchProductResults').append(resp.content);
                } else {
                    $('#fetchProductResults').html(resp.content);
                }
                $('#offset').val(resp.offset);
                if (resp.total <= resp.limit) {
                    $(".search_load_btn").removeAttr('onclick');
                    $(".search_load_btn").addClass('d-none');
                } else {
                    $(".search_load_btn").attr('onclick', 'loadProducts(1);');
                    $(".search_load_btn").removeClass('d-none');
                }
            } else {
                $('#fetchProductResults').html(resp.content);
            }
            ajaxindicatorstop();
        }
    });
}
function checkQuantity (obj) {
    var quantity = parseInt($(obj).val(), 10);
    var product_id = $(obj).attr("data-id");
    var max_value = $(obj).attr("data-max_value");
 
    // If quantity is less than 1 or not a number, set it to 1
    
    if (isNaN(quantity) || quantity < 1) {
        $(obj).val(1);
    }else{
        // alert(max_value);
        if(quantity > max_value){
            $(obj).val(max_value);
        }else{
            $(obj).val(quantity);
        }
    }
}
function checkQuantityCart (obj) {
    var quantity = parseInt($(obj).val(), 10);
    var product_id = $(obj).attr("data-id");
    var max_value = $(obj).attr("data-max_value");
    var cart_id = $(obj).attr("data-id");
    var value = 1;
    // If quantity is less than 1 or not a number, set it to 1
    
    if (isNaN(quantity) || quantity < 1) {
        value = 1;
        $(obj).val(1);
    }else{
        // alert(max_value);
        if(quantity > max_value){
            value = max_value;
            $(obj).val(max_value);
        }else{
            value = quantity;
            $(obj).val(quantity);
        }
    }
    changeCartQty(cart_id,value);
}
function getProductQuantity (obj) {
    var size_value = $(obj).val();
    var product_id = $(obj).find(':selected').attr('data-product_id');
    // ajaxindicatorstart();
    var csrf_token = $('input[name=_token]').val();
    currentRequest = $.ajax({
        type: 'GET',
        url: full_path + 'get-product-size',
        dataType: 'json',
        data: {size_value: size_value},
        beforeSend: function () {
            if (currentRequest !== null) {
                currentRequest.abort();
            }
        },
        success: function (resp) {
            
           
            $('#product_price_'+product_id).html(resp.html);
            
            $('#addtocart'+product_id).attr('data-size_id', resp.size_id);
            $('#prod_sku').html(resp.size_sku);
            if(resp.size_qty<=0){
                $('#out-of-stock').removeClass('d-none');
                if(resp.pre_order=='yes'){
                    $('#product'+product_id).prop('max',100);
                    $('#product'+product_id).attr('data-max_value', 100);
                    $('#pre-order').removeClass('d-none');
                }else{
                    $('#pre-order').addClass('d-none');
                    $('#product'+product_id).prop('max',resp.size_qty);
                    $('#product'+product_id).attr('data-max_value', resp.size_qty);
                }
            }else{
                $('#product'+product_id).prop('max',resp.size_qty);
                $('#product'+product_id).attr('data-max_value', resp.size_qty);
                $('#pre-order').addClass('d-none');
                $('#out-of-stock').addClass('d-none');
            }
            
            // ajaxindicatorstop();
        }
    });
    
}
function loadMore() {
    ajaxindicatorstart();
    var data = new FormData($('#filterProductForm')[0]);
    var csrf_token = $('input[name=_token]').val();
    $(".search_load_btn").addClass('d-none');
    currentRequest = $.ajax({
        type: 'POST',
        url: $('#filterProductForm').attr('action'),
        headers: {'X-CSRF-TOKEN': csrf_token},
        dataType: 'json',
        data: data,
        beforeSend: function () {
            if (currentRequest !== null) {
                currentRequest.abort();
            }
        },
        success: function (resp) {
            $('#fetchProductResults').append(resp.content)
            $('img').on("error", function () {
                this.src = $(this).data('src');
            });
            $('[name="offset"]').val(resp.offset);
            if (resp.total <= resp.offset) {
                $(".search_load_btn").removeAttr('onclick');
                $(".search_load_btn").addClass('d-none');
            } else {
                $(".search_load_btn").attr('onclick', 'loadMore();');
                $(".search_load_btn").removeClass('d-none');
            }
            ajaxindicatorstop();
        }
    });
}

function increaseValueCart(temp) {
    var value = parseInt(document.getElementById(temp).value, 10);
    var max_value = $('#'+temp).attr("data-max_value");
    var cart_id = $('#'+temp).attr("data-id");
    value = isNaN(value) ? 1 : value;
    value++;
    
    if(value>max_value){
        value = max_value;
    }
    if(value<1){
        value = 1;
    }
    document.getElementById(temp).value = value;
    changeCartQty(cart_id,value);
}

function decreaseValueCart(temp) {
    var value = parseInt(document.getElementById(temp).value, 10);
    var cart_id = $('#'+temp).attr("data-id");
    value = isNaN(value) ? 1 : value;
    value < 1 ? (value = 1) : '';
    value--;
    if(value<1){
        value = 1;
    }
    document.getElementById(temp).value = value;
    changeCartQty(cart_id,value);
}
function changeCartQty(cart_id, qty){
    ajaxindicatorstart();
    var csrf_token = $('input[name=_token]').val();
    currentRequest = $.ajax({
        type: 'POST',
        headers: {'X-CSRF-TOKEN': csrf_token},
        url: full_path + 'change-cart-qty',
        dataType: 'json',
        data: {cart_id: cart_id,qty:qty},
        beforeSend: function () {
            if (currentRequest !== null) {
                currentRequest.abort();
            }
        },
        success: function (resp) {
            if (resp.type === 1) {
                $('#product_total_price_'+cart_id).html(resp.product_total_price);
                $('.cart_total_price').html(resp.total_price);
            } else {
                error_msg(resp.msg);
            }
            ajaxindicatorstop();
        }
    });
}
// Modal
$(document).ready(function () {
    // $(".size-guide-btn").on("click", function () {
    //     $("#size-guide").modal('show');
    // });
    // $(".video-pop").on("click", function () {
    //     $("#video-modal").modal('show');
    // });
})
function showSizeGuideModal(obj) {
    var product_size_guide_image = $(obj).data('product_size_guide_image');
    var image = document.getElementById('size-guide-image');
        
        // Change the src attribute
        image.src = "/public/uploads/product/size-guide/"+product_size_guide_image;
    $('.modal').modal('hide');
    $('#size-guide').modal('show');
}
function showVideoModal(obj) {
    // alert(1);
    var product_iframe = $(obj).data('product_iframe');

    $('#product-video-iframe').html(product_iframe)
    $('.modal').modal('hide');
    $('#video-modal').modal('show');
}















