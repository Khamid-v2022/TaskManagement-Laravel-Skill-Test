$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    window.showToast = function (message, type) {
        type = type || 'success';
    
        var $toast = $('#appToast');
        var $body = $('#appToastBody');
    
        $toast.removeClass('text-bg-success text-bg-danger text-bg-warning text-bg-primary');
    
        if (type === 'success') {
            $toast.addClass('text-bg-success');
        } else if (type === 'error') {
            $toast.addClass('text-bg-danger');
        } else if (type === 'warning') {
            $toast.addClass('text-bg-warning');
        } else {
            $toast.addClass('text-bg-primary');
        }
    
        $body.text(message);
    
        var toast = bootstrap.Toast.getOrCreateInstance($toast[0], {
            delay: 3000
        });
        toast.show();
    };
});