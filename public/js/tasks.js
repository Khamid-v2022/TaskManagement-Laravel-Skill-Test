$(function () {

    $('#btnAddTask').on('click', function () {
        resetForm();
        $("#taskModal").modal("show");
    });

    // Edit button click
    $("#tasksTableBody").on('click', '.btn-edit', function () {
        resetForm();

        var $btn = $(this);

        $('#task_id').val($btn.data('id'));
        $('#name').val($btn.data('name'));
        $('#project_id').val($btn.data('project-id') || '');
        $('#description').val($btn.data('description') || '');

        $('#taskModalTitle').text('Edit Task');

        $("#taskModal").modal("show");
    });


    // Form submit - Add or Update task
    $('#taskForm').on('submit', function (e) {
        e.preventDefault();

        var $form = $(this);

        var id = $('#task_id').val();
        var isEdit = id ? true : false;
        var url = isEdit
            ? $form.data('update-url') + '/' + id
            : $form.data('store-url');

        var method = isEdit ? 'PUT' : 'POST';

       
       var $submitBtn = $form.find('#taskFormSubmit');

        console.log(url);

        $submitBtn.prop('disabled', true);

        $.ajax({
            url: url,
            method: method,
            data: $form.serialize(),
            success: function (res) {
                var task = res.task;

                if (isEdit) {
                    var $row = $('#tasksTableBody tr[data-id="' + task.id + '"]');
                    $row.replaceWith(rowHtml(task));

                    resetForm();
                    $submitBtn.prop('disabled', false);
                    $('#taskModal').modal('toggle');
    
                    showToast(res.message || 'Task created successfully', 'success');
                } else {
                    // $('#tasksTableBody tr td[colspan]').closest('tr').remove();
                    // $('#tasksTableBody').append(rowHtml(task));
                    location.reload()
                }


               
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    var msg = errors.name ? errors.name[0] : 'Validation failed';
                    // console.log(msg);
                    showToast(msg, 'error');
                } else {
                    showToast('Something went wrong', 'error');
                }
            },
            complete: function () {
                $submitBtn.prop('disabled', false);
            }
        });
    });


    // Delete button click
    $("#tasksTableBody").on('click', '.btn-delete', function () {
        var id = $(this).data('id');
        var url = $('#taskForm').data('update-url') + '/' + id;

        Swal.fire({
            title: 'Are you sure?',
            text: 'This task will be deleted.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then(function (result) {
            if (!result.isConfirmed) return;

            $.ajax({
                url: url,
                method: 'DELETE',
                data: id,
                success: function (res) {
                    $('#tasksTableBody tr[data-id="' + id + '"]').remove();
                    if ($('#tasksTableBody tr').length === 0) {
                        $('#tasksTableBody').append(
                            '<tr><td colspan="4" class="text-center text-muted py-4">No tasks yet.</td></tr>'
                        );
                    }
                    showToast(res.message || 'Task deleted successfully', 'success');
                },
                error: function () {
                    showToast('Something went wrong', 'error');
                }
            });
        });
    });

    $('#tasksTableBody').sortable({
        handle: '.drag-handle',
        items: 'tr[data-id]',
        axis: 'y',
        update: function () {
            var order = [];
            $('#tasksTableBody tr[data-id]').each(function () {
                order.push($(this).data('id'));
            });
            $.ajax({
                url: '/tasks/reorder', // or table data attr
                method: 'POST',
                data: {
                    order: order,
                },
                success: function (res) {
                },
                error: function () {
                }
            });
        }
    });
});

function resetForm() {
    var $form = $('#taskForm');
    $form[0].reset();

    $('#task_id').val('');
    $('#taskModalTitle').text('Add Task');

    $form.find('.is-invalid').removeClass('is-invalid');
}

function rowHtml(task) {
    var created = task.created_at
        ? task.created_at.replace('T', ' ').substring(0, 16)
        : '';

    console.log(task);
    var projectName = (task.project && task.project.name) ? task.project.name : 'Unassigned';
    var projectId = task.project_id || '';

    var desc = task.description || '';

    return '<tr data-id="' + task.id + '">' +
        '<td class="drag-handle text-muted cursor-pointer">&#9776;</td>' +
        '<td class="task-name">' + $('<div>').text(task.name).html() + '</td>' +
        '<td>' + projectName + '</td>' +
        '<td>' + created + '</td>' +
        '<td class="text-end text-nowrap">' +
            '<button type="button" class="btn btn-sm btn-outline-secondary btn-edit" ' +
                'data-id="' + task.id + '" ' +
                'data-name="' + $('<div>').text(task.name).html() + '" ' +
                'data-project-id="' + projectId + '" ' +
                'data-description="' + $('<div>').text(desc).html() + '">Edit</button> ' +
            '<button type="button" class="btn btn-sm btn-outline-danger btn-delete" ' +
                'data-id="' + task.id + '">Delete</button>' +
        '</td>' +
    '</tr>';
}