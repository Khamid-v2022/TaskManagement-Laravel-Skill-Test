@extends('layouts.app')

@section('title', 'Projects')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Projects</h1>
        <button class="btn btn-primary" id="btnAddProject">Add Project</button>
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
                    <tbody id="tableBody">
                        @forelse ($projects as $project)
                            <tr data-id="{{ $project->id }}">
                                <td class="project-name">{{ $project->name }}</td>
                                <td>{{ $project->created_at->format('Y-m-d H:i') }}</td>
                                <td class="text-end text-nowrap">
                                    <button type="button"
                                        class="btn btn-sm btn-outline-secondary btn-edit"
                                        data-id="{{ $project->id }}"
                                        data-name="{{ $project->name }}"
                                        data-description="{{ $project->description }}">
                                        Edit
                                    </button>
                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger btn-delete"
                                            data-id="{{ $project->id }}">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    No projects yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>



    <div class="modal fade" id="projectModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="projectForm" method="POST" class="modal-content" data-store-url="{{ route('projects.store') }}" data-update-url="{{ url('/projects') }}">
                @csrf
                <input type="hidden" name="project_id" id="project_id" value="">
                <div class="modal-header">
                    <h5 class="modal-title" id="projectModalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" id="name"
                            class="form-control"
                            value="{{ old('name') }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="projectFormSubmit">Save</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/projects.js') }}"></script>
@endpush
