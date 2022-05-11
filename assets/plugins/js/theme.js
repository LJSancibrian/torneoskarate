(function($) {
    "use strict"; // Start of use strict

    // Toggle the side navigation
    $("#sidebarToggle, #sidebarToggleTop").on('click', function(e) {
        $("body").toggleClass("sidebar-toggled");
        $(".sidebar").toggleClass("toggled");
        if ($(".sidebar").hasClass("toggled")) {
            $('.sidebar .collapse').collapse('hide');
        };
    });

    // Close any open menu accordions when window is resized below 768px
    $(window).resize(function() {
        if ($(window).width() < 768) {
            $('.sidebar .collapse').collapse('hide');
        };
    });

    // Prevent the content wrapper from scrolling when the fixed side navigation hovered over
    $('body.fixed-nav .sidebar').on('mousewheel DOMMouseScroll wheel', function(e) {
        if ($(window).width() > 768) {
            var e0 = e.originalEvent,
                delta = e0.wheelDelta || -e0.detail;
            this.scrollTop += (delta < 0 ? 1 : -1) * 30;
            e.preventDefault();
        }
    });

    // Scroll to top button appear
    $(document).on('scroll', function() {
        var scrollDistance = $(this).scrollTop();
        if (scrollDistance > 100) {
            $('.scroll-to-top').fadeIn();
        } else {
            $('.scroll-to-top').fadeOut();
        }
    });

    // Smooth scrolling using jQuery easing
    $(document).on('click', 'a.scroll-to-top', function(e) {
        var $anchor = $(this);
        $('html, body').stop().animate({
            scrollTop: ($($anchor.attr('href')).offset().top)
        }, 1000, 'easeInOutExpo');
        e.preventDefault();
    });

    $('[data-toggle="tooltip"]').tooltip()

})(jQuery); // End of use strict


jQuery.fn.dataTable.Api.register('sum()', function() {
    return this.flatten().reduce(function(a, b) {
        if (typeof a === 'string') {
            a = a.replace(/[^\d.-]/g, '') * 1;
        }
        if (typeof b === 'string') {
            b = b.replace(/[^\d.-]/g, '') * 1;
        }

        return a + b;
    }, 0);
});

$(window).resize(function(e) {
    if ($(window).width() <= 768) {
        $('#sidebarToggleTop').click()
    }
});

$(document).ready(function() {
    if (localStorage.getItem("adminEspacioLeon") != null) {
        checktoken(localStorage.getItem("adminEspacioLeon"))
    }
    $(window).resize();
})

function logout() {
    localStorage.removeItem("adminEspacioLeon")
    window.location.href = base_url + 'cerrarsesion';
}

function isInteger(num) {
    return (num ^ 0) === num;
}

function getFormData($form) {
    var unindexed_array = $form.serializeArray();
    var indexed_array = {};
    $.map(unindexed_array, function(n, i) {
        if (isInteger(n['value'])) { val = parseIn(n['value']) } else { val = n['value'] };
        indexed_array[n['name']] = val;
    });
    return indexed_array;
}

function checktoken(token) {
    var settings = {
        "url": api_url + "WS/Gestion/checkToken",
        "method": "POST",
        "timeout": 0,
        "headers": {
            "Content-Type": "application/json"
        },
        "data": JSON.stringify({
            "token": token
        }),
    };

    $.ajax(settings).done(function(response) {
        if (response.error > 0) {
            logout()
        }
    }).fail(function(response) {
        logout()
    });
}

const swal = Swal.mixin({
    customClass: {
        confirmButton: 'btn btn-primary m-2',
        cancelButton: 'btn btn-secondary m-2',
        denyButton: 'btn btn-danger m-2'
    },
    buttonsStyling: false,
    showClass: {
        popup: 'animated flipInX'
    },
    hideClass: {
        popup: 'animated flipOutX'
    }
})

function showmsg(msg) {
    swal.fire({
            icon: 'info',
            title: 'ATENCIÓN',
            html: msg
        })
        /*var modal = '<div id="modalTemp" class="modal fade" tabindex="-1" role="dialog">';
        modal += '<div class="modal-dialog modal-dialog-centered" role="document">';
        modal += '<div class="modal-content">';
        modal += '<div class="modal-body">';
        modal += '<p style="font-size: 20px;text-align:center">' + msg + '</p>';
        modal += '</div></div></div></div>';
        $("body").append(modal);

        $('#modalTemp').modal('show')
        setTimeout(function() {
            $('#modalTemp').modal('hide')
        }, 3000);*/


}



$(document).on('hidden.bs.modal', '#modalTemp', function(e) {
    $(this).remove()
})

$(document).ajaxStart(function() {
    $('body').addClass('wait');

}).ajaxComplete(function() {

    $('body').removeClass('wait');

});


if ($('.datatable').lenght > 0) {
    $('.datatable').DataTable();
}

$(document).on('click', '[role="tab"]', function() {
    if ($('.dataTable').length > 0) {
        setTimeout(function() {
            $(".dataTable").DataTable().columns.adjust();
        }, 300)
    }
});


//localStorage.removeItem("adminEspacioLeon");