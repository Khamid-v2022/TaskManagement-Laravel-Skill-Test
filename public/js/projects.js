$(function () {

    $('#btnAddProject').on('click', function () {
        resetForm();
        $("#projectModal").modal("show");
    });

    // Edit button click
    $("#tableBody").on('click', '.btn-edit', function () {
        resetForm();

        var $btn = $(this);

        $('#project_id').val($btn.data('id'));
        $('#name').val($btn.data('name'));

        $('#projectModalTitle').text('Edit Task');

        $("#projectModal").modal("show");
    });


    // Form submit - Add or Update task
    $('#projectForm').on('submit', function (e) {
        e.preventDefault();

        var $form = $(this);

        var id = $('#project_id').val();
        var isEdit = id ? true : false;
        var url = isEdit
            ? $form.data('update-url') + '/' + id
            : $form.data('store-url');

        var method = isEdit ? 'PUT' : 'POST';

       
       var $submitBtn = $form.find('#projectFormSubmit');

        $submitBtn.prop('disabled', true);

        $.ajax({
            url: url,
            method: method,
            data: $form.serialize(),
            success: function (res) {
                var project = res.project;

                if (isEdit) {
                    var $row = $('#tableBody tr[data-id="' + project.id + '"]');
                    $row.replaceWith(rowHtml(project));
                } else {
                    $('#tableBody tr td[colspan]').closest('tr').remove();
                    $('#tableBody').append(rowHtml(project));
                }


                resetForm();
                $submitBtn.prop('disabled', false);
                $('#projectModal').modal('toggle');

                showToast(res.message || 'Project created successfully', 'success');
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
    $("#tableBody").on('click', '.btn-delete', function () {
        var id = $(this).data('id');
        var url = $('#projectForm').data('update-url') + '/' + id;

        Swal.fire({
            title: 'Are you sure?',
            text: 'This project will be deleted.',
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
                    $('#tableBody tr[data-id="' + id + '"]').remove();
                    if ($('#tableBody tr').length === 0) {
                        $('#tableBody').append(
                            '<tr><td colspan="3" class="text-center text-muted py-4">No projects yet.</td></tr>'
                        );
                    }
                    showToast(res.message || 'Project deleted successfully', 'success');
                },
                error: function () {
                    showToast('Something went wrong', 'error');
                }
            });
        });
    });
});

function resetForm() {
    var $form = $('#projectForm');
    $form[0].reset();

    $('#project_id').val('');
    $('#projectModalTitle').text('Add Task');

    $form.find('.is-invalid').removeClass('is-invalid');
}

function rowHtml(project) {
    var created = project.created_at
        ? project.created_at.replace('T', ' ').substring(0, 16)
        : '';
    var desc = project.description || '';
    return '<tr data-id="' + project.id + '">' +
        '<td class="project-name">' + $('<div>').text(project.name).html() + '</td>' +
        '<td>' + created + '</td>' +
        '<td class="text-end text-nowrap">' +
            '<button type="button" class="btn btn-sm btn-outline-secondary btn-edit" ' +
                'data-id="' + project.id + '" ' +
                'data-name="' + $('<div>').text(project.name).html() + '" ' +
                '>Edit</button> ' +
            '<button type="button" class="btn btn-sm btn-outline-danger btn-delete" ' +
                'data-id="' + project.id + '">Delete</button>' +
        '</td>' +
    '</tr>';
}