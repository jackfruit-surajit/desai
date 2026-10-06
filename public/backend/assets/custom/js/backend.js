

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

function error_msg(msg) {
    toastr.error(msg, '');
    
}
function success_msg(msg) {
    toastr.success(msg, '');
}


$(document).ready(function () {
    function isEmpty(el){
      return !$.trim(el.html())
    }
    $(document).on('change','#state',function () {
        var link = $(this).find(':selected').attr('data-href');
        if(link != "")
        {
          $('#city').load(link);
          $('#city').prop('disabled',false);
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
    // Size Section
    $("#size-check").change(function() {
        if(this.checked) {
            $("#size-display").show();
            $("#stckprod").hide();
            $("#skuprod").hide();
        }
        else
        {
            $("#size-display").hide();
            $("#stckprod").show();
            $("#skuprod").show();
    
        }
    });
    
    $("#size-btn").on('click', function(){
    
        $("#size-section").append(''+
            '<div class="size-area">'+
                '<span class="remove size-remove"><i class="fas fa-times"></i></span>'+
                '<div  class="row g-3">'+
                    '<div class="col-lg-3">'+
                        '<div class="mb-3">'+
                            '<label class="form-label">'+
                                'Size Name : <i class="ph-duotone ph-info ms-1" data-bs-toggle="tooltip" data-bs-title="(eg. S,M,L,XL,XXL,3XL,4XL)"></i>'+
                            '</label>'+
                            '<input type="text" name="size[]" class="form-control" placeholder="Size Name" required>'+
                        '</div>'+
                    '</div>'+
                    '<div class="col-lg-3">'+
                        '<div class="mb-3">'+
                            '<label class="form-label">'+
                                'Size Stock : <i class="ph-duotone ph-info ms-1" data-bs-toggle="tooltip" data-bs-title="(Number of stock of this size)"></i>'+
                            '</label>'+
                            '<input type="number" name="size_stock[]" class="form-control" placeholder="Size Stock" value="1" min="1" required>'+
                        '</div>'+
                    '</div>'+
                    '<div class="col-lg-3">'+
                        '<div class="mb-3">'+
                            '<label class="form-label">'+
                                'Size Price : (Final Price=Price+Size Price)<i class="ph-duotone ph-info ms-1" data-bs-toggle="tooltip" data-bs-title="(This price will be added with base price)"></i>'+
                            '</label>'+
                            '<input type="number" name="size_price[]" class="form-control" placeholder="Size Price" value="0" min="0" required>'+
                        '</div>'+
                    '</div>'+
                    '<div class="col-lg-3">'+
                        '<div class="mb-3">'+
                            '<label class="form-label">'+
                                'Size SKU : <i class="ph-duotone ph-info ms-1" data-bs-toggle="tooltip" data-bs-title="(This SKU different for all sizes)"></i>'+
                            '</label>'+
                            '<input type="text" name="size_sku[]" class="form-control" placeholder="Size SKU" required>'+
                        '</div>'+
                    '</div>'+
                '</div>'+
            '</div>'
        +'');
    
    });
    
    $(document).on('click','.size-remove', function(){
    
        $(this.parentNode).remove();
        if (isEmpty($('#size-section'))) {
    
            $("#size-section").append(''+
                '<div class="size-area">'+
                    '<span class="remove size-remove"><i class="fas fa-times"></i></span>'+
                    '<div  class="row g-3">'+
                        '<div class="col-lg-3">'+
                            '<div class="mb-3">'+
                                '<label class="form-label">'+
                                    'Size Name : <i class="ph-duotone ph-info ms-1" data-bs-toggle="tooltip" data-bs-title="(eg. S,M,L,XL,XXL,3XL,4XL)"></i>'+
                                '</label>'+
                                '<input type="text" name="size[]" class="form-control" placeholder="Size Name" required>'+
                            '</div>'+
                        '</div>'+
                        '<div class="col-lg-3">'+
                            '<div class="mb-3">'+
                                '<label class="form-label">'+
                                    'Size Stock : <i class="ph-duotone ph-info ms-1" data-bs-toggle="tooltip" data-bs-title="(Number of stock of this size)"></i>'+
                                '</label>'+
                                '<input type="number" name="size_stock[]" class="form-control" placeholder="Size Stock" value="1" min="1" required>'+
                            '</div>'+
                        '</div>'+
                        '<div class="col-lg-3">'+
                            '<div class="mb-3">'+
                                '<label class="form-label">'+
                                    'Size Price : <i class="ph-duotone ph-info ms-1" data-bs-toggle="tooltip" data-bs-title="(This price will be added with base price)"></i>'+
                                '</label>'+
                                '<input type="number" name="size_price[]" class="form-control" placeholder="Size Price" value="0" min="0" required>'+
                            '</div>'+
                        '</div>'+
                        '<div class="col-lg-3">'+
                            '<div class="mb-3">'+
                                '<label class="form-label">'+
                                    'Size SKU : <i class="ph-duotone ph-info ms-1" data-bs-toggle="tooltip" data-bs-title="(This SKU different for all sizes)"></i>'+
                                '</label>'+
                                '<input type="text" name="size_sku[]" class="form-control" placeholder="Size SKU" required>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                '</div>'
            +'');
        }
    
    });
    
    // Size Section Ends
});


function deleteUser(obj) {
    var url = $(obj).data('href');
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.confirm({
        title: 'Delete User',
        content: 'Are you sure to delete this User?',
        type: 'red',
        typeAnimated: true,
        buttons: {
            confirm: {
                text: '<i class="fa fa-check" aria-hidden="true"></i> Confirm',
                btnClass: 'btn-red',
                action: function () {
                    $.ajax({
                        url: url,
                        headers: {'X-CSRF-TOKEN': csrf_token},
                        type: 'get',
                        dataType: 'json',
                        success: function (resp) {
                            if (resp.status && resp.status === 200) {
                                // $('.page-content').prepend('<div class="alert alert-success mt-2"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'
                                //         + '<span>' + resp.msg + '</span></div>');
                                success_msg(resp.msg);

                                $('#user-management').DataTable().ajax.reload();

                            } else {
                                // $('.page-content').prepend('<div class="alert alert-danger mt-2"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'
                                //         + '<span>' + resp.msg + '</span></div>');
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
function deleteUserAll(obj) {
    var url = $(obj).data('href');
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.confirm({
        title: 'Delete Users',
        content: 'Are you sure to delete All User?',
        type: 'red',
        typeAnimated: true,
        buttons: {
            confirm: {
                text: '<i class="fa fa-check" aria-hidden="true"></i> Confirm',
                btnClass: 'btn-red',
                action: function () {
                    $.ajax({
                        url: url,
                        headers: {'X-CSRF-TOKEN': csrf_token},
                        type: 'get',
                        dataType: 'json',
                        success: function (resp) {
                            if (resp.status && resp.status === 200) {
                                // $('.page-content').prepend('<div class="alert alert-success mt-2"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'
                                //         + '<span>' + resp.msg + '</span></div>');
                                success_msg(resp.msg);

                                $('#user-management').DataTable().ajax.reload();

                            } else {
                                // $('.page-content').prepend('<div class="alert alert-danger mt-2"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'
                                //         + '<span>' + resp.msg + '</span></div>');
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
function deleteOrderAll(obj) {
    var url = $(obj).data('href');
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.confirm({
        title: 'Delete Orders',
        content: 'Are you sure to delete All Order?',
        type: 'red',
        typeAnimated: true,
        buttons: {
            confirm: {
                text: '<i class="fa fa-check" aria-hidden="true"></i> Confirm',
                btnClass: 'btn-red',
                action: function () {
                    $.ajax({
                        url: url,
                        headers: {'X-CSRF-TOKEN': csrf_token},
                        type: 'get',
                        dataType: 'json',
                        success: function (resp) {
                            if (resp.status && resp.status === 200) {
                                success_msg(resp.msg);

                                $('#order-management').DataTable().ajax.reload();

                            } else {
                                // $('.page-content').prepend('<div class="alert alert-danger mt-2"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'
                                //         + '<span>' + resp.msg + '</span></div>');
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



function deleteState(obj) {
    var url = $(obj).data('href');
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.confirm({
        title: 'Delete State',
        content: 'Are you sure to delete this State?',
        type: 'red',
        typeAnimated: true,
        buttons: {
            confirm: {
                text: '<i class="fa fa-check" aria-hidden="true"></i> Confirm',
                btnClass: 'btn-red',
                action: function () {
                    $.ajax({
                        url: url,
                        headers: {'X-CSRF-TOKEN': csrf_token},
                        type: 'get',
                        dataType: 'json',
                        success: function (resp) {
                            if (resp.status && resp.status === 200) {
                                // $('.page-content').prepend('<div class="alert alert-success mt-2"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'
                                //         + '<span>' + resp.msg + '</span></div>');
                                success_msg(resp.msg);

                                $('#state-management').DataTable().ajax.reload();

                            } else {
                                // $('.page-content').prepend('<div class="alert alert-danger mt-2"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'
                                //         + '<span>' + resp.msg + '</span></div>');
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
function deleteCity(obj) {
    var url = $(obj).data('href');
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.confirm({
        title: 'Delete City',
        content: 'Are you sure to delete this City?',
        type: 'red',
        typeAnimated: true,
        buttons: {
            confirm: {
                text: '<i class="fa fa-check" aria-hidden="true"></i> Confirm',
                btnClass: 'btn-red',
                action: function () {
                    $.ajax({
                        url: url,
                        headers: {'X-CSRF-TOKEN': csrf_token},
                        type: 'get',
                        dataType: 'json',
                        success: function (resp) {
                            if (resp.status && resp.status === 200) {
                                // $('.page-content').prepend('<div class="alert alert-success mt-2"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'
                                //         + '<span>' + resp.msg + '</span></div>');
                                success_msg(resp.msg);

                                $('#city-management').DataTable().ajax.reload();

                            } else {
                                // $('.page-content').prepend('<div class="alert alert-danger mt-2"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'
                                //         + '<span>' + resp.msg + '</span></div>');
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

function deleteSchool(obj) {
    var url = $(obj).data('href');
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.confirm({
        title: 'Delete School',
        content: 'Are you sure to delete this School?',
        type: 'red',
        typeAnimated: true,
        buttons: {
            confirm: {
                text: '<i class="fa fa-check" aria-hidden="true"></i> Confirm',
                btnClass: 'btn-red',
                action: function () {
                    $.ajax({
                        url: url,
                        headers: {'X-CSRF-TOKEN': csrf_token},
                        type: 'get',
                        dataType: 'json',
                        success: function (resp) {
                            if (resp.status && resp.status === 200) {
                                // $('.page-content').prepend('<div class="alert alert-success mt-2"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'
                                //         + '<span>' + resp.msg + '</span></div>');
                                success_msg(resp.msg);

                                $('#school-management').DataTable().ajax.reload();

                            } else {
                                // $('.page-content').prepend('<div class="alert alert-danger mt-2"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'
                                //         + '<span>' + resp.msg + '</span></div>');
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
function deleteCategory(obj) {
    var url = $(obj).data('href');
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.confirm({
        title: 'Delete Category',
        content: 'Are you sure to delete this Category?',
        type: 'red',
        typeAnimated: true,
        buttons: {
            confirm: {
                text: '<i class="fa fa-check" aria-hidden="true"></i> Confirm',
                btnClass: 'btn-red',
                action: function () {
                    $.ajax({
                        url: url,
                        headers: {'X-CSRF-TOKEN': csrf_token},
                        type: 'get',
                        dataType: 'json',
                        success: function (resp) {
                            if (resp.status && resp.status === 200) {
                                // $('.page-content').prepend('<div class="alert alert-success mt-2"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'
                                //         + '<span>' + resp.msg + '</span></div>');
                                success_msg(resp.msg);

                                $('#category-management').DataTable().ajax.reload();

                            } else {
                                // $('.page-content').prepend('<div class="alert alert-danger mt-2"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'
                                //         + '<span>' + resp.msg + '</span></div>');
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
function deleteSubcategory(obj) {
    var url = $(obj).data('href');
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.confirm({
        title: 'Delete Subcategory',
        content: 'Are you sure to delete this Subcategory?',
        type: 'red',
        typeAnimated: true,
        buttons: {
            confirm: {
                text: '<i class="fa fa-check" aria-hidden="true"></i> Confirm',
                btnClass: 'btn-red',
                action: function () {
                    $.ajax({
                        url: url,
                        headers: {'X-CSRF-TOKEN': csrf_token},
                        type: 'get',
                        dataType: 'json',
                        success: function (resp) {
                            if (resp.status && resp.status === 200) {
                                // $('.page-content').prepend('<div class="alert alert-success mt-2"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'
                                //         + '<span>' + resp.msg + '</span></div>');
                                success_msg(resp.msg);

                                $('#subcategory-management').DataTable().ajax.reload();

                            } else {
                                // $('.page-content').prepend('<div class="alert alert-danger mt-2"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'
                                //         + '<span>' + resp.msg + '</span></div>');
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

function deleteDepartment(obj) {
    var url = $(obj).data('href');
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.confirm({
        title: 'Delete Department',
        content: 'Are you sure to delete this Department?',
        type: 'red',
        typeAnimated: true,
        buttons: {
            confirm: {
                text: '<i class="fa fa-check" aria-hidden="true"></i> Confirm',
                btnClass: 'btn-red',
                action: function () {
                    $.ajax({
                        url: url,
                        headers: {'X-CSRF-TOKEN': csrf_token},
                        type: 'get',
                        dataType: 'json',
                        success: function (resp) {
                            if (resp.status && resp.status === 200) {
                                success_msg(resp.msg);

                                $('#department-management').DataTable().ajax.reload();

                            } else {
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

function deleteCourse(obj) {
    var url = $(obj).data('href');
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.confirm({
        title: 'Delete Course',
        content: 'Are you sure to delete this Course?',
        type: 'red',
        typeAnimated: true,
        buttons: {
            confirm: {
                text: '<i class="fa fa-check" aria-hidden="true"></i> Confirm',
                btnClass: 'btn-red',
                action: function () {
                    $.ajax({
                        url: url,
                        headers: {'X-CSRF-TOKEN': csrf_token},
                        type: 'get',
                        dataType: 'json',
                        success: function (resp) {
                            if (resp.status && resp.status === 200) {
                                success_msg(resp.msg);

                                $('#course-management').DataTable().ajax.reload();

                            } else {
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

function deleteLab(obj) {
    var url = $(obj).data('href');
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.confirm({
        title: 'Delete Lab',
        content: 'Are you sure to delete this Lab?',
        type: 'red',
        typeAnimated: true,
        buttons: {
            confirm: {
                text: '<i class="fa fa-check" aria-hidden="true"></i> Confirm',
                btnClass: 'btn-red',
                action: function () {
                    $.ajax({
                        url: url,
                        headers: {'X-CSRF-TOKEN': csrf_token},
                        type: 'get',
                        dataType: 'json',
                        success: function (resp) {
                            if (resp.status && resp.status === 200) {
                                success_msg(resp.msg);

                                $('#lab-management').DataTable().ajax.reload();

                            } else {
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

function deleteFaculty(obj) {
    var url = $(obj).data('href');
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.confirm({
        title: 'Delete Faculty',
        content: 'Are you sure to delete this Faculty?',
        type: 'red',
        typeAnimated: true,
        buttons: {
            confirm: {
                text: '<i class="fa fa-check" aria-hidden="true"></i> Confirm',
                btnClass: 'btn-red',
                action: function () {
                    $.ajax({
                        url: url,
                        headers: {'X-CSRF-TOKEN': csrf_token},
                        type: 'get',
                        dataType: 'json',
                        success: function (resp) {
                            if (resp.status && resp.status === 200) {
                                success_msg(resp.msg);

                                $('#faculty-management').DataTable().ajax.reload();

                            } else {
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

function deleteFaq(obj) {
    var url = $(obj).data('href');
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.confirm({
        title: 'Delete Faq',
        content: 'Are you sure to delete this Faq?',
        type: 'red',
        typeAnimated: true,
        buttons: {
            confirm: {
                text: '<i class="fa fa-check" aria-hidden="true"></i> Confirm',
                btnClass: 'btn-red',
                action: function () {
                    $.ajax({
                        url: url,
                        headers: {'X-CSRF-TOKEN': csrf_token},
                        type: 'get',
                        dataType: 'json',
                        success: function (resp) {
                            if (resp.status && resp.status === 200) {
                                // $('.page-content').prepend('<div class="alert alert-success mt-2"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'
                                //         + '<span>' + resp.msg + '</span></div>');
                                success_msg(resp.msg);

                                $('#faq-management').DataTable().ajax.reload();

                            } else {
                                // $('.page-content').prepend('<div class="alert alert-danger mt-2"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'
                                //         + '<span>' + resp.msg + '</span></div>');
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

function deleteInnovation(obj) {
    var url = $(obj).data('href');
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.confirm({
        title: 'Delete Innovation',
        content: 'Are you sure to delete this Innovation?',
        type: 'red',
        typeAnimated: true,
        buttons: {
            confirm: {
                text: '<i class="fa fa-check" aria-hidden="true"></i> Confirm',
                btnClass: 'btn-red',
                action: function () {
                    $.ajax({
                        url: url,
                        headers: {'X-CSRF-TOKEN': csrf_token},
                        type: 'get',
                        dataType: 'json',
                        success: function (resp) {
                            if (resp.status && resp.status === 200) {
                                success_msg(resp.msg);

                                $('#innovation-management').DataTable().ajax.reload();

                            } else {
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

function deleteResearch(obj) {
    var url = $(obj).data('href');
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.confirm({
        title: 'Delete Research',
        content: 'Are you sure to delete this Research?',
        type: 'red',
        typeAnimated: true,
        buttons: {
            confirm: {
                text: '<i class="fa fa-check" aria-hidden="true"></i> Confirm',
                btnClass: 'btn-red',
                action: function () {
                    $.ajax({
                        url: url,
                        headers: {'X-CSRF-TOKEN': csrf_token},
                        type: 'get',
                        dataType: 'json',
                        success: function (resp) {
                            if (resp.status && resp.status === 200) {
                                success_msg(resp.msg);

                                $('#research-management').DataTable().ajax.reload();

                            } else {
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

function deleteEvent(obj) {
    var url = $(obj).data('href');
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.confirm({
        title: 'Delete Event',
        content: 'Are you sure to delete this Event?',
        type: 'red',
        typeAnimated: true,
        buttons: {
            confirm: {
                text: '<i class="fa fa-check" aria-hidden="true"></i> Confirm',
                btnClass: 'btn-red',
                action: function () {
                    $.ajax({
                        url: url,
                        headers: {'X-CSRF-TOKEN': csrf_token},
                        type: 'get',
                        dataType: 'json',
                        success: function (resp) {
                            if (resp.status && resp.status === 200) {
                                success_msg(resp.msg);

                                $('#event-management').DataTable().ajax.reload();

                            } else {
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

function deleteAnnualFest(obj) {
    var url = $(obj).data('href');
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.confirm({
        title: 'Delete Annual Fest',
        content: 'Are you sure to delete this Annual Fest?',
        type: 'red',
        typeAnimated: true,
        buttons: {
            confirm: {
                text: '<i class="fa fa-check" aria-hidden="true"></i> Confirm',
                btnClass: 'btn-red',
                action: function () {
                    $.ajax({
                        url: url,
                        headers: {'X-CSRF-TOKEN': csrf_token},
                        type: 'get',
                        dataType: 'json',
                        success: function (resp) {
                            if (resp.status && resp.status === 200) {
                                success_msg(resp.msg);

                                $('#annual-fest-management').DataTable().ajax.reload();

                            } else {
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

function deleteTestimonial(obj) {
    var url = $(obj).data('href');
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.confirm({
        title: 'Delete Testimonial',
        content: 'Are you sure to delete this Testimonial?',
        type: 'red',
        typeAnimated: true,
        buttons: {
            confirm: {
                text: '<i class="fa fa-check" aria-hidden="true"></i> Confirm',
                btnClass: 'btn-red',
                action: function () {
                    $.ajax({
                        url: url,
                        headers: {'X-CSRF-TOKEN': csrf_token},
                        type: 'get',
                        dataType: 'json',
                        success: function (resp) {
                            if (resp.status && resp.status === 200) {
                                success_msg(resp.msg);

                                $('#testimonial-management').DataTable().ajax.reload();

                            } else {
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

function deleteTeam(obj) {
    var url = $(obj).data('href');
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.confirm({
        title: 'Delete Team',
        content: 'Are you sure to delete this Team?',
        type: 'red',
        typeAnimated: true,
        buttons: {
            confirm: {
                text: '<i class="fa fa-check" aria-hidden="true"></i> Confirm',
                btnClass: 'btn-red',
                action: function () {
                    $.ajax({
                        url: url,
                        headers: {'X-CSRF-TOKEN': csrf_token},
                        type: 'get',
                        dataType: 'json',
                        success: function (resp) {
                            if (resp.status && resp.status === 200) {
                                success_msg(resp.msg);

                                $('#team-management').DataTable().ajax.reload();

                            } else {
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

function deleteIndustrialVisit(obj) {
    var url = $(obj).data('href');
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.confirm({
        title: 'Delete Industrial Visit',
        content: 'Are you sure to delete this Industrial Visit?',
        type: 'red',
        typeAnimated: true,
        buttons: {
            confirm: {
                text: '<i class="fa fa-check" aria-hidden="true"></i> Confirm',
                btnClass: 'btn-red',
                action: function () {
                    $.ajax({
                        url: url,
                        headers: {'X-CSRF-TOKEN': csrf_token},
                        type: 'get',
                        dataType: 'json',
                        success: function (resp) {
                            if (resp.status && resp.status === 200) {
                                success_msg(resp.msg);

                                $('#visit-management').DataTable().ajax.reload();

                            } else {
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

function deletePlacement(obj) {
    var url = $(obj).data('href');
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.confirm({
        title: 'Delete Placement',
        content: 'Are you sure to delete this Placement?',
        type: 'red',
        typeAnimated: true,
        buttons: {
            confirm: {
                text: '<i class="fa fa-check" aria-hidden="true"></i> Confirm',
                btnClass: 'btn-red',
                action: function () {
                    $.ajax({
                        url: url,
                        headers: {'X-CSRF-TOKEN': csrf_token},
                        type: 'get',
                        dataType: 'json',
                        success: function (resp) {
                            if (resp.status && resp.status === 200) {
                                success_msg(resp.msg);

                                $('#placement-management').DataTable().ajax.reload();

                            } else {
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

function deleteJob(obj) {
    var url = $(obj).data('href');
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.confirm({
        title: 'Delete Job',
        content: 'Are you sure to delete this Job?',
        type: 'red',
        typeAnimated: true,
        buttons: {
            confirm: {
                text: '<i class="fa fa-check" aria-hidden="true"></i> Confirm',
                btnClass: 'btn-red',
                action: function () {
                    $.ajax({
                        url: url,
                        headers: {'X-CSRF-TOKEN': csrf_token},
                        type: 'get',
                        dataType: 'json',
                        success: function (resp) {
                            if (resp.status && resp.status === 200) {
                                success_msg(resp.msg);

                                $('#job-management').DataTable().ajax.reload();

                            } else {
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

function deleteContact(obj) {
    var url = $(obj).data('href');
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.confirm({
        title: 'Delete Contact',
        content: 'Are you sure to delete this Contact?',
        type: 'red',
        typeAnimated: true,
        buttons: {
            confirm: {
                text: '<i class="fa fa-check" aria-hidden="true"></i> Confirm',
                btnClass: 'btn-red',
                action: function () {
                    $.ajax({
                        url: url,
                        headers: {'X-CSRF-TOKEN': csrf_token},
                        type: 'get',
                        dataType: 'json',
                        success: function (resp) {
                            if (resp.status && resp.status === 200) {
                                // $('.page-content').prepend('<div class="alert alert-success mt-2"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'
                                //         + '<span>' + resp.msg + '</span></div>');
                                success_msg(resp.msg);

                                $('#contact-management').DataTable().ajax.reload();

                            } else {
                                // $('.page-content').prepend('<div class="alert alert-danger mt-2"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'
                                //         + '<span>' + resp.msg + '</span></div>');
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
function deleteSlider(obj) {
    var url = $(obj).data('href');
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.confirm({
        title: 'Delete Slider',
        content: 'Are you sure to delete this Slider?',
        type: 'red',
        typeAnimated: true,
        buttons: {
            confirm: {
                text: '<i class="fa fa-check" aria-hidden="true"></i> Confirm',
                btnClass: 'btn-red',
                action: function () {
                    $.ajax({
                        url: url,
                        headers: {'X-CSRF-TOKEN': csrf_token},
                        type: 'get',
                        dataType: 'json',
                        success: function (resp) {
                            if (resp.status && resp.status === 200) {
                                // $('.page-content').prepend('<div class="alert alert-success mt-2"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'
                                //         + '<span>' + resp.msg + '</span></div>');
                                success_msg(resp.msg);

                                $('#slider-management').DataTable().ajax.reload();

                            } else {
                                // $('.page-content').prepend('<div class="alert alert-danger mt-2"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'
                                //         + '<span>' + resp.msg + '</span></div>');
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
function deleteStaff(obj) {
    var url = $(obj).data('href');
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.confirm({
        title: 'Delete Staff',
        content: 'Are you sure to delete this Staff?',
        type: 'red',
        typeAnimated: true,
        buttons: {
            confirm: {
                text: '<i class="fa fa-check" aria-hidden="true"></i> Confirm',
                btnClass: 'btn-red',
                action: function () {
                    $.ajax({
                        url: url,
                        headers: {'X-CSRF-TOKEN': csrf_token},
                        type: 'get',
                        dataType: 'json',
                        success: function (resp) {
                            if (resp.status && resp.status === 200) {
                                // $('.page-content').prepend('<div class="alert alert-success mt-2"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'
                                //         + '<span>' + resp.msg + '</span></div>');
                                success_msg(resp.msg);

                                $('#staff-management').DataTable().ajax.reload();

                            } else {
                                // $('.page-content').prepend('<div class="alert alert-danger mt-2"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'
                                //         + '<span>' + resp.msg + '</span></div>');
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
function changeStatus(obj) {
    
    // var link = $(obj).find(':selected').attr('data-href');
    // var status = $(obj).val();
    // var csrf_token = $('meta[name="csrf-token"]').attr('content'); 
    
    var link = $(obj).attr('data-href');
    // Get the status based on whether the checkbox is checked or not
    var status = $(obj).is(':checked') ? 1 : 0;
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: link,
        headers: {'X-CSRF-TOKEN': csrf_token},
        type: 'get',
        dataType: 'json',
        data: {status:status},
        success: function (resp) {
            $('#user-management').DataTable().ajax.reload();
            success_msg('status change successfully.');
        }
    });
}
function changeStatusStaff(obj) {
    
    // var link = $(obj).find(':selected').attr('data-href');
    // var status = $(obj).val();
    // var csrf_token = $('meta[name="csrf-token"]').attr('content'); 
    
    var link = $(obj).attr('data-href');
    // Get the status based on whether the checkbox is checked or not
    var status = $(obj).is(':checked') ? 1 : 0;
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: link,
        headers: {'X-CSRF-TOKEN': csrf_token},
        type: 'get',
        dataType: 'json',
        data: {status:status},
        success: function (resp) {
            $('#staff-management').DataTable().ajax.reload();
            success_msg('status change successfully.');
        }
    });
}


function deleteInquiry(obj) {
    var url = $(obj).data('href');
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.confirm({
        title: 'Delete Inquiry',
        content: 'Are you sure to delete this Inquiry?',
        type: 'red',
        typeAnimated: true,
        buttons: {
            confirm: {
                text: '<i class="fa fa-check" aria-hidden="true"></i> Confirm',
                btnClass: 'btn-red',
                action: function () {
                    $.ajax({
                        url: url,
                        headers: {'X-CSRF-TOKEN': csrf_token},
                        type: 'get',
                        dataType: 'json',
                        success: function (resp) {
                            if (resp.status && resp.status === 200) {
                                // $('.page-content').prepend('<div class="alert alert-success mt-2"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'
                                //         + '<span>' + resp.msg + '</span></div>');
                                success_msg(resp.msg);

                                $('#inquiry-management').DataTable().ajax.reload();

                            } else {
                                // $('.page-content').prepend('<div class="alert alert-danger mt-2"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'
                                //         + '<span>' + resp.msg + '</span></div>');
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

function sendLink(obj) {
    var url = $(obj).data('href');
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.confirm({
        title: 'Send Service Inquiry Link',
        content: 'Are you sure to send the Link ?',
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
                        headers: {'X-CSRF-TOKEN': csrf_token},
                        type: 'get',
                        dataType: 'json',
                        success: function (resp) {
                            ajaxindicatorstop();
                            if (resp.status && resp.status === 200) {
                                success_msg(resp.msg);

                                $('#inquiry-management').DataTable().ajax.reload();

                            } else {
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

function acceptBasicServiceInquiry(obj){
    var url = $(obj).data('href');
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.confirm({
        title: 'Accept',
        content: 'Are you sure to accept of Basic Service Inquiry?',
        type: 'green',
        typeAnimated: true,
        buttons: {
            confirm: {
                text: '<i class="fa fa-check" aria-hidden="true"></i> Confirm',
                btnClass: 'btn-success',
                action: function () {
                    ajaxindicatorstart();
                    $.ajax({
                        url: url,
                        headers: {'X-CSRF-TOKEN': csrf_token},
                        type: 'get',
                        dataType: 'json',
                        success: function (resp) {
                            ajaxindicatorstop();
                            if (resp.status && resp.status === 200) {
                                success_msg(resp.msg);

                                $('#basic-service-inquiry-management').DataTable().ajax.reload();

                            } else {
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

function rejectBasicServiceInquiry(obj){
    var url = $(obj).data('href');
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.confirm({
        title: 'Reject',
        content: 'Are you sure to reject of Basic Service Inquiry?',
        type: 'red',
        typeAnimated: true,
        buttons: {
            confirm: {
                text: '<i class="fa fa-check" aria-hidden="true"></i> Confirm',
                btnClass: 'btn-danger',
                action: function () {
                    ajaxindicatorstart();
                    $.ajax({
                        url: url,
                        headers: {'X-CSRF-TOKEN': csrf_token},
                        type: 'get',
                        dataType: 'json',
                        success: function (resp) {
                            ajaxindicatorstop();
                            if (resp.status && resp.status === 200) {
                                success_msg(resp.msg);

                                $('#basic-service-inquiry-management').DataTable().ajax.reload();

                            } else {
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

function acceptDetailedServiceRequirement(obj){
    var url = $(obj).data('href');
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.confirm({
        title: 'Accept',
        content: 'Are you sure to accept of Detailed Service Requirement?',
        type: 'green',
        typeAnimated: true,
        buttons: {
            confirm: {
                text: '<i class="fa fa-check" aria-hidden="true"></i> Confirm',
                btnClass: 'btn-success',
                action: function () {
                    ajaxindicatorstart();
                    $.ajax({
                        url: url,
                        headers: {'X-CSRF-TOKEN': csrf_token},
                        type: 'get',
                        dataType: 'json',
                        success: function (resp) {
                            ajaxindicatorstop();
                            if (resp.status && resp.status === 200) {
                                success_msg(resp.msg);

                                $('#detailed-service-requirement-management').DataTable().ajax.reload();

                            } else {
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

function rejectDetailedServiceRequirement(obj){
    var url = $(obj).data('href');
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.confirm({
        title: 'Reject',
        content: 'Are you sure to reject of Detailed Service Requirement?',
        type: 'red',
        typeAnimated: true,
        buttons: {
            confirm: {
                text: '<i class="fa fa-check" aria-hidden="true"></i> Confirm',
                btnClass: 'btn-danger',
                action: function () {
                    ajaxindicatorstart();
                    $.ajax({
                        url: url,
                        headers: {'X-CSRF-TOKEN': csrf_token},
                        type: 'get',
                        dataType: 'json',
                        success: function (resp) {
                            ajaxindicatorstop();
                            if (resp.status && resp.status === 200) {
                                success_msg(resp.msg);

                                $('#detailed-service-requirement-management').DataTable().ajax.reload();

                            } else {
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

function sendTemplate(obj){

    var template = $('input[name="template[]"]:checked')
                    .map(function () {
                        return this.value;
                    }).get();

    // console.log('template :', template);
    if(template.length === 0){
        alert("Please choose atleast one template !");
        return;
    }

    var url = $(obj).data('href');
    var csrf_token = $('meta[name="csrf-token"]').attr('content');

    $.confirm({
        title: 'Service Template',
        content: 'Are you sure to send of this Service Template?',
        type: 'success',
        typeAnimated: true,
        buttons: {
            confirm: {
                text: '<i class="fa fa-check" aria-hidden="true"></i> Confirm',
                btnClass: 'btn-primary',
                action: function () {
                    ajaxindicatorstart();
                    $.ajax({
                        url: url,
                        headers: {'X-CSRF-TOKEN': csrf_token},
                        type: 'get',
                        data: {template},
                        dataType: 'json',
                        success: function (resp) {
                            ajaxindicatorstop();
                            if (resp.status && resp.status === 200) {
                                success_msg(resp.msg);
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1200);
                            } else {
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






