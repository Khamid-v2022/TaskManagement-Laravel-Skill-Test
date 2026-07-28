@extends('layouts.app')

@section('title', 'Tasks')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Tasks</h1>
        <button class="btn btn-primary" id="btnAddTask">Add Task</button>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th class="text-nowrap">Created</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="tasksTableBody">
                        @forelse ($tasks as $task)
                            <tr data-id="{{ $task->id }}">
                                <td>{{ $task->name }}</td>
                                <td>{{ $task->created_at->format('Y-m-d H:i') }}</td>
                                <td class="text-end text-nowrap">
                                    <button type="button"
                                        class="btn btn-sm btn-outline-secondary btn-edit"
                                        data-id="{{ $task->id }}"
                                        data-name="{{ $task->name }}"
                                        data-description="{{ $task->description }}">
                                        Edit
                                    </button>
                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger btn-delete"
                                            data-id="{{ $task->id }}">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">
                                    No tasks yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>



    <div class="modal fade" id="taskModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="taskForm" method="POST" class="modal-content" data-store-url="{{ route('tasks.store') }}" data-update-url="{{ url('/tasks') }}">
                @csrf
                <input type="hidden" name="task_id" id="task_id" value="">
                <div class="modal-header">
                    <h5 class="modal-title" id="taskModalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" id="name"
                            class="form-control"
                            value="{{ old('name') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="taskFormSubmit">Save</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/tasks.js') }}"></script>
@endpush
