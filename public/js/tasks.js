$(function () {
    $('#taskForm').on('submit', function (e) {
        e.preventDefault();

        var $form = $(this);
        var url = $form.data('store-url');
        var $submitBtn = $form.find('#taskFormSubmit');

        console.log(url);

        $submitBtn.prop('disabled', true);

        $.ajax({
            url: url,
            method: 'POST',
            data: $form.serialize(),
            success: function (res) {
                var task = res.task;
                var created = task.created_at ? task.created_at.replace('T', ' ').substring(0, 16) : '';

                // remove empty row if present
                $('#tasksTableBody tr td[colspan]').closest('tr').remove();

                $('#tasksTableBody').append(
                    '<tr data-id="' + task.id + '">' +
                        '<td>' + task.name + '</td>' +
                        '<td>' + created + '</td>' +
                        '<td></td>' +
                    '</tr>'
                );

                $form[0].reset();
                $submitBtn.prop('disabled', false);
                $('#taskModal').modal('hide');
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    var msg = errors.name ? errors.name[0] : 'Validation failed';
                    console.log(msg);
                } else {
                    
                }
            },
            complete: function () {
                $submitBtn.prop('disabled', false);
            }
        });
    });
});