@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addTaskModal">Add Task</button>

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover mx-auto" style="width: 80%;">
            <thead class="thead-dark">
                <tr>
                    <th>Sr.No</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="taskTableBody">
            </tbody>
        </table>
    </div>
</div>

<!-- add task modal -->
<div class="modal fade" id="addTaskModal" tabindex="-1" role="dialog" aria-labelledby="addTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTaskModalLabel">Add Task</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addTaskForm">
                    @csrf
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" name="title" id="title" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" name="description" id="description" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="startDate">Start Date</label>
                        <input type="date" class="form-control" name="startDate" id="startDate" required>
                    </div>
                    <div class="form-group">
                        <label for="dueDate">Due Date</label>
                        <input type="date" class="form-control" name="dueDate" id="dueDate" required>
                    </div>
                    <div class="form-group">
                        <label for="status_id">Status</label>
                        <select class="form-control" name="status_id" id="status_id">
                            @foreach($mst_status as $status)
                                <option value="{{ $status->id }}">{{ $status->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Task</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- edit task modal -->
<div class="modal fade" id="editTaskModal" tabindex="-1" role="dialog" aria-labelledby="editTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTaskModalLabel">Edit Task</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editTaskForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editTaskId">
                    <div class="form-group">
                        <label for="editTitle">Title</label>
                        <input type="text" class="form-control" name="title" id="editTitle" required>
                    </div>
                    <div class="form-group">
                        <label for="editDescription">Description</label>
                        <textarea class="form-control" name="description" id="editDescription" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="editStartDate">Start Date</label>
                        <input type="date" class="form-control" name="startDate" id="editStartDate" required>
                    </div>
                    <div class="form-group">
                        <label for="editDueDate">Due Date</label>
                        <input type="date" class="form-control" name="dueDate" id="editDueDate" required>
                    </div>
                    <div class="form-group">
                        <label for="editStatusId">Status</label>
                        <select class="form-control" name="status_id" id="editStatusId">
                            @foreach($mst_status as $status)
                                <option value="{{ $status->id }}">{{ $status->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
     $(document).ready(function() 
     {
            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        loadTasks();

        function loadTasks() {
            $.ajax({
                url: "{{ route('task.index') }}",
                method: 'GET',
                dataType: 'json',
                success: function(response) 
                {
                        var taskTableBody = $('#taskTableBody');
                        taskTableBody.empty();

                    if (response && response.tasks && response.tasks.length > 0) {
                        $.each(response.tasks, function(index, task) {
                            var row = `
                                <tr>
                                    <td>${index + 1}</td> 
                                    <td>${task.title}</td> 
                                    <td>${task.status ? task.status.name : 'N/A'}</td> 
                                    <td>
                                        <button class="btn btn-warning edit-button" data-bs-toggle="modal" data-bs-target="#editTaskModal" 
                                                data-id="${task.id}" data-title="${task.title}"
                                                data-description="${task.description}" data-status="${task.status_id}"
                                                data-start-date="${task.startDate}" data-due-date="${task.dueDate}">
                                            Edit
                                        </button>
                                        <button class="btn btn-danger" onclick="deleteTask(${task.id})">Delete</button>
                                    </td>
                                </tr>
                            `;
                            taskTableBody.append(row);
                        });
                    } else {
                        Swal.fire("No tasks found.");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error: ", xhr.responseText);
                    Swal.fire("Something went wrong. Please try again.");
                }
            });
        }

        //add modal
        $('#addTaskForm').on('submit', function(e) 
        {
        e.preventDefault();
        
        var formData = $(this).serialize(); 

        $.ajax({
            url: "{{ route('task.store') }}",
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) 
            {
                $('#addTaskModal').modal('hide');
                $('#addTaskForm')[0].reset();
                loadTasks();
                Swal.fire("Task added successfully!");
                window.location.reload();
            },
            error: function(response) 
            {
                Swal.fire("Something went wrong. Please try again.");
                window.location.reload();
            }
        });
    });

    //edit modal
    $('#taskTableBody').on('click', '.edit-button', function() 
    {
        var taskId = $(this).data('id');
        var taskTitle = $(this).data('title');
        var taskDescription = $(this).data('description');
        var taskStatus = $(this).data('status');
        var taskStartDate = $(this).data('startDate');
        var taskDueDate = $(this).data('dueDate');

        $('#editTaskId').val(taskId);
        $('#editTitle').val(taskTitle);
        $('#editDescription').val(taskDescription);
        $('#editStatusId').val(taskStatus);
        $('#editStartDate').val(taskStartDate);
        $('#editDueDate').val(taskDueDate);

        $('#editTaskForm').attr('action', `/task/${taskId}`);
    });

    $('#editTaskForm').on('submit', function(e) 
    {
        e.preventDefault();
        var taskId = $('#editTaskId').val();
        var formData = $(this).serialize();

        $.ajax({
            url: $(this).attr('action'), 
            method: 'PUT',
            data: formData,
            dataType: 'json',
            success: function(response) 
            {
                $('#editTaskModal').modal('hide');
                loadTasks();
                Swal.fire("Task updated successfully!");
                window.location.reload();
            },
            error: function(xhr, status, error) 
            {
                console.error("AJAX Error:", status, error, xhr.responseText);
                Swal.fire("An error occurred while updating the task. Please check the console for details.");
                window.location.reload();
            }
        });
    });

    function deleteTask(taskId) 
    {
        if (confirm('Are you sure you want to delete this task?')) 
        {
            $.ajax({
                url: `/task/${taskId}`,
                method: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) 
                {
                    loadTasks();
                    Swal.fire('Task deleted successfully');
                    window.location.reload();
                },
                error: function() 
                {
                    Swal.fire("Something went wrong. Please try again.");
                    window.location.reload();
                }
            });
        }
    } 

});

   
</script>

@endsection
