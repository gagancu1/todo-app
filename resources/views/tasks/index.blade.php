<!DOCTYPE html>
<html>
<head>
    <title>PHP - Simple To Do List App</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <style>
  
      .blue-text {
            color: #4682B4; 
        }
        .center-content {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px; 
            margin-top: 20px; 
        }
        .form-group {
            width: 50%; 
            margin: 0 auto;
        }
        .center-content .form-control {
            flex: 5; 
            margin-right: 10px;
            height: 38px; 
        }
        .center-content .btn {
            flex: ; 
            height: 38px; 
        }
        .btn-custom {
            background-color: #4682B4; 
            color: white; 
            border: none; 
        }
        .mt-4 {
            margin-top: 1.5rem; 
        }
        
</style>

</head>
<body>
    <div class="container">
        <h1 class="blue-text mt-4">PHP - Simple To Do List App</h1> 
        <div class="form-group">
            <div class="center-content">
                <input type="text" id="taskInput" class="form-control" placeholder="Enter Task">
                <button id="addTaskBtn" class="btn btn-custom">Add Task</button>
            </div>
        </div>
    <div class="text-center mt-4">
        <button id="showAllBtn" class="btn btn-secondary mb-2">Show All Tasks</button>
    </div>
    <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Task</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="taskList">
            </tbody>
        </table>
    </div>
    <script>
        $(document).ready(function() {
            function fetchTasks(showAll = false) {
                $.ajax({
                    url: '/tasks',
                    method: 'GET',
                    success: function(response) {
                        let tasksHtml = '';
                        response.forEach(function(task, index) {
                            if (showAll || !task.completed) {
                                tasksHtml += `<tr>
                                    <td>${index + 1}</td>
                                    <td class="${task.completed ? 'task-done' : ''}">${task.task}</td>
                                    <td>${task.completed ? 'Done' : 'Pending'}</td>
                                    <td>
                                        <button class="btn btn-success btn-sm completeBtn" data-id="${task.id}" ${task.completed ? 'style="display:none;"' : ''}>✓</button>
                                        <button class="btn btn-danger btn-sm deleteBtn" data-id="${task.id}">✗</button>
                                    </td>
                                </tr>`;
                            }
                        });
                        $('#taskList').html(tasksHtml);
                    },
                    error: function(error) {
                        console.error("Error fetching tasks", error);
                    }
                });
            }

            fetchTasks();

            $('#addTaskBtn').click(function() {
                const taskInput = $('#taskInput').val();
                if (taskInput.trim() !== '') {
                    $.ajax({
                        url: '/tasks',
                        method: 'POST',
                        data: {
                            task: taskInput,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            fetchTasks();
                            $('#taskInput').val('');
                        },
                        error: function(response) {
                            alert('Task already exists or is invalid.');
                        }
                    });
                }
            });

            $(document).on('click', '.completeBtn', function() {
                const taskId = $(this).data('id');
                $.ajax({
                    url: `/tasks/${taskId}`,
                    method: 'PUT',
                    data: {
                        completed: true,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        fetchTasks();
                    },
                    error: function(error) {
                        console.error("Error updating task", error);
                    }
                });
            });

            $(document).on('click', '.deleteBtn', function() {
                const taskId = $(this).data('id');
                if (confirm('Are you sure to delete this task?')) {
                    $.ajax({
                        url: `/tasks/${taskId}`,
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            fetchTasks();
                        },
                        error: function(error) {
                            console.error("Error deleting task", error);
                        }
                    });
                }
            });

            $('#showAllBtn').click(function() {
                fetchTasks(true);
            });
        });
    </script>
</body>
</html>
        